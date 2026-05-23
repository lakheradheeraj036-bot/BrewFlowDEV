<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['cafe', 'hotel', 'restaurant', 'bar', 'other'])->default('cafe');
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending'])->default('pending');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable()->default('Australia');
            $table->string('timezone')->default('Australia/Sydney');
            $table->string('currency')->default('AUD');
            $table->string('logo')->nullable();
            $table->json('settings')->nullable();
            $table->timestamp('subscription_expires_at')->nullable();
            $table->string('subscription_plan')->default('free');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
