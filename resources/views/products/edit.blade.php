@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('products.title')

                    <a style="position: absolute; right: 25px; top: 6px;" href="{{route('products.audit', ['id' => $product->id])}}" class="btn btn-sm btn-link">@lang('products.btn.audit')</a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::open(['route' => ['products.save', $product->id ? : 'new']]) !!}

					<div class="row">
                        <div class="col-md-3">
							{{ Form::label('type', __('products.type'), ['class' => 'field-label']) }}
							@php ( $optionsArr = [''=>''])
							@foreach ($typeOptions as $option)
                                @php ( $optionsArr[$option] = __('products.types.' . $option) )
							@endforeach

                            {{ Form::select('type', $optionsArr, $product->type, ['class' => 'form-control js-select2-no-tags']) }}
						</div>

						<div class="col-md-3">
							{{ Form::label('brand', __('products.brand'), ['class' => 'field-label']) }}
							<!--{{ Form::text('brand', $product->brand, ['class' => 'form-control']) }}-->
                            <select class="form-control js-select2" id="brand" name="brand">
                                @foreach ($product->getDistinct('brand') as $value)
                                <option value="{{$value}}" {{$product->brand == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach
                            </select>
						</div>

						<div class="col-md-3">
							{{ Form::label('conception', __('products.conception'), ['class' => 'field-label']) }}
							<!--{{ Form::text('conception', $product->conception, ['class' => 'form-control']) }}-->
                            <select class="form-control js-select2" id="conception" name="conception">
                                @foreach ($product->getDistinct('conception') as $value)
                                <option value="{{$value}}" {{$product->conception == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach
                            </select>
						</div>

                        <div class="col-md-3">
							{{ Form::label('sub_conception', __('products.sub_conception'), ['class' => 'field-label']) }}
							<!--{{ Form::text('sub_conception', $product->sub_conception, ['class' => 'form-control']) }}-->
                            <select class="form-control js-select2" id="sub_conception" name="sub_conception">
                                @foreach ($product->getDistinct('sub_conception') as $value)
                                <option value="{{$value}}" {{$product->sub_conception == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach
                            </select>
						</div>
                    </div>

                    <hr/>

                    <div class="row">

						<div class="col-md-3">
							{{ Form::label('size', __('products.size'), ['class' => 'field-label']) }}
							<!--{{ Form::text('size', $product->size, ['class' => 'form-control']) }}-->
                            <select class="form-control js-select2" id="size" name="size">
                                @foreach ($product->getDistinct('size') as $value)
                                <option value="{{$value}}" {{$product->size == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach
                            </select>
						</div>



						<div class="col-md-3">
							{{ Form::label('kg_range', __('products.kg_range'), ['class' => 'field-label']) }}
							<!--{{ Form::text('kg_range', $product->kg_range, ['class' => 'form-control']) }}-->
                            <select class="form-control js-select2" id="kg_range" name="kg_range">
                                @foreach ($product->getDistinct('kg_range') as $value)
                                <option value="{{$value}}" {{$product->kg_range == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach
                            </select>
						</div>

                        <div class="col-md-3">
							{{ Form::label('diaper_count_in_package', __('products.diaper_count_in_package'), ['class' => 'field-label']) }}
							{{ Form::text('diaper_count_in_package', $product->diaper_count_in_package, ['class' => 'form-control']) }}
						</div>

                        <div class="col-md-3">
							{{ Form::label('machine', __('products.machine'), ['class' => 'field-label']) }}
                            <br />
                            {{ $product->machine ? : '---' }}
						</div>
					</div>

                    <hr/>

                    <div class="row">

						<div class="col-md-3">
							{{ Form::label('analysis_limits', __('products.analysis_limits'), ['class' => 'field-label']) }}
							@php ( $optionsArr = [''=>''])
							@foreach ($analysisLimitsOptions as $option)
                                @php ( $optionsArr[$option] = __('analysis_limits.' . $option) )
							@endforeach

                            {{ Form::select('analysis_limits', $optionsArr, $product->analysis_limits, ['class' => 'form-control']) }}
						</div>
					</div>
					<hr/>
                    <div class="row">
                        <div class="col-md-6 text-left">
                            @if ($product->recipes->isEmpty() && $product->samplesLists->isEmpty())
                                <button id='delete' class='btn btn-danger'> {{ __('products.btn.delete') }}</button>
                            @else
                                {{ __('products.cannot_delete') }}
                            @endif
                        </div>
                        <div class="col-md-6 text-right">
                            {{ Form::submit(__('products.btn.save'), ['class' => 'btn btn-success', 'name' => 'save']) }}
                            {{ Form::submit(__('products.btn.save_and_create_recipe'), ['class' => 'btn btn-info', 'name' => 'save_and_create_recipe']) }}
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
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('javascript')
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>

    <script>

    var editor;

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
        });

        $('#delete').click(function() {
            if (confirm("{{ __('products.confirm_delete') }}")) {
                window.location.href = "{{ route('products.delete', ['id' => $product->id]) }}";
            }
            return false;
        });

        $("form input").keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                return false;
            } else {
                return true;
            }
        });

        $('.js-select2').select2({
            tags: true,
            width: '100%'
        });

        $('.js-select2-no-tags').select2({
            width: '100%'
        });
    });
    </script>
@endsection
