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

// Swapping {id} for {ritual} to enable Model Binding
Route::get('/rituals/{ritual}/display', [RitualController::class, 'display'])->name('rituals.display');
Route::get('/rituals/{ritual}/liturgy', [RitualController::class, 'liturgy'])->name('rituals.liturgy');
Route::get('/slideshows', [SlideshowController::class, 'index'])->name('slideshows.index');
Route::get('/slideshows/{id}/view', [SlideshowController::class, 'view'])->name('slideshows.view');

// Books & Contacts (Public Views)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/contact', [ContactController::class, 'contactus'])->name('contactus');
Route::get('/contacts/thanks', [ContactController::class, 'thanks'])->name('contacts.thanks');
Route::post('/contacts/submit', [ContactController::class, 'submit'])->name('contacts.submit');

// Onboarding
Route::get('/members/join', [MemberController::class, 'join'])->name('members.join');
Route::post('/members/join', [MemberController::class, 'savejoin'])->name('members.savejoin');
Route::middleware(['auth', 'role:joiner'])->group(function () {
    Route::get('/members/join', [MemberController::class, 'join'])->name('members.join');
});

/*
|--------------------------------------------------------------------------
| 2. THE MEMBER SANCTUARY (Auth + Role: Member/SeniorDruid/Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:member|senior_druid|admin'])->group(function () {
    // Allow both roles to hit the index, but Blade will handle column visibility
    Route::get('/members', [MemberController::class, 'index'])
        ->name('members.index');

    Route::get('/liturgy/find', [LiturgyController::class, 'find'])->name('liturgy.find');
    Route::get('/liturgy/list', [LiturgyController::class, 'list'])->name('liturgy.list');
    Route::get('/liturgy/{id}/downloadSource', [LiturgyController::class, 'downloadSource'])->name('liturgy.downloadSource');
    Route::get('/grove/pay', [GroveController::class, 'pay']);
    Route::get('/grove/bylaws', [GroveController::class, 'bylaws']);
    Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
    Route::post('/members/{member}/update', [MemberController::class, 'update']); // Optional: Keep for legacy forms
});


/*
|--------------------------------------------------------------------------
| 3. THE MASTER KEY (Auth + Role: SeniorDruid/Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:senior_druid|admin'])->group(function () {

    // 1. Specific Ritual & Announcement Actions (The "Interceptors")
    Route::get('/rituals/editNames', [RitualController::class, 'editNames']);
    Route::get('/rituals/editCultures', [RitualController::class, 'editCultures']);
    Route::get('rituals/{id}/uploadlit', [RitualController::class, 'uploadlit'])->name('rituals.uploadlit');
    Route::post('rituals/storelit', [RitualController::class, 'storelit'])->name('rituals.storelit');

    Route::get('announcements/{announcement}/uploadpic', [AnnouncementController::class, 'uploadpic'])->name('announcements.uploadpic');
    Route::post('announcements/{announcement}/storepic', [AnnouncementController::class, 'storepic'])->name('announcements.storepic');
    Route::get('announcements/{announcement}/activate', [AnnouncementController::class, 'activate'])->name('announcements.activate');

    // 2. Contact & Member Custom Logic
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts/{id}/spam', [ContactController::class, 'spam'])->name('contacts.spam');
    Route::post('/contacts/{id}/replied', [ContactController::class, 'reply'])->name('contacts.replied');
    Route::get('/contacts/{type}/list', [ContactController::class, 'list'])->name('contacts.list');
    Route::delete('/members/{member}/deletejoin', [MemberController::class, 'deletejoin'])->name('members.deletejoin');
    Route::delete('/contacts/delete-spam', [ContactController::class, 'massDeleteSpam'])->name('contacts.deleteSpam');

    // 3. Schedule & Specific Member Queries
    Route::get('/schedule', [ElementController::class, 'editSchedule'])->name('schedule.edit');
    Route::put('/schedule/{id}', [ElementController::class, 'updateSchedule'])->name('schedule.update');
    Route::get('/members/newmembers', [MemberController::class, 'newmembers'])->name('members.newmembers');
    Route::post('/members/{id}/accept', [MemberController::class, 'accept'])->name('members.accept');
    Route::put('/elements/{id}/update', [ElementController::class, 'updatePost'])->name('elements.update');
    Route::put('membership/restore', [MemberController::class, 'restore'])->name('members.restore');
    Route::get('/sections/{id}/on', [SectionController::class, 'on'])->name('sections.on');
    Route::get('/sections/{id}/off', [SectionController::class, 'off'])->name('sections.off');

    // 4. Management Resources (The "Catch-Alls" at the bottom)
    Route::resource('rituals', RitualController::class)->except(['index', 'display', 'liturgy']);
    Route::resource('slideshows', SlideshowController::class)->except(['index', 'view']);
    Route::resource('sections', SectionController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('venues', VenueController::class);
    Route::resource('books', BookController::class)->except(['index', 'show']);
    Route::resource('elements', ElementController::class);
    Route::resource('members', MemberController::class)->except(['index']);
    Route::resource('roles', RoleController::class);
});


/*
|--------------------------------------------------------------------------
| 4. THE ADMIN VAULT (Auth + Role: Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);

    // Role Management (Direct Actions - All Named)
    Route::get('/roles', [RoleController::class, 'roles'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{name}/edit', [RoleController::class, 'edit'])->name('roles.edit');

    // Permission Management (Named for easy removal/addition)
    Route::get('/roles/pcreate', [RoleController::class, 'pcreate'])->name('roles.permissions.create');
    Route::post('/roles/pstore', [RoleController::class, 'pstore'])->name('roles.permissions.store');
// Use match to support the transition, or just change it to post
    Route::match(['get', 'post'], '/roles/{name}/{pname}/remove', [RoleController::class, 'remove'])
        ->name('roles.permissions.remove');
    Route::post('/roles/{name}/add', [RoleController::class, 'add'])->name('roles.permissions.add');
    Route::post('/roles/{name}/set', [RoleController::class, 'set'])->name('roles.permissions.set');
    Route::delete('/permissions/{permission}', [RoleController::class, 'pdestroy'])
        ->name('permissions.pdestroy');

    // System Config (Named)
    Route::get('/permissions', [RoleController::class, 'permissions'])->name('permissions.index');
    Route::get('/grove/setup', [GroveController::class, 'setup'])->name('grove.setup');
    Route::get('/grove/upload', [GroveController::class, 'upload'])->name('grove.upload');
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
