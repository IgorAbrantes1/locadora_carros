@extends('layouts.app')

@section('content')
    <login csrf_token="{{ @csrf_token() }}" url="{{ route('login-api') }}" login_name="{{ __('Login') }}" email_name="{{ __('E-Mail Address') }}" password_name="{{ __('Password') }}" remember_name="{{ __('Remember Me') }}" forgot_name="{{ __('Forgot Your Password?') }}"></login>
@endsection
