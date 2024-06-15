<?php

use App\Http\Controllers\admin\ProductAdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\StaffsController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Login,Register
/* Api Register */
Route::get('/users', [UserAuthController::class, 'index']);
Route::get('/getusers/{id}', [UserAuthController::class, 'usershow']);
Route::post('/register', [UserAuthController::class, 'userRegister']);
Route::post('/login', [UserAuthController::class, 'userLogin']);
Route::post('/logout', [UserAuthController::class, 'userlogout'])
    ->middleware('auth:sanctum');

//Update User Profile
Route::get('/getuser/{id}', [UserAuthController::class, 'usershow']);
Route::post('/update/{id}', [UserAuthController::class, 'userUpdate']);
Route::post('/avatar/{id}', [UserAuthController::class, 'userAvatar']);
Route::post('/updaterole/{id}', [UserAuthController::class, 'userUpdateRole']);
//Update User Profile

//Home 
Route::post('/search', [ProductAdminController::class, 'search']);
Route::get('/search', [ProductAdminController::class, 'search']);


//Staffs
Route::get('/getstaffs', [StaffsController::class, 'index']);
//Product
Route::get('/getproducts', [ProductAdminController::class, 'index']);
Route::get('/getcate', [ProductAdminController::class, 'getcategories']);
Route::post('/addproducts', [ProductAdminController::class, 'store']);
Route::get('/show/{id}', [ProductAdminController::class, 'show']);
Route::post('/updateproducts/{id}', [ProductAdminController::class, 'update']);
Route::delete('/products/{id}', [ProductAdminController::class, 'destroy']);
//Product

// //Order
Route::post('/add-to-cart', [ShoppingCartController::class, 'addToCart']);
Route::post('/updatecart', [ShoppingCartController::class, 'updateCart']);
Route::get('/add-to-cart-get-products', [ShoppingCartController::class, 'index']);
Route::delete('/add-to-cartdelete/{id}', [ShoppingCartController::class, 'deleteFromCart']);
Route::post('/checkout', [ShoppingCartController::class, 'checkout']);

//Wishlist
Route::get('/getwishlist', [WishlistController::class, 'index']);
Route::post('/addwishlist', [WishlistController::class, 'addToWishlist']);
Route::delete('/deletewishlist/{id}', [WishlistController::class, 'deleteFromWishlist']);

//Bill
Route::get('/bills', [OrderController::class, 'index']);
Route::get('/billdetail/{id}', [OrderController::class, 'billsdetail']);

//Payment 
Route::post('/payment', [PaymentController::class, 'vn_pay']);
