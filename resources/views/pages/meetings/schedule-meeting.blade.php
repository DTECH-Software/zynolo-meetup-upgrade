@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Schedule Meeting</h4>
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
                        Nominated Attendees and Schedule Meeting.
                    </p>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="form-row-preview">
                            <form>
                                <div class="mb-3">
                                    <label for="attendees" class="form-label">Select Attendees</label>
                                    <select id="attendees" class="form-select" multiple>
                                        <option value="Dushan">Dushan Wijesundara</option>
                                        <option value="Karanika">Karanika Christie</option>
                                        <option value="Buddhi">Buddhi Kasun</option>
                                        <option value="Nalaka">Nalaka Mudannayaka</option>
                                        <option value="Indeewara">Indeewara Jayasuriya</option>
                                        <option value="SuperAdmin">Super Admin</option>
                                        <option value="Kithmini">Kithmini Ranathunga</option>
                                        <option value="Tineth">Tineth Vihanga</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control">
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control">
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="time" class="form-label">End Time</label>
                                    <input type="date" class="form-control">
                                </div>


                                <div class="mb-3 col-md-12">
                                    <label for="message" class="form-label">Additional Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" placeholder="Enter your message here"></textarea>
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
                    <h2 class="fw-bold mb-3">Meeting Timeline</h2>

                    <!-- Date & Time -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date & Time</label>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <input type="date" class="form-control" style="max-width: 180px;">
                            <span class="badge bg-success px-3 py-2">From</span>
                            <input type="time" class="form-control" style="max-width: 100px;">
                            <span class="badge bg-primary px-3 py-2">To</span>
                            <input type="time" class="form-control" style="max-width: 100px;">
                        </div>
                    </div>

                    <!-- Meeting Title -->
                    <div class="border rounded p-3 mb-3">
                        <h5 class="mb-2">ee4</h5>
                        <hr>
                        <p class="mb-1">
                            <strong>To :</strong> dushanc@dtech.lk, karanikac@dtechlk.com, kasunm@dtech.lk,
                            nalakam@dtechlk.com, indeewaraj@dtechlk.com
                        </p>
                        <p class="mb-1">
                            <strong>Location :</strong> Meeting Room
                        </p>
                        <div class="mb-2">
                            <label class="form-label fw-bold mb-1">Meeting Link :</label>
                            <input type="text" class="form-control" placeholder="Enter meeting link">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Schedule Meeting</button>
                        <button type="button" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>

            </div> <!-- end card-->
        </div>
    </div>
    <!-- end row -->
@endsection
