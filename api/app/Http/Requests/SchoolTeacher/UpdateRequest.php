<?php

namespace App\Http\Requests\SchoolTeacher;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\SchoolTeacher
 *
 * Attributes:
 * @property int $id
 * @property int $school_id
 * @property int $classroom_id
 * @property int $user_id
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
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
            'school_id' => ['sometimes', 'required', 'nullable', 'int', 'exists:schools,id'],
            'classroom_id' => ['required', 'nullable', 'int', 'exists:school_classrooms,id'],
            'user_id' => ['required', 'nullable', 'int', 'exists:users,id',
                Rule::unique('school_students')->where(function ($query) {
                    return $query->where('school_classrooms', request()->input('school_classrooms'))
                        ->where('user_id', request()->input('user_id'));
                })->ignore($this->id)],
            'status' => ['sometimes', 'nullable', Rule::enum(Status::class)]
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->status ?? Status::ACTIVE,
        ]);
    }

    public function messages()
    {
        return [
            'school_id.required' => 'انتخاب مدرسه الزامی است',
            'school_id' => 'مدرسه انتخابی معتبر نیست',

            'classroom_id.required' => 'انتخاب کلاس الزامی است',
            'classroom_id' => 'کلاس انتخابی معتبر نیست',

            'user_id.required' => 'انتخاب کاربر الزامی است',
            'user_id' => 'کاربر انتخابی معتبر نیست',
            'user_id.unique' => 'این کاربر قبلا معلم این کلاس شده است',

            'status' => 'وضعیت ارسال شده نامعتبر است',
        ];
    }
}
