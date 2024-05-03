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

use Laucov\Validation\Rules\NotIn;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\NotIn
 */
class NotInTest extends RuleTestCase
{
    /**
     * Provides arguments and expectations for validation tests.
     */
    public function dataProvider(): array
    {
        return [
            [
                [[-16, 128], false],
                ['values' => '-16, 128'],
                [
                    0, 3, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16, 17, 18, 20, 24,
                    25, 26, 27, 28,
                ],
            ],
            [
                [[-16, 128], true],
                ['values' => '-16, 128'],
                [
                    0, 1, 3, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18,
                    19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
                ],
            ],
            [
                [[-16.0, 128.0], false],
                ['values' => '-16.0, 128.0'],
                [
                    0, 3, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16, 17, 18, 20, 24,
                    25, 26, 27, 28,
                ],
            ],
            [
                [[-16.0, 128.0], true],
                ['values' => '-16.0, 128.0'],
                [
                    0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16, 17, 18,
                    19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
                ],
            ],
            [
                [['-16.0', '128.0'], false],
                ['values' => '"-16.0", "128.0"'],
                [
                    0, 3, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16, 17, 18, 20, 24,
                    25, 26, 27, 28,
                ],
            ],
            [
                [['-16.0', '128.0'], true],
                ['values' => '"-16.0", "128.0"'],
                [
                    0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16,
                    17, 18, 19, 20, 21, 23, 24, 25, 26, 27, 28, 30,
                ],
            ],
            [
                [['-16.000', '128.000'], false],
                ['values' => '"-16.000", "128.000"'],
                [
                    0, 3, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16, 17, 18, 20, 24,
                    25, 26, 27, 28,
                ],
            ],
            [
                [['-16.000', '128.000'], true],
                ['values' => '"-16.000", "128.000"'],
                [
                    0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16,
                    17, 18, 19, 20, 21, 22, 24, 25, 26, 27, 28, 29,
                ],
            ],
            [
                [[0], false],
                ['values' => '0'],
                [
                    1, 2, 4, 5, 6, 7, 9, 10, 11, 12, 14, 15, 16, 17, 18, 19,
                    21, 22, 23, 24, 25, 27, 28, 29, 30,
                ],
            ],
            [
                [[0], true],
                ['values' => '0'],
                [
                    0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17,
                    18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
                ],
            ],
            [
                [['foo', 'bar'], false],
                ['values' => '"foo", "bar"'],
                [
                    0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 19,
                    20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
                ],
            ],
            [
                [['foo', 'bar'], true],
                ['values' => '"foo", "bar"'],
                [
                    0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16,
                    19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
                ],
            ],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::formatList
     * @covers ::formatValue
     * @covers ::getInfo
     * @covers ::validate
     * @dataProvider dataProvider
     */
    public function testCanValidate(
        array $arguments,
        array $expected_info,
        array $expected_success,
    ): void {
        $rule = new NotIn(...$arguments);
        $this->assertRuleInfo($rule, $expected_info);
        $this->assertValidation($rule, $expected_success);
    }

    /**
     * @covers ::validate
     * @uses Laucov\Validation\Rules\NotIn::__construct
     */
    public function testDoesNotAcceptNonScalarValues(): void
    {
        $this->assertRejectsNonScalarValues(new NotIn([[]], false));
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(NotIn::class, true);
    }

    /**
     * Get values to validate.
     */
    protected function getValues(): array
    {
        return [
            0 => false,
            1 => true,
            2 => -16,
            3 => 0,
            4 => 128,
            5 => -16.0,
            6 => -16.00000001,
            7 => -0.00000001,
            8 => 0.0,
            9 => 0.00000001,
            10 => 127.99999999,
            11 => 128.0,
            12 => '',
            13 => '0',
            14 => 'false',
            15 => '1',
            16 => 'true',
            17 => 'foo',
            18 => 'bar',
            19 => '-16',
            20 => '0',
            21 => '128',
            22 => '-16.0',
            23 => '-16.000',
            24 => '-16.00000001',
            25 => '-0.00000001',
            26 => '0.0',
            27 => '0.00000001',
            28 => '127.99999999',
            29 => '128.0',
            30 => '128.000',
        ];
    }
}
