<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class WorkflowNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $body;
    protected $icon;
    protected $color;
    protected $actionUrl;
    protected $actionLabel;
    protected $filamentTitle;
    protected $filamentBody;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $body, $icon = 'heroicon-o-bell', $color = 'primary', $actionUrl = null, $actionLabel = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->icon = $icon;
        $this->color = $color;
        $this->actionUrl = $actionUrl;
        $this->actionLabel = $actionLabel;
        $this->filamentTitle = $title;
        $this->filamentBody = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->body)
            ->action($this->actionLabel ?? 'View', $this->actionUrl ?? url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'icon' => $this->icon,
            'color' => $this->color,
            'actions' => $this->actionUrl ? [
                [
                    'label' => $this->actionLabel ?? 'View',
                    'url' => $this->actionUrl,
                ]
            ] : [],
            'format' => 'filament',
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'icon' => $this->icon,
            'iconColor' => $this->color,
            'actions' => $this->actionUrl ? [
                [
                    'name' => $this->actionLabel ?? 'View',
                    'url' => $this->actionUrl,
                    'color' => 'primary',
                ]
            ] : [],
            'format' => 'filament',
        ];
    }
}
