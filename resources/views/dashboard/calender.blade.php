@extends('layouts.master')
@section('css')

<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{asset('build/assets/libs/fullcalendar/main.min.css')}}">

@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto"> {{__('Work Order Calender')}}</h4><span class="text-muted mt-1 tx-13 ml-2 mb-0">/
                Page</span>
        </div>
    </div>

</div>
<!-- breadcrumb -->
@endsection
@section('content')
@include('messages_alert')

<div class="row row-sm">
<div class="col-xl-3">
                            <div class="card custom-card">
                              
                                <div class="card-body p-0">
                                    <div id="external-events" class="p-3">
                                        <div
                                        class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event  border p-2 mb-2 h5 border-primary" style="background:#64e764;border-radius:10px;height:36px">
                                        <div class="fc-event-main"> Confirmed</div>
                                        </div>
                                     
                                        <div
                                        class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-secondary border p-2 h5 mb-2 border-secondary"
                                        data-class="bg-secondary" style="border-radius:10px;border-radius:10px;height:36px">
                                        <div class="fc-event-main">  Enroute</div>
                                        </div>
                                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-info p-2 border h5 mb-2 border-info"
                                        data-class="bg-info" style="border-radius:10px;height:36px">
                                        <div class="fc-event-main"> Start Work</div>
                                        </div>
                                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-warning h5 p-2 h5 border mb-2 border-success"
                                        data-class="bg-success" style="border-radius:10px;height:36px">
                                        <div class="fc-event-main"> On Hold</div>
                                        </div>
                                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-success h5 p-2 h5 border mb-2 border-success"
                                        data-class="bg-success" style="border-radius:10px;height:36px">
                                        <div class="fc-event-main"> Completed</div>
                                        </div>
                                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event bg-danger  p-2 mb-2 h5 border border-teal"
                                        data-class="bg-danger" style="border-radius:10px;height:36px">
                                        <div class="fc-event-main">  Invoiced</div>
                                        </div>
                                        <div
                                        class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event  p-2 border h5 mb-2 border-warning"
                                        style="background:#800080;border-radius:10px;height:36px" data-class="bg-warning;b">
                                        <div class="fc-event-main">  Paid</div>
                                        </div>
                                     
                                        <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event  p-2 mb-2 h5 border border-danger"
                                        data-class="bg-danger" style="border-radius:10px;height:36px;background:#cc0000">
                                        <div class="fc-event-main">  Cancel</div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">

                    <div class="p-2" id="calendar">

                    </div>

            </div>    
     </div>  
  </div> 
</div>   

         

@endsection

@section('js')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
 $(document).ready(function(){


        display_events();
      function display_events(){
        var events= Array();
        var color;
        $.ajax({
            url: '{{ route('events') }}',

            type: "get",
            dataType: 'json',


            success: function(res) {
                console.log(res); 
               result=res.data;
                 $.each(result, function(i,index) {
                    
                    if(result[i].workorder.status == 'Completed')      
                            {  
                                col = "#03c03c";  
                            } 
                               
                            else if (result[i].workorder.status == 'Enroute')  
                            {    
                                col = "grey";   
                            }   
                            else if (result[i].workorder.status == 'Paid')  
                            {    
                                col = "#800080";   
                            } 
                            else if (result[i].workorder.status == 'Invoiced')  
                            {    
                                col = "#DC3545";   
                            } 
                            else if (result[i].workorder.status == 'Cancel' )  
                            {    
                                col = "#cc0000";   
                            } 
                            else if (result[i].workorder.status == 'StartWork')  
                            {    
                                col = "#30d5c8";   
                            }
                            else if (result[i].workorder.status == 'Confirmed')  
                            {    
                                col = "#64e764";   
                            }
                            else if (result[i].workorder.status == 'OnHold')  
                            {    
                                col = "#f4ca16";   
                            }

                             else {   
                                col = "#eeeeee";  
                            } 

                            
                if(result[i].workorder.status !== 'Pending') 
                {
                    
                 
                 events.push({     
                title:'#WO-'+result[i].workorder.id+' '+result[i].workorder.status,
                start:result[i].from_date +'T'+ result[i].from_time,
                end:result[i].from_date +'T'+ result[i].to_time,
            
                color:col,
                textColor:'white'});
                 }

                });
               
             
                    console.log(events); 

         var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'

            },
            events: events
            

    
        
        });
        calendar.render();
            }
        });

      }

 });
   
    </script>



<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>

<script src="{{URL::asset('assets//plugins/notify/js/notifit-custom.js')}}"></script>


@endsection