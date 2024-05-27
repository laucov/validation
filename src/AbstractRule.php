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
 * Validates values.
 */
abstract class AbstractRule implements RuleInterface
{
    /**
     * Context data.
     */
    protected object $data;

    /**
     * Custom error message.
     */
    protected null|string $message = null;

    /**
     * Get the rule's info.
     * 
     * @return array<string>
     */
    abstract public function getInfo(): array;

    /**
     * Validate a single value.
     */
    abstract public function validate(mixed $value): bool;

    /**
     * Get the rule's custom error message, if previously set.
     */
    public function getMessage(): null|string
    {
        return $this->message;
    }

    /**
     * Set data to contextualize the next validated values.
     */
    public function setData(array|object $data): void
    {
        $this->data = (object) $data;
    }

    /**
     * Set a custom error message for the rule.
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Get the data that is contextualizing the current validated value.
     */
    protected function getData(): object
    {
        $this->data ??= new \stdClass();
        return $this->data;
    }
}
