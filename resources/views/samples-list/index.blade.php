@extends('layouts.app')

@section('content')
<div class="container container-wide">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('samples_list.title')</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

					<div class='row'>
                        <form>
                        <div class='col-sm-4'>
                            <input type="text" name="q" id="search_text" value="{{$searchText}}" class="form-control" />
                        </div>
                        <div class='col-sm-2'>
                            <input type="submit" value="@lang('samples_list.search')" id="search_btn" class="form-control" />
                        </div>
                       <div class='col-sm-2'>
                            <button value="@lang('samples_list.reset')" id="reset_btn" class="form-control text-center">@lang('samples_list.reset')</button>
                        </div>
                        </form>
                        <div class='col-sm-4 text-right'>
                            <a href='{{route('samples_lists.new')}}' class='btn btn-success btn-sm'>@lang('samples_list.btn.new')</a>
                        </div>

                    </div>

					<table class="table table-hover">
                        <thead>
                        <!--<th class='col-sm-1 text-left'>#</th>-->

                        @foreach ($columns as $column)
                        <th class='col-sm-1 text-center'>
                            @include('samples-list.partials.filter_menu', $column)
                        </th>
                        @endforeach

                        <th class='col-sm-1 text-center'>@lang('samples_list.header.action')</th>
                        </thead>
                        <tbody>
                            @foreach ($samplesLists as $samplesList)
                            @php ($listProduct = $samplesList->getLinkedProduct())
                            <tr style='{{!empty($samplesList->analysis_end_date) ? 'background-color:#dddddd;':'' }}'>
                                <!--<td>{{$samplesList->id}}</td>-->
                                <td class='text-center'>{{ $samplesList->sample_number }}</td>
                                <td class='text-center'>{{ $samplesList->rd_delivery_date }}</td>
                                <td class='text-center'>{{ $samplesList->priority }}</td>
                                <td class='text-center'>{{$listProduct ? $listProduct->brand : ' - '}}</td>
                                <td class='text-center'>{{$listProduct ? $listProduct->conception : ' - '}}</td>
                                <td class='text-center'>{{$listProduct ? $listProduct->size : ' - '}}</td>
                                <td class='text-center'>
                                    @if ($listProduct && $listProduct->type)
                                        @lang('products.types.' . $listProduct->type)
                                    @endif
                                </td>
                                <td class='text-center'>{{ $samplesList->market }}</td>

                                <td class='text-center'>{{ $samplesList->manifacturing_date }}</td>
                                <td class='text-center'>{{ $samplesList->batch }}</td>
                                <td class='text-center'>{{ $samplesList->manifacturer }}</td>

                                <td class='text-center'>
                                    <a href='{{route('samples_lists.edit', ['id' => $samplesList->id])}}' class='btn btn-info btn-sm'>@lang('samples_list.btn.edit')</a>
                                    @php ($firstPackage = $samplesList->packages()->first())

                                    @if ($firstPackage)
                                        <a href='{{ route('samples_list.package.index', ['packageId' => $firstPackage->id]) }}' class='btn btn-warning btn-sm'>@lang('samples_list.btn.analysis')</a>
                                    @else
                                        <a href='#' class='btn btn-warning btn-sm disabled'>@lang('samples_list.btn.analysis')</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class='row'>
                        <div class='col-sm-12 text-right'>
                            {{ $samplesLists->appends(Request::except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css_files')
@endsection

@section('javascript')

   	<script>

    var editor;

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        $('#reset_btn').click(function() {
            $('#search_text').val('');
        });
    });

    </script>

@endsection
