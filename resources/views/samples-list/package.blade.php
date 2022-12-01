@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('samples_list.packages')
                    ( {{$samplesList->getPackageInfo()}} )
                    <a style="position: absolute; right: 250px; top: 6px;" href="{{route('samples_lists.audit_package', ['id' => $samplesPackage->id])}}" class="btn btn-sm btn-link">@lang('samples_list.btn.audit')</a>

                    <a style="position: absolute; right: 25px; top: 6px;" href="{{route('samples_lists.edit', ['id' => $samplesList->id])}}" class="btn btn-sm btn-success">@lang('samples_list.back_to_samples_list')</a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">

                        <table id="packages" class="table table-striped table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>@lang('samples_list.manifacturing_time')</th>
                                    <th>@lang('samples_list.samples_count')</th>
                                    <th>@lang('samples_list.comment')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="row">
						<div class="col-md-3">
							@lang('samples_list.analyses'):
						</div>
					</div>

					<div class="row">
                        @php ($enabledAnalyses = $samplesPackage->getEnabledAnalyses())
                        @php ($enabledAnalysesWithStatus = $samplesPackage->getEnabledAnalysesWithStatus())
                        @foreach ($analysisDefinitions as $analysisDefinition)
						<div class="col-md-3">
                            @if (in_array($analysisDefinition->slug, $enabledAnalyses))
                            <button class="form-control analysis-btn" data-analysis="{{$analysisDefinition->slug}}">
                                @lang('samples_list.analyses_types.' . $analysisDefinition->slug)

                                @if ($enabledAnalysesWithStatus[$analysisDefinition->slug] == \App\Analysis::STATUS_COMPLETE)
                                    ✔
                                @elseif ($enabledAnalysesWithStatus[$analysisDefinition->slug] == \App\Analysis::STATUS_IN_PROGRESS)
                                    ...
                                @endif
                            </button>
                            @else
                            <button class="form-control analysis-btn disabled" disabled="">@lang('samples_list.analyses_types.' . $analysisDefinition->slug)</button>
                            @endif
						</div>
                        @endforeach
					</div>

                    <div class="row">
						<div class="col-md-offset-9 col-md-3 text-right">
                            <button type="button" class="btn btn-info" id="set_enabled_analyses">@lang('samples_list.set_enabled_analyses')</button>
						</div>
					</div>

                    <hr />

                    <table id="diaper_weights" class="table table-striped table-bordered" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>Average weight, g</th>
                                <th>MIN weight, g</th>
								<th>MAX weight, g</th>
                                <th>Standard deviation</th>
                                <th>∆, g</th>
                                <th>Count</th>
							</tr>
						</thead>
					</table>

                    <hr />

					<div class="row">
                        <div class="col-md-3 text-left">
							<button type="button" class="btn btn-danger" id="delete_samples">@lang('samples_list.btn.delete_selected')</button>
						</div>
                        <div class="col-md-offset-4 col-md-2">
							<input class="form-control" type="text" name="weight" id="weight" />
                        </div>
						<div class="col-md-3">
							<button type="button" class="form-control" id="add_sample">@lang('samples_list.create')</button>
						</div>
					</div>


					<table id="samples" class="table table-striped table-bordered" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="40px"></th>
								<th>@lang('samples_list.analyses')</th>
                                <th>@lang('samples_list.assigned_analyses_completed')</th>
								<th>@lang('samples_list.weight')</th>
							</tr>
						</thead>
					</table>


                    <div class="row">
						<div class="col-md-offset-9 col-md-3 text-right">

                            <button type="button" class="btn btn-info" id="automatic_analyses_assignment"
                                {{$samplesPackage->samples_assigned ? 'disabled="disabled"': ''}}
                            >@lang('samples_list.automatic_analyses_assignment')</button>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>

    <div id="assignmentsModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('samples_list.confirm_samples_assignment')</h4>
                </div>
                <div class="modal-body" id="assignmentsValues">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('samples_list.btn.cancel')</button>
                    <button type="button" class="btn btn-primary" id="assignData">@lang('samples_list.btn.assign')</button>
                </div>
            </div>
        </div>
    </div>

    <div id="enabledAnalysesModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('samples_list.select_analyses')</h4>
                </div>
                <div class="modal-body" id="assignmentsValues">
                    @foreach ($analysisDefinitions as $analysisDefinition)
                        <input type="checkbox" class="js-enabled-analysis" name="enabled_analyses[]" value="{{ $analysisDefinition->slug }}" id="analysis_{{ $analysisDefinition->slug }}">
                        <label for="analysis_{{ $analysisDefinition->slug }}">@lang('samples_list.analyses_types.' . $analysisDefinition->slug)</label>
                        <br />
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('samples_list.btn.cancel')</button>
                    <button type="button" class="btn btn-primary" id="enableAnalyses">@lang('samples_list.btn.set_enabled_analyses')</button>
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
@endsection

@section('javascript')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Editor-1.6.5/js/dataTables.editor.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Editor-1.6.5/js/editor.bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>

   	<script>

    var editor;
    var packages_editor;

    var analyses_short_names = {
        @foreach ($analyses as $analysis)
            {{$analysis['slug']}}: '@lang('samples_list.analyses_types_short.' . $analysis['slug'])',
        @endforeach
    };

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var diaperWeightsLimitsJson = {!! $diaperWeightsLimitsJson !!};

    function addSample() {
        var samplesPackageId = {{ $samplesPackage->id }};

        var weight = $('#weight').val();

        if(weight == '') {
            alert('@lang('samples_list.errors.no_weight_entered')');
            $('#weight').select();
            return;
        }

        var route = '{{ route('samples_list.samples.add', ['samplesPackageId' => '__SAMPLES_PACKAGE_ID__']) }}';
		route = route.replace('__SAMPLES_PACKAGE_ID__', samplesPackageId);

        $.ajax({
            type: "POST",
            url: route,
            data: {
                weight: weight
            },
            success: function( data ) {
                if (data.success) {
                    $('#samples').DataTable().ajax.reload();
                    $('#diaper_weights').DataTable().ajax.reload();
                    $('#weight').select();
                } else {
                    alert(data.error);
                }
            }
        });
        return;
    }

	$(document).ready(function() {
		editor = new $.fn.dataTable.Editor( {
			ajax: "{{ route('samples_list.samples_json', ['samplesPackageId' => $samplesPackage->id]) }}",
			idSrc:  'id',
			table: "#samples",
			fields: [ {
					type:     'select',
					label:    '@lang('samples_list.analyses'):',
					name:     'analyses',
					multiple: true,
					separator: ',',
					options: [
                        { label: '@lang('samples_list.analyses_types.none')',	value: '' },
						@foreach ($analyses as $analysis)
                            @if (in_array($analysis->slug, $enabledAnalyses))
                                { label: '@lang('samples_list.analyses_types.' . $analysis['slug'])',	value: '{{$analysis['slug']}}' },
                            @endif
						@endforeach
					]
				}, {
					label: "@lang('samples_list.assigned_analyses_completed'):",
					name: "assigned_analyses_completed",
					attr: {
						type: 'text'
					}
				}, {
					label: "@lang('samples_list.weight'):",
					name: "weight",
					attr: {
						type: 'number',
						step: '0.01',
                        lang: 'en-150'
					}
				}
			]
		} );

		// Activate an inline edit on click of a table cell
		$('#samples').on( 'click', 'tbody td:not(:first-child)', function (e) {
			editor.inline( this );
		} );

		$('#samples').DataTable( {
			dom: "Bfrtip",
			ajax: "{{ route('samples_list.samples_json', ['samplesPackageId' => $samplesPackage->id]) }}",
			order: [],
			searching: false,
			paging: false,
			bInfo: false,
			columns: [
				{
					data: null,
					defaultContent: '',
					className: 'select-checkbox readonly',
					orderable: false
				},
				{
                    data: "analyses",
                    render: function ( data, type, row ) {
                        var analyses = data.split(',');
                        var shortNamesArr = [];
                        for (analysis in analyses) {
                            shortNamesArr.push(analyses_short_names[analyses[analysis]]);
                        }

                        return shortNamesArr.join(',');
                    }
                },
                { data: 'assigned_analyses_completed', sClass: "text-center" },
				{ data: "weight", sClass: "text-center" }
			],
			select: {
				style:    'multi',
				selector: 'td:first-child'
			},

			buttons: [
//				{ extend: "create", editor: editor },
//				{ extend: "edit",   editor: editor },
//				{ extend: "remove", editor: editor }
			],

            "fnRowCallback": function( nRow, aData ) {
                var i = 1;
                $.each(aData, function(field, value){
                    if(field === 'weight'){
                        if('lower_limit' in diaperWeightsLimitsJson
                            && diaperWeightsLimitsJson['lower_limit'] != ''
                            && parseFloat(diaperWeightsLimitsJson['lower_limit']) > parseFloat(value)) {

                            $("td:eq(" + i + ")", nRow).removeClass("alert-lower-limit");
                            $("td:eq(" + i + ")", nRow).addClass("below-limit");
                        } else if('alert_lower_limit' in diaperWeightsLimitsJson
                            && diaperWeightsLimitsJson['alert_lower_limit'] != ''
                            && parseFloat(diaperWeightsLimitsJson['alert_lower_limit']) > parseFloat(value)) {

                            $("td:eq(" + i + ")", nRow).removeClass("below-limit");
                            $("td:eq(" + i + ")", nRow).addClass("alert-lower-limit");
                        } else {
                            $("td:eq(" + i + ")", nRow).removeClass("below-limit");
                            $("td:eq(" + i + ")", nRow).removeClass("alert-lower-limit");
                        }

                        if('upper_limit' in diaperWeightsLimitsJson
                            && diaperWeightsLimitsJson['upper_limit'] != ''
                            && parseFloat(diaperWeightsLimitsJson['upper_limit']) < parseFloat(value)) {

                            $("td:eq(" + i + ")", nRow).removeClass("alert-upper-limit");
                            $("td:eq(" + i + ")", nRow).addClass("above-limit");
                        } else if('alert_upper_limit' in diaperWeightsLimitsJson
                            && diaperWeightsLimitsJson['alert_upper_limit'] != ''
                            && parseFloat(diaperWeightsLimitsJson['alert_upper_limit']) < parseFloat(value)) {

                            $("td:eq(" + i + ")", nRow).removeClass("above-limit");
                            $("td:eq(" + i + ")", nRow).addClass("alert-upper-limit");
                        } else {
                            $("td:eq(" + i + ")", nRow).removeClass("above-limit");
                            $("td:eq(" + i + ")", nRow).removeClass("alert-upper-limit");
                        }
                    }
                    i++;
                });
            }
		} );

		$('#add_sample').click(addSample);

        $('#weight').keypress(function (e) {
            if (e.which == 13) {
                addSample();
                return false;
            }
        });

        editor.on('initEdit', function() {
			editor.show();
			editor.disable('assigned_analyses_completed');
		});

        editor.on('postEdit', function() {
			$('#diaper_weights').DataTable().ajax.reload();
		});

        var packages = [{
            "id": 1,
            "manifacturing_time": "15:10",
            "samples_count": 10
        }];

        var packages_count = 1;

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
			ajax: "{{ route('samples_list.package.json', ['samplesPackageId' => $samplesPackage->id]) }}",
			order: [],
			searching: false,
			paging: false,
			bInfo: false,
			columns: [
				{ data: "manifacturing_time", sClass: "text-center" },
                { data: "samples_count", sClass: "text-center" },
                { data: "comment", sClass: "text-center" }
			],
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

		$('.analysis-btn').click(function (){
			var analysis = $(this).data('analysis');
			var ids = $.map($('#samples').DataTable().rows('.selected').data(), function (item) {
				return item.id;
			});

            var analysisUrl = "{{ route('samples_list.do_analysis', ['samplesPackageId' => $samplesPackage->id, 'samplesIds'=> '__IDS__', 'analysys'=> '__ANALYSIS__']) }}";


			analysisUrl = analysisUrl.replace('__ANALYSIS__', analysis);

            if(ids.length == 0) {

                $.ajax({
                    type: "GET",
                    url: "{{ route('samples_list.get_analysis_samples_count', ['samplesPackageId' => $samplesPackage->id]) }}",
                    data: {
                        analysis: $(this).data('analysis')
                    },
                    success: function( msg ) {
                        if(msg.count == 0) {
                            alert('@lang('samples_list.errors.no_samples_selected')');
                        } else {
                            analysisUrl = analysisUrl.replace('__IDS__', 'all');
                            window.location.href = analysisUrl;
                        }
                    }
                });
                return;
            }

            analysisUrl = analysisUrl.replace('__IDS__', ids.join(','));
            window.location.href = analysisUrl;
		});


        $('#diaper_weights').DataTable( {
			dom: "Bfrtip",
			ajax: "{{ route('samples_list.diaper_weights.json', ['samplesPackageId' => $samplesPackage->id]) }}",
			order: [],
			searching: false,
			paging: false,
			bInfo: false,
			columns: [
				{ data: "average", sClass: "text-center" },
                { data: "min", sClass: "text-center" },
                { data: "max", sClass: "text-center" },
                { data: "standard_deviation", sClass: "text-center" },
                { data: "delta", sClass: "text-center" },
                { data: "count", sClass: "text-center" },
			],
			select: {
				style:    'multi',
				selector: 'td:first-child'
			},
            buttons: [
//				{ extend: "create", editor: editor },
//				{ extend: "edit",   editor: editor },
//				{ extend: "remove", editor: editor }
			]
		});

        $('.average-values-btn').click(function (){
			var analysis = $(this).data('analysis');

            var analysisUrl = "{{ route('samples_list.average_values', ['samplesPackageId' => $samplesPackage->id, 'analysys'=> '__ANALYSIS__']) }}";

			analysisUrl = analysisUrl.replace('__ANALYSIS__', analysis);

            $.ajax({
                type: "GET",
                url: "{{ route('samples_list.get_analysis_samples_count', ['samplesPackageId' => $samplesPackage->id]) }}",
                data: {
                    analysis: $(this).data('analysis')
                },
                success: function( msg ) {
                    if(msg.count == 0) {
                        alert('@lang('samples_list.errors.no_analyses_done')');
                    } else {
                        analysisUrl = analysisUrl.replace('__IDS__', 'all');
                        window.location.href = analysisUrl;
                    }
                }
            });
		});
	} );


	$(function () {
		$('.date').datetimepicker({
			allowInputToggle: true,
			format: "DD/MM/YYYY"
		});

		$('.time').datetimepicker({
			allowInputToggle: true,
			format: "HH:mm"
		});

        $('#delete_samples').click(function() {
			var ids = $.map($('#samples').DataTable().rows('.selected').data(), function (item) {
				return item.id;
			});

            if(ids.length > 0) {

                if (confirm("{{ __('samples_list.confirm_samples_delete') }}")) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('samples_list.analysis_samples_delete', ['samplesPackageId' => $samplesPackage->id]) }}",
                        data: {
                            ids: ids
                        },
                        success: function( data ) {
                            if(data.success) {
                                $('#samples').DataTable().ajax.reload();
                                $('#diaper_weights').DataTable().ajax.reload();
                            } else {
                                alert('Error deleting!');
                            }
                        }
                    });
                }
                return;
            } else {
                alert('@lang('samples_list.errors.no_samples_selected')');
            }

            analysisUrl = analysisUrl.replace('__IDS__', ids.join(','));
            window.location.href = analysisUrl;
        });


        var assignmentsData = null;
        $('#automatic_analyses_assignment').click(function() {
			var ids = $.map($('#samples').DataTable().rows('').data(), function (item) {
				return item.id;
			});

            $('#assignmentsValues').html('');
            assignmentsData = null;

            $.ajax({
                type: "GET",
                url: "{{ route('samples_list.assignments.json', ['samplesPackageId' => $samplesPackage->id]) }}",
                success: function( data ) {
                    if(data.success) {
                        assignmentsData = data.data;
                        var confirmationStr = "";
                        Object.keys(assignmentsData).forEach(function(key, index) {
                            var analysisStr = "<strong>" + data.analyses_names[key] + ":</strong> ";
                            var samplesWeights = [];

                            for (var i=0; i < assignmentsData[key].length; i++) {
                                samplesWeights.push(assignmentsData[key][i].weight);
                            }

                            analysisStr += samplesWeights.join(', ') + "<br />";
                            confirmationStr += analysisStr;
                        });

                        $('#assignmentsValues').html(confirmationStr);
                        $('#assignmentsModal').modal('show');
                    } else {
                        alert(data.error);
                    }
                }
            });
        });

        $('#assignData').click(function() {
            $.ajax({
                type: "POST",
                url: "{{ route('samples_list.assignments.json_post', ['samplesPackageId' => $samplesPackage->id]) }}",
                data: {
                    data: assignmentsData
                },
                success: function( data ) {
                    if(data.success) {
                        $('#assignmentsModal').modal('hide');
                        $('#samples').DataTable().ajax.reload();
                        $('#automatic_analyses_assignment').prop('disabled', true);
                    } else {
                        alert(data.error);
                    }
                }
            });
        });

        $('#set_enabled_analyses').click(function() {
			var ids = $.map($('#samples').DataTable().rows('').data(), function (item) {
				return item.id;
			});

            $('#assignmentsValues').html('');
            assignmentsData = null;

            $.ajax({
                type: "GET",
                url: "{{ route('samples_list.enabled_analyses.json', ['samplesPackageId' => $samplesPackage->id]) }}",
                success: function( data ) {
                    if(data.success) {
                        console.log(data.data);

                        $('.js-enabled-analysis').prop('checked', false);

                        for(index in data.data) {
                            $('#analysis_' + data.data[index]).prop('checked', true);
                        }

                        $('#enabledAnalysesModal').modal('show');

                    } else {
                        alert(data.error);
                    }
                }
            });
        });

        $('#enableAnalyses').click(function() {

            var enabledAnalyses = [];

            $('.js-enabled-analysis:checked').each(function (index){
                enabledAnalyses.push($(this).val());
            });

            $.ajax({
                type: "POST",
                url: "{{ route('samples_list.enabled_analyses.json_post', ['samplesPackageId' => $samplesPackage->id]) }}",
                data: {
                    enabled_analyses: enabledAnalyses
                },
                success: function( data ) {
                    if(data.success) {
                        window.location.reload(true);
                    } else {
                        alert(data.error);
                    }
                }
            });
        });
	});
    </script>

@endsection
