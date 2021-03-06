<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralProductRequest extends FormRequest
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
            'name' => 'required|max:100',
            'slug' => 'required|unique:products,slug',
            'description' => 'required|max:1000',
            'short_description' => 'nullable|max:500',
            'categories' => 'required|array|min:1',
            'categories.*' => 'numeric|exists:categories,id',
            'tags' => 'nullable|array|min:1',
            'tags.*' => 'numeric|exists:tags,id',
            'brand_id' => 'required|exists:brands,id',

        ];
    }

    public function messages()
    {
        return [
            'required'=>__('admin/validation.required'),
            'name.max'=>__('admin/validation.nmax'),
            'description.max'=>__('admin/validation.dmax'),
            'short_description.max'=>__('admin/validation.smax'),
            'slug.unique'=>__('admin/validation.unique2'),
            'exists'=>__('admin/validation.exists'),
            'min'=>__('admin/validation.min1'),



        ];
    }
}
