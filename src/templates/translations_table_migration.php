<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudlySingularTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snake_singular_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('snake_singular_id')->unsigned()->index();
            $table->string('locale', 2)->index();

            $table->unique(['snake_singular_id', 'locale']);

            $table->foreign('snake_singular_id')
                ->references('id')->on('snake_plural')
                ->onDelete('cascade');

            $table->foreign('locale')
                ->references('code')->on('locales')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snake_singular_translations');
    }
}
