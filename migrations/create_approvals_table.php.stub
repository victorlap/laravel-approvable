<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('approvable_type');
            $table->integer('approvable_id');
            $table->integer('user_id')->nullable();
            $table->string('key');
            $table->text('value')->nullable();
            $table->boolean('approved')->nullable()->default(null);
            $table->timestamps();
            $table->index(array('approvable_id', 'approvable_type'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvals');
    }
}
