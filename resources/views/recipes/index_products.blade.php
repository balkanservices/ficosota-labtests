@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('recipes.recipes')</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class='row'>
                        <div class='col-sm-6 text-left'>
                            <a href='{{route('recipes.index', ['concept' => $concept, 'machine' => $machine])}}' class='btn {{ $active ? 'btn-primary' : 'btn-default' }}  btn-sm'>@lang('recipes.btn.active_recipes')</a>
                            <a href='{{route('recipes.index_not_active', ['concept' => $concept, 'machine' => $machine])}}' class='btn {{ !$active ? 'btn-primary' : 'btn-default' }}  btn-sm'>@lang('recipes.btn.not_active_recipes')</a>
                        </div>
                        <div class='col-sm-6 text-right'>
                            <a href='{{route('recipes.new')}}' class='btn btn-success btn-sm'>@lang('recipes.btn.new')</a>
                        </div>
                    </div>

                    <table class="table table-hover">
                        <thead>
                        <th class='col-sm-5 text-left'>@lang('recipes.header.concept')</th>
                        </thead>
                        <tbody>
                            @forelse ($conceptsArr as $conceptObj)
                            <tr>
                                <td class='text-left'>
                                    @if ($active)
                                    <a href='{{route('recipes.index', ['concept' => $conceptObj->concept])}}' class=''>{{ $conceptObj->concept }}</a>
                                    @else
                                    <a href='{{route('recipes.index_not_active', ['concept' => $conceptObj->concept])}}' class=''>{{ $conceptObj->concept }}</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class='text-left'>
                                    @lang('recipes.no_records')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css_files')
	<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('javascript')
    <script>

    var editor;

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    </script>
@endsection
