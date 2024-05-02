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

use Laucov\Validation\Rules\In;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\In
 */
class InTest extends RuleTestCase
{
    public function dataProvider(): array
    {
        return [
            [[[-16, 128], false], [1, 2, 4, 5, 11, 19, 21, 22, 23, 29, 30]],
            [[[-16, 128], true], [2, 4]],
            [[[-16.0, 128.0], false], [1, 2, 4, 5, 11, 19, 21, 22, 23, 29, 30]],
            [[[-16.0, 128.0], true], [5, 11]],
            [[['-16.0', '128.0'], false], [1, 2, 4, 5, 11, 19, 21, 22, 23, 29, 30]],
            [[['-16.0', '128.0'], true], [22, 29]],
            [[['-16.000', '128.000'], false], [1, 2, 4, 5, 11, 19, 21, 22, 23, 29, 30]],
            [[['-16.000', '128.000'], true], [23, 30]],
            [[[0], false], [0, 3, 8, 13, 20, 26]],
            [[[0], true], [3]],
            [[['foo', 'bar'], false], [1, 17, 18]],
            [[['foo', 'bar'], true], [17, 18]],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::validate
     * @dataProvider dataProvider
     */
    public function testCanValidate(array $arguments, array $expected): void
    {
		$rule = new In(...$arguments);
        $this->assertValidation($rule, $expected);
    }

    /**
     * @covers ::validate
     * @uses Laucov\Validation\Rules\In::__construct
     */
    public function testDoesNotAcceptNonScalarValues(): void
    {
        $this->assertRejectsNonScalarValues(new In([[]], false));
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(In::class, true);
    }

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
