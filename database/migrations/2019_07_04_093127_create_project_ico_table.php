<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectIcoTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('project_ico', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('eshop_id')->unique();
            $table->string('web_url');
            $table->string('ico')->nullable();
            $table->timestamp('skip_until_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }
}
