@extends('layout.main_layout')

@section('content') 
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar pt-7 pt-lg-10">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                    <!--begin::Actions-->
                    <div></div>
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="" class="btn btn-flex btn-secondary h-40px fs-7 fw-bold">Đánh đã đọc</a>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="shadow d-flex align-items-center bg-light-warning rounded p-5 mb-7">
                    <i class="ki-outline ki-snapchat text-warning fs-1 me-5"></i>
                    <!--begin::Title-->
                    <div class="flex-grow-1 me-2">
                        <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">Thông báo</a>
                        <span class="fs-5 fw-semibold d-block">hồ sơ của bạn đã được gửi đi</span>
                    </div>
                    <!--end::Title-->
                </div>
                <div class="shadow d-flex align-items-center bg-light-secondary rounded p-5 mb-7">
                    <i class="ki-outline ki-snapchat text-secondary fs-1 me-5"></i>
                    <!--begin::Title-->
                    <div class="flex-grow-1 me-2">
                        <a href="#" class=" fw-bold text-gray-800 text-hover-primary fs-4">Thông báo</a>
                        <span class="fs-5 fw-semibold d-block">hồ sơ của bạn đã được gửi đi</span>
                    </div>
                    <!--end::Title-->
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
@endsection
