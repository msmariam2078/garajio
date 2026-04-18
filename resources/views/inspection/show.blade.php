<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delta Turf Care Job Card</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      font-size: 14px;
    }

    .job-card {
      max-width: 900px;
      margin: auto;
      border: 2px solid #008ac8;
      padding: 20px;
    }

    .header {
      text-align: center;
      margin-bottom: 10px;
    }

    .header h2 {
      color: #008ac8;
      margin: 0;
    }

    .job-no {
      text-align: right;
      font-weight: bold;
      color: #f44336;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    td, th {
      border: 1px solid #ccc;
      padding: 6px;
      text-align: left;
    }

    .section-title {
      background-color: #f0f0f0;
      font-weight: bold;
    }

    .checkbox-group label {
      margin-right: 15px;
    }

    .signature {
      margin-top: 40px;
      display: flex;
      justify-content: space-between;
    }

    .footer-logos {
      display: flex;
      justify-content: space-around;
      margin-top: 30px;
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }

    .footer-logos img {
      height: 40px;
    }
  </style>
</head>
<body>

<div class="job-card">
  <div class="header">
    <h2>Delta Turf Care</h2>
    <p>JOB CARD</p>
  </div>

  <div class="job-no">
    No: <strong>{{$inspection->id}}</strong>
  </div>

  <table>
    <tr>
        <h3 style="background:#eeeeee;text-align:center;width:98%;padding:10px;margin:0">Customer/Machine Details</h3>
    </tr>
    <tr>
      <td>Customer Name:</td>
      <td>{{$customerDetails->full_name}}</td>
      <td>Phone</td>
      <td>+{{$customerDetails->phone_number}}{{$customerDetails->phone_number}}<</td>
    </tr>
    <tr>
      <td>Technician Name:</td>
      <td>ALEX MATHIU</td>
      <td>Date:</td>
      <td>{{\Carbon\Carbon::parse($inspection->created_at)->format('y-m-d')}}</td>
    </tr>
    <tr>
      <td>Make:</td>
      <td>{{$vehicles->vehicle_makes?->make_name}}</td>
      <td>Model No:</td>
      <td>{{$vehicles->vehicle_makes?->make_name}}</td>
    </tr>
    <tr>
      <td>Serial No:</td>
      <td>21052355Y</td>
      <td>Machine Hours:</td>
      <td>{{$inspection->machine_hours}}</td>
    </tr>
  
  </table>
<table>
    <tr>
        <h3 style="background:#eeeeee;text-align:center;width:98%;padding:10px;margin:0">Action Requered</h3>
    </tr>
    <tr>
      <td style="20%">
           Status of Visit:
  <div class="checkbox-group">
    <label><input type="checkbox" checked> Breakdown</label><br>
    <label><input type="checkbox"> Planned Maintenance</label><br>
    <label><input type="checkbox"> Warranty</label><br>
  </div>

      </td>
      <td> 
         Type of Follow-Up:
              <div class="checkbox-group">
                      <label><input type="checkbox"> Quote Only</label><br>
                       <label><input type="checkbox"> Quote & Repair</label><br>
                       <label><input type="checkbox"> Other</label><br>
          </div></td>
      <td>
        <table>
            <tr>
                <td></td>
                <td>Recieved By</td>
            </tr>
             <tr>
                <td>Name</td>
                <td></td>
            </tr>
             <tr>
                <td>Date</td>
                <td></td>
            </tr>
       </table>
      </td>
    
    </tr>
   

  
  </table>
  




  
  <table>
    <tr class="section-title">
      <th>Part Number</th>
      <th>Description</th>
      <th>Qty</th>
    </tr>
    @foreach($quotations as $quotation)
    <tr><td>{{$quotation->item_no}}</td><td>{{$quotation->product_name}}/td><td>{{$quotation->qty}}</td></tr>
   @endforeach
  </table>


  <table>
    
    <tr >
        <div style="background:#eeeeee;text-align:center;width:98%;padding:10px;margin:0">
               Current Status
       </div>  
    </tr>
    <tr>
        <td>
          <div class="checkbox-group">
                <label><input type="checkbox"> Order Completed & Dispatched</label>
               <label><input type="checkbox"> Requires more information</label>
                <label><input type="checkbox"> Incomplete / Pending Parts</label>
         </div>
      </td>
   </tr>
  <tr>
    <td style="height:200px;text-align:top">Remark:</td>
  </tr>
   
  
  
   </table>

 



  <!-- <div class="footer-logos">
    <img src="https://upload.wikimedia.org/wikipedia/en/thumb/9/99/Toro_Company_logo.svg/1200px-Toro_Company_logo.svg.png" alt="TORO" />
    <img src="https://www.green-tek.com/wp-content/uploads/2019/05/GreenTek-Logo-Hor.png" alt="GreenTek" />
    <img src="https://allett.co.uk/cdn/shop/files/Allet_logo.png" alt="ALLETT" />
    <img src="https://www.redeem-x.com/assets/images/logo.png" alt="Redexim Charterhouse" />
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/20/Kioti_logo.svg/1200px-Kioti_logo.svg.png" alt="Kioti" />
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Stihl_Logo.svg/1280px-Stihl_Logo.svg.png" alt="Stihl" />
  </div>
</div> -->
    <section class="buttons-section" style="text-align: center; margin-top: 20px;">

            <button id="email-invoice-btn"
                style="padding: 10px 20px; background-color: #008CBA; color: white; border: none; cursor: pointer; margin-right: 10px;">
                Email Inspection
            </button>
            <button id="print-invoice-btn"
                style="padding: 10px 20px; background-color: #f44336; color: white; border: none; cursor: pointer;">
                Print Inspection
            </button>
        </section>

</body>
</html>
<script>
    document.getElementById("print-invoice-btn").addEventListener("click", function() {
    //console.log(1);
     window.print();
//     var bookingId = {
//         {
//             $booking - > id
//         }
   });
        document.getElementById("email-invoice-btn").addEventListener("click", function() {
        var inspection = @json($inspection->id);
        // Send request to email invoice
        fetch(`/send-inspection-email/${inspection}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("inspection has been emailed successfully.");
                } else {
                    alert("Failed to email inspection.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("There was an error emailing the inspection.");
            });
    });
</script>
