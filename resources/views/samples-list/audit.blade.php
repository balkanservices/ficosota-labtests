@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('products.audit.title')

                    <a style="position: absolute; right: 25px; top: 6px;" href="{{route('samples_lists.edit', ['id' => $samplesList->id])}}" class="btn btn-sm btn-success">@lang('samples_list.back_to_samples_list')</a>
                </div>

                <div class="panel-body">
                    @foreach ($auditLog as $diff)
                        @foreach($diff->getModified() as $field => $value)
                            @if(!in_array($field, ['id', 'samples_list_id']))
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>{{ $diff->user->name }}</strong> {{ \Carbon\Carbon::parse($diff->created_at)->diffForHumans() }}
                                </div>
                                <div class="col-md-3">
                                    @php ($additionalLabelComponent = '')

                                    @if($diff->auditable_type == 'App\SamplesPackage')
                                        @lang('samples_list.header.package')
                                        @php ($package = App\SamplesPackage::find($diff->auditable_id))
                                        {{ $package ? $package->manifacturing_time . ":" : "" }}
                                        @php ($additionalLabelComponent = 'package.')
                                    @endif

                                    @if($field == 'qa_journal_id')
                                        @lang( 'qa_journals.qa_journals' )
                                    @else
                                        @lang( 'samples_list.' . $field )
                                   @endif
                                </div>
                                <div class="col-md-3" style="background: #ffe9e9" class="deleted-line">
                                    @if($field == 'qa_journal_id')
                                        @if(isset($value["old"]))
                                            @php ($qaJournal = App\QaJournal::find($value["old"]))
                                            {{ $qaJournal ? $qaJournal->getName() : "" }}
                                        @endif
                                    @else
                                        {{ isset($value["old"]) ? $value["old"]: "" }}
                                    @endif
                                </div>
                                <div class="col-md-3" style="background: #e9ffe9" class="added-line">
                                    @if($field == 'qa_journal_id')
                                        @if(isset($value["new"]))
                                            @php ($qaJournal = App\QaJournal::find($value["new"]))
                                            {{ $qaJournal ? $qaJournal->getName() : "" }}
                                        @endif
                                    @else
                                        {{ isset($value["new"]) ? $value["new"]: "" }}
                                    @endif
                                </div>
                            </div>
                            <hr />
                            @endif
                        @endforeach
                    @endforeach
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

    var editor;
    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
</script>
@endsection
