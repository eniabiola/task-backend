<?php

namespace App\Http\Requests;

use App\Http\DTO\CreateTaskDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date_format:Y-m-d\TH:i|after:now',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'due_date.date_format' => "Date format must be Y-m-d\TH:i such as 01/31/2050 01:01 AM",
        ];
    }

    public function toDTO(): CreateTaskDTO
    {
        return CreateTaskDTO::fromRequest(
            $this->validated(),
            $this->user()->id
        );
    }
}
