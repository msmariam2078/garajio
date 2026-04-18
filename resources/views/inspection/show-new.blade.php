<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Equipment Audit Checklist</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    :root{
      --ink:#0b1220;
      --muted:#5b667a;
      --line:#d8dee9;
      --accent:#0e7afe;
      --bg:#ffffff;
      --paper:#f7f9fc;
    }
    *{box-sizing:border-box}
    html,body{height:100%}

    body{
      margin:0;
      font:14px/1.4 system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji";
      color:var(--ink);
      background:var(--paper);
    }
    section{
    margin:0 60px;
    }
    .container{
      max-width:1000px;
      margin:24px auto 64px;
      
      background:var(--bg);
      border:1px solid var(--line);
      border-radius:14px;
      box-shadow:0 6px 20px rgba(2,6,23,.06);
    }
     header{
      /* display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:16px
     */
      height:15%;
      padding:20px;
    
    }
    .title{
      font-size:18px;;letter-spacing:.3px;text-align:center;font-weight:600;;color:#002387;
    }
    .actions{display:flex;gap:8px;flex-wrap:wrap}
    button{
      appearance:none;border:1px solid var(--line);background:#fff;color:var(--ink);
      padding:8px 12px;border-radius:10px;cursor:pointer;font-weight:600
    }
    button.primary{border-color:transparent;background:var(--accent);color:#fff}
    button:active{transform:translateY(1px)}

    h2{font-size:18px;margin:22px 0 10px;color:black;}
    h3{font-size:15px;margin:18px 0 8px;color:var(--muted);text-transform:uppercase;letter-spacing:.08em}

    .grid{
      display:grid;gap:10px
    }
    .grid.cols-2{grid-template-columns:1fr 1fr}
    .grid.cols-3{grid-template-columns:repeat(3,1fr)}
    .grid.cols-4{grid-template-columns:repeat(4,1fr)}
    .field{display:flex;gap:8px;align-items:center}
    .field label{min-width:140px;color:var(--muted)}
    .field input{flex:1;min-width:0;padding:8px 10px;border:1px solid var(--line);border-radius:8px}

    .card{border:1px solid var(--line);border-radius:12px;padding:14px;background:#fff}

    table{width:100%;border-collapse:separate;border-spacing:0;border:1px solid black;border-radius:12px;overflow:hidden}
    thead th{background:#f3f6fb;text-align:left;font-weight:700;color:#2b3340}
    th,td{padding:10px 12px;border:1px solid black ;vertical-align:top;font-weight:600;}
    tbody tr:last-child td{border-bottom:none}
    td.small{width:120px}
    td.center{text-align:center}
    input[type="text"], select, textarea{width:100%;padding:8px 10px;border:1px solid var(--line);border-radius:8px;resize:vertical}
    textarea{min-height:36px}

    .legend{display:flex; flex-direction: column; flex-wrap:wrap;color:var(--muted)}
    .legend span{display:inline-flex;align-items:center;gap:6px}
    .pill{display:inline-block;padding:.15rem .5rem;border-radius:999px;border:1px solid var(--line);font-weight:800;font-size:12px;color:#black}

    .cert-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:10px}

    .note{color:var(--muted);font-size:12px}
    @media print {
            .buttons-section {
                display: none;
            }
            header{
                 margin:0 60px;
            }
             /* .footer-item {
              display:flex;
              flex-direction:column;
              justify-content:center;
              align-items:center;
              gap:0px;
              padding:0;
              margin:0;
           
             font-size:10px;
             
          
    } */
            /* footer{
              display:none;
            } */
          }
     footer {
      position: relative;
      background: linear-gradient(90deg, #0e5ba8, #1ea6c5);
      color: #fff;
     padding: 50px 40px 40px;
     margin-top:40px;
      overflow: hidden;
    }

    /* Wave shape */
    footer::before {
      content: "";
      position: absolute;
      top: -50px;
      left: 0;
      width: 100%;
      height: 100px;
      background: #0e5ba8;
      border-top-right-radius: 80% 100px;
      border-top-left-radius: 30% 100px;
    }

    .footer-content {
      position: relative;
      /* display: flex;
      flex-wrap: wrap;
      justify-content: space-between; */
      padding-bottom:15px;
      gap: 20px;
      z-index: 1;
    }

    .footer-content h3,h2{
        color:white;
      margin: 0 0 10px;
      font-size: 18px;
      font-weight: bold;
    }

    .footer-item {
      flex: 1 1 250px;
      display: flex;
     justify-content:space-between;
      gap: 30px;
      font-size: 20px;
    }

    .footer-item i {
      margin-right: 8px;
    }

    .footer-link {
      display: flex;
      align-items: center;
    }

    .footer-link a {
      color: #fff;
      text-decoration: none;
    }

    .footer-link a:hover {
      text-decoration: underline;
    }

    .footer-bottom {
      text-align: center;
      font-size: 12px;
      margin-top: 20px;
      opacity: 0.8;
    }

    @media (max-width: 760px){
      .grid.cols-2,.grid.cols-3,.grid.cols-4{grid-template-columns:1fr}
      .field{flex-direction:column;align-items:stretch}
      .cert-grid{grid-template-columns:1fr}
    }

    /* Print-friendly styles */
    @media print{
      body{background:#fff}
      .container{box-shadow:none;border:none;margin:0;padding:0;max-width:100%}
      .grid.cols-2{grid-template-columns:1fr 1fr}
      header .actions{display:none}
      img{width:150;
      height:50px;}
      a[href]:after{content:""}
    }
  </style>
</head>
<body>
  <div class="container" >
    <header>
    <img src="{{URL::asset('/assets/invoice.png')}}"/>
      <div class="title">Equipment Audit Checklist</div>
      <!-- <div class="actions">
        <button type="button" onclick="window.print()" class="primary">Print</button>
        <button type="button" onclick="saveAsJSON()">Save as JSON</button>
        <button type="button" onclick="clearForm()">Clear</button>
      </div> -->
    </header>

    <!-- General Information -->
    <section class="card">
      <h3>General Information</h3>
      <div class="grid cols-2">
        <div class="field"><label for="client">Client/Site</label><input id="client" name="client" type="text" value="{{$customerDetails->full_name}}" placeholder="" /></div>
        <div class="field"><label for="date">Date</label><input id="date" name="date" type="date" value="{{\Carbon\Carbon::parse($inspection->created_at)->format('Y-m-d') ?? ''}}"/></div>
        <div class="field"><label for="machineType">Machine Type</label><input id="machineType" name="machineType" type="text" /></div>
        <div class="field"><label for="hours">Hours</label><input id="hours" name="hours" type="text" value="{{$inspection->machine_hours}}"/></div>
        <div class="field"><label for="model">Model</label><input id="model" name="model" type="text"  value="{{$inspection->vehicledetails?->vehicle_models?->model_name}}"/></div>
        <div class="field"><label for="sn">SN</label><input id="sn" name="sn" type="text" value="#INS00{{$inspection->id}}"/></div>
      </div>
    </section>

    <!-- Condition Summary -->
    <section>
      <h3>Condition Summary</h3>
      <table>
        <thead>
          <tr>
            <th style="width:180px">Category</th>
            <th class="small">Condition</th>
            <th>Comments</th>
            <th style="width:200px">Recommended Action</th>
          </tr>
        </thead>
        <tbody>
          
          <tr>
            <td>{{$inspection->condition_summary}}</td>
            <td><input type="text" placeholder="Good working condition" /></td>
            <td><input type="text" placeholder="{{$inspection->condition_comment}}" /></td>
            <td><input type="text" value="No repairs required" /></td>
          </tr>
          <tr>
      
        </tbody>
      </table>
       <h3 style="margin-top:20px;">Detailed Audit Checklist</h3>
    </section>

    <!-- Detailed Audit Checklist -->
    <!-- <section>
      <h2>Detailed Audit Checklist</h2> -->

      <!-- helper legend -->
      <!-- <div class="legend" style="margin:10px 0 14px">
        <span>Condition:</span>
        <span><span class="pill">E</span> Excellent</span>
        <span><span class="pill">G</span> Good</span>
        <span><span class="pill">F</span> Fair</span>
        <span><span class="pill">P</span> Poor</span>
      </div> -->

      <!-- Engine -->
      
       @foreach($groups as $group)
       <section>
      <h3>{{$group->code}}</h3>
      <table>
        <thead>
          <tr>
            <th>Component</th>
            <th class="small">Condition (E/G/F/P)</th>
            <th class="small">Maintenance Required (Yes/No)</th>
            <th>Comments</th>
          </tr>
        </thead>
        <tbody>
          @foreach($group->mainpoints as $point)
          <tr><td>{{$point->point_name}}</td>
          <td><input type="text" value="{{$point->condition}}"/></td>
          <td><input type="text" value="{{$point->maintenance_required}}"/></td>
          <td><textarea>{{$point->comment}}</textarea></td></tr>
         @endforeach
        </tbody>
      </table>
</section>
      @endforeach

      <!-- Transmission/Drive System
      <h3>Transmission / Drive System</h3>
      <table>
        <thead>
          <tr>
            <th>Component</th>
            <th class="small">Condition (E/G/F/P)</th>
            <th class="small">Maintenance Required (Yes/No)</th>
            <th>Comments</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Hydraulic fluid level</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Hydraulic hoses and fittings</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Drive belts</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Drive chain</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Pulleys and sprockets</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Transmission fluid</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Clutch function</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Differential and axle lubrication</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Gearbox condition</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Unusual noise during operation</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Hydraulic pumps and motors</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
        </tbody>
      </table> -->

      <!-- Cutting Deck -->
      <!-- <h3>Cutting Deck</h3>
      <table>
        <thead>
          <tr>
            <th>Component</th>
            <th class="small">Condition (E/G/F/P)</th>
            <th class="small">Maintenance Required (Yes/No)</th>
            <th>Comments</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Blade sharpness</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Blade condition</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Blade rotation</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Deck leveling</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Cutting height adjustment</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Deck lift system (hydraulic or manual)</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Deck cleaning and debris removal</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Deck suspension</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Anti-scalp rollers (if applicable)</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Spindle and pulley condition</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Deck height calibration accuracy</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
        </tbody>
      </table> -->

      <!-- Hydraulic System -->
      <!-- <h3>Hydraulic System</h3>
      <table>
        <thead>
          <tr>
            <th>Component</th>
            <th class="small">Condition (E/G/F/P)</th>
            <th class="small">Maintenance Required (Yes/No)</th>
            <th>Comments</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Hydraulic fluid level</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Hydraulic filter</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Hydraulic lines and hoses</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Hydraulic pumps</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Cylinder condition</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Valve function</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Foaming/contamination in hydraulic fluid</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
        </tbody>
      </table> -->

      <!-- Operator Cabin/Seat -->
      <!-- <h3>Operator Cabin / Seat</h3>
      <table>
        <thead>
          <tr>
            <th>Component</th>
            <th class="small">Condition (E/G/F/P)</th>
            <th class="small">Maintenance Required (Yes/No)</th>
            <th>Comments</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>Seat condition</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Adjustable seat mechanism</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Seat suspension system</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Armrests and controls accessibility</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Foot pedal position</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Cabin cleanliness (if enclosed)</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Visibility (windows/mirrors)</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
        </tbody>
      </table> -->

      <!-- Miscellaneous Components -->
      <!-- <h3>Miscellaneous Components</h3>
      <table> -->
        <!-- <thead>
          <tr>
            <th>Component</th>
            <th class="small">Condition (E/G/F/P)</th>
            <th class="small">Maintenance Required (Yes/No)</th>
            <th>Comments</th>
          </tr>
        -- </thead> -->
        <!-- <tbody>
          <tr><td>Operator display (lights, gauges)</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Throttle control cable</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Mower deck guards and shields</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Deck height adjuster function</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>Canopy or sunshade (if installed)</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
          <tr><td>GPS/telemetry system (if installed)</td><td><input type="text"/></td><td><input type="text"/></td><td><textarea></textarea></td></tr>
        </tbody>
      </table> 
    </section> -->

    <!-- Condition Key -->
    <section class="card">
      <h3>Condition Key</h3>
      <div class="legend">
        <span><span class="pill">E</span> No issues detected, fully operational.</span>
        <span><span class="pill">G</span> Minor wear and tear, but functional.</span>
        <span><span class="pill">F</span> Requires some repairs or maintenance.</span>
        <span><span class="pill">P</span> Significant issues; not functional or unsafe.</span>
      </div>
    </section>

    <!-- Technician Certification -->
    <section>
      <h3>Technician's Certification</h3>
        <p class="note">I hereby certify that I have inspected the machine(s) as per the above checklist and recorded the observations accurately.</p>
     <table>
        <thead>
          <tr>
            <th >Technician Name:<span style="float:right;padding-right:10px;">{{$inspection->bookingInfo?->technicianInfo?->full_name}}</span></th>
            <th class="">Signature:</th>
         
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Date:<span style="float:right;padding-right:10px;">{{\carbon\Carbon::today()->format('y-m-d')}}</span></th>
          
            <td></td>
          
          </tr>
          <tr>
       
       
        </tbody>
      </table>
      <!-- <div class="cert-grid">
        <div class="field"><label for="techName">Technician Name</label><input id="techName" type="text" /></div>
        <div class="field"><label for="signature">Signature</label><input id="signature" type="text" placeholder="Sign or type name" /></div>
        <div class="field"><label for="certDate">Date</label><input id="certDate" type="date" /></div>
      </div> -->
    
    </section>
     <footer>
    <div class="footer-content" style="color:white">
      <div class="footer-item">
        <h2>Delta Irrigation and Agriculture Service Ltd. Co.</h2>
        <h3 style="font-weight: normal;">شركة دلتا للأنظمة الري والخدمات الزراعية المحدودة</h3>
       
      </div>
          <div class="footer-content" style="color:white">
         <div class="footer-item">
         <div class="footer-link"><i class="fa fa-location-dot"></i> Nabra St, Al-Masani’, Riyadh 14714</div>
        <div class="footer-link"><i class="fa fa-globe"></i><a href="https://www.deltaturfcare.com"> www.deltaturfcare.com</a></div>
                <div class="footer-link"><i class="fa fa-location-dot"></i> شارع نبرة، المصانع، الرياض ١٤٧١٤</div>
       
      </div>

      <div class="footer-item">
        
        <div class="footer-link"><i class="fa fa-envelope"></i> info@deltaturfcare.com</div>
        <div class="footer-link"><i class="fa fa-phone"></i> +966 114 333 458</div>
        <div class="footer-link"><i class="fa fa-headset"></i> Customer Service +966 552 045 600</div>
      </div>
    </div>
    <div class="footer-bottom">
      © 2025 Delta Irrigation and Agriculture Service Ltd. Co. All rights reserved.
    </div>
  

  </footer>
        <section class="buttons-section" style="text-align: center; margin-top: 20px;">

            <button id="email-invoice-btn"
                style="padding: 10px 20px; background-color: #00b16a; color: white; border: none; cursor: pointer; margin-right: 10px;">
                Email Inspection
            </button>
            <button id="print-invoice-btn"
                style="padding: 10px 20px; background-color: #f44336; color: white; border: none; cursor: pointer;">
                Print Inspection
            </button>
        </section>
    
  </div>

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
</body>
</html>


