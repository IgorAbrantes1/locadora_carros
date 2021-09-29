@extends('layouts.app')

@section('content')
    <home page="{{ trans('Dashboard') }}">
        <div class="card">
            <div class="card-header">
                <span class="h4 m-0 p-0 row justify-content-center">{{ trans('Dashboard') }}</span>
            </div>
            <div class="card-body">
                <slot/>
            </div>
        </div>
    </home>
@endsection
