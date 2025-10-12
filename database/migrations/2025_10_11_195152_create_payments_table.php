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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('paid_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Usuario que confirmó/registró el pago');
            $table->foreignId('cash_register_id')
                ->nullable()
                ->constrained('cash_registers')
                ->nullOnDelete()
                ->comment('Caja donde se registró el pago');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['efectivo', 'transferencia']);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
