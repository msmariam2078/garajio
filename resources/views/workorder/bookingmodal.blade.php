<div class="modal-body" id="modal">
   
        <input type="hidden" name="technician_id" id="technician_id" value="{{ $technicianId }}">
        <input type="hidden" name="work_order_id" id="workOrder_id" value="{{ $workOrderId }}">

        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="fromDate">From Date:</label>
                <input type="date" class="form-control" name="from_date" id="from_date" required>
            </div>
            <div class="form-group col-md-12">
                <label for="fromTime">From Time:</label>
                <input type="time" class="form-control" name="from_time" id="from_time" required>
            </div>
            <div class="form-group col-md-12">
                <label for="toTime">To Time:</label>
                <input type="time" class="form-control" name="to_time" id="to_time" required >
            </div>
        </div>
        <button type="submit" class="btn btn-primary" id="confirm">Confirm Booking</button>
  
</div>
<script>
	 function collectFormData() {
            return {
                from_date: $('#from_date').val(),
                from_time: $('#from_time').val(),
                to_time: $('#to_time').val(),
				technician_id: $('#technician_id').val(),
				workOrder_id: $('#workOrder_id').val(),
      
            };
        }
$(document).on("click", "#confirm", function() {
   let formData = collectFormData();
  
           $.ajax({
                url: "{{ route('allocate.technician') }}",
                method: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                  
                        alert(response.message);
                        $("#customModal").modal('hide');
                    
                   
                },
               
            });

 
   
});
	$(document).on("click", ".customModal", function() {   
		$(".modaldemo1").modal("hide");

		setTimeout(() => {
			$("#yourModalId").modal("show");
		}, 500);
	});

	$(document).on("shown.bs.modal", function() {
		const today = new Date();

		const formatDate = (date) => {
			const year = date.getFullYear();
			const month = (date.getMonth() + 1).toString().padStart(2, "0");
			const day = date.getDate().toString().padStart(2, "0");
			return `${year}-${month}-${day}`;
		};

		$("#from_date").val(formatDate(today));

		const now = new Date();
		now.setMinutes(now.getMinutes()); // Add 10 minutes

		const formatTime = (date) => {
			const hours = date.getHours().toString().padStart(2, "0");
			const minutes = date.getMinutes().toString().padStart(2, "0");
			return `${hours}:${minutes}`;
		};

		$("#from_time").val(formatTime(now));

		function updateToTime() {
			let fromTime = $("#from_time").val();
			if (fromTime) {
				let [hours, minutes] = fromTime.split(":").map(Number);
				minutes += 30;
				if (minutes >= 60) {
					hours += 1;
					minutes -= 60;
				}
				const toTime = `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}`;
				$("#to_time").val(toTime);
			}
		}

		$("#from_time").on("input", updateToTime);
		
		updateToTime();
	});
</script>

<script>
	 // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split('T')[0];
    // Set it as the minimum date
    document.getElementById("from_date").setAttribute("min", today);
</script>
