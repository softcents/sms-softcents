@foreach($recharges as $recharge)
    <tr>
        {{-- <td>{{ ($recharges->perPage() * ($recharges->currentPage() - 1)) + $loop->iteration }}</td> --}}
        <td>{{ $loop->iteration }}</td>
        <td>{{ optional($recharge->user)->name }}</td>
        <td>{{ $recharge->invoice_no }}</td>
        <td>{{ $recharge->gateway->name ?? '' }}</td>
        <td>{{ ($recharge->created_at)->format('d M Y') }}</td>
        <td class="fw-bold text-dark">{{ currency_format($recharge->amount) }}</td>
        <td>
            @if ($recharge->status == 'pending')
                <div class="badge bg-warning">
                    {{ ucfirst($recharge->status) }}
                </div>
            @elseif ($recharge->status == 'approved')
                <div class="badge bg-success">
                    {{ ucfirst($recharge->status) }}
                </div>
            @else
                <div class="badge bg-danger">
                    {{ ucfirst($recharge->status) }}
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
                        <li>
                            <a href="{{ route('admin.recharges.show', $recharge->id) }}" class="view-btn">
                                <i class="fal fa-eye"></i>
                                {{ __('View') }}
                            </a>
                        </li>
                        <a target="_blank" href="{{ route('admin.recharges.print', $recharge->id) }}">
                            <i class="fas fa-print"></i>
                            {{ __('Print Invoice') }}
                        </a>
                        @can('recharges-update')
                        @if ($recharge->gateway->is_manual)
                        @if ($recharge->status != 'approved')
                        <a href="javascript:void(0)" class="approve-reject" data-url="{{ route('admin.recharge.status', [$recharge->id, 'status' => 'approved']) }}">
                            <i class="fas fa-check"></i>
                            {{ __('Approve') }}
                        </a>
                        @endif
                        @if ($recharge->status != 'canceled')
                        <a href="javascript:void(0)" class="approve-reject" data-url="{{ route('admin.recharge.status', [$recharge->id, 'status' => 'canceled']) }}">
                            <i class="far fa-window-close"></i>
                            {{ __('Cancel') }}
                        </a>
                        @endif
                        @endif
                        @endcan
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
