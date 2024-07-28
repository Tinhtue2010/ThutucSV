        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Page title-->
                    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
                            Danh sách chế độ chính sách
                        </h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <div class="d-flex flex-row flex-wrap">
                            <div class="d-flex flex-row me-3">
                                <div class="ki-duotone ki-update-folder fs-2x cursor-pointer text-danger">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </div>
                                <p class="m-auto fw-medium">: Lập phiếu bổ sung hồ sơ</p>
                            </div>
                            <div class="d-flex flex-row me-3">
                                <div class="ki-duotone ki-minus-square fs-2x cursor-pointer text-danger">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </div>
                                <p class="m-auto fw-medium">: Lập phiếu từ chối giải quyết hồ sơ</p>
                            </div>
                            <div class="d-flex flex-row me-3">
                                <div class="ki-duotone ki-document fs-2x  text-dark">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </div>
                                <p class="m-auto fw-medium">: Chi tiết đơn</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex w-100 flex-wrap">
                        <div onclick="taoQuyetDinhMGHP()" class="btn btn-secondary">Tạo, cập nhật quyết định</div>
                        <div onclick="quyet_dinh_danh_sach()"  class="btn btn-secondary ms-3 cursor-pointer">Xem quyết định và danh sách</div>
                        <a href="{{ route('PhongDaoTao.CheDoChinhSach.xoaQuyetDinh') }}" class="btn btn-danger ms-3">Xóa quyết định</a>
                        <a href="{{ route('PhongDaoTao.CheDoChinhSach.guiTBSALL') }}" class="btn btn-success ms-3">Thông báo và khóa DS</a>
                    </div>
                    <div class="d-flex align-items-center gap-2 gap-lg-3 mt-3 w-100">
                        <div id="import-file-diem-sv" class="btn btn-flex btn-outline h-40px fs-7 fw-bold position-relative cursor-pointer mr-3">
                            <input class="cursor-pointer m-0 p-0 top-0 left-0 w-100 h-100 position-absolute" style="opacity: 0" type="file" id="avatar" name="avatar" accept=".csv" />
                            {{ __('Thêm danh sách danh điểm sinh viên thuộc 20%') }}
                        </div>
                        <div id="import-file-ktx" class="btn btn-flex btn-outline h-40px fs-7 fw-bold position-relative cursor-pointer mr-3">
                            <input class="cursor-pointer m-0 p-0 top-0 left-0 w-100 h-100 position-absolute" style="opacity: 0" type="file" id="avatar" name="avatar" accept=".csv" />
                            {{ __('Thêm danh sách sinh viên ở ktx') }}
                        </div>
                        <div onclick="import_qt_3()" class="btn btn-flex btn-outline h-40px fs-7 fw-bold position-relative cursor-pointer mr-3">
                            Thêm SV từ nghị định 81
                        </div>

                        <a href="{{ route('PhongDaoTao.CheDoChinhSach.cancelImport') }}" class="btn btn-danger ms-3">Hủy các import</a>
                    </div>
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
