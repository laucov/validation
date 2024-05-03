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

use Laucov\Validation\Rules\Contains;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\Contains
 */
class ContainsTest extends RuleTestCase
{
    /**
     * Provides arguments and expectations for validation tests.
     */
    public function dataProvider(): array
    {
        return [
            [['quick'], ['needles' => '"quick"'], [0]],
            [[' '], ['needles' => '" "'], [0, 1]],
            [['.'], ['needles' => '"."'], [0, 2]],
            [['@', '.'], ['needles' => '"@", "."'], [0, 2, 3]],
            [['@', '.', ' '], ['needles' => '"@", ".", " "'], [0, 1, 2, 3]],
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
        $rule = new Contains(...$arguments);
        $this->assertRuleInfo($rule, $expected_info);
        $this->assertValidation($rule, $expected_success);
    }

    /**
     * @covers ::validate
     * @uses Laucov\Validation\Rules\Contains::__construct
     */
    public function testDoesNotAcceptNonScalarValues(): void
    {
        $this->assertRejectsNonScalarValues(new Contains());
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(Contains::class, true);
    }

    /**
     * Get values to validate.
     */
    protected function getValues(): array
    {
        return [
            0 => 'The quick brown fox jumps over the lazy dog.',
            1 => 'Lorem ipsum dolor sit amet',
            2 => 'john.doe@foobar.com',
            3 => 'johndoe@localhost',
        ];
    }
}
