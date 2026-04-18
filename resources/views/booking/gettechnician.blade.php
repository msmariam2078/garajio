<tbody>
    @forelse($technicians as $technician)
    <tr>
        <td>{{ $technician->first_name }} {{ $technician->last_name }} (ID: {{ $technician->id }})</td>

        <td>
            @php
            $todayWorkHours = $technician->workHours->where('workingday', $todayDay)->where('start_date',
            $today)->first();
            $status = $todayWorkHours ? $todayWorkHours->status : null;
            @endphp

            @if($status === 1)
            Online
            @elseif($status === 0)
            Offline
            @else
            --
            @endif
        </td>

        <td>
            @if($status === 1)
            <span class="badge badge-success">Active</span>
            @elseif($status === 0)
            <span class="badge badge-danger">Inactive</span>
            @else
            <span class="badge badge-secondary">--</span>
            @endif
        </td>

        <td>
            @if($status !== null)
            {{ $todayWorkHours->workingday }}
            @else
            --
            @endif
        </td>

        <td>{{ isset($technician->distance) ? number_format($technician->distance, 2) . ' km' : '--' }}</td>

        <td>
            <div class="hour-bar"
                style="display: flex; justify-content: space-around; border-top: 2px solid #000; padding-top: 10px;">
                @foreach(range(0, 23) as $hour)
                @php
                $startTime = $todayWorkHours ? strtotime($todayWorkHours->start_time) : null;
                $endTime = $todayWorkHours ? strtotime($todayWorkHours->end_time) : null;
                $hourTime = strtotime($hour . ":00");
                @endphp

                @if($startTime && $endTime && $hourTime >= $startTime && $hourTime < $endTime) <div class="hour"
                    style="text-align: center; width: 30px; background-color: #4caf50;">
                    <span style="font-weight: bold;">{{ $hour }}</span>
            </div>
            @else
            <div class="hour" style="text-align: center; width: 30px; background-color: #ccc;">
                <span style="font-weight: bold;">{{ $hour }}</span>
            </div>
            @endif
            @endforeach
            </div>
        </td>

        <td>
            <button type="button" class="btn btn-primary customModal"  data-title="{{ __('Book Technician') }}"
                data-url="{{ route('book.technicians', ['technicianId' => $technician->id, 'workOrderId' => $workOrder->id]) }}">
                Book
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center">No technicians available.</td>
    </tr>
    @endforelse
</tbody>



