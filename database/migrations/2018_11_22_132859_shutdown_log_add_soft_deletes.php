<?php

use App\Migration\LogsMigration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class ShutdownLogAddSoftDeletes
 */
class ShutdownLogAddSoftDeletes extends LogsMigration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->getSchema()->table('if_shutdown_log', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $this->getSchema()->table('if_shutdown_log', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}