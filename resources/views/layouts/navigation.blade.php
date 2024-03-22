@php
    use App\Helpers\NavigationHelper;
@endphp

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        
        <li class="nav-item">
            <form method="POST" style="display:inline;" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" class="nav-link"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="mr-2 fas fa-power-off text-danger"></i>
                </a>
            </form>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard.index') }}" class="brand-link">
        
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2 mb-5">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                {{-- <li class="nav-item">
                    <a href="{{ route('home.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>Homepage</p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}"
                        class="nav-link {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @foreach (NavigationHelper::getMainMenu() as $mainMenu)
                    @can('index ' . $mainMenu->url)
                        <li
                            class="nav-item {{ request()->segment(2) == Str::after($mainMenu->url, 'admin/') ? 'menu-open' : '' }}">
                            <a href="{{ url('/' . $mainMenu->url) }}"
                                class="nav-link {{ request()->segment(2) == Str::after($mainMenu->url, 'admin/') ? 'active' : '' }}">
                                <i class="nav-icon {{ $mainMenu->icon }}"></i>
                                <p>{{ $mainMenu->name }}</p>

                                @if ($mainMenu->subMenus->isNotEmpty())
                                    <i class="right fas fa-angle-left"></i>
                                @endif
                            </a>

                            @if ($mainMenu->subMenus->isNotEmpty())
                                <ul class="nav nav-treeview">
                                    @foreach ($mainMenu->subMenus as $submenu)
                                        <li class="nav-item">
                                            <a href="{{ url('/' . $submenu->url) }}"
                                                class="nav-link {{ url()->current() == url('/' . $submenu->url) ? 'active' : '' }}">
                                                <i class="{{ $submenu->icon }}"></i>
                                                <p>{{ $submenu->name }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endcan
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
