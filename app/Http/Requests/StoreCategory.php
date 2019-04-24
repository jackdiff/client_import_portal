<?php

namespace App\Http\Requests;

class StoreCategory extends AppBaseRequest
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
            'name' => 'required|unique:categories|max:200'
        ];
    }

    public function messages() {
        return [
        'name.required' => __('Tên danh mục không được trống'),
        'name.max'  => __('Tên danh mục không được quá 200 kí tự'),
    ];
    }
}
