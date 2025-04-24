<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheDoChinhSach\CheDoChinhSachKeHoachTaiChinhController;
use App\Http\Controllers\CheDoChinhSach\CheDoChinhSachLanhDaoPhongDaoTaoController;
use App\Http\Controllers\CheDoChinhSach\CheDoChinhSachLanhDaoTruongController;
use App\Http\Controllers\CheDoChinhSach\CheDoChinhSachPhongDaoTaoController;
use App\Http\Controllers\CheDoChinhSachController;
use App\Http\Controllers\ClassManagerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HoSo\GiaoVienController;
use App\Http\Controllers\HoSo\KeHoachTaiChinhController;
use App\Http\Controllers\HoSo\KhoaController;
use App\Http\Controllers\HoSo\LanhDaoPhongDaoTaoController;
use App\Http\Controllers\HoSo\LanhDaoTruongController;
use App\Http\Controllers\HoSo\PhongDaoTaoController;
use App\Http\Controllers\HoSoChungTuPhongDaoTaoController;
use App\Http\Controllers\KhoaManagerController;
use App\Http\Controllers\MienGiamHP\MienGiamHPCanBoPhongDaoTaoController;
use App\Http\Controllers\MienGiamHP\MienGiamHPKeHoachTaiChinhController;
use App\Http\Controllers\MienGiamHP\MienGiamHPLanhDaoTruongController;
use App\Http\Controllers\MienGiamHP\MienGiamHPPhongDaoTaoController;
use App\Http\Controllers\MienGiamHPController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PhieuController;
use App\Http\Controllers\RutHoSo\GiaoVienRHSController;
use App\Http\Controllers\ProfileGiaoVienController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentManagerController;
use App\Http\Controllers\TeacherManagerController;
use App\Http\Controllers\StopStudyController;
use App\Http\Controllers\TroCapHocPhi\TroCapHocPhiKeHoachTaiChinhController;
use App\Http\Controllers\TroCapHocPhi\TroCapHocPhiLanhDaoPhongDaoTaoController;
use App\Http\Controllers\TroCapHocPhi\TroCapHocPhiLanhDaoTruongController;
use App\Http\Controllers\TroCapHocPhi\TroCapHocPhiPhongDaoTaoController;
use App\Http\Controllers\TroCapXaHoi\TroCapXaHoiCanBoPhongDaoTaoController;
use App\Http\Controllers\TroCapXaHoi\TroCapXaHoiKeHoachTaiChinhController;
use App\Http\Controllers\TroCapXaHoi\TroCapXaHoiLanhDaoTruongController;
use App\Http\Controllers\TroCapXaHoi\TroCapXaHoiPhongDaoTaoController;
use App\Http\Controllers\TroCapXHController;
use App\Mail\SendMail;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

use mikehaertl\wkhtmlto\Pdf;


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

Route::get('/create-storage-link', function () {
    Artisan::call('storage:link');
});

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
        if (Role(0)) {
            return redirect()->route('studentManager.index');
        }
        if (Role(2) || Role(3)) {
            return redirect()->route('GiaoVien.index');
        }
        if (Role(4)) {
            return redirect()->route('PhongDaoTao.index');
        }
        if (Role(5)) {
            return redirect()->route('KeHoachTaiChinh.index');
        }
        if (Role(6)) {
            return redirect()->route('LanhDaoPhongDaoTao.index');
        }
        if (Role(7)) {
            return redirect()->route('LanhDaoTruong.index');
        }
        return redirect()->route('GiaoVien.index');
    })->name('home');
    // chỉ sinh viên được vào
    Route::middleware('role:student')->group(function () {
        Route::name('student.')->group(function () {
            Route::get('/student-info', [StudentController::class, 'index'])->name('info');
            Route::get('/get-data-info', [StudentController::class, 'getDataInfo'])->name('getDataInfo');
            Route::post('/update', [StudentController::class, 'update'])->name('update');
        });

        Route::name('StopStudy.')->prefix('xin-thoi-hoc')->group(function () {
            Route::get('/', [StopStudyController::class, 'index'])->name('index');
            Route::post('/create_view_pdf', [StopStudyController::class, 'CreateViewPdf'])->name('CreateViewPdf');
            Route::post('/ky_don_pdf', [StopStudyController::class, 'KyDonPdf'])->name('KyDonPdf');
            Route::get('/view_pdf/{id}', [StopStudyController::class, 'viewPdf'])->name('viewPdf');
            Route::get('/view_demo_pdf', [StopStudyController::class, 'viewDemoPdf'])->name('viewDemoPdf');
        });

        Route::name('MienGiamHp.')->prefix('mien-giam-hp')->group(function () {
            Route::get('/', [MienGiamHPController::class, 'index'])->name('index');
            Route::post('/create_view_pdf', [MienGiamHPController::class, 'CreateViewPdf'])->name('CreateViewPdf');
            Route::get('/view_pdf/{id}', [MienGiamHPController::class, 'viewPdf'])->name('viewPdf');
            Route::get('/view_demo_pdf', [MienGiamHPController::class, 'viewDemoPdf'])->name('viewDemoPdf');
            Route::post('/ky_don_pdf', [MienGiamHPController::class, 'KyDonPdf'])->name('KyDonPdf');
        });

        Route::name('TroCapXH.')->prefix('tro-cap-xh')->group(function () {
            Route::get('/', [TroCapXHController::class, 'index'])->name('index');
            Route::post('/create_view_pdf', [TroCapXHController::class, 'CreateViewPdf'])->name('CreateViewPdf');
            Route::get('/view_pdf/{id}', [TroCapXHController::class, 'viewPdf'])->name('viewPdf');
            Route::get('/view_demo_pdf', [TroCapXHController::class, 'viewDemoPdf'])->name('viewDemoPdf');
            Route::post('/ky_don_pdf', [TroCapXHController::class, 'KyDonPdf'])->name('KyDonPdf');
        });
        Route::name('CheDoChinhSach.')->prefix('che-do-chinh-sach')->group(function () {
            Route::get('/', [CheDoChinhSachController::class, 'index'])->name('index');
            Route::post('/create_view_pdf', [CheDoChinhSachController::class, 'CreateViewPdf'])->name('CreateViewPdf');
            Route::get('/view_pdf/{id}', [CheDoChinhSachController::class, 'viewPdf'])->name('viewPdf');
            Route::get('/view_demo_pdf', [CheDoChinhSachController::class, 'viewDemoPdf'])->name('viewDemoPdf');
        });
    });

    Route::name('Profile.GiaoVien.')->prefix('giao-vien')->group(function () {
        Route::get('/teacher-info', [ProfileGiaoVienController::class, 'index'])->name('info');
        Route::get('get-data-info', [ProfileGiaoVienController::class, 'getDataInfo'])->name('getDataInfo');
        Route::post('update', [ProfileGiaoVienController::class, 'update'])->name('update');
    });

    Route::middleware('role:giaoVien')->group(function () {
        Route::name('GiaoVien.')->prefix('giao-vien')->group(function () {
            Route::get('/', [GiaoVienController::class, 'index'])->name('index');
            Route::get('get-data', [GiaoVienController::class, 'getData'])->name('getData');
            Route::post('xacnhan', [GiaoVienController::class, 'xacnhan'])->name('xacnhan');
            Route::post('khongxacnhan', [GiaoVienController::class, 'khongxacnhan'])->name('khongxacnhan');
            Route::post('/ky_don_pdf', [GiaoVienController::class, 'KyDonPdf'])->name('KyDonPdf');
        });
        Route::name('Khoa.')->prefix('khoa')->group(function () {
            Route::get('/', [KhoaController::class, 'index'])->name('index');
            Route::get('get-data', [KhoaController::class, 'getData'])->name('getData');
            Route::post('xacnhan', [KhoaController::class, 'xacnhan'])->name('xacnhan');
            Route::post('khongxacnhan', [KhoaController::class, 'khongxacnhan'])->name('khongxacnhan');
            Route::post('/ky_don_pdf', [KhoaController::class, 'KyDonPdf'])->name('KyDonPdf');
        });
    });

    Route::middleware('role:notStudent')->group(function () {
        Route::name('MienGiamHP.')->prefix('mien-giam-hp')->group(function () {
            Route::get('tinh-so-luong', [MienGiamHPPhongDaoTaoController::class, 'tinhSoLuong'])->name('tinhSoLuong');
        });
    });
    Route::middleware('role:4')->group(function () {
        Route::name('PhongDaoTao.')->prefix('phong-dao-tao')->group(function () {

            Route::name('HoSoChungTu.')->prefix('ho-so-chung-tu')->group(function () {
                Route::get('/', [HoSoChungTuPhongDaoTaoController::class, 'index'])->name('index');
                Route::post('/save-all', [HoSoChungTuPhongDaoTaoController::class, 'saveAll'])->name('saveAll');
                Route::get('/get-data', [HoSoChungTuPhongDaoTaoController::class, 'getData'])->name('getData');
            });

            
            Route::get('/', [PhongDaoTaoController::class, 'index'])->name('index');
            Route::get('get-data', [PhongDaoTaoController::class, 'getData'])->name('getData');
            Route::post('bosunghs', [PhongDaoTaoController::class, 'bosunghs'])->name('bosunghs');
            Route::post('bosunghs-pdf', [PhongDaoTaoController::class, 'bosunghsPDF'])->name('bosunghsPDF');
            Route::get('getbosunghs/{id?}', [PhongDaoTaoController::class, 'getbosunghs'])->name('getbosunghs');
            Route::post('tiepnhanhs', [PhongDaoTaoController::class, 'tiepnhanhs'])->name('tiepnhanhs');
            Route::post('tiepnhanhs-pdf', [PhongDaoTaoController::class, 'tiepnhanhsPDF'])->name('tiepnhanhsPDF');

            Route::get('gettiepnhanhs/{id?}', [PhongDaoTaoController::class, 'gettiepnhanhs'])->name('gettiepnhanhs');
            Route::post('tuchoihs', [PhongDaoTaoController::class, 'tuchoihs'])->name('tuchoihs');
            Route::post('tuchoihs-pdf', [PhongDaoTaoController::class, 'tuchoihsPDF'])->name('tuchoihsPDF');

            Route::post('duyeths', [PhongDaoTaoController::class, 'duyeths'])->name('duyeths');
            Route::post('khongxacnhan', [PhongDaoTaoController::class, 'khongxacnhan'])->name('khongxacnhan');

            Route::name('MienGiamHP.')->prefix('mien-giam-hp')->group(function () {
                Route::get('/', [MienGiamHPPhongDaoTaoController::class, 'index'])->name('index');
                Route::get('get-data', [MienGiamHPPhongDaoTaoController::class, 'getData'])->name('getData');
                Route::get('update-percent', [MienGiamHPPhongDaoTaoController::class, 'updatePercent'])->name('updatePercent');
                Route::post('create-quyet-dinh', [MienGiamHPPhongDaoTaoController::class, 'createQuyetDinh'])->name('createQuyetDinh');
                Route::get('get-quyet-dinh', [MienGiamHPPhongDaoTaoController::class, 'getQuyetDinh'])->name('getQuyetDinh');
                Route::get('xoa-quyet-dinh', [MienGiamHPPhongDaoTaoController::class, 'xoaQuyetDinh'])->name('xoaQuyetDinh');
                Route::get('create-list', [MienGiamHPPhongDaoTaoController::class, 'createList'])->name('createList');
                Route::get('delete-list', [MienGiamHPPhongDaoTaoController::class, 'deleteList'])->name('deleteList');
                Route::get('gui-tb-sv', [MienGiamHPPhongDaoTaoController::class, 'guiTBSV'])->name('guiTBSV');
                Route::get('gui-tb-all', [MienGiamHPPhongDaoTaoController::class, 'guiTBSALL'])->name('guiTBSALL');
            });

            Route::name('TroCapXaHoi.')->prefix('tro-cap-xa-hoi')->group(function () {
                Route::get('/', [TroCapXaHoiPhongDaoTaoController::class, 'index'])->name('index');
                Route::get('get-data', [TroCapXaHoiPhongDaoTaoController::class, 'getData'])->name('getData');
                Route::get('update-trocap', [TroCapXaHoiPhongDaoTaoController::class, 'updateTroCap'])->name('updateTroCap');
                Route::get('create-list', [TroCapXaHoiPhongDaoTaoController::class, 'createList'])->name('createList');
                Route::get('delete-list', [TroCapXaHoiPhongDaoTaoController::class, 'deleteList'])->name('deleteList');
                Route::get('gui-tb-sv', [TroCapXaHoiPhongDaoTaoController::class, 'guiTBSV'])->name('guiTBSV');
                Route::get('gui-tb-all', [TroCapXaHoiPhongDaoTaoController::class, 'guiTBSALL'])->name('guiTBSALL');

                Route::post('create-quyet-dinh', [TroCapXaHoiPhongDaoTaoController::class, 'createQuyetDinh'])->name('createQuyetDinh');
                Route::get('get-quyet-dinh', [TroCapXaHoiPhongDaoTaoController::class, 'getQuyetDinh'])->name('getQuyetDinh');
                Route::get('xoa-quyet-dinh', [TroCapXaHoiPhongDaoTaoController::class, 'xoaQuyetDinh'])->name('xoaQuyetDinh');
            });
            Route::name('TroCapHocPhi.')->prefix('tro-cap-hoc-phi')->group(function () {
                Route::get('/', [TroCapHocPhiPhongDaoTaoController::class, 'index'])->name('index');
                Route::get('get-data', [TroCapHocPhiPhongDaoTaoController::class, 'getData'])->name('getData');
                Route::get('update-percent', [TroCapHocPhiPhongDaoTaoController::class, 'updatePercent'])->name('updatePercent');
                Route::get('create-list', [TroCapHocPhiPhongDaoTaoController::class, 'createList'])->name('createList');
                Route::get('delete-list', [TroCapHocPhiPhongDaoTaoController::class, 'deleteList'])->name('deleteList');
                Route::get('gui-tb-sv', [TroCapHocPhiPhongDaoTaoController::class, 'guiTBSV'])->name('guiTBSV');

                Route::get('gui-tb-all', [TroCapHocPhiPhongDaoTaoController::class, 'guiTBSALL'])->name('guiTBSALL');

                Route::post('create-quyet-dinh', [TroCapHocPhiPhongDaoTaoController::class, 'createQuyetDinh'])->name('createQuyetDinh');
                Route::get('get-quyet-dinh', [TroCapHocPhiPhongDaoTaoController::class, 'getQuyetDinh'])->name('getQuyetDinh');
                Route::get('xoa-quyet-dinh', [TroCapHocPhiPhongDaoTaoController::class, 'xoaQuyetDinh'])->name('xoaQuyetDinh');
            });
            Route::name('CheDoChinhSach.')->prefix('che-do-chinh-sach')->group(function () {
                Route::get('/', [CheDoChinhSachPhongDaoTaoController::class, 'index'])->name('index');
                Route::get('get-data', [CheDoChinhSachPhongDaoTaoController::class, 'getData'])->name('getData');
                Route::get('update-percent', [CheDoChinhSachPhongDaoTaoController::class, 'updatePercent'])->name('updatePercent');
                Route::get('create-list', [CheDoChinhSachPhongDaoTaoController::class, 'createList'])->name('createList');
                Route::get('delete-list', [CheDoChinhSachPhongDaoTaoController::class, 'deleteList'])->name('deleteList');
                Route::get('gui-tb-sv', [CheDoChinhSachPhongDaoTaoController::class, 'guiTBSV'])->name('guiTBSV');

                Route::post('import-file-ktx', [CheDoChinhSachPhongDaoTaoController::class, 'importFileKTX'])->name('importFileKTX');
                Route::post('import-file-diem-sv', [CheDoChinhSachPhongDaoTaoController::class, 'importFileDiemSV'])->name('importFileDiemSV');
                Route::post('import-qt-2', [CheDoChinhSachPhongDaoTaoController::class, 'ImportQTMGHP'])->name('ImportQTMGHP');
                Route::get('cancel-import', [CheDoChinhSachPhongDaoTaoController::class, 'cancelImport'])->name('cancelImport');

                Route::get('gui-tb-all', [CheDoChinhSachPhongDaoTaoController::class, 'guiTBSALL'])->name('guiTBSALL');

                Route::post('create-quyet-dinh', [CheDoChinhSachPhongDaoTaoController::class, 'createQuyetDinh'])->name('createQuyetDinh');
                Route::get('get-quyet-dinh', [CheDoChinhSachPhongDaoTaoController::class, 'getQuyetDinh'])->name('getQuyetDinh');
                Route::get('xoa-quyet-dinh', [CheDoChinhSachPhongDaoTaoController::class, 'xoaQuyetDinh'])->name('xoaQuyetDinh');
            });
        });
    });
    Route::middleware('role:5')->group(function () {
        Route::name('KeHoachTaiChinh.')->prefix('ke-hoach-tai-chinh')->group(function () {
            Route::get('/', [KeHoachTaiChinhController::class, 'index'])->name('index');
            Route::get('get-data', [KeHoachTaiChinhController::class, 'getData'])->name('getData');
            Route::post('xacnhan', [KeHoachTaiChinhController::class, 'xacnhan'])->name('xacnhan');
            Route::post('ky_don_pdf', [KeHoachTaiChinhController::class, 'KyDonPdf'])->name('KyDonPdf');
            Route::post('khongxacnhan', [KeHoachTaiChinhController::class, 'khongxacnhan'])->name('khongxacnhan');

            Route::name('MienGiamHP.')->prefix('mien-giam-hp')->group(function () {
                Route::get('/', [MienGiamHPKeHoachTaiChinhController::class, 'index'])->name('index');
                Route::get('get-data', [MienGiamHPKeHoachTaiChinhController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [MienGiamHPKeHoachTaiChinhController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [MienGiamHPKeHoachTaiChinhController::class, 'tuchoi'])->name('tuchoi');
            });

            Route::name('TroCapXaHoi.')->prefix('tro-cap-xa-hoi')->group(function () {
                Route::get('/', [TroCapXaHoiKeHoachTaiChinhController::class, 'index'])->name('index');
                Route::get('get-data', [TroCapXaHoiKeHoachTaiChinhController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [TroCapXaHoiKeHoachTaiChinhController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [TroCapXaHoiKeHoachTaiChinhController::class, 'tuchoi'])->name('tuchoi');
            });
            Route::name('TroCapHocPhi.')->prefix('tro-cap-hoc-phi')->group(function () {
                Route::get('/', [TroCapHocPhiKeHoachTaiChinhController::class, 'index'])->name('index');
                Route::get('get-data', [TroCapHocPhiKeHoachTaiChinhController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [TroCapHocPhiKeHoachTaiChinhController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [TroCapHocPhiKeHoachTaiChinhController::class, 'tuchoi'])->name('tuchoi');
            });

            Route::name('CheDoChinhSach.')->prefix('che-do-chinh-sach')->group(function () {
                Route::get('/', [CheDoChinhSachKeHoachTaiChinhController::class, 'index'])->name('index');
                Route::get('get-data', [CheDoChinhSachKeHoachTaiChinhController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [CheDoChinhSachKeHoachTaiChinhController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [CheDoChinhSachKeHoachTaiChinhController::class, 'tuchoi'])->name('tuchoi');
            });
        });
    });
    Route::middleware('role:6')->group(function () {
        Route::name('LanhDaoPhongDaoTao.')->prefix('lanh-dao-phong-dao-tao')->group(function () {
            Route::get('/', [LanhDaoPhongDaoTaoController::class, 'index'])->name('index');
            Route::get('get-data', [LanhDaoPhongDaoTaoController::class, 'getData'])->name('getData');
            Route::post('xacnhan', [LanhDaoPhongDaoTaoController::class, 'xacnhan'])->name('xacnhan');
            Route::post('xacnhan-pdf', [LanhDaoPhongDaoTaoController::class, 'xacnhanPDF'])->name('xacnhanPDF');

            Route::post('tuchoihs', [LanhDaoPhongDaoTaoController::class, 'tuchoihs'])->name('tuchoihs');
            Route::post('tuchoihs-pdf', [LanhDaoPhongDaoTaoController::class, 'tuchoihsPDF'])->name('tuchoihsPDF');

            Route::name('MienGiamHP.')->prefix('mien-giam-hp')->group(function () {
                Route::get('/', [MienGiamHPCanBoPhongDaoTaoController::class, 'index'])->name('index');
                Route::get('get-data', [MienGiamHPCanBoPhongDaoTaoController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [MienGiamHPCanBoPhongDaoTaoController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [MienGiamHPCanBoPhongDaoTaoController::class, 'tuchoi'])->name('tuchoi');
            });

            Route::name('TroCapXaHoi.')->prefix('tro-cap-xa-hoi')->group(function () {
                Route::get('/', [TroCapXaHoiCanBoPhongDaoTaoController::class, 'index'])->name('index');
                Route::get('get-data', [TroCapXaHoiCanBoPhongDaoTaoController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [TroCapXaHoiCanBoPhongDaoTaoController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [TroCapXaHoiCanBoPhongDaoTaoController::class, 'tuchoi'])->name('tuchoi');
            });
            Route::name('TroCapHocPhi.')->prefix('tro-cap-hoc-phi')->group(function () {
                Route::get('/', [TroCapHocPhiLanhDaoPhongDaoTaoController::class, 'index'])->name('index');
                Route::get('get-data', [TroCapHocPhiLanhDaoPhongDaoTaoController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [TroCapHocPhiLanhDaoPhongDaoTaoController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [TroCapHocPhiLanhDaoPhongDaoTaoController::class, 'tuchoi'])->name('tuchoi');
            });

            Route::name('CheDoChinhSach.')->prefix('che-do-chinh-sach')->group(function () {
                Route::get('/', [CheDoChinhSachLanhDaoPhongDaoTaoController::class, 'index'])->name('index');
                Route::get('get-data', [CheDoChinhSachLanhDaoPhongDaoTaoController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [CheDoChinhSachLanhDaoPhongDaoTaoController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [CheDoChinhSachLanhDaoPhongDaoTaoController::class, 'tuchoi'])->name('tuchoi');
            });
        });
    });
    Route::middleware('role:7')->group(function () {
        Route::name('LanhDaoTruong.')->prefix('lanh-dao-truong')->group(function () {
            Route::get('/', [LanhDaoTruongController::class, 'index'])->name('index');
            Route::get('get-data', [LanhDaoTruongController::class, 'getData'])->name('getData');
            Route::post('xacnhan', [LanhDaoTruongController::class, 'xacnhan'])->name('xacnhan');
            Route::post('xacnhan-pdf', [LanhDaoTruongController::class, 'xacnhanPDF'])->name('xacnhanPDF');
            Route::post('tuchoihs', [LanhDaoTruongController::class, 'tuchoihs'])->name('tuchoihs');
            Route::post('tuchoihs-pdf', [LanhDaoTruongController::class, 'tuchoihsPDF'])->name('tuchoihsPDF');

            Route::name('MienGiamHP.')->prefix('mien-giam-hp')->group(function () {
                Route::get('/', [MienGiamHPLanhDaoTruongController::class, 'index'])->name('index');
                Route::get('get-data', [MienGiamHPLanhDaoTruongController::class, 'getData'])->name('getData');
                Route::post('xacnhan-pdf', [MienGiamHPLanhDaoTruongController::class, 'xacnhanPDF'])->name('xacnhanPDF');
                Route::post('xacnhan', [MienGiamHPLanhDaoTruongController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [MienGiamHPLanhDaoTruongController::class, 'tuchoi'])->name('tuchoi');
            });

            Route::name('TroCapXaHoi.')->prefix('tro-cap-xa-hoi')->group(function () {
                Route::get('/', [TroCapXaHoiLanhDaoTruongController::class, 'index'])->name('index');
                Route::get('get-data', [TroCapXaHoiLanhDaoTruongController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [TroCapXaHoiLanhDaoTruongController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [TroCapXaHoiLanhDaoTruongController::class, 'tuchoi'])->name('tuchoi');
            });

            Route::name('TroCapHocPhi.')->prefix('tro-cap-hoc-phi')->group(function () {
                Route::get('/', [TroCapHocPhiLanhDaoTruongController::class, 'index'])->name('index');
                Route::get('get-data', [TroCapHocPhiLanhDaoTruongController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [TroCapHocPhiLanhDaoTruongController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [TroCapHocPhiLanhDaoTruongController::class, 'tuchoi'])->name('tuchoi');
            });

            Route::name('CheDoChinhSach.')->prefix('che-do-chinh-sach')->group(function () {
                Route::get('/', [CheDoChinhSachLanhDaoTruongController::class, 'index'])->name('index');
                Route::get('get-data', [CheDoChinhSachLanhDaoTruongController::class, 'getData'])->name('getData');
                Route::post('xacnhan', [CheDoChinhSachLanhDaoTruongController::class, 'xacnhan'])->name('xacnhan');
                Route::get('tuchoi', [CheDoChinhSachLanhDaoTruongController::class, 'tuchoi'])->name('tuchoi');
            });
        });
    });
    //tất cả được vào trừ sinh viên
    Route::middleware('role:notStudent')->group(function () {
        Route::name('GiaoVien.')->prefix('giao-vien')->group(function () {
            Route::get('get-data-child/{id?}', [GiaoVienController::class, 'getDataChild'])->name('getDataChild');
        });
    });
    Route::middleware('role:studentManager')->name('studentManager.')
        ->prefix('student-manager')->group(function () {
            Route::get('/', [StudentManagerController::class, 'index'])->name('index');
            Route::get('get-data', [StudentManagerController::class, 'getData'])->name('getData');
            Route::get('get-data/{id?}', [StudentManagerController::class, 'getDataChild'])->name('getDataChild');
            Route::get('detele/{id?}', [StudentManagerController::class, 'detele'])->name('detele');
            Route::post('create', [StudentManagerController::class, 'create'])->name('create');
            Route::post('update/{id?}', [StudentManagerController::class, 'update'])->name('update');
            Route::post('import-file', [StudentManagerController::class, 'importFile'])->name('importFile');

            Route::get('reset-pass/{id?}', [StudentManagerController::class, 'resetPass'])->name('resetPass');

            Route::post('status', [StudentManagerController::class, 'status'])->name('status');
        });

    Route::middleware('role:khoaManager')->name('khoaManager.')->prefix('khoa-manager')->group(function () {
        Route::get('/', [KhoaManagerController::class, 'index'])->name('index');
        Route::get('get-data', [KhoaManagerController::class, 'getData'])->name('getData');
        Route::get('get-data/{id?}', [KhoaManagerController::class, 'getDataChild'])->name('getDataChild');
        Route::get('detele/{id?}', [KhoaManagerController::class, 'detele'])->name('detele');
        Route::post('create', [KhoaManagerController::class, 'create'])->name('create');
        Route::post('update/{id?}', [KhoaManagerController::class, 'update'])->name('update');
        Route::post('import-file', [KhoaManagerController::class, 'importFile'])->name('importFile');

        Route::get('nganh', [KhoaManagerController::class, 'nganh'])->name('nganh');
        Route::get('nganh/{id?}', [KhoaManagerController::class, 'nganhKhoa'])->name('nganhKhoa');
        Route::get('lop/{id?}', [KhoaManagerController::class, 'lop'])->name('lop');
    });

    Route::middleware('role:classManager')->name('classManager.')->prefix('class-manager')->group(function () {
        Route::get('/', [ClassManagerController::class, 'index'])->name('index');
        Route::get('get-data', [ClassManagerController::class, 'getData'])->name('getData');
        Route::get('get-data/{id?}', [ClassManagerController::class, 'getDataChild'])->name('getDataChild');
        Route::get('detele/{id?}', [ClassManagerController::class, 'detele'])->name('detele');
        Route::post('create', [ClassManagerController::class, 'create'])->name('create');
        Route::post('update/{id?}', [ClassManagerController::class, 'update'])->name('update');
        Route::post('import-file', [ClassManagerController::class, 'importFile'])->name('importFile');
    });

    Route::middleware('role:teacherManager')->name('teacherManager.')->prefix('teacher-manager')->group(function () {
        Route::get('/', [TeacherManagerController::class, 'index'])->name('index');
        Route::get('get-data', [TeacherManagerController::class, 'getData'])->name('getData');
        Route::get('get-data/{id?}', [TeacherManagerController::class, 'getDataChild'])->name('getDataChild');
        Route::get('detele/{id?}', [TeacherManagerController::class, 'detele'])->name('detele');
        Route::post('create', [TeacherManagerController::class, 'create'])->name('create');
        Route::post('update/{id?}', [TeacherManagerController::class, 'update'])->name('update');
        Route::post('import-file', [TeacherManagerController::class, 'importFile'])->name('importFile');

        Route::get('reset-pass/{id?}', [TeacherManagerController::class, 'resetPass'])->name('resetPass');
    });

    Route::name('notification.')->prefix('notification')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('read-all', [NotificationController::class, 'readAll'])->name('readAll');
        Route::get('/{type}', [NotificationController::class, 'viewNotifi'])->name('viewNotifi');
    });

    Route::name('otp.')->prefix('otp')->group(function () {
        Route::get('chu-ky', [OtpController::class, 'createOtpChuKy'])->name('createOtpChuKy');
        Route::get('check-chu-ky/{otp?}', [OtpController::class, 'checkOtpChuKy'])->name('checkOtpChuKy');
        Route::get("check-info-signature",[OtpController::class,'checkSignature'])->name('checkSignature');
    });
    
    
});

Route::get('pdf', [DocumentController::class, 'index']);

Route::name('phieu.')->prefix('phieu')->group(function () {
    Route::get('giai-quyet-cong-viec/{id?}', [PhieuController::class, 'giaQuyetCongViec'])->name('giaQuyetCongViec');
    Route::get('get-data/{id?}', [PhieuController::class, 'getData'])->name('getData');
    Route::get('{id?}', [PhieuController::class, 'index'])->name('index');
});
