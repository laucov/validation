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
 * Stores rules and validate values with them.
 */
class Ruleset
{
    /**
     * Context data.
     */
    protected array|object $data = [];

    /**
     * Current errors.
     * 
     * @var array<string>
     */
    protected array $errors = [];

    /**
     * Message to use when a "required" or "required_with" error is created.
     */
    protected null|string $obligatorinessMessage = null;

    /**
     * Whether the value is always required.
     */
    protected bool $required = false;

    /**
     * Keys that - when not empty - make the value required.
     * 
     * @var array<string>
     */
    protected array $requiredWith = [];

    /**
     * Registered rules.
     * 
     * @var array<RuleInterface>
     */
    protected array $rules = [];

    /**
     * Add a new rule.
     */
    public function addRule(RuleInterface ...$rules): static
    {
        array_push($this->rules, ...$rules);
        return $this;
    }

    /**
     * Get current errors.
     * 
     * @return array<Error>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Make the value always required.
     */
    public function require(null|array $with = null, $message = null): static
    {
        $this->required = $with === null;
        $this->requiredWith = $with ?? [];
        $this->obligatorinessMessage = $message;
        return $this;
    }

    /**
     * Set data to contextualize the next validated values.
     */
    public function setData(array|object $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Validate a value with all registered rules.
     */
    public function validate(mixed $value): bool
    {
        // Empty errors.
        $this->errors = [];

        // Check empty value.
        if ($this->isEmpty($value)) {
            // Check if is required.
            if (!$this->isRequired()) {
                return true;
            }
            // Set error.
            if ($this->required) {
                $rule_name = 'required';
                $params = [];
            } else {
                $rule_name = 'required_with';
                $params = ['keys' => implode(', ', $this->requiredWith)];
            }
            $message = $this->obligatorinessMessage;
            $this->errors[] = new Error($rule_name, $params, $message);
            return false;
        }

        // Validate each rule.
        foreach ($this->rules as $rule) {
            $rule->setData($this->data);
            if (!$rule->validate($value)) {
                $this->errors[] = Error::createFromRule($rule);
            }
        }

        return count($this->errors) === 0;
    }

    /**
     * Check whether `$data` has the given key.
     */
    protected function hasKey(string $key): bool
    {
        return is_array($this->data)
            ? isset($this->data[$key])
            : isset($this->data->$key);
    }

    /**
     * Check whether a value is empty.
     */
    protected function isEmpty(mixed $value): bool
    {
        return $value === null || $value === '' || $value === [];
    }

    /**
     * Check whether the value is currently required.
     */
    protected function isRequired(): bool
    {
        // Check if is always required.
        if ($this->required) {
            return true;
        }

        // Check if is conditionally required.
        if (count($this->requiredWith) === 0) {
            return false;
        }

        // Check if all data keys are present.
        foreach ($this->requiredWith as $key) {
            if (!$this->hasKey($key)) {
                return false;
            }
        }

        return true;
    }
}
