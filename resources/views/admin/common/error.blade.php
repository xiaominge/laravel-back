@extends('layouts.admin')

@section('title', config('app.name') . ' - 管理平台 - 错误页面')

@section('css')
    <style></style>
@endsection

@section('content')
    @php
        @endphp
    <div class="layui-container">
        <div class="fly-panel">
            <div class="fly-none">
                <h2><i class="layui-icon layui-icon-404"></i></h2>
                <p>
                    @if($errors->has('msg'))
                        {{ $errors->first('msg') }}
                    @endif
                </p>
            </div>
        </div>
    </div>

@endsection
