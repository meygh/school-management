<?php

namespace App\Http\Requests\SchoolClassroom;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\SchoolClassroom
 *
 * Attributes:
 * @property int $id
 * @property int $school_id
 * @property string $name
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
            'name' => ['required', 'string', 'max:50', 'unique:school_classrooms,name,' . $this->id],
            'status' => ['sometimes', 'nullable', Rule::enum(Status::class)]
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->school_id  && $this?->school) {
            $this->merge(['school_id' => $this->school]);
        }

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

            'name.required' => 'نام مدرسه الزامی است',
            'name.max' => 'حداکثر تعداد نویسه نام مدرسه ۱۵۰ عدد است',
            'name.unique' => 'نام مدرسه باید منحصر به فرد باشد',

            'status' => 'وضعیت ارسال شده نامعتبر است',
        ];
    }
}
