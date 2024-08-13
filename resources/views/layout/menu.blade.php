<div class="app-sidebar-menu-secondary menu menu-rounded menu-column mb-6">
    @if (Role(0))
        <div class="menu-item mb-2">
            <div class="menu-heading text-uppercase fs-7 fw-bold"> Quản lý</div>

            <div class="app-sidebar-separator separator"></div>
        </div>

        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('studentManager.index') ? 'active' : '' }}" href="{{ route('studentManager.index') }}">
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

        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('khoaManager.index') ? 'active' : '' }}" href="{{ route('khoaManager.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-category fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Quản lý khoa, phòng ban</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>

        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('classManager.index') ? 'active' : '' }}" href="{{ route('classManager.index') }}">
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
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('teacherManager.index') ? 'active' : '' }}" href="{{ route('teacherManager.index') }}">
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
    @if (Role(4) || Role(6))
        <div class="menu-item mb-2">
            <div class="menu-heading text-uppercase fs-7 fw-bold"> Quản lý</div>

            <div class="app-sidebar-separator separator"></div>
        </div>

        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('khoaManager.index') ? 'active' : '' }}" href="{{ route('khoaManager.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-category fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Quản lý khoa, phòng ban</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>

        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('classManager.index') ? 'active' : '' }}" href="{{ route('classManager.index') }}">
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
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('teacherManager.index') ? 'active' : '' }}" href="{{ route('teacherManager.index') }}">
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
    @if (Role(2) || Role(3))
        <div class="menu-item mb-2 mt-4">
            <div class="menu-heading text-uppercase fs-7 fw-bold">Hồ sơ sinh viên (GVCN)</div>

            <div class="app-sidebar-separator separator"></div>
        </div>
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('GiaoVien.index') ? 'active' : '' }}" href="{{ route('GiaoVien.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-document fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Tiếp nhận & Xử lý đơn</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link  {{ request('gvcn') === 'true' && request()->is('student-manager') ? 'active' : '' }}" href="{{ route('studentManager.index', ['gvcn' => 'true']) }}">
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

    @if (Role(3))
        <div class="menu-item mb-2 mt-4">
            <div class="menu-heading text-uppercase fs-7 fw-bold">Hồ sơ sinh viên (Khoa)</div>

            <div class="app-sidebar-separator separator"></div>
        </div>
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('Khoa.index') ? 'active' : '' }}" href="{{ route('Khoa.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-document fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Tiếp nhận & Xử lý đơn</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request('khoa') === 'true' && request()->is('student-manager') ? 'active' : '' }}" href="{{ route('studentManager.index', ['khoa' => 'true']) }}">
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

    @if (Role(4))
        <div class="menu-item mb-2 mt-4">
            <div class="menu-heading text-uppercase fs-7 fw-bold">Hồ sơ sinh viên</div>

            <div class="app-sidebar-separator separator"></div>
        </div>
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('PhongDaoTao.index') ? 'active' : '' }}" href="{{ route('PhongDaoTao.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-document fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Tiếp nhận & Xử lý đơn</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->is('student-manager') ? 'active' : '' }}" href="{{ route('studentManager.index') }}">
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
    @if (Role(4))
        <div class="menu-item mb-2 mt-4">
            <div class="menu-heading text-uppercase fs-7 fw-bold">Hồ sơ chứng từ</div>

            <div class="app-sidebar-separator separator"></div>
        </div>
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('PhongDaoTao.HoSoChungTu.index') ? 'active' : '' }}" href="{{ route('PhongDaoTao.HoSoChungTu.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-document fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Quản lý hồ sơ chứng từ</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
    @endif
    @if (Role(5))
        <div class="menu-item mb-2 mt-4">
            <div class="menu-heading text-uppercase fs-7 fw-bold">Hồ sơ sinh viên</div>

            <div class="app-sidebar-separator separator"></div>
        </div>
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('KeHoachTaiChinh.index') ? 'active' : '' }}" href="{{ route('KeHoachTaiChinh.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-document fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Tiếp nhận & Xử lý đơn</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->is('student-manager') ? 'active' : '' }}" href="{{ route('studentManager.index') }}">
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
    @if (Role(6))
        <div class="menu-item mb-2 mt-4">
            <div class="menu-heading text-uppercase fs-7 fw-bold">Hồ sơ sinh viên</div>

            <div class="app-sidebar-separator separator"></div>
        </div>
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('LanhDaoPhongDaoTao.index') ? 'active' : '' }}" href="{{ route('LanhDaoPhongDaoTao.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-document fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Tiếp nhận & Xử lý đơn</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->is('student-manager') ? 'active' : '' }}" href="{{ route('studentManager.index') }}">
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
    @if (Role(7))
        <div class="menu-item mb-2 mt-4">
            <div class="menu-heading text-uppercase fs-7 fw-bold">Hồ sơ sinh viên</div>

            <div class="app-sidebar-separator separator"></div>
        </div>
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('LanhDaoTruong.index') ? 'active' : '' }}" href="{{ route('LanhDaoTruong.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-document fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Tiếp nhận & Xử lý đơn</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->is('student-manager') ? 'active' : '' }}" href="{{ route('studentManager.index') }}">
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
    {{-- @if (!Role(1))
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('approve.index') ? 'active' : '' }}" href="{{ route('approve.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-document fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Hồ sơ khoa</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
    @endif --}}

    @if (Role(1))
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('student.info') ? 'active' : '' }}" href="{{ route('student.info') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-more-2 fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Thông tin cá nhân</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
        <div class="menu-item mb-2 mt-4">
            <div class="menu-heading text-uppercase fs-7 fw-bold"> Biểu mẫu</div>

            <div class="app-sidebar-separator separator"></div>
        </div>
        <!--begin::Menu Item-->
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('StopStudy.index') ? 'active' : '' }}" href="{{ route('StopStudy.index') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-more-2 fs-2"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Xin rút hồ sơ</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
        <!--end::Menu Item-->
        @if (Role('studentActive'))
            <!--begin::Menu Item-->
            <div class="menu-item">
                <!--begin::Menu link-->
                <a class="menu-link {{ request()->routeIs('MienGiamHp.index') ? 'active' : '' }}" href="{{ route('MienGiamHp.index') }}">
                    <!--begin::Icon-->
                    <span class="menu-icon">
                        <i class="ki-outline ki-more-2 fs-2"></i>
                    </span>
                    <!--end::Icon-->
                    <!--begin::Title-->
                    <span class="menu-title">Miễn giảm học phí</span>
                    <!--end::Title-->
                </a>
                <!--end::Menu link-->
            </div>
            <!--end::Menu Item-->
            <!--begin::Menu Item-->
            <div class="menu-item">
                <!--begin::Menu link-->
                <a class="menu-link {{ request()->routeIs('TroCapXH.index') ? 'active' : '' }}" href="{{ route('TroCapXH.index') }}">
                    <!--begin::Icon-->
                    <span class="menu-icon">
                        <i class="ki-outline ki-more-2 fs-2"></i>
                    </span>
                    <!--end::Icon-->
                    <!--begin::Title-->
                    <span class="menu-title">Trợ cấp xã hội</span>
                    <!--end::Title-->
                </a>
                <!--end::Menu link-->
            </div>
            <!--end::Menu Item-->
            @if (Auth::user()->checkNganhCheDoChinhSach())
                <!--begin::Menu Item-->
                <div class="menu-item">
                    <!--begin::Menu link-->
                    <a class="menu-link {{ request()->routeIs('CheDoChinhSach.index') ? 'active' : '' }}" href="{{ route('CheDoChinhSach.index') }}">
                        <!--begin::Icon-->
                        <span class="menu-icon">
                            <i class="ki-outline ki-more-2 fs-2"></i>
                        </span>
                        <!--end::Icon-->
                        <!--begin::Title-->
                        <span class="menu-title">Chế độ chính sách</span>
                        <!--end::Title-->
                    </a>
                    <!--end::Menu link-->
                </div>
            @endif

            <!--end::Menu Item-->
        @endif

    @endif

    <div class="menu-item mb-2 mt-4">
        <div class="menu-heading text-uppercase fs-7 fw-bold"> Khác</div>
        <div class="app-sidebar-separator separator"></div>
    </div>

    @if (!Role(1))
        <div class="menu-item">
            <!--begin::Menu link-->
            <a class="menu-link {{ request()->routeIs('Profile.GiaoVien.info') ? 'active' : '' }}" href="{{ route('Profile.GiaoVien.info') }}">
                <!--begin::Icon-->
                <span class="menu-icon">
                    <i class="ki-outline ki-gear fs-2x"></i>
                </span>
                <!--end::Icon-->
                <!--begin::Title-->
                <span class="menu-title">Cài đặt</span>
                <!--end::Title-->
            </a>
            <!--end::Menu link-->
        </div>
    @endif

    <div class="menu-item">
        <!--begin::Menu link-->
        <a class="menu-link {{ request()->routeIs('notification.index') ? 'active' : '' }}" href="{{ route('notification.index') }}">
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
