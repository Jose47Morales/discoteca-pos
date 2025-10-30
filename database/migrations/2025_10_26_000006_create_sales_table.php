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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->nullable()->constrained('tables')->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('cash_register_id')->constrained('cash_registers')->onDelete('restrict');
            $table->decimal('total', 12, 2);
            $table->enum('status', ['pendiente', 'pagado', 'cancelado'])->default('pendiente')->index();
            $table->timestamp('paid_at')->nullable()->index();
            $table->enum('payment_method', ['efectivo', 'transferencia'])->default('efectivo')->nullable();
            $table->string('paid_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
