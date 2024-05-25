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

use Laucov\Validation\Rules\Matches;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\Matches
 */
class MatchesTest extends RuleTestCase
{
    /**
     * Provides arguments and expectations for validation tests.
     */
    public function dataProvider(): array
    {
        $data = [
            'a' => 'foo',
            'b' => 123,
            'c' => false,
        ];

        return [
            [
                ['a'],
                $data,
                ['key' => 'a'],
                [0],
            ],
            [
                ['b'],
                $data,
                ['key' => 'b'],
                [3],
            ],
            [
                ['c'],
                $data,
                ['key' => 'c'],
                [6],
            ],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getInfo
     * @covers ::validate
     * @uses Laucov\Validation\AbstractRule::setData
     * @dataProvider dataProvider
     */
    public function testCanValidate(
        array $arguments,
        array $data,
        array $expected_info,
        array $expected_success,
    ): void {
        $rule = new Matches(...$arguments);
        $rule->setData($data);
        $this->assertRuleInfo($rule, $expected_info);
        $this->assertValidation($rule, $expected_success);
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(Matches::class, true);
    }

    /**
     * Get values to validate.
     */
    protected function getValues(): array
    {
        return [
            0 => 'foo',
            1 => 'bar',
            2 => '123',
            3 => 123,
            4 => '0',
            5 => 0,
            6 => false,
            7 => '',
        ];
    }
}
