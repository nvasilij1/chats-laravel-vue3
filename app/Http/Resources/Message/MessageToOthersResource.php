<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;

class MessageToOthersResource extends AbstractMessageResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->toOwnerArray($request);
    }
}
