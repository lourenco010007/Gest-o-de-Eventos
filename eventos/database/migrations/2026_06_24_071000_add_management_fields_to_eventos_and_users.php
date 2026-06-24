<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('salao_id')->nullable()->after('user_id')->constrained('saloes')->nullOnDelete();
            $table->string('status')->default('pendente')->after('private');
            $table->text('status_reason')->nullable()->after('status');
            $table->date('requested_date')->nullable()->after('status_reason');
            $table->time('requested_hora_inicio')->nullable()->after('requested_date');
            $table->time('requested_hora_fim')->nullable()->after('requested_hora_inicio');
            $table->timestamp('requested_at')->nullable()->after('requested_hora_fim');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['salao_id']);
            $table->dropColumn([
                'user_id',
                'salao_id',
                'status',
                'status_reason',
                'requested_date',
                'requested_hora_inicio',
                'requested_hora_fim',
                'requested_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
