<?php

namespace App\Http\Requests;


class BrandCatalogRequest extends CatalogRequest
{
    protected $entity= [
        'name'=>  'category',
        'table'=> 'categories',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return parent::rules();
    }

    public function createItem()
    {
        $rules= [];

        return array_merge(parent::createItem(), $rules);
    }

    public function updateItem()
    {
        $rules= [];

        return array_merge(parent::updateItem(), $rules);
    }
}
