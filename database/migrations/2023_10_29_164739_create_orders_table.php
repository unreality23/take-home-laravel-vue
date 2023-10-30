<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->decimal('total_amount', 20, 2);
            $table->enum('status', ['pending', 'shipped', 'cancelled', 'processing', 'delivered'])->default('pending');
            $table->unsignedBigInteger('invoice_id')->nullable(); // Adding new column
            $table->foreign('invoice_id')->references('id')->on('invoices'); // Defining foreign key
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
        Schema::dropIfExists('orders');
    }
}
