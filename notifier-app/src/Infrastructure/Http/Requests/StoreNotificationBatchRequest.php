<?php

namespace Src\Infrastructure\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Domain\NotificationBatch\ValueObjects\Priority;

class StoreNotificationBatchRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'channel' => ['required', 'string', Rule::in(['email', 'sms', 'push'])],
            'subject' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'priority' => ['sometimes', 'string', Rule::in(Priority::cases())],
            'recipient_ids' => ['required', 'array'],
            'recipient_ids.*' => ['int'],
        ];
    }
}
