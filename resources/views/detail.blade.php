@extends('layouts.app')

@section('content')
    <detail-component :action="'{{$action}}'"></detail-component>
@endsection