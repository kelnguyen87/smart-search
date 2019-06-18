<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_dashboard', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phrase',255);
            $table->text('result');
            $table->unsignedInteger('shop_id');
            $table->timestamps();
        });

        Schema::table('report_dashboard',function (Blueprint $table){
            $table->foreign('shop_id')->references('id')
                ->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_dashboard');
    }
}
