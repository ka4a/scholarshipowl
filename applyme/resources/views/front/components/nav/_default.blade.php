<nav class="main-nav navbar-fixed-top Navbar navbar-static">
    <div class="container-fluid">
        <div class="pull-left">
            <a
                href="{{ route('front.index') }}"
                class="logo"
                title="">
                <img src="/imgs/am-logo.png" alt="" height="40">
            </a>
        </div>

        <ul class="nav navbar-nav navbar-left">
            <li class="Navbar__item dropdown">
                <a
                    href=""
                    title=""
                    class="dropdown-toggle nav-item"
                    id="dropdownMenu1"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">FEATURES <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('front.features.index') }}">Features</a></li>
                    <li><a href="{{ route('front.features.courses') }}">Courses</a></li>
                    <li><a href="{{ route('front.features.admissions-coaching') }}">Admissions Coaching</a></li>
                    <li><a href="{{ route('front.features.essay-assistance') }}">Essay Assistance</a></li>
                    <li><a href="{{ route('front.features.interview-preparation') }}">Interview Preparation</a></li>
                    <li><a href="{{ route('front.features.personalized-scholarships-list') }}">Personalized Scholarships List</a></li>
                    <li><a href="{{ route('front.features.guidance-for-parents') }}">Guidance for Parents</a></li>
                </ul>
            </li>
            <li class="Navbar__item">
                <a
                    href="https://academy.apply.me"
                    target="_blank"
                    title="Apply.Me Academy"
                    class="nav-item">ACADEMY</a>
            </li>
            <li class="Navbar__item">
                <a
                    href="{{ route('front.pricing') }}"
                    title=""
                    class="nav-item">PRICING</a>
            </li>
            <li class="Navbar__item">
                <a
                    href="{{ route('front.about-us') }}"
                    title=""
                    class="nav-item">ABOUT US</a>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="Navbar__item">
                <p class="navbar-btn">
                    <a
                        href="https://academy.apply.me/"
                        title="Apply.Me Accademy"
                        class="btn btn-default btn-am-default-light">ACCESS THE ACADEMY</a>
                </p>
            </li>
        </ul>
    </div>
</nav>
