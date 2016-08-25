<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Pushbullet\PushbulletChannel;
use NotificationChannels\Pushbullet\PushbulletMessage;

class PushBullet extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [PushbulletChannel::class];
    }

    /**
     * Get the pushbullet representation of the notification.
     *
     * @return \NotificationChannels\Pushbullet\PushbulletMessage
     */
    public function toPushbullet()
    {
        if ($this->url) {
            return PushbulletMessage::create($this->message)
                ->link()
                ->title($this->title)
                ->url($this->url);
        } else {
            return PushbulletMessage::create($this->message)
                ->note()
                ->title($this->title);
        }
    }

}
