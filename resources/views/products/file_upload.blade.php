@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('products.file_upload')</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {!! Form::open(['route' => ['products.process_file_upload'], 'files'=>'true']) !!}
					
					<div class="row">
						<div class="col-md-3">
							{{ Form::label('file', __('products.select_file_to_upload'), ['class' => 'field-label']) }}
							{{ Form::file('file') }}
						</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 text-right">
                        {{ Form::submit(__('products.btn.upload'), ['class' => 'btn btn-success', 'name' => 'upload']) }}
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
	<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
@endsection

@section('javascript')
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>

    <script>

    var editor;
	
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>
@endsection
