<?php

namespace App\Http\Requests\Proposal;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'responsible_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'proposal_template_id' => ['nullable', 'integer', 'exists:proposal_templates,id'],
            'status' => ['required', 'string'],
            'issue_date' => ['required', 'date'],
            'valid_until' => ['nullable', 'date'],
            'title' => ['nullable', 'string', 'max:255'],
            'general_notes' => ['nullable', 'string'],
            'currency' => ['nullable', 'string', 'size:3'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'integer', 'exists:products,id'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'numeric'],
            'items.*.unit_price' => ['required', 'numeric'],
            'items.*.discount_amount' => ['nullable', 'numeric'],
        ];
    }
}
