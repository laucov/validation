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

namespace Laucov\Validation;
use Laucov\Validation\Interfaces\RuleInterface;

/**
 * Stores information about a validation error.
 */
class Error
{
    public static function createFromRule(RuleInterface $rule): Error
    {
        return new Error($rule::class, $rule->getInfo());
    }

    /**
     * Create the error instance.
     */
    public function __construct(
        /**
         * Rule identification.
         */
        public string $rule,

        /**
         * Rule formatted parameters.
         */
        public array $parameters,
    ) {
        // Check parameters.
        foreach ($this->parameters as $parameter) {
            if (!is_string($parameter)) {
                $message = 'All error parameters must be strings.';
                throw new \InvalidArgumentException($message);
            }
        }
    }
}
