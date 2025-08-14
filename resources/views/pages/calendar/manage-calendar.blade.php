@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Manage Calendar</h4>
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
                        Add Holiday Types
                    </h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div class="mb-3">
                                    <label for="holiday" class="form-label">Name of the Holiday</label>
                                    <input type="text" class="form-control" id="holiday" name="holiday" required>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="/" class="btn btn-primary">
                                        Add Holiday
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
                    <h4 class="text-muted mb-3">
                        Add Meeting Categories
                    </h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Name of the Category</label>
                                    <input type="text" class="form-control" id="category" name="category" required>
                                </div>

                                <div class="mb-3">
                                    <label for="attendees" class="form-label">Select Attendees</label>
                                    <select class="form-select" id="attendees" name="attendees" required>
                                        <option value="">User 1</option>
                                        <option value="">User 2</option>
                                        <option value="">User 3</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="title" class="form-label">Pick the Category Colour</label>
                                    <input type="color" class="form-control form-control-color" id="categoryColor"
                                        name="categoryColor" value="#5d95ca" required>
                                </div>

                                <div>
                                    <a href="" class="btn btn-primary">
                                        Add Meeting Category
                                    </a>
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
