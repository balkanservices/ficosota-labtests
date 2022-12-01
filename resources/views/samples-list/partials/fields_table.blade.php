@php ($tableName = $parentName . '_table_sample_' . $sampleId )
@php ($editorName = $parentName . '_editor_' . $sampleId )
@php ($analysisDefinition = $analysisDefinition->getAttributesPropertiesFlat() )
<script>
@if (strpos($sampleId, 'color_stability__') !== 0)
	var func = function() {
	setTimeout(function(){
@else
	$(document).ready(function() {
@endif

@if (!in_array($sampleId, ['average_values', 'average_values_belly_back', 'average_values_sideways', 'average_values_standing']))
var {{$editorName}} = new $.fn.dataTable.Editor({
	ajax: {
		url: "{{ route('samples_list.analyses_json_post', ['analysis' => $analysis, 'samplesIds' => $sampleId, 'table' => $parentName]) }}"
	},
	idSrc:  'id',
	table: "#{{$tableName}}",
	fields: [
	@foreach($attributes as $attribute)
	@php ($fullAttributeName = $parentName ? ($parentName . '__' . $attribute['name']) : $attribute['name'])
	{
		label: "@lang('samples_list.analysis_fields.' . $analysis . '.' . $attribute['name']):",
		name: "{{$fullAttributeName}}",
		@if($attribute['properties']['type'] == 'textarea') type: "textarea", @endif

		@if($attribute['properties']['type'] == 'select')
			type: "select",
			@if(isset($attribute['options']))
				options: [ @foreach ($attribute['options'] as $value => $label) { label: '{{Lang::trans($label)}}',	value: '{{$value}}' }, @endforeach ],
			@endif
		@endif

		@if($attribute['properties']['type'] == 'checkbox')
			type: "checkbox",
			@if(isset($attribute['options']))
				unselectedValue: '',
				options: [ @foreach ($attribute['options'] as $value => $label) { label: '{{Lang::trans($label)}}',	value: '{{$value}}'}, @endforeach ],
			@endif
		@endif

		@if(isset($attribute['multiple']))
			multiple: {{ $attribute['multiple'] ? 'true' : 'false' }},
		@endif

		attr: {!! str_replace('"type":"number"', '"type":"text"', json_encode($attribute['properties'])) !!}
	} @if(!$loop->last) , @endif
	@endforeach
	]
});

{{$editorName}}.on('submitSuccess', function ( e, json ) {
	refreshTables(json, "{{ $sampleId }}");
});

$('#{{$tableName}}').on('click', 'tbody td:not(:first-child)', function (e) {
	{{$editorName}}.inline(this);
});
@endif

	$('#{{$tableName}}').DataTable({
		dom: "Bfrtip",
		ajax: { url: "{{ route('samples_list.analyses_json', ['analysis' => $analysis, 'samplesPackageId' => $samplesPackage->id, 'samplesIds' => $sampleId, 'table' => $parentName]) }}" },
@if (strpos($sampleId, 'color_stability__') !== 0)
		serverSide: true,
		deferLoading: 1,
		deferRender: true,
		data: [{
	@foreach($attributes as $attribute)
	@php ($fullAttributeName = $parentName ? ($parentName . '__' . $attribute['name']) : $attribute['name'])
			{{ $fullAttributeName }}: @json($analysesAttributesBySampleId[$sampleId][$fullAttributeName]),
	@endforeach
			'id': @json( $analysesAttributesBySampleId[$sampleId]['id'] )
		}],
@endif
		order: [],
		searching: false,
		paging: false,
		bInfo: false,
		ordering: false,
		language: {
			decimal: "."
		},
		columns: [
	@foreach($attributes as $attribute)
	@php ($fullAttributeName = $parentName ? ($parentName . '__' . $attribute['name']) : $attribute['name'])
		{
			data: "{{$fullAttributeName}}",

			@if($attribute['properties']['type'] == 'select' && isset($attribute['options']) && !$isAverageValues)
				render: function ( data, type, row ) {
					@foreach ($attribute['options'] as $value => $label) @php ($attribute['options'][$value] = Lang::trans($label) ) @endforeach
					var optionTypes = {!! json_encode($attribute['options']) !!};

					if(data == null || data == '') {
						return '';
					}

					return optionTypes[data];
				},
			@endif

			@if($attribute['type'] == 'checkbox' && !$isAverageValues)
				render: function ( data, type, row ) {
					@foreach ($attribute['labels'] as $value => $label) @php ($attribute['labels'][$value] = Lang::trans($label) ) @endforeach
					var optionTypes = {!! json_encode($attribute['labels']) !!};

					if(data == null || data == '') {
						return '';
					}

					return optionTypes[data];
				},
			@endif
			sClass: "text-center"
		} @if(!$loop->last) , @endif
	@endforeach
		],
		@if (!in_array($sampleId, ['average_values', 'average_values_belly_back', 'average_values_sideways', 'average_values_standing']))
		keys: {
			keys: [ 9, 13 ],
			editor: {{$editorName}},
			editOnFocus: true,
			focus: ':eq(0)'
		},
		@endif
		"fnRowCallback": function( nRow, aData ) {
			rowCallback( nRow, aData, "{{ $sampleId }}" );
		},
		buttons: []
	});

	@if (!in_array($sampleId, ['average_values', 'average_values_belly_back', 'average_values_sideways', 'average_values_standing']))
	{{$editorName}}.on('initEdit', function() {
		{{$editorName}}.show();

		@foreach($attributes as $attribute)
			@if ($attribute['type'] === 'formula' || !empty($attribute['properties']['readonly']) || $isAverageValues)
				@php ($fullAttributeName = $parentName ? ($parentName . '__' . $attribute['name']) : $attribute['name'])
				{{$editorName}}.disable('{{$fullAttributeName}}');
			@endif
		@endforeach

	});
	@endif
	
@if (strpos($sampleId, 'color_stability__') !== 0)
	},0);
	};
	tableLoaders['{{ $sampleId }}'].push(func);
@else
	});
@endif
</script>