<?php

namespace App\Http\Requests;

class AnalyzeImport extends AppBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fileImport' => 'required|mimexls'
        ];
    }

    public function messages() {
        return [
            'fileImport.required' => __('Không có file được chọn'),
            'fileImport.mimexls'  => __('Không phải là file excel'),
        ];
    }
}
