@php ($tablesTabIndex = 1)
@foreach( $attributes as $attribute )
	@php ($fullAttributeName = $parentName ? ($parentName . '__' . $attribute['name']) : $attribute['name'])
	@if ($attribute['type'] == 'group')
		@if ($attribute['group_fields'][0]['type'] == 'group')
			@include('samples-list.partials.fields_tables', [
				'parentName' => $fullAttributeName,
				'attributes' => $attribute['group_fields'],
				'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
			])
		@else
			@include('samples-list.partials.fields_table', [
				'tablesTabIndex' => $tablesTabIndex,
				'parentName' => $fullAttributeName,
				'attributes' => $attribute['group_fields'],
				'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
			])
			@php ($tablesTabIndex++)
		@endif
	@else
		@include('samples-list.partials.fields_tables', [
			'parentName' => $fullAttributeName,
			'attributes' => $attribute['group_fields'],
			'analysesAttributesBySampleId' => $analysesAttributesBySampleId,
		])
	@endif
@endforeach

