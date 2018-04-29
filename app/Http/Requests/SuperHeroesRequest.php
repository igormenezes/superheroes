<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuperHeroesRequest extends FormRequest
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
        $rules = [
            'real_name' => 'required',
            'origin_description' => 'required',
            'superpowers' => 'required',
            'catch_phrase' => 'required',
            'images' => 'required'
        ];

        if(!$this->has('id')){
          $rules += ['nickname' => 'required|unique:superheroes']; 
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => ':attribute não pode estar vazio.',
            'unique' => 'Nickname já existe'
        ];
    }
}
