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
                    <div class="d-flex align-items-center gap-2 gap-lg-3 mt-3 w-100">
                        <div id="import-file-ktx" class="btn btn-flex btn-outline h-40px fs-7 fw-bold position-relative cursor-pointer mr-3">
                            <input class="cursor-pointer m-0 p-0 top-0 left-0 w-100 h-100 position-absolute" style="opacity: 0" type="file" id="avatar" name="avatar" accept=".csv" />
                            {{ __('Import sinh viên ở ktx') }}
                        </div>
                        <div id="import-file-ktx" class="btn btn-flex btn-outline h-40px fs-7 fw-bold position-relative cursor-pointer mr-3">
                            Thêm SV từ quy trình 3 thuộc 7 ngành
                        </div>
                        <div id="import-file-ktx" class="btn btn-flex btn-outline h-40px fs-7 fw-bold position-relative cursor-pointer mr-3">
                            <input class="cursor-pointer m-0 p-0 top-0 left-0 w-100 h-100 position-absolute" style="opacity: 0" type="file" id="avatar" name="avatar" accept=".csv" />
                            {{ __('Import danh điểm sinh viên thuộc 20%') }}
                        </div>
                        <div id="btn_doituong" class="btn btn-flex btn-outline h-40px fs-7 fw-bold position-relative cursor-pointer mr-3">
                            Đối tượng
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
                                    <a href="" target="_blank" class="menu-link px-3">Xem phiếu</a>
                                    <a href="{{ route('PhongDaoTao.TroCapXaHoi.createList') }}" class="menu-link px-3">Tạo danh sách và thông báo đến cán bộ phòng đào tạo</a>
                                    <a href="{{ route('PhongDaoTao.TroCapXaHoi.deleteList') }}" class="menu-link px-3">Xóa danh sách</a>
                                    <a href="" class="menu-link px-3">Yêu cầu bổ xung thông tin đối với sinh viên là dân tộc thiểu số</a>
                                </div>
                                <!--end::Menu item-->

                            </div>
                            <!--end::Menu 3-->
                            <!--end::Menu-->
                        </div>
                    </div>
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
