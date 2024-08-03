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

namespace Tests;

use Laucov\Validation\Interfaces\RuleInterface;
use PHPUnit\Framework\TestCase;

/**
 * Provides a method to get an object's rule attribute.
 */
abstract class RuleTestCase extends TestCase
{
    /**
     * Get values to validate.
     * 
     * This function must return key-value pairs for filtering.
     */
    abstract protected function getValues(): array;

    /**
     * Assert that a class can be used as a property attribute.
     */
    protected function assertIsPropertyAttribute(
        string $class_name,
        bool $is_repeatable = false,
    ): void {
        // Get class attributes.
        $reflection = new \ReflectionClass($class_name);
        $attributes = $reflection->getAttributes();

        // Set expected bitmask.
        $bitmask = $is_repeatable
            ? \Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE
            : \Attribute::TARGET_PROPERTY;

        // Check if the class is a property attribute.
        foreach ($attributes as $attribute) {
            // Check if is an attribute.
            if ($attribute->getName() !== \Attribute::class) {
                continue;
            }
            // Check if targets properties.
            $argument = $attribute->getArguments()[0] ?? null;
            if ($argument !== $bitmask) {
                continue;
            }
            $this->assertTrue(true);
            return;
        }

        // Fail if couldn't find an `Attribute` attribute.
        $message = 'Failed to assert that %s is a %s property attribute.';
        $repeatable = $is_repeatable ? 'repeatable' : 'non-repeatable';
        $this->fail(sprintf($message, $class_name, $repeatable));
    }

    /**
     * Assert that a rule invalidates non-scalar values.
     */
    protected function assertRejectsNonScalarValues(RuleInterface $rule): void
    {
        $values = $this->getNonScalarValues();
        foreach ($values as $value) {
            if ($rule->validate($value)) {
                $class_name = array_slice(explode('\\', $rule::class), -1)[0];
                $export = var_export($value, true);
                $message = 'Failed to assert that %s expects scalar values.'
                    . PHP_EOL . '$rule->validate(%s) returned true.';
                $this->fail(sprintf($message, $class_name, $export));
            }
        }
        $this->assertTrue(true);
    }

    /**
     * Assert the rule info output.
     */
    protected function assertRuleInfo(RuleInterface $rule, array $expected): void
    {
        $class_name = array_slice(explode('\\', $rule::class), -1)[0];
        $actual = $rule->getInfo();
        $message = 'Assert that %s->getInfo() only contains strings.';
        $message = sprintf($message, $class_name);
        $this->assertContainsOnly('string', $actual, message: $message);
        foreach ($expected as $key => $value) {
            $message = 'Assert that %s->getInfo()["%s"] exists.';
            $message = sprintf($message, $class_name, $key);
            $this->assertArrayHasKey($key, $actual, $message);
            $message = 'Assert that %s->getInfo()["%s"] is %s.';
            $export = var_export($value, true);
            $message = sprintf($message, $class_name, $key, $export);
            $this->assertSame($value, $actual[$key], $message);
        }

        // Check size.
        $this->assertSameSize($expected, $actual, $message);
    }

    /**
     * Test all values from `ruleProvider()` with `$rule->validate()`.
     * 
     * Assert that the valid values correspond to the indexes in `$expected`.
     * 
     * @param RuleInterface $rule Rule to test validation.
     * @param array $expected Valid indexes from `ruleProvider()`'s values.
     */
    protected function assertValidation(
        RuleInterface $rule,
        array $expected,
    ): void {
        $class_name = array_slice(explode('\\', $rule::class), -1)[0];
        foreach ($this->getValues() as $i => $value) {
            $export = var_export($value, true);
            $is_valid = in_array($i, $expected, true);
            $state = $is_valid ? 'valid' : 'not valid.';
            $message = 'Assert that %s->validate(%s) is %s.';
            $message = sprintf($message, $class_name, $export, $state);
            $this->assertSame($is_valid, $rule->validate($value), $message);
        }
    }

    /**
     * Get test non-scalar values.
     */
    protected function getNonScalarValues(): array
    {
        return [
            null,
            [],
            [[]],
            [1, 2],
            ['a', 'b', 'c'],
            [null, 'a', 1.23, []],
            new \stdClass(),
            fopen('data://text/plain,foobar', 'r'),
            function () {},
            fn ($foo) => 'bar',
        ];
    }
}
