<!-- Sidebar-right -->
<div class="sidebar sidebar-right sidebar-animate">
    <div class="panel panel-primary card mb-0 box-shadow">
        <div class="tab-menu-heading border-0 p-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 text-white">Notifications</h5>
            <a href="#" class="sidebar-remove text-white"><i class="fe fe-x"></i></a>
        </div>

        @php
        use Carbon\Carbon;
        use Illuminate\Support\Facades\Auth;

        $user = Auth::user();
        $commentsQuery = DB::table('booking_comments')
            ->join('users', 'booking_comments.user_id', '=', 'users.id')
            ->select('booking_comments.*', 'users.first_name as user_name')
            ->whereDate('booking_comments.created_at', Carbon::today()) // Filter by today's date
            ->orderBy('booking_comments.created_at', 'desc');

        if ($user->type === 'technician') {
            $commentsQuery->where('booking_comments.user_id', $user->id); // Show only technician's comments
        }

        $comments = $commentsQuery->get()->groupBy('booking_id');
        @endphp

        <div class="panel-body tabs-menu-body latest-tasks p-3 border-0">
            @if($comments->isEmpty())
                <p class="text-center text-muted">No new comments today.</p>
            @else
                @foreach($comments as $bookingId => $bookingComments)
                    @php $latestComment = $bookingComments->first(); @endphp

                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <a href="javascript:void(0);" class="booking-link font-weight-bold text-primary"
                                    data-booking-id="{{ $bookingId }}" style="cursor: pointer;">
                                    <i class="mdi mdi-bookmark-check"></i> Booking ID: {{ $bookingId }}
                                </a>
                            </h6>
                            <span class="badge badge-pill badge-danger">{{ $bookingComments->count() }} New</span>
                        </div>

                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <span class="avatar bg-primary brround avatar-md mr-2"></span>
                                <div>
                                    <p class="mb-1">
                                        <b>{{ $latestComment->user_name }}</b>: {{ Str::limit($latestComment->comment, 50) }}
                                    </p>
                                    <small class="text-muted">
                                        <i class="mdi mdi-clock-outline"></i>
                                        {{ \Carbon\Carbon::parse($latestComment->created_at)->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>


<script>
$(document).on("click", ".booking-link", function() {
    let bookingId = $(this).data("booking-id");

    $.ajax({
        url: "{{ route('journal.update-count') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            booking_id: bookingId
        },
        success: function(response) {
            if (response.success) {

                let badge = $(this).closest('.card').find('.badge');
                let newCount = response.count;

                if (newCount > 0) {
                    badge.text(newCount + " New");
                } else {
                    $(this).closest('.card').fadeOut();
                }
            }
        }.bind(this)
    });
});
</script>