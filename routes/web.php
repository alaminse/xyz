<?php
// FrontEnd Controllers
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard\McqController;
use App\Http\Controllers\Dashboard\SbaController;
use App\Http\Controllers\Dashboard\NoteController;
use App\Http\Controllers\Dashboard\MockVivaController;
use App\Http\Controllers\Dashboard\OspeStationController;
use App\Http\Controllers\Dashboard\FlashCardController;
use App\Http\Controllers\Dashboard\AssessmentController;
use App\Http\Controllers\Dashboard\VideoStreamController;
use App\Http\Controllers\Dashboard\LectureVideoController;
use App\Http\Controllers\Dashboard\WrittenAssessmentController;


Auth::routes();

Route::group(['middleware' => ['auth', 'role:user']], function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/reset/{course}', [UserController::class, 'profile_reset'])->name('profile.reset');
    Route::post('/update/profile/{id}', [UserController::class, 'update_profile'])->name('update.profile');
    Route::get('/change/password', [UserController::class, 'change_password'])->name('change.password');
    Route::post('/update/password/{id}', [UserController::class, 'update_password'])->name('update.password');

    Route::get('/password/change', [UserController::class, 'password_change'])->name('password.change');
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware(['auth', 'role:user'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/enrollments', [HomeController::class, 'enrollments'])->name('enrollments');
        Route::get('/all/courses', [HomeController::class, 'allCourses'])->name('all.courses');
        Route::get('/dashboard/course/{slug}', [HomeController::class, 'courseDetails'])->name('user.course.details');

        Route::controller(AssessmentController::class)
            ->prefix('assessments')
            ->as('assessments.')
            ->group(function () {
                Route::get('/{course}', 'index')->name('index');
                Route::get('/attend/{assessment}/{course?}', 'exam')->name('exam');
                Route::post('/submit', 'submit')->name('submit');
                Route::get('/show/{slug}', 'show')->name('show');
                Route::get('/rank/{slug}', 'rank')->name('rank');
                Route::get('/print/{slug}', 'print')->name('print');
                Route::get('/see/all/{course}', 'see_all')->name('see.all');
                Route::get('/{course}/{chapter?}/{lesson?}', 'getByChapter')->name('by.chapter');
            });

        // // Frontend Flash Card Routes
        Route::controller(FlashCardController::class)
            ->prefix('flashs')
            ->as('flashs.')
            ->group(function () {
                Route::get('/review/{slug}', 'review')->name('review');
                Route::get('/{course}', 'index')->name('index');
                Route::get('/{course}/{chapter}/{lesson?}', 'getTest')->name('test');
                Route::post('/update-progress', 'updateProgress')->name('updateProgress');
            });

        Route::controller(LectureVideoController::class)
            ->prefix('lecturevideo')
            ->as('videos.')
            ->group(function () {
                Route::get('/{course}', 'index')->name('index');
                Route::get('/details/{course}/{chapter?}/{lesson?}', 'details')->name('details');
                Route::get('/single/details/{slug}/{course?}', 'single_details')->name('single.details');
            });


        Route::controller(McqController::class)
            ->prefix('mcqs')
            ->as('mcqs.')
            ->group(function () {

                Route::get('/{course}', 'index')->name('index');
                Route::get('/review/{slug}', 'review')->name('review');
                Route::get('/{course}/{chapter}/{lesson?}', 'getTest')->name('test');
                Route::post('/update-progress', 'updateProgress')->name('updateProgress');
                Route::post('/finish', 'finishQuiz')->name('finish');
            });

        Route::controller(MockVivaController::class)
            ->prefix('mockviva')
            ->as('mockvivas.')
            ->group(function () {
                Route::get('/{course}', 'index')->name('index');
                Route::get('/test/{course}/{chapter?}/{lesson?}', 'test')->name('test');
            });

        Route::controller(NoteController::class)
            ->prefix('notes')
            ->as('notes.')
            ->group(function () {
                Route::get('/{course}', 'index')->name('index');
                Route::post('/search', 'search')->name('search');
                Route::get('/details/{course}/{chapter?}/{lesson?}', 'details')->name('details');
                Route::get('/re-exam/{progress}', 'progress')->name('re-exam');
                Route::get('/single/details/{slug}/{query?}', 'single_details')->name('single.details');
            });

        Route::controller(OspeStationController::class)
            ->prefix('ospestation')
            ->as('ospes.')
            ->group(function () {
                Route::get('/{course}', 'index')->name('index');
                Route::get('/test/{course}/{chapter?}/{lesson?}', 'test')->name('test');
            });
        // ok

        Route::controller(SbaController::class)
            ->prefix('sbas')
            ->as('sbas.')
            ->group(function () {
                Route::get('/{slug}/review', 'review')->name('review');
                Route::get('/{course}', 'index')->name('index');
                Route::get('/{course}/{chapter}/{lesson?}', 'getTest')->name('test');
                Route::post('/update-progress', 'updateProgress')->name('updateProgress');
                Route::post('/finish', 'finishQuiz')->name('finish');
            });

        Route::controller(WrittenAssessmentController::class)
            ->prefix('writtenassessment')
            ->as('writtens.')
            ->group(function () {
                Route::get('/{course}', 'index')->name('index');
                Route::get('/details/{course}/{chapter?}/{lesson?}', 'details')->name('details');
                Route::get('/single/details/{slug}/{query?}', 'single_details')->name('single.details');
            });


        // Secure MP4 streaming
        Route::get('/secure-video/stream/{id}', [VideoStreamController::class, 'streamMp4'])
            ->name('video.stream.secure');

        // HLS streaming
        Route::get('/secure-video/hls/{id}/{file}', [VideoStreamController::class, 'streamHls'])
            ->name('video.hls.secure');
    });

Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/privacy/policy', [HomeController::class, 'privacy'])->name('privacy.policy');
Route::get('/terms/condition', [HomeController::class, 'terms'])->name('terms.condition');
Route::get('/states/{country}', [DashboardController::class, 'states']);

Route::controller(CourseController::class)
    ->prefix('courses')
    ->as('courses.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('details/{course}', 'details')->name('details');
        Route::get('/{slug}',  'getCourse');
        Route::get('/checkout/{course}/{isTrial?}',  'checkout')->name('checkout');
        Route::post('/checkout/store/{course}',  'checkout_store')->name('checkout.store');
        Route::get('/invoice/{slug}', 'invoice')->name('invoice');
    });
