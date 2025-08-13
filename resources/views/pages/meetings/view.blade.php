@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-2">
            <h4 class="page-title mb-0 me-4">Meetings</h4>
            <button type="button" class="btn btn-primary mt-2 px-3" data-bs-toggle="modal" data-bs-target="#newMeetingModal">
                <i class="uil uil-schedule"></i> New Meeting
            </button>
        </div>
    </div>
    <!-- end page title -->
    <div class="col-md-12 d-flex mb-3">
        <div class="card flex-fill d-flex flex-column h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">Today's Meetings</h4>

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
                        <div class="progress-bar" role="progressbar" style="width: 91%" aria-valuenow="91" aria-valuemin="0"
                            aria-valuemax="100"></div>
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
    </div>
    <!-- Add User Modal -->
    <div class="modal fade" id="newMeetingModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addUserForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Schedule Meeting</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- User details form fields -->
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
                        <div class="mb-3">
                            <label for="date" class="form-label">Pick Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>


                        <div class="mb-3">
                            <label for="startTime" class="form-label">Pick a Start Time</label>
                            <input type="time" class="form-control" id="startTime" name="startTime" required>
                        </div>

                        <div class="mb-3">
                            <label for="endTime" class="form-label">Pick an End Time</label>
                            <input type="time" class="form-control" id="endTime" name="endTime" required>
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

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="/schedule-meeting" class="btn btn-primary">
                            Add Member
                        </a>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
