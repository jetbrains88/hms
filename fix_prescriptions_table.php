<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Updating prescriptions table...\n";

try {
    Schema::table('prescriptions', function (Blueprint $table) {
        if (!Schema::hasColumn('prescriptions', 'morning')) {
            $table->unsignedTinyInteger('morning')->default(0)->after('frequency');
            echo "Added morning column.\n";
        }
        if (!Schema::hasColumn('prescriptions', 'evening')) {
            $table->unsignedTinyInteger('evening')->default(0)->after('morning');
            echo "Added evening column.\n";
        }
        if (!Schema::hasColumn('prescriptions', 'night')) {
            $table->unsignedTinyInteger('night')->default(0)->after('evening');
            echo "Added night column.\n";
        }
        if (Schema::hasColumn('prescriptions', 'duration') && !Schema::hasColumn('prescriptions', 'days')) {
            $table->renameColumn('duration', 'days');
            echo "Renamed duration to days.\n";
        }
    });
    echo "Done.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
