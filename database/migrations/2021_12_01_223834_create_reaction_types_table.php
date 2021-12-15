<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReactionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->boolean('discrete');        // true => discrete / integer, false => non-discrete / float
            $table->integer('min');
            $table->integer('max');
            $table->json('icons')->nullable();  // [ x => 'url/path' ], x is integer that corresponds to value in range
            $table->json('names')->nullable();  // [ x => 'name' ], x is integer that corresponds to value in range.

            $table->softDeletes();
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
        Schema::dropIfExists('reaction_types');
    }
}
