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

namespace Laucov\Validation\Rules\Traits;

/**
 * Provides methods for rules that handle a value or a list as its main parameters.
 */
trait ValueRuleTrait
{
    /**
     * Format the given list of values as a comma-separated list.
     */
    protected function formatList(array $values): string
    {
        // Format each value.
        $values = array_map([$this, 'formatValue'], $values);

        // Join as a list.
        return implode(', ', $values);
    }

    /**
     * Format the given value as a string.
     */
    protected function formatValue(mixed $value): string
    {
        // Quote or export value.
        return match (gettype($value)) {
            'string' => '"' . $value . '"',
            default => var_export($value, true),
        };
    }
}
