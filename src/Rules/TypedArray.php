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
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class TypedArray extends AbstractRule
{
    /**
     * Allowed types.
     * 
     * @var array<string>
     */
    protected array $types = [];
    
    /**
     * Create the rule instance.
     */
    public function __construct(string ...$types)
    {
        $this->types = $types;
    }

    /**
     * Get the rule's info.
     * 
     * @return array<string>
     */
    public function getInfo(): array
    {
        return ['types' => implode('|', $this->types)];
    }

    /**
     * Validate a single value.
     */
    public function validate(mixed $value): bool
    {
        if (!is_array($value)) {
            return false;
        }
        foreach ($value as $element) {
            $type = $this->getType($element);
            if (!in_array($type, $this->types, true)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the value type.
     */
    public function getType(mixed $value): string
    {
        return match ($type = gettype($value)) {
            'boolean' => 'bool',
            'double' => 'float',
            'integer' => 'int',
            'NULL' => 'null',
            'resource (closed)' => 'resource',
            default => $type,
        };
    }
}
