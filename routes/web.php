<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TripActivityController;

Route::get('/', function () {
    try {
        $settings = \App\Models\LandingSetting::all()->pluck('value', 'key')->toArray();
        $features = \App\Models\LandingFeature::where('is_active', true)->orderBy('order')->get();
        $showcases = \App\Models\LandingShowcase::where('is_active', true)->orderBy('order')->get();
        $stats = \App\Models\LandingStat::where('is_active', true)->orderBy('order')->get();
        $testimonials = \App\Models\LandingTestimonial::where('is_active', true)->orderBy('order')->get();
    } catch (\Throwable $e) {
        $settings = [];
        $features = collect();
        $showcases = collect();
        $stats = collect();
        $testimonials = collect();
    }

    return view('landing', compact('settings', 'features', 'showcases', 'stats', 'testimonials'));
})->name('landing');

// Public Sub-Navbar Routes (Berita, Kontak, Photobooth Digital)
Route::get('/news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [\App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::get('/photobooth', [\App\Http\Controllers\PhotoboothController::class, 'index'])->name('photobooth.index');

// Google Auth Routes
Route::get('/auth/google', [\App\Http\Controllers\GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\GoogleAuthController::class, 'callback']);

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
    Route::get('/for-you', [\App\Http\Controllers\ForYouController::class, 'index'])->name('for-you');

    // Search
    Route::get('/search',          [\App\Http\Controllers\SearchController::class, 'index'])  ->name('search');
    Route::get('/search/cari',     [\App\Http\Controllers\SearchController::class, 'cari'])   ->name('search.cari');
    Route::get('/search/kode',     [\App\Http\Controllers\SearchController::class, 'kode'])   ->name('search.kode');
    Route::get('/search/partner',  [\App\Http\Controllers\SearchController::class, 'partner'])->name('search.partner');
    Route::get('/search/populer',  [\App\Http\Controllers\SearchController::class, 'populer'])->name('search.populer');
    
    // Trips
    Route::resource('trips', TripController::class);
    Route::get('/trips/{trip}/summary', [TripController::class, 'summary'])->name('trips.summary');
    Route::get('/trips/{trip}/edit-dates', [TripController::class, 'editDates'])->name('trips.edit-dates');
    
    // Trip Activities
    Route::post('/trips/days/{day}/activities', [TripActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{activity}', [TripActivityController::class, 'show'])->name('activities.show');
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

    // Profile (static routes before /profile/{user})
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/gamification/{user?}', [\App\Http\Controllers\ProfileController::class, 'gamification'])->name('profile.gamification');
    Route::get('/profile/{user}', [\App\Http\Controllers\ProfileController::class, 'showUser'])->name('profile.user');
    
    // Trip public features
    Route::get('/trips/{trip}/public', [\App\Http\Controllers\TripController::class, 'publicShow'])->name('trips.public_show');
    Route::get('/activities/{activity}/public', [\App\Http\Controllers\TripActivityController::class, 'publicShow'])->name('activities.public_show');
    Route::post('/trips/{trip}/like', [\App\Http\Controllers\TripController::class, 'toggleLike'])->name('trips.like');
    Route::post('/trips/{trip}/clone', [\App\Http\Controllers\TripController::class, 'cloneToWishlist'])->name('trips.clone');
    Route::patch('/trips/{trip}/visibility', [\App\Http\Controllers\TripController::class, 'toggleVisibility'])->name('trips.visibility');
});

/* ------------------------------------------------------------------ */
/*  Admin Routes (/ctrl-twogo-admin/login)                           */
/* ------------------------------------------------------------------ */
Route::prefix('ctrl-twogo-admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login']);

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', [\App\Http\Controllers\Admin\AdminOverviewController::class, 'index'])->name('overview');

        // Users Management
        Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/status', [\App\Http\Controllers\Admin\AdminUserController::class, 'updateStatus'])->name('users.status');
        Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\AdminUserController::class, 'resetPassword'])->name('users.reset_password');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('users.destroy');

        // Itinerary Management
        Route::get('/itineraries', [\App\Http\Controllers\Admin\AdminItineraryController::class, 'index'])->name('itineraries.index');
        Route::get('/itineraries/{trip}', [\App\Http\Controllers\Admin\AdminItineraryController::class, 'show'])->name('itineraries.show');
        Route::post('/itineraries/{trip}/flag', [\App\Http\Controllers\Admin\AdminItineraryController::class, 'toggleFlag'])->name('itineraries.flag');
        Route::delete('/itineraries/{trip}', [\App\Http\Controllers\Admin\AdminItineraryController::class, 'destroy'])->name('itineraries.destroy');

        // Gamification & XP System
        Route::get('/gamification', [\App\Http\Controllers\Admin\AdminGamificationController::class, 'index'])->name('gamification.index');
        Route::post('/gamification/rules', [\App\Http\Controllers\Admin\AdminGamificationController::class, 'updateRules'])->name('gamification.rules');

        // Landing Page CMS
        Route::get('/landing', [\App\Http\Controllers\Admin\AdminLandingController::class, 'index'])->name('landing.index');
        Route::post('/landing/settings', [\App\Http\Controllers\Admin\AdminLandingController::class, 'updateSettings'])->name('landing.settings');

        // Features CRUD
        Route::post('/landing/features', [\App\Http\Controllers\Admin\AdminLandingController::class, 'storeFeature'])->name('landing.features.store');
        Route::put('/landing/features/{feature}', [\App\Http\Controllers\Admin\AdminLandingController::class, 'updateFeature'])->name('landing.features.update');
        Route::delete('/landing/features/{feature}', [\App\Http\Controllers\Admin\AdminLandingController::class, 'destroyFeature'])->name('landing.features.destroy');

        // Stats CRUD
        Route::post('/landing/stats', [\App\Http\Controllers\Admin\AdminLandingController::class, 'storeStat'])->name('landing.stats.store');
        Route::put('/landing/stats/{stat}', [\App\Http\Controllers\Admin\AdminLandingController::class, 'updateStat'])->name('landing.stats.update');
        Route::delete('/landing/stats/{stat}', [\App\Http\Controllers\Admin\AdminLandingController::class, 'destroyStat'])->name('landing.stats.destroy');

        // Testimonials CRUD
        Route::post('/landing/testimonials', [\App\Http\Controllers\Admin\AdminLandingController::class, 'storeTestimonial'])->name('landing.testimonials.store');
        Route::put('/landing/testimonials/{testimonial}', [\App\Http\Controllers\Admin\AdminLandingController::class, 'updateTestimonial'])->name('landing.testimonials.update');
        Route::delete('/landing/testimonials/{testimonial}', [\App\Http\Controllers\Admin\AdminLandingController::class, 'destroyTestimonial'])->name('landing.testimonials.destroy');

        // News CMS CRUD
        Route::resource('news', \App\Http\Controllers\Admin\AdminNewsController::class);

        // Feedback / Kritik & Saran
        Route::get('/feedback', [\App\Http\Controllers\Admin\AdminFeedbackController::class, 'index'])->name('feedback.index');
        Route::post('/feedback/{feedback}/read', [\App\Http\Controllers\Admin\AdminFeedbackController::class, 'toggleRead'])->name('feedback.read');
        Route::delete('/feedback/{feedback}', [\App\Http\Controllers\Admin\AdminFeedbackController::class, 'destroy'])->name('feedback.destroy');
    });
});

