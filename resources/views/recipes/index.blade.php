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
                        <th class='col-sm-9 text-left'>
                            @if ($active)
                                <a href='{{route('recipes.index')}}' class=''>@lang('recipes.header.concepts')</a>
                                >
                                <a href='{{route('recipes.index', ['concept' => $concept])}}' class=''>{{ $concept }}</a>
                                >
                                {{ $machine }}
                            @else
                                <a href='{{route('recipes.index_not_active')}}' class=''>@lang('recipes.header.concepts')</a>
                                >
                                <a href='{{route('recipes.index_not_active', ['concept' => $concept])}}' class=''>{{ $concept }}</a>
                                >
                                <a href='{{route('recipes.index_not_active', ['concept' => $concept, 'machine' => $machine])}}' class=''>{{ $machine }}</a>
                                >
                                {{ $size }}
                            @endif
                        </th>
                        <th class='col-sm-3 text-center'>@lang('recipes.header.action')</th>
                        </thead>
                        <tbody>
                            @forelse ($recipes as $recipe)
                            <tr>
                                <td class='text-left'>
                                    {{ $recipe->getName() }}
                                </td>
                                <td class='text-center'>
                                    <a href='{{route('recipes.edit', ['id' => $recipe->id])}}' class='btn btn-info btn-sm'>@lang('recipes.btn.edit')</a>
                                    <a href='{{route('recipes.view', ['id' => $recipe->id])}}' class='btn btn-warning btn-sm'>@lang('recipes.btn.view')</a>
                                    @if ($active)
                                        @if ($recipe->usedMaterials->isEmpty() && $recipe->isLatestRevision())
                                            <a href='#' class='btn btn-danger btn-sm js-delete-btn' data-id="{{ $recipe->id }}"> {{ __('recipes.btn.delete') }}</button>
                                        @else
                                            <a href='#' class='btn btn-danger btn-sm' disabled> {{ __('recipes.btn.delete') }}</button>
                                        @endif
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

                    <div class='row'>
                        <div class='col-sm-12 text-right'>
                            {{ $recipes->links() }}
                        </div>
                    </div>

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

    $(document).ready(function() {
        $('.js-delete-btn').click(function() {
            if (confirm("{{ __('recipes.confirm_delete') }}")) {
                window.location.href = "{{ route('recipe.delete', ['id' => $recipe->id]) }}";
            }
            return false;
        });
	} );


    </script>
@endsection
