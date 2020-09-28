<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\User\MeController;
use App\Http\Controllers\Designs\UploadController;
use App\Http\Controllers\Designs\DesignController;
use App\Http\Controllers\Comments\CommentController;
use App\Http\Controllers\Teams\TeamController;
use App\Http\Controllers\Invitations\InvitationController;
use App\Http\Controllers\Chats\ChatController;
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

Route::get('me', [MeController::class, 'getMe']);


Route::get('designs', [DesignController::class, 'index']);
Route::get('designs/{design:id}', [DesignController::class, 'show']);


Route::get('teams/slug/{team:slug}', [TeamController::class, 'findBySlug']);


Route::get('search/designs', [DesignController::class, 'search']);


Route::group(['middleware' => ['auth:api']], function(){
    Route::post('logout', [LoginController::class, 'logout']);
    Route::put('settings/profile', [SettingsController::class, 'updateProfile']);
    Route::put('settings/password', [SettingsController::class, 'updatePassword']);

    Route::post('designs', [UploadController::class, 'upload']);
    Route::put('designs/{design:id}', [DesignController::class, 'update']);
    Route::delete('designs/{design:id}', [DesignController::class, 'destroy']);
    Route::post('designs/{design:id}/comments', [CommentController::class, 'store']);
    Route::put('comments/{comment:id}', [CommentController::class, 'update']);
    Route::delete('comments/{comment:id}', [CommentController::class, 'destroy']);


    Route::post('designs/{design:id}/like', [DesignController::class, 'like']);
    Route::get('designs/{design:id}/like', [DesignController::class, 'userLikes']);

    //teams
    Route::post('teams', [TeamController::class, 'store']);
    Route::get('teams/{team:id}', [TeamController::class, 'find']);
    Route::get('teams', [TeamController::class, 'index']);
    Route::get('users/teams', [TeamController::class, 'fetchUserTeams']);
    Route::put('teams/{team:id}', [TeamController::class, 'update']);
    Route::delete('teams/{team:id}', [TeamController::class, 'destroy']);

    //invitations
    Route::post('invitations/{team:id}',[InvitationController::class, 'invite']);
    Route::post('invitations/{invitation:id}/resend', [InvitationController::class, 'resend']);
    Route::post('invitations/{invitation:id}/response',[InvitationController::class, 'respond']);
    Route::delete('invitations/{invitation:id}', [InvitationController::class, 'destroy']);

    //chats
    Route::post('chats',[ChatController::class,'sendMessage'] );
    Route::get('chats',[ChatController::class, 'getUserChats']);
    Route::get('chats/{chat:id}/messages', [ChatController::class, 'getChatMessages']);
    Route::put('chats/{chat:id}/read', [ChatController::class, 'markRead']);
    Route::delete('messages/{chat:id}', [ChatController::class, 'destroyMessage']);



});


Route::group(['middleware' => ['guest:api']], function(){
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.reset');
    Route::post('register',[RegisterController::class, 'register']);
    Route::post('verification/verify',[VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('verification/resend',[VerificationController::class, 'resend']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('password/reset', [ResetPasswordController::class,'reset']);


});
