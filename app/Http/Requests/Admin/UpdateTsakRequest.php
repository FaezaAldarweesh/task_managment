<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTsakRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id|nullable',
            'title' => 'unique:tasks,id', 
            'description' => 'string|min:10|max:150',
            'priority' => 'in:High,Medium,Low',
            'due_date' => 'date',
            'status' => 'nullable|in:Assigned,Received,Done',
        ];
    }
    //===========================================================================================================================
    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'status' => 'error 422',
            'message' => 'فشل التحقق يرجى التأكد من المدخلات',
            'errors' => $validator->errors(),
        ]));
    }
    //===========================================================================================================================
    protected function passedValidation()
    {
        //تسجيل وقت إضافي
        Log::info('تمت عملية التحقق بنجاح في ' . now());

    }
    //===========================================================================================================================
    public function attributes(): array
    {
        return [
            'user_id' => 'اسم الموظف',
            'title' => 'عنوان المهمة',
            'description' => 'وصف المهمة',
            'priority' => 'درجة أهمية المهمة',
            'due_date' => 'تاريخ التسليم',
            'status' => 'حالة المهمة',
        ];
    }
    //===========================================================================================================================

    public function messages(): array
    {
        return [
            'unique' => ':attribute  موجود سابقاً , يجب أن يكون :attribute غير مكرر',
            'max' => 'الحد الأقصى لطول  :attribute هو 50 حرف',
            'min' => 'الحد الأدنى لطول :attribute على الأقل هو 2 حرف',
            'integer' => 'يجب أن يكون الحقل :attribute من نمط int',
            'exists' => 'يجب أن يكون :attribute موجودا ضمن جدول الموظفين',
            'string' => 'يحب أن يكون الحقل :attribute يحوي محارف',
            'date' => 'يجب أن يكون الحقل :attribute تاريخاً',
            'priority.in' => 'يجب أن تكون قيمة الحقل إحدى القيم التالية : High,Medium,Low',
            'status.in' => 'يجب أن تكون قيمة الحقل إحدى القيم التالية : Assigned,Recived,Done',
        ];
    }
}
