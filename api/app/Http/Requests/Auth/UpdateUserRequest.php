<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
//            'username' => ['required', 'string', 'min:8', 'max:20', 'unique:users,username,' . $this->user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user->id],

            'password' => 'required|confirmed|min:8', // Should match with password_confirmation
        ];
    }

    public function messages()
    {
        return [
            'firstname' => 'درج نام شما الزامی است',
            'lastname' => 'درج نام خانوادگی الزامی است',

            'email.required' => 'وارد کردن یک ایمیل معتبر الزامی است',
            'email.email' => 'ایمیل وارد شده معتبر نیست',
            'email.unique' => 'این ایمیل قبل در سامانه ثبت شده است',

            'username.required' => 'لطفا یک نام کاربری مناسب بین ۸ تا ۲۰ نویسه وارد کنید',
            'username.unique' => 'نام کاربری شما قبلا در این سامانه انتخاب شده لطفا نام دیگری وارد کنید',
            'username.min' => 'نام کاربری باید حداقل ۸ نویسه باشد',
            'username.max' => 'نام کاربری نباید بیشتر از ۲۰ نویسه باشد',

            'password.required' => 'تعیین گذرواژه الزامی است',
            'password.min' => 'گذرواژه باید حداقل ۸ نویسه باشد',

            'profile.national_code' => 'لطفا کد ملی را به درستی وارد کنید',
            'profile.mobile' => 'شماره موبایل را به درستی در قالب 09121234567',
        ];
    }
}
