<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            // Core Identity (Keep these nullable as needed, except email)
            $table->string('mid_name')->nullable()->change();
            $table->string('rel_name')->nullable()->change();

            // Contact & Location
            $table->text('address')->nullable()->change();
            $table->string('pri_phone')->nullable()->change();
            $table->string('alt_phone')->nullable()->change();

            // Membership Tracking
            $table->string('status')->nullable()->change();
            $table->string('category')->nullable()->change();
            $table->date('joined')->nullable()->change();

            // External/Legacy IDs
            $table->string('adf')->nullable()->change();
            $table->string('adf_join')->nullable()->change();
            $table->string('adf_renew')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
