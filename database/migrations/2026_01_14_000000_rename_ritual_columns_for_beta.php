<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('rituals', function (Blueprint $table) {
            $table->renameColumn('name', 'RitualName');
            $table->renameColumn('culture', 'RitualCulture');
        });
    }

    public function down(): void {
        Schema::table('rituals', function (Blueprint $table) {
            $table->renameColumn('RitualName', 'name');
            $table->renameColumn('RitualCulture', 'culture');
        });
    }
};
