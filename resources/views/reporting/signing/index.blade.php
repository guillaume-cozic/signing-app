@extends('adminlte::page')

@section('content')
    @include('reporting.signing.reporting')
@endsection

@section('adminlte_js')
    @parent
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
@endsection
