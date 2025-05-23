<div id="app-sidepanel" class="app-sidepanel sidepanel-visible">
    <div id="sidepanel-drop" class="sidepanel-drop"></div>
    <div class="sidepanel-inner d-flex flex-column">
        <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">×</a>
        <div class="app-branding">
            <a class="app-logo" href="#">
                <img class="logo-icon me-2" src="{{ asset('admin/images/app-logo.svg') }}" alt="logo">
                <span class="logo-text">
                    PORTAL
                </span>
            </a>
        </div>

        <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
            <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="nav-icon">
                            <i class="fa-solid fa-gauge fs-5"></i>
                        </span>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <i class="fa-solid fa-layer-group fs-5"></i>
                        </span>
                        <span class="nav-link-text">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <i class="fa-solid fa-layer-group fs-5"></i>
                        </span>
                        <span class="nav-link-text">Skills</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <i class="fa-solid fa-layer-group fs-5"></i>
                        </span>
                        <span class="nav-link-text">Work Experience</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <i class="fa-solid fa-layer-group fs-5"></i>
                        </span>
                        <span class="nav-link-text">Projects</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.educations') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.educations') }}">
                        <span class="nav-icon">
                            <i class="fa-solid fa-layer-group fs-5"></i>
                        </span>
                        <span class="nav-link-text">Education</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="nav-icon">
                            <i class="fa-solid fa-layer-group fs-5"></i>
                        </span>
                        <span class="nav-link-text">Blogs</span>
                    </a>
                </li>
                <li class="nav-item has-submenu">
                    <a class="nav-link submenu-toggle" href="#" data-bs-toggle="collapse"
                        data-bs-target="#submenu-2" aria-expanded="false" aria-controls="submenu-2">
                        <span class="nav-icon">

                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-columns-gap"
                                fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M6 1H1v3h5V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12h-5v3h5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8H1v7h5V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6h-5v7h5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z">
                                </path>
                            </svg>
                        </span>
                        <span class="nav-link-text">Settings</span>
                        <span class="submenu-arrow">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-down"
                                fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z">
                                </path>
                            </svg>
                        </span>
                    </a>
                    <div id="submenu-2" class="collapse submenu submenu-2" data-bs-parent="#menu-accordion">
                        <ul class="submenu-list list-unstyled">
                            <li class="submenu-item">
                                <a class="submenu-link" href="#">
                                    Change Password
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
