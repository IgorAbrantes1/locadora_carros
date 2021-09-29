@extends('layouts.app')

@section('content')
    <brands page="{{ trans('Brands') }}" route_store_brand="{{ route('brand.store') }}"></brands>
@endsection
