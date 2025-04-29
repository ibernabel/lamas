<!-- This is a test comment to ensure the correct view is being loaded -->
@php($logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout'))
@php($profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout'))

@if (config('adminlte.usermenu_profile_url', false))
    @php($profile_url = Auth::user()->adminlte_profile_url())
@endif

@if (config('adminlte.use_route_url', false))
    @php($profile_url = $profile_url ? route($profile_url) : '')
    @php($logout_url = $logout_url ? route($logout_url) : '')
@else
    @php($profile_url = $profile_url ? url($profile_url) : '')
    @php($logout_url = $logout_url ? url($logout_url) : '')
@endif

<li class="nav-item dropdown user-menu">

    {{-- User menu toggler --}}
    <a href="#" class="nav-link dropdown-toggle flex " data-toggle="dropdown">
        <span @if (config('adminlte.usermenu_image')) class="mr-2 d-none d-lg-inline text-gray-600 small"@endif>
            {{ Auth::user()->name }}
        </span>
        @if (config('adminlte.usermenu_image'))
            <img src="{{ Auth::user()->adminlte_image() }}" class="user-image  object-cover"
                alt="{{ Auth::user()->name }}">
        @endif
    </a>

    {{-- User menu dropdown --}}
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right rounded-lg shadow-2xl border-0 mr-4 bg-white">

        {{-- User menu image --}}

        {{-- User menu header --}}
        @if (!View::hasSection('usermenu_header') && config('adminlte.usermenu_header'))
            <li
                class="user-header {{ config('adminlte.usermenu_header_class', 'bg-primary') }}
                @if (!config('adminlte.usermenu_image')) h-auto @endif">
                @if (config('adminlte.usermenu_image'))
                    <img src="{{ Auth::user()->adminlte_image() }}" class="w-10 h-10 rounded-full object-cover elevation-2 border-0"
                        alt="{{ Auth::user()->name }}">
                @endif
                <p class="mt-4 @if (!config('adminlte.usermenu_image')) @endif">
                    {{ Auth::user()->name }}
                    @if (config('adminlte.usermenu_desc'))
                        <small>{{ Auth::user()->adminlte_desc() }}</small>
                    @endif
                </p>
            </li>
        @else
            @yield('usermenu_header')
        @endif

        {{-- Divider --}}
        <li class="dropdown-divider"></li>

        {{-- Configured user menu links --}}
        @each('adminlte::partials.navbar.dropdown-item', $adminlte->menu('navbar-user'), 'item')

        {{-- User menu body --}}
        @hasSection('usermenu_body')
            <li class="user-body">
                @yield('usermenu_body')
            </li>
        @endif

        {{-- User menu footer --}}
        <li class="user-footer rounded-lg">
            @if ($profile_url)
                <a href="{{ $profile_url }}"
                    class="nav-link btn btn-default btn-flat d-inline-block rounded-lg shadow-xs">
                    <i class="fa fa-fw fa-user text-lightblue"></i>
                    {{ __('adminlte::menu.profile') }}
                </a>
            @endif
            <a class="btn btn-default btn-flat float-right rounded-lg shadow-xs @if (!$profile_url) btn-block @endif"
                href="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-fw fa-power-off text-red"></i>
                {{ __('adminlte::adminlte.log_out') }}
            </a>
            <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
                @if (config('adminlte.logout_method'))
                    {{ method_field(config('adminlte.logout_method')) }}
                @endif
                {{ csrf_field() }}
            </form>
        </li>

    </ul>

</li>
