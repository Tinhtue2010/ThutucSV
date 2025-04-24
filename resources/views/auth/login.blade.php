@extends('layout.auth_layout')

@section('content')
    <div class="d-flex flex-row h-100 ">
        <div class="d-none col-6 d-md-flex position-relative">
            <img class="rounded-start-4 position-absolute h-100 w-100 object-fit-cover" src="/assets/custom/mainviewUHL.jpg"
                alt="">
            <div class="rounded-start-4 position-absolute opacity-25 h-100 w-100 top-0 right-0 bg-black"></div>
        </div>
        <!--begin::Form-->
        <form class="form col-12 col-md-6 p-20 bg-white rounded-4" novalidate="novalidate" id="kt_sign_in_form" action="#">
            @csrf
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Heading-->
                <div class="text-start mb-10">
                    <!--begin::Title-->
                    <h1 class="text-dark mb-3 fs-3x" data-kt-translate="sign-in-title">Đăng nhập</h1>
                    <!--end::Title-->
                </div>
                <!--begin::Heading-->
                <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                    <!--begin::Label-->
                    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                        <span class="required">Tài khoản</span>
                    </label>
                    <!--end::Label-->
                    <input type="text" placeholder="Mã sinh viên" name="username" autocomplete="off"
                        class="form-control" />
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                </div>
                <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                    <!--begin::Label-->
                    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                        <span class="required">Mật khẩu</span>
                    </label>
                    <!--end::Label-->
                    <input type="password" placeholder="Mật khẩu" name="password" autocomplete="off"
                        data-kt-translate="sign-in-input-password" class="form-control" />
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                </div>
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
                    <div></div>
                    <!--begin::Link-->
                    <a href="#" class="link-success" data-kt-translate="sign-in-forgot-password">Quên
                        mật khẩu</a>
                    <!--end::Link-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Actions-->
                <div class="d-flex flex-stack">
                    <!--begin::Submit-->
                    <button id="kt_sign_in_submit" class="btn btn-success me-2 flex-shrink-0">
                        <!--begin::Indicator label-->
                        <span class="indicator-label" data-kt-translate="sign-in-submit">Đăng
                            nhập</span>
                        <!--end::Indicator label-->
                        <!--begin::Indicator progress-->
                        <span class="indicator-progress">
                            <span data-kt-translate="general-progress">Please wait...</span>
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                        <!--end::Indicator progress-->
                    </button>
                    <!--end::Submit-->
                </div>
                <!--end::Actions-->
            </div>
            <!--begin::Body-->
        </form>
        <!--end::Form-->
    </div>
@endsection

@push('js')
    <script>
        @include('auth.script')
    </script>
@endpush
