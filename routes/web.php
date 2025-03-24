<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PlayController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ListenerController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Auth\AuthLisController;
use App\Http\Controllers\Auth\AuthSubsController;

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

// General/Guest Route
Route::get('/', function () {
    return view('welcome');
});

// Utilities Routes
Route::post('/upload-filepond', function(Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        // Store the file in a temporary folder: public/tmp/genre
        $filePath = $file->store('tmp/genre', 'public');
        return response($filePath, 200)
               ->header('Content-Type', 'text/plain');
    }
    return response('No file uploaded', 400);
})->name('filepond.upload');

Route::delete('/upload-filepond-revert', function(Request $request) {
    $file = $request->input('file');
    // Ensure only a temporary file is being deleted
    if ($file && strpos($file, 'tmp/genre') === 0) {
        Storage::disk('public')->delete($file);
        return response()->json('File deleted', 200);
    }
    return response()->json('No file to delete', 400);
})->name('filepond.revert');

// For logo (temporary)
Route::post('/upload-filepond-logo-temp', function(Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        // Store in a temporary folder
        $filePath = $file->store('radio/tmp/logo', 'public');
        return response()->json($filePath, 200);
    }
    return response()->json('No file uploaded', 400);
})->name('filepond.upload.logo.temp');

Route::delete('/revert-filepond-logo-temp', function(Request $request) {
    $file = $request->input('file');
    if ($file && Storage::disk('public')->exists($file)) {
        Storage::disk('public')->delete($file);
        return response()->json('File deleted', 200);
    }
    return response()->json('No file to delete', 400);
})->name('filepond.revert.logo.temp');

// For banner (temporary)
Route::post('/upload-filepond-banner-temp', function(Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        // Store in a temporary folder
        $filePath = $file->store('radio/tmp/banner', 'public');
        return response()->json($filePath, 200);
    }
    return response()->json('No file uploaded', 400);
})->name('filepond.upload.banner.temp');

Route::delete('/revert-filepond-banner-temp', function(Request $request) {
    $file = $request->input('file');
    if ($file && Storage::disk('public')->exists($file)) {
        Storage::disk('public')->delete($file);
        return response()->json('File deleted', 200);
    }
    return response()->json('No file to delete', 400);
})->name('filepond.revert.banner.temp');


// Admin Authentications
Route::get('/admin-mradiofy-a', [AuthController::class, 'signIn'])->name('super.signin');
Route::post('/admin-mradiofy-a', [AuthController::class, 'handleSignIn'])->name('super.signin.post');
// Route::post('/logout', [AuthController::class, 'signOut'])->name('super.signout');
// Route::get('/lockscreen', [AuthController::class, 'lock'])->name('lockscreen');
// Route::post('/unlock', [AuthController::class, 'unlock'])->name('unlock');
Route::get('/auth-logout', [AuthController::class, 'logoutpage'])->name('logoutpage');

// Admin Routes
Route::prefix('admin-101')->middleware(['admin.auth.check'])->group(function () {
// Route::prefix('admin-101')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('super.dashboard');
    Route::get('/radio-adjustments', [AdminController::class, 'radioAdjustments'])->name('radio-adjustments');
    Route::get('/genres', [AdminController::class, 'radioGenres'])->name('radio-genres');
    Route::get('/radio-languages', [AdminController::class, 'radioLanguages'])->name('radio-languages');
    Route::get('/plan-subscribers', [AdminController::class, 'radioPlan'])->name('radio-plan');
});

// Subscribers Authentications
Route::get('/subs-signin', [AuthSubsController::class, 'signIn'])->name('subs.signin');
Route::post('/subs-signin', [AuthSubsController::class, 'handleSignIn'])->name('subs.signin.post');
Route::get('/register-subscriber-mradiofy', [AuthSubsController::class, 'signUp'])->name('subs.signup');

// Route::post('/logout', [AuthController::class, 'signOut'])->name('super.signout');
// Route::get('/lockscreen', [AuthController::class, 'lock'])->name('lockscreen');
// Route::post('/unlock', [AuthController::class, 'unlock'])->name('unlock');
Route::get('/auth-logout', [AuthController::class, 'logoutpage'])->name('logoutpage');

// Subscriber Routes
Route::prefix('reg-201')->middleware(['subs.auth.check'])->group(function () {
// Route::prefix('reg-201')->group(function () {
    Route::get('/', [SubscriberController::class, 'dashboard'])->name('subscriber.dashboard');
    Route::get('/subs-radios', [SubscriberController::class, 'Radio'])->name('subs-radios');
    Route::get('/subs-radio-manage/{radio_id}', [SubscriberController::class, 'radioManage'])->name('subs-radio-manage');
    Route::get('/subs-radio-server/{radio_id}', [SubscriberController::class, 'radioServer'])->name('subs-radio-server');
    Route::get('/subs-new-plans', [SubscriberController::class, 'RadioNewPlan'])->name('subs.new.plan');
    Route::get('/subs-my-plans', [SubscriberController::class, 'RadioMyPlan'])->name('subs.my.plan');
    // Subscriber dashboard and related routes
});

// Listener Routes
// Route::prefix('listener')->group(function () {
    Route::get('/', [ListenerController::class, 'home'])->name('listener.home');
    Route::get('/radio/{radio}', [ListenerController::class, 'radioView'])->name('listener.radio');
Route::middleware(['lis.auth.check'])->group(function () {
});


Route::get('/account-mradiofy', [AuthLisController::class, 'signIn'])->name('lis.signin');
Route::post('/account-mradiofy', [AuthLisController::class, 'handleSignIn'])->name('lis.signin.post');
Route::get('/register-account-mradiofy', [AuthLisController::class, 'signUp'])->name('lis.signup');
// Route::post('/register-account-mradiofy', [AuthLisController::class, 'handleSignUp'])->name('lis.signup.post');
Route::post('/account-lis-logout', [AuthLisController::class, 'signOut'])->name('lis.logout.post');
