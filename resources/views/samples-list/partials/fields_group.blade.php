@if ($name !== '' )
<div class='row'>
	<div class='col-xs-12 text-center'>
	@if ($attributes[0]['type'] == 'group')
		<h4>@lang('samples_list.analysis_fields.' . $analysis. '.' . $name)</h4>
	@else
		<h5>@lang('samples_list.analysis_fields.' . $analysis. '.' . $name)</h5>
	@endif
	</div>
</div>
@endif

@php ($tableStarted = false)
@php ($hasGroupedTableHeader = false)
@php ($attributesWithGroupedHeader = [])

@if (isset($groupedHeaders) )
@php ($tableStarted = true)
@php ($hasGroupedTableHeader = true)
@php ($shownGroupedHeaders = [])

	<table id="{{$parentName}}_table_sample_{{$sampleId}}" class="table table-striped table-bordered analysis-table" width="100%" cellspacing="0">
	<thead><tr>
@foreach( $attributes as $attribute )
@php ($groupedHeader = GroupedHeaderHelper::getAttributeGroupedHeader($attribute, $groupedHeaders))
	@if($groupedHeader)
		@if(!in_array($groupedHeader['name'], $shownGroupedHeaders))
@php ($shownGroupedHeaders[] = $groupedHeader['name'])
@php ($fullAttributeName = $parentName ? ($parentName . '__' . $groupedHeader['name']) : $groupedHeader['name'])
@php ($colspan = count($groupedHeader['fields']))
	<th colspan="{{$colspan}}" class='text-center'>@lang('samples_list.analysis_fields.' . $analysis. '.' . $fullAttributeName)</th>
		@endif
@php ($attributesWithGroupedHeader[] = $attribute)
	@else
@php ($fullAttributeName = $parentName ? ($parentName . '__' . $attribute['name']) : $attribute['name'])
	<th rowspan="2">@lang('samples_list.analysis_fields.' . $analysis. '.' . $fullAttributeName)</th>
	@endif
@endforeach
@if(!empty($attributesWithGroupedHeader)) </tr><tr> @endif
@php ($attributes = $attributesWithGroupedHeader)
@endif


@foreach( $attributes as $attribute )
	@php ($fullAttributeName = $parentName ? ($parentName . '__' . $attribute['name']) : $attribute['name'])

	@if ($attribute['type'] == 'group')
		@include('samples-list.partials.fields_group', [
			'name' => $fullAttributeName,
			'parentName' => $fullAttributeName,
			'attributes' => $attribute['group_fields'],
			'groupedHeaders' => isset($attribute['grouped_headers']) ? $attribute['grouped_headers'] : null
		])
	@elseif ($attribute['type'] == 'tab_group')
		@if ($attribute['label_bg'] !== '' )
		<div class='row'>
			<div class='col-xs-12 text-center'>
				<h4>@lang('samples_list.analysis_fields.' . $analysis. '.' . $attribute['name'])</h4>
			</div>
		</div>
		@endif

		<ul class="nav nav-tabs" role="tablist">
			@foreach( $attribute['group_fields'] as $groupField )
			<li role="presentation" class="{{$loop->first ? 'active' : ''}}"><a href="#{{$fullAttributeName}}_{{$groupField['name']}}_{{$sampleId}}" aria-controls="{{$fullAttributeName}}_{{$groupField['name']}}_{{$sampleId}}" role="tab" data-toggle="tab">@lang('samples_list.analysis_fields.' . $analysis. '.' . $fullAttributeName . '__' . $groupField['name'])</a></li>
			@endforeach
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			@foreach( $attribute['group_fields'] as $groupField )
			<div role="tabpanel" class="tab-pane {{$loop->first ? 'active' : ''}}" id="{{$fullAttributeName}}_{{$groupField['name']}}_{{$sampleId}}">
				@include('samples-list.partials.fields_group', [
					'name' => '',
					'parentName' => $fullAttributeName . '__' . $groupField['name'],
					'attributes' => $groupField['group_fields'],
					'groupedHeaders' => isset($groupField['grouped_headers']) ? $groupField['grouped_headers'] : null
				])
			</div>
			@endforeach
		</div>
	@else
		@if (!$tableStarted)
			@php ($tableStarted = true)
	<table id="{{$parentName}}_table_sample_{{$sampleId}}" class="table table-striped table-bordered analysis-table" width="100%" cellspacing="0">
		<thead><tr>
		@endif
			<th>@lang('samples_list.analysis_fields.' . $analysis. '.' . $fullAttributeName)</th>
	@endif
@endforeach

@if ($tableStarted)
		</tr></thead>
	</table>
@endif