@extends('layouts.master')
@section('css')
@endsection
@section('page-content')
  @php
    $formSearchUserIndex = null;
    if (!empty($request->UserIndex)) {
        $formSearchUserIndex = $request->UserIndex;
    }
    $user = isset($user) ? $user : [];
    $listOption = config()->get('params.options.m_user');
  @endphp
  <x-error-summary/>
  <div class="card form-custom form-master">
    <form method="post"
      action="{{ !empty($user) ? route('master.user.update', ['userCd' => $user->user_cd]) : route('master.user.store') }}">
      <div class="card-body" style="position: relative;">
        <h4 class="card-title">登録/修正/削除画面</h4>
        {{ csrf_field() }}
        @if (!empty($formSearchUserIndex))
          @foreach ($formSearchUserIndex as $key => $value)
            <input type="hidden" name="UserIndex[{{ $key }}]" value="{{ $value }}">
          @endforeach
        @endif
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">ユーザID</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-2L" name="user_cd" maxlength="255"
                    @if($user) readonly  @endif value="{{ data_get($user, 'user_cd', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-user_cd"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">パスワード</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="password" class="form-control size-2L" name="passwd" maxlength="255" autocomplete="new-password"
                         value="{{ !empty($user) ? configParam('USER_PASSWD_EDIT') : '' }}" />
                  <div class="error_message">
                    <span class=" text-danger" id="error-passwd"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">権限グループ</label>
                <div class="col-12 col-md-10 group-input">
                  <select class="form-control size-M" name="group">
                    <option value=""></option>
                    @foreach ($listOption['group'] as $key => $name)
                      <option value="{{ $key }}" @selected(data_get($user, 'group', '') == $key)>{{ $name }}</option>
                    @endforeach
                  </select>
                  <div class="error_message">
                    <span class=" text-danger" id="error-group"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">備考</label>
                <div class="col-12 col-md-10 group-input">
                  <input type="text" class="form-control size-2L" name="biko" maxlength="255"
                    value="{{ data_get($user, 'biko', '') }}">
                  <div class="error_message">
                    <span class=" text-danger" id="error-biko"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="row">
                <label class="col-12 col-md-2 col-form-label text-nowrap ">休眠フラグ</label>
                <div class="col-12 col-md-10 group-input">
                  <select class="form-control size-S" name="kyumin_flg">
                    @foreach ($listOption['kyumin_flg'] as $key => $name)
                      <option value="{{ $key }}" @selected(data_get($user, 'kyumin_flg', '0') == $key)>{{ $name }}</option>
                    @endforeach
                  </select>
                  <div class="error_message">
                    <span class=" text-danger" id="error-kyumin_flg"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="button-form">
          <button class="btn btn-back min-wid-110" id="backButton" data-href="{{ route('master.user.index') }}"
            onclick="redirectBack(this, 'UserIndex')" type="button">{{ trans('app.labels.btn-back') }}</button>
          <button class="btn btn-insert min-wid-110" type="button" onclick="submitForm(this)">
            @if (!empty($user))
              {{ trans('app.labels.btn-update') }}
            @else
              {{ trans('app.labels.btn-insert') }}
            @endif
          </button>
          @if (!empty($user))
            <button class="btn btn-delete min-wid-110" type="button"
              onclick="deleteData(this)">{{ trans('app.labels.btn-delete') }}</button>
          @endif
        </div>
      </div>
    </form>
  </div>
  <div class="popup-confirm"></div>
@endsection

@section('js')
  <script>
    @if (!empty($user))
      function handleDelete() {
        $.ajax({
          url: '{{ route('master.user.destroy', ['userCd' => $user->user_cd]) }}',
          method: 'DELETE',
          data: {},
          success: function(res) {
            if (res.status == 200) {
              $('#backButton').click();
            } else {
              Swal.fire({
                title: res.message,
                icon: "error"
              });
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error('AJAX Error:', textStatus, errorThrown);
          },
          complete: function() {

          }
        })
      }
    @endif

    $(document).ready(function() {
      $('form').find('select, input').change(function() {
        hasChangeData = true;
      });
    });
  </script>
@endsection
