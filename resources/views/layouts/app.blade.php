<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
	@yield('css_files')

	<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/flag-icon.min.css') }}" rel="stylesheet">
</head>
<body lang="en-US">
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

					@guest

					@else
						<ul class="nav navbar-nav">
							<li class="{{ Request::is('/') ? 'active' : '' }}"><a href="{{ route('home') }}">@lang('home.home')</a></li>
                            <li class="{{ Request::is('product*') ? 'active' : '' }}"><a href="{{ route('products.index') }}">@lang('home.products')</a></li>
							<li class="{{ Request::is('recipe*') ? 'active' : '' }}"><a href="{{ route('recipes.index') }}">@lang('home.recipes')</a></li>
							<li class="{{ Request::is('qa-journal*') ? 'active' : '' }}"><a href="{{ route('qa_journals.index') }}">@lang('home.qa_journals')</a></li>
							<li class="{{ Request::is('samples-list*') ? 'active' : '' }}"><a href="{{ route('samples_list.index') }}">@lang('home.samples_list')</a></li>

                            @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('supervisor'))
                            <li class="{{ Request::is('users*') ? 'active' : '' }}"><a href="{{ route('users.index') }}">@lang('home.users')</a></li>
                            @endif
						</ul>
					@endguest

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                @if (App::getLocale() == 'bg')
                                <span class="flag-icon flag-icon-bg"></span>
                                @else
                                <span class="flag-icon flag-icon-gb"></span>
                                @endif
                            </a>

                            <ul class="dropdown-menu">
                                @php ($routeParameters = request()->route()->parameters)
                                <li>

                                    @php ($routeParameters['_locale'] = 'en')
                                    <a href="{{ route(Request::route()->getName(), $routeParameters) }}">
                                        <span class="flag-icon flag-icon-gb"></span> English
                                    </a>
                                </li>
                                <li>
                                    @php ($routeParameters['_locale'] = 'bg')
                                    <a href="{{ route(Request::route()->getName(), $routeParameters) }}">
                                        <span class="flag-icon flag-icon-bg"></span> Български
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">@lang('auth.login')</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            @lang('auth.logout')
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <!--<script src="{{ asset('js/app.js') }}"></script>-->
	<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>

    @yield('javascript')
</body>
</html>
