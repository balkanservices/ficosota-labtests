@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('qa_journals.title')</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
					
					{!! Form::open(['route' => ['qa_journals.new'], 'method' => 'get']) !!}
                    
                    <div class="row">
						<div class="col-md-3">
							{{ Form::label('recipeId', __('qa_journals.choose_recipe'), ['class' => 'field-label']) }}
						</div>
                        
                        <div class="col-md-3">
							{{ Form::select('recipeId', $recipes, null, ['class' => 'form-control']) }}
						</div>
                    </div>
                    
                    <hr />
                    <div class="row">
                        <div class="col-md-12 text-right">
                        {{ Form::submit(__('qa_journals.btn.save'), ['class' => 'btn btn-success', 'name' => 'save']) }}
                        </div>
                    </div>
					{!! Form::close() !!}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
