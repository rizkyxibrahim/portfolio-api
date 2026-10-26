<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'reminder_at' => $this->reminder_at?->toISOString(),
            'is_sent' => $this->is_sent,
            'is_completed' => $this->is_completed,
            'recurring' => $this->recurring,
            'recurring_pattern' => $this->recurring_pattern,
            'delivery_status' => $this->delivery_status,
            'retry_count' => $this->retry_count,
            'max_retries' => $this->max_retries,
            'dedupe_key' => $this->dedupe_key,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}