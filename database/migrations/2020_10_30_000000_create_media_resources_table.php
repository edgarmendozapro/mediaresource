<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_resources', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('media_resource_id')
                ->unsigned()
                ->nullable();
            $table->string('file_name');
            $table->string('file_type');
            $table->integer('file_size')->unsigned();
            $table->string('file_extension');
            $table->string('url')->unique();
            $table->smallInteger('width')->unsigned();
            $table->smallInteger('height')->unsigned();
            $table->smallInteger('duration')->unsigned()->nullable();
            $table->boolean('is_compressed')->default(false);
            $table->string('alias')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table
                ->foreign('media_resource_id')
                ->references('id')
                ->on('media_resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_resources');
    }
}
