<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'username' => ['required', 'string', 'regex:/^[a-zA-Z0-9\-]+$/', 'min:8', 'max:20', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],

            'password' => 'required|confirmed|min:8', // Should match with password_confirmation

            'status' => ['sometimes', 'nullable', 'int', Rule::enum(UserStatus::class)]
        ];
    }

    protected function prepareForValidation(): void
    {
        // Set user as student if didn't set
        if (is_null($this->status)) {
            $this->merge([
                'status' => UserStatus::VISITOR->value,
            ]);
        }
    }

    public function messages()
    {
        return [
            'firstname' => 'درج نام شما الزامی است',
            'lastname' => 'درج نام خانوادگی الزامی است',

            'email.required' => 'وارد کردن یک ایمیل معتبر الزامی است',
            'email.email' => 'ایمیل وارد شده معتبر نیست',
            'email.unique' => 'این ایمیل قبلا ثبت شده، اگر اطلاعات ورود خود را فراموش کردید می توانید آن را بازیابی کنید',

            'username.required' => 'لطفا یک نام کاربری مناسب بین ۸ تا ۲۰ نویسه وارد کنید',
            'username.unique' => 'نام کاربری شما قبلا در این سامانه انتخاب شده لطفا نام دیگری وارد کنید',
            'username.min' => 'نام کاربری باید حداقل ۸ نویسه باشد',
            'username.max' => 'نام کاربری نباید بیشتر از ۲۰ نویسه باشد',
            'username.regex' => 'نام کاربری فقط میتواند شامل حروف، اعداد و - باشد',

            'password.required' => 'تعیین گذرواژه الزامی است',
            'password.min' => 'گذرواژه باید حداقل ۸ نویسه باشد',
            'password.confirmed' => 'تایید گذرواژه با گذرواژه یکسان نیست',
        ];
    }
}
