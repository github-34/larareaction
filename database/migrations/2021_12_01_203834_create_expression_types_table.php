<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpressionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expression_types', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->enum('range_type', ['int', 'float']);
            $table->integer('min');
            $table->integer('max');
            $table->json('icons')->nullable();      // [ x => 'url/path' ], x is integer that corresponds to value in range
            $table->json('labels')->nullable();     // [ x => 'label' ], x is integer that corresponds to value in range.
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
        Schema::dropIfExists('expression_types');
    }
}
