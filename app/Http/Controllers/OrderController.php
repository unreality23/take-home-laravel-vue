<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;


class OrderController extends Controller {

    public function index( Request $request ) {
        $invoiceFilter = $request->get( 'invoiced' );

        if ( auth()->user()->type === 'admin' ) {
            $query = Order::query()->with( 'orderDetails.product' );
        } else {
            $query = Order::query()->where( 'user_id', auth()->id() )
                          ->with( 'orderDetails.product' );
        }


        if ( $invoiceFilter ) {
            if ( $invoiceFilter == 'invoiced' ) {
                $query->whereNotNull( 'invoice_id' );
            } elseif ( $invoiceFilter == 'not_invoiced' ) {
                $query->whereNull( 'invoice_id' );
            }
        }

        $orders = $query->get();

        return response()->json( $orders );
    }

    public function store( Request $request ) {

        try {
            $order               = new Order();
            $order->user_id      = auth()->id();
            $order->total_amount = $request->total_amount;
            $order->status       = $request->status ?? 'pending';
            $order->save();

            foreach ( $request->items as $item ) {

                $orderDetail             = new OrderDetail();
                $orderDetail->order_id   = $order->id;
                $orderDetail->product_id = $item['product_id'];
                $orderDetail->quantity   = $item['quantity'];
                $orderDetail->price      = $item['price'];
                $orderDetail->save();
            }

            $this->clearCart( auth()->id() );

            return response()->json( [ 'message' => 'Order placed successfully' ] );
        } catch ( \Exception $e ) {
            \Log::error( $e->getMessage() );

            return response()->json( [ 'message' => 'An error occurred while placing the order' ] );
        }


    }

    public function createInvoice( Order $order ) {
        $invoice = new Invoice( [
            'invoice_number' => 'OR' . now()->timestamp,
            'invoice_date'   => now(),
            'total_amount'   => $order->total_amount,
        ] );

        $invoice = $order->invoice()->save( $invoice );

        $order->invoice_id = $invoice->id;
        $order->save();

        $orderDetails = $order->orderDetails()->with( 'product' )->get();

        return response()->json( [
            'message'      => 'Invoice created succesfully',
            'invoice'      => $invoice,
            'orderDetails' => $orderDetails
        ] );
    }

    public function getInvoice( Order $order ) {

        $invoice = $order->invoice;

        $orderDetails = $order->orderDetails()->with( 'product' )->get();

        if ( $invoice ) {
            return response()->json( [
                'invoice'      => $invoice,
                'orderDetails' => $orderDetails
            ] );
        } else {
            return response()->json( [ 'message' => 'No Invoice for this order' ] );
        }
    }

    private function clearCart( $userId ) {
        $cart = Cart::where( 'user_id', $userId )->first();
        if ( $cart ) {
            CartItem::where( 'cart_id', $cart->id )->delete();
        }
    }

    public function update( Request $request, Order $order ) {
        $newStatus     = $request->input( 'status' );
        $order->status = $newStatus;
        $order->save();

        return response()->json( $order );
    }

    public function generateInvoice( Order $order ) {
        if ( ! $order->invoice_id ) {
            $response = $this->createInvoice( $order );
            $data     = json_decode( $response->getContent() );

            return response()->json( [
                'message'    => 'Invoice created successfully',
                'invoice_id' => $data->invoice->id
            ] );
        } else {
            return response()->json( [ 'message' => 'Invoice already exists for this order' ] );
        }
    }
}
