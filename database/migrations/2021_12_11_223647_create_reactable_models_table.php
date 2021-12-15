<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReactableModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reactable_models', function (Blueprint $table) {
            $table->id();
            $table->string('reactable_type');
            $table->foreignId('reaction_type_id');
            $table->timestamps();
            $table->unique('reactable_type', 'reaction_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reactable_models');
    }
}
