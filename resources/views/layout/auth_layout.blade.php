<!DOCTYPE html>
	<html lang="vi" style="font-size: 13px!important">

	<head>
		<title>Đăng nhập</title>
		<meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->

	<body id="kt_body" class="bg-success" >
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if (document.documentElement) { if (document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if (localStorage.getItem("data-bs-theme") !== null) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Aside-->
				<div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
					<div class="container d-flex flex-row">
						<h1 class="d-none d-md-flex text-white fw-medium text-center pe-5 border-end border-3">UBND TỈNH QUẢNG NINH <br>
							TRƯỜNG ĐẠI HỌC HẠ LONG
						</h1>
						<div class="d-flex flex-grow-1">
							<h1 class="text-white ps-5 m-auto text-center fs-2">HỆ THỐNG GIẢI QUYẾT THỦ TỤC VỀ LĨNH VỰC CÔNG TÁC
								HỌC SINH, SINH VIÊN</h1>

						</div>
					</div>
					<!--begin::Wrapper-->
					<div
						class="d-flex my-10 rounded-4 justify-content-between flex-column-fluid flex-column w-100 mw-800px bg-light">
						<!--begin::Body-->
						@yield('content')
						<!--end::Body-->
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Aside-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--end::Main-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="{{asset('assets/plugins/global/plugins.bundle.js')}}"></script>
		<script src="{{asset('assets/js/scripts.bundle.js')}}"></script>

		<!--end::Javascript-->
		@stack('js')
	</body>
	<!--end::Body-->

	</html>