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

use Laucov\Validation\Rules\LessThanOrEqualTo;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\LessThanOrEqualTo
 */
class LessThanOrEqualToTest extends RuleTestCase
{
    /**
     * Provides arguments and expectations for validation tests.
     */
    public function dataProvider(): array
    {
        return [
            [[80], ['value' => '80'], [1, 3, 4, 5, 7, 9, 10]],
            [[0], ['value' => '0'], [1, 4, 9, 10]],
            [[1], ['value' => '1'], [1, 3, 4, 9, 10]],
            [[-13.00000001], ['value' => '-13.00000001'], [3, 4, 10]],
            [
                ['foobar'],
                ['value' => '"foobar"'],
                [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ],
            [
                ['eoobar'],
                ['value' => '"eoobar"'],
                [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ],
            [
                ['Foobar'],
                ['value' => '"Foobar"'],
                [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ],
            [
                ['Eoobar'],
                ['value' => '"Eoobar"'],
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ],
            [[true], ['value' => 'true'], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            [[false], ['value' => 'false'], [4, 9]],
        ];
    }

    /**
     * @covers ::__construct
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
        $rule = new LessThanOrEqualTo(...$arguments);
        $this->assertRuleInfo($rule, $expected_info);
        $this->assertValidation($rule, $expected_success);
    }

    /**
     * @covers ::validate
     * @uses Laucov\Validation\Rules\LessThanOrEqualTo::__construct
     */
    public function testDoesNotAcceptNonScalarValues(): void
    {
        $this->assertRejectsNonScalarValues(new LessThanOrEqualTo(0));
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(LessThanOrEqualTo::class, true);
    }

    /**
     * Get values to validate.
     */
    protected function getValues(): array
    {
        return [
            0 => 'Foobar',
            1 => '-12.456',
            2 => '89',
            3 => true,
            4 => false,
            5 => 41,
            6 => 123,
            7 => 45.6,
            8 => 99.8451,
            9 => 0,
            10 => -16,
        ];
    }
}
