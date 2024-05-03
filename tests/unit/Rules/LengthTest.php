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

use Laucov\Validation\Rules\Length;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\Length
 */
class LengthTest extends RuleTestCase
{
    /**
     * Provides arguments and expectations for validation tests.
     */
    public function dataProvider(): array
    {
        return [
            [[6], ['min' => '6', 'max' => ''], [0, 1, 2, 3, 6, 7, 8]],
            [[0, 6], ['min' => '0', 'max' => '6'], [0, 4, 5, 6, 7, 8]],
            [[6, 6], ['min' => '6', 'max' => '6'], [0, 6, 7, 8]],
            [[14], ['min' => '14', 'max' => ''], [1, 2, 3]],
            [[14, 14], ['min' => '14', 'max' => '14'], [1, 2]],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getInfo
     * @covers ::validate
     * @dataProvider dataProvider
     */
    public function testCanValidate(
        array $arguments,
        array $expected_info,
        array $expected_success,
    ): void {
        $rule = new Length(...$arguments);
        $this->assertRuleInfo($rule, $expected_info);
        $this->assertValidation($rule, $expected_success);
    }

    /**
     * @covers ::validate
     * @uses Laucov\Validation\Rules\Length::__construct
     */
    public function testDoesNotAcceptNonScalarValues(): void
    {
        $this->assertRejectsNonScalarValues(new Length());
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(Length::class);
    }

    /**
     * Get values to validate.
     */
    protected function getValues(): array
    {
        return [
            0 => 'Foobar',
            1 => 'Lorem ipsum do',
            2 => '部分地区最高温升幅12℃以上',
            3 => 'The quick brown fox jumps over the lazy dog.',
            4 => true,
            5 => false,
            6 => 1112.2,
            7 => 111222,
            8 => 111222.0000,
        ];
    }
}
