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
            $table->string('name', 200);
            $table->string('company', 200);
            $table->string('address', 500);
            $table->string('no', 200);
            $table->string('tel', 200);
            $table->string('mobile_tel', 200);
            $table->string('position', 200);
            $table->string('website', 200);
            $table->string('city', 200);
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
