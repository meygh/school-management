<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 * @package App\Http\Requests\Organization
 *
 * Attributes:
 * @property int $id
 * @property string $name
 * @property int|null $zone_id
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
            'name' => ['required', 'string', 'max:150', 'unique:departments,name'],
            'zone_id' => ['sometimes', 'nullable', 'int', 'exists:zones,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام دپارتمان الزامی است',
            'name.max' => 'حداکثر تعداد نویسه نام دپارتمان ۱۵۰ عدد است',
            'name.unique' => 'نام دپارتمان باید منحصر به فرد باشد',

            'zone_id' => 'منطقه انتخابی نامعتبر است',
//            'zone_id.exists' => 'منطقه مورد نظر تعریف نشده است',
        ];
    }
}
