@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('products.audit.title')

                    <a style="position: absolute; right: 25px; top: 6px;" href="{{route('products.edit', ['id' => $product->id])}}" class="btn btn-sm btn-success">@lang('products.back_to_product')</a>
                </div>

                <div class="panel-body">
                    @foreach ($auditLog as $diff)
                        @foreach($diff->getModified() as $field => $value)
                            @if(!in_array($field, ['id']))
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>{{ $diff->user->name }}</strong> {{ \Carbon\Carbon::parse($diff->created_at)->diffForHumans() }}
                                </div>
                                <div class="col-md-3">
                                    @lang( 'products.' . $field )
                                </div>
                                <div class="col-md-3" style="background: #ffe9e9" class="deleted-line">
                                    {{ isset($value["old"]) ? $value["old"]: "" }}
                                </div>
                                <div class="col-md-3" style="background: #e9ffe9" class="added-line">
                                    {{ isset($value["new"]) ? $value["new"]: "" }}
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
