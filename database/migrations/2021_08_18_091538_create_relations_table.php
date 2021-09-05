<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->string('parent_key');
            $table->string('child_key');
            $table->foreignId('parent_id')->constrained('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('child_id')->constrained('posts')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

            $table->index(['parent_id', 'child_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relations');
    }
}
