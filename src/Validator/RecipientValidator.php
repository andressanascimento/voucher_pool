<?php

namespace App\Validator;

use \Respect\Validation\Validator as V;
use \Respect\Validation\Exceptions\NestedValidationExceptionInterface;

class RecipientValidator
{
    /**
     * List of constraints
     *
     * @var array
     */
    protected $rules = [];
    
    /**
     * List of customized messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * List of returned errors in case of a failing assertion
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Just another constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->initRules();
        $this->initMessages();
    }
    
    /**
     * Set the user subscription constraints
     *
     * @return void
     */
    public function initRules()
    {
        $this->rules['name'] = V::alpha()->notEmpty()->setName("name");
        $this->rules['email'] = V::email()->notEmpty()->setName("email");
    }

    public function initMessages()
    {
        $this->messages = [
            'alpha'                 => '{{name}} must only contain alphabetic characters.',
            'email'                 => 'Please make sure you typed a correct email address.',
            'notEmpty'            => '{{name}} is required',
        ];
    }

    public function assert(array $inputs)
    {
        foreach ($this->rules as $rule => $validators) {
            $value = array_key_exists($rule, $inputs) ? $inputs[$rule] : null;
            try {
                $validators->assert($value);
            } catch (NestedValidationException $ex) {
                $this->errors = $ex->getMessages();
                return false;
            }
        }

        return true;
    }
}