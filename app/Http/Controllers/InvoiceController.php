<?php

namespace App\Http\Controllers;

use App\Models\Invoice;

class InvoiceController extends Controller {
    public function index() {
        if ( auth()->user()->type === 'admin' ) {
            // If the user is an admin, get all invoices along with their related orders.
            $invoices = Invoice::with( 'order' )->get();
        } else {
            // If the user is not an admin, only get the invoices related to their orders.
            $invoices = Invoice::whereHas( 'order', function ( $query ) {
                $query->where( 'user_id', auth()->id() );
            } )->with( 'order' )->get();
        }

        return response()->json( $invoices );
    }
}
