@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class='pull-left'>@lang('recipes.title')</div>

                    <div class="text-right ">

                        @if ($recipe->getPreviousRevision())
                            <a class='btn btn-sm btn-default' href="{{  route('recipes.edit', ['id' => $recipe->getPreviousRevision()->id]) }}">{{ __('recipes.previous_revision') }}</a>
                        @else
                            <button class='btn btn-sm btn-default' disabled='disabled'>{{ __('recipes.previous_revision') }}</button>
                        @endif

                        @if ($recipe->getNextRevision())
                            <a class='btn btn-sm btn-default'href="{{  route('recipes.edit', ['id' => $recipe->getNextRevision()->id]) }}">{{ __('recipes.next_revision') }}</a>
                        @else
                            <button class='btn btn-sm btn-default' disabled='disabled'>{{ __('recipes.next_revision') }}</button>
                        @endif

                        <a href="{{route('recipes.audit_all_revisions', ['id' => $recipe->id])}}" class="btn btn-sm btn-link">@lang('recipes.btn.audit')</a>

                    </div>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {!! Form::open(['route' => ['recipes.save', $recipe->id ? : 'new']]) !!}

                    <div class="row">
                        <div class="col-md-3">
							<label for="product_id" class="field-label">{{__('products.title')}}</label>
						</div>

                        <div class="col-md-3 text-center">
                            @if ($recipe->product)
                                <a href='{{ route('products.edit', ['id' => $recipe->product->id]) }}' target='_blank'>
                                    {{$recipe->product->getName()}}
                                </a>
                            @else
                                ---
                            @endif
						</div>

                        <div class="col-md-6 text-center">
                            <a class='btn btn-sm btn-info' href="{{  route('recipes.download.word', ['id' => $recipe->id]) }}">{{ __('recipes.download.word') }}</a>

                            <a class='btn btn-sm btn-info' href="{{  route('recipes.download.pdf', ['id' => $recipe->id]) }}">{{ __('recipes.download.pdf') }}</a>
                        </div>
					</div>

                    <hr />

                    <div class="row">
						<div class="col-md-3">
							{{ Form::label('revision_number', __('recipes.revision_number'), ['class' => 'field-label']) }}
						</div>

                        <div class="col-md-3">
							{{ Form::number('revision_number', $recipe->revision_number, ['class' => 'form-control', 'style' => 'transform: none; text-align: center']) }}
						</div>

                        <div class="col-md-3">
							{{ Form::label('revision_date', __('recipes.revision_date'), ['class' => 'field-label']) }}
						</div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class='input-group date date-picker'>
                                    <input type='text' name='revision_date' class="form-control" value='{{ $recipe->revision_date }}' />
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
							{{ Form::label('rd_specialist_id', __('recipes.rd_specialist'), ['class' => 'field-label']) }}
						</div>

                        <div class="col-md-3">
							{{ Form::select('rd_specialist_id', [""=>""] + $rdSpecialists, $recipe->rd_specialist_id, ['class' => 'form-control']) }}
						</div>

                        <div class="col-md-3">
							{{ Form::label('in_effect_from', __('recipes.in_effect_from'), ['class' => 'field-label']) }}
						</div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class='input-group date date-picker'>
                                    <input type='text' name='in_effect_from' class="form-control" value='{{ $recipe->in_effect_from }}' />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
						</div>
					</div>

					<hr />

					@foreach ($recipe->visibleIngredients() as $ingredient)

					<div class="row">
						<div class="col-md-2 text-left ingredient-name-div">
							{{-- Form::text('ingredient_name', $ingredient->name, ['class' => 'form-control']) --}}
                            {{$ingredient->name}}
                            <div id='ingredient_{{$ingredient->id}}_buttons' class='recipe-buttons'></div>
						</div>
						<div class="col-md-10">

							<table id="ingredient_{{$ingredient->id}}"
                                   data-ingredient-id="{{$ingredient->id}}"
                                   data-has-cut-length="{{$ingredient->hasCutLength()}}"
                                   data-has-elastics-count-and-elongation="{{$ingredient->hasElasticsCountAndElongation()}}"
                                   class="table table-striped table-bordered options-table" width="100%" cellspacing="0">
								<thead>
									<tr>
                                        <th>@lang('recipes.ingredient_options.type.header')</th>
                                        <th>@lang('recipes.ingredient_options.priority')</th>
										<th class='col-sm-4'>@lang('recipes.ingredient.trade_name')</th>
										<th>@lang('recipes.ingredient.width')</th>
                                        @if($ingredient->hasCutLength())
                                        <th>@lang('recipes.ingredient.cut_length')</th>
                                        @endif
										<th>@lang('recipes.ingredient.supplier')</th>
										<th>@lang('recipes.ingredient.metric_unit')</th>
										<th>@lang('recipes.ingredient.consumption_per_piece')</th>
                                        @if($ingredient->hasElasticsCountAndElongation())
                                        <th>@lang('recipes.ingredient.elastics_count')</th>
                                        <th>@lang('recipes.ingredient.elongation')</th>
                                        @endif
										<th class='col-sm-4'>@lang('recipes.ingredient.comment')</th>
									</tr>
								</thead>
							</table>

						</div>
					</div>
					<hr>
					@endforeach



                    <div class="row">
						<div class="col-md-12">
							{{ Form::label('comment', __('recipes.comment'), ['class' => 'field-label']) }}
							{{ Form::textarea('comment', $recipe->comment, ['class' => 'form-control', 'rows' => 5]) }}
						</div>
					</div>

                    <hr />

                    <div class="row">
						<div class="col-md-12">
                            {{ Form::checkbox('final_version', null, $recipe->final_version, ['id' => 'final_version']) }}
							{{ Form::label('final_version', __('recipes.final_version'), ['class' => 'field-label']) }}
						</div>
					</div>

                    <hr />

                    <div class="row">
                        <div class="col-md-6 text-left">
                            @if ($recipe->usedMaterials->isEmpty())
                                <button id='delete' class='btn btn-danger'> {{ __('recipes.btn.delete') }}</button>
                            @else
                                {{ __('recipes.cannot_delete') }}
                            @endif
                        </div>
                        <div class="col-md-6 text-right">
                        {{ Form::submit(__('recipes.btn.save'), ['class' => 'btn btn-success', 'name' => 'save']) }}
                        {{ Form::submit(__('recipes.btn.save_and_create_new_revision'), ['class' => 'btn btn-warning', 'name' => 'save_and_create_new_revision']) }}
{{--
                        {{ Form::submit(__('recipes.btn.save_and_create_qa_journal'), ['class' => 'btn btn-info', 'name' => 'save_and_create_qa_journal']) }}
--}}
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
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/datatables/Editor-1.6.5/css/editor.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/datatables/Editor-1.6.5/css/editor.bootstrap.min.css') }}" rel="stylesheet">
{{--    <link href="{{ asset('js/datatables/Editor-1.6.5/css/editor.jqueryui.min.css') }}" rel="stylesheet"> --}}
@endsection

@section('javascript')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Editor-1.6.5/js/dataTables.editor.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Editor-1.6.5/js/editor.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/editor.autoComplete.js') }}"></script>
{{--    <script src="{{ asset('js/datatables/Editor-1.6.5/js/editor.jqueryui.min.js') }}"></script> --}}
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>

    <script>

    var editor;

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var optionTypes = {!! json_encode($optionTypes) !!};

    function getAutocompleteData(request, response, field) {
        $.ajax({
            url: '{{ route('recipe.options_autocomplete_json') }}',
            dataType: 'json',
            data: {
                type: field,
                q: request.term
            },
            success: function (json) {
                response(json);
            }
        });
    }

    function addOption(ingredientId) {
        var route = '{{ route('recipe.options_json', ['ingredientId' => '__INGREDIENT_ID__']) }}';
		route = route.replace('__INGREDIENT_ID__', ingredientId);

        $.ajax({
            type: "POST",
            url: route,
            data: {
                'action': 'create',
                data: [
                    {
                        'trade_name' : ''
                    }
                ]
            },
            success: function( msg ) {
                $('#ingredient_' + ingredientId).DataTable().ajax.reload();
            }
        });
        return;
    }

	function initOptionsTable(ingredientId, hasCutLength, hasElasticsCountAndElongation) {

		var route = '{{ route('recipe.options_json', ['ingredientId' => '__INGREDIENT_ID__']) }}';
		route = route.replace('__INGREDIENT_ID__', ingredientId);

		var tableId = "#ingredient_" + ingredientId;

        var fields = [];
        fields.push({
            type:     'select',
            label:    '@lang('recipes.ingredient_option.type'):',
            name:     'type',
            multiple: false,
            options: [
                { label: '',	value: '' },
                @foreach ($optionTypes as $optionType => $optionTypeLabel)
                    { label: '{{$optionTypeLabel}}',	value: '{{$optionType}}' },
                @endforeach
            ]
        });
        fields.push({
            type:     'select',
            label:    '@lang('recipes.ingredient_option.priority'):',
            name:     'priority',
            multiple: false,
            options: [
                { label: '',	value: '' },
                @for ($i=1;$i<=3;$i++)
                    { label: '{{$i}}',	value: '{{$i}}' },
                @endfor
            ]
        });
        fields.push({
            label: "@lang('recipes.ingredient.trade_name'):",
            name: "name",
            attr: {
                type: 'text',
            },
            type: "autoComplete",
            opts: {
                source: function (request, response) {
                    getAutocompleteData(request, response, "trade_name");
                },
                select: function (event, ui) {
                    editor.submit();
                    return false;
                }
            }
        });

        fields.push({
            label: "@lang('recipes.ingredient.width'):",
            name: "width",
            attr: {
                type: 'text'
            },
            type: "autoComplete",
            opts: {
                source: function (request, response) {
                    getAutocompleteData(request, response, "width");
                },
                select: function (event, ui) {
                    editor.submit();
                    return false;
                }
            }
        });

        if(hasCutLength) {
            fields.push({
                label: "@lang('recipes.ingredient.cut_length'):",
                name: "cut_length",
                attr: {
                    type: 'text'
                },
                type: "autoComplete",
                opts: {
                    source: function (request, response) {
                        getAutocompleteData(request, response, "cut_length");
                    },
                    select: function (event, ui) {
                        editor.submit();
                        return false;
                    }
                }
            });
        }

        fields.push({
            label: "@lang('recipes.ingredient.supplier'):",
            name: "supplier",
            attr: {
                type: 'text'
            },
            type: "autoComplete",
            opts: {
                source: function (request, response) {
                    getAutocompleteData(request, response, "supplier");
                },
                select: function (event, ui) {
                    editor.submit();
                    return false;
                }
            }
        });
        fields.push({
            label: "@lang('recipes.ingredient.metric_unit'):",
            name: "metric_unit",
            attr: {
                type: 'text'
            },
            type: "autoComplete",
            opts: {
                source: function (request, response) {
                    getAutocompleteData(request, response, "metric_unit");
                },
                select: function (event, ui) {
                    editor.submit();
                    return false;
                }
            }
        });
        fields.push({
            label: "@lang('recipes.ingredient.consumption_per_piece'):",
            name: "consumption_per_piece",
            attr: {
                type: 'text'
            },
            type: "autoComplete",
            opts: {
                source: function (request, response) {
                    getAutocompleteData(request, response, "consumption_per_piece");
                },
                select: function (event, ui) {
                    editor.submit();
                    return false;
                }
            }
        });

        if(hasElasticsCountAndElongation) {
            fields.push({
                label: "@lang('recipes.ingredient.elastics_count'):",
                name: "elastics_count",
                attr: {
                    type: 'text'
                }
            });

            fields.push({
                label: "@lang('recipes.ingredient.elongation'):",
                name: "elongation",
                attr: {
                    type: 'text'
                }
            });
        }

        fields.push({
            label: "@lang('recipes.ingredient.comment'):",
            name: "comment",
            attr: {
                type: 'text'
            }
        });

		var editor = new $.fn.dataTable.Editor( {
			ajax: route,
			idSrc:  'id',
			table: "#ingredient_" + ingredientId,
			fields: fields
		} );

		// Activate an inline edit on click of a table cell
        $(tableId).on( 'click', 'tbody td', function (e) {
			editor.inline( this, {
                onBlur: 'submit'
            });
		} );

        var columns = [];
        columns.push({
            data: "type",
            render: function ( data, type, row ) {
                if(data == null) {
                    return '';
                }

                return optionTypes[data];
            }
        });
        columns.push({ data: "priority", className: 'text-center',});
        columns.push({ data: "name"});
        columns.push({ data: "width", sClass: "text-center" });
        if(hasCutLength) {
            columns.push({ data: "cut_length"});
        }
        columns.push({ data: "supplier" });
        columns.push({ data: "metric_unit", sClass: "text-center" });
        columns.push({ data: "consumption_per_piece", sClass: "text-right" });
        if(hasElasticsCountAndElongation) {
            columns.push({ data: "elastics_count"});
            columns.push({ data: "elongation"});
        }
        columns.push({ data: "comment" });

		var table = $(tableId).DataTable( {
			dom: "Bfrtip",
			ajax: route,
			order: [],
			searching: false,
			paging: false,
			bInfo: false,
			ordering: false,
			language: {
				decimal: "."
			},
			columns: columns,
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
			buttons: [
                {
                    text: '@lang('recipes.btn.new_option')',
                    action: function ( e, dt, node, config ) {
                        addOption(ingredientId);
                    },
                    className: 'buttons-create'
                }
			],
            keys: {
                keys: [ 9, 13 ],
                editor: editor,
                editOnFocus: true,
                focus: ':eq(0)',
                columns: ':not(:first-child)'
            },
            "fnRowCallback": function( nRow, aData ) {
                if (aData.type == '{{ App\RecipeIngredientOption::TYPE_MAIN }}') {
                    $(nRow).addClass("main-material");
                } else {
                    $(nRow).removeClass("main-material");
                }

                if (aData.has_changed) {
                    $(nRow).addClass("has-changed");
                } else {
                    $(nRow).removeClass("has-changed");
                }
            }
		} );

        editor.on('initEdit', function() {
			editor.show();
		});

        //This is needed because of how the autocomplete plugin affects events
        $( "form" ).keypress(function( event ) {
            if ( event.which == 13 ) {
                editor.submit();
                return false;
            }
        });

        table.buttons().container().appendTo( $(tableId + '_buttons') );
	}


	$(document).ready(function() {

		$('.options-table').each(function() {
			initOptionsTable($(this).data('ingredient-id'), $(this).data('has-cut-length'), $(this).data('has-elastics-count-and-elongation'));
		});

        $('.date-picker').datetimepicker({
            defaultDate: "@php (date('Y-m-d'))",
            format: "YYYY-MM-DD"
        });

        $('#delete').click(function() {
            if (confirm("{{ __('recipes.confirm_delete') }}")) {
                window.location.href = "{{ route('recipe.delete', ['id' => $recipe->id]) }}";
            }
            return false;
        });
	} );

    </script>
@endsection
