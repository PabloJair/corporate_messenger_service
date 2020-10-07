<?php


namespace App;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;
use NotificationChannels\Fcm\Resources\FcmOptions;


class NewNotification extends Notification
{

    public function via($notifiable)
    {
        return [FcmChannel::class];

    }

    function __construct(AssigmentOfActivity $assigmentActivity){
        $this->assigmentActivity =$assigmentActivity;
    }

        private  $assigmentActivity;
    public function toFcm($notifiable)
    {



        $fcm= FcmMessage::create()

            ->setData(['data1' => 'value', 'data2' => 'value2'])
            ->setToken($notifiable->device_id)
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('Actividad nueva Asignada')
                ->setBody('Inicia el dia : '.$this->assigmentActivity->start_date." y termina: ".$this->assigmentActivity->end_date)
                ->setImage('https://blogsterapp.com/wp-content/uploads/2018/03/como-elaborar-un-calendario-editorial.jpg'))

            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
            )->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios')));



        return $fcm;
    }


}
