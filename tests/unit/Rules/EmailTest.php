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

use Laucov\Validation\Rules\Email;
use Tests\RuleTestCase;

/**
 * @coversDefaultClass \Laucov\Validation\Rules\Email
 */
class EmailTest extends RuleTestCase
{
    /**
     * Provides arguments and expectations for validation tests.
     */
    public function dataProvider(): array
    {
        return [
            [[], [0, 1, 8]],
            [[false], [0, 1, 8]],
            [[true], [0, 1, 2, 3, 8]],
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getInfo
     * @covers ::validate
     * @dataProvider dataProvider
     */
    public function testCanValidate(array $arguments, array $expected): void
    {
        $rule = new Email(...$arguments);
        $this->assertRuleInfo($rule, []);
        $this->assertValidation($rule, $expected);
    }

    /**
     * @covers ::validate
     * @uses Laucov\Validation\Rules\Email::__construct
     */
    public function testDoesNotAcceptNonScalarValues(): void
    {
        $this->assertRejectsNonScalarValues(new Email());
    }

    /**
     * @coversNothing
     */
    public function testIsPropertyAttribute(): void
    {
        $this->assertIsPropertyAttribute(Email::class, true);
    }

    /**
     * Get values to validate.
     */
    protected function getValues(): array
    {
        return [
            0 => 'john.doe@foobar.com',
            1 => 'MR-joseph-someone@somewhere.org',
            2 => 'SR-josé-alguém@algum-lugar.org',
            3 => '字@domain.com',
            4 => 'not-an-email',
            5 => 'mary@domínio.com',
            6 => 'someone@localhost',
            7 => 'invalid name@domain.net',
            8 => 'valid+name@domain.net',
        ];
    }
}
