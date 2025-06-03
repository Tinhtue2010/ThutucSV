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
                            Danh sách sinh viên được miễn giảm học phí
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
                        @if (isset($hoso) && $hoso)
                            <a target="_blank" href="{{ route('downloadDanhSach', ['nam_hoc' => $hoso->nam_hoc, 'ky_hoc' => $hoso->ky_hoc, 'type' => 1]) }}"
                                class="btn btn-secondary ms-3">Xem danh sách</a>
                            <a target="_blank" href="/storage/{{ $hoso->file_quyet_dinh }}"
                                class="btn btn-secondary ms-3">Xem quyết định</a>
                            <a href="{{ route('PhongDaoTao.MienGiamHP.xoaQuyetDinh') }}" class="btn btn-danger ms-3">Xóa
                                quyết định</a>
                            <a href="{{ route('PhongDaoTao.MienGiamHP.guiTBSALL') }}" class="btn btn-success ms-3">Thông
                                báo và khóa DS</a>
                        @endif
                    </div>
                    <div id="tinhtong" class="me-auto ms-0">
                    </div> 
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
