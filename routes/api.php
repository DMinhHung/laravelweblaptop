<?php

use App\Http\Controllers\admin\ProductAdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\StaffsController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserAuthControllerAdmin;
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
//Login,Register User
/* Api Register */
Route::get('/users', [UserAuthController::class, 'index']);
Route::get('/getusers/{id}', [UserAuthController::class, 'usershow']);
Route::post('/register', [UserAuthController::class, 'userRegister']);
Route::post('/login', [UserAuthController::class, 'userLogin']);
Route::post('/logout', [UserAuthController::class, 'userlogout'])
    ->middleware('auth:sanctum');

//Login, Register Admin
Route::post('/loginadmin', [UserAuthControllerAdmin::class, 'adminLogin']);
Route::post('/registeradmin', [UserAuthControllerAdmin::class, 'adminRegister']);
Route::post('/adminlogout', [UserAuthControllerAdmin::class, 'adminlogout'])
    ->middleware('auth:sanctum');
//Update User Profile
Route::get('/getuser/{id}', [UserAuthController::class, 'usershow']);
Route::post('/update/{id}', [UserAuthController::class, 'userUpdate']);
Route::post('/avatar/{id}', [UserAuthController::class, 'userAvatar']);
Route::post('/updaterole/{id}', [UserAuthController::class, 'userUpdateRole']);
//Update User Profile

Route::post('/search', [ProductAdminController::class, 'search']);
Route::get('/search', [ProductAdminController::class, 'search']);

//Dashboard
Route::get('/dashboard/sanpham', [DashboardController::class, 'sanpham']);
Route::get('/dashboard/donhang', [DashboardController::class, 'donhang']);
Route::get('/dashboard/tien', [DashboardController::class, 'tien']);

//Staffs
Route::get('/getstaffs', [StaffsController::class, 'index']);
Route::post('/addstaffs', [StaffsController::class, 'store']);
Route::get('/show/{id}', [StaffsController::class, 'show']);
Route::post('/updatestaffs/{id}', [StaffsController::class, 'update']);
Route::delete('/deletestaffs/{id}', [StaffsController::class, 'destroy']);

// Route::get('/searchstaff', [StaffsController::class, 'search']);
// Route::post('/searchstaff', [StaffsController::class, 'search']);
//Staffs

//Customers
Route::get('/getcustomer', [CustomerController::class, 'index']);
Route::post('/addcustomer', [CustomerController::class, 'store']);
Route::get('/show/{id}', [CustomerController::class, 'show']);
Route::post('/updatecustomer/{id}', [CustomerController::class, 'update']);
Route::delete('/deletecustomer/{id}', [CustomerController::class, 'destroy']);

// Route::get('/searchcustomer', [CustomerController::class, 'search']);
// Route::post('/searchcustomer', [CustomerController::class, 'search']);
// //Customers


//Product
Route::get('/getproducts', [ProductAdminController::class, 'index']);
Route::get('/getcate', [ProductAdminController::class, 'getcategories']);
Route::post('/addproducts', [ProductAdminController::class, 'store']);
Route::get('/show/{id}', [ProductAdminController::class, 'show']);
Route::post('/updateproducts/{id}', [ProductAdminController::class, 'update']);
Route::delete('/products/{id}', [ProductAdminController::class, 'destroy']);

//Reviews 
Route::get('/getreviews/{id}', [RatingController::class, 'index']);
Route::post('/addreviews', [RatingController::class, 'ratingReview']);

// Route::post('/productadmin', [ProductAdminController::class, 'search']);
// Route::get('/productadmin', [ProductAdminController::class, 'search']);
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
Route::get('/getstatus', [OrderController::class, 'status']);
Route::put('/updateorderstatus/{checkoutId}', [OrderController::class, 'updateOrderStatus']);
Route::get('/bills', [OrderController::class, 'index']);
Route::get('/billdetail/{id}', [OrderController::class, 'billsdetail']);
Route::put('/updatestatus/{id}', [OrderController::class, 'updatestatus']);

Route::post('/searchorder', [OrderController::class, 'search']);
Route::get('/searchorder', [OrderController::class, 'search']);


//Payment 
Route::post('/payment', [PaymentController::class, 'vn_pay']);
Route::post('/payment/callback', [PaymentController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/payment/callback', [PaymentController::class, 'vnpayReturn'])->name('vnpay.return');