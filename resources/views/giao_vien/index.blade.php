@extends('layout.main_layout')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        @include('giao_vien.header')
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
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
                                    class="form-control w-250px ps-12" placeholder="Tên, mã sinh viên" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <div class="w-100 mw-150px">
                                <label class="form-label">Loại hồ sơ</label>
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid filter-select" data-name="type"
                                    data-control="select2" data-placeholder="Loại hồ sơ">
                                    <option></option>
                                    <option value="all">Hiển thị tất cả</option>
                                    <option value="0">Đơn xin rút hồ sơ</option>
                                    <option value="1">Đơn xin miễn giảm học phí</option>
                                    <option value="2">Đơn xin trợ cấp xã hội</option>
                                    <option value="3">Đơn xin trợ cấp học phí</option>
                                    <option value="4">Đơn xin chế độ chính sách</option>
                                </select>
                                <!--end::Select2-->
                            </div>
                            <div class="w-100 mw-150px">
                                <label class="form-label">Năm</label>
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid filter-select" data-name="year"
                                    data-control="select2" data-placeholder="Năm">
                                    <option></option>
                                    <option value="all">Hiển thị tất cả</option>
                                    @for ($year = 2000; $year <= 2100; $year++)
                                        <option @if ($year == date('Y')) selected @endif
                                            value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <!--end::Select2-->
                            </div>
                            @include('common.select_status_ho_so')


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
                                    <th class="text-nowrap" data-name="student_code">{{ __('Mã sinh viên') }}</th>
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
                                        <li class="paginate_button page-item next" id="kt_ecommerce_products_table_next">
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
    @include('giao_vien.table')
    @include('giao_vien.xacnhan', ['target' => 'xacnhan'])
    @include('giao_vien.khongxacnhan', ['target' => 'khongxacnhan'])
    @include('giao_vien.chitiet', ['target' => 'chitiet'])
    @include('giao_vien.tientrinh', ['target' => 'tientrinh'])
@endsection
