<?php 

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// use EloquentGoogleTranslate;

class CreateTranslationsTable extends Migration {

    public function up()
    {
        Schema::create( EloquentGoogleTranslate::getTranslationsTableName() , function(Blueprint $table) {

            $table->increments('id')->unsigned();
            $table->bigInteger('model_id')->unsigned();
            $table->string('column');
            $table->string('model');
            $table->string('locale', 10);
            $table->longText('translation');
            $table->timestamps();

            $table->unique(['model', 'model_id', 'locale', 'column']);
        });
    }

    public function down()
    {
        Schema::drop( EloquentGoogleTranslate::getTranslationsTableName() );
    }
}