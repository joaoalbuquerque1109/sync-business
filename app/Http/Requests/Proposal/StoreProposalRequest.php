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
            'client_id' => ['required', 'integer'],
            'responsible_user_id' => ['required', 'integer'],
            'proposal_template_id' => ['nullable', 'integer'],
            'status' => ['required', 'string'],
            'issue_date' => ['required', 'date'],
            'valid_until' => ['nullable', 'date'],
            'items' => ['array'],
            'items.*.quantity' => ['required', 'numeric'],
            'items.*.unit_price' => ['required', 'numeric'],
        ];
    }
}
