<?php

namespace Vortechron\Essentials\Core;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification as BaseNoti;
use Illuminate\Notifications\Messages\MailMessage;

class Notification extends BaseNoti
{
    use Queueable;

    protected $base_path = 'mail';

    protected $description = '';

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown(
            $this->base_path . '.' . (new \ReflectionClass($this))->getShortName(), 
            $this->getData()
        );
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

    public function getData()
    {
        return [
            //
        ];
    }

    public function getBasePath()
    {
        return $this->base_path;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
