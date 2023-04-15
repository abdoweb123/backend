<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'phone' => 'required|unique:posts|max:255',
             'name' => 'required',
             'phone_intreal' => 'required',
             'email' => 'required',
             'birth_date' => 'required',
             'image' => 'required',
             'ssid_driver' => 'required',
             'address' => 'required',
             'imgcert' => 'required',
             'passport' => 'required',
             'ssidfront' => 'required',
             'ssidback' => 'required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
