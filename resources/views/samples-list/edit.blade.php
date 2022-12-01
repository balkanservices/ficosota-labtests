@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('samples_list.title')

                    <a style="position: absolute; right: 25px; top: 6px;" href="{{route('samples_lists.audit', ['id' => $samplesList->id])}}" class="btn btn-sm btn-link">@lang('samples_list.btn.audit')</a>
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

					{!! Form::open(['route' => ['samples_lists.save', $samplesList->id ? : 'new']]) !!}
					<div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    {{ Form::label('qa_journal_id', __('qa_journals.title'), ['class' => 'field-label']) }}
                                </div>

                                <div class="col-md-6">
                                    @if (empty($samplesList->product_id))
                                        {{ Form::select('qa_journal_id', $qaJournals, $samplesList->qa_journal_id, ['class' => 'form-control js-select2-search']) }}
                                    @else
                                        {{ Form::select('qa_journal_id', $qaJournals, $samplesList->qa_journal_id, ['class' => 'form-control js-select2-search', 'disabled' => true]) }}
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    @lang('samples_list.or')
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    {{ Form::label('product_id', __('products.competitive_title'), ['class' => 'field-label']) }}
                                </div>

                                <div class="col-md-6">
                                    @if (empty($samplesList->qa_journal_id))
                                        {{ Form::select('product_id', $products, $samplesList->product_id, ['class' => 'form-control js-select2-search']) }}
                                    @else
                                        {{ Form::select('product_id', $products, $samplesList->product_id, ['class' => 'form-control js-select2-search', 'disabled' => true]) }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            @php ($linkedProduct = null)
                            @if($samplesList->qa_journal)
                                @if($samplesList->qa_journal->recipe)
                                    @if($samplesList->qa_journal->recipe->product)
                                        @lang('products.title'): <a href='{{ route('products.edit', ['id' => $samplesList->qa_journal->recipe->product->id]) }}' target='_blank'>
                                            {{$samplesList->qa_journal->recipe->product->getName()}}
                                            @php ($linkedProduct = $samplesList->qa_journal->recipe->product )
                                        </a><br />
                                    @endif

                                    @lang('recipes.title'): <a href='{{ route('recipes.edit', ['id' => $samplesList->qa_journal->recipe->id]) }}' target='_blank'>
                                        {{$samplesList->qa_journal->recipe->getName()}}
                                    </a>
                                @endif

                                <br />

                                @lang('qa_journals.title'): <a href='{{ route('qa_journals.edit', ['id' => $samplesList->qa_journal->id]) }}' target='_blank'>
                                    {{$samplesList->qa_journal->getShortName()}}
                                </a>
                            @endif

                            @if($sampleListProduct)
                                @lang('products.title'): <a href='{{ route('products.edit', ['id' => $sampleListProduct->id]) }}' target='_blank'>
                                    {{$sampleListProduct->getName()}}
                                    @php ($linkedProduct = $sampleListProduct )
                                </a>
                            @endif

                        </div>
					</div>

                    <br />

                    <br />

                    <div class="row">

                        <div class="col-md-3 field-div-text">
                            <label class="field-label">@lang('products.brand')</label><br />
                            {{ $linkedProduct ? $linkedProduct->brand : ''}}
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label">@lang('products.conception')</label><br />
                            {{ $linkedProduct ? $linkedProduct->conception : ''}}
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label">@lang('products.size')</label><br />
                            {{ $linkedProduct ? $linkedProduct->size : ''}}
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label">@lang('products.kg_range')</label><br />
                            {{ $linkedProduct ? $linkedProduct->kg_range : ''}}
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label">@lang('products.type')</label><br />
                            @if ($linkedProduct)
                                @lang('products.types.' . $linkedProduct->type)
                            @endif
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label">@lang('products.sub_conception')</label><br />
                            {{ $linkedProduct ? $linkedProduct->sub_conception : ''}}
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="region">@lang('samples_list.region')</label><br />
                            <select class="form-control js-select2" id="region" name="region">
                                @php($distinctValues = $samplesList->getDistinct('region'))
                                @foreach ($distinctValues as $value)
                                <option value="{{$value}}" {{old('region', $samplesList->region) == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach

                                @php ($newOldValue = old('region'))
                                @if(!empty($newOldValue) && !in_array($newOldValue, $distinctValues))
                                <option value="{{$newOldValue}}" selected="selected">{{$newOldValue}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="market">@lang('samples_list.market')</label>
                            <select class="form-control js-select2" id="market" name="market">
                                @php($distinctValues = $samplesList->getDistinct('market'))
                                @foreach ($distinctValues as $value)
                                <option value="{{$value}}" {{old('market', $samplesList->market) == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach

                                @php ($newOldValue = old('market'))
                                @if(!empty($newOldValue) && !in_array($newOldValue, $distinctValues))
                                <option value="{{$newOldValue}}" selected="selected">{{$newOldValue}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="country_of_origin">@lang('samples_list.country_of_origin')</label>
                            <select class="form-control js-select2" id="country_of_origin" name="country_of_origin">
                                @php($distinctValues = $samplesList->getDistinct('country_of_origin'))
                                @foreach ($distinctValues as $value)
                                <option value="{{$value}}" {{old('country_of_origin', $samplesList->country_of_origin) == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach

                                @php ($newOldValue = old('country_of_origin'))
                                @if(!empty($newOldValue) && !in_array($newOldValue, $distinctValues))
                                <option value="{{$newOldValue}}" selected="selected">{{$newOldValue}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-3 field-div-date">
                            <label class="field-label" for="manifacturing_date">@lang('samples_list.manifacturing_date')</label>
                            <div class="form-group">
                                <div class='input-group date' id='manifacturing_date_div'>
                                    <input type="text" value="{{old('manifacturing_date', $samplesList->manifacturing_date)}}" class="form-control" id="manifacturing_date" name="manifacturing_date">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="batch">@lang('samples_list.batch')</label>
                            <select class="form-control js-select2" id="batch" name="batch">
                                @php($distinctValues = $samplesList->getDistinct('batch'))
                                @foreach ($distinctValues as $value)
                                <option value="{{$value}}" {{old('batch', $samplesList->batch) == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach

                                @php ($newOldValue = old('batch'))
                                @if(!empty($newOldValue) && !in_array($newOldValue, $distinctValues))
                                <option value="{{$newOldValue}}" selected="selected">{{$newOldValue}}</option>
                                @endif
                            </select>
                        </div>

                         <div class="col-md-3 field-div-text">
                            <label class="field-label" for="manifacturer">@lang('samples_list.manifacturer')</label>
                            <select class="form-control js-select2" id="manifacturer" name="manifacturer">
                                @php($distinctValues = $samplesList->getDistinct('manifacturer'))
                                @foreach ($distinctValues as $value)
                                <option value="{{$value}}" {{old('manifacturer', $samplesList->manifacturer) == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach

                                @php ($newOldValue = old('manifacturer'))
                                @if(!empty($newOldValue) && !in_array($newOldValue, $distinctValues))
                                <option value="{{$newOldValue}}" selected="selected">{{$newOldValue}}</option>
                                @endif
                            </select>
                        </div>


                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="sample_number">@lang('samples_list.sample_number')</label>
							{{ Form::text('sample_number', $samplesList->sample_number, ['class' => 'form-control text-center']) }}
						</div>


                        <div class="col-md-3 field-div-date">
                            <label class="field-label" for="rd_delivery_date">@lang('samples_list.rd_delivery_date')</label>

                            @php ($rdDateFilled = empty(old('rd_delivery_date', $samplesList->rd_delivery_date)) ? false: true)
                            <div class="form-group">
                                <div class='input-group date' id='rd_delivery_date_div'>
                                    <input type="text" value="{{old('rd_delivery_date', $samplesList->rd_delivery_date)}}" class="form-control {{$rdDateFilled ? '' : 'required'}}" id="rd_delivery_date" name="rd_delivery_date">
                                    <span class="input-group-addon {{$rdDateFilled ? '' : 'required'}}">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="priority">@lang('samples_list.priority')</label>
                            <select class="form-control js-select2" id="priority" name="priority">
                                @php($distinctValues = $samplesList->getDistinct('priority'))
                                @foreach ($distinctValues as $value)
                                <option value="{{$value}}" {{old('priority', $samplesList->priority) == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach

                                @php ($newOldValue = old('priority'))
                                @if(!empty($newOldValue) && !in_array($newOldValue, $distinctValues))
                                <option value="{{$newOldValue}}" selected="selected">{{$newOldValue}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="elastic_elements">@lang('samples_list.elastic_elements')</label>
                            <select class="form-control js-select2" id="elastic_elements" name="elastic_elements">
                                @php($distinctValues = $samplesList->getDistinct('elastic_elements'))
                                @foreach ($distinctValues as $value)
                                <option value="{{$value}}" {{old('elastic_elements', $samplesList->elastic_elements) == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach

                                @php ($newOldValue = old('elastic_elements'))
                                @if(!empty($newOldValue) && !in_array($newOldValue, $distinctValues))
                                <option value="{{$newOldValue}}" selected="selected">{{$newOldValue}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-3 field-div-date">
                            <label class="field-label" for="manifacturing_time">@lang('samples_list.manifacturing_time')</label>
                            <div class="form-group">
                                <div class='input-group time' id='manifacturing_time_div'>
                                    <input type="text" value="{{old('manifacturing_time', $samplesList->manifacturing_time)}}" class="form-control" id="manifacturing_time" name="manifacturing_time">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="samples_count">@lang('samples_list.samples_count')</label>
							{{ Form::text('samples_count', $samplesList->samples_count, ['class' => 'form-control text-center']) }}
						</div>

                        <div class="col-md-3 field-div-date">
                            <label class="field-label" for="buying_date">@lang('samples_list.buying_date')</label>

                            @php ($buyingDateFilled = empty(old('buying_date', $samplesList->buying_date)) ? false: true)
                            <div class="form-group">
                                <div class='input-group date' id='buying_date_div'>
                                    <input type="text" value="{{old('buying_date', $samplesList->buying_date)}}" class="form-control {{$buyingDateFilled ? '' : 'required'}}" id="buying_date" name="buying_date">
                                    <span class="input-group-addon {{$buyingDateFilled ? '' : 'required'}}">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 field-div-textarea">
                            <label class="field-label" for="pictograms">@lang('samples_list.pictograms')</label>
                            <textarea class="form-control" id="pictograms" name="pictograms">{{old('pictograms', $samplesList->pictograms)}}</textarea>
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="breathable_sheet">@lang('samples_list.breathable_sheet')</label>
                            <select class="form-control js-select2" id="breathable_sheet" name="breathable_sheet">
                                @php($distinctValues = $samplesList->getDistinct('breathable_sheet'))
                                @foreach ($distinctValues as $value)
                                <option value="{{$value}}" {{old('breathable_sheet', $samplesList->breathable_sheet) == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach

                                @php ($newOldValue = old('breathable_sheet'))
                                @if(!empty($newOldValue) && !in_array($newOldValue, $distinctValues))
                                <option value="{{$newOldValue}}" selected="selected">{{$newOldValue}}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 field-div-textarea">
                            <label class="field-label" for="lotion_smell">@lang('samples_list.lotion_smell')</label>
                            <textarea class="form-control" id="lotion_smell" name="lotion_smell">{{old('lotion_smell', $samplesList->lotion_smell)}}</textarea>
                        </div>

                        <div class="col-md-6 field-div-textarea">
                            <label class="field-label" for="lotion_composition">@lang('samples_list.lotion_composition')</label>
                            <textarea class="form-control" id="lotion_composition" name="lotion_composition">{{old('lotion_composition', $samplesList->lotion_composition)}}</textarea>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-3 field-div-date">
                            <label class="field-label" for="analysis_start_date">@lang('samples_list.analysis_start_date')</label>
                            <div class="form-group">
                                <div class='input-group date' id='analysis_start_date_div'>
                                    <input type="text" value="{{old('analysis_start_date', $samplesList->analysis_start_date)}}" class="form-control" id="analysis_start_date" name="analysis_start_date">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 field-div-date">
                            <label class="field-label" for="analysis_planned_end_date">@lang('samples_list.analysis_planned_end_date')</label>
                            <div class="form-group">
                                <div class='input-group date' id='analysis_planned_end_date_div'>
                                    <input type="text" value="{{old('analysis_planned_end_date', $samplesList->analysis_planned_end_date)}}" class="form-control" id="analysis_planned_end_date" name="analysis_planned_end_date">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 field-div-date">
                            <label class="field-label" for="analysis_end_date">@lang('samples_list.analysis_end_date')</label>
                            <div class="form-group">
                                <div class='input-group date' id='analysis_end_date_div'>
                                    <input type="text" value="{{old('analysis_end_date', $samplesList->analysis_end_date)}}" class="form-control" id="analysis_end_date" name="analysis_end_date">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 field-div-textarea">
                            <label class="field-label" for="status">@lang('samples_list.status')</label>
                            <textarea class="form-control" id="status" name="status">{{old('status', $samplesList->status)}}</textarea>
                        </div>
                        <div class="col-md-6 field-div-textarea">
                            <label class="field-label" for="comment">@lang('samples_list.comment')</label>
                            <textarea class="form-control" id="comment" name="comment">{{old('comment', $samplesList->comment)}}</textarea>
                        </div>

                        <div class="col-md-3 field-div-text">
                            <label class="field-label" for="urgent_analysis_samples">@lang('samples_list.urgent_analysis_samples')</label>
                            <select class="form-control js-select2" id="urgent_analysis_samples" name="urgent_analysis_samples">
                                @php($distinctValues = $samplesList->getDistinct('urgent_analysis_samples'))
                                @foreach ($distinctValues as $value)
                                <option value="{{$value}}" {{old('urgent_analysis_samples', $samplesList->urgent_analysis_samples) == $value ? 'selected="selected"': ''}}>{{$value}}</option>
                                @endforeach

                                @php ($newOldValue = old('urgent_analysis_samples'))
                                @if(!empty($newOldValue) && !in_array($newOldValue, $distinctValues))
                                <option value="{{$newOldValue}}" selected="selected">{{$newOldValue}}</option>
                                @endif
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 text-left">
                            <button id='delete' class='btn btn-danger'> {{ __('samples_list.btn.delete') }}</button>
                        </div>
                        <div class="col-md-6 text-right">
                        {{ Form::submit(__('samples_list.btn.save'), ['class' => 'btn btn-success', 'name' => 'save']) }}
                        </div>
                    </div>

					{!! Form::close() !!}

                    <hr />
                    <div class="row">
                        <div class="col-md-8">
                            <label class="field-label" for="packages">@lang('samples_list.header.packages')</label>
                        </div>
                        <div class="col-md-4">
                            <!--<button type="button" class="form-control" id="add_package">@lang('samples_list.create')</button>-->
                        </div>
                    </div>
                    <div class="row">

                        <table id="packages" class="table table-striped table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>@lang('samples_list.manifacturing_time')</th>
                                    <th>@lang('samples_list.samples_count')</th>
                                    <th>@lang('samples_list.comment')</th>
                                    <th>@lang('samples_list.action')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css_files')
	<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/datatables/Editor-1.6.5/css/editor.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/datatables/Editor-1.6.5/css/editor.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Editor-1.6.5/js/dataTables.editor.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Editor-1.6.5/js/editor.bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>

   	<script>

    var editor;
    var packages_editor;

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	$(document).ready(function() {
        var packages_editor = new $.fn.dataTable.Editor( {
			ajax: "{{ route('samples_list.packages.json_post', ['samplesListId' => $samplesList->id]) }}",
			idSrc:  'id',
			table: "#packages",
			fields: [ {
                    label: "@lang('samples_list.manifacturing_time'):",
					name: "manifacturing_time"
				}, {
					label: "@lang('samples_list.samples_count'):",
					name: "samples_count",
					attr: {
						type: 'number'
					}
				}, {
					label: "@lang('samples_list.comment'):",
					name: "comment",
					attr: {
						type: 'textarea'
					}
				}
			]
		} );

		// Activate an inline edit on click of a table cell
		$('#packages').on( 'click', 'tbody td', function (e) {
			packages_editor.inline( this );
		} );

		$('#packages').DataTable( {
			dom: "Bfrtip",
			ajax: "{{ route('samples_list.packages.json', ['samplesListId' => $samplesList->id]) }}",
			order: [],
			searching: false,
			paging: false,
			bInfo: false,
			columns: [
				{ data: "manifacturing_time", sClass: "text-center" },
                { data: "samples_count", sClass: "text-center" },
                { data: "comment", sClass: "text-center" },
                { data: null, sClass: "text-center", "defaultContent": "" }
			],
            columnDefs: [{"targets":3, "data":"id", "render": function(data,type,full,meta)
            {
                var packageUrl = "{{ route('samples_list.package.index', ['packageId' => '__ID__']) }}";
                packageUrl = packageUrl.replace('__ID__', data.id);
                return '<a class="btn btn-info btn-xs" href="' + packageUrl + '">@lang("samples_list.package_analyses")</a>';
            }}],
			select: {
				style:    'multi',
				selector: 'td:first-child'
			},
            keys: {
                keys: [ 9, 13 ],
                editor: packages_editor,
                editOnFocus: true,
                focus: ':eq(0)'

            },
            buttons: [
//				{ extend: "create", editor: editor },
//				{ extend: "edit",   editor: editor },
//				{ extend: "remove", editor: editor }
			]
		} );

        packages_editor.on('initEdit', function() {
			packages_editor.show();
            packages_editor.disable('manifacturing_time');
            packages_editor.disable('samples_count');
		});

        packages_editor.on('submitComplete', function() {
		});

//        $('#add_package').click(function(){
//            $.ajax({
//				type: "POST",
//				url: "{{ route('samples_list.packages.create_rows', ['samplesListId' => $samplesList->id]) }}",
//				data: {
//					new_rows: 1
//				},
//				success: function( msg ) {
//					$('#packages').DataTable().ajax.reload();
//				}
//			});
//			return;
//		});

        $('#qa_journal_id').change(function() {
            if($('#qa_journal_id').val() !== '') {
                $('#product_id').val('');
                $('#product_id').prop("disabled", true);
            } else {
                $('#product_id').prop("disabled", false);
            }
        });

        $('#product_id').change(function() {
            if($('#product_id').val() !== '') {
                $('#qa_journal_id').val('');
                $('#qa_journal_id').prop("disabled", true);
            } else {
                $('#qa_journal_id').prop("disabled", false);
            }
        });
	} );


	$(function () {
		$('.date').datetimepicker({
			allowInputToggle: true,
			format: "YYYY-MM-DD"
		});

		$('.time').datetimepicker({
			allowInputToggle: true,
			format: "HH:mm"
		});

        $('#delete').click(function() {
            if (confirm("{{ __('samples_list.confirm_delete') }}")) {
                window.location.href = "{{ route('samples_list.delete', ['id' => $samplesList->id]) }}";
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

        $('.js-select2-search').select2({
            tags: false,
            dropdownAutoWidth : true
        });
	});
    </script>

@endsection
