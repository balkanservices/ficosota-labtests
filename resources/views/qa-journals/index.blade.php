@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('qa_journals.qa_journals')</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class='row'>
                        <div class='col-sm-12 text-right'>
                            <a href='{{route('qa_journals.new')}}' class='btn btn-success btn-sm'>@lang('qa_journals.btn.new')</a>
                        </div>
                    </div>

					<table class="table table-hover">
                        <thead>
                        <th class='col-sm-1 text-left'>#</th>
                        <th class='col-sm-4 text-left'>@lang('qa_journals.header.recipe')</th>
                        <th class='col-sm-4 text-left'>@lang('qa_journals.header.batch_number')</th>
                        <th class='col-sm-3 text-center'>@lang('qa_journals.header.action')</th>
                        </thead>
                        <tbody>
                            @foreach ($qaJournals as $qaJournal)
                            <tr>
                                <td>{{$qaJournal->id}}</td>
                                <td class='text-left'>{{$qaJournal->recipe ? $qaJournal->recipe->getName() : ''}}</td>
                                <td class='text-left'>{{$qaJournal->batch_number ? '#'.$qaJournal->batch_number : ''}} {{$qaJournal->batch_date ? '/ ' . $qaJournal->batch_date : ''}}</td>
                                <td class='text-center'>
                                    <a href='{{route('qa_journals.edit', ['id' => $qaJournal->id])}}' class='btn btn-info btn-sm'>@lang('qa_journals.btn.edit')</a>
                                    <a href='{{route('samples_lists.new', ['qaJournalId' => $qaJournal->id])}}' class='btn btn-success btn-sm'>@lang('qa_journals.btn.new_samples_list')</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class='row'>
                        <div class='col-sm-12 text-right'>
                            {{ $qaJournals->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
