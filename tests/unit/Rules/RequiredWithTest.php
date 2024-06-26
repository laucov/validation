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

namespace Tests\Unit\Rules;

use Laucov\Validation\Rules\RequiredWith;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\RequiredWith
 */
class RequiredWithTest extends RuleTestCase
{
    /**
     * Provides arguments and expectations for validation tests.
     */
    public function dataProvider(): array
    {
        $data = [
            'name' => 'John',
            'age' => 42,
            'email' => 'john.doe@foomail.net',
        ];

        return [
            [
                ['name'],
                $data,
                ['keys' => 'name'],
                [0, 1, 2, 6, 8],
            ],
            [
                ['name', 'age'],
                $data,
                ['keys' => 'name, age'],
                [0, 1, 2, 6, 8],
            ],
            [
                ['address'],
                $data,
                ['keys' => 'address'],
                [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
            [
                ['email', 'gender'],
                $data,
                ['keys' => 'email, gender'],
                [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getData
     * @covers ::getInfo
     * @covers ::setData
     * @covers ::validate
     * @dataProvider dataProvider
     */
    public function testCanValidate(
        array $constructor_args,
        array $data,
        array $expected_info,
        array $expected_success
    ): void {
        $rule = new RequiredWith(...$constructor_args);
        $rule->setData($data);
        $this->assertRuleInfo($rule, $expected_info);
        $this->assertValidation($rule, $expected_success);
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(RequiredWith::class, true);
    }

    /**
     * Get values to validate.
     */
    protected function getValues(): array
    {
        return [
            0 => 'Some value',
            1 => 478541245,
            2 => ['foo', 'bar' => 'baz'],
            3 => null,
            4 => [],
            5 => '',
            6 => new \stdClass(),
            7 => 0,
            8 => true,
            9 => false,
        ];
    }
}
