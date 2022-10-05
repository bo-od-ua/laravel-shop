<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryCatalogRequest extends FormRequest
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
        switch($this->method()){
            case 'POST':
                return [
                    'parent_id'=> 'integer',
                    'name'=>      'required|max:100',
                    'slug'=>      'required|max:100|unique:categories,slug|regex:~^[-_a-z0-9]+$~i',
                    'image'=>     'mimes:jpeg,jpg,png|max:5000',
                ];
            case 'PUT':
            case 'PATCH':
                $model= $this->route('category');
                $id= $model->id;
                return[
                    'parent_id'=> 'integer',
                    'name'=>      'required|max:100',
                    'slug'=>      'required|max:100|unique:categories,slug,'.$id.',id|regex:~^[-_a-z0-9]+$~i',
                    'image'=>      'mimes:jpeg,jpg,png|max:5000'
                ];
        }
    }
}
