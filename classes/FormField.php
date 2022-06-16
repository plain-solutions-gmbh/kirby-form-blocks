<?php

namespace microman;

use Kirby\Cms\Block;
use Kirby\Toolkit\A;
use Kirby\Toolkit\V;
use Kirby\Toolkit\Str;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\Escape;

class FormField extends Block
{

    /**
     * Visitor send some values
     *
     * @var Bool
     */
    protected $isFilled;

    /**
     * Creates a field
     * 
     * @param array $params Fieldsdata
     * @param object $parent
     * 
     * @return null
     */
    public function __construct(array $params, object $parent)
    {

        $this->parent = $parent;
        
        //TODO: Hook before field
        if (array_key_exists('options', $params['content'])) {
            $params['content']['opt'] = $this->setOptions($params);
        } else {
            $params['content']['value'] = $this->setValue($params);
        }
        //TODO: Hook after field

        $this->isFilled = $params['isFilled'];
        parent::__construct($params);

    }

    /**
     * Get request value by parameter or array of all if $slug is empty
     * 
     * @param string $slug
     * 
     * @return array|string
     */
    private function request($slug = NULL)
    {
        if (is_null($slug))
            return get();

        return get(is_string($slug) ? $slug : $slug->toString()) ?: "";
    }

    /**
     * Prepare the options to work with them
     * 
     * @param array $field
     * 
     * @return array 
     */
    private function setOptions(array $field): array
    {
        if (!$field['isFilled']) {
            return $field['content']['options'];
        }

        return array_map(function ($option) use ($field) {

            if ($field['type'] == 'formfields/checkbox') {
                $option['selected'] = in_array($option['slug'], array_keys($this->request()));
            } else {
                $option['selected'] = $this->request($field['content']['slug']) ==  $option['slug'];
            }

            return $option;

        }, $field['content']['options']);

    }

    /**
     * Prepare the value to work with them
     * 
     * @param array $field
     * 
     * @return string
     */
    private function setValue(array $field): string
    {
        if ($field['isFilled']) {
            return $this->request($field['content']['slug']);
        }

        if (isset($field['content']['default'])) {
            return $field['content']['default'];
        }

        return "";
    }

    /**
     * Retruns the value of the field
     *
     * @param bool $raw return value without parsing
     * 
     * @return string
     */
    public function value($raw = false): string
    {
        if ($this->hasOptions()) {
            return A::join($this->selectedOptions($raw ? 'slug' : 'label'), ', ');
        }

        return $raw ? $this->content()->value() : Escape::html($this->content()->value());
    }

    /**
     * Get Autofill
     *
     * @param bool return with attribute
     * 
     * @return string|null
     */
    public function autofill($html = false)
    {
        $val = $this->content()->autofill();

        if (!$html) return $val;
        if (!$val->isEmpty()) return ' autocomplete="' . $val . '"';

        return "";
    }

    /**
     * Get Aria Error Atribute
     *
     * 
     * @return string|null
     */
    public function ariaAttr()
    {
        if ($this->isValid()) return "";
        return 'invalid aria-describedby="' . $this->id() . '-error-message"';

    }

    /**
     * Get required
     *
     * @param bool|string return with attribute
     * 
     * @return string|bool
     */
    public function required($html = false)
    {
        if (!$html) {
            return $this->content()->required()->isTrue();
        }

        if ($this->content()->required()->isTrue()) {
            if ($html === 'asterisk') return '*';
            if ($html === 'attr') return ' required';
        }
        return "";
    }


    /**
     * Convert type
     *
     * @param bool $onlyName
     * 
     * @return string
     */
    public function type($onlyName = false): string
    {
        if ($onlyName) {
            return A::last(Str::split($this->type, '/'));
        }
        return $this->type;
    }

    /*********************/
    /** Options Methods **/
    /*********************/


    /**
     * Check if this this field is an option field
     * 
     * @return bool
     */
    public function hasOptions(): bool
    {
        return !$this->options()->isEmpty();
    }

    /**
     * Returns option fields as structure
     * 
     * @return Kirby\Cms\Structure
     */
    
    public function options()
    {
        return $this->opt()->toStructure();
    }

    /**
     * Get Selected options as Array or by $prop
     * 
     * @param array $prop
     * @return array|NULL
     */
    public function selectedOptions($prop = 'label')
    {
        $out = [];
        foreach ($this->options()->toArray() as $value) {
            if ($value['selected'])
                array_push($out,$value[$prop]);
        }
        return $out;
        return $this->options()->filterBy('selected', true)->pluck($prop, true);
    }

    /************************/
    /** Validation Methods **/
    /************************/

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
     * Get array of validator
     * 
     * @return array
     */
    private function validator(): array
    {

        if (!$this->isFilled)
            return [];
            
        $validator = $this->validate()->toStructure()->toArray();

        if ($this->required()) {
            $msg = $this->required_fail()
            ->or(option('microman.formblock.translations.' . I18n::locale() . 'field_message'))
            ->or(I18n::translate('form.block.field_message'));
            
            array_push($validator, ['validate' => 'different', 'different' => '', 'msg' => $msg]);
        }

        return $validator;
    }

    /**
     * Get array of all validators (with errors if occur)
     * 
     * @return array
     */
    public function errorMessages(): array
    {
        $rules = [];
        $messages = [];

        foreach ($this->validator()  as $v) {
            $rule = Str::lower($v['validate']);
            $rules[$rule] = [isset($v[$rule]) ? $v[$rule] : "" ];
            $messages[$rule] = $v['msg'] ?: NULL;
        }

        return V::errors($this->value(), $rules, $messages);
    }

    /**
     * Get first failed fields message
     * 
     * @return string
     */
    public function errorMessage(): string
    {
        return A::first($this->errorMessages()) ?: "";
    }

    /**
     * Get true if everything filled right
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        return count($this->errorMessages()) == 0;
    }

    /**
     * Get true if everything failed
     * 
     * @return bool
     */
    public function isInvalid(): bool
    {
        return !$this->isValid();
    }

    /**
     * Controller for the blockfield snippet
     *
     * @return array
     */
    public function controller(): array
    {
        return [
            'formfield'   => $this,
            'content' => $this->content(),
            'id'      => $this->id(),
            'prev'    => $this->prev(),
            'next'    => $this->next()
        ];
    }
}