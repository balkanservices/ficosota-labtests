@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('products.products')</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class='row'>
                        <div class='col-sm-12 text-right'>
                            <a href='{{route('products.new')}}' class='btn btn-success btn-sm'>@lang('products.btn.new')</a>
                            <a href='{{route('products.file_upload')}}' class='btn btn-success btn-sm'>@lang('products.btn.file_upload')</a>
                        </div>
                    </div>
                    
                    <table class="table table-hover">
                        <thead>
                        <th class='col-sm-1 text-left'>#</th>
                        <th class='col-sm-4 text-left'>@lang('products.header.name')</th>
                        <th class='col-sm-3 text-center'>@lang('products.header.action')</th>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr>
                                <td>{{$product->id}}</td>
                                <td class='text-left'>{{$product->getName()}}</td>
                                <td class='text-center'>
                                    <a href='{{route('products.edit', ['id' => $product->id])}}' class='btn btn-info btn-sm'>@lang('products.btn.edit')</a>
                                    <a href='{{route('recipes.new', ['productId' => $product->id])}}' class='btn btn-success btn-sm'>@lang('products.btn.new_recipe')</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class='row'>
                        <div class='col-sm-12 text-right'>
                            {{ $products->links() }}
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
	
	
    
    </script>
@endsection
