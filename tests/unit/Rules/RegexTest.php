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

use Laucov\Validation\Rules\Regex;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\Regex
 * @todo Test non-string values.
 */
class RegexTest extends RuleTestCase
{
    /**
     * Provides arguments and expectations for validation tests.
     */
    public function dataProvider(): array
    {
        return [
            [
                ['/^\p{L}+$/'],
                ['pattern' => '/^\p{L}+$/'],
                [8],
            ],
            [
                ['/\.com$/'],
                ['pattern' => '/\.com$/'],
                [2, 3],
            ],
            [
                ['/^[A-Za-z\d\s\.]+$/'],
                ['pattern' => '/^[A-Za-z\d\s\.]+$/'],
                [0, 1, 6, 7, 8],
            ],
            [
                ['/^.+[@\?\.]+.+$/'],
                ['pattern' => '/^.+[@\?\.]+.+$/'],
                [2, 3, 4, 5],
            ],
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
        $rule = new Regex(...$arguments);
        $this->assertRuleInfo($rule, $expected_info);
        $this->assertValidation($rule, $expected_success);
    }

    /**
     * @covers ::validate
     * @uses Laucov\Validation\Rules\Regex::__construct
     */
    public function testDoesNotAcceptNonScalarValues(): void
    {
        $this->assertRejectsNonScalarValues(new Regex('/^\d+$/'));
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(Regex::class, true);
    }

    /**
     * Get values to validate.
     */
    protected function getValues(): array
    {
        return [
            0 => 'The quick brown fox jumps over the lazy dog.',
            1 => 'Lorem ipsum dolor',
            2 => 'john.doe@foobar.com',
            3 => 'michael.scott@dundermifflin.com',
            4 => '<?php class Foo {}',
            5 => '<?php class Bar extends Foo {}',
            6 => '5063516945005047',
            7 => '5063 5169 4500 5047',
            8 => 'FooBarBaz'
        ];
    }
}
