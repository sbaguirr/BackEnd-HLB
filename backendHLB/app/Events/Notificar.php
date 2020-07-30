<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Solicitud;

class Notificar implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $username;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($username)
    {
        $this->username = $username;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('solicitud');
    }
 
    public function broadcastAs() //Nombre del evento
  {
      return 'notificar';
  } 

  public function broadcastWith() { //Lo específico que quiero enviar
    return [
      'titulo' => "HLB-Servicio web de solicitudes",
      'mensaje' =>  "El usuario {$this->username} ha realizado una nueva solicitud",
      'pendientes' => Solicitud::contar_pendientes(),
    ];
  }
}
