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

namespace Laucov\Validation\Rules;

use Laucov\Validation\AbstractRule;

/**
 * Requires a value to contain a non-empty value.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class RequiredWith extends AbstractRule
{
    /**
     * Combination of keys that will require the value to be present.
     * 
     * @var array<int|string>
     */
    protected array $keys;

    /**
     * Create the rule instance.
     */
    public function __construct(int|string ...$keys)
    {
        $this->keys = $keys;
    }

    /**
     * Validate a single value.
     */
    public function validate(mixed $value): bool
    {
        // Check if all required keys are present.
        foreach ($this->keys as $key) {
            if (!isset($this->getData()->$key)) {
                return true;
            }
        }

        return !empty($value);
    }
}
