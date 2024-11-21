<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>GSI</title>
    <link rel="stylesheet" href="style.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/navbar/admin.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

    <!-- DESKTOP VIEW -->
    <div class="sidebar close">
        <a href="{{ route('dashboard') }}">
            <div class="logo-details">
                <img src="{{ asset('assets/img/assetslogo.png') }}" alt="Global Service Indonesia Logo"
                    class="sidebar-logo">
                <span class="logo_name">ssets</span>
            </div>
        </a>
        <ul class="nav-links">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    <span class="link_name">Dashboard</span>
                </a>
            </li>
            <li>
                <div class="iocn-link">
                    <a
                        class="{{ request()->routeIs('assets.total') || request()->routeIs('assets.index') || request()->routeIs('assets.location') ? 'active' : '' }}">
                        <i class="fa-solid fa-cubes" {{ request()->routeIs('assets.total') || request()->routeIs('assets.index') || request()->routeIs('assets.location') ? 'active' : '' }}"></i>
                        <span class="link_name">Asset</span>
                    </a>
                    <i class='bx bxs-chevron-left arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a href="{{ route('assets.total') }}"
                            class="{{ request()->routeIs('assets.total') ? 'active' : '' }}">Total</a></li>
                    <li><a href="{{ route('assets.index') }}"
                            class="{{ request()->routeIs('assets.index') ? 'active' : '' }}">List</a></li>
                    <li><a href="{{ route('assets.location') }}"
                            class="{{ request()->routeIs('assets.location') ? 'active' : '' }}">Location</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a
                        class="{{ request()->routeIs('transactions.index') || request()->routeIs('transactions.handover') || request()->routeIs('transactions.indexreturn') ? 'active' : '' }}">
                        <i class='fa-solid fa-chart-column'
                            class="{{ request()->routeIs('transactions.index') || request()->routeIs('transactions.handover') || request()->routeIs('transactions.indexreturn') ? 'active' : '' }}"></i>
                        <span class="link_name">Activity</span>
                    </a>
                    <i class='bx bxs-chevron-left arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a href="{{ route('transactions.index') }}"
                            class="{{ request()->routeIs('transactions.index') ? 'active' : '' }}">Approval Status</a>
                    </li>
                    <li><a href="{{ route('transactions.handover') }}"
                            class="{{ request()->routeIs('transactions.handover') ? 'active' : '' }}">Handover</a></li>
                    <li><a href="{{ route('transactions.indexreturn') }}"
                            class="{{ request()->routeIs('transactions.indexreturn') ? 'active' : '' }}">Return</a></li>
                    <li><a href="{{ route('assets.scrap') }}"
                            class="{{ request()->routeIs('assets.scrap') ? 'active' : '' }}">Scrap</a></li>
                    <li><a href="{{ route('assets.maintenance') }}"
                            class="{{ request()->routeIs('assets.maintenance') ? 'active' : '' }}">
                            Maintenance
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                @livewire('sidebar-notification')
            </li>
            <li>
                <a href="{{ route('inventory.history') }}"
                    class="{{ request()->routeIs('inventory.history') ? 'active' : '' }}">
                    <i class='fa-solid fa-clock'></i>
                    <span class="link_name">History</span>
                </a>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#"
                        class="{{ request()->routeIs('customer.index') || request()->routeIs('assets.add-asset') || request()->routeIs('merk.index') ? 'active' : '' }}">
                        <i class='fa-solid fa-gear'
                            class="{{ request()->routeIs('customer.index') || request()->routeIs('assets.add-asset') || request()->routeIs('merk.index') ? 'active' : '' }}"></i>
                        <span class="link_name">Setting</span>
                    </a>
                    <i class='bx bxs-chevron-left arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a href="{{ route('customer.index') }}"
                            class="{{ request()->routeIs('customer.index') ? 'active' : '' }}">Users</a></li>
                    <li><a href="{{ route('assets.add-asset') }}"
                            class="{{ request()->routeIs('assets.add-asset') ? 'active' : '' }}">Add Asset</a></li>
                    <li><a href="{{ route('merk.index') }}"
                            class="{{ request()->routeIs('merk.index') ? 'active' : '' }}">Add Merk</a></li>
                </ul>
            </li>
            <div class="bottom-section">
                <li>
                    <a href="https://helpdesk.globalservice.co.id/" target="_blank"
                        class="{{ request()->routeIs('sales.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-headset"></i>
                        <span class="link_name" style="font-size:19px; margin-left:-10px;">Help Center</span>
                    </a>
                    <a href="#" onclick="toggleNightMode()">
                        <i class="fa-solid fa-moon" style="margin-top:-20px"></i>
                        <span class="link_name"
                            style="font-size:19px; margin-top:-20px; margin-left:-10px; margin-right:10px;">Dark
                            Mode</span>
                        <button id="nightModeToggle" class="toggle-switch" style="margin-top:-20px">
                            <i id="modeIcon" class='bx bx-moon'></i>
                            <div class="slider"></div>
                        </button>
                    </a>
                </li>
            </div>
        </ul>
    </div>
    <div class="home-content">
        <i class='bx bx-menu'></i>
        <span class="text gsi-button">Admin Dashboard</span>
        <!-- Admin -->
        <div class="profile-details-top">
            <div class="profile-content">
                <img src="{{ asset('assets/img/admin.png') }}" alt="profileImg">
            </div>
            <div class="name-job">
                <div class="profile_name">Admin</div>
                <div class="job">Infrastructure</div>
            </div>

            <i class="fa-solid fa-right-from-bracket" id="logout-icon" style="cursor: pointer;"></i>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
    <section class="home-section">
        <div>
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </section>

    <!-- MOBILE VIEW -->
    <!-- Header khusus untuk tampilan mobile -->
    <header class="mobile-header">
        <button class="back-button" onclick="window.history.back()">
            <i class="fa-solid fa-circle-chevron-left"></i>
        </button>
        <div class="profile">
            <button id="darkModeButton" class="dark-mode-toggle" onclick="toggleDarkMode()" style="margin-right:10px;">
                <i id="darkModeIcon" class="bx bx-moon"></i> <!-- Default: Ikon bulan -->
            </button>
            @livewire('ticketmobile')
            <i class="fa fa-sign-out-alt fa-xl" id="logout-icon-mobile" style="cursor: pointer; margin-left:10px;"></i>
        </div>
    </header>
    <!-- Logout Form -->
    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>





    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        <!-- Assets Menu -->
        <a href="javascript:void(0)" class="menu-toggle" data-target="assets-submenu">
            <i class="fas fa-cubes"></i>
            <span>Assets</span>
        </a>
        <div id="assets-submenu" class="submenu">
            <a href="{{ route('assets.total') }}"
                class="{{ request()->routeIs('assets.total') ? 'active' : '' }}">Total</a>
            <a href="{{ route('assets.index') }}"
                class="{{ request()->routeIs('assets.index') ? 'active' : '' }}">List</a>
            <a href="{{ route('assets.location') }}"
                class="{{ request()->routeIs('assets.location') ? 'active' : '' }}">Location</a>
            <a href="{{ route('inventory.history') }}"
                class="{{ request()->routeIs('inventory.history') ? 'active' : '' }}">History</a>
        </div>
        <!-- Activity Menu -->
        <a href="javascript:void(0)" class="menu-toggle" data-target="activity-submenu">
            <i class="fas fa-chart-line"></i>
            <span>Activity</span>
        </a>
        <div id="activity-submenu" class="submenu">
            <a href="{{ route('transactions.index') }}"
                class="{{ request()->routeIs('transactions.index') ? 'active' : '' }}">Approval Status</a>
            <a href="{{ route('transactions.handover') }}"
                class="{{ request()->routeIs('transactions.handover') ? 'active' : '' }}">Handover</a>
            <a href="{{ route('transactions.indexreturn') }}"
                class="{{ request()->routeIs('transactions.indexreturn') ? 'active' : '' }}">Return</a>
            <a href="{{ route('assets.scrap') }}"
                class="{{ request()->routeIs('assets.scrap') ? 'active' : '' }}">Scrap</a>
            <a href="{{ route('assets.maintenance') }}"
                class="{{ request()->routeIs('assets.maintenance') ? 'active' : '' }}">Maintenance</a>
        </div>

        <!-- Settings Menu -->
        <a href="javascript:void(0)" class="menu-toggle" data-target="settings-submenu">
            <i class="fas fa-user-cog"></i>
            <span>Settings</span>
        </a>
        <div id="settings-submenu" class="submenu">
            <a href="{{ route('customer.index') }}"
                class="{{ request()->routeIs('customer.index') ? 'active' : '' }}">Users</a>
            <a href="{{ route('assets.add-asset') }}"
                class="{{ request()->routeIs('assets.add-asset') ? 'active' : '' }}">Add Asset</a>
            <a href="{{ route('merk.index') }}" class="{{ request()->routeIs('merk.index') ? 'active' : '' }}">Add
                Merk</a>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</body>

</html>
<script>
    function toggleDarkMode() {
        const body = document.body;
        const icon = document.getElementById('darkModeIcon');

        // Toggle mode
        body.classList.toggle('dark-mode');

        // Ganti ikon
        if (body.classList.contains('dark-mode')) {
            icon.classList.remove('bx-moon');
            icon.classList.add('bx-sun'); // Ikon matahari
        } else {
            icon.classList.remove('bx-sun');
            icon.classList.add('bx-moon'); // Ikon bulan
        }

        // Simpan preferensi pengguna di localStorage
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    }

    // Cek preferensi dark mode saat halaman dimuat
    window.onload = function () {
        const body = document.body;
        const icon = document.getElementById('darkModeIcon');

        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            icon.classList.remove('bx-moon');
            icon.classList.add('bx-sun');
        }
    };

    // Function to toggle dark mode and handle switch movement
    function toggleNightMode() {
        const body = document.body;
        const icon = document.getElementById('modeIcon');
        const logoName = document.querySelector('.logo_name');
        const toggleSwitch = document.getElementById('nightModeToggle'); // Reference to the switch

        // Toggle dark mode class
        body.classList.toggle('dark-mode');
        toggleSwitch.classList.toggle('active'); // Add 'active' class to move the slider

        // Check if dark mode is now enabled or disabled
        if (body.classList.contains('dark-mode')) {
            icon.classList.remove('bx-moon');
            icon.classList.add('bx-sun');
            logoName.style.color = 'white'; // Change logo text color when dark mode is enabled
            localStorage.setItem('dark-mode', 'enabled'); // Save dark mode state
        } else {
            icon.classList.remove('bx-sun');
            icon.classList.add('bx-moon');
            logoName.style.color = ''; // Reset logo text color when dark mode is disabled
            localStorage.setItem('dark-mode', 'disabled'); // Save dark mode state
        }
    }

    // Check if dark mode is already enabled when the page loads
    document.addEventListener("DOMContentLoaded", function () {
        const body = document.body;
        const icon = document.getElementById('modeIcon');
        const logoName = document.querySelector('.logo_name');
        const toggleSwitch = document.getElementById('nightModeToggle'); // Reference to the switch

        // Check the saved dark mode preference from localStorage
        const darkModeState = localStorage.getItem('dark-mode');

        if (darkModeState === 'enabled') {
            body.classList.add('dark-mode');
            toggleSwitch.classList.add('active'); // Add 'active' class to move the slider
            icon.classList.remove('bx-moon');
            icon.classList.add('bx-sun');
            logoName.style.color = 'white'; // Set logo text color to white when dark mode is enabled
        } else {
            toggleSwitch.classList.remove('active'); // Remove 'active' class when dark mode is disabled
            icon.classList.remove('bx-sun');
            icon.classList.add('bx-moon');
            logoName.style.color = ''; // Reset logo text color when dark mode is disabled
        }
    });

    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
        arrow[i].addEventListener("click", (e) => {
            let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
            arrowParent.classList.toggle("showMenu");
        });
    }

    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");

    // Check localStorage for sidebar state and set it
    if (localStorage.getItem('sidebarState') === 'open') {
        sidebar.classList.remove("close");
    } else {
        sidebar.classList.add("close");
    }

    sidebarBtn.addEventListener("click", () => {
        sidebar.classList.toggle("close");
        // Update localStorage based on the sidebar state
        if (sidebar.classList.contains("close")) {
            localStorage.setItem('sidebarState', 'closed');
        } else {
            localStorage.setItem('sidebarState', 'open');
        }
    });

    // Logout functionality with SweetAlert confirmation
    document.getElementById('logout-icon').addEventListener('click', function (e) {
        e.preventDefault();  // Prevent form from submitting immediately

        // Show SweetAlert modal for logout confirmation with improved design
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out of the system.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#6B07C2",
            cancelButtonColor: "#d33",
            confirmButtonText: "Log Out",
            cancelButtonText: "Cancel",
            preConfirm: () => {
                // Show loading indicator
                Swal.showLoading();
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve();
                    }, 500); // Durasi loading sebelum logout, Anda dapat menyesuaikannya
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, submit the logout form
                document.getElementById('logout-form').submit();
            }
        });
    });

    // Logout functionality with SweetAlert confirmation
    document.getElementById('logout-icon-mobile').addEventListener('click', function (e) {
        e.preventDefault();  // Prevent form from submitting immediately

        // Show SweetAlert modal for logout confirmation with improved design
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out of the system.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#6B07C2",
            cancelButtonColor: "#d33",
            confirmButtonText: "Log Out",
            cancelButtonText: "Cancel",
            preConfirm: () => {
                // Show loading indicator
                Swal.showLoading();
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve();
                    }, 500); // Durasi loading sebelum logout, Anda dapat menyesuaikannya
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, submit the logout form
                document.getElementById('logout-form-mobile').submit();
            }
        });
    });


    // Untuk add color pada sidebar menu saat berada d page tersebut
    document.querySelectorAll('.nav-links li a').forEach(link => {
        link.addEventListener('click', function () {
            // Remove active class from all links and icons
            document.querySelectorAll('.nav-links li a').forEach(link => {
                link.classList.remove('active');
            });
            // Add active class to the clicked link and icon
            this.classList.add('active');
        });
    });

    // Add click event listeners to all menu links
    document.querySelectorAll('.nav-links li a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) { // Check if it's mobile view
                sidebar.classList.add("close"); // Close the sidebar
                localStorage.setItem('sidebarState', 'closed'); // Update localStorage
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const toggles = document.querySelectorAll('.menu-toggle');

        toggles.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const target = document.getElementById(toggle.getAttribute('data-target'));

                // Hide all other submenus
                document.querySelectorAll('.submenu').forEach(function (submenu) {
                    if (submenu !== target) {
                        submenu.classList.remove('show'); // Hide other submenus
                    }
                });

                // Toggle the clicked submenu
                target.classList.toggle('show');
            });
        });
    });



</script>
<style>
    .badge-ticket {
        position: absolute;
        top: 5px;
        right: 10px;
        font-size: 11px;
        font-weight: bold;
        color: white;
        background-color: #db1f1f;
        padding: 3px 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .badge-tickets {
        position: absolute;
        top: 20px;
        right: 90px;
        font-size: 8px;
        font-weight: bold;
        color: white;
        background-color: #db1f1f;
        padding: 1px 6px;
        border-radius: 50%;
        display: inline-block;
    }
</style>