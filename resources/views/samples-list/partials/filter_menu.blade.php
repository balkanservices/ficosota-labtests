<ul class="dropdown-menu" aria-labelledby="dropdownMenu3">
    <li class="dropdown-header"></li>
</ul>

@php ($params = Request::all())
@php ($route =  Route::currentRouteName())

@php ($paramCustomValue = '')
@if (isset($params[$paramName]) && !in_array($params[$paramName], $values))
    @php ($paramCustomValue = $params[$paramName])
@endif

<div class="dropdown">
    <button class="btn btn-default dropdown-toggle samples-lists-col-header" type="button" id="dropdownMenu{{ $paramName }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        @lang($label)

        @if (isset($params[$paramName]) && in_array($params[$paramName], $values)):
            @if ($paramName == 'type')
                <u>@lang('products.types.' . $params[$paramName])</u>
            @else
                <u>{{ $params[$paramName] }}</u>
            @endif

        @elseif(isset($params['sort']) && $params['sort'] == $paramName . '__a_z'):
            <u>@lang('sort.a_z')</u>
        @elseif(isset($params['sort']) && $params['sort'] == $paramName . '__z_a'):
            <u>@lang('sort.z_a')</u>
        @elseif(!empty($paramCustomValue))
        <u>{{$paramCustomValue}}</u>
        @endif
        <!--<span class="caret"></span>-->
    </button>

    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        @php ( $isSortedByParam = isset($params['sort']) && in_array($params['sort'], [$paramName . '__a_z', $paramName . '__z_a']))
        @php ($clearParams = [$paramName => null, 'page' => null])
        @if ($isSortedByParam)
            @php ($clearParams = array_merge($clearParams, ['sort' => null]))
        @endif



        @if ((isset($params[$paramName]) && in_array($params[$paramName], $values))
            || $isSortedByParam || !empty($paramCustomValue) )

            <li><a href="{{ route($route, array_merge($params, $clearParams)) }}">@lang('sort.clear')</a></li>
            <li class='divider' role='separator'></li>
        @endif

        @php ($params = array_merge($params, $clearParams))
        <li><a href="{{ route($route, array_merge($params, ['sort' => $paramName . '__a_z', $paramName => null])) }}">@lang('sort.a_z')</a></li>
        <li><a href="{{ route($route, array_merge($params, ['sort' => $paramName . '__z_a', $paramName => null])) }}">@lang('sort.z_a')</a></li>
        @if (!empty($values))
            <li class='divider' role='separator'></li>
            <li>
                <form method="GET" action="">
                    <input type="text" class='form-control' name="{{$paramName}}" value="{{ $paramCustomValue }}" style='width: 90%; margin-left: 5%; margin-right: 5%;'/>
                    @foreach ($params as $hiddenParam => $hiddenParamValue)
                        @if ($hiddenParam != $paramName)
                        <input type="hidden" name="{{$hiddenParam}}" value="{{ $hiddenParamValue }}"/>
                        @endif
                    @endforeach
                </form>
            </li>
            @foreach ( $values as $value )
            <li><a href="{{ route($route, array_merge($params, [$paramName => $value])) }}">
                @if ($paramName == 'type')
                    @lang('products.types.' . $value)
                @else
                    {{ $value }}
                @endif
            </a></li>
            @endforeach
        @endif
    </ul>
</div>