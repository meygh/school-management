<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (
            Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->boolean('remember')) ||
            Auth::attempt(['username' => $this->email, 'password' => $this->password], $this->boolean('remember'))
        ) {
            RateLimiter::clear($this->throttleKey());

            return;
        }
        RateLimiter::hit($this->throttleKey());

        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => __('auth.failed'),
            ], 422)
        );
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => __('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ], 400)
        );
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->input('email')) . '|' . $this->ip()
        );
    }

    public function messages()
    {
        return [
            'email.required' => 'ایمیل یا نام کاربری الزامی است',
//            'email.email' => 'مقدار فیلد ایمیل باید یک آدرس ایمیل معتبر باشد.',
            'password.required' => 'فیلد گذرواژه ضروری است',
        ];
    }
}
