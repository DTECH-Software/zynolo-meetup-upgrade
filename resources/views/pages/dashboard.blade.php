@extends('layouts.app')

@section('content')
    <!-- Start Content-->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <form class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-light" id="dash-daterange">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="mdi mdi-calendar-range font-13"></i>
                            </span>
                        </div>
                        <a href="/profile" class="btn btn-primary ms-2">
                            <i class="mdi mdi-account"></i>
                        </a>
                    </form>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="row">
        <div class="col-xl-6 col-lg-12">
            <div class="card">
                <div class="card-body d-flex flex-column flex-md-row align-items-center  text-center text-md-start">
                    <span class="m-2 me-md-4">
                        <img src="assets/images/user.png" style="height: 100px;" alt="avatar image"
                            class="rounded-circle img-thumbnail">
                    </span>
                    <div>
                        <h4 class="mt-1 mb-1">Tineth Pathirage</h4>
                        <p class="font-13">Associate Software Engineer</p>

                        <span class="badge badge-info-lighten py-1 px-2 font-13">
                            <i class="mdi mdi-home me-1"></i>D Tech (Pvt) Ltd
                        </span>
                    </div>
                </div>

                <!-- end card-body-->
            </div>
        </div> <!-- end col -->

        <div class="col-xl-6 col-lg-12">
            <div class="card cta-box bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-center">
                        <div class="w-100 overflow-hidden">
                            <div class="d-flex align-items-center">
                                <h3 class="mt-0 text-reset mb-0 d-flex align-items-center">
                                    <i class=" uil-clock-three"></i>
                                </h3>
                                <span id="timeDisplay" class="ms-1">Loading time...</span>
                            </div>

                            <h3 class="m-0 fw-normal cta-box-title text-reset">Welcome to <b>ZYNOLO MEETUP</b> for enhanced
                                efficiency <img src="assets/images/wave.gif" alt="Icon"
                                    style="width: 35px; height: 35px; vertical-align: center;"></h3>
                        </div>
                        <img class="ms-3" src="assets/images/email-campaign.svg" width="130" height="124"
                            alt="Generic placeholder image">
                    </div>
                </div>
                <!-- end card-body -->
            </div>
        </div>
    </div>
    <!-- end row-->


    <div class="row">
        <!-- Left Side -->
        <div class="col-md-6 d-flex mb-3">
            <div class="card cta-box text-bg-primary flex-fill d-flex flex-column h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-center">
                        <div class="w-100 overflow-hidden">

                            <h3 class="m-0 mb-2 fw-normal cta-box-title text-reset">Create a <b>New Meeting</b></h3>
                            <a href="/view-meetings" class="btn btn-sm bg-white text-black rounded-pill">New Meeting <i
                                    class="mdi mdi-arrow-right"></i></a>
                        </div>
                        <img class="my-3" src="assets/images/report.svg" width="180" alt="Generic placeholder image">
                    </div>
                </div>
                <!-- end card-body -->
            </div>
            <!-- end card-->
        </div> <!-- end col-->
        <div class="col-md-6 d-flex mb-3">
            <div class="card flex-fill d-flex flex-column h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">Meeting Status</h4>

                </div>

                <div class="card-body pt-2">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0">
                                <i class="mdi mdi-file-edit widget-icon bg-primary-lighten text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h5 class="my-0 fw-semibold">Completed Meetings</h5>
                            </div>
                            <h4 class="my-0">10</h4>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 91%" aria-valuenow="91"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0">
                                <i class="mdi mdi-account-multiple widget-icon bg-success-lighten text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h5 class="my-0 fw-semibold">Remaining Meetings</h5>
                            </div>
                            <h4 class="my-0">10</h4>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 47%" aria-valuenow="47"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>


    <div class="row align-items-stretch">
        <!-- Calendar Column -->
        <div class="col-12 col-md-6 d-flex">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">Calendar</h4>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">Today</a>
                            <a href="javascript:void(0);" class="dropdown-item">Yesterday</a>
                            <a href="javascript:void(0);" class="dropdown-item">Last Week</a>
                            <a href="javascript:void(0);" class="dropdown-item">Last Month</a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-2 pb-2 pt-0 mt-n2">
                    <div data-provide="datepicker-inline" data-date-today-highlight="true" class="calendar-widget"></div>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-12 col-md-6 d-flex">
            <div class="card flex-fill">
                <div class="card-body d-flex flex-column">
                    <h4 class="text-muted">
                        Today Meeting Schedule
                    </h4>

                    <div class="tab-pane show active" id="alt-pagination-preview">
                        <div style="max-height:350px; overflow-y:auto; -ms-overflow-style:none; scrollbar-width:none;"
                            onscroll="this.style.scrollbarWidth='none'">
                            <style>
                                div[style*='overflow-y:auto']::-webkit-scrollbar {
                                    display: none;
                                }
                            </style>
                            <table class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Meeting Name</th>
                                        <th>Members</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>Online</td>
                                        <td>Tiger Nixon</td>
                                        <td>user@gmail.com</td>
                                        <td><i class="mdi mdi-circle text-success"></i> Active</td>
                                        <td>User</td>
                                        <td class="table-action">
                                            <a href="/edit" class="action-icon"> <i class="mdi mdi-pencil"></i></a>
                                            <a href="" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Online</td>
                                        <td>Garrett Winters</td>
                                        <td>user@gmail.com</td>
                                        <td><i class="mdi mdi-circle text-danger"></i> Inactive</td>
                                        <td>Admin</td>
                                        <td class="table-action">
                                            <a href="/edit" class="action-icon"> <i class="mdi mdi-pencil"></i></a>
                                            <a href="" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Online</td>
                                        <td>Jhone Swerds</td>
                                        <td>user@gmail.com</td>
                                        <td><i class="mdi mdi-circle text-success"></i> Active</td>
                                        <td>Super Admin</td>
                                        <td class="table-action">
                                            <a href="/edit" class="action-icon"> <i class="mdi mdi-pencil"></i></a>
                                            <a href="" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Online</td>
                                        <td>Jhone Swerds</td>
                                        <td>user@gmail.com</td>
                                        <td><i class="mdi mdi-circle text-success"></i> Active</td>
                                        <td>Super Admin</td>
                                        <td class="table-action">
                                            <a href="/edit" class="action-icon"> <i class="mdi mdi-pencil"></i></a>
                                            <a href="" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Online</td>
                                        <td>Jhone Swerds</td>
                                        <td>user@gmail.com</td>
                                        <td><i class="mdi mdi-circle text-success"></i> Active</td>
                                        <td>Super Admin</td>
                                        <td class="table-action">
                                            <a href="/edit" class="action-icon"> <i class="mdi mdi-pencil"></i></a>
                                            <a href="" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Online</td>
                                        <td>Jhone Swerds</td>
                                        <td>user@gmail.com</td>
                                        <td><i class="mdi mdi-circle text-success"></i> Active</td>
                                        <td>Super Admin</td>
                                        <td class="table-action">
                                            <a href="/edit" class="action-icon"> <i class="mdi mdi-pencil"></i></a>
                                            <a href="" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                    <!-- more rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
