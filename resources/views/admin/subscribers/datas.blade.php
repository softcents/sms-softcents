@foreach($subscribers as $subscriber)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $subscriber->invoice_no }}</td>
        <td>{{ optional($subscriber->user)->name }}</td>
        <td>{{ optional($subscriber->plan)->title }}</td>
        <td>{{ $subscriber->gateway->name ?? '' }}</td>
        <td class="fw-bold text-dark">{{ $subscriber->price }}</td>
        <td class="fw-bold text-dark">{{ $subscriber->total_sms }}</td>
        <td>{{ $subscriber->will_expire }}</td>
        <td>{{ ($subscriber->created_at)->format('d M Y') }}</td>
        <td>
            @if ($subscriber->status == 'pending')
                <div class="badge bg-warning">
                    {{ ucfirst($subscriber->status) }}
                </div>
            @elseif ($subscriber->status == 'approved')
                <div class="badge bg-success">
                    {{ ucfirst($subscriber->status) }}
                </div>
            @else
                <div class="badge bg-danger">
                    {{ ucfirst($subscriber->status) }}
                </div>
            @endif
        </td>
        <td>
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.subscribers.show', $subscriber->id) }}" class="view-btn">
                            <i class="fal fa-eye"></i>
                            {{ __('View') }}
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ route('admin.subscribers.print', $subscriber->id) }}">
                            <i class="fas fa-print"></i>
                            {{ __('Print Invoice') }}
                        </a>
                    </li>
                    @can('orders-update')
                    @if ($subscriber->status == 'pending')
                    <li>
                        <a href="javascript:void(0)" class="approve-reject" data-url="{{ route('admin.subscription.status', [$subscriber->id, 'status' => 'approved']) }}">
                            <i class="fas fa-check"></i>
                            {{ __('Approve') }}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="approve-reject" data-url="{{ route('admin.subscription.status', [$subscriber->id, 'status' => 'canceled']) }}">
                            <i class="far fa-window-close"></i>
                            {{ __('Cancel') }}
                        </a>
                    </li>
                    @endif
                    @endcan
                </ul>
            </div>
        </td>
@endforeach
