<?php

use App\Http\Controllers\ApproveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassManagerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HoSo\GiaoVienController;
use App\Http\Controllers\KhoaManagerController;
use App\Http\Controllers\MienGiamHPController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RutHoSo\GiaoVienRHSController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentManagerController;
use App\Http\Controllers\TeacherManagerController;
use App\Http\Controllers\StopStudyController;
use App\Http\Controllers\TroCapXHController;
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
        if(Role(0))
        {
            return redirect()->route('approve.index');
        }
        return redirect()->route('GiaoVien.index');
    })->name('home');
    // chỉ sinh viên được vào
    Route::middleware('role:student')->group(function () {
        Route::name('student.')->group(function () {
            Route::get('/student-info', [StudentController::class, 'index'])->name('info');
            Route::get('get-data-info', [StudentController::class, 'getDataInfo'])->name('getDataInfo');
            Route::post('update', [StudentController::class, 'update'])->name('update');
        });

        Route::name('StopStudy.')->prefix('xin-thoi-hoc')->group(function () {
           Route::get('/', [StopStudyController::class, 'index'])->name('index');
           Route::post('/create_view_pdf', [StopStudyController::class,'CreateViewPdf'])->name('CreateViewPdf');
           Route::get('/view_pdf/{id}', [StopStudyController::class,'viewPdf'])->name('viewPdf');
           Route::get('/view_demo_pdf', [StopStudyController::class,'viewDemoPdf'])->name('viewDemoPdf');
        });

        Route::name('MienGiamHp.')->prefix('mien-giam-hp')->group(function () {
            Route::get('/', [MienGiamHPController::class, 'index'])->name('index');
            Route::post('/create_view_pdf', [MienGiamHPController::class,'CreateViewPdf'])->name('CreateViewPdf');
            Route::get('/view_pdf/{id}', [MienGiamHPController::class,'viewPdf'])->name('viewPdf');
            Route::get('/view_demo_pdf', [MienGiamHPController::class,'viewDemoPdf'])->name('viewDemoPdf');
         });

         Route::name('TroCapXH.')->prefix('tro-cap-xh')->group(function () {
            Route::get('/', [TroCapXHController::class, 'index'])->name('index');
            Route::post('/create_view_pdf', [TroCapXHController::class,'CreateViewPdf'])->name('CreateViewPdf');
            Route::get('/view_pdf/{id}', [TroCapXHController::class,'viewPdf'])->name('viewPdf');
            Route::get('/view_demo_pdf', [TroCapXHController::class,'viewDemoPdf'])->name('viewDemoPdf');
         });
    });

    //tất cả được vào trừ sinh viên
    Route::middleware('role:notStudent')->group(function () {
        Route::name('approve.')->prefix('approve')->group(function () {
            Route::get('/', [ApproveController::class, 'index'])->name('index');
            Route::get('get-data', [ApproveController::class, 'getData'])->name('getData');
            Route::get('xacnhan/{id?}', [ApproveController::class, 'xacnhan'])->name('xacnhan');
            Route::get('khongxacnhan/{id?}', [ApproveController::class, 'khongxacnhan'])->name('khongxacnhan');
            Route::get('/view_pdf/{id?}', [ApproveController::class,'viewPdf'])->name('viewPdf');
        });
    });

    Route::middleware('role:giaoVien')->group(function () {
        Route::name('GiaoVien.')->prefix('giao-vien')->group(function () {
            Route::get('/', [GiaoVienController::class, 'index'])->name('index');
            Route::get('get-data', [GiaoVienController::class, 'getData'])->name('getData');
            Route::post('xacnhan', [GiaoVienController::class, 'xacnhan'])->name('xacnhan');
            Route::post('khongxacnhan', [GiaoVienController::class, 'khongxacnhan'])->name('khongxacnhan');
            Route::get('/view_pdf/{id?}', [GiaoVienController::class,'viewPdf'])->name('viewPdf');
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

    Route::name('notification.')->prefix('notification')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
    });
});

Route::get('pdf', [DocumentController::class, 'index']);
