@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Add Users</h4>
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
                                <div class="mb-3">
                                    <label for="inputEmployee" class="form-label">Employee</label>
                                    <input type="text" class="form-control" id="inputEmployee" list="employeeList"
                                        placeholder="Add Employee">
                                    <datalist id="employeeList"></datalist>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="inputName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="" placeholder="Enter User Name">
                                </div>

                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" placeholder="Enter email">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with
                                        anyone else.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1"
                                        placeholder="Password">
                                </div>

                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Re Password</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1"
                                        placeholder="Re Password">
                                </div>

                                <div class="mb-3">
                                    <label for="inputRole" class="form-label">Add User Role</label>
                                    <select id="inputState" class="form-select">
                                        <option>Select Role</option>
                                        <option>Option 1</option>
                                        <option>Option 2</option>
                                        <option>Option 3</option>
                                    </select>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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

<!-- JavaScript Code For Auto Complete Input Field -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const employeeInput = document.getElementById("inputEmployee");
        const employeeList = document.getElementById("employeeList");

        // Sample data
        const employees = [
            "John Doe",
            "John Smith",
            "John Kuweth",
            "John Wick",
            "Michael Brown",
            "Emily Davis",
            "Sarah Wilson",
            "Laura Lee",
            "James Anderson"
        ];

        // Function to filter and display matching data
        function filterEmployees(query) {
            // Clear existing options
            employeeList.innerHTML = "";

            // Filter employees based on the query
            const filtered = employees.filter(employee =>
                employee.toLowerCase().includes(query.toLowerCase())
            );

            // Add filtered results to datalist
            filtered.forEach(employee => {
                const option = document.createElement("option");
                option.value = employee;
                employeeList.appendChild(option);
            });

            // If the input matches an employee exactly, keep showing suggestions
            if (filtered.includes(query)) {
                const option = document.createElement("option");
                option.value = query;
                employeeList.appendChild(option);
            }
        }

        // Add event listener for typing in the input
        employeeInput.addEventListener("input", (event) => {
            filterEmployees(event.target.value);
        });

        // Add event listener to close suggestions on selection
        employeeInput.addEventListener("change", () => {
            // Check if the input value matches any of the options
            const selectedValue = employeeInput.value;
            const options = Array.from(employeeList.options);

            if (options.some(option => option.value === selectedValue)) {
                // Clear datalist options to "close" it
                employeeList.innerHTML = "";
            }
        });
    });
</script>
