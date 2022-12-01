@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('samples_list.average_values'):
                    @lang('samples_list.analyses_types.' . $analysis)

                    <a style="position: absolute; right: 25px; top: 6px;" href="{{route('samples_list.package.index', ['id' => $samplesPackage->id])}}" class="btn btn-sm btn-success">@lang('samples_list.back_to_package')</a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @php ($sampleId = 'average_values')
                    <div role="tabpanel" class="tab-pane" id="sample_{{$sampleId}}">

                        @include('samples-list.partials.fields_group', [
                            'name' => '',
                            'parentName' => null,
                            'attributes' => $analysisDefinition->getDefinitionArray()
                        ])
                    </div>

                </div>

                <div class="panel-footer">
                    @lang('samples_list.average_values'):
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
    <script src="//cdn.quilljs.com/1.3.4/quill.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var analysisLimits = {!! $analysisLimitsJson !!};
    </script>


    @php ($sampleId = 'average_values')
    @php ($isAverageValues = true)
    @include('samples-list.partials.fields_tables', [
        'parentName' => null,
        'attributes' => $analysisDefinition->getDefinitionArray()
    ])
@endsection
