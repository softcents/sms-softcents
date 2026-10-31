@foreach($recharges as $recharge)
    <tr>
        <td>{{ $loop->iteration }}</td>
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
                        <a target="_blank" href="{{ route('users.recharges.show', $recharge->id) }}">
                            <i class="fas fa-print"></i>
                            {{ __('Print Invoice') }}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach
