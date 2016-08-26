<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Pushover\PushoverChannel;
use NotificationChannels\Pushover\PushoverMessage;

class Pushover extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $url;

    /**
     * Create a new notification instance.
     *
     * @param $title
     * @param $content
     * @param string $url
     */
    public function __construct($title = '', $content, $url = '')
    {
        $this->title = $title;
        $this->content = $content;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [PushoverChannel::class];
    }

    /**
     * Get the pushover representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \NotificationChannels\Pushover\PushoverMessage
     */
    public function toPushover($notifiable)
    {
        return PushoverMessage::create($this->content)
            ->title($this->title)
            ->sound('pushover')
            ->normalPriority()
            ->url($this->url);
    }

}
