@extends('layouts.main')

@section('content')
@include('properties.list', ['property' => $property, 'categories' => $categories])
@endsection
