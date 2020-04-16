@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    @include('user.reset_password_form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection