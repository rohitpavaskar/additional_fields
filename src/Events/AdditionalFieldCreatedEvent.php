<?php

namespace Rohitpavaskar\AdditionalField\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdditionalFieldCreatedEvent {

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    public $field;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($custom_field) {
        $this->field = $custom_field;
    }

}
