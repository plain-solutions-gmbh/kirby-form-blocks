<?php

namespace microman;

use Kirby\Cms\Block;
use Kirby\Toolkit\A;
use Kirby\Toolkit\V;
use Kirby\Toolkit\Str;
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
        $options = [];
        foreach ($field['content']['options'] as $option) {
         
            if ($field['type'] == 'formfields/checkbox' && $field['isFilled']) {
                $option['selected'] = in_array($option['slug'], array_keys($this->request())) ;
            } else if ($field['type'] != 'formfields/checkbox') {
                $default = isset($field['content']['default']) ? $field['content']['default'] : "";
                $option['selected'] = $field['isFilled'] ? ($this->request($field['content']['slug']) ==  $option['slug']) : $default  == $option['slug'];
            }
            array_push($options, $option);
        }
        return $options;
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
     * Convert width
     *
     * @param string $kind Example (1/4) grid:3, slash:1/4, dash:1-4 
     * 
     * @return string|null
     */
    public function width($kind = 'slash')
    {
        $val = $this->content()->width();

        if ($val == NULL) {
            return;
        }

        if ($kind == 'dash') {
            return str_replace('/', '-', $val);
        }

        if ($kind == 'grid') {
                return [
                    "1_1" => 12,
                    "1_2" => 6,
                    "1_3" => 4,
                    "1_4" => 3,
                    "2_3" => 8,
                    "3_4" => 9
                ][str_replace('/', '_', $val)];
        };
        
        return $val;
    }

    /**
     * Convert columns
     *
     * @param string $kind Example (1/4) grid:3, slash:1/4, dash:1-4 
     * 
     * @return string|null
     */
    public function columns($kind = "grid")
    {
        $val = $this->content()->columns()->value();

        if ($kind == 'grid') {
            return ($val > 0) ? 12 / $val : 1;
        }

        if ($kind == 'slash' && $val > 0) {
            return ($val > 0) ? "1/" . $val : 1;
        }

        return ($val > 0) ? "1-" . $val : 1;;
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
    public function selectedOptions($prop = 'value')
    {
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
        return $this->fields->isFilled();
    }

    /**
     * Get array of validator
     * 
     * @return array
     */
    public function validator(): array
    {

        if (!$this->isFilled)
            return [];
            
        $validator = $this->validate()->toStructure()->toArray();

        if ($this->required()->isTrue())
            array_push($validator, ['validate' => 'different', 'different' => '', 'msg' => $this->required_fail()->toString()]);

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