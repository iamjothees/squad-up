<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Relations\Relation;

class MorphId implements ValidationRule, DataAwareRule
{
    protected $data = [];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $prefix = str($attribute)->before('_id');

        $morphModel = app(Relation::getMorphedModel($this->data["{$prefix}_type"]))::find($value);
        if (is_null($morphModel)) {
            $fail("The {$attribute} is not a valid {$prefix}.");
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }
}
