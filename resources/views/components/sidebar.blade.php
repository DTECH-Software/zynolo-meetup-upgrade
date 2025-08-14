<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="/dashboard" class="logo logo-light">
        <span class="logo-lg">
            <img src="/assets/images/logo-white.png" style="width: 200px; height: auto;" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="/assets/images/zynolo-small.png" style="width: 35px; height: 35px;" alt="small logo">
        </span>
    </a>


    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title">Navigation</li>

            <li class="side-nav-item">
                <a href="/dashboard" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-title">More Links</li>


            <li class="side-nav-item">
                <a href="/view-users" class="side-nav-link">
                    <i class="uil-users-alt"></i>
                    <span> Users </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="/calendar" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> Calendar </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="/view-meetings" class="side-nav-link">
                    <i class="uil-meeting-board"></i>
                    <span> Meetings </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#availability" aria-expanded="false" aria-controls="availability"
                    class="side-nav-link">
                    <i class="uil-clock"></i>
                    <span> Availability </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="availability">
                    <ul class="side-nav-second-level">
                        <li><a href="/send-availability">Send Availability Check Request</a></li>
                        <li><a href="/check-availability">Check User Availability</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="/manage-calendar" class="side-nav-link">
                    <i class="uil-schedule"></i>
                    <span> Manage Calendar </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#permission" aria-expanded="false" aria-controls="permission"
                    class="side-nav-link">
                    <i class="uil-lock"></i>
                    <span> Permissions</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="permission">
                    <ul class="side-nav-second-level">
                        <li><a href="/add-permission">Add Permissions</a></li>
                    </ul>
                </div>
            </li>


        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar End ========== -->
