@extends('layouts.app')

@section('content')
    <login csrf_token="{{ csrf_token() }}" route_login="{{ route('login-api') }}"></login>
@endsection
