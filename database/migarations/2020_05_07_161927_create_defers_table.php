<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defers', function (Blueprint $table) {
            $table->id();
            $table->string('master_type')->nullable();
            $table->string('master_field')->nullable();
            $table->string('slave_type')->nullable();
            $table->string('slave_id')->nullable();
            $table->string('session_key');
            $table->boolean('is_bind')->default(0);
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
        Schema::dropIfExists('defers');
    }
}
