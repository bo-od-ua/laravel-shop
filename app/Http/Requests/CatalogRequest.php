<?php

namespace App\Http\Requests;

use App\Rules\CategoryParent;
use Illuminate\Foundation\Http\FormRequest;

class CatalogRequest extends FormRequest
{
    protected $entity= [];
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
        switch ($this->method()){
            case 'POST':
                return $this->createItem();
            case 'PUT':
            case 'PATCH':
                return $this->updateItem();
        }
    }

    protected function createItem(){
        return [
            'name'=> [
                'required',
                'max:100',
            ],
            'slug'=> [
                'required',
                'max:100',
                'unique:'.$this->entity['table'].',slug',
                'regex:~^[-_a-z0-9]+$~i',
            ],
            'image'=> [
                'mimes:jpeg,jpg,png',
                'max:5000',
            ]
        ];
    }

    protected function updateItem(){
        $model= $this->route($this->entity['name']);
        return [
            'name'=> [
                'required',
                'max:100',
            ],
            'slug'=> [
                'required',
                'max:100',
                'unique:'.$model->id.',slug',
                'regex:~^[-_a-z0-9]+$~i',
            ],
            'image'=> [
                'mimes:jpeg,jpg,png',
                'max:5000',
            ]
        ];
    }
}
