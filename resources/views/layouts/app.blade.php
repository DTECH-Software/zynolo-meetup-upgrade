<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Zynolo MeetUp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Zynolo Meetup">
    <meta name="author" content="Tineth Pathirage">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/zynolo-small.png">
    <!-- Styles Here -->
    @include('libraries.styles')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        @include('components.topbar')
        @include('components.sidebar')

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!--Content-->
                    @yield('content')
                    <!--End Content-->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            @include('components.footer')
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->
    <!-- Script Here -->
    @include('libraries.scripts')
</body>

</html>
