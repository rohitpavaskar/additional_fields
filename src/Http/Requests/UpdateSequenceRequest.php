<?php

namespace Rohitpavaskar\AdditionalField\Http\Requests;

use Config;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSequenceRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'additional_fields.*.id' => 'required|exists:additional_fields,id',
        ];
    }

}
