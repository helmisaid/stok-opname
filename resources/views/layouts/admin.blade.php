<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="dark" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- Favicon icon-->
<link
  rel="shortcut icon"
  type="image/png"
  href="{{ asset('assets/images/logos/favicon.png') }}"
/>

<!-- Core Css -->
<link rel="stylesheet" href="{{asset('assets/css/styles.css') }}" />
@yield('csspage')
  <title>Ecommerce</title>
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="{{ asset('assets/images/logos/loader.svg') }}" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="left-sidebar with-vertical">
      <!-- ---------------------------------- -->
<!-- Start Vertical Layout Sidebar -->
<!-- ---------------------------------- -->
<div class="brand-logo d-flex align-items-center justify-content-between">
  <a href="{{ asset('dark/index') }}') }}" class="text-nowrap logo-img">
    <img src="{{ asset('assets/images/logos/logo-light.svg') }}" class="dark-logo" alt="Logo-Dark" />
    <img src="{{ asset('assets/images/logos/logo-dark.svg') }}" class="light-logo" alt="Logo-light" />
  </a>
  <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
    <i class="ti ti-x"></i>
  </a>
</div>

<div class="scroll-sidebar" data-simplebar>
  <!-- Sidebar navigation-->
  @include('layouts.sidebar')
  <!-- End Sidebar navigation -->
</div>

<div class=" fixed-profile mx-3 mt-3">
  <div class="card bg-primary-subtle mb-0 shadow-none">
    <div class="card-body p-4">
      <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
          <img src="{{ asset('assets/images/profile/user-1.jpg') }}" width="45" height="45" class="img-fluid rounded-circle" alt="" />
          <div>
            <h5 class="mb-1">{{ $user->username ?? 'Guest' }}</h5>
            <p class="mb-0">{{ ucfirst($user->role ?? 'User') }}</p>

          </div>
        </div>
        <a href="{{ route('logout') }}" class="position-relative" data-bs-toggle="tooltip" data-bs-placement="top"
          data-bs-title="Logout">
          <iconify-icon icon="solar:logout-line-duotone" class="fs-8"></iconify-icon>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- ---------------------------------- -->
<!-- Start Vertical Layout Sidebar -->
<!-- ---------------------------------- -->
    </aside>
    <!--  Sidebar End -->
    <div class="page-wrapper">


      <aside class="left-sidebar with-horizontal">
        <!-- Sidebar scroll-->
{{-- < --}}
      </aside>

      <div class="body-wrapper">
        <div class="container-fluid">
          <!--  Header Start -->
          @include('layouts.header')
          <!--  Header End -->
          <!-- Welcome Section Start -->
          @yield('content')
          <!-- Educators End -->
        </div>
      </div>
      <script>
  function handleColorTheme(e) {
    $("html").attr("data-color-theme", e);
    $(e).prop("checked", !0);
  }
</script>
<button class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn"
  type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
  <i class="icon ti ti-settings fs-7"></i>
</button>

<div class="offcanvas customizer offcanvas-end" tabindex="-1" id="offcanvasExample"
  aria-labelledby="offcanvasExampleLabel">
  <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
    <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">
      Settings
    </h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body h-n80" data-simplebar>
    <h6 class="fw-semibold fs-4 mb-2">Theme</h6>

    <div class="d-flex flex-row gap-3 customizer-box" role="group">
      <input type="radio" class="btn-check light-layout" name="theme-layout" id="light-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary" for="light-layout"><i
          class="icon ti ti-brightness-up fs-7 me-2"></i>Light</label>

      <input type="radio" class="btn-check dark-layout" name="theme-layout" id="dark-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary" for="dark-layout"><i class="icon ti ti-moon fs-7 me-2"></i>Dark</label>
    </div>

    <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Direction</h6>
    <div class="d-flex flex-row gap-3 customizer-box" role="group">
      <input type="radio" class="btn-check" name="direction-l" id="ltr-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary" for="ltr-layout"><i
          class="icon ti ti-text-direction-ltr fs-7 me-2"></i>LTR</label>

      <input type="radio" class="btn-check" name="direction-l" id="rtl-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary" for="rtl-layout"><i
          class="icon ti ti-text-direction-rtl fs-7 me-2"></i>RTL</label>
    </div>

    <h6 class="mt-5 fw-semibold fs-4 mb-2">Theme Colors</h6>

    <div class="d-flex flex-row flex-wrap gap-3 customizer-box color-pallete" role="group">
      <input type="radio" class="btn-check" name="color-theme-layout" id="Blue_Theme"
        autocomplete="off" />
      <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center" onclick="handleColorTheme('Blue_Theme')"
        for="Blue_Theme" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="BLUE_THEME">
        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-1">
          <i class="ti ti-check text-white d-flex icon fs-5"></i>
        </div>
      </label>

      <input type="radio" class="btn-check" name="color-theme-layout"  id="Aqua_Theme"
        autocomplete="off" />
      <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center" onclick="handleColorTheme('Aqua_Theme')"
        for="Aqua_Theme" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="AQUA_THEME">
        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-2">
          <i class="ti ti-check text-white d-flex icon fs-5"></i>
        </div>
      </label>

      <input type="radio" class="btn-check" name="color-theme-layout" id="Purple_Theme" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center" onclick="handleColorTheme('Purple_Theme')"
        for="Purple_Theme" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="PURPLE_THEME">
        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-3">
          <i class="ti ti-check text-white d-flex icon fs-5"></i>
        </div>
      </label>

      <input type="radio" class="btn-check" name="color-theme-layout" id="green-theme-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center" onclick="handleColorTheme('Green_Theme')"
        for="green-theme-layout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="GREEN_THEME">
        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-4">
          <i class="ti ti-check text-white d-flex icon fs-5"></i>
        </div>
      </label>

      <input type="radio" class="btn-check" name="color-theme-layout" id="cyan-theme-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center" onclick="handleColorTheme('Cyan_Theme')"
        for="cyan-theme-layout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="CYAN_THEME">
        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-5">
          <i class="ti ti-check text-white d-flex icon fs-5"></i>
        </div>
      </label>

      <input type="radio" class="btn-check" name="color-theme-layout" id="orange-theme-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary d-flex align-items-center justify-content-center" onclick="handleColorTheme('Orange_Theme')"
        for="orange-theme-layout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="ORANGE_THEME">
        <div class="color-box rounded-circle d-flex align-items-center justify-content-center skin-6">
          <i class="ti ti-check text-white d-flex icon fs-5"></i>
        </div>
      </label>
    </div>

    <h6 class="mt-5 fw-semibold fs-4 mb-2">Layout Type</h6>
    <div class="d-flex flex-row gap-3 customizer-box" role="group">
      <div>
        <input type="radio" class="btn-check" name="page-layout" id="vertical-layout" autocomplete="off" />
        <label class="btn p-9 btn-outline-primary" for="vertical-layout"><i
            class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Vertical</label>
      </div>
      <div>
        <input type="radio" class="btn-check" name="page-layout" id="horizontal-layout" autocomplete="off" />
        <label class="btn p-9 btn-outline-primary" for="horizontal-layout"><i
            class="icon ti ti-layout-navbar fs-7 me-2"></i>Horizontal</label>
      </div>
    </div>

    <h6 class="mt-5 fw-semibold fs-4 mb-2">Container Option</h6>

    <div class="d-flex flex-row gap-3 customizer-box" role="group">
      <input type="radio" class="btn-check" name="layout" id="boxed-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary" for="boxed-layout"><i
          class="icon ti ti-layout-distribute-vertical fs-7 me-2"></i>Boxed</label>

      <input type="radio" class="btn-check" name="layout" id="full-layout" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary" for="full-layout"><i
          class="icon ti ti-layout-distribute-horizontal fs-7 me-2"></i>Full</label>
    </div>

    <h6 class="fw-semibold fs-4 mb-2 mt-5">Sidebar Type</h6>
    <div class="d-flex flex-row gap-3 customizer-box" role="group">
      <a href="javascript:void(0)" class="fullsidebar">
        <input type="radio" class="btn-check" name="sidebar-type" id="full-sidebar" autocomplete="off" />
        <label class="btn p-9 btn-outline-primary" for="full-sidebar"><i
            class="icon ti ti-layout-sidebar-right fs-7 me-2"></i>Full</label>
      </a>
      <div>
        <input type="radio" class="btn-check " name="sidebar-type" id="mini-sidebar" autocomplete="off" />
        <label class="btn p-9 btn-outline-primary" for="mini-sidebar"><i
            class="icon ti ti-layout-sidebar fs-7 me-2"></i>Collapse</label>
      </div>
    </div>

    <h6 class="mt-5 fw-semibold fs-4 mb-2">Card With</h6>

    <div class="d-flex flex-row gap-3 customizer-box" role="group">
      <input type="radio" class="btn-check" name="card-layout" id="card-with-border" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary" for="card-with-border"><i
          class="icon ti ti-border-outer fs-7 me-2"></i>Border</label>

      <input type="radio" class="btn-check" name="card-layout" id="card-without-border" autocomplete="off" />
      <label class="btn p-9 btn-outline-primary" for="card-without-border"><i
          class="icon ti ti-border-none fs-7 me-2"></i>Shadow</label>
    </div>
  </div>
</div>
    </div>
    <div class="dark-transparent sidebartoggler"></div>



  </div>

  <!-- Import Js Files -->
@yield('jspage')
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/theme/app.dark.init.js') }}"></script>
<script src="{{ asset('assets/js/theme/theme.js') }}"></script>
<script src="{{ asset('assets/js/theme/app.min.js') }}"></script>
<script src="{{ asset('assets/js/theme/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/js/theme/feather.min.js') }}"></script>

<!-- solar icons -->
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/js/dashboards/dashboard2.js') }}"></script>
</body>

</html>
