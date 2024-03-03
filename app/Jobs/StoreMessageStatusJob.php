<?php

namespace App\Jobs;

use App\Events\StoreMessageStatusEvent;
use App\Models\MessageStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreMessageStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $message;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $message)
    {
        //
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data['user_ids'] as $userId) {
            MessageStatus::create([
                'chat_id' => $this->data['chat_id'],
                'message_id' => $this->message->id,
                'user_id' => $userId
            ]);

            $count = MessageStatus::where('chat_id', $this->data['chat_id'])
                ->where('user_id', $userId)
                ->where('is_read', false)
                ->count();

            broadcast(new StoreMessageStatusEvent($count, (int) $this->data['chat_id'], (int) $userId, $this->message));
        }
    }
}
