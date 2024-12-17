<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class notificacion implements ShouldBroadcast
{
    use Queueable, Dispatchable, InteractsWithSockets, SerializesModels;

    public $usuarioId;
    public $notificacion;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($usuario, $notificacion)
    {
        //
        $this->usuarioId = $usuario;
        $this->notificacion= $notificacion;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("App.User." . $this->usuarioId);
    }
    public function broadcastWith()
    {
        return ['notificacion' => $this->notificacion,"usuario"=>$this->usuarioId];
    }
}
