<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get( 'version', function () {
    return response()->json( [ 'version' => config( 'app.version' ) ] );
} );


Route::middleware( 'auth:api' )->get( '/user', function ( Request $request ) {
    Log::debug( 'User:' . serialize( $request->user() ) );

    return $request->user();
} );


Route::namespace( 'App\\Http\\Controllers\\API\V1' )->group( function () {
    Route::get( 'profile', 'ProfileController@profile' );
    Route::put( 'profile', 'ProfileController@updateProfile' );
    Route::post( 'change-password', 'ProfileController@changePassword' );
    Route::get( 'tag/list', 'TagController@list' );
    Route::get( 'category/list', 'CategoryController@list' );
    Route::post( 'product/upload', 'ProductController@upload' );

    Route::apiResources( [
        'user'     => 'UserController',
        'product'  => 'ProductController',
        'category' => 'CategoryController',
        'tag'      => 'TagController',
    ] );
} );

Route::namespace( 'App\\Http\\Controllers' )->group( function () {
    Route::prefix( 'cart' )->group( function () {
        Route::post( '{userId}/add', 'CartController@addToCart' );
        Route::delete( '{userId}/remove/{productId}', 'CartController@removeFromCart' );
        Route::get( '{userId}/items', 'CartController@getCartItems' );
    } );

    Route::prefix( 'orders' )->group( function () {
        Route::post( '/', 'OrderController@store' );
        Route::get( '/', 'OrderController@index' );
    } );
} );

Route::namespace( 'App\\Http\\Controllers' )->group( function () {
    Route::middleware( [ 'auth:api' ] )->group( function () {
        Route::get( '/invoices', 'InvoiceController@index' );
        Route::prefix( 'orders' )->group( function () {
            Route::post( '/', 'OrderController@store' );
            Route::get( '/', 'OrderController@index' );
            Route::post( '{order}/invoice', 'OrderController@createInvoice' );
            Route::post( '{order}/invoice', 'OrderController@generateInvoice' );
            Route::get( '{order}/invoice', 'OrderController@getInvoice' );
            Route::put( '{order}', 'OrderController@update' );
        } );
    } );
} );
