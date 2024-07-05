@extends('layout.main_layout')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        @include('phong_dao_tao.create_ds_che_do_chinh_sach.header')
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
                                       class="form-control form-control-solid w-250px ps-12" placeholder="Tên, mã sinh viên"/>
                            </div>
                            <!--end::Search-->
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
                                <th class="text-nowrap" data-name="id">{{__('Trạng thái')}}</th>
                                <th class="text-nowrap" data-name="student_code">{{__('Sinh viên')}}</th>
                                <th class="text-nowrap" data-name="id">{{__('Đối tượng')}}</th>
                                <th class="text-nowrap">{{__('Ký túc xá')}}</th>
                                <th class="text-nowrap">{!!__('Miễn giảm học phí')!!}</th>
                                <th class="text-nowrap">{!!__('Hỗ trợ tiền ăn')!!}</th>
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

    @include('phong_dao_tao.create_ds_che_do_chinh_sach.table')
    @include('phong_dao_tao.cauhinhmiengiamhp', ['target' => 'cauhinhmiengiamhp'])
    @include('giao_vien.chitiet', ['target' => 'chitiet'])
    @include('giao_vien.tientrinh',['target'=>'tientrinh'])
    @include('phong_dao_tao.tiepnhanhs', ['target' => 'tiepnhanhs'])
    @include('phong_dao_tao.bosunghs', ['target' => 'bosunghs'])
    @include('phong_dao_tao.tuchoihs', ['target' => 'tuchoihs'])
    @include('phong_dao_tao.duyethoso',['target'=>'duyethoso'])
    @include('phong_dao_tao.create_ds_che_do_chinh_sach.import_ktx')
    @include('phong_dao_tao.create_ds_che_do_chinh_sach.doi_tuong')
@endsection
