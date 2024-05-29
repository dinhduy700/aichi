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
  @yield('css')
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body class="popup">
<style>
  .popup .page-body-wrapper {
    min-height: 100vh;
    padding-top: 0px;
  }
</style>
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper">
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-12 grid-margin">
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
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@yield('js')
</body>

</html>
