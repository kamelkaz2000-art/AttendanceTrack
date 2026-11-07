<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('permission_name', 100);
            $table->foreignId('role_id')->constrained('roles');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['permission_name', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
