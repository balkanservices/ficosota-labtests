@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('qa_journals.title')

                    <a style="position: absolute; right: 25px; top: 6px;" href="{{route('qa_journals.audit', ['id' => $qaJournal->id])}}" class="btn btn-sm btn-link">@lang('qa_journals.btn.audit')</a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

					{!! Form::open(['route' => ['qa_journals.save', $qaJournal->id ? : 'new']]) !!}

                    <div class="row">
						<div class="col-md-3">
							{{ Form::label('recipe_id', __('recipes.title'), ['class' => 'field-label']) }}
						</div>

                        <div class="col-md-3">
							@if($qaJournal->recipe)
                                <a href='{{ route('recipes.edit', ['id' => $qaJournal->recipe->id]) }}' target='_blank'>
                                    {{$qaJournal->recipe->getName()}}
                                </a>
                            @endif
						</div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
							{{ Form::label('batch_number', __('qa_journals.batch_number'), ['class' => 'field-label']) }}
						</div>

                        <div class="col-md-3">
							{{ Form::text('batch_number', $qaJournal->batch_number, ['class' => 'form-control']) }}
						</div>

                        <div class="col-md-3">
							{{ Form::label('batch_number', __('qa_journals.batch_date'), ['class' => 'field-label']) }}
						</div>

                        <div class="col-md-3 field-div-date">
                            <div class="form-group">
                                <div class='input-group date' id='batch_date'>
                                    <input type="text" value="{{$qaJournal->batch_date}}" class="form-control" id="rd_delivery_date" name="batch_date">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
					</div>

                    <hr />

					<div class="row">
						<div class="col-md-3">
							<strong>@lang('qa_journals.ingredients')</strong>
						</div>
                        <div class="col-md-5">
						</div>
                        <div class="col-md-2">
							<strong>@lang('qa_journals.option_batch_number')</strong>
						</div>
                        <div class="col-md-2">
							<strong>@lang('qa_journals.option_fs_batch_number')</strong>
						</div>
					</div>

					@foreach ($qaJournal->recipe->ingredients as $ingredient)

					<div class="row">
						<div class="col-md-3">
							{{ $ingredient->name }}:
						</div>

						<div class="col-md-5">
                            @php ( $optionsArr = [''=>''])
							@foreach ($ingredient->options as $option)
                                @php ( $optionsArr[$option->id] = $option->name . ' / ' .$option->supplier )
							@endforeach

                            @php ($selectedOption = isset($qaJournal->getSelectedOptions()[$ingredient->id]) ? $qaJournal->getSelectedOptions()[$ingredient->id] : null )
                            @php ($selectedOptionId = $selectedOption ? $selectedOption->option_id : null)

                            {{ Form::select('ingredients[' . $ingredient->id . ']', $optionsArr, $selectedOptionId, ['class' => 'form-control ingredient-select']) }}

						</div>

                        <div class="col-md-2">
                            {{ Form::text('option_batch_number[' . $ingredient->id . ']', $selectedOption ? $selectedOption->option_batch_number : '', ['class' => 'form-control']) }}
						</div>

                        <div class="col-md-2">
                            {{ Form::text('option_fs_batch_number[' . $ingredient->id . ']', $selectedOption ? $selectedOption->option_fs_batch_number : '', ['class' => 'form-control']) }}
						</div>
					</div>

					@endforeach

                    <hr />
                    <div class="row">
                        <div class="col-md-6 text-left">
                            @if ($qaJournal->samplesLists->isEmpty())
                                <button id='delete' class='btn btn-danger'> {{ __('qa_journals.btn.delete') }}</button>
                            @else
                                {{ __('qa_journals.cannot_delete') }}
                            @endif
                        </div>
                        <div class="col-md-6 text-right">
                        {{ Form::submit(__('qa_journals.btn.save'), ['class' => 'btn btn-success', 'name' => 'save']) }}
                        {{ Form::submit(__('qa_journals.btn.save_and_create_samples_list'), ['class' => 'btn btn-info', 'name' => 'save_and_create_samples_list']) }}
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
	$(function () {
{{--
		$('.btn').click(function (e){
            var stopSubmit = false;
            $('.ingredient-select').each(function (){
                if($(this).val() == '') {
                    if($(this).parent().prev().html().indexOf('Frontal wings') == -1) {
                        stopSubmit = true;
                    }
                }
            });

			if(stopSubmit) {
                alert('@lang("qa_journals.all_options_except_frontal_wings_need_to_be_selected")');
                e.preventDefault();
                return;
            }

		});
--}}
		$('.date').datetimepicker({
			allowInputToggle: true,
			format: "YYYY-MM-DD"
		});

        $('#delete').click(function() {
            if (confirm("{{ __('qa_journals.confirm_delete') }}")) {
                window.location.href = "{{ route('qa_journals.delete', ['id' => $qaJournal->id]) }}";
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
	});
    </script>

@endsection

