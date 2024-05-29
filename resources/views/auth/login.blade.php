<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ getPageHeadTitle('login') }}</title>

  <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
  <link href="{{ asset('/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

  <link href="{{ asset('assets/css/bootstrap-table.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom_page.css') }}">
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
{{--                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">--}}
                <h3 class="text-primary fs-30 font-weight-medium">{{ __('app.name') }}</h3>
              </div>
              <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                  <input type="text" value="{{ old('user_cd') }}" name="user_cd"
                    class="form-control form-control-lg" id="user_cd">
                  @if ($errors->has('user_cd'))
                    <span class="text-danger text-errors" role="alert">
                      <strong>{{ $errors->first('user_cd') }}</strong>
                    </span>
                  @endif
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" name="password">
                  @if ($errors->has('password'))
                    <span class="text-danger text-errors" role="alert">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                    {{ __('app.labels.login') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
