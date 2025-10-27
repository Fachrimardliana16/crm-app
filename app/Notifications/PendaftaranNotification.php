<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action as FilamentAction;

class PendaftaranNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $tanggal;
    protected $url;

    /**
     * Buat notifikasi baru.
     */
    public function __construct($user, $tanggal, $url)
    {
        $this->user = $user;
        $this->tanggal = $tanggal;
        $this->url = $url;
    }

    /**
     * Tentukan channel pengiriman notifikasi.
     */
    public function via(object $notifiable): array
    {
        // Gunakan database, bukan email
        return ['database'];
    }

    /**
     * Data yang disimpan ke tabel 'notifications'.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pendaftaran pelanggan telah dibuat',
            'body' => "Tanggal: {$this->tanggal}\nOleh: {$this->user->name}",
            'url' => $this->url,
            'button' => 'Check detail data pendaftaran',
        ];
    }

    /**
     * Tampilkan juga sebagai popup Filament Notification.
     */
    public function toFilamentNotification($notifiable)
    {
        return FilamentNotification::make()
            ->title('Pendaftaran pelanggan telah dibuat')
            ->body("Tanggal: {$this->tanggal}\nOleh: {$this->user->name}")
            ->actions([
                FilamentAction::make('Lihat Detail')
                    ->url($this->url)
                    ->button(),
            ])
            ->success()
            ->sendToDatabase($notifiable); // otomatis simpan ke database
    }
}
