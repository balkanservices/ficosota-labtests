@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('recipes.audit')

                    <a style="position: absolute; right: 25px; top: 6px;" href="{{route('recipes.edit', ['id' => $recipe->id])}}" class="btn btn-sm btn-success">@lang('recipes.back_to_recipe')</a>
                </div>

                <div class="panel-body">

                    @foreach ($auditLog as $diff)
                        @foreach($diff->getModified() as $field => $value)
                            @if ( !isset($value['old']))
                                @php($value['old'] = null)
                            @endif

                            @if(!in_array($field, ['id', 'recipe_ingredient_id', 'final_version_edited', 'final_version_edited_at']))
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>{{ $diff->user->name }}</strong> <br /> {{ \Carbon\Carbon::parse($diff->created_at)->diffForHumans() }}
                                </div>
                                <div class="col-md-1">
                                    @lang('recipes.revision_number')

                                    @if (isset($diff->auditable->revision_number))
                                        #{{$diff->auditable->revision_number}}
                                    @elseif (isset($diff->auditable->recipe_ingredient->recipe->revision_number))
                                        #{{$diff->auditable->recipe_ingredient->recipe->revision_number}}
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    @php ($additionalLabelComponent = '')

                                    @if($diff->auditable_type == 'App\RecipeIngredientOption')
                                        @php ($option = App\RecipeIngredientOption::find($diff->auditable_id))
                                        @php ($ingredient = $option->recipe_ingredient)
                                        {{ $ingredient ? $ingredient->name . ", " : "" }}
                                        {{ $option ? $option->name . ": " : "" }}

                                        @php ($additionalLabelComponent = 'ingredient.')
                                    @endif

                                    @if($field == 'rd_specialist_id')
                                        @lang( 'recipes.rd_specialist')
                                    @elseif($field == 'product_id')
                                        @lang( 'products.product')
                                    @elseif($field == 'main_material')
                                        @lang( 'recipes.ingredient.marked_as_main_material')
                                    @else
                                        @if(!empty($additionalLabelComponent) && $field == 'name')
                                            @lang( 'recipes.ingredient.trade_name' )
                                        @else
                                            @lang( 'recipes.' . $additionalLabelComponent . $field )
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-3" style="background: #ffe9e9" class="deleted-line">
                                    @if($field == 'rd_specialist_id')
                                        @php ($rdSpecialist = App\User::find($value["old"]))
                                        {{ $rdSpecialist ? $rdSpecialist->name : "" }}
                                    @elseif($field == 'product_id')
                                        @php ($product = App\Product::find($value["old"]))
                                        {{ $product ? $product->getName() : "" }}
                                    @else
                                        {{ isset($value["old"]) ? $value["old"]: "" }}
                                    @endif
                                </div>
                                <div class="col-md-3" style="background: #e9ffe9" class="added-line">
                                    @if($field == 'rd_specialist_id')
                                        @php ($rdSpecialist = App\User::find($value["new"]))
                                        {{ $rdSpecialist ? $rdSpecialist->name : "" }}
                                    @elseif($field == 'product_id')
                                        @php ($product = App\Product::find($value["new"]))
                                        {{ $product ? $product->getName() : "" }}
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
