@extends('layouts.app')

@section('content')
    <brands page="{{ trans('Brands') }}" route_store_brand="{{ route('brand.store') }}" route_index_brand="{{ route('brand.index') }}"></brands>
@endsection
