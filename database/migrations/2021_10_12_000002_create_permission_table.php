<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('slug');
            $table->boolean('view');
            $table->boolean('add');
            $table->boolean('delete');
            $table->boolean('edit');
            $table->boolean('other');
            $table->unsignedInteger('id_role')->nullable();
            $table->foreign('id_role')->references('id')->on('role');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('permission');
    }
}
