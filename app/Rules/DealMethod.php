<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DealMethod implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($param)
    {
        $this->method = $param;
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $deals = json_decode($value);
        foreach ($deals as $deal) {
            if ($deal == "Family Friday" || $deal == "Two Large" || $deal == "Two Medium" || $deal == "Two Small") {
                if ($this->method == "Collection") {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Deal conditions are invalid.';
    }
}
