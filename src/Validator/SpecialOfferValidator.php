<?php

namespace App\Validator;

use \Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\NestedValidationException;

class SpecialOfferValidator
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
        $this->rules['name'] = V::alnum()->notEmpty()->setName("name");
        $this->rules['discount'] = V::digit()->positive()->between(0, 1)->notEmpty()->setName("discount");
        $this->rules['expired_date'] = V::date('Y-m-d')->notEmpty()->setName("expired_date");
    }

    public function initMessages()
    {
        $this->messages = [
            'alnum'                 => '{{name}} must only contain alphabetic and numeric characters.',
            'digit'                 => '{{name}} must only numeric characters',
            'positive'                 => '{{name}} must only positive numeric characters',
            'between'                 => '{{name}} should be a value between 0 and 1',
            'date'                 => '{{name}} should be a date (format: 12-02-28)',
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

    public function getErrors()
    {
        return $this->errors;
    }
}