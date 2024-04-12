<?php

namespace App\Http\Requests\Supervisor;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class PatchRequest
 * @package App\Http\Requests\SchoolStudent
 *
 * Attributes:
 * @property int $id
 * @property int $school_id
 * @property int $user_id
 * @property array|null $params
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class PatchRequest extends FormRequest
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
            'school_id' => ['sometimes', 'nullable', 'int', 'exists:departments,id'],
            'user_id' => ['required', 'nullable', 'int', 'exists:users,id',
                Rule::unique('department_users')->where(function ($query) {
                    return $query->where('school_id', request()->input('school_id') ?? $this->school_id)
                        ->where('user_id', request()->input('user_id'));
                })->ignore($this->id)],
            'params' => ['sometimes', 'string', 'nullable'],
            'status' => ['sometimes', 'nullable', Rule::enum(Status::class)]
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->status ?? Status::ACTIVE,
            'params' => is_array($this->params) ? json_encode($this->params) : $this->params
        ]);
    }

    public function messages()
    {
        return [
            'school_id' => 'دپارتمان انتخابی معتبر نیست',

            'user_id.required' => 'انتخاب کاربر الزامی است',
            'user_id.unique' => 'این کاربر قبلا مسئول این دپارتمان شده است',
            'user_id' => 'کاربر انتخابی معتبر نیست',

            'params' => 'پارامترهای ارسال شده نامعتبر است',
            'status' => 'وضعیت ارسال شده نامعتبر است',
        ];
    }
}
