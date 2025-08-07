<?php

namespace App\Validation;

use Psr\Http\Message\RequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected $errors = [];

    public function validate(
        RequestInterface $request,
        array $rules,
    ): static
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            } catch (NestedValidationException $exception) {
                $this->errors[$field] = $exception->getMessages();
            }
        }

        $_SESSION['errors'] = $this->errors;

        return $this;
    }

    public function failed(): bool
    {
        return !empty($this->errors);
    }
}
