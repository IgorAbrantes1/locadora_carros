<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date_period');
            $table->dateTime('end_date_expected_period');
            $table->dateTime('end_date_performed_period');
            $table->float('daily_value', 8, 2);
            $table->integer('km_initial');
            $table->integer('km_final');
            $table->foreignId('customer_id')->references('id')->on('customers');
            $table->foreignId('car_id')->references('id')->on('cars');
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
        Schema::dropIfExists('rentals');
    }
}
