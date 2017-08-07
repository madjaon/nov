@extends('auth.layouts.master')

@section('title', 'Đăng Nhập Tài Khoản')

@section('body')
    <div class="row">
        <div class="col-sm-6">
            <div class="box-style mb-3">
                <div class="d-inline-flex py-2 title">@yield('title')</div>
            </div>
            @include('auth.common.errors')
            <form action="{{ url('user/login') }}" method="post" id="reform">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <div class="form-group">
                    <input name="username" type="text" value="{{ old('username') }}" class="form-control" placeholder="Tên Đăng Nhập" maxlength="255" required>
                </div>
                <div class="form-group">
                    <input name="password" type="password" class="form-control" placeholder="Mật Khẩu" maxlength="255" required>
                </div>
                <div class="form-group d-flex align-items-center">
                    <!-- <button type="submit" class="btn btn-primary" id="submit"><i class="fa fa-btn fa-sign-in mr-1"></i>Đăng Nhập</button> -->
                    <button class="btn btn-primary g-recaptcha" data-sitekey="{{ RECAPTCHASITEKEY }}" data-callback="onSubmit"><i class="fa fa-btn fa-sign-in mr-1"></i>Đăng Nhập</button>
                    <a href="{{ url('user/password/reset') }}" class="ml-3">Quên mật khẩu?</a>
                    <a href="{{ url('user/register') }}" class="ml-3">Đăng Ký Tài Khoản</a>
                </div>
            </form>
        </div>
        <div class="col-sm-6"></div>
    </div>
@endsection
