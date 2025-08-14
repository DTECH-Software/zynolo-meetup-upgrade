@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Send Availability Request</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Form row -->
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted font-14">
                        Check the availability of users and send a request to them.
                    </p>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div class="mb-3 col-md-12">
                                    <label for="title" class="form-label">Availability Title</label>
                                    <input type="text" class="form-control">
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="message" class="form-label">Availability Description</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter your message here"></textarea>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="/send-email" class="btn btn-primary">
                                        Create Request
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
