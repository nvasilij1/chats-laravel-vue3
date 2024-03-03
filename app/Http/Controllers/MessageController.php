<?php

namespace App\Http\Controllers;

use App\Events\StoreMessageEvent;
use App\Events\StoreMessageStatusEvent;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Jobs\StoreMessageStatusJob;
use App\Models\Message;
use App\Models\MessageStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $message = Message::create([
            'chat_id' => $data['chat_id'],
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);


        foreach ($data['user_ids'] as $userId) {
            MessageStatus::create([
                'chat_id' => $data['chat_id'],
                'message_id' => (int) $message->id,
                'user_id' => $userId
            ]);

            $count = MessageStatus::where('chat_id', $data['chat_id'])
                ->where('user_id', $userId)
                ->where('is_read', false)
                ->count();

            broadcast(new StoreMessageStatusEvent($count, $data['chat_id'], (int) $userId, $message));
        }

       // StoreMessageStatusJob::dispatch($data, $message)->onQueue('store_messages');

        broadcast(new StoreMessageEvent($message))->toOthers();


        return MessageResource::make($message)->resolve();
    }
}
