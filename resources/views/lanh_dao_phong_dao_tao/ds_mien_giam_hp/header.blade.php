        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container"
                class="app-container container-fluid d-flex align-items-stretch d-flex flex-column">
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
                            <div onclick="xacnhanDS()" class="btn btn-primary me-2">Phê duyệt danh sách</div>
                            <a href="{{ route('LanhDaoPhongDaoTao.MienGiamHP.tuchoi') }}" class="btn btn-warning">Từ
                                chối danh sách</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex w-100 flex-wrap mt-5">
                    @if (isset($hoso) && $hoso)
                        <a target="_blank" href="{{ route('downloadDanhSach', ['nam_hoc' => $hoso->nam_hoc, 'ky_hoc' => $hoso->ky_hoc, 'type' => 1]) }}" class="btn btn-secondary ms-3">Xem
                            danh sách</a>
                        <a target="_blank" href="/storage/{{ $hoso->file_quyet_dinh }}"
                            class="btn btn-secondary ms-3">Xem quyết định</a>
                    @endif
                </div>
                <br>
                <!--end::Toolbar wrapper-->
                <div id="tinhtong" class="w-100">
                </div>
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
