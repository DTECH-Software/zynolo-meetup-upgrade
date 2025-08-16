@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Profile</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5 d-flex mb-4">
            <div class="card text-center flex-fill d-flex flex-column h-100">
                <div class="card-body">
                    <img src="assets/images/user.png" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                    <h4 class="mb-0 mt-2">Tineth Pathirage</h4>
                    <p class="font-14 mb-1">Associate Software Engineer</p>
                    <span class="badge badge-secondary-lighten py-1 px-2 font-13"><i class="mdi mdi-home me-1"></i>D Tech
                        (Pvt)
                        Ltd</span>


                    <div class="text-start mt-3">
                        <h4 class="font-13 text-uppercase">About Me :</h4>
                        <p class="text-muted font-13 mb-3">
                            Hi I'm Tineth Pathirage, has been the industry's standard dummy text ever since the
                            1500s, when an unknown printer took a galley of type.
                        </p>
                        <p class="text-muted mb-2 font-13"><strong>Full Name :</strong> <span class="ms-2">Alexander
                                Pierce</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Mobile :</strong><span class="ms-2">(+94)
                                123 1234</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span
                                class="ms-2 ">tineth@email.domain</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Location :</strong> <span class="ms-2">Colombo, Sri
                                Lanka</span></p>
                    </div>

                    <ul class="social-list list-inline mt-3 mb-0">
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i
                                    class="mdi mdi-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i
                                    class="mdi mdi-google"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i
                                    class="mdi mdi-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i
                                    class="mdi mdi-github"></i></a>
                        </li>
                    </ul>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7 d-flex mb-4">
            <div class="card flex-fill d-flex flex-column h-100">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="settings">
                            <form>
                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Personal
                                    Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="firstname"
                                                placeholder="Enter first name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="useremail"
                                                placeholder="Enter email">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <h5 class="mb-3 text-uppercase bg-light rounded p-2"><i class="mdi mdi-lock me-1"></i></i>
                                    Update Password</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="companyname" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="companyname"
                                                placeholder="Enter current password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="companyname" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="companyname"
                                                placeholder="Enter new password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cwebsite" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="cwebsite"
                                                placeholder="Enter confirm password">
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success mt-2 px-3"><i
                                            class="mdi mdi-content-save"></i>
                                        Save</button>
                                </div>
                            </form>
                        </div>
                        <!-- end settings content-->

                    </div> <!-- end tab-content -->
                </div> <!-- end card body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div>
    <!-- end row-->
@endsection
