@extends('layout.main_layout')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        @include('phong_dao_tao.ho_so_chung_tu.header')
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!--begin::Products-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Card title-->
                        <div class="card-title w-100">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                <input type="text" data-kt-ecommerce-product-filter="search"
                                       class="form-control w-250px ps-12" placeholder="Tên hồ sơ, mã sinh viên, lớp"/>
                            </div>
                            <!--end::Search-->

                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                <div class="w-100 mw-250px">
                                    <label class="form-label">Loại hồ sơ</label>
                                    
                                    <!--begin::Select2-->
                                    <select class="form-select  filter-select" data-name="group" data-control="select2" data-placeholder="Loại hồ sơ">
                                        <option value="all" selected>Hiển thị tất cả</option>
                                        <option value="1">Rút hồ sơ</option>
                                        <option value="2">Miễn giảm học phí</option>
                                        <option value="3">Trợ cấp xã hội</option>
                                        <option value="4">Trợ cấp học phí</option>
                                        <option value="5">Chế độ chính sách</option>
                                    </select>
                                    <!--end::Select2-->
                                </div>
                                <div class="w-100 mw-150px">
                                    <label class="form-label">Năm</label>
                                    <!--begin::Select2-->
                                    <select class="form-select  filter-select" data-name="year" data-control="select2" data-placeholder="Năm">
                                        <option value="all">Hiển thị tất cả</option>
                                        @php
                                            $currentYear = date('Y');
                                            $currentMonth = date('m');
                                            if ($currentMonth <= 6) {
                                                $selectedYear = $currentYear - 1 . '-' . $currentYear;
                                            } else {
                                                $selectedYear = $currentYear . '-' . ($currentYear + 1);
                                            }
                                        @endphp

                                        @for ($year = 2010; $year <= 2100; $year++)
                                            @php
                                                $optionValue = $year . '-' . ($year + 1);
                                            @endphp
                                            <option value="{{ $optionValue }}"
                                                {{-- {{ $optionValue == $selectedYear ? 'selected' : '' }} --}}
                                                >
                                                {{ $optionValue }}
                                            </option>
                                        @endfor
                                    </select>
                                    <!--end::Select2-->
                                </div>
                            </div>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="frm-table">
                            <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th></th>
                                <th class="text-nowrap" data-name="id">{{__('id')}}</th>
                                <th class="text-nowrap" data-name="id">{{__('Tên hồ sơ')}}</th>
                                <th class="text-nowrap" data-name="id">{{__('Kỳ học')}}</th>
                                <th class="text-nowrap" data-name="id">{{__('Năm học')}}</th>
                                <th class="text-nowrap" data-name="id">{{__('Phân loại')}}</th>
                                <th class="text-nowrap" data-name="id">{{__('File')}}</th>
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
                                                <option value="all">Hiển thị tất cả</option>
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
    @include('phong_dao_tao.ho_so_chung_tu.save_phieu', ['target' => 'ho_so_chung_tu'])
    @include('phong_dao_tao.ho_so_chung_tu.download_ho_so_chung_tu', ['target' => 'download_ho_so_chung_tu'])
    @include('phong_dao_tao.ho_so_chung_tu.table', ['target' => 'ho_so_chung_tu'])
@endsection
