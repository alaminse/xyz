<?php

use App\Http\Controllers\Backend\AssessmentController;
use App\Http\Controllers\Backend\ChapterController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Backend\EnrolleController;
use App\Http\Controllers\Backend\LessonController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\FlashCardController;
use App\Http\Controllers\Backend\LectureVideoController;
use App\Http\Controllers\Backend\McqController;
use App\Http\Controllers\Backend\MockVivaController;
use App\Http\Controllers\Backend\NoteController;
use App\Http\Controllers\Backend\OspeStationController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SbaController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\WrittenAssessmentController;
use App\Http\Controllers\Dashboard\AssessmentController as DashboardAssessmentController;

Route::get('/admin', function () {
    return redirect()->route('admin.login');
});

Route::get('/admin/login', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('user')) {
            return redirect('/');
        } else {
            return redirect('/admin/dashboard');
        }
    }
    return view('backend.login');
})->name('admin.login');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/admin/edit/profile', [AdminController::class, 'edit_profile'])->name('admin.edit.profile');
    Route::post('/admin/update/profile/{id}', [AdminController::class, 'update_profile'])->name('admin.update.profile');
    Route::get('/admin/change/password', [AdminController::class, 'change_password'])->name('admin.change.password');
    Route::post('/admin/update/password/{id}', [AdminController::class, 'update_password'])->name('admin.update.password');
});

Route::middleware('auth')
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::controller(DashboardAssessmentController::class)
            ->prefix('assessments')
            ->as('assessments.')
            ->group(function () {
                Route::get('/print/{slug}', 'print')->name('print');
            });

        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('permissions', PermissionController::class);
        Route::post('/summernote/upload', [AdminController::class, 'summernote'])->name('summernote.upload');

        Route::controller(CourseController::class)
            ->prefix('courses')
            ->as('courses.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{course}', 'edit')->name('edit');
                Route::put('/update/{course}', 'update')->name('update');
                Route::get('/destroy/{course}', 'destroy')->name('destroy');
                Route::get('/status/{course}', 'status')->name('status');
            });

        Route::controller(ChapterController::class)
            ->prefix('chapters')
            ->as('chapters.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{chapter}', 'edit')->name('edit');
                Route::post('/update/{chapter}', 'update')->name('update');
                Route::get('/destroy/{chapter}', 'destroy')->name('destroy');
                Route::get('/status/{chapter}', 'status')->name('status');
                Route::get('/get', 'getByChapters');
            });

        Route::controller(LessonController::class)
            ->prefix('lessons')
            ->as('lessons.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{lesson}', 'show')->name('show');
                Route::get('/edit/{lesson}', 'edit')->name('edit');
                Route::post('/update/{lesson}', 'update')->name('update');
                Route::get('/destroy/{lesson}', 'destroy')->name('destroy');
                Route::get('/status/{lesson}', 'status')->name('status');
                Route::get('/get', 'get')->name('get');
            });



        Route::controller(SbaController::class)
            ->prefix('sbas')
            ->as('sbas.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{sba}', 'show')->name('show');
                Route::get('/edit/{sba}', 'edit')->name('edit');
                Route::post('/update/{sba}', 'update')->name('update');
                Route::get('/destroy/{sba}', 'destroy')->name('destroy');
                Route::get('/status/{sba}', 'status')->name('status');
                Route::get('/get-lessons/{chapter}', 'getLessons')->name('get.lessons');
                Route::post('/get/chapters', 'getChapters')->name('get.chapters');
                Route::get('/get/data', 'getData');
                Route::get('/get-notes', 'getNotes');

                // Question Management Routes
                Route::post('/{sba}/questions', 'storeQuestion')->name('questions.store');
                Route::get('/questions/{question}', 'getQuestion')->name('questions.get');
                Route::post('/questions/{question}', 'updateQuestion')->name('questions.update');
                Route::delete('/questions/{question}', 'destroyQuestion')->name('questions.destroy');
                Route::post('/{sba}/check-duplicate', 'checkDuplicate')->name('questions.check');

            });

        Route::controller(McqController::class)
            ->prefix('mcqs')
            ->as('mcqs.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{mcq}', 'show')->name('show');
                Route::get('/edit/{mcq}', 'edit')->name('edit');
                Route::post('/update/{mcq}', 'update')->name('update');
                Route::get('/destroy/{mcq}', 'destroy')->name('destroy');
                Route::get('/status/{mcq}', 'status')->name('status');
                Route::get('/get/data', 'getData');
                Route::get('/get-notes', 'getNotes');

                // Question Management Routes
                Route::post('/{mcq}/questions', 'storeQuestion')->name('questions.store');
                Route::get('/questions/{question}', 'getQuestion')->name('questions.get');
                Route::post('/questions/{question}', 'updateQuestion')->name('questions.update');
                Route::delete('/questions/{question}', 'destroyQuestion')->name('questions.destroy');
                Route::post('/{mcq}/check-duplicate', 'checkDuplicate')->name('questions.check');
            });

        Route::controller(FlashCardController::class)
            ->prefix('flashs')
            ->as('flashs.')
            ->group(function () {
                // CRUD routes
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{flash}', 'show')->name('show');
                Route::get('/edit/{flash}', 'edit')->name('edit');
                Route::post('/update/{flash}', 'update')->name('update');
                Route::get('/destroy/{flash}', 'destroy')->name('destroy');
                Route::get('/status/{flash}', 'status')->name('status');

                // AJAX routes
                Route::get('/get/data', 'getData')->name('get.data');
                Route::get('/get-notes', 'getNotes');

                // Question management
                Route::post('/{flash}/questions', 'storeQuestion')->name('questions.store');
                Route::get('/questions/{question}', 'getQuestion')->name('questions.get');
                Route::post('/questions/{question}', 'updateQuestion')->name('questions.update');
                Route::delete('/questions/{question}', 'destroyQuestion')->name('questions.destroy');
                Route::post('/{flash}/check-duplicate', 'checkDuplicate')->name('questions.check');
            });
        // Unchecked

        Route::controller(NoteController::class)
            ->prefix('notes')
            ->as('notes.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{note}', 'show')->name('show');
                Route::get('/edit/{note}', 'edit')->name('edit');
                Route::put('/update/{note}', 'update')->name('update');
                Route::get('/destroy/{note}', 'destroy')->name('destroy');
                Route::get('/status/{note}', 'status')->name('status');
                Route::get('/get/lesson', 'get_lesson')->name('get.lesson');
                Route::get('/get/data', 'getData');
            });

        Route::controller(AssessmentController::class)
            ->prefix('assessments')
            ->as('assessments.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/status/{assessment}', 'status')->name('status');
                Route::get('/destroy/{assessment}', 'destroy')->name('destroy');
                Route::get('/edit/{assessment_course}', 'edit')->name('edit');
                Route::get('/show/{assessment_course}', 'show')->name('show');
                Route::post('/update/{assessment}', 'update')->name('update');
                Route::get('/rank/{assessment}', 'rank')->name('rank');
                Route::get('/get/data', 'getData');
                Route::get('/user/progress', 'user_progress')->name('user.progress');
                Route::get('/user/progress/data', 'userProgressData');
                Route::get('/leaderboard/{assessment}', 'leaderboard')->name('leaderboard');
                Route::get('/user-progress/{progressId}', 'userAssessmentDetails')->name('user-details');

                // Question Management Routes
                Route::post('/question/store', 'storeQuestion')->name('question.store');
                Route::post('/question/update/{questionId}', 'updateQuestion')->name('question.update');
                Route::post('/question/delete/{questionId}', 'deleteQuestion')->name('question.delete');

            });


        Route::controller(WrittenAssessmentController::class)
            ->prefix('writtenassessments')
            ->as('writtenassessments.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{written}', 'show')->name('show');
                Route::get('/edit/{written}', 'edit')->name('edit');
                Route::post('/update/{written}', 'update')->name('update');
                Route::get('/destroy/{written}', 'destroy')->name('destroy');
                Route::get('/status/{written}', 'status')->name('status');
                Route::get('/get/lesson', 'get_lesson')->name('get.lesson');
                Route::get('/get/data', 'getData');
            });


        Route::controller(LectureVideoController::class)
            ->prefix('lecturevideos')
            ->as('lecturevideos.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/show/{video}', 'show')->name('show');
                Route::get('/edit/{video}', 'edit')->name('edit');
                Route::put('/update/{video}', 'update')->name('update');
                Route::get('/destroy/{video}', 'destroy')->name('destroy');
                Route::get('/status/{video}', 'status')->name('status');
                Route::get('/get/lesson', 'get_lesson')->name('get.lesson');
                Route::get('/get/data', 'getData');
                Route::post('/video-upload', 'videoUpload')->name('video.upload');


                Route::post('/upload', 'videoUpload')->name('upload');
                Route::post('/upload-bunny', 'uploadToBunny')->name('upload.bunny');
                Route::get('/bunny-status/{videoId}', 'getBunnyVideoStatus')->name('bunny.status');

            });

        Route::controller(MockVivaController::class)
            ->prefix('mockvivas')
            ->as('mockvivas.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/status/{mock}', 'status')->name('status');
                Route::get('/edit/{mock}', 'edit')->name('edit');
                Route::get('/show/{mock}', 'show')->name('show');
                Route::put('/update/{mock}', 'update')->name('update');
                Route::get('/destroy/{mock}', 'destroy')->name('destroy');
                Route::get('/get/data', 'getData');
                Route::post('/upload-image', 'uploadImage')->name('uploadImage');
            });

            // OSPE Station Routes
        Route::controller(OspeStationController::class)
            ->prefix('ospestations')
            ->name('ospestations.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/{ospe}/show', 'show')->name('show');
                Route::get('/{ospe}/edit', 'edit')->name('edit');
                Route::put('/{ospe}/update', 'update')->name('update');
                Route::delete('/{ospe}/destroy', 'destroy')->name('destroy');
                Route::post('/{ospe}/status', 'status')->name('status');
                Route::get('/get-data', 'getData')->name('getData');

                // Question Group Management Routes
                Route::post('/{ospe}/questions/store', 'storeQuestionGroup')->name('questions.store');
                Route::put('/questions/{question}/update', 'updateQuestionGroup')->name('questions.update');
                Route::delete('/questions/{question}/delete', 'questionDelete')->name('questions.delete');
            });

        // Route::controller(OspeStationController::class)
        //     ->prefix('ospestations')
        //     ->as('ospestations.')
        //     ->group(function () {
        //         Route::get('/', 'index')->name('index');
        //         Route::get('/create', 'create')->name('create');
        //         Route::post('/store', 'store')->name('store');
        //         Route::get('/status/{ospe}', 'status')->name('status');
        //         Route::get('/show/{ospe}', 'show')->name('show');
        //         Route::get('/edit/{ospe}', 'edit')->name('edit');
        //         Route::put('/update/{ospe}', 'update')->name('update');
        //         Route::put('/question/update/{question}', 'questionUpdate')->name('question.update');
        //         Route::get('/question/delete/{question}', 'questionDelete')->name('question.delete');
        //         Route::get('/destroy/{ospe}', 'destroy')->name('destroy');
        //         Route::get('/get/data', 'getData')->name('getData');
        //     });

        Route::controller(EnrolleController::class)
            ->prefix('enrolles')
            ->as('enrolles.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/report', 'report')->name('report');
                Route::post('/status', 'status')->name('status');
            });

        // Settings --------------------------------------------
        Route::controller(SettingsController::class)
            ->prefix('settings')
            ->as('settings.')
            ->group(function () {
                Route::get('/logo', 'logo')->name('logo');
                Route::post('/logo/update/{setting?}', 'logo_update')->name('logo.update');
                Route::get('/contact', 'contact')->name('contact');
                Route::post('/contact/update/{setting?}', 'contact_update')->name('contact.update');
                Route::post('/socials/update/{setting?}', 'socials_update')->name('socials.update');
                Route::get('/faqs', 'faqs')->name('faqs');
                Route::post('/faqs/update/{setting?}', 'faqs_update')->name('faqs.update');
                Route::get('/about', 'about')->name('about');
                Route::post('/about/update/{setting?}', 'about_update')->name('about.update');
                Route::get('/terms', 'terms')->name('terms');
                Route::post('/terms/update/{setting?}', 'terms_update')->name('terms.update');
                Route::get('/privacy', 'privacy')->name('privacy');
                Route::post('/privacy/update/{setting?}', 'privacy_update')->name('privacy.update');
                Route::get('/slider', 'slider')->name('slider');
                Route::post('/slider/update/{setting?}', 'slider_update')->name('slider.update');
                Route::post('/snote/update/{setting?}', 'snote_update')->name('snote.update');
            });
    });
