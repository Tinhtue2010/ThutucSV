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
                            Quản lý sinh viên</h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                    <!--begin::Actions-->
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        {{-- <div id="btn-totnghiep" class="btn btn-flex btn-primary h-40px fs-7 fw-bold">Sinh viên tốt nghiệp</div>
                        <div id="btn-chuatotnghiep" class="btn btn-flex btn-primary h-40px fs-7 fw-bold">Sinh viên đang học</div> --}}
                            <div id="import-file" class="btn btn-flex btn-primary h-40px fs-7 fw-bold position-relative cursor-pointer mr-3">
                                <input class="m-0 p-0 top-0 left-0 w-100 h-100 position-absolute" style="opacity: 0" type="file" id="avatar" name="avatar" accept=".csv" />
                                {{__("Thêm danh sách sinh viên")}}
                            </div>
                        <a href="#" class="btn btn-flex btn-primary h-40px fs-7 fw-bold" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_new_target">Thêm sinh viên</a>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        