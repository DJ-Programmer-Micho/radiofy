<?php

use Illuminate\Http\Request;

use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PlayController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ListenerController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\Auth\AuthLisController;
use App\Http\Controllers\Auth\AuthSubsController;

// Utilities Routes
Route::post('/set-locale', [Localization::class, 'setLocale'])->name('setLocale');
//*****************************
//   GENRE IMAGES PROCESS
//*****************************/
Route::post('/upload-filepond', function(Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
    } elseif ($request->hasFile('file_sq')) {
        $file = $request->file('file_sq');
    } else {
        return response('No file uploaded', 400);
    }
    // Store the file in a temporary folder: public/tmp/genre
    $filePath = $file->store('tmp/genre', 'public');
    return response($filePath, 200)
           ->header('Content-Type', 'text/plain');
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
//*****************************
//   GENRE IMAGES PROCESS
//*****************************/
Route::post('/upload-lang-filepond', function(Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
    } elseif ($request->hasFile('file_sq')) {
        $file = $request->file('file_sq');
    } else {
        return response('No file uploaded', 400);
    }
   
    $filePath = $file->store('tmp/lang', 'public');
    return response($filePath, 200)
           ->header('Content-Type', 'text/plain');
})->name('filepond.lang.upload');


Route::delete('/upload-lang-filepond-revert', function(Request $request) {
    $file = $request->input('file');
   
    if ($file && strpos($file, 'tmp/lang') === 0) {
        Storage::disk('public')->delete($file);
        return response()->json('File deleted', 200);
    }
    return response()->json('No file to delete', 400);
})->name('filepond.lang.revert');
//*****************************
//   INTERNAL RADIO IMAGES PROCESS
//*****************************/
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
//*****************************
//   EXTERNAL RADIO IMAGES PROCESS
//*****************************/
// For logo external (temporary)
Route::post('/upload-filepond-external-logo-temp', function(Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        // Store in a temporary folder
        $filePath = $file->store('e-radio/tmp/logo', 'public');
        return response()->json($filePath, 200);
    }
    return response()->json('No file uploaded', 400);
})->name('filepond.upload.external-logo.temp');

Route::delete('/revert-filepond-external-logo-temp', function(Request $request) {
    $file = $request->input('file');
    if ($file && Storage::disk('public')->exists($file)) {
        Storage::disk('public')->delete($file);
        return response()->json('File deleted', 200);
    }
    return response()->json('No file to delete', 400);
})->name('filepond.revert.external-logo.temp');

// For banner external (temporary)
Route::post('/upload-filepond-external-banner-temp', function(Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        // Store in a temporary folder
        $filePath = $file->store('e-radio/tmp/banner', 'public');
        return response()->json($filePath, 200);
    }
    return response()->json('No file uploaded', 400);
})->name('filepond.upload.external-banner.temp');

Route::delete('/revert-filepond-external-banner-temp', function(Request $request) {
    $file = $request->input('file');
    if ($file && Storage::disk('public')->exists($file)) {
        Storage::disk('public')->delete($file);
        return response()->json('File deleted', 200);
    }
    return response()->json('No file to delete', 400);
})->name('filepond.revert.external-banner.temp');



// Admin Authentications
Route::get('/admin-mradiofy-a', [AuthController::class, 'signIn'])->name('super.signin');
Route::post('/admin-mradiofy-a', [AuthController::class, 'handleSignIn'])->name('super.signin.post');
// Route::post('/logout', [AuthController::class, 'signOut'])->name('super.signout');
// Route::get('/lockscreen', [AuthController::class, 'lock'])->name('lockscreen');
// Route::post('/unlock', [AuthController::class, 'unlock'])->name('unlock');
Route::get('/auth-logout', [AuthController::class, 'logoutpage'])->name('logoutpage');

// Admin Routes
Route::prefix('admin-101')->middleware(['admin.auth.check','set.local'])->group(function () {
// Route::prefix('admin-101')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('super.dashboard');
    Route::get('/radio-adjustments', [AdminController::class, 'radioAdjustments'])->name('radio-adjustments');
    Route::get('/genres', [AdminController::class, 'radioGenres'])->name('radio-genres');
    Route::get('/radio-languages', [AdminController::class, 'radioLanguages'])->name('radio-languages');
    Route::get('/plan-subscribers', [AdminController::class, 'radioPlan'])->name('radio-plan');
    Route::get('/radio-verify', [AdminController::class, 'radioVerify'])->name('radio-verify');
    Route::get('/radio-prmotion', [AdminController::class, 'radioPromotion'])->name('radio-promotion');
});

// Subscribers Authentications
Route::get('/subs-signin', [AuthSubsController::class, 'signIn'])->name('subs.signin');
Route::post('/subs-signin', [AuthSubsController::class, 'handleSignIn'])->name('subs.signin.post');
Route::get('/register-subscriber-mradiofy', [AuthSubsController::class, 'signUp'])->name('subs.signup');
Route::get('/subs-mradiofy-forget-password', [AuthSubsController::class, 'forgetPassword'])->name('subs.forget');
Route::get('/subs-mradiofy-enter-new-password', [AuthSubsController::class, 'newPassword'])->name('subs.password');
Route::post('/account-subs-logout', [AuthSubsController::class, 'signOut'])->name('subs.logout.post');

// Route::post('/logout', [AuthController::class, 'signOut'])->name('super.signout');
// Route::get('/lockscreen', [AuthController::class, 'lock'])->name('lockscreen');
// Route::post('/unlock', [AuthController::class, 'unlock'])->name('unlock');
Route::get('/auth-logout', [AuthController::class, 'logoutpage'])->name('logoutpage');

// Subscriber Routes
Route::prefix('reg-201')->middleware(['subs.auth.check','set.local'])->group(function () {
    Route::get('/', [SubscriberController::class, 'dashboard'])->name('subscriber.dashboard');
    Route::get('/subs-profile', [SubscriberController::class, 'profile'])->name('subscriber.profile');
    Route::get('/subs-radios', [SubscriberController::class, 'Radio'])->name('subs-radios');
    Route::get('/subs-radio-manage/{radio_id}', [SubscriberController::class, 'radioManage'])->name('subs-radio-manage');
    Route::get('/subs-radio-server/{radio_id}', [SubscriberController::class, 'radioServer'])->name('subs-radio-server');
    Route::get('/subs-external-radios', [SubscriberController::class, 'externalRadio'])->name('subs-external-radios');
    Route::get('/subs-external-radio-manage/{radio_id}', [SubscriberController::class, 'externalRadioManage'])->name('subs-external-radio-manage');
    Route::get('/subs-new-plans', [SubscriberController::class, 'RadioNewPlan'])->name('subs.new.plan');
    Route::get('/subs-my-plans', [SubscriberController::class, 'RadioMyPlan'])->name('subs.my.plan');
    Route::get('/subs-radio-verify', [SubscriberController::class, 'RadioNewVerify'])->name('subs.new.verify');
    Route::get('/subs-my-verify', [SubscriberController::class, 'RadioMyVerify'])->name('subs.my.verify');
    Route::get('/subs-radio-promo', [SubscriberController::class, 'RadioNewPromo'])->name('subs.new.promo');
    Route::get('/subs-my-promo', [SubscriberController::class, 'RadioMyPromo'])->name('subs.my.promo');
    Route::get('/soft-butt', [SubscriberController::class, 'softButt'])->name('subs.soft.butt');
    Route::get('/soft-zara', [SubscriberController::class, 'softZara'])->name('subs.soft.zara');
    Route::get('/support', [SubscriberController::class, 'support'])->name('subs.support');
});

// Listener Routes
Route::middleware('set.local')->group(function () {
    Route::get('/', [ListenerController::class, 'home'])->name('listener.home');
    Route::get('/genres', [ListenerController::class, 'genre'])->name('listener.genre');
    Route::get('/genres/{genreId}', [ListenerController::class, 'genreView'])->name('listener.genreView');
    Route::get('/languages', [ListenerController::class, 'Language'])->name('listener.language');
    Route::get('/languages/{code}', [ListenerController::class, 'LanguageView'])->name('listener.languageView');
    Route::get('/radio/{slug}', [ListenerController::class, 'radioView'])->name('listener.radio');
    Route::middleware(['lis.auth.check'])->group(function () {
        Route::get('/library', [ListenerController::class, 'library'])->name('listener.library');
        Route::get('/profile', [ListenerController::class, 'profile'])->name('listener.profile');
    });
    
    
    Route::get('/register-account-mradiofy', [AuthLisController::class, 'signUp'])->name('lis.signup');
    Route::get('/account-mradiofy', [AuthLisController::class, 'signIn'])->name('lis.signin');
    Route::post('/account-mradiofy', [AuthLisController::class, 'handleSignIn'])->name('lis.signin.post');
    Route::get('/account-mradiofy-forget-password', [AuthLisController::class, 'forgetPassword'])->name('lis.forget');
    Route::get('/account-mradiofy-enter-new-password', [AuthLisController::class, 'newPassword'])->name('lis.password');
    Route::post('/account-lis-logout', [AuthLisController::class, 'signOut'])->name('lis.logout.post');
});


Route::prefix('landing')->middleware('set.local')->group(function () {
    Route::get('/', [LandingController::class, 'home'])->name('landing.home');
});

/*
|--------------------------------------------------------------------------
| Third Part Route
|--------------------------------------------------------------------------
*/
Route::post('/contactus-whatsapp', [SupportController::class, 'contactUsApp'])->name('contactUsApp');
