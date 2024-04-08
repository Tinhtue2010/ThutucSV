<div class="app-sidebar-menu-secondary menu menu-rounded menu-column mb-6">
    @if (Role(0) || Role(4) || Role(5))
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link" href="{{ route('studentManager.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-lock-2 fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Quản lý sinh viên</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
    @endif
    @if (!Role(1))
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link" href="{{ route('approve.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-lock-2 fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Duyệt hồ sơ</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
    @endif
    @if (Role(1))
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link" href="{{ route('student.info') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-lock-2 fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Xin rút hồ sơ</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
    @endif

</div>
