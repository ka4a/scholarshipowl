@extends('admin.base')
@section('content')
    <div class="box">
        <div class="box-header">
            <div class="box-name">
                Transactional Email - Test
            </div>
        </div>
        <div class="box-content">
            @include('admin.marketing.transactional_email.test-form')
        </div>
    </div>
@endsection
