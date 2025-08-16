@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">User Permissions</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Form row -->
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-muted mb-3">
                        Admin
                    </h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div style="overflow-y:auto; -ms-overflow-style:none; scrollbar-width:none;"
                                    onscroll="this.style.scrollbarWidth='none'">
                                    <style>
                                        div[style*='overflow-y:auto']::-webkit-scrollbar {
                                            display: none;
                                        }
                                    </style>
                                    <table class="table table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Permissions</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Permission Administration</td>
                                                <td class="text-center"><input type="checkbox" name="permission"></td>
                                            </tr>
                                            <tr>
                                                <td>Calendar Management</td>
                                                <td class="text-center"><input type="checkbox" name="permission"></td>
                                            </tr>
                                            <tr>
                                                <td>Add Users</td>
                                                <td class="text-center"><input type="checkbox" name="permission"></td>
                                            </tr>
                                            <!-- more rows -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 d-flex flex-wrap gap-2">
                                    <a href="/" class="btn btn-primary">
                                        Assign Permissions
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
@endsection
