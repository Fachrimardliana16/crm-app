<?php

namespace App\Services;

use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\Survei;
use App\Models\Rab;
use App\Notifications\WorkflowNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class WorkflowNotificationService
{
    /**
     * Send notification when new pendaftaran is created
     */
    public function pendaftaranCreated(Pendaftaran $pendaftaran): void
    {
        $users = $this->getSupervisors();
        
        $actionUrl = route('filament.admin.resources.pendaftarans.view', $pendaftaran);
        
        NotificationFacade::send($users, new WorkflowNotification(
            title: 'Pendaftaran Baru',
            body: "Pendaftaran baru dengan nomor {$pendaftaran->nomor_registrasi} telah dibuat",
            icon: 'heroicon-o-plus-circle',
            color: 'success',
            actionUrl: $actionUrl,
            actionLabel: 'Lihat Detail'
        ));
    }

    /**
     * Send notification when pendaftaran status changes
     */
    public function pendaftaranStatusChanged(Pendaftaran $pendaftaran, string $oldStatus, string $newStatus): void
    {
        $statusMessages = [
            'draft' => 'dalam tahap draft',
            'proses' => 'sedang diproses',
            'survei' => 'menunggu survei',
            'rab' => 'dalam tahap RAB',
            'selesai' => 'telah selesai',
            'ditolak' => 'telah ditolak',
        ];

        $colors = [
            'draft' => 'gray',
            'proses' => 'warning',
            'survei' => 'info',
            'rab' => 'primary',
            'selesai' => 'success',
            'ditolak' => 'danger',
        ];

        $icons = [
            'draft' => 'heroicon-o-document',
            'proses' => 'heroicon-o-clock',
            'survei' => 'heroicon-o-map',
            'rab' => 'heroicon-o-calculator',
            'selesai' => 'heroicon-o-check-circle',
            'ditolak' => 'heroicon-o-x-circle',
        ];

        // Determine which users to notify based on status
        $users = collect();
        if ($newStatus === 'survei') {
            $users = $this->getSurveyors();
        } elseif ($newStatus === 'rab') {
            $users = $this->getFinanceTeam();
        } else {
            $users = $this->getSupervisors();
        }

        $actionUrl = route('filament.admin.resources.pendaftarans.view', $pendaftaran);

        NotificationFacade::send($users, new WorkflowNotification(
            title: 'Status Pendaftaran Diperbarui',
            body: "Pendaftaran {$pendaftaran->nomor_registrasi} {$statusMessages[$newStatus]}",
            icon: $icons[$newStatus] ?? 'heroicon-o-bell',
            color: $colors[$newStatus] ?? 'primary',
            actionUrl: $actionUrl,
            actionLabel: 'Lihat Detail'
        ));
    }

    /**
     * Send notification when new survei is created
     */
    public function surveiCreated(Survei $survei): void
    {
        $users = $this->getSurveyors();
        
        $actionUrl = route('filament.admin.resources.surveis.edit', $survei);

        NotificationFacade::send($users, new WorkflowNotification(
            title: 'Survei Baru Perlu Dikerjakan',
            body: "Survei untuk pendaftaran {$survei->pendaftaran->nomor_registrasi} telah dibuat",
            icon: 'heroicon-o-map',
            color: 'info',
            actionUrl: $actionUrl,
            actionLabel: 'Kerjakan Survei'
        ));
    }

    /**
     * Send notification when survei status changes
     */
    public function surveiStatusChanged(Survei $survei, string $oldStatus, string $newStatus): void
    {
        $statusMessages = [
            'draft' => 'dalam tahap draft',
            'menunggu_approval' => 'menunggu persetujuan',
            'approved' => 'telah disetujui',
            'rejected' => 'telah ditolak',
            'selesai' => 'telah selesai',
        ];

        $colors = [
            'draft' => 'gray',
            'menunggu_approval' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'selesai' => 'success',
        ];

        $icons = [
            'draft' => 'heroicon-o-document',
            'menunggu_approval' => 'heroicon-o-clock',
            'approved' => 'heroicon-o-check-circle',
            'rejected' => 'heroicon-o-x-circle',
            'selesai' => 'heroicon-o-check-circle',
        ];

        // Determine which users to notify
        $users = collect();
        if ($newStatus === 'menunggu_approval') {
            $users = $this->getSupervisors();
        } elseif ($newStatus === 'approved') {
            $users = $this->getFinanceTeam();
        } else {
            $users = $this->getSurveyors();
        }

        $actionUrl = route('filament.admin.resources.surveis.view', $survei);

        NotificationFacade::send($users, new WorkflowNotification(
            title: 'Status Survei Diperbarui',
            body: "Survei untuk pendaftaran {$survei->pendaftaran->nomor_registrasi} {$statusMessages[$newStatus]}",
            icon: $icons[$newStatus] ?? 'heroicon-o-bell',
            color: $colors[$newStatus] ?? 'primary',
            actionUrl: $actionUrl,
            actionLabel: 'Lihat Detail'
        ));
    }

    /**
     * Send notification when new RAB is created
     */
    public function rabCreated(Rab $rab): void
    {
        $users = $this->getFinanceTeam();
        
        $actionUrl = route('filament.admin.resources.rabs.view', $rab);

        NotificationFacade::send($users, new WorkflowNotification(
            title: 'RAB Baru Perlu Direview',
            body: "RAB untuk pendaftaran {$rab->pendaftaran->nomor_registrasi} telah dibuat",
            icon: 'heroicon-o-calculator',
            color: 'warning',
            actionUrl: $actionUrl,
            actionLabel: 'Review RAB'
        ));
    }

    /**
     * Send notification when RAB status changes
     */
    public function rabStatusChanged(Rab $rab, string $oldStatus, string $newStatus): void
    {
        $statusMessages = [
            'draft' => 'dalam tahap draft',
            'review' => 'sedang direview',
            'approved' => 'telah disetujui',
            'rejected' => 'telah ditolak',
            'final' => 'telah final',
        ];

        $colors = [
            'draft' => 'gray',
            'review' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'final' => 'success',
        ];

        $icons = [
            'draft' => 'heroicon-o-document',
            'review' => 'heroicon-o-clock',
            'approved' => 'heroicon-o-check-circle',
            'rejected' => 'heroicon-o-x-circle',
            'final' => 'heroicon-o-check-circle',
        ];

        // Determine which users to notify
        $users = collect();
        if ($newStatus === 'review') {
            $users = $this->getSupervisors();
        } elseif (in_array($newStatus, ['approved', 'rejected'])) {
            $users = $this->getFinanceTeam();
        } else {
            $users = $this->getSupervisors();
        }

        $actionUrl = route('filament.admin.resources.rabs.view', $rab);

        NotificationFacade::send($users, new WorkflowNotification(
            title: 'Status RAB Diperbarui',
            body: "RAB untuk pendaftaran {$rab->pendaftaran->nomor_registrasi} {$statusMessages[$newStatus]}",
            icon: $icons[$newStatus] ?? 'heroicon-o-bell',
            color: $colors[$newStatus] ?? 'primary',
            actionUrl: $actionUrl,
            actionLabel: 'Lihat Detail'
        ));
    }

    /**
     * Get users with supervisor role
     */
    private function getSupervisors(): Collection
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->get();
    }

    /**
     * Get users with surveyor role
     */
    private function getSurveyors(): Collection
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['super_admin', 'panel_user']);
        })->get();
    }

    /**
     * Get users with finance role
     */
    private function getFinanceTeam(): Collection
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['super_admin', 'panel_user']);
        })->get();
    }
}