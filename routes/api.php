<?php

use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PostHistoryController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SocialController;
use App\Http\Controllers\Api\StatusUpdateController;
use App\Http\Controllers\Api\UserController;

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Auth Route
Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function () {
    Route::get('{provider}/redirect', [SocialController::class, 'redirect']);
    Route::get('{provider}/callback', [SocialController::class, 'handleCallback']);
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('activate_email/{code}', [RegisterController::class, 'activateEmail']);

    Route::post('forgotPasswordCreate', [RegisterController::class, 'forgotPasswordCreate']);

    Route::post('forgotPassword/{token}', [RegisterController::class, 'forgotPasswordToken']);
});


Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'user'
], function () {
    Route::get('getUser', [UserController::class, 'getUser']);
    //Status
    Route::resource('status', StatusUpdateController::class)->except('destroy');
    Route::get('post/hidden', [StatusUpdateController::class,'getHiddenPost']);
    Route::post('status/restore/{id}', [StatusUpdateController::class,'restore']);
    Route::post('status/restore', [StatusUpdateController::class,'restoreAll']);
    Route::delete('status/hidden/{id}', [StatusUpdateController::class,'hidden']);
    Route::delete('status/delete/{id}', [StatusUpdateController::class,'delete']);
    Route::get('status/{statusUpdate}/like', [LikeController::class, 'likePost']);
    //History
    Route::get('history/me', [PostHistoryController::class,'getListHistory']);
    Route::get('history/me/{id}', [PostHistoryController::class,'getPostOfHistory']);
    Route::get('history/user/{id}', [PostHistoryController::class,'getListHistoryFriend']);
    Route::put('history/me/update/{id}', [PostHistoryController::class,'updateHistory']);
    Route::post('history/sort/byHistory', [PostHistoryController::class,'sortHistory']);
    Route::post('history/sort/byPost', [PostHistoryController::class,'sortPost']);
    //Friend
    Route::get('friend/list', [FriendController::class, 'getFriend']);//Friend List, Request Friend,Block Friend
    Route::get('friend/recommend', [FriendController::class, 'getRecommendFriend']);//Recommend Friend
    Route::get('friend/list/search', [FriendController::class, 'searchFriend']);//Search Friend
    Route::get('friend/post/{id}', [FriendController::class, 'getListOfFriend']);//get post of Friend
    Route::post('friend/add/{id}', [FriendController::class, 'addFriend']);
    Route::post('friend/accept/{id}', [FriendController::class, 'acceptFriend']);
    Route::post('friend/decline/{id}', [FriendController::class, 'declineFriend']);
    Route::post('friend/block/{id}', [FriendController::class, 'blockFriend']);
    Route::post('friend/unblock/{id}', [FriendController::class, 'unblockFriend']);
    Route::delete('friend/delete/{id}', [FriendController::class, 'deleteFriend']);
    //Logout
    Route::post('logout', [LoginController::class, 'logout']);
});
