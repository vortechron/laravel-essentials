<?php

namespace Vortechron\Essentials\Core;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Vortechron\Essentials\Models\Config;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as BaseNoti;

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
        extract($this->reassignWithProxy());
        return (new MailMessage)
            ->subject(eval($this->getSubject()))
            ->markdown(
                $this->base_path . '.' . (new \ReflectionClass($this))->getShortName(), 
                $this->getData()
            );
    }

    protected function reassignWithProxy()
    {
        $data = collect($this->getData());
        
        return $data->map(function ($value, $key) {
            return new GetterProxy($value);
        })->toArray();
    }

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

    public function getDataDescription()
    {
        return [
            //
        ];
    }

    public function getSlug()
    {
        return Str::slug((new \ReflectionClass($this))->getShortName());
    }

    public function getBasePath()
    {
        return $this->base_path;
    }

    public function getSubject()
    {
        $subject = Config::find("notifications.{$this->getSlug()}");

        return $subject ?? $this->subject;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
