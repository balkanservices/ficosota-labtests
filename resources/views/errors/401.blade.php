@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('errors.header')</div>

                <div class="panel-body">
                    @lang('errors.not_authorized')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
