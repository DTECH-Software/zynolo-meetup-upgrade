@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Permission Administration</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Form row -->
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-muted mb-3">
                        Add User Role
                    </h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="/" class="btn btn-primary">
                                        Add Role
                                    </a>
                                </div>
                            </form>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-muted mb-3">
                        Add Permission
                    </h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Permission Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="/" class="btn btn-primary">
                                        Add Permission
                                    </a>
                                </div>
                            </form>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->

    <div class="col-md-12 d-flex">
        <div class="card flex-fill d-flex flex-column h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">User Roles</h4>
            </div>

            <div class="card-body pt-2">
                <div class="row d-flex">

                    <div class="col-12 col-md-3">
                        <a href="/user-permission">
                            <div class="card text-bg-info overflow-hidden">
                                <div class="card-body p-1">
                                    <div class="toll-free-box text-center">
                                        <h5 class="text-reset"> <i class="mdi mdi-account-key"
                                                style="font-size: 50px"></i>Admin
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-3">
                        <a href="/user-permission">
                            <div class="card text-bg-info overflow-hidden">
                                <div class="card-body p-1">
                                    <div class="toll-free-box text-center">
                                        <h5 class="text-reset"> <i class="mdi mdi-account-key"
                                                style="font-size: 50px"></i>User
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
                <!-- end row-->
            </div>
        </div>
    </div>

    <!-- Form row -->
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-muted mb-3">
                        Permissions
                    </h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div class="border" style="border-radius: 10px; padding: 1rem; margin-bottom: 1rem;">
                                    <p><i class="mdi mdi-lock me-1"></i>Permission Administration</p>
                                    <p><i class="mdi mdi-lock me-1"></i>Calendar Management</p>
                                    <p><i class="mdi mdi-lock me-1"></i>Add Users</p>
                                </div>
                            </form>
                        </div>
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
@endsection
