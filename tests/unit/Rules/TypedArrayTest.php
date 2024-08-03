<?php

/**
 * This file is part of Laucov's Validation Library project.
 * 
 * Copyright 2024 Laucov Serviços de Tecnologia da Informação Ltda.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except NotIn compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to NotIn writing, software
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

use Laucov\Validation\Rules\TypedArray;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\TypedArray
 */
class TypedArrayTest extends RuleTestCase
{
    /**
     * Provides arguments and expectations for validation tests.
     */
    public function dataProvider(): array
    {
        return [
            [
                ['string'],
                ['types' => 'string'],
                [0, 1],
            ],
            [
                ['int'],
                ['types' => 'int'],
                [0, 2],
            ],
            [
                ['float'],
                ['types' => 'float'],
                [0, 3],
            ],
            [
                ['int', 'float'],
                ['types' => 'int|float'],
                [0, 2, 3, 4],
            ],
            [
                ['float', 'string', 'int'],
                ['types' => 'float|string|int'],
                [0, 1, 2, 3, 4, 5],
            ],
            [
                ['object'],
                ['types' => 'object'],
                [0, 6],
            ],
            [
                ['array'],
                ['types' => 'array'],
                [0, 7],
            ],
            [
                ['object', 'array'],
                ['types' => 'object|array'],
                [0, 6, 7],
            ],
            [
                ['null', 'string'],
                ['types' => 'null|string'],
                [0, 1, 8],
            ],
            [
                ['resource'],
                ['types' => 'resource'],
                [0, 9],
            ],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getInfo
     * @covers ::getType
     * @covers ::validate
     * @dataProvider dataProvider
     */
    public function testCanValidate(
        array $arguments,
        array $expected_info,
        array $expected_success,
    ): void {
        $rule = new TypedArray(...$arguments);
        $this->assertRuleInfo($rule, $expected_info);
        $this->assertValidation($rule, $expected_success);
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(TypedArray::class, false);
    }

    /**
     * Get values to validate.
     */
    protected function getValues(): array
    {
        $files = [
            fopen('data://text/plain,abc', 'r'),
            fopen('data://text/plain,def', 'r'),
        ];
        fclose($files[1]); // PHP returns "resource (closed)" since 7.2
        return [
            0 => [],
            1 => ['foo', 'bar', 'baz'],
            2 => [1, 2, 3, 4],
            3 => [1.5, 2.0, 2.5, 3.0],
            4 => [1.5, 2, 2.5, 3],
            5 => ['1.5', 2, 2.5, '3'],
            6 => [new \stdClass, new \stdClass, (object) []],
            7 => [[], [1, 2, 3], ['foo', 'bar']],
            8 => ['foo', null],
            9 => $files,
        ];
    }
}
