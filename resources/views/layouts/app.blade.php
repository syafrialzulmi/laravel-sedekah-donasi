<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ $settingApp->name_app ?? '-- Aplikasi --' }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    @php
        $favicon = $settingApp?->favicon
            ? asset('storage/' . $settingApp->favicon)
            : asset('assets/img/favicon/favicon.ico');
    @endphp

    <link rel="icon" href="{{ $favicon }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href={{ asset('assets/vendor/fonts/boxicons.css') }} />

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href={{ asset('assets/vendor/css/core.css') }} class="template-customizer-core-css" />
    <link rel="stylesheet" href={{ asset('assets/vendor/css/theme-default.css') }} class="template-customizer-theme-css" />
    <link rel="stylesheet" href={{ asset('assets/css/demo.css') }} />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href={{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }} />

    <link rel="stylesheet" href={{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }} />

    <!-- Sertakan CSS untuk Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Sertakan Leaflet-Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src={{ asset('assets/vendor/js/helpers.js') }}></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src={{ asset('assets/js/config.js') }}></script>

     {{-- Select2 CSS (boleh pindah ke layout kalau dipakai global) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- (Opsional) Tema Bootstrap-5 untuk Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.6.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

    <style>
    .bd-callout {
        padding: 1.25rem;
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
        border-left-width: .25rem;
        border-radius: .25rem;
        background-color: #f8f9fa;
    }

    .bd-callout-success {
        border-left-color: #198754;
    }

    .bd-callout-danger {
        border-left-color: #dc3545;
    }

    .bd-callout-secondary {
        border-left-color: #6c757d;
    }
</style>

    @stack('styles')
  </head>

  <body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        @include('components.sidebar')

        <!-- Layout container -->
        <div class="layout-page">

            @include('components.header')

          <!-- Content wrapper -->
          <div class="content-wrapper">

            @yield('main')

            @include('components.footer')

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

@if(session('success'))
    <div class="position-fixed bottom-0 start-50 translate-middle-x  p-3" style="z-index: 1055; bottom: 2rem;">
        <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="position-fixed bottom-0 start-50 translate-middle-x  p-3" style="z-index: 1055; bottom: 2rem;">
        <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
@endif

    {{-- <div class="buy-now">
      <a
        href="https://themeselection.com/products/sneat-bootstrap-html-admin-template/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
        >Upgrade to Pro</a
      >
    </div> --}}

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    {{-- jQuery (dibutuhkan oleh Select2) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>

    {{-- <script src={{ asset('assets/vendor/libs/jquery/jquery.js') }}></script> --}}
    <script src={{ asset('assets/vendor/libs/popper/popper.js') }}></script>
    <script src={{ asset('assets/vendor/js/bootstrap.js') }}></script>
    <script src={{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}></script>

    <script src={{ asset('assets/vendor/js/menu.js') }}></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src={{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}></script>

    <!-- Main JS -->
    <script src={{ asset('assets/js/main.js') }}></script>

    <!-- Page JS -->
    <script src={{ asset('assets/js/dashboards-analytics.js') }}></script>

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- jQuery & DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Sertakan JS untuk Leaflet -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Sertakan Leaflet-Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

    <script>
        setTimeout(() => {
            const toastEl = document.querySelector('.toast');
            if (toastEl) {
                const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
                toast.hide();
            }
        }, 3000); // auto-hide after 3 seconds
    </script>

    @stack('scripts')
  </body>
</html>
