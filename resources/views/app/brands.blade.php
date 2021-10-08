@extends('layouts.app')

@section('content')
    <brands page="{{ trans('Brands') }}" route_index_brand="{{ route('brand.index') }}"></brands>
@endsection
