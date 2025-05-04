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
                            Danh sách sinh viên được trợ cấp học phí
                        </h1>
                        <!--end::Title-->
                    </div>
                    
                    <!--end::Page title-->
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <div class="d-flex flex-row flex-wrap">
                            <div onclick="xacnhanDS()" class="btn btn-primary me-2">Phê duyệt danh sách</div>
                            <a href="{{ route('LanhDaoTruong.TroCapHocPhi.tuchoi') }}" class="btn btn-warning">Từ chối danh sách</a>
                        </div>
                    </div>
                    <div class="d-flex w-100 flex-wrap mt-5">
                        @if (isset($hoso) && $hoso)
                            <a target="_blank" href="/storage/{{ $hoso->file_list }}" class="btn btn-secondary ms-3">Xem
                                danh sách</a>
                            <a target="_blank" href="/storage/{{ $hoso->file_quyet_dinh }}"
                                class="btn btn-secondary ms-3">Xem quyết định</a>
                        @endif
                    </div>
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
