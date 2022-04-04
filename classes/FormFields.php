<?php

namespace microman;

use Kirby\Cms\Blocks;
use Kirby\Http\Server;

class FormFields extends Blocks
{

    /**
     * Visitor send some values
     *
     * @var Bool
     */
    protected $isFilled;

    /**
     * Magic getter function
     *
     * @param array $params
     * @param object $parent
     * @param string $formid
     * 
     * @return mixed
     */
    public function __construct(array $params, object $parent, string $formid)
    {
        $this->parent = $parent;
        
        $this->isFilled = Server::get('REQUEST_METHOD') === 'POST' && get($formid);

        foreach ($params as $formfield) {

            $this->add(
                new FormField(
                    [
                        "content" => $formfield['content'],
                        'id' => $formfield['id'],
                        //Escape the prefixes form formfield type
                        'type' => preg_replace('/_[0-9]+_|\_/', '/', $formfield['type']),
                        'isFilled' => $this->isFilled()
                    ], $this->parent()
                )
            );

        }
    }

    /**
     * Magic caller for field methods
     *
     * @param string $key
     * @param mixed $arguments
     * 
     * @return mixed
     */
    public function __call(string $key, $arguments)
    {
        // collection methods
        if ($this->hasMethod($key) === true) {
            return $this->callMethod($key, $arguments);
        }
        if ($field = $this->findBy('slug', str_replace('_', '-', $key)))
            return $field;

        return NULL;
    }


    /**
     * Get error fields
     * 
     * @param string|NULL $separator Unset returns Array
     * @return string|array
     */
    public function errorFields($separator = NULL)
    {
        $errors = [];

        foreach ($this as $f) {
            if (!$f->isValid()) {
                array_push($errors, $f->label()->toString());
            }
        }

        return is_null($separator) ? $errors : implode($separator, $errors);
    }

    /**
     * Check if all field filled right
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        return count($this->errorFields()) == 0 || !$this->isFilled();
    }


    /**
     * Check if form is filled
     * 
     * @return bool
     */
    public function isFilled(): bool
    {
        return $this->isFilled;
    }

    /**
     * Check if the bear grabs into the honeypot
     * 
     * @param string HoneypotID
     * 
     * @return bool
     */
    public function checkHoneypot($hpId): bool
    {
        if ((get($hpId) === NULL || get($hpId) !== "") && $this->isFilled()) {
            $this->isFilled = false;
            return false;
        };
        return true;

    }

}