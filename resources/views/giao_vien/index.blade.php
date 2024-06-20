@extends('layout.main_layout')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        @include('giao_vien.header')
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Name -->
                <div class="card mb-5 mb-xl-10">
                    <div class="card-body pt-9 pb-0">
                        <!--begin::Details-->
                        <div class="d-flex flex-wrap flex-sm-nowrap">
                            <!--begin: Pic-->
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed">
                                    <img src="{{ asset('assets/custom/avatar.png') }}" alt="image">
                                </div>
                            </div>
                            <!--end::Pic-->
                            <!--begin::Info-->
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <!--begin::User-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Name-->
                                        <div class="d-flex align-items-center mb-2">
                                            <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $teacher->full_name }}</a>
                                        </div>
                                        <!--end::Name-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                                <i class="ki-outline ki-abstract-39 fs-4 me-1"></i>{{ $teacher->khoa_name }}</a>
                                            <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                                <i class="ki-outline ki-sms fs-4 me-1"></i>{{ $teacher->email }}</a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::User-->
                                </div>
                                <!--end::Title-->
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap flex-stack">
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column flex-grow-1 pe-8">
                                        <!--begin::Stats-->
                                        <div class="d-flex flex-wrap">
                                            <!--begin::Stat-->
                                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                <!--begin::Number-->
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-archive-tick fs-3 text-success me-2"></i>
                                                    <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="{{ $student->so_tien }}" data-kt-initialized="1">Chức danh</div>
                                                </div>
                                                <!--end::Number-->
                                                <!--begin::Label-->
                                                <div class="fw-semibold fs-6 text-gray-400">{{$teacher->chuc_danh}}</div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Stat-->
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Progress-->
                                    <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                                        <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                            <span class="fw-semibold fs-6 text-gray-400">Hoàn thành hồ sơ</span>
                                            <span class="fw-bold fs-6">{{ $percent }}%</span>
                                        </div>
                                        <div class="h-5px mx-3 w-100 bg-light mb-3">
                                            <div class="bg-success rounded h-5px" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <!--end::Progress-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                    </div>
                </div>
                <!--end::Name-->
                <!--teacher info-->
                <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                    <!--begin::Card header-->
                    <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">Thông tin cá nhân</h3>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Action-->
                        <div onclick="btnEdit()" class="btn btn-sm btn-primary align-self-center">Chỉnh sửa</div>
                        <!--end::Action-->
                    </div>
                    <!--begin::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body p-9">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Họ và tên</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $teacher->full_name }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Địa chỉ</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $teacher->dia_chi }}
                                </span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Số điện thoại</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $teacher->sdt }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Email</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $teacher->email }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <!--begin::Label-->
                            <label class="col-lg-4 fw-semibold text-muted">Chức danh</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $teacher->chuc_danh }}</span>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::teacher info-->














                <!--begin::Products-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                <input type="text" data-kt-ecommerce-product-filter="search"
                                       class="form-control form-control-solid w-250px ps-12" placeholder="Tên, mã sinh viên"/>
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <div class="w-100 mw-150px">
                                <label class="form-label">Loại hồ sơ</label>
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid filter-select" data-name="type" data-control="select2" data-placeholder="Loại hồ sơ">
                                    <option></option>
                                    <option value="all">Hiển thị tất cả</option>
                                    <option value="0">Đơn xin rút hồ sơ</option>
                                    <option value="1">Đơn xin miễn giảm học phí</option>
                                    <option value="2">Đơn xin trợ cấp xã hội</option>
                                    <option value="3">Đơn xin chế độ chính sách</option>
                                </select>
                                <!--end::Select2-->
                            </div>
                            <div class="w-100 mw-150px">
                                <label class="form-label">Năm</label>
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid filter-select" data-name="year" data-control="select2" data-placeholder="Năm">
                                    <option></option>
                                    <option value="all">Hiển thị tất cả</option>
                                    @for ($year = 2000; $year <= 2100; $year++)
                                        <option @if($year == date('Y')) @endif value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <!--end::Select2-->
                            </div>
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="frm-table">
                            <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th></th>
                                <th class="text-nowrap" data-name="student_code">{{__('Mã sinh viên')}}</th>
                                <th class="text-nowrap" data-name="full_name">{{ __('Họ và tên') }}</th>
                                <th class="text-nowrap" data-name="lop_name">{{ __('Lớp') }}</th>
                                <th class="text-nowrap" data-name="created_at">{{ __('Thời gian') }}</th>
                                <th class="text-nowrap" data-name="type">{{ __('Loại') }}</th>
                                <th class="text-nowrap" data-name="status">{{ __('Trạng thái') }}</th>
                                <td class="text-nowrap">{{ __('Chức năng') }}</td>
                            </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            </tbody>
                        </table>
                        <!--end::Table-->
                        <div class="row pt-5 pb-5">
                            <div
                                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="d-flex flex-row">
                                    <div class="dataTables_length" id="length-table"><label><select
                                                name="kt_ecommerce_products_table_length"
                                                aria-controls="kt_ecommerce_products_table"
                                                class="form-select form-select-sm form-select-solid">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select></label></div>
                                </div>
                            </div>
                            <div
                                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="dataTables_paginate paging_simple_numbers"
                                     id="kt_ecommerce_products_table_paginate">
                                    <ul class="pagination">
                                        <li class="paginate_button page-item previous disabled"
                                            id="kt_ecommerce_products_table_previous">
                                            <a href="#" aria-controls="kt_ecommerce_products_table" data-dt-idx="0"
                                               tabindex="0" class="page-link"><i class="previous"></i></a>
                                        </li>
                                        <li class="paginate_button page-item active">
                                            <a href="#" aria-controls="kt_ecommerce_products_table" data-dt-idx="1"
                                               tabindex="0" class="page-link">1</a>
                                        </li>
                                        <li class="paginate_button page-item next"
                                            id="kt_ecommerce_products_table_next">
                                            <a href="#" aria-controls="kt_ecommerce_products_table" data-dt-idx="6"
                                               tabindex="0" class="page-link">
                                                <i class="next"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Products-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    @include('common.select_status_ho_so')
    @include('giao_vien.table')
    @include('giao_vien.xacnhan',['target'=>'xacnhan'])
    @include('giao_vien.khongxacnhan',['target'=>'khongxacnhan'])
    @include('giao_vien.chitiet',['target'=>'chitiet'])
    @include('giao_vien.tientrinh',['target'=>'tientrinh'])
    @include('giao_vien.field')
    @include('giao_vien.validate')
@endsection
