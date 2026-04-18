@component('mail::message')


    Dear {{$details['to_name']}} 

	
	{{ $details['body'] }}

		Details:
		- Booking ID: {{ $details['m_detail_booking'] }}
		- Work Order ID: {{ $details['m_detail_workorder'] }}
		- Customer Name: {{ $details['m_detail_customer'] }}

	Thank you for your prompt attention.

   	@if(!empty($details['footer']))
	{{ $details['footer'] }}
	@endif

	Thanks,
	{{ $details['user_name']  }}
	{{config('app.name')}}
@endcomponent
