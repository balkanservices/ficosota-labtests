@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('users.users')</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-hover">
                        <thead>
                        <th class='col-sm-1 text-left'>#</th>
                        <th class='col-sm-4 text-left'>@lang('users.header.name')</th>
                        <th class='col-sm-4 text-left'>@lang('users.header.email')</th>
                        <th class='col-sm-4 text-left'>@lang('users.header.role')</th>
                        <th class='col-sm-3 text-center'>@lang('users.header.action')</th>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td class='text-left'>{{$user->name}}</td>
                                <td class='text-left'>{{$user->email}}</td>
                                <td class='text-left'>{{$user->getRolesDescriptionsStr()}}</td>
                                <td class='text-center'>
                                    <a href='{{route('users.edit', ['id' => $user->id])}}' class='btn btn-info btn-sm'>@lang('users.btn.edit')</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class='row'>
                        <div class='col-sm-12 text-right'>
                            {{ $users->links() }}
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
