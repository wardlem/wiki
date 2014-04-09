@extends('foundation')

@section('content')
<nav class="top-bar" data-topbar>
    <ul class="title-area">
        <li class="name">
            <h1>
                <a href="#">
                    @if(isset($siteTitle))
                    {{{ h1 }}}
                    @else
                    Welcome to the Wiki
                    @endif
                </a>
            </h1>
        </li>
    </ul>

    <section class="top-bar-section">
        <!-- Right Nav Section -->
        <ul class="right">
            <li class="has-form">
                <div class="row collapse">
                    <div class="large-8 small-9 columns">
                        <input type="text" placeholder="Find Stuff">
                    </div>
                    <div class="large-4 small-3 columns">
                        <a href="#" class="alert button expand">Search</a>
                    </div>
                </div>
            </li>
            <li class="has-dropdown">
                <a href="#">User Name</a>
                <ul class="dropdown">
                    <li><a href="#">Logout</a></li>
                    <li><a href="#">Settings</a></li>
                </ul>
            </li>
        </ul>
    </section>
</nav>
<aside class="left-off-canvas-menu">
    <ul class="off-canvas-list">
        @yield('leftNav')
    </ul>
</aside>

<section class="main-section">
    @yield('main')
</section>

<a class="exit-off-canvas"></a>

@stop
