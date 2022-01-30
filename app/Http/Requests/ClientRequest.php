<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'client.name' => ['nullable', 'string','min:3'],
            'client.last_name' => ['nullable','string','min:5'],
            'client.phone' => ['nullable', "regex:/^([0-9\s\-\+\(\)]*)$/", 'min:10'],
            'client.email' => ['nullable','email'],
            'client.birthday' => ['nullable','date_format:Y-m-d'],
            'client.mail_id' => ['nullable','exists:mails,id', "unique:clients,mail_id"],
            'client.phone_id' => ['nullable','exists:phones,id', "unique:clients,phone_id"],
            'client.assessment' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
          'client.mail_id.unique'=> 'З одного звернення можливо створити лише одну картку клієнта',
          'client.phone_id.unique'=> 'З одного звернення можливо створити лише одну картку клієнта',
        ];
    }
}
