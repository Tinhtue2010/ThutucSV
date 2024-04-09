<div class="app-sidebar-menu-secondary menu menu-rounded menu-column mb-6">
    <div class="menu-item mb-2">
        <div class="menu-heading text-uppercase fs-7 fw-bold"> Quản lý</div>

        <div class="app-sidebar-separator separator"></div>
    </div>

    @if (Role(0) || Role(4) || Role(5))
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('studentManager.index') ? 'active' : '' }}"
                href="{{ route('studentManager.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-category fs-2"></i>
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

    @if (Role(0) || Role(3) || Role(6))
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('khoaManager.index') ? 'active' : '' }}"
                href="{{ route('khoaManager.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-category fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Quản lý khoa</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
    @endif

    @if (Role(0) || Role(2) || Role(4))
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('classManager.index') ? 'active' : '' }}"
                href="{{ route('classManager.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-category fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Quản lý lớp</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
    @endif
    @if (Role(0) || Role(2) || Role(4))
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('teacherManager.index') ? 'active' : '' }}"
                href="{{ route('teacherManager.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-category fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Quản lý Cán bộ/Giảng viên</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
    @endif

    <div class="menu-item mb-2 mt-4">
        <div class="menu-heading text-uppercase fs-7 fw-bold"> Tiếp nhận & Xử lý</div>

        <div class="app-sidebar-separator separator"></div>
    </div>

    @if (!Role(1))
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('approve.index') ? 'active' : '' }}"
                href="{{ route('approve.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-document fs-2"></i>
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

    <div class="menu-item mb-2 mt-4">
        <div class="menu-heading text-uppercase fs-7 fw-bold"> Biểu mẫu</div>

        <div class="app-sidebar-separator separator"></div>
    </div>

    @if (Role(0) || Role(1))
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('student.info') ? 'active' : '' }}"
                href="{{ route('student.info') }}">
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

    <div class="menu-item mb-2 mt-4">
        <div class="menu-heading text-uppercase fs-7 fw-bold"> Khác</div>

        <div class="app-sidebar-separator separator"></div>
    </div>

    <div class="menu-item">
        <!--begin::Menu link-->
        <a class="menu-link "
            href="">
            <!--begin::Icon-->
            <span class="menu-icon">
                <i class="bi bi-inbox fs-1"></i>
            </span>
            <!--end::Icon-->
            <!--begin::Title-->
            <span class="menu-title">Hòm thư</span>
            <!--end::Title-->
        </a>
        <!--end::Menu link-->
    </div>
</div>
