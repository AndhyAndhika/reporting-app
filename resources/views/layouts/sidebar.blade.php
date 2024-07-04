
{{-- Sidebar --}}
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion text-dark" id="sidenavAccordion" style="background-color: #dcdce6;">
        <div class="sb-sidenav-menu">
            <div class="nav fs-5">
                {{-- Logo SAT --}}
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('img/logo-sat.jpg') }}" class="rounded-circle d-block mx-auto mt-3" alt="{{ asset('img/logo-sat.jpg') }}" style="width: 8.5rem;">
                </a>

                <div class="sb-sidenav-menu-heading"></div>

                {{-- Menu link to dashboard --}}
                <a class="nav-link hover-text-dark" href="{{ route('dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user fa-lg"></i></div>
                    Dashboard
                </a>

                {{-- Menu link to Input Data --}}
                <a class="nav-link hover-text-dark" href="{{ route('inputdata.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-file-pen fa-xl"></i></div>
                    Input Data
                </a>

                {{-- Menu link to Reporting --}}
                <a class="nav-link hover-text-dark" href="{{ route('reporting.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-line fa-xl"></i></div>
                    Bussines Plan
                </a>

                {{-- Menu link to Setup Data --}}
                <a class="nav-link hover-text-dark" href="{{ route('setupdata.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-gear fa-xl"></i></div>
                    Setup Data
                </a>

                {{-- Menu link to Manpower --}}
                <a class="nav-link hover-text-dark" href="{{ route('manpower.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users fa-lg"></i></div>
                    Manpower
                </a>
                {{-- <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Layouts
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="layout-static.html">Static Navigation</a>
                        <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Pages
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                            Authentication
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="login.html">Login</a>
                                <a class="nav-link" href="register.html">Register</a>
                                <a class="nav-link" href="password.html">Forgot Password</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                            Error
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="401.html">401 Page</a>
                                <a class="nav-link" href="404.html">404 Page</a>
                                <a class="nav-link" href="500.html">500 Page</a>
                            </nav>
                        </div>
                    </nav>
                </div>
                <div class="sb-sidenav-menu-heading">Addons</div>
                <a class="nav-link" href="charts.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Charts
                </a>
                <a class="nav-link" href="tables.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    Tables
                </a> --}}
            </div>
        </div>

        {{-- Footer to update Version --}}
        <div class="sb-sidenav-footer">
            <div class="d-flex align-items-center justify-content-center small">
                <p class="mb-0"><i class="text-muted fa-solid fa-code-commit small"> : V1.0.0 </i></p>
            </div>
        </div>
    </nav>
</div>


