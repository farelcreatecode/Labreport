<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // report_created, technician_assigned, status_changed
            $table->string('recipient_role'); // admin, technician, student
            $table->string('recipient_name')->nullable();
            $table->string('recipient_target'); // email / phone / WhatsApp
            $table->string('channel')->default('whatsapp'); // whatsapp, email, in_app
            $table->string('title');
            $table->text('message');
            $table->json('payload')->nullable();
            $table->string('status')->default('sent'); // queued, sent, delivered, failed
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
