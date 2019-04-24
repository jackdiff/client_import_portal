<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200)->nullable(true);
            $table->string('company', 200)->nullable(true);
            $table->string('address', 500)->nullable(true);
            $table->string('no', 200)->nullable(true);
            $table->string('tel', 200)->nullable(true);
            $table->string('mobile_tel', 200)->nullable(true);
            $table->string('position', 200)->nullable(true);
            $table->string('website', 200)->nullable(true);
            $table->string('city', 200)->nullable(true);
            $table->string('sheet_source', 40);
            $table->bigInteger('category_id');
            $table->index('category_id');
            $table->index('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
