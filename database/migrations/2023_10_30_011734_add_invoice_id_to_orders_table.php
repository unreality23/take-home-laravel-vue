<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id')->nullable(); // Adding new column
            $table->foreign('invoice_id')->references('id')->on('invoices'); // Defining foreign key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']); // Dropping foreign key constraint
            $table->dropColumn('invoice_id'); // Dropping the column
        });
    }
}
