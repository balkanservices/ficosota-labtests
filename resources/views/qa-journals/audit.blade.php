@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('qa_journals.audit.title')

                    <a style="position: absolute; right: 25px; top: 6px;" href="{{route('qa_journals.edit', ['id' => $qaJournal->id])}}" class="btn btn-sm btn-success">@lang('qa_journals.back_to_qa_journals')</a>
                </div>

                <div class="panel-body">
                    @foreach ($auditLog as $diff)
                        @foreach($diff->getModified() as $field => $value)
                            @if(!in_array($field, ['id', 'ingredient_id', 'qa_journal_id', 'recipe_id']))
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>{{ $diff->user->name }}</strong> {{ \Carbon\Carbon::parse($diff->created_at)->diffForHumans() }}
                                </div>
                                <div class="col-md-3">
                                    @if($field == 'option_id')
                                        @if(isset($value["new"]))
                                            @php ($option = App\RecipeIngredientOption::find($value["new"]))
                                            {{ $option ? $option->recipe_ingredient->name : "" }}
                                        @endif
                                    @else
                                        @lang( 'qa_journals.' . $field )
                                    @endif
                                </div>
                                <div class="col-md-3" style="background: #ffe9e9" class="deleted-line">
                                    @if($field == 'option_id')
                                        @if(isset($value["old"]))
                                            @php ($option = App\RecipeIngredientOption::find($value["old"]))
                                            {{ $option ? $option->name : "" }}
                                        @endif
                                    @else
                                        {{ isset($value["old"]) ? $value["old"]: "" }}
                                    @endif
                                </div>
                                <div class="col-md-3" style="background: #e9ffe9" class="added-line">
                                    @if($field == 'option_id')
                                        @if(isset($value["new"]))
                                            @php ($option = App\RecipeIngredientOption::find($value["new"]))
                                            {{ $option ? $option->name : "" }}
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
