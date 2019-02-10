<?php

namespace NovaConditionalFields;

use Laravel\Nova\Panel;

use Laravel\Nova\Http\Requests\NovaRequest;

class Condition extends Panel
{
    public $conditions = [];

    public $storeCondition = false;

    public $component = 'conditional';

    public function fill(NovaRequest $request, $model)
    {
        $attributes = collect($this->conditions)->pluck('fields.*.attribute')->first();

        foreach($attributes as $attribute) {
            $this->fillInto($request, $model, $attribute, $attribute);
        }
    }

    public function resolve($resource, $attribute = null)
    {
        foreach($this->conditions as $condition)
        {
            foreach($condition['fields'] as $field)
            {
                $field->resolve($resource, $attribute);
            }
        }

        $this->withMeta([
            'conditional' => true,
            'parent' => $this->attribute,
            'conditions' => $this->conditions
        ]);
    }

    public function fieldsWhen($value, $fields)
    {
        $this->conditions[] = [
            'value' => $value,
            'fields' => $fields
        ];

        return $this;
    }
}