<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. THE PUBLIC "HOLES" (No Auth Required)
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/home', [WelcomeController::class, 'index']);
Route::get('/dashboard', [WelcomeController::class, 'index'])->name('dashboard');


// Rituals & Slideshows (Public Views)
Route::get('/rituals', [RitualController::class, 'index'])->name('rituals.index');
Route::get('/rituals/{id}/display', [RitualController::class, 'display'])->name('rituals.display');
Route::get('/rituals/{id}/liturgy', [RitualController::class, 'liturgy'])->name('rituals.liturgy');

Route::get('/slideshows', [SlideshowController::class, 'index'])->name('slideshows.index');
Route::get('/slideshows/{id}/view', [SlideshowController::class, 'view'])->name('slideshows.view');

// Books & Contacts (Public Views)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/contact', [ContactController::class, 'contactus'])->name('contactus');
Route::get('/contacts/thanks', [ContactController::class, 'thanks'])->name('contacts.thanks');
Route::post('/contacts/submit', [ContactController::class, 'submit'])->name('contacts.submit');

// Onboarding
Route::get('/members/join', [MemberController::class, 'join']);
Route::post('/members/join', [MemberController::class, 'savejoin']);


/*
|--------------------------------------------------------------------------
| 2. THE MEMBER SANCTUARY (Auth + Role: Member/SeniorDruid/Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:member|SeniorDruid|admin'])->group(function () {
    Route::get('/members', [MemberController::class, 'index']);
    Route::get('/liturgy/find', [LiturgyController::class, 'find'])->name('liturgy.find');
    Route::get('/liturgy/list', [LiturgyController::class, 'list'])->name('liturgy.list');
    Route::get('/liturgy/{id}/downloadSource', [LiturgyController::class, 'downloadSource'])->name('liturgy.downloadSource');
    Route::get('/grove/pay', [GroveController::class, 'pay']);
    Route::get('/grove/bylaws', [GroveController::class, 'bylaws']);
    Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
    Route::post('/members/{id}/update', [MemberController::class, 'update'])->name('members.update');
});


/*
|--------------------------------------------------------------------------
| 3. THE MASTER KEY (Auth + Role: SeniorDruid/Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:SeniorDruid|admin'])->group(function () {

    // Management Resources
    Route::resource('rituals', RitualController::class)->except(['index', 'display', 'liturgy']);
    Route::resource('slideshows', SlideshowController::class)->except(['index', 'view']);
    Route::resource('sections', SectionController::class)->except(['index']);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('venues', VenueController::class);
    Route::resource('books', BookController::class)->except(['index', 'show']);
    Route::resource('elements', ElementController::class);

    // Ritual & Announcement Specifics
    Route::get('/rituals/editNames', [RitualController::class, 'editNames']);
    Route::get('/rituals/editCultures', [RitualController::class, 'editCultures']);
    Route::get('rituals/{id}/uploadlit', [RitualController::class, 'uploadlit'])->name('rituals.uploadlit');
    Route::post('rituals/storelit', [RitualController::class, 'storelit'])->name('rituals.storelit');
    Route::get('announcements/{id}/uploadpic', [AnnouncementController::class, 'uploadpic'])->name('announcements.uploadpic');
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts/{id}/spam', [ContactController::class, 'spam'])->name('contacts.spam');
    Route::post('/contacts/{id}/replied', [ContactController::class, 'reply'])->name('contacts.replied');
    Route::get('/contacts/{type}/list', [ContactController::class, 'list'])->name('contacts.list');
    Route::delete('/members/{id}/deletejoin', [MemberController::class, 'deletejoin'])->name('members.deletejoin');
    Route::delete('/contacts/delete-spam', [ContactController::class, 'massDeleteSpam'])->name('contacts.deleteSpam');

    // Schedule & Members Management
    Route::get('/schedule', [ElementController::class, 'editSchedule'])->name('schedule.edit');
    Route::put('/schedule/{id}', [ElementController::class, 'updateSchedule'])->name('schedule.update');
    Route::get('/members/newmembers', [MemberController::class, 'newmembers']);
    Route::post('/members/{id}/accept', [MemberController::class, 'accept'])->name('members.accept');
    Route::delete('/members/{id}/deletejoin', [MemberController::class, 'deletejoin'])->name('members.deletejoin');
});


/*
|--------------------------------------------------------------------------
| 4. THE ADMIN VAULT (Auth + Role: Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/roles', [RoleController::class, 'roles']);
    Route::get('/permissions', [RoleController::class, 'permissions']);
    Route::get('/grove/setup', [GroveController::class, 'setup']);
    Route::get('/grove/hack', [GroveController::class, 'hack']);
    Route::get('/grove/upload', [GroveController::class, 'upload']);
});


/*
|--------------------------------------------------------------------------
| 5. SYSTEM & AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
