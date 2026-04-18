@component('mail::message')
	# {{ $details['heading'] ?? 'Dear User,' }}

	{{ $details['body'] }}

	@if(!empty($details['button']))
		@component('mail::button', ['url' => $details['button']['url']])
			{{ $details['button']['label'] }}
		@endcomponent
	@endif

	@if(!empty($details['footer']))
		{{ $details['footer'] }}
	@endif

	Thanks,<br>
	{{ $details['from_name'] ?? config('app.name') }}
@endcomponent
