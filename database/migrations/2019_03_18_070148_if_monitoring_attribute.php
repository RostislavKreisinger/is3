<?php


use Illuminate\Database\Schema\Blueprint;


class IfMonitoringAttribute extends \App\Migration\ImportSupportMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->getSchema()->create('if_monitoring_attribute', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name",50);
            $table->integer("criticalValue",false,true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->getSchema()->dropIfExists('if_monitoring_attribute');
    }
}
