<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

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

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/dashboard', [WelcomeController::class, 'index'])->name('dashboard');
Route::get('/home', [HomeController::class, 'index']);

// =========================================================================
// SECTIONS (Apollo Version - Manual Explicit Routing)
// =========================================================================

// 1. The Listing Page (The one that was throwing the 405 error)
Route::get('sections', [SectionController::class, 'index'])->name('sections.index');

// 2. The Creation Flow
Route::get('sections/create', [SectionController::class, 'create'])->name('sections.create');
Route::post('sections', [SectionController::class, 'store'])->name('sections.store');

// 3. The Editing Flow (Using your custom updatePost logic)
Route::get('sections/{id}/edit', [SectionController::class, 'edit'])->name('sections.edit');
Route::post('sections/{id}/update', [SectionController::class, 'updatePost'])->name('sections.update');

// 4. The Deletion (For the Apollo-fied x-delete-button)
Route::delete('sections/{id}', [SectionController::class, 'destroy'])->name('sections.destroy');

// 5. Custom Binary Toggles
Route::put('/sections/{section}/on', [SectionController::class, 'on'])->name('sections.on');
Route::put('/sections/{section}/off', [SectionController::class, 'off'])->name('sections.off');

// 4. ELEMENT ROUTES (Keep these separate)
Route::get('elements/create', [ElementController::class, 'create'])->name('elements.store');;
Route::post('elements/store', [ElementController::class, 'store']);
Route::get('elements/{id}/edit', [ElementController::class, 'edit']);
Route::match(['post', 'put'], 'elements/{id}/update', [ElementController::class, 'updatePost']);
Route::delete('elements/{id}', [ElementController::class, 'destroy'])->name('elements.destroy');;

Route::resource('slideshows', SlideshowController::class);
Route::get('/slideshows/{id}/view', [SlideshowController::class, 'view']);

Route::get('/rituals/editNames', [RitualController::class, 'editNames']);
Route::get('/rituals/editCultures', [RitualController::class, 'editCultures']);
Route::resource('rituals', RitualController::class);
Route::get('/rituals/{admin}/list', [RitualController::class, 'list']);
Route::get('/rituals/editCultures', [RitualController::class, 'editCultures']);
Route::put('/rituals/{id}/updateParameter', [RitualController::class, 'updateParameter']);
Route::get('/rituals/{id}/display', [RitualController::class, 'display']);
Route::get('/rituals/{id}/liturgy', [RitualController::class, 'liturgy'])->name('rituals.liturgy');
Route::get('rituals/{id}/uploadlit', [RitualController::class, 'uploadlit'])
    ->name('rituals.uploadlit');
Route::post('rituals/storelit', [RitualController::class, 'storelit'])
    ->name('rituals.storelit');

Route::put('/books/{id}', [BookController::class, 'update']);
Route::get('/books', [BookController::class, 'index']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{id}/edit', [BookController::class, 'edit']);
Route::get('/books/create', [BookController::class, 'create']);
Route::get('/books/{id}/sure', [BookController::class, 'sure']);
Route::delete('/books/{id}/destroy', [BookController::class, 'destroy']);

Route::get('/contact', [ContactController::class, 'contactus']);
Route::get('/contacts/thanks', [ContactController::class, 'thanks']);
Route::post('/contacts/submit', [ContactController::class, 'submit']);
Route::get('/contacts', [ContactController::class, 'index']);
Route::get('/contacts/{id}/spam', [ContactController::class, 'spam']);
Route::get('/contacts/{id}/reply', [ContactController::class, 'reply']);
Route::get('/contacts/{type}/list', [ContactController::class, 'list']);

Route::get('/grove/setup', [GroveController::class, 'setup']);
Route::get('/grove/hack', [GroveController::class, 'hack']);
Route::post('/grove/litfile', [GroveController::class, 'litfile']);
Route::post('/grove/picfile', [GroveController::class, 'picfile']);
Route::post('/grove/uploadFile', [GroveController::class, 'uploadfile']);
Route::get('/grove/upload', [GroveController::class, 'upload']);
Route::get('/grove/bylaws', [GroveController::class, 'bylaws']);
Route::get('/grove/pay', [GroveController::class, 'pay']);
Route::get('/grove/donate', [GroveController::class, 'donate']);
// Standardized Apollo Routes
Route::get('/schedule', [ElementController::class, 'editSchedule'])->name('schedule.edit');
Route::put('/schedule/{id}', [ElementController::class, 'updateSchedule'])->name('schedule.update');Route::get('/grove/', [GroveController::class, 'index']);

Route::get('/liturgy/find', [LiturgyController::class, 'find'])->name('liturgy.find');
Route::post('/liturgy/list', [LiturgyController::class, 'list']);
Route::get('/liturgy/{id}/get', [LiturgyController::class, 'get']);

Route::resource('venues', VenueController::class);

// Standard Views & Listing (Safe GET requests)
Route::get('/members', [MemberController::class, 'index']); // List all members
Route::get('/members/full', [MemberController::class, 'full']); // List all (full view)
Route::get('/members/create', [MemberController::class, 'create']); // Form to create
Route::get('/members/{id}/edit', [MemberController::class, 'edit']); // Form to edit
Route::get('/members/{id}/sure', [MemberController::class, 'sure']); // Confirmation view (SAFE)
Route::get('/members/newmembers', [MemberController::class, 'newmembers']); // List pending members
Route::get('/members/join', [MemberController::class, 'join']); // Join form (SAFE)
// State-Changing Actions (POST, PUT, DELETE)
Route::post('/members', [MemberController::class, 'store']); // Create new member (from /members/create)
Route::post('/members/join', [MemberController::class, 'savejoin']); // Save new joiner (from /members/join)
Route::delete('/members/{member}/deletejoin', [MemberController::class, 'deletejoin'])->name('members.deletejoin');
Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');// CRITICAL FIXES: Changing GET to secure verbs for state-changing actions
Route::post('/members/restore', [MemberController::class, 'restore']); // Restore/undelete a member
Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('members.destroy');// Custom workflow route
Route::post('/members/{id}/accept', [MemberController::class, 'accept'])->name('members.accept');

Route::resource('users', UserController::class);;;
Route::put('/users/{id}/make', [UserController::class, 'make']);
Route::put('/users/{id}/superuser', [UserController::class, 'superuser']);

Route::get('/roles/create', [RoleController::class, 'create']);
Route::post('/roles/store', [RoleController::class, 'store']);
Route::get('/roles/{name}/sure', [RoleController::class, 'sure']);
Route::get('/roles/{name}/destroy', [RoleController::class, 'destroy']);
Route::get('/roles/pcreate', [RoleController::class, 'pcreate']);
Route::post('/roles/pstore', [RoleController::class, 'pstore']);
Route::get('/roles/{name}/edit', [RoleController::class, 'edit']);
Route::get('/roles/{name}/{pname}/remove', [RoleController::class, 'remove']);
Route::get('/roles/{name}/add', [RoleController::class, 'add']);
Route::get('/roles/{name}/set', [RoleController::class, 'set']);
Route::get('/roles/{name}/psure', [RoleController::class, 'psure']);
Route::get('/roles/{name}/pdestroy', [RoleController::class, 'pdestroy']);

Route::resource('announcements', AnnouncementController::class);
Route::delete('/announcements/{id}/destroy', [AnnouncementController::class, 'destroy']);
Route::get('/announcements/{announcement}/activate', [AnnouncementController::class, 'activate'])
    ->name('announcements.activate');
// Specific Picture Upload routes
Route::get('announcements/{id}/uploadpic', [AnnouncementController::class, 'uploadpic'])
    ->name('announcements.uploadpic');

Route::post('announcements/storepic', [AnnouncementController::class, 'storepic'])
    ->name('announcements.storepic');
Route::get('/roles', [RoleController::class, 'roles']);
Route::get('/permissions', [RoleController::class, 'permissions']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
