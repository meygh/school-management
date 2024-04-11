<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class PatchUserProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'neighborhoodId' => ['required', 'int', 'exists:neighborhoods,id'],

            'avatar' => ['sometimes', 'nullable', 'mimes:jpg,png'],
            'personalPhoto' => ['sometimes', 'nullable', 'mimes:jpg'],

            'nationalCode' => ['sometimes', 'required', 'string', 'min:10', 'max:10', 'regex:/^[0-9]{10}$/'],

            'mobile' => ['sometimes', 'required', 'string', 'regex:/^09+[0-9]{9}$/'],
            'phone' => ['sometimes', 'string', 'regex:/^[0-9]{9,11}$/'],

            'address' => ['sometimes', 'string'],
            'zipcode' => ['sometimes', 'string', 'min:10', 'max:10', 'regex:/^[0-9]{10}$/'],

            'workAddress' => ['sometimes', 'string'],
            'workPhone' => ['sometimes', 'string', 'regex:/^[0-9]{9,11}$/'],

            'cv' => ['sometimes', 'nullable', 'string'],

            'educationCertificate' => ['sometimes', 'nullable', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' => 'تصویر آواتار فقط از نوع JPG و PNG مجاز است',
            'personalPhoto.mimes' => 'تصویر پرسنلی فقط از نوع JPG مجاز است',

            'neighborhoodId.required' => 'لطفا محله‌ای که در آن سکونت دارید انتخاب کنید',
            'neighborhoodId' => 'محله‌ی انتخابی نامعتبر است',

            'nationalCode.required' => 'درج کد ملی صحیح الزامی است',
            'nationalCode' => 'کد ملی وارد شده نامعتبر است',

            'mobile.required' => 'تلفن همراه الزامی است',
            'mobile' => 'شماره موبایل را به درستی در قالب 09121234567',
            'phone' => 'تلفن نامعتبر است',
            'workPhone' => 'تلفن محل کار نامعتبر است',
            'zipcode' => 'کدپستی نامعتبر است',

            'educationCertificate' => 'مدرک تحصیلی نامعتبر است',
        ];
    }
}
