@extends('layouts.core')
@section('app')
    <nav class="sb-topnav navbar navbar-expand" style="background-color: #1a457d;">
        <a class="navbar-brand ps-3 fs-5 text-light" href="{{ route('dashboard') }}">SINAR AGUNG TEKNIK</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 text-light" id="sidebarToggle" href="#!"><i class="fa-solid fa-bars fs-3"></i></button>

        {{-- right navbar section --}}
        <ul class="navbar-nav d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0 ">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle text-light fs-5" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

    </nav>
    <div id="layoutSidenav">
        @include('layouts.sidebar')
        <div id="layoutSidenav_content">

            {{-- Body On Content --}}
            <main>
                <div class="container-fluid px-3 pt-3">
                    @yield('content')
                </div>
            </main>

            {{-- Footer --}}
            <footer class="py-4 mt-auto" style="background-color: #e9e9e9;">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-end justify-content-end small">
                        <div class="text-muted">Copyright &copy; CV. SINAR AGUNG TEKNIK</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection
