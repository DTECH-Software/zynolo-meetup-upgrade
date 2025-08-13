@extends('layouts.app')

@section('content')

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Edit User</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<!-- Form row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <p class="text-muted font-14">
                Please fill out all required fields accurately.
                </p>
                <div class="tab-content">
                    <div class="tab-pane show active" id="form-row-preview">
                        <form>
                            <div class="row g-2">
                                <div class="mb-3 col-md-12">
                                    <label for="inputName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="" placeholder="Enter User Name">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="example-select">
                                    <option>Choose</option>
                                 </select>
                            </div>

                            <div class="mb-3">
                                <label for="Role" class="form-label">Role</label>
                                <select class="form-select" id="example-select">
                                    <option>Choose</option>
                                 </select>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="submit" class="btn btn-secondary">Cancel</button>
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