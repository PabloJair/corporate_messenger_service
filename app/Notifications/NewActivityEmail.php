<?php
namespace App\Notifications;
use App\AssigmentOfActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
class NewActivityEmail extends Notification
{


    function __construct(AssigmentOfActivity $assigmentActivity){
        $this->assigmentActivity =$assigmentActivity;
    }

    private  $assigmentActivity;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Te informamos que se te asigno una notificación ')
            ->line('donde tendras que realizar: "'.$this->assigmentActivity->notes.'"')
            ->line('E inicia el dia :'.$this->assigmentActivity->start_date."a las "
                .$this->assigmentActivity->start_time." y termina: ".$this->assigmentActivity->end_date." a las "
                .$this->assigmentActivity->end_time)
            ->line('Te recomendamos verificar tu actividad dentro de la app')
            ->greeting('Hola: '.$notifiable->name." ".$notifiable->paternal_surname)->subject('Actividad asignada');

    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
