{{--@extends('adminlte::page')--}}
{{--@extends('adminlte::auth.register')--}}
@extends('adminlte::auth.login')
@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
{{--    code my login--}}
    {{--<div class="container-fluid row m-0 p-0 ">
        <div class="div-header col-12 m-0 p-0">
            <h1 class="text-uppercase title-header text-white pl-3">hệ thông quản lý sinh viên</h1>
        </div>
        <div class="div-main col-12 m-0 p-0 row">
            <div class="box-login mx-auto my-auto shadow-lg border-success">
                <form action="{{route('checkLogin')}}" class="needs-validation" method="POST" novalidate>
                    @csrf
                    <div class="header-form">
                        <p class="title-form text-capitalize">Thông tin đăng nhập</p>
                    </div>

                    <div class="main-form">
                        <!-- input email -->
                        <div class="form-group">
                            <label for="txtEmail">Email:</label>
                            <input type="email" class="form-control" id="txtEmail" placeholder="Nhập Email" name="txtEmail"
                                   required minlength="8" maxlength="255">
                            <div class="invalid-feedback">Hãy nhập email chính xác, từ 8 ký tự trở lên</div>
                        </div>

                        <!-- input password -->
                        <div class="form-group">
                            <label for="txtPassword">Mật khẩu:</label>
                            <input type="password" class="form-control" id="txtPassword" placeholder="Nhập mật khẩu"
                                   name="txtPassword" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" required
                                   minlength="8" maxlength="16">
                            <div class="invalid-feedback">Gồm chữ hoa, chữ thường, chữ số, từ 8 đến 16 ký tự</div>
                        </div>

                        <!-- checkbox remenber login -->
                        <div class="form-group form-check">
                            <label class="form-check-label" for="cbRemenber">
                                <input class="form-check-input" id="cbRemenber" type="checkbox" name="cbRemenber"> Nhớ thông
                                tin đăng nhập.
                            </label>
                        </div>
                        <a href="#" class="d-block mb-2 text-capitalize text-decoration-none">forgot password?</a>

                        <!-- button submit -->
                        <button type="submit" class="btn p-2 mb-2 btn-primary w-75 mx-auto d-block">Đăng nhập</button>

                    </div>

                    <div class="footer-form py-2">
                        <p class="text-center">Chưa có tài khoản? <a href="#">Đăng ký ngay</a></p>
                    </div>
                </form>
            </div>
        </div>

        <div class="div-footer col-12 m-0 p-0">
        </div>
    </div>--}}
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/login.css')}}">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop



