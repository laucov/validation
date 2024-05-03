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
 * Requires a value to be a valid email address.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Email extends AbstractRule
{
    /**
     * Create the rule instance.
     */
    public function __construct(
        /**
         * Whether to accept non-ASCII characters in the address.
         */
        protected bool $unicode = false,
    ) {
    }

    /**
     * Get the rule's info.
     * 
     * @return array<string>
     */
    public function getInfo(): array
    {
        return [];
    }

    /**
     * Validate a single value.
     */
    public function validate(mixed $value): bool
    {
        if (!is_scalar($value)) {
            return false;
        }

        $options = $this->unicode ? FILTER_FLAG_EMAIL_UNICODE : 0;

        return filter_var($value, FILTER_VALIDATE_EMAIL, $options) !== false;
    }
}
