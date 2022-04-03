<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->morphs('expressable');
            $table->foreignId('expression_type_id');
            $table->float('expression');
            $table->ipAddress('created_from');
            $table->ipAddress('updated_from')->nullable();
            $table->ipAddress('deleted_from')->nullable();
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
        Schema::dropIfExists('expressions');
    }
}
