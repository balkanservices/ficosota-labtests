@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('users.users')</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {!! Form::open(['route' => ['users.save', $user->id]]) !!}

					<div class="row">
						<div class="col-md-3">
                            <strong>@lang('users.name')</strong>
						</div>
                        <div class="col-md-3">
                            {{ $user->name }}
                        </div>
                    </div>

                    <hr />

                    <div class="row">
						<div class="col-md-3">
                            <strong>@lang('users.email')</strong>
						</div>
                        <div class="col-md-3">
                            {{ $user->email }}
                        </div>
                    </div>

                    <hr />

                    <div class="row">
						<div class="col-md-3">
                            <strong>@lang('users.role')</strong>
						</div>
                        <div class="col-md-3">
                            @if(Auth::user()->hasRole('admin') || !$user->hasRole('admin'))
                            {{ Form::select('role', $roles, isset($user->roles[0]) ? $user->roles[0]->name : '', ['class' => 'form-control']) }}
                            @else
                            {{ isset($user->roles[0]) ? $user->roles[0]->description : '' }}
                            @endif
                        </div>
                    </div>

					<hr/>

                    <div class="row">
                        <div class="col-md-12 text-right">
                        {{ Form::submit(__('users.btn.save'), ['class' => 'btn btn-success', 'name' => 'save']) }}
                        </div>
                    </div>
					{!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css_files')
@endsection

@section('javascript')
    <script>

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>
@endsection
