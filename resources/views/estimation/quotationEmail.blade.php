@component('mail::message')


    Dear {{$details['name' ?? 'user']}} 

	
	{{ $details['body'] }}

   @if(!empty($details['footer']))
		{{ $details['footer'] }}
	@endif

	Thanks,
	{{ $details['from_name'] ?? config('app.name') }}
@endcomponent
