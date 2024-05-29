<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ getPageHeadTitle(request()->route()->getName()) }}</title>

  <link rel="stylesheet" href="{{asset('vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <link href="{{asset('/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/css/bootstrap-table.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom_page.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jspreadsheet.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jsuites.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jspreadsheet.theme.css') }}">

  @if(request()->get('create_by_iframe'))
  <style>
    .page-body-wrapper
    {
      padding-top: 0 !important;
    }
    #backButton
    {
      display: none !important;
    }
  </style>
  @endif
  @yield('css')
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @if(empty(request()->get('create_by_iframe')))
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-left navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ route('menu') }}">
          <img src="{{asset('assets/images/logo.svg')}}" class="mr-2" alt="logo"/>
          <h3 class="text-primary font-weight-medium d-inline-flex">{{ __('app.name') }}</h3>
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <h4 class="text-primary font-weight-medium d-inline-flex mb-0">{{ getPageTitle(request()->route()->getName()) }}</h4>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item">
                <button class="btn btn-secondary " style="width: 65px; height: 32px; font-size: 9pt; background-color: #4B49AC; color: white;" type="button" onclick="closePage()">終了</button>
            </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img class="bg-primary" src="{{asset('assets/images/faces/user-128.svg')}}" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="{{route('logout')}}">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    @endif
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12">
              @if (session('success'))
                  <div class="alert alert-success">
                    {{ session('success') }}
                  </div>
                @endif
                @if (session('danger'))
                  <div class="alert alert-danger">
                    {{ session('danger') }}
                  </div>
                @endif
                @if (session('info'))
                  <div class="alert alert-info">
                    {{ session('info') }}
                  </div>
                @endif
              @yield('page-content')
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
{{--        <footer class="footer">--}}

{{--        </footer>--}}
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>

{{--  <script src="{{asset('vendors/chart.js/Chart.min.js')}}"></script>--}}

  <script src="{{asset('assets/js/template.js')}}"></script>
  <script src="{{asset('assets/js/settings.js')}}"></script>
{{--  <script src="{{asset('assets/js/todolist.js')}}"></script>--}}
  <!-- endinject -->
  <script src="{{ asset('assets/js/bootstrap-table.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('assets/js/jquery.ui.datepicker-ja.js') }}"></script>
  <!-- End custom js for this page-->
  <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/custom/common.js') }}"></script>
  <script src="{{ asset('assets/custom/custom-table.js') }}"> </script>
{{--  <script src="{{ asset('assets/js/sidebar.js') }}"></script>--}}
  <script src="{{ asset('assets/custom/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/jspreadsheet.js') }}"></script>
  <script src="{{ asset('assets/js/jsuites.js') }}"></script>
  <script src="{{ asset('assets/js/moment.js') }}"></script>
  <script>
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var isUseIframe;
    @if(request()->get('create_by_iframe'))
      isUseIframe = true;
    @endif
  </script>
  @yield('js')
</body>

</html>

