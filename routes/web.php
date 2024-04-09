<?php

    use App\Http\Controllers\ApproveController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\ClassManagerController;
    use App\Http\Controllers\KhoaManagerController;
    use App\Http\Controllers\StudentController;
    use App\Http\Controllers\StudentManagerController;
    use App\Http\Controllers\TeacherManagerController;
    use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/check_login', [AuthController::class, 'checkLogin'])
        ->name('checkLogin');
    Route::get('/log-out', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => ['auth']], function () {
        // chuyển hướng nếu vô /
        Route::get('/', function () {
            if (Role(1)) {
                return redirect()->route('student.info');
            }
            return redirect()->route('approve.index');
        })->name('home');
        // chỉ sinh viên được vào
        Route::middleware('role:student')->group(function () {
            Route::name('student.')->group(function () {
                Route::get('/student-info', [StudentController::class, 'index'])->name('info');
            });
        });

        //tất cả được vào trừ sinh viên
        Route::middleware('role:notStudent')->group(function () {
            Route::name('approve.')->prefix('approve')->group(function () {
                Route::get('/', [ApproveController::class, 'index'])->name('index');
            });
        });

        // quyền admin hoặc là quyền bên phòng đào tạo được vào
        Route::middleware('role:studentManager')->name('studentManager.')
            ->prefix('student-manager')->group(function () {
                Route::get('/', [StudentManagerController::class, 'index'])->name('index');
                Route::get('get-data', [StudentManagerController::class, 'getData'])->name('getData');
                Route::get('get-data/{id?}', [StudentManagerController::class, 'getDataChild'])->name('getDataChild');
                Route::get('detele/{id?}', [StudentManagerController::class, 'detele'])->name('detele');
                Route::post('create', [StudentManagerController::class, 'create'])->name('create');
                Route::post('update/{id?}', [StudentManagerController::class, 'update'])->name('update');
                Route::post('import-file', [StudentManagerController::class, 'importFile'])->name('importFile');
        });

        Route::middleware('role:khoaManager')->name('khoaManager.')->prefix('khoa-manager')->group(function () {
                Route::get('/', [KhoaManagerController::class, 'index'])->name('index');
                Route::get('get-data', [KhoaManagerController::class, 'getData'])->name('getData');
                Route::get('get-data/{id?}', [KhoaManagerController::class, 'getDataChild'])->name('getDataChild');
                Route::get('detele/{id?}', [KhoaManagerController::class, 'detele'])->name('detele');
                Route::post('create', [KhoaManagerController::class, 'create'])->name('create');
                Route::post('update/{id?}', [KhoaManagerController::class, 'update'])->name('update');
        });

        Route::middleware('role:classManager')->name('classManager.')->prefix('class-manager')->group(function () {
                Route::get('/', [ClassManagerController::class, 'index'])->name('index');
                Route::get('get-data', [ClassManagerController::class, 'getData'])->name('getData');
                Route::get('get-data/{id?}', [ClassManagerController::class, 'getDataChild'])->name('getDataChild');
                Route::get('detele/{id?}', [ClassManagerController::class, 'detele'])->name('detele');
                Route::post('create', [ClassManagerController::class, 'create'])->name('create');
                Route::post('update/{id?}', [ClassManagerController::class, 'update'])->name('update');
        });

        Route::middleware('role:teacherManager')->name('teacherManager.')->prefix('teacher-manager')->group(function () {
                Route::get('/', [TeacherManagerController::class, 'index'])->name('index');
                Route::get('get-data', [TeacherManagerController::class, 'getData'])->name('getData');
                Route::get('get-data/{id?}', [TeacherManagerController::class, 'getDataChild'])->name('getDataChild');
                Route::get('detele/{id?}', [TeacherManagerController::class, 'detele'])->name('detele');
                Route::post('create', [TeacherManagerController::class, 'create'])->name('create');
                Route::post('update/{id?}', [TeacherManagerController::class, 'update'])->name('update');
        });

        Route::prefix('notificationCenter')->group(function () {
            Route::get('/', [NotificationCenterController::class, 'index'])->name('index');
        });
    });
