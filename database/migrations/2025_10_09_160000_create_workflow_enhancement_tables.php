<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SLA TRACKING - Service Level Agreement monitoring
        Schema::create('sla_tracking', function (Blueprint $table) {
            $table->uuid('id_sla')->primary();
            $table->string('tabel_referensi'); // pendaftaran, survei, rab, instalasi, pengaduan
            $table->uuid('id_referensi'); // ID dari tabel yang direferensi
            $table->string('proses_name'); // validasi_dokumen, survey_lapangan, etc
            $table->timestamp('mulai_proses');
            $table->timestamp('target_selesai');
            $table->timestamp('actual_selesai')->nullable();
            $table->enum('status_sla', ['on_time', 'warning', 'overdue', 'completed'])->default('on_time');
            $table->integer('sla_hours'); // Target jam penyelesaian
            $table->integer('actual_hours')->nullable(); // Actual jam penyelesaian
            $table->boolean('escalated')->default(false);
            $table->timestamp('escalated_at')->nullable();
            $table->string('escalated_to')->nullable(); // NIP atasan
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index(['tabel_referensi', 'id_referensi']);
            $table->index(['status_sla', 'target_selesai']);
            $table->index(['escalated', 'escalated_at']);
        });

        // // NOTIFICATIONS - Multi-channel notification system
        // Schema::create('notifications', function (Blueprint $table) {
        //     $table->uuid('id_notification')->primary();
        //     $table->string('tabel_referensi')->nullable(); // pendaftaran, pengaduan, etc
        //     $table->uuid('id_referensi')->nullable(); // ID dari tabel yang direferensi
        //     $table->uuid('id_pelanggan')->nullable();
        //     $table->string('type'); // sms, email, whatsapp, system
        //     $table->string('event_trigger'); // tagihan_ready, sla_warning, etc
        //     $table->string('recipient'); // nomor HP / email
        //     $table->string('subject')->nullable();
        //     $table->text('message');
        //     $table->json('template_data')->nullable(); // Data untuk template
        //     $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending');
        //     $table->timestamp('sent_at')->nullable();
        //     $table->timestamp('delivered_at')->nullable();
        //     $table->text('error_message')->nullable();
        //     $table->integer('retry_count')->default(0);
        //     $table->string('external_id')->nullable(); // ID dari provider eksternal

        //     $table->timestamps();

        //     $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('set null');
        //     $table->index(['status', 'sent_at']);
        //     $table->index(['type', 'event_trigger']);
        //     $table->index(['id_pelanggan', 'created_at']);
        // });

        // WORKFLOW_TRANSITIONS - Track status changes
        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->uuid('id_transition')->primary();
            $table->string('tabel_referensi'); // pendaftaran, survei, rab, etc
            $table->uuid('id_referensi');
            $table->string('status_from');
            $table->string('status_to');
            $table->string('triggered_by'); // NIP user
            $table->string('trigger_type')->default('manual'); // manual, automatic, scheduled
            $table->json('transition_data')->nullable(); // Additional data for transition
            $table->text('catatan')->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->string('approved_by')->nullable(); // NIP approver
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['tabel_referensi', 'id_referensi']);
            $table->index(['status_from', 'status_to']);
            $table->index(['triggered_by', 'created_at']);
        });

        // BUSINESS_RULES - Configurable workflow rules
        Schema::create('business_rules', function (Blueprint $table) {
            $table->uuid('id_rule')->primary();
            $table->string('rule_name');
            $table->string('rule_type'); // sla, validation, notification, escalation
            $table->string('applies_to'); // pendaftaran, survei, rab, instalasi, pengaduan
            $table->json('conditions'); // JSON conditions for rule execution
            $table->json('actions'); // JSON actions to execute
            $table->integer('priority')->default(1);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();

            $table->string('dibuat_oleh');
            $table->timestamps();

            $table->index(['rule_type', 'applies_to']);
            $table->index(['is_active', 'priority']);
        });

        // INTEGRATION_LOGS - External system integration tracking
        Schema::create('integration_logs', function (Blueprint $table) {
            $table->uuid('id_log')->primary();
            $table->string('system_name'); // SAKEP_2025, GIS, PaymentGateway, etc
            $table->string('operation_type'); // CREATE, UPDATE, DELETE, SYNC
            $table->string('endpoint')->nullable();
            $table->string('tabel_referensi')->nullable();
            $table->uuid('id_referensi')->nullable();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->enum('status', ['success', 'failed', 'pending'])->default('pending');
            $table->text('error_message')->nullable();
            $table->integer('response_time_ms')->nullable();
            $table->string('correlation_id')->nullable(); // For tracking related operations

            $table->timestamps();

            $table->index(['system_name', 'operation_type']);
            $table->index(['status', 'created_at']);
            $table->index(['correlation_id']);
        });

        // DOCUMENT_APPROVALS - Digital signature & approval tracking
        Schema::create('document_approvals', function (Blueprint $table) {
            $table->uuid('id_approval')->primary();
            $table->string('tabel_referensi'); // rab, survei, instalasi
            $table->uuid('id_referensi');
            $table->string('document_type'); // RAB, BA_Survey, BA_Instalasi, etc
            $table->string('document_path')->nullable(); // Encrypted file path
            $table->string('approver_nip');
            $table->string('approver_name');
            $table->string('approver_role');
            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'revision_needed'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->string('digital_signature')->nullable(); // Hash of digital signature
            $table->json('approval_metadata')->nullable(); // IP, device, etc

            $table->timestamps();

            $table->index(['tabel_referensi', 'id_referensi']);
            $table->index(['approver_nip', 'approval_status']);
            $table->index(['document_type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_approvals');
        Schema::dropIfExists('integration_logs');
        Schema::dropIfExists('business_rules');
        Schema::dropIfExists('workflow_transitions');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('sla_tracking');
    }
};
