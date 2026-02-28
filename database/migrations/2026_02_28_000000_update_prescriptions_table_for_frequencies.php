<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('prescriptions', 'morning')) {
                $table->unsignedTinyInteger('morning')->default(0)->after('frequency');
            }
            if (!Schema::hasColumn('prescriptions', 'evening')) {
                $table->unsignedTinyInteger('evening')->default(0)->after('morning');
            }
            if (!Schema::hasColumn('prescriptions', 'night')) {
                $table->unsignedTinyInteger('night')->default(0)->after('evening');
            }
            if (Schema::hasColumn('prescriptions', 'duration') && !Schema::hasColumn('prescriptions', 'days')) {
                $table->renameColumn('duration', 'days');
            }
        });
    }

    public function down()
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            if (Schema::hasColumn('prescriptions', 'days') && !Schema::hasColumn('prescriptions', 'duration')) {
                $table->renameColumn('days', 'duration');
            }
            if (Schema::hasColumn('prescriptions', 'morning')) {
                $table->dropColumn(['morning', 'evening', 'night']);
            }
        });
    }
};
