<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toOwnerArray(Request $request, bool $isOwner = false): array
    {
        return [
            'id' => $this->id,
            'chat_id' => $this->chat_id,
            'user_name' => $this->user->name,
            'body' => $this->body,
            'time' => $this->getTimeAgo($this->time),
            'is_owner' => $isOwner
        ];
    }

    private function getTimeAgo($carbonObject): array|string
    {
        return str_ireplace(
            ['seconds', 'second', 'minutes', 'minute', 'hours', 'hour', 'days', 'day', 'weeks', 'week', 'ago'],
            ['секунды', 'секунд', 'минуты', 'минут', 'часов', 'час', 'дней', 'день', 'недель', 'неделя', 'назад'],
            $carbonObject
        );
    }
}
