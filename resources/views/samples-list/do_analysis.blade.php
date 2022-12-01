@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					@lang('samples_list.analyses_types.' . $analysis)

					( {{$samplesPackage->samples_list->getPackageName()}} )

					<a style="position: absolute; right: 250px; top: 6px;" href="{{route('samples_lists.audit_analysis', ['$samplesPackageId' => $samplesPackage->id, 'analysis' => $analysis])}}" class="btn btn-sm btn-link">@lang('samples_list.btn.audit')</a>

					<a style="position: absolute; right: 25px; top: 6px;" href="{{route('samples_list.package.index', ['id' => $samplesPackage->id])}}" class="btn btn-sm btn-success">@lang('samples_list.back_to_package')</a>
				</div>

				<div class="panel-body">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif

					@php ($selectSampleId = null)

					@if (count($samplesIdsArr) == 1)
						@php ($selectSampleId = $samplesIdsArr[0])
					@endif

					@if($analysis != 'color_stability')
					<ul class="nav nav-tabs main-tabs" role="tablist">
						@foreach( $sampleAnalyses as $sampleAnalysis )
							@php ($sampleId = $sampleAnalysis->sample->id)

							@if($selectSampleId == null)
								@php ($active = $loop->first ? 'active' : '')
							@else
								@php ($active = $sampleId == $selectSampleId ? 'active' : '')
							@endif
						<li role="presentation" class="{{$active}} {{ $sampleAnalysis->getMannequinPositionCssClass()}}"><a href="#sample_{{$sampleId}}" aria-controls="sample_{{$sampleId}}" data-sample-id="{{$sampleId}}" role="tab" data-toggle="tab">Weight: {{$sampleAnalysis->sample->weight}}</a></li>
						@endforeach

						@if ($analysisDefinition->slug == 'absorbtion_before_leakage')
							@php ($active = 'average_values_belly_back' == $selectSampleId ? 'active' : '')
							<li role="presentation" class="{{$active}} abl_belly_back"><a href="#sample_average_values_belly_back" aria-controls="sample_average_values_belly_back" data-sample-id="average_values_belly_back" role="tab" data-toggle="tab">@lang('samples_list.average_values_belly_back')</a></li>
							@php ($active = 'average_values_sideways' == $selectSampleId ? 'active' : '')
							<li role="presentation" class="{{$active}} abl_sideways"><a href="#sample_average_values_sideways" aria-controls="sample_average_values_sideways" data-sample-id="average_values_sideways" role="tab" data-toggle="tab">@lang('samples_list.average_values_sideways')</a></li>
							@php ($active = 'average_values_standing' == $selectSampleId ? 'active' : '')
							<li role="presentation" class="{{$active}} abl_standing"><a href="#sample_average_values_standing" aria-controls="sample_average_values_standing" data-sample-id="average_values_standing" role="tab" data-toggle="tab">@lang('samples_list.average_values_standing')</a></li>
						@endif
						
						@php ($active = 'average_values' == $selectSampleId ? 'active' : '')
						<li role="presentation" class="{{$active}}"><a href="#sample_average_values" aria-controls="sample_average_values" data-sample-id="average_values" role="tab" data-toggle="tab">@lang('samples_list.average_values')</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						@foreach( $sampleAnalyses as $sampleAnalysis )
							@php ($sampleId = $sampleAnalysis->sample->id)
							@if($selectSampleId == null)
								@php ($active = $loop->first ? 'active' : '')
							@else
								@php ($active = $sampleId == $selectSampleId ? 'active' : '')
							@endif
						<div role="tabpanel" class="tab-pane {{$active}}" id="sample_{{$sampleId}}">

							@include('samples-list.partials.fields_group', [
								'name' => '',
								'parentName' => null,
								'attributes' => $analysisDefinition->getDefinitionArray()
							])
						</div>
						@endforeach

						@if ($analysisDefinition->slug == 'absorbtion_before_leakage')
							@php ($sampleId = 'average_values_belly_back')
							@php ($active = $sampleId == $selectSampleId ? 'active' : '')
							<div role="tabpanel" class="tab-pane {{$active}}" id="sample_average_values_belly_back">
								@include('samples-list.partials.fields_group', [
									'name' => '',
									'parentName' => null,
									'attributes' => $analysisDefinition->getDefinitionArray()
								])
							</div>

							@php ($sampleId = 'average_values_sideways')
							@php ($active = $sampleId == $selectSampleId ? 'active' : '')
							<div role="tabpanel" class="tab-pane {{$active}}" id="sample_average_values_sideways">
								@include('samples-list.partials.fields_group', [
									'name' => '',
									'parentName' => null,
									'attributes' => $analysisDefinition->getDefinitionArray()
								])
							</div>

							@php ($sampleId = 'average_values_standing')
							@php ($active = $sampleId == $selectSampleId ? 'active' : '')
							<div role="tabpanel" class="tab-pane {{$active}}" id="sample_average_values_standing">
								@include('samples-list.partials.fields_group', [
									'name' => '',
									'parentName' => null,
									'attributes' => $analysisDefinition->getDefinitionArray()
								])
							</div>
						@endif

						@php ($sampleId = 'average_values')
						@php ($active = $sampleId == $selectSampleId ? 'active' : '')
						<div role="tabpanel" class="tab-pane {{$active}}" id="sample_average_values">
							@include('samples-list.partials.fields_group', [
								'name' => '',
								'parentName' => null,
								'attributes' => $analysisDefinition->getDefinitionArray()
							])
						</div>
					</div>
					@else
						@php ($sampleId = 'color_stability__' . implode('_', $samplesIdsArr))
						<div role="tabpanel" class="tab-pane" id="sample_{{$sampleId}}">

							@include('samples-list.partials.fields_group', [
								'name' => '',
								'parentName' => null,
								'attributes' => $analysisDefinition->getDefinitionArray()
							])
						</div>
					@endif


				</div>
				<div class="panel-footer">
					@lang('samples_list.analyses_types.' . $analysis)

					<a style="position: absolute; right: 25px; bottom: 25px;" href="{{route('samples_list.package.index', ['id' => $samplesPackage->id])}}" class="btn btn-sm btn-success">@lang('samples_list.back_to_package')</a>
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
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		var tableLoaders = {};
		
		@php ($activeSampleId = null)
		@foreach( $sampleAnalyses as $sampleAnalysis )
			@if($selectSampleId == null)
				@if ($loop->first) 
					@php ($activeSampleId = $sampleAnalysis->sample_id)
				@endif
			@else
				@php ($activeSampleId = $selectSampleId)
			@endif

			tableLoaders['{{$sampleAnalysis->sample_id}}'] = [];
			tableLoaders['{{$sampleAnalysis->sample_id}}_loaded'] = false;
		@endforeach
		
		tableLoaders['average_values'] = [];
		tableLoaders['average_values_loaded'] = false;
		
		@if ($analysisDefinition->slug == 'absorbtion_before_leakage')
			tableLoaders['average_values_belly_back'] = [];
			tableLoaders['average_values_belly_back_loaded'] = false;

			tableLoaders['average_values_sideways'] = [];
			tableLoaders['average_values_sideways_loaded'] = false;

			tableLoaders['average_values_standing'] = [];
			tableLoaders['average_values_standing_loaded'] = false;
		@endif
		
		
		var analysisLimits = {!! $analysisLimitsJson !!};
   
		$(document).ready(function() {
			$('.main-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				var sampleId = $(e.target).data("sample-id")
				if (!tableLoaders[sampleId + '_loaded']) {
					$('.main-tabs').append('<li id="datatableLoadingIndicator_' + sampleId + '" role="presentation" style="padding-top: 10px;"><span class="label label-warning"  style="font-size:14px;padding:0.7em;">@lang('samples_list.loading')</span></li>');
				}
				loadTables(sampleId);
			});
			
			loadTables('{{ $activeSampleId }}');
			
			@if ($analysisDefinition->slug == 'absorbtion_before_leakage')
				loadTables('average_values_belly_back');
				loadTables('average_values_sideways');
				loadTables('average_values_standing');
			@endif
			
			$('.main-tabs').append('<li id="datatableLoadingIndicator_average_values" role="presentation" style="padding-top: 10px;"><span class="label label-warning"  style="font-size:14px;padding:0.7em;">@lang('samples_list.loading')</span></li>');
			loadTables('average_values');
		});
		
		
		function loadTables(sampleId) {
			if (!tableLoaders[sampleId + '_loaded']) {
				var tableLoadersLength = tableLoaders[sampleId].length;
				for(var i = 0; i < tableLoadersLength;i++) {
					var tableLoader = tableLoaders[sampleId].shift();
					tableLoader();
					tableLoaders[sampleId + '_loaded'] = true;
				}
			}
		}
		
		
		function rowCallback( nRow, aData, sampleId ) {
			var i = 0;
			$.each(aData, function(field, value){
				if(field in analysisLimits){
					if('lower_limit' in analysisLimits[field]
						&& analysisLimits[field]['lower_limit'] != ''
						&& parseFloat(analysisLimits[field]['lower_limit']) > parseFloat(value)) {

						$("td:eq(" + i + ")", nRow).removeClass("alert-lower-limit");
						$("td:eq(" + i + ")", nRow).addClass("below-limit");
					} else if('alert_lower_limit' in analysisLimits[field]
						&& analysisLimits[field]['alert_lower_limit'] != ''
						&& parseFloat(analysisLimits[field]['alert_lower_limit']) > parseFloat(value)) {

						$("td:eq(" + i + ")", nRow).removeClass("below-limit");
						$("td:eq(" + i + ")", nRow).addClass("alert-lower-limit");
					} else {
						$("td:eq(" + i + ")", nRow).removeClass("below-limit");
						$("td:eq(" + i + ")", nRow).removeClass("alert-lower-limit");
					}

					if('upper_limit' in analysisLimits[field]
						&& analysisLimits[field]['upper_limit'] != ''
						&& parseFloat(analysisLimits[field]['upper_limit']) < parseFloat(value)) {

						$("td:eq(" + i + ")", nRow).removeClass("alert-upper-limit");
						$("td:eq(" + i + ")", nRow).addClass("above-limit");
					} else if('alert_upper_limit' in analysisLimits[field]
						&& analysisLimits[field]['alert_upper_limit'] != ''
						&& parseFloat(analysisLimits[field]['alert_upper_limit']) < parseFloat(value)) {

						$("td:eq(" + i + ")", nRow).removeClass("above-limit");
						$("td:eq(" + i + ")", nRow).addClass("alert-upper-limit");
					} else {
						$("td:eq(" + i + ")", nRow).removeClass("above-limit");
						$("td:eq(" + i + ")", nRow).removeClass("alert-upper-limit");
					}
				}
				i++;
			});
			
			if (typeof aData.completion__analysis_complete !== "undefined" && $('#datatableLoadingIndicator_' + sampleId).is(':visible')){
				$('#datatableLoadingIndicator_' + sampleId).fadeOut("slow");
			}
		}
		
		function refreshTables(json, sampleId) {
			$.each(json.tables_to_refresh, function(index, table) {
				var fullTableId = table + "_table_sample_" + sampleId;
				$('#' + fullTableId).DataTable().ajax.reload();

				fullTableId = table + "_table_sample_average_values";
				$('#' + fullTableId).DataTable().ajax.reload();

				if ($('#' + fullTableId + '_belly_back').length) {
					$('#' + fullTableId + '_belly_back').DataTable().ajax.reload();
					$('#' + fullTableId + '_sideways').DataTable().ajax.reload();
					$('#' + fullTableId + '_standing').DataTable().ajax.reload();
				}
			});
			
			//Refresh the according table in average values
			if (typeof json.data[0] !== 'undefined') {
				$.each(json.data[0], function (key, value) {
					if (key == 'id') {
						return true;
					}

					var keyElements = key.split('__');
					keyElements.pop();
					var tableName = keyElements.join('__');

					var fullTableId = tableName + "_table_sample_average_values";

					if($("#" + fullTableId).length == 0) {
						keyElements = tableName.split('__');
						keyElements.pop();
						tableName = keyElements.join('__');
						fullTableId = tableName + "_table_sample_average_values";
					}

					$('#' + fullTableId).DataTable().ajax.reload();

					if ($('#' + fullTableId + '_belly_back').length) {
						$('#' + fullTableId + '_belly_back').DataTable().ajax.reload();
						$('#' + fullTableId + '_sideways').DataTable().ajax.reload();
						$('#' + fullTableId + '_standing').DataTable().ajax.reload();
					}
					
					return false;
				});
			}
		}
		
		function initEditor() {
			
		}
	</script>
	@php ($isAverageValues = false)
	@if($analysis != 'color_stability')
		{{-- If only one sample is selected load it first --}}
		@if($selectSampleId != null)
			@php ($sampleId = $selectSampleId)
			@include('samples-list.partials.fields_tables', [
				'parentName' => null,
				'attributes' => $analysisDefinition->getDefinitionArray(),
				'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
			])
		@endif

		@foreach( $sampleAnalyses as $sampleAnalysis )
			@php ($sampleId = $sampleAnalysis->sample_id)
			@if($sampleId != $selectSampleId)
				@include('samples-list.partials.fields_tables', [
					'parentName' => null,
					'attributes' => $analysisDefinition->getDefinitionArray(),
					'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
				])
			@endif

		@endforeach

		@if ($analysisDefinition->slug == 'absorbtion_before_leakage')
			@php ($sampleId = 'average_values_belly_back')
			@php ($isAverageValues = true)
			@include('samples-list.partials.fields_tables', [
				'parentName' => null,
				'attributes' => $analysisDefinition->getDefinitionArray(),
				'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
			])

			@php ($sampleId = 'average_values_sideways')
			@php ($isAverageValues = true)
			@include('samples-list.partials.fields_tables', [
				'parentName' => null,
				'attributes' => $analysisDefinition->getDefinitionArray(),
				'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
			])

			@php ($sampleId = 'average_values_standing')
			@php ($isAverageValues = true)
			@include('samples-list.partials.fields_tables', [
				'parentName' => null,
				'attributes' => $analysisDefinition->getDefinitionArray(),
				'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
			])
		@endif

		@php ($sampleId = 'average_values')
		@php ($isAverageValues = true)
		@include('samples-list.partials.fields_tables', [
			'parentName' => null,
			'attributes' => $analysisDefinition->getDefinitionArray(),
			'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
		])
	@else
		@php ($sampleId = 'color_stability__' . implode('_', $samplesIdsArr))
			@include('samples-list.partials.fields_tables', [
				'parentName' => null,
				'attributes' => $analysisDefinition->getDefinitionArray(),
				'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
			])
	@endif
@endsection
