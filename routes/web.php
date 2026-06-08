<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripActivityController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function() { return redirect()->route('trips.index'); })->name('dashboard');
    
    // Wishlist
    Route::get('/trips/{trip}/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlists.index_for_trip');
    Route::post('/trips/{trip}/wishlist', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlists.store');
    Route::delete('/wishlist/{wishlist}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlists.destroy');
    Route::post('/wishlist/{wishlist}/vote', [\App\Http\Controllers\WishlistController::class, 'vote'])->name('wishlists.vote');
    // For You page
    Route::get('/for-you', function() {
        return view('for-you.index');
    })->name('for-you');

    // Search page
    Route::get('/search', function() {
        return view('search.index');
    })->name('search');
    
    // Trips
    Route::resource('trips', TripController::class);
    Route::get('/trips/{trip}/summary', [TripController::class, 'summary'])->name('trips.summary');
    
    // Trip Activities
    Route::post('/trips/days/{day}/activities', [TripActivityController::class, 'store'])->name('activities.store');
    Route::put('/activities/{activity}', [TripActivityController::class, 'update'])->name('activities.update');
    Route::delete('/activities/{activity}', [TripActivityController::class, 'destroy'])->name('activities.destroy');
    Route::post('/activities/{activity}/toggle', [TripActivityController::class, 'toggleComplete'])->name('activities.toggle');
    Route::post('/activities/{activity}/complete', [TripActivityController::class, 'complete'])->name('activities.complete');
    // Friends
    Route::get('/friends', [\App\Http\Controllers\FriendController::class, 'index'])->name('friends.index');
    Route::get('/friends/search', [\App\Http\Controllers\FriendController::class, 'search'])->name('friends.search');
    Route::post('/friends/{friend}/request', [\App\Http\Controllers\FriendController::class, 'sendRequest'])->name('friends.request');
    Route::post('/friends/request/{friendship}/accept', [\App\Http\Controllers\FriendController::class, 'acceptRequest'])->name('friends.accept');
    Route::delete('/friends/request/{friendship}/decline', [\App\Http\Controllers\FriendController::class, 'declineRequest'])->name('friends.decline');
    Route::delete('/friends/{friend}', [\App\Http\Controllers\FriendController::class, 'remove'])->name('friends.remove');

    // Invitations
    Route::get('/trips/{trip}/invite', [\App\Http\Controllers\InvitationController::class, 'showInviteForm'])->name('invitations.show');
    Route::post('/join-trip', [\App\Http\Controllers\InvitationController::class, 'inviteViaCode'])->name('invitations.join_code');
    Route::post('/trips/{trip}/invite', [\App\Http\Controllers\InvitationController::class, 'sendInvite'])->name('invitations.send');
    Route::get('/trips/invitations/accept/{token}', [\App\Http\Controllers\InvitationController::class, 'acceptInvite'])->name('invitations.accept');
    // In-app invitations
    Route::get('/invitations', [\App\Http\Controllers\InvitationController::class, 'index'])->name('invitations.index');
    Route::post('/invitations/{invitation}/accept', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept_inapp');
    Route::post('/invitations/{invitation}/decline', [\App\Http\Controllers\InvitationController::class, 'decline'])->name('invitations.decline_inapp');

    // Split budget / complete
    Route::post('/trips/{trip}/complete', [\App\Http\Controllers\TripController::class, 'complete'])->name('trips.complete');

    // Expenses
    Route::get('/trips/{trip}/budget', [\App\Http\Controllers\ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/trips/{trip}/budget/create', [\App\Http\Controllers\ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/trips/{trip}/budget', [\App\Http\Controllers\ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [\App\Http\Controllers\ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::get('/budget', [\App\Http\Controllers\ExpenseController::class, 'dashboard'])->name('expenses.dashboard');
    
    // Documents
    Route::get('/trips/{trip}/documents', [\App\Http\Controllers\DocumentController::class, 'index'])->name('documents.index');
    Route::post('/trips/{trip}/documents', [\App\Http\Controllers\DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{document}', [\App\Http\Controllers\DocumentController::class, 'destroy'])->name('documents.destroy');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
