<!DOCTYPE html>
<html lang="en">
<head>
    <title>ad4mat - VersionLog</title>
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,700" rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='{{ asset("css/bootstrap.min.css") }}'/>
    <link rel='stylesheet' type='text/css' href='{{ asset("css/versionRoll.css") }}'/>
    <link rel='stylesheet' type='text/css' href='{{ asset("form-multiselect/css/multi-select.css") }}'/>
    <link rel='stylesheet' type='text/css' href='{{ asset("simplePagination/simplePagination.css") }}'/>

</head>

<body>
<div class="header">
    <div>
        <img src="/img/as-rgb-blau-23.png" alt="advancedStore">
        <h1>Version Log</h1>
    </div>


</div>

<div class="container">

    @if(Request::path() !== 'tv')
        @if (!\Illuminate\Support\Facades\Auth::check())
            @include('VersionLog.loginmodal')
            <a id="login"
               type="button"
               class="topbtn"
               data-toggle="modal"
               data-target="#favoritesModal">
                Login
            </a>


        @else

            <a href="{{ url('/logout') }}"
               class="topbtn"
               onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">Logout
            </a>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                {{ csrf_field() }}
            </form>

            <a id="create"
               class="topbtn"
               type="button"
               data-toggle="modal"
               data-target="#favoritesModal">
                New entry
            </a>

            <a
                    href="{{ url('/intern/drafts') }}"
                    class="topbtn"
                    onclick="event.preventDefault();
           document.getElementById('drafts-form').submit();">
                @if (Request::path() == 'intern/drafts')
                    Publications
                    <form id="drafts-form" action="{{ url('/intern') }}" method="GET">
                        {{ csrf_field() }}
                    </form>
                @else
                    Drafts
                    <form id="drafts-form" action="{{ url('/intern/drafts') }}" method="GET">
                        {{ csrf_field() }}
                    </form>
                @endif
            </a>

            {{--<a href="{{ url('/tv') }}"
               class="topbtn"
               onclick="event.preventDefault();
       document.getElementById('tv-form').submit();">TV
            </a>
            <form id="tv-form" action="{{ url('/tv') }}" method="GET">
                {{ csrf_field() }}
            </form>
            --}}
        @endif

    @endif
    @include('VersionLog.createmodal')
    @include('VersionLog.editlayer')





    <input id="search" class="ui-autocomplete-input" placeholder="Filter by Tool, Team or User">
    @yield('content')

</div>

@yield('scripts')
<script type='text/javascript' src='{{ asset("js/jquery-1.10.2.min.js") }}'></script>
<script type='text/javascript' src='{{ asset("js/jqueryui-1.10.3.min.js") }}'></script>
<script type='text/javascript' src='{{ asset("js/bootstrap.min.js") }}'></script>
<script type='text/javascript' src='{{ asset("form-multiselect/js/jquery.multi-select.min.js") }}'></script>
<script type='text/javascript' src='{{ asset("js/custom/tools/versionroll.js") }}'></script>
<script type='text/javascript' src='{{ asset("js/simplePagination/jquery.simplePagination.js") }}'></script>
</body>

</html>
