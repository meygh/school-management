<?php

namespace App\Http\Requests\SchoolPrinciple;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class StoreRequest
 * @package App\Http\Requests\SchoolPrinciple
 *
 * Attributes:
 * @property int $id
 * @property int $school_id
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
            'user_id' => ['required', 'nullable', 'int', 'exists:users,id',
                Rule::unique('school_principles')->where(function ($query) {
                    return $query->where('school_id', request()->input('school_id'))
                        ->where('user_id', request()->input('user_id'));
                })],
            'status' => ['sometimes', 'required', Rule::enum(Status::class)],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Set principle status active if didn't set
        if (is_null($this->status)) {
            $this->merge([
                'status' => Status::ACTIVE->value,
            ]);
        }

        if (!$this->school_id  && $this?->school) {
            $this->merge(['school_id' => $this->school->id]);
        }

        if (!$this->user_id  && $this?->user) {
            $this->merge(['user_id' => $this->user->id]);
        }
    }

    public function messages()
    {
        return [
            'school_id.required' => 'انتخاب مدرسه الزامی است',
            'school_id' => 'مدرسه انتخابی معتبر نیست',

            'user_id.required' => 'انتخاب کاربر الزامی است',
            'user_id' => 'کاربر انتخابی معتبر نیست',
            'user_id.unique' => 'این کاربر قبلا مسئول این مدرسه شده است',

            'status' => 'وضعیت ارسال شده نامعتبر است',
        ];
    }
}
