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

use Laucov\Validation\Error;
use Laucov\Validation\Rules\GreaterThan;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Error
 */
class ErrorTest extends TestCase
{
    /**
     * @covers ::createFromRule
     * @uses Laucov\Validation\AbstractRule::getMessage
     * @uses Laucov\Validation\AbstractRule::setMessage
     * @uses Laucov\Validation\Error::__construct
     * @uses Laucov\Validation\Rules\GreaterThan::__construct
     * @uses Laucov\Validation\Rules\GreaterThan::getInfo
     * @uses Laucov\Validation\Rules\Traits\ValueRuleTrait::formatValue
     */
    public function testCanCreateFromRule(): void
    {
        // Create from rule.
        $rule = new GreaterThan(10);
        $error = Error::createFromRule($rule);
        $this->assertSame(GreaterThan::class, $error->rule);
        $this->assertCount(1, $error->parameters);
        $this->assertSame('10', $error->parameters['value']);
        $this->assertNull($error->message);

        // Add a message.
        $rule->setMessage('Should be greater than 10.');
        $error = Error::createFromRule($rule);
        $this->assertSame(GreaterThan::class, $error->rule);
        $this->assertCount(1, $error->parameters);
        $this->assertSame('10', $error->parameters['value']);
        $this->assertSame('Should be greater than 10.', $error->message);
    }

    /**
     * @covers ::__construct
     */
    public function testCanInstantiate(): void
    {
        // Create error without message.
        $error = new Error('length', ['min' => '8', 'max' => '16']);
        $this->assertSame('length', $error->rule);
        $this->assertIsArray($error->parameters);
        $this->assertCount(2, $error->parameters);
        $this->assertSame('8', $error->parameters['min'] ?? null);
        $this->assertSame('16', $error->parameters['max'] ?? null);
        $this->assertNull($error->message);

        // Create error with message.
        $error = new Error(
            'ends_with',
            ['value' => 'bar'],
            'The value should end with "bar".',
        );
        $this->assertSame('ends_with', $error->rule);
        $this->assertIsArray($error->parameters);
        $this->assertCount(1, $error->parameters);
        $this->assertSame('bar', $error->parameters['value'] ?? null);
        $this->assertSame('The value should end with "bar".', $error->message);
    }

    /**
     * @covers ::__construct
     */
    public function testMustUseStringArrayAsParameters(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Error('foobar', ['foo' => 'bar', 'baz' => 42]);
    }
}
