<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150', 'unique:schools,name,' . $this->school->id],
            'zone' => ['required', 'int'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام مدرسه الزامی است',
            'name.max' => 'حداکثر تعداد نویسه نام مدرسه ۱۵۰ عدد است',
            'name.unique' => 'نام مدرسه باید منحصر به فرد باشد',

            'zone.required' => 'تعیین منطقه مدرسه الزامی است',
            'zone' => 'منطقه انتخابی نامعتبر است',
        ];
    }
}
