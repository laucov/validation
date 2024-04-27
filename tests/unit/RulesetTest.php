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
    protected Ruleset $ruleset;

    /**
     * @covers ::addRule
     * @covers ::getErrors
     * @covers ::validate
     * @uses Laucov\Validation\AbstractRule::setData
     * @uses Laucov\Validation\Rules\Length::__construct
     * @uses Laucov\Validation\Rules\Length::validate
     * @uses Laucov\Validation\Rules\Regex::__construct
     * @uses Laucov\Validation\Rules\Regex::validate
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
        $this->assertInstanceOf(Length::class, $errors[0]);

        // Violate rule #2.
        $this->assertFalse($this->ruleset->validate('barfoo'));
        $errors = $this->ruleset->getErrors();
        $this->assertIsArray($errors);
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(Regex::class, $errors[0]);

        // Violate both rules.
        $this->assertFalse($this->ruleset->validate('bar'));
        $errors = $this->ruleset->getErrors();
        $this->assertIsArray($errors);
        $this->assertCount(2, $errors);
        $this->assertInstanceOf(Length::class, $errors[0]);
        $this->assertInstanceOf(Regex::class, $errors[1]);

        // Test valid value.
        $this->assertTrue($this->ruleset->validate('foobar'));
    }

    /**
     * @covers ::setData
     * @uses Laucov\Validation\AbstractRule::setData
     * @uses Laucov\Validation\AbstractRule::getData
     * @uses Laucov\Validation\Rules\Regex::__construct
     * @uses Laucov\Validation\Rules\Regex::validate
     * @uses Laucov\Validation\Rules\RequiredWith::__construct
     * @uses Laucov\Validation\Rules\RequiredWith::validate
     * @uses Laucov\Validation\Ruleset::addRule
     * @uses Laucov\Validation\Ruleset::getErrors
     * @uses Laucov\Validation\Ruleset::validate
     */
    public function testCanUseData(): void
    {
        // Create data.
        $data = [
            'name' => 'Machado de Assis',
            'birth' => '1839-07-21',
            'country' => 'BRA',
            'state' => 'MN',
        ];

        // Set initial rules.
        $this->ruleset
            ->setData($data)
            ->addRule(new Regex('/^[A-Za-z\s]*$/'))
            ->addRule(new RequiredWith('state', 'country'));
        
        // Validate.
        $this->assertTrue($this->ruleset->validate('Rio de Janeiro'));
        $this->assertFalse($this->ruleset->validate(''));

        // Get errors.
        $errors = $this->ruleset->getErrors();
        $this->assertIsArray($errors);
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(RequiredWith::class, $errors[0]);
    }

    protected function setUp(): void
    {
        $this->ruleset = new Ruleset();
    }
}
