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
                            Thông tin xử lý đơn</h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <div class="d-flex flex-row flex-wrap">
                            <div class="d-flex flex-row me-3">
                                <div class="ki-duotone ki-check-square fs-2x cursor-pointer text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </div>
                                <p class="m-auto fw-medium">: Tiếp nhận hồ sơ</p>
                            </div>
                            <div class="d-flex flex-row me-3">
                                <div class="ki-duotone ki-file-added fs-2x cursor-pointer text-primary">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </div>
                                <p class="m-auto fw-medium">: Duyệt hồ sơ</p>
                            </div>
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
                                <div class="ki-duotone ki-information-2 fs-2x  text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </div>
                                <p class="m-auto fw-medium">: Tiến trình xử lý</p>
                            </div>
                            <div class="d-flex flex-row me-3">
                                <div class="ki-duotone ki-document fs-2x  text-dark">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </div>
                                <p class="m-auto fw-medium">: Chi tiết đơn</p>
                            </div>
                            <div class="d-flex flex-row me-3">
                                <div class="ki-duotone ki-abstract-4 fs-2x  text-dark">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </div>
                                <p class="m-auto fw-medium">: Phiếu theo dõi giải quyết công việc</p>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 justify-content-end flex-wrap d-flex">
                        <div class="card-toolbar">
                            <!--begin::Menu-->
                            <button class="btn btn-icon btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
                                <i class="ki-outline ki-dots-square fs-2x text-dark me-n1"></i>
                            </button>
                            <!--begin::Menu 3-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                                <!--begin::Heading-->
                                <div class="menu-item px-3">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">{{ __('Chức năng') }}</div>
                                </div>
                                <!--end::Heading-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="{{ route('PhongDaoTao.MienGiamHP.index') }}" target="_blank" class="menu-link px-3">Danh sách miễn giảm học phí</a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="{{ route('PhongDaoTao.TroCapXaHoi.index') }}" target="_blank" class="menu-link px-3">Danh sách trợ cấp xã hội</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu 3-->
                            <!--end::Menu-->
                        </div>
                    </div>
                    <div class="w-100 d-flex">
                        @if ($tb_miengiamhp > 0)
                            <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-2 w-100">
                                <i class="ki-outline ki-snapchat text-warning fs-1 me-5"></i>
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class=" fw-bold text-gray-800 text-hover-warning fs-4">Thông báo</a>
                                    <span class="fs-5 fw-semibold d-block">
                                        Cần gửi danh sách miễn giảm học phí đến khoa và sinh viên
                                    </span>
                                </div>
                                <a href="{{ route('PhongDaoTao.MienGiamHP.guiTBSV') }}" class="btn btn-primary">Gửi</a>
                            </div>
                        @endif
                        @if ($tb_trocapxahoi > 0)
                            <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-2 w-100">
                                <i class="ki-outline ki-snapchat text-warning fs-1 me-5"></i>
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class=" fw-bold text-gray-800 text-hover-warning fs-4">Thông báo</a>
                                    <span class="fs-5 fw-semibold d-block">
                                        Cần gửi danh sách trợ cấp xã hội đến khoa và sinh viên
                                    </span>
                                </div>
                                <a href="{{ route('PhongDaoTao.TroCapXaHoi.guiTBSV') }}" class="btn btn-primary">Gửi</a>
                            </div>
                        @endif
                    </div>
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
