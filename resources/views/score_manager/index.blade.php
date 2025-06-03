@extends('layout.main_layout')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        @include('score_manager.header')
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
                                    class="form-control w-250px ps-12" placeholder="Mã sv, mã HS, Họ Tên" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <div class="w-100 mw-150px">
                                <label class="form-label">Lớp</label>
                                <!--begin::Select2-->
                                <select class="form-select  filter-select" data-name="lop_id" data-control="select2"
                                    data-placeholder="Lớp">
                                    <option></option>
                                    <option value="all">Hiển thị tất cả</option>
                                    @foreach ($lops as $item)
                                        <option value="{{ $item->ma_lop }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Select2-->
                            </div>
                            <div class="w-100 mw-150px">
                                <label class="form-label">Khoá học</label>
                                <!--begin::Select2-->
                                <select class="form-select  filter-select" data-name="khoa_hoc" data-control="select2"
                                    data-placeholder="Khoá học">
                                    <option></option>
                                    <option value="all">Hiển thị tất cả</option>
                                    @for ($year = 1; $year <= 100; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                                <!--end::Select2-->
                            </div>
                            <div class="w-100 mw-150px">
                                <label class="form-label">Học kỳ</label>
                                <!--begin::Select2-->
                                <select class="form-select  filter-select" data-name="ky_hoc" data-control="select2"
                                    data-placeholder="Học kỳ">
                                    <option></option>
                                    <option value="all">Hiển thị tất cả</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                                <!--end::Select2-->
                            </div>
                            <div class="w-100 mw-150px">
                                <label class="form-label">Năm học</label>
                                <!--begin::Select2-->
                                <select class="form-select  filter-select" data-name="nam_hoc" data-control="select2"
                                    data-placeholder="Năm học">
                                    <option></option>
                                    <option value="all">Hiển thị tất cả</option>
                                    @for ($year = 2001; $year <= 2030; $year++)
                                        <option @if ($year == date('Y')) selected @endif
                                            value="{{ $year - 1 }}-{{ $year }}">
                                            {{ $year - 1 }}-{{ $year }}</option>
                                    @endfor
                                </select>
                                <!--end::Select2-->
                            </div>


                            <div class="w-100 mw-150px">
                                <label class="form-label">Trạng thái</label>

                                <select class="form-select  filter-select" data-name="status" data-control="select2"
                                    data-hide-search="true" data-placeholder="Trạng thái">
                                    <option></option>
                                    <option value="all">Hiển thị tất cả</option>
                                    <option value="0">Đang học</option>
                                    <option value="2">Đã ra trường</option>
                                    <option value="1">Rút hồ sơ</option>
                                </select>
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
                                    <th><input type="checkbox" onchange="selectAll(this)" /></th>
                                    <th class="text-nowrap" data-name="student_code">{{ __('Mã sinh viên') }}</th>
                                    <th class="text-nowrap" data-name="full_name">{{ __('Họ và tên') }}</th>
                                    <th class="text-nowrap" data-name="lop_name">{{ __('Lớp') }}</th>
                                    <th class="text-nowrap" data-name="khoa_name">{{ __('Khoa') }}</th>
                                    <th class="text-nowrap" data-name="ky_hoc">{{ __('Học kỳ') }}</th>
                                    <th class="text-nowrap" data-name="nam_hoc">{{ __('Năm học') }}</th>
                                    <th class="text-nowrap" data-name="diem_ht">{{ __('Điểm HT') }}</th>
                                    <th class="text-nowrap" data-name="xep_loai_ht">{{ __('Xếp loại HT') }}</th>
                                    <th class="text-nowrap" data-name="diem_rl">{{ __('Điểm RL') }}</th>
                                    <th class="text-nowrap" data-name="xep_loai_rl">{{ __('Xếp loại RL') }}</th>
                                    <th class="text-nowrap" data-name="xep_loai">{{ __('Xếp loại') }}</th>
                                    <th class="text-nowrap" data-name="so_tc_ht">{{ __('Số TC HT') }}</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            </tbody>
                        </table>
                        <!--end::Table-->
                        <div id="datatable-info" class="mt-5 fs-5 text-muted"></div>
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
    @include('score_manager.validate')
    @include('score_manager.table')
    @include('score_manager.calculate', ['target' => 'score_calculate'])
    @include('score_manager.import')
@endsection
