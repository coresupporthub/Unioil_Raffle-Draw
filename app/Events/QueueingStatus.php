<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\QueueingStatusModel;

class QueueingStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {

    }

    public function broadcastWith():array{

        $q = QueueingStatusModel::where('status', 'inprogress')->first();

        if($q){
            if($q->items + 1 != $q->total_items){
                $q->update([
                    'items' => $q->items + 1,
                ]);

                $status = 'inprogress';
            }else{
                $q->update([
                    'items' => $q->items + 1,
                    'status'=> 'done'
                ]);

                $status = 'done';
            }

            $items = $q->items + 1;
            $progress = "$items/$q->total_items";
        }
        return ['success'=> true, 'queue_id'=> $q->queue_id ?? null, 'status'=> $status ?? null, 'progress'=> $progress ?? null];
     }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('queueingstatus'),
        ];
    }
}
