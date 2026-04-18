@php
    $previousSender = null;
    $previousDate = null;
@endphp

@foreach ($messages as $message)
    @php
        $isSameSender = $previousSender === $message['sender'];
        $alignment = $message['sender'] === 'self' ? 'flex-row-reverse' : '';
        $bubbleAlignment = $message['sender'] === 'self' ? 'right' : 'left';
        $avatar = $message['sender'] === 'self' ? Auth::user()->image : $receiver->image;
    @endphp

    @if ($message['date'] !== $previousDate)
        <label class="main-chat-time">
            <span>{{ \Carbon\Carbon::parse($message['date'])->format('F-j, Y') }}</span>
        </label>
    @endif

    @if (!$isSameSender) 
        <div class="media {{ $alignment }}">
            <div class="main-img-user online">
                <img alt="avatar" src="{{URL::asset('assets/img/media/admin.jpg')}}">
            </div>
            <div class="media-body">
    @endif

    <div class="main-msg-wrapper {{ $isSameSender ? '' : $bubbleAlignment }}">
        {{ $message['content'] }}
    </div>

    @if ($loop->last || $messages[$loop->index + 1]['sender'] !== $message['sender'])
        <div>
            <span>{{ $message['time'] }}</span>
            <a href="javascript:void(0);">
                <i class="icon ion-android-more-horizontal"></i>
            </a>
        </div>
        </div>
        </div>
    @endif

    @php
        $previousSender = $message['sender'];
        $previousDate = $message['date'];
    @endphp
@endforeach