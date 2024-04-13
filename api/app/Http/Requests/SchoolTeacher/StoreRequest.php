<?php

namespace App\Http\Requests\SchoolTeacher;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class StoreRequest
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
class StoreRequest extends FormRequest
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
            'school_id' => ['required', 'nullable', 'int', 'exists:schools,id'],
            'classroom_id' => ['required', 'nullable', 'int', 'exists:school_classrooms,id'],
            'user_id' => ['required', 'nullable', 'int', 'exists:users,id',
                Rule::unique('school_teachers')->where(function ($query) {
                    return $query->where('classroom_id', request()->input('classroom_id'))
                        ->where('user_id', request()->input('user_id'));
                })],
            'status' => ['sometimes', 'nullable', Rule::enum(Status::class)],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ((!$this->school_id || !$this->classroom_id)  && $this?->classroom) {
            $this->merge([
                'school_id' => $this->classroom->school_id,
                'classroom_id' => $this->classroom->id
            ]);
        }

        if (!$this->user_id && $this?->userTeacher) {
            $user_id = $this->userTeacher->id;

            if (!$this->userTeacher->isTeacher()) {
                $user_id = 'invalid';
            }

            $this->merge(['user_id' => $user_id]);
        }

        // Set principle status active if didn't set
        if (is_null($this->status)) {
            $this->merge([
                'status' => Status::ACTIVE->value,
            ]);
        }
    }

    public function messages()
    {
        return [
            'school_id.required' => 'انتخاب مدرسه الزامی است',
            'school_id' => 'مدرسه انتخابی معتبر نیست',

            'classroom_id.required' => 'انتخاب کلاس الزامی است',
            'classroom_id' => 'کلاس انتخابی معتبر نیست',

            'user_id.required' => 'انتخاب کاربر الزامی است',
            'user_id.unique' => 'این کاربر قبلا معلم این کلاس شده است',
            'user_id' => 'کاربر انتخابی یک معلم معتبر نیست',

            'status' => 'وضعیت ارسال شده نامعتبر است',
        ];
    }
}
