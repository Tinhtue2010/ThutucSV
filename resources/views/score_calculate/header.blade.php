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
                            Quản lý thông tin điểm sinh viên</h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Page title-->
                    @if (Role(0))
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                        </div>
                        <!--end::Actions-->
                    @endif
                    <div class="d-flex w-100 flex-wrap">
                        <a target="_blank" id="download-link"
                            data-base-url="{{ route('scoreCalculate.downloadDanhSach', ['nam_hoc' => $nam_hoc_goc, 'ky_hoc' => $ky_hoc_goc, 'topPercent' => $topPercent]) }}"
                            href="#" onclick="handleDownload(event)" class="btn btn-secondary ms-3">Xem danh
                            sách</a>
                        <a id="download-link"
                            data-base-url="{{ route('scoreCalculate.updateDT3', ['nam_hoc' => $nam_hoc_goc, 'ky_hoc' => $ky_hoc_goc, 'topPercent' => $topPercent]) }}"
                            href="#" onclick="handleUpdate(event)" class="btn btn-secondary ms-3">Cập nhật vào đối tượng 3</a>

                        <button onclick="addToHiddenInsert(event)" class="btn btn-success ms-3" id="btn-add">Thêm sinh viên</button>

                            {{-- <div onclick="xacnhanDS()" class="btn btn-primary me-2">Cập nhật vào đối tượng 2</div> --}}

                        {{-- <a
                            href="{{ route('scoreCalculate.updateDT3', ['nam_hoc' => $nam_hoc_goc, 'ky_hoc' => $ky_hoc_goc, 'topPercent' => $topPercent]) }}"
                            class="btn btn-secondary ms-3">Cập nhật vào đối tượng 2</a> --}}
                    </div>
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
