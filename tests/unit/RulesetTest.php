<?php

/**
 * This file is part of Laucov's Validation Library project.
 * 
 * Copyright 2024 Laucov Serviços de Tecnologia da Informação Ltda.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @package validation
 * 
 * @author Rafael Covaleski Pereira <rafael.covaleski@laucov.com>
 * 
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * 
 * @copyright © 2024 Laucov Serviços de Tecnologia da Informação Ltda.
 */

declare(strict_types=1);

namespace Tests\Unit;

use Laucov\Validation\AbstractRule;
use Laucov\Validation\Interfaces\RuleInterface;
use Laucov\Validation\Rules\GreaterThan;
use Laucov\Validation\Rules\Length;
use Laucov\Validation\Rules\Regex;
use Laucov\Validation\Rules\RequiredWith;
use Laucov\Validation\Ruleset;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Ruleset
 */
class RulesetTest extends TestCase
{
    /**
     * Ruleset instance.
     */
    protected Ruleset $ruleset;

    /**
     * @covers ::addRule
     * @covers ::getErrors
     * @covers ::validate
     * @uses Laucov\Validation\AbstractRule::setData
     * @uses Laucov\Validation\Error::__construct
     * @uses Laucov\Validation\Error::createFromRule
     * @uses Laucov\Validation\Rules\Length::__construct
     * @uses Laucov\Validation\Rules\Length::getInfo
     * @uses Laucov\Validation\Rules\Length::validate
     * @uses Laucov\Validation\Rules\Regex::__construct
     * @uses Laucov\Validation\Rules\Regex::getInfo
     * @uses Laucov\Validation\Rules\Regex::validate
     * @uses Laucov\Validation\Ruleset::isEmpty
     */
    public function testCanAddRulesAndValidate(): void
    {
        // Add rules.
        $this->ruleset->addRule(new Length(4), new Regex('/^foo/'));

        // Violate rule #1.
        $this->assertFalse($this->ruleset->validate('foo'));
        $errors = $this->ruleset->getErrors();
        $this->assertIsArray($errors);
        $this->assertCount(1, $errors);
        $this->assertIsObject($errors[0]);
        $this->assertSame(Length::class, $errors[0]->rule);
        $this->assertSame('4', $errors[0]->parameters['min']);
        $this->assertSame('', $errors[0]->parameters['max']);

        // Violate rule #2.
        $this->assertFalse($this->ruleset->validate('barfoo'));
        $errors = $this->ruleset->getErrors();
        $this->assertIsArray($errors);
        $this->assertCount(1, $errors);
        $this->assertIsObject($errors[0]);
        $this->assertSame(Regex::class, $errors[0]->rule);
        $this->assertSame('/^foo/', $errors[0]->parameters['pattern']);

        // Violate both rules.
        $this->assertFalse($this->ruleset->validate('bar'));
        $errors = $this->ruleset->getErrors();
        $this->assertIsArray($errors);
        $this->assertCount(2, $errors);
        $this->assertIsObject($errors[0]);
        $this->assertIsObject($errors[1]);
        $this->assertSame(Length::class, $errors[0]->rule);
        $this->assertSame('4', $errors[0]->parameters['min']);
        $this->assertSame('', $errors[0]->parameters['max']);
        $this->assertSame(Regex::class, $errors[1]->rule);
        $this->assertSame('/^foo/', $errors[1]->parameters['pattern']);

        // Test valid value.
        $this->assertTrue($this->ruleset->validate('foobar'));
    }

    /**
     * @covers ::setData
     * @uses Laucov\Validation\AbstractRule::setData
     * @uses Laucov\Validation\AbstractRule::getData
     * @uses Laucov\Validation\Error::__construct
     * @uses Laucov\Validation\Error::createFromRule
     * @uses Laucov\Validation\Rules\Regex::__construct
     * @uses Laucov\Validation\Rules\Regex::validate
     * @uses Laucov\Validation\Rules\RequiredWith::__construct
     * @uses Laucov\Validation\Rules\RequiredWith::getInfo
     * @uses Laucov\Validation\Rules\RequiredWith::validate
     * @uses Laucov\Validation\Ruleset::addRule
     * @uses Laucov\Validation\Ruleset::getErrors
     * @uses Laucov\Validation\Ruleset::hasKey
     * @uses Laucov\Validation\Ruleset::isEmpty
     * @uses Laucov\Validation\Ruleset::isRequired
     * @uses Laucov\Validation\Ruleset::requireWith
     * @uses Laucov\Validation\Ruleset::validate
     */
    public function testCanSetData(): void
    {
        // Create data.
        $data = [
            'name' => 'Machado de Assis',
            'birth' => '1839-07-21',
        ];

        // Create custom rule.
        // The rule conditionally request a 2 uppercase letter value
        // to be passed if `country` is set in the given data.
        $rule = new class extends AbstractRule {
            public function getInfo(): array
            {
                return [];
            }
            public function validate(mixed $value): bool
            {
                if (!isset($this->getData()->country)) {
                    return true;
                } else {
                    return preg_match('/^[A-Z]{2}$/', $value) === 1;
                }
            }
        };

        // Set rule and data.
        $this->ruleset
            ->setData($data)
            ->addRule($rule);

        // Validate.
        $this->assertTrue($this->ruleset->validate('MN'));
        $this->assertTrue($this->ruleset->validate('A'));
        $this->assertTrue($this->ruleset->validate('001'));

        // Change data.
        $data['country'] = 'BRA';
        $this->ruleset->setData($data);

        // Validate again.
        $this->assertTrue($this->ruleset->validate('MN'));
        $this->assertFalse($this->ruleset->validate('A'));
        $this->assertFalse($this->ruleset->validate('001'));

        // Get errors.
        $errors = $this->ruleset->getErrors();
        $this->assertIsArray($errors);
        $this->assertCount(1, $errors);
        $this->assertIsObject($errors[0]);
        $this->assertCount(0, $errors[0]->parameters);
    }

    /**
     * @covers ::addRule
     * @covers ::hasKey
     * @covers ::isEmpty
     * @covers ::isRequired
     * @covers ::require
     * @covers ::requireWith
     * @covers ::validate
     * @uses Laucov\Validation\AbstractRule::setData
     * @uses Laucov\Validation\Error::__construct
     * @uses Laucov\Validation\Error::createFromRule
     * @uses Laucov\Validation\Rules\GreaterThan::__construct
     * @uses Laucov\Validation\Rules\GreaterThan::getInfo
     * @uses Laucov\Validation\Rules\GreaterThan::validate
     * @uses Laucov\Validation\Rules\Traits\ValueRuleTrait::formatValue
     * @uses Laucov\Validation\Ruleset::getErrors
     * @uses Laucov\Validation\Ruleset::setData
     */
    public function testHandlesOptionalValues(): void
    {
        // Test optional value.
        $this->ruleset->addRule(new GreaterThan(0));
        $this->assertTrue($this->ruleset->validate(1));
        $this->assertFalse($this->ruleset->validate(0));
        $this->assertTrue($this->ruleset->validate(null));
        $this->assertTrue($this->ruleset->validate(''));
        $this->assertTrue($this->ruleset->validate([]));

        // Test conditionally required value.
        $this->ruleset
            ->requireWith('foo', 'baz')
            ->setData(['bar' => 'abc']);
        $this->assertTrue($this->ruleset->validate(1));
        $this->assertFalse($this->ruleset->validate(0));
        $this->assertTrue($this->ruleset->validate(null));
        $this->assertTrue($this->ruleset->validate(''));
        $this->assertTrue($this->ruleset->validate([]));
        $this->ruleset->setData(['foo' => 'abc', 'baz' => 'def']);
        $this->assertTrue($this->ruleset->validate(1));
        $this->assertFalse($this->ruleset->validate(0));
        $this->assertFalse($this->ruleset->validate(null));
        $this->assertFalse($this->ruleset->validate(''));
        $this->assertFalse($this->ruleset->validate([]));
        $errors = $this->ruleset->getErrors();
        $this->assertCount(1, $errors);
        $this->assertIsObject($errors[0]);
        $this->assertSame('required_with', $errors[0]->rule);
        $this->assertCount(1, $errors[0]->parameters);
        $this->assertSame('foo, baz', $errors[0]->parameters['keys']);

        // Test required value.
        $this->ruleset->require();
        $this->assertTrue($this->ruleset->validate(1));
        $this->assertFalse($this->ruleset->validate(0));
        $this->assertFalse($this->ruleset->validate(null));
        $this->assertFalse($this->ruleset->validate(''));
        $this->assertFalse($this->ruleset->validate([]));
        $errors = $this->ruleset->getErrors();
        $this->assertCount(1, $errors);
        $this->assertIsObject($errors[0]);
        $this->assertSame('required', $errors[0]->rule);
        $this->assertCount(0, $errors[0]->parameters);

        // Remove requirements.
        $this->ruleset
            ->require(false)
            ->requireWith();
        $this->assertTrue($this->ruleset->validate(1));
        $this->assertFalse($this->ruleset->validate(0));
        $this->assertTrue($this->ruleset->validate(null));
        $this->assertTrue($this->ruleset->validate(''));
        $this->assertTrue($this->ruleset->validate([]));

        // Require with explicit bool value.
        $this->ruleset
            ->require(true)
            ->setData([]);
        $this->assertTrue($this->ruleset->validate(1));
        $this->assertFalse($this->ruleset->validate(0));
        $this->assertFalse($this->ruleset->validate(null));
        $this->assertFalse($this->ruleset->validate(''));
        $this->assertFalse($this->ruleset->validate([]));
    }

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        $this->ruleset = new Ruleset();
    }
}
