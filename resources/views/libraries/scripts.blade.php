<!-- Vendor js -->
<script src="assets/js/vendor.min.js"></script>

<!-- Daterangepicker js -->
<script src="assets/vendor/daterangepicker/moment.min.js"></script>
<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>

<!-- Apex Charts js -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

<!-- Vector Map js -->
<script src="assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/vendor/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

<!-- Dashboard App js -->
<script src="assets/js/pages/demo.dashboard.js"></script>

<!-- App js -->
<script src="assets/js/app.min.js"></script>

<!-- Bootstrap Datepicker js -->
<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<!-- Chart js -->
<script src="assets/vendor/chart.js/chart.min.js"></script>

<!-- Projects Analytics Dashboard App js -->
<script src="assets/js/pages/demo.dashboard-projects.js"></script>

<!-- Fullcalendar js -->
<script src="assets/vendor/fullcalendar/main.min.js"></script>

<!-- Calendar App Demo js -->
<script src="assets/js/pages/demo.calendar.js"></script>

<!-- Todo js -->
<script src="assets/js/ui/component.todo.js"></script>

<!-- Widgets Demo js -->
<script src="assets/js/pages/demo.widgets.js"></script>

 <!-- dragula js-->
 <script src="assets/vendor/dragula/dragula.min.js"></script>

 <!-- demo js -->
 <script src="assets/js/ui/component.dragula.js"></script>

<!-- quill js -->
<script src="assets/vendor/quill/quill.min.js"></script>

<!-- Init js-->
<script src="assets/js/pages/demo.tasks.js"></script>

<!--Display Sri Lankan Time-->
<script>
    function updateTime() {
        const timeDisplay = document.getElementById("timeDisplay");
        const options = { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
        const currentTime = new Date().toLocaleTimeString(undefined, options).toUpperCase();
        timeDisplay.textContent = currentTime;
    }

    // Update the time immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);
</script>

<script>
    // JavaScript to handle file selection
    document.getElementById('fileInput').addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        document.getElementById('fileName').textContent = `Selected file: ${file.name}`;
      }
    });
</script>

<!-- Code Highlight js -->
<script src="assets/vendor/highlightjs/highlight.pack.min.js"></script>
<script src="assets/vendor/clipboard/clipboard.min.js"></script>
<script src="assets/js/hyper-syntax.js"></script>


<!-- Datatables js -->
<script src="assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="assets/vendor/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js"></script>
<script src="assets/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>

<!-- Datatable Demo Aapp js -->
<script src="assets/js/pages/demo.datatable-init.js"></script>

<!-- All Country List Using Online API -->

<script>
  // Fetch the list of countries from the API
  fetch('https://restcountries.com/v3.1/all')
      .then(response => response.json())
      .then(data => {
          const countrySelect = document.getElementById('inputCountry');
          data.forEach(country => {
              // Create a new option element for each country
              const option = document.createElement('option');
              option.value = country.name.common;
              option.textContent = country.name.common;
              countrySelect.appendChild(option);
          });
      })
      .catch(error => console.error('Error fetching countries:', error));
</script>

<!-- Theme Config Js -->
<script src="assets/js/hyper-config.js"></script>




