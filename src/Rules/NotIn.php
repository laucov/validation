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
use Laucov\Validation\Rules\Traits\ValueRuleTrait;

/**
 * Requires a value not to be in a list.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class NotIn extends AbstractRule
{
    use ValueRuleTrait;
    
    /**
     * Create the rule instance.
     */
    public function __construct(
        /**
         * Allowed values.
         */
        protected array $values,

        /**
         * Whether to perform strict comparisons when validating.
         */
        protected bool $strict,
    ) {
    }

    /**
     * Get the rule's info.
     * 
     * @return array<string>
     */
    public function getInfo(): array
    {
        return ['values' => $this->formatList($this->values)];
    }

    /**
     * Validate a single value.
     */
    public function validate(mixed $value): bool
    {
        if (!is_scalar($value)) {
            return false;
        }

        return !in_array($value, $this->values, $this->strict);
    }
}
