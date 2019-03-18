<?php

use Illuminate\Database\Seeder;

class MonitoringAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"average_run_time","criticalValue"=>3600]);
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"count_long_run_time_flows","criticalValue"=>100]);
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"long_average_import_time_to_start","criticalValue"=>3600]);
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"long_average_etl_time_to_start","criticalValue"=>3600]);
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"long_average_calc_time_to_start","criticalValue"=>3600]);
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"long_average_output_time_to_start","criticalValue"=>3600]);
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"long_average_import_run_time","criticalValue"=>3600]);
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"long_average_etl_run_time","criticalValue"=>3600]);
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"long_average_calc_run_time","criticalValue"=>3600]);
        \Monkey\Connections\MDDatabaseConnections::getImportSupportConnection()
            ->table("if_monitoring_attribute")->insert(["name"=>"long_average_output_run_time","criticalValue"=>3600]);
    }
}
