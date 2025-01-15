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
        Schema::table('habits', function (Blueprint $table) {
            $table->date('month_year')->nullable()->after('type');
        });
    }
    
    public function down()
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn('month_year');
        });
    }
};
