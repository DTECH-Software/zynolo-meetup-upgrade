@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Send Availability Email</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Form row -->
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted font-14">
                        Send an email to the selected attendees with the availability details.
                    </p>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div id="timeSlotsWrapper">
                                    <!-- Initial Time Slot -->
                                    <div class="border position-relative"
                                        style="border-radius: 10px; padding: 1rem; margin-bottom: 1rem;">
                                        <button type="button" class="btn btn-sm btn-danger position-absolute"
                                            style="top: 10px; right: 10px; display: none;"
                                            onclick="removeTimeSlot(this)">X</button>
                                        <h5 class="mb-2 text-primary">Time Slot 01</h5>
                                        <div class="mb-3 col-md-12">
                                            <label for="date" class="form-label">Date</label>
                                            <input type="date" class="form-control">
                                        </div>

                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label for="startTime" class="form-label">Start Time</label>
                                                <input type="time" class="form-control">
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label for="endTime" class="form-label">End Time</label>
                                                <input type="date" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <button type="button" class="btn btn-primary" onclick="addTimeSlot()">Add Time
                                        Slot</button>
                                </div>

                                <div class="mb-3">
                                    <label for="category" class="form-label">Select User Emails</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Email 1</option>
                                        <option value="">Email 2</option>
                                        <option value="">Email 3</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold mb-1">Type CC Email</label>
                                    <input type="text" class="form-control"
                                        placeholder="user1@gmail.com,user2@gmail.com">
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary">Validate Meeting Details</button>
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
                    <h2 class="fw-bold mb-3">Meeting Details</h2>

                    <!-- Meeting Title -->
                    <div class="border" style="border-radius: 10px; padding: 1rem; margin-bottom: 1rem;">
                        <h4 class="mb-2">Meeting Title</h4>
                        <hr>
                        <p class="mb-1">
                            Description here...
                        </p>

                    </div>

                </div>

            </div> <!-- end card-->
        </div>
    </div>
    <!-- end row -->
    <script>
        let timeSlotCount = 1;

        function addTimeSlot() {
            timeSlotCount++;
            const wrapper = document.getElementById('timeSlotsWrapper');

            const newSlot = document.createElement('div');
            newSlot.className = 'border position-relative';
            newSlot.style.cssText = 'border-radius: 10px; padding: 1rem; margin-bottom: 1rem;';

            newSlot.innerHTML = `
        <button type="button" class="btn btn-sm btn-danger position-absolute" 
            style="top: 10px; right: 10px;"
            onclick="removeTimeSlot(this)">X</button>
        <h5 class="mb-2 text-primary">Time Slot ${String(timeSlotCount).padStart(2, '0')}</h5>
        <div class="mb-3 col-md-12">
            <label class="form-label">Date</label>
            <input type="date" class="form-control">
        </div>
        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label">Start Time</label>
                <input type="time" class="form-control">
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">End Time</label>
                <input type="date" class="form-control">
            </div>
        </div>
    `;

            wrapper.appendChild(newSlot);
        }

        function removeTimeSlot(button) {
            button.parentElement.remove();
        }
    </script>
@endsection
