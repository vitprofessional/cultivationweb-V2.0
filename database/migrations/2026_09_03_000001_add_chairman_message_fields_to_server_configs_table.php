<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('server_configs')) {
            return;
        }

        Schema::table('server_configs', function (Blueprint $table) {
            if (!Schema::hasColumn('server_configs', 'boardChairmanDesignation')) {
                $table->string('boardChairmanDesignation')->nullable()->after('boardChairmanName');
            }
            if (!Schema::hasColumn('server_configs', 'boardChairmanMessage')) {
                $table->text('boardChairmanMessage')->nullable()->after('boardChairmanImg');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('server_configs')) {
            return;
        }

        Schema::table('server_configs', function (Blueprint $table) {
            if (Schema::hasColumn('server_configs', 'boardChairmanMessage')) {
                $table->dropColumn('boardChairmanMessage');
            }
            if (Schema::hasColumn('server_configs', 'boardChairmanDesignation')) {
                $table->dropColumn('boardChairmanDesignation');
            }
        });
    }
};