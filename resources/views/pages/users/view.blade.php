@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-2">
            <h4 class="page-title mb-0 me-4">View Users</h4>
            <button type="button" class="btn btn-success mt-2 px-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="mdi mdi-account"></i> Add User
            </button>
        </div>

    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted font-14">
                        Access and manage user profiles effortlessly. Ensure data accuracy and support user needs
                        effectively.
                    </p>
                    <div class="tab-pane show active" id="alt-pagination-preview">
                        <table id="alternative-page-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                <tr>
                                    <td class="table-user">
                                        <img src="assets/images/avatar.jpg" alt="table-user" class="me-2 rounded-circle" />
                                    </td>
                                    <td>1</td>
                                    <td>Tiger Nixon</td>
                                    <td>user@gmail.com</td>
                                    <td><i class="mdi mdi-circle text-success"></i> Active</td>
                                    <td>User</td>
                                    <td class="table-action">
                                        <a href="#" class="action-icon" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <a href="" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-user">
                                        <img src="assets/images/avatar.jpg" alt="table-user" class="me-2 rounded-circle" />
                                    </td>
                                    <td>2</td>
                                    <td>Garrett Winters</td>
                                    <td>user@gmail.com</td>
                                    <td><i class="mdi mdi-circle text-danger"></i> Inactive</td>
                                    <td>Admin</td>
                                    <td class="table-action">
                                        <a href="#" class="action-icon" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <a href="" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="table-user">
                                        <img src="assets/images/avatar.jpg" alt="table-user" class="me-2 rounded-circle" />
                                    </td>
                                    <td>3</td>
                                    <td>Jhone Swerds</td>
                                    <td>user@gmail.com</td>
                                    <td><i class="mdi mdi-circle text-success"></i> Active</td>
                                    <td>Super Admin</td>
                                    <td class="table-action">
                                        <a href="#" class="action-icon" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <a href="" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div> <!-- end preview-->
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div> <!-- end row-->
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addUserForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- User details form fields -->
                        <div class="mb-3">
                            <label for="userName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="userName" name="userName" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="userEmail" required>
                        </div>

                        <div class="mb-3">
                            <label for="userRole" class="form-label">Role</label>
                            <select class="form-select" id="userRole" name="userRole" required>
                                <option value="User">User</option>
                                <option value="Admin">Admin</option>
                                <option value="Super Admin">Super Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="userAvatar" class="form-label">User Image</label>
                            <input class="form-control" type="file" id="userAvatar" name="userAvatar"
                                accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editUserForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editUserName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editUserName" name="userName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editUserEmail" name="userEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserStatus" class="form-label">Status</label>
                            <select class="form-select" id="editUserStatus" name="userStatus" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editUserRole" class="form-label">Role</label>
                            <select class="form-select" id="editUserRole" name="userRole" required>
                                <option value="User">User</option>
                                <option value="Admin">Admin</option>
                                <option value="Super Admin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#addUserForm').on('submit', function(e) {
                e.preventDefault();
                // collect form data
                let data = $(this).serialize();

                // example ajax POST to your route
                $.post('/users/store', data, function(response) {
                    // handle response - close modal, reload page, update table, etc
                    $('#addUserModal').modal('hide');
                    alert('User added successfully');
                    location.reload(); // or update table dynamically
                }).fail(function() {
                    alert('Error adding user.');
                });
            });
        });
    </script>
@endsection
