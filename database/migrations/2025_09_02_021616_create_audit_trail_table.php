<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_trail', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // asset, transfer, print
            $table->unsignedBigInteger('entity_id');
            $table->string('action'); // CREATED, UPDATED, TRANSFERRED, PRINTED
            $table->foreignId('performed_by')->nullable()->constrained('users');
            $table->json('changes')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trail');
    }
};
