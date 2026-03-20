<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'status' => $this->status,
            'issue_date' => $this->issue_date,
            'valid_until' => $this->valid_until,
            'subtotal_amount' => $this->subtotal_amount,
            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,
            'client' => $this->whenLoaded('client'),
            'responsible_user' => $this->whenLoaded('responsibleUser'),
            'template' => $this->whenLoaded('template'),
            'items' => $this->whenLoaded('items'),
            'versions' => $this->whenLoaded('versions'),
            'status_histories' => $this->whenLoaded('statusHistories'),
        ];
    }
}
