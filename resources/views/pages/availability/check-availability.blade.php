@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">User Availability</h4>
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
                                <div class="mb-3">
                                    <label for="title" class="form-label">Select Title</label>
                                    <select class="form-select" id="title" name="title" required>
                                        <option value="">Title 1</option>
                                        <option value="">Title 2</option>
                                        <option value="">Title 3</option>
                                    </select>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="/" class="btn btn-primary">
                                        Check Availability
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

    <!-- Form row -->
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-muted mb-3">
                        User Availability Timeline
                    </h3>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Category 1</option>
                                        <option value="">Category 2</option>
                                        <option value="">Category 3</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Meeting Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>

                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label for="date" class="form-label">Pick Date</label>
                                        <input type="date" class="form-control" id="date" name="date" required>
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label for="startTime" class="form-label">Pick a Start Time</label>
                                        <input type="time" class="form-control" id="startTime" name="startTime" required>
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label for="endTime" class="form-label">Pick an End Time</label>
                                        <input type="time" class="form-control" id="endTime" name="endTime" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="locaion" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">Meeting Method</label>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="meetingMethod" id="online"
                                            value="online" required>
                                        <label class="form-check-label" for="online">Online</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="meetingMethod" id="offline"
                                            value="offline">
                                        <label class="form-check-label" for="offline">Offline</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="url" class="form-label">Meeting URL</label>
                                    <input type="text" class="form-control" id="url" name="url"
                                        placeholder="https://example.com/meeting" required>
                                </div>
                                <div>
                                    <a href="/schedule-meeting" class="btn btn-primary">
                                        Add Members
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
