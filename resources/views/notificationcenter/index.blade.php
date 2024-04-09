@extends('layout.main_layout')

@section('content')
        <!--begin::Inbox App - Messages -->
        <div class="d-flex flex-column flex-column-fluid">

            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                <!--begin::Card-->
                <div class="card">
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <!--begin::Actions-->
                        <div class="d-flex flex-wrap gap-2">
                            <!--begin::Checkbox-->
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-4 me-lg-7">
                                <input class="form-check-input" type="checkbox" data-kt-check="true"
                                    data-kt-check-target="#kt_inbox_listing .form-check-input" value="1" />
                            </div>
                            <!--end::Checkbox-->
                            <!--begin::Reload-->
                            <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                                data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top" title="Reload">
                                <i class="ki-outline ki-arrows-circle fs-2"></i>
                            </a>
                            <!--end::Reload-->
                            <!--begin::Archive-->
                            <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                                data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top" title="Archive">
                                <i class="ki-outline ki-sms fs-2"></i>
                            </a>
                            <!--end::Archive-->
                            <!--begin::Delete-->
                            <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                                data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top" title="Delete">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </a>
                            <!--end::Delete-->
                            <!--begin::Filter-->
                            <div>
                                <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <i class="ki-outline ki-down fs-2"></i>
                                </a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                    data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-inbox-listing-filter="show_all">All</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-inbox-listing-filter="show_read">Read</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-inbox-listing-filter="show_unread">Unread</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-inbox-listing-filter="show_starred">Starred</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-inbox-listing-filter="show_unstarred">Unstarred</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Filter-->
                            <!--begin::Sort-->
                            <span>
                                <a href="#" class="btn btn-sm btn-icon btn-light btn-active-light-primary"
                                    data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top"
                                    title="Sort">
                                    <i class="ki-outline ki-dots-square fs-3 m-0"></i>
                                </a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                    data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-inbox-listing-filter="filter_newest">Newest</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-inbox-listing-filter="filter_oldest">Oldest</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3"
                                            data-kt-inbox-listing-filter="filter_unread">Unread</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </span>
                            <!--end::Sort-->
                        </div>
                        <!--end::Actions-->
                        <!--begin::Actions-->
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                <input type="text" data-kt-inbox-listing-filter="search"
                                    class="form-control form-control-sm form-control-solid mw-100 min-w-125px min-w-lg-150px min-w-xxl-200px ps-11"
                                    placeholder="Search inbox" />
                            </div>
                            <!--end::Search-->
                            <!--begin::Toggle-->
                            <a href="#"
                                class="btn btn-sm btn-icon btn-color-primary btn-light btn-active-light-primary d-lg-none"
                                data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top"
                                title="Toggle inbox menu" id="kt_inbox_aside_toggle">
                                <i class="ki-outline ki-burger-menu-2 fs-3 m-0"></i>
                            </a>
                            <!--end::Toggle-->
                        </div>
                        <!--end::Actions-->
                    </div>
                    <div class="card-body p-0">
                        <!--begin::Table-->
                        <table class="table table-hover table-row-dashed fs-6 gy-5 my-0" id="kt_inbox_listing">
                            <thead class="d-none">
                                <tr>
                                    <th>Checkbox</th>
                                    <th>Actions</th>
                                    <th>Author</th>
                                    <th>Title</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-9">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid mt-3">
                                            <input class="form-check-input" type="checkbox" value="1" />
                                        </div>
                                    </td>
                                    <td class="min-w-80px">
                                        <!--begin::Star-->
                                        <a href="#"
                                            class="btn btn-icon btn-color-gray-400 btn-active-color-primary w-35px h-35px"
                                            data-bs-toggle="tooltip" data-bs-placement="right" title="Star">
                                            <i class="ki-outline ki-star fs-3"></i>
                                        </a>
                                        <!--end::Star-->
                                        <!--begin::Important-->
                                        <a href="#"
                                            class="btn btn-icon btn-color-gray-400 btn-active-color-primary w-35px h-35px"
                                            data-bs-toggle="tooltip" data-bs-placement="right" title="Mark as important">
                                            <i class="ki-outline ki-save-2 fs-4 mt-1"></i>
                                        </a>
                                        <!--end::Important-->
                                    </td>
                                    <td class="w-150px w-md-175px">
                                        <a href="../../demo38/dist/apps/inbox/reply.html"
                                            class="d-flex align-items-center text-dark">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px me-3">
                                                <div class="symbol symbol-35px symbol-circle">
                                                    <img alt="Pic" src="assets/media/avatars/md.jpg" />
                                                </div>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::Name-->
                                            <span class="fw-semibold">Melody Mark</span>
                                            <!--end::Name-->
                                        </a>
                                    </td>
                                    <td>
                                        <div class="text-dark gap-1 pt-2">
                                            <!--begin::Heading-->
                                            <a href="../../demo38/dist/apps/inbox/reply.html" class="text-dark">
                                                <span class="fw-bold">Check out my new video at PH</span>
                                                <span class="fw-bold d-none d-md-inine">-</span>
                                                <span class="d-none d-md-inine text-muted">Love you guys &lt;3&lt;3&lt;3....</span>
                                            </a>
                                            <!--end::Heading-->
                                        </div>
                                        <!--begin::Badges-->
                                        <div class="badge badge-light-primary">inbox</div>
                                        <div class="badge badge-light-warning">task</div>
                                        <!--end::Badges-->
                                    </td>
                                    <td class="w-100px text-end fs-7 pe-9">
                                        <span class="fw-semibold">now</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Inbox App - Messages -->
@endsection
