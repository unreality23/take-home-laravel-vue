<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request ) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Cart $cart
     *
     * @return \Illuminate\Http\Response
     */
    public function show( Cart $cart ) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Cart $cart
     *
     * @return \Illuminate\Http\Response
     */
    public function edit( Cart $cart ) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Cart $cart
     *
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, Cart $cart ) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Cart $cart
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy( Cart $cart ) {
        //
    }

    public function addToCart( Request $request, $userId ) {
        $cart     = Cart::firstOrCreate( [ 'user_id' => $userId ] );
        $cartItem = CartItem::updateOrCreate(
            [ 'cart_id' => $cart->id, 'product_id' => $request->product_id ],
            [ 'quantity' => \DB::raw( 'quantity + ' . $request->quantity ) ]
        );

        return response()->json( $cartItem );
    }

    public function removeFromCart( $userId, $productId ) {
        $cart = Cart::where( 'user_id', $userId )->first();
        if ( $cart ) {
            CartItem::where( 'cart_id', $cart->id )->where( 'product_id', $productId )->delete();
        }

        return response()->json( [ 'message' => 'Item removed from your cart' ] );
    }

    public function getCartItems( $userId ) {
        $cart = Cart::with( 'cartItems.product' )->where( 'user_id', $userId )->first();

        return response()->json( $cart ? $cart->cartItems : [] );
    }
}
