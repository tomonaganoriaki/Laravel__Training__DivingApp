<?php
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredAdminController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('admin.welcome');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth:admin'])->name('admin.dashboard');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredAdminController::class, 'create'])->name('register')->where('id', '[0-9]+');
    Route::post('register', [RegisteredAdminController::class, 'store'])->where('id', '[0-9]+');
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login')->where('id', '[0-9]+');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->where('id', '[0-9]+');
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request')->where('id', '[0-9]+');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email')->where('id', '[0-9]+');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset')->where('id', '[0-9]+');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store')->where('id', '[0-9]+');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice')->where('id', '[0-9]+');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify')->where('id', '[0-9]+');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send')->where('id', '[0-9]+');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm')->where('id', '[0-9]+');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])->where('id', '[0-9]+');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update')->where('id', '[0-9]+');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->where('id', '[0-9]+');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->where('id', '[0-9]+');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->where('id', '[0-9]+');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->where('id', '[0-9]+');

    Route::get('/account', [AdminAccountController::class, 'index'])->name('account.index')->where('id', '[0-9]+');
    Route::get('/account/create', [AdminAccountController::class, 'create'])->name('account.create')->where('id', '[0-9]+');
    Route::post('/account', [AdminAccountController::class, 'store'])->name('account.store')->where('id', '[0-9]+');
    Route::get('/account/{id}/edit', [AdminAccountController::class, 'edit'])->name('account.edit')->where('id', '[0-9]+');
    Route::post('/account/{id}', [AdminAccountController::class, 'update'])->name('account.update')->where('id', '[0-9]+');
    Route::delete('/account/{id}', [AdminAccountController::class, 'destroy'])->name('account.destroy')->where('id', '[0-9]+');

    Route::get('/category', [CategoryController::class, 'index'])->name('category.index')->where('id', '[0-9]+');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create')->where('id', '[0-9]+');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store')->where('id', '[0-9]+');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit')->where('id', '[0-9]+');
    Route::post('/category/{id}', [CategoryController::class, 'update'])->name('category.update')->where('id', '[0-9]+');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy')->where('id', '[0-9]+');

    Route::get('/tag', [TagController::class, 'index'])->name('tag.index')->where('id', '[0-9]+');
    Route::get('/tag/create', [TagController::class, 'create'])->name('tag.create')->where('id', '[0-9]+');
    Route::post('/tag', [TagController::class, 'store'])->name('tag.store')->where('id', '[0-9]+');
    Route::get('/tag/{id}/edit', [TagController::class, 'edit'])->name('tag.edit')->where('id', '[0-9]+');
    Route::post('/tag/{id}', [TagController::class, 'update'])->name('tag.update')->where('id', '[0-9]+');
    Route::delete('/tag/{id}', [TagController::class, 'destroy'])->name('tag.destroy')->where('id', '[0-9]+');
});

