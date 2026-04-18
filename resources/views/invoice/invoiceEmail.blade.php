@component('mail::message')
	<!-- # {{ $details['heading'] ?? 'Dear User,' }} -->


    Dear {{$details['user_name'] ?? 'user'}} 
	
	{{ $details['body'] }}
    @if(!empty($details['footer']))
		{{ $details['footer'] }}
	@endif

	Best regards,
	{{ $details['from_name'] ?? config('app.name') }}
@endcomponent