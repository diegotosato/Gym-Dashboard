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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('slug', 100);
            $table->enum('gender', ['male', 'female']);
            $table->string('email')->unique();
            $table->string('phone', 20)->unique();
            $table->date('date_of_birth');
            $table->string('address', 255);
            $table->string('tax_code', 25)->unique();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
