<?php
declare(strict_types=1);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\OAuthRepoController;
use App\Http\Controllers\OAuthUserController;
use App\Http\Controllers\RepoController;
use App\Http\Controllers\RowTableDeleteController;
use App\Http\Controllers\RowTableUpdateController;
use App\Http\Controllers\RowTableCreateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('admin.home') : redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.home');
    Route::get('/repo', [RepoController::class, 'index'])->name('repo.index');
    Route::get('/oauth-repo', [OauthRepoController::class, 'index'])->name('oauth_repo.index');
    Route::get('/oauth-user', [OauthUserController::class, 'index'])->name('oauth_user.index');
    Route::post('/update-row', [RowTableUpdateController::class, 'updateRow'])->name('update.row');
    Route::post('/delete-row', [RowTableDeleteController::class, 'deleteRow'])->name('delete.row');
    Route::post('/create-row', [RowTableCreateController::class, 'createRow'])->name('create.row');
});