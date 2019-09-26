<?php

use Illuminate\Database\Schema\Blueprint;

class IfMonitoring extends \App\Migration\ImportSupportMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->getSchema()->create('if_monitoring', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("attribute_id",false,true);
            $table->addColumn('integer', "number_of_sequels", ['autoIncrement'=>false,'unsigned'=>true,'default'=>0]);
            $table->dateTime("start_issue");
            $table->integer("solver_user_id",false,true);
            $table->integer("confirm_resolution",false,true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->getSchema()->dropIfExists('if_monitoring');
    }
}
