<?php

namespace App\Services;

use App\Models\Notifications;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class NotificationService
{
    const CHANNELS = [
        'sms' => 'SMS',
        'email' => 'Email',
        'whatsapp' => 'WhatsApp',
        'system' => 'System'
    ];

    const TEMPLATES = [
        'tagihan_ready' => [
            'subject' => 'Tagihan PDAM Bulan {{bulan}}',
            'message' => 'Tagihan PDAM Anda sebesar Rp {{jumlah}} telah tersedia. Jatuh tempo: {{tanggal}}. Info: {{info_tambahan}}'
        ],
        'sla_warning' => [
            'subject' => 'Peringatan SLA - {{proses}}',
            'message' => 'Proses {{proses}} untuk {{pelanggan}} akan melewati batas waktu SLA dalam {{jam}} jam.'
        ],
        'instalasi_selesai' => [
            'subject' => 'Instalasi PDAM Selesai',
            'message' => 'Instalasi PDAM Anda telah selesai. Nomor meter: {{nomor_meter}}. Layanan aktif mulai {{tanggal}}.'
        ],
        'pengaduan_update' => [
            'subject' => 'Update Pengaduan #{{nomor}}',
            'message' => 'Pengaduan Anda #{{nomor}} status: {{status}}. {{keterangan}}'
        ]
    ];

    public function sendNotification(
        string $type,
        string $eventTrigger,
        string $recipient,
        array $templateData = [],
        ?string $pelangganId = null,
        ?string $referensiTable = null,
        ?string $referensiId = null
    ): string {
        // Create notification record
        $notification = Notifications::create([
            'tabel_referensi' => $referensiTable,
            'id_referensi' => $referensiId,
            'id_pelanggan' => $pelangganId,
            'type' => $type,
            'event_trigger' => $eventTrigger,
            'recipient' => $recipient,
            'subject' => $this->buildSubject($eventTrigger, $templateData),
            'message' => $this->buildMessage($eventTrigger, $templateData),
            'template_data' => json_encode($templateData),
            'status' => 'pending'
        ]);

        // Queue the notification for sending
        Queue::push(new \App\Jobs\SendNotificationJob($notification->id_notification));

        return $notification->id_notification;
    }

    public function sendMultiChannelNotification(
        array $channels,
        string $eventTrigger,
        array $templateData = [],
        ?Pelanggan $pelanggan = null,
        ?string $referensiTable = null,
        ?string $referensiId = null
    ): array {
        $notifications = [];

        foreach ($channels as $channel) {
            $recipient = $this->getRecipientForChannel($channel, $pelanggan);

            if ($recipient) {
                $notifications[] = $this->sendNotification(
                    $channel,
                    $eventTrigger,
                    $recipient,
                    $templateData,
                    $pelanggan?->id_pelanggan,
                    $referensiTable,
                    $referensiId
                );
            }
        }

        return $notifications;
    }

    private function getRecipientForChannel(string $channel, ?Pelanggan $pelanggan): ?string
    {
        if (!$pelanggan) return null;

        return match($channel) {
            'sms', 'whatsapp' => $pelanggan->nomor_hp,
            'email' => $pelanggan->email,
            default => null
        };
    }

    private function buildSubject(string $eventTrigger, array $data): string
    {
        $template = self::TEMPLATES[$eventTrigger]['subject'] ?? $eventTrigger;
        return $this->replaceTemplateVars($template, $data);
    }

    private function buildMessage(string $eventTrigger, array $data): string
    {
        $template = self::TEMPLATES[$eventTrigger]['message'] ?? 'Update untuk {{pelanggan}}';
        return $this->replaceTemplateVars($template, $data);
    }

    private function replaceTemplateVars(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }

    public function markAsDelivered(string $notificationId): bool
    {
        $notification = Notifications::find($notificationId);

        if ($notification) {
            $notification->update([
                'status' => 'delivered',
                'delivered_at' => now()
            ]);
            return true;
        }

        return false;
    }

    public function markAsFailed(string $notificationId, string $errorMessage): bool
    {
        $notification = Notifications::find($notificationId);

        if ($notification) {
            $notification->update([
                'status' => 'failed',
                'error_message' => $errorMessage,
                'retry_count' => $notification->retry_count + 1
            ]);
            return true;
        }

        return false;
    }

    public function resendFailedNotifications(): int
    {
        $failedNotifications = Notifications::where('status', 'failed')
            ->where('retry_count', '<', 3)
            ->get();

        foreach ($failedNotifications as $notification) {
            Queue::push(new \App\Jobs\SendNotificationJob($notification->id_notification));
        }

        return $failedNotifications->count();
    }
}
