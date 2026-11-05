@forelse($senderids as $senderid)
    @php
        $assignedCount = isset($senderUserCounts) && isset($senderUserCounts[$senderid->sender]) ? $senderUserCounts[$senderid->sender] : 1;
    @endphp
    <tr>
        @can('senderids-delete')
        <td class="checkbox text-start">
            <input type="checkbox" name="ids[]" class="checkbox-item" value="{{ $senderid->id }}" data-url="{{ route('admin.senderids.delete-all') }}">
        </td>
        @endcan
        <td>{{ $loop->iteration + ($senderids instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($senderids->currentPage() - 1) * $senderids->perPage() : 0) }}</td>
        <td>
            <div class="fw-bold">{{ optional($senderid->user)->name ?? 'N/A' }}</div>
            @if(optional($senderid->user)->username)
                <small class="text-primary font-monospace">@<span>{{ $senderid->user->username }}</span></small>
            @endif
        </td>
        <td>{{ optional($senderid->user)->phone ?? 'N/A' }}</td>
        <td>
            <div class="d-flex align-items-center gap-1 flex-wrap">
                <span class="fw-bold text-dark font-monospace" style="font-size: 13.5px;">{{ $senderid->sender }}</span>
                @if($assignedCount > 1)
                    <a href="javascript:void(0)" class="badge bg-info text-white text-decoration-none btn-view-assigned-users shadow-sm" data-sender="{{ $senderid->sender }}" title="{{ __('Given to :count users. Click to view all.', ['count' => $assignedCount]) }}" style="font-size: 11px; padding: 4px 8px; border-radius: 12px; cursor: pointer;">
                        <i class="fas fa-users me-1"></i> {{ $assignedCount }} {{ __('Users') }}
                    </a>
                @else
                    <span class="badge bg-light text-muted border" style="font-size: 11px; padding: 3px 6px; border-radius: 12px;" title="{{ __('Given to 1 user') }}">
                        <i class="fas fa-user me-1"></i> 1
                    </span>
                @endif
            </div>
        </td>
        <td>
            @if($senderid->gateway)
                <span class="badge px-2 py-1" style="background-color: #4e73df; color: #ffffff; font-size: 12px; font-weight: 600; border-radius: 6px; letter-spacing: 0.3px;">
                    <i class="fas fa-network-wired me-1" style="font-size: 10px;"></i>{{ $senderid->gateway->name }}
                </span>
            @else
                <span class="badge px-2 py-1" style="background-color: #f6c23e; color: #5a3c00; font-size: 12px; font-weight: 600; border-radius: 6px;">
                    <i class="fas fa-exclamation-triangle me-1" style="font-size: 10px;"></i>{{ __('Unassigned') }}
                </span>
            @endif
        </td>
        <td>
            <div class="badge bg-{{ $senderid->type == 'Non-Masking' ? 'success' : 'danger' }}">
                {{ $senderid->type }}
            </div>
        </td>
        <td>
            <div class="badge bg-{{ $senderid->is_default == 1 ? 'success' : 'warning' }}">
                {{ $senderid->is_default == 1 ? __('Yes') : __('No') }}
            </div>
        </td>
        <td class="text-center w-150">
            @can('senderids-update')
                <label class="switch">
                    <input type="checkbox" {{ $senderid->status == 1 ? 'checked' : '' }} class="status" data-url="{{ route('admin.senderids.status', $senderid->id)}}">
                    <span class="slider round"></span>
                </label>
            @else
                <div class="badge bg-{{ $senderid->status == 1 ? 'success' : 'danger' }}">
                    {{ $senderid->status == 1 ? __('Active') : __('Deactive') }}
                </div>
            @endcan
        </td>
        <td>
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0)" class="btn-view-assigned-users" data-sender="{{ $senderid->sender }}">
                            <i class="fal fa-users text-info"></i>
                            {{ __('Assigned Users') }} ({{ $assignedCount }})
                        </a>
                    </li>
                    @can('senderids-update')
                    <li>
                        <a href="javascript:void(0)" class="btn-quick-change-gateway" data-id="{{ $senderid->id }}" data-sender="{{ $senderid->sender }}" data-gateway-id="{{ $senderid->gateway_id ?? 0 }}" data-gateway-name="{{ optional($senderid->gateway)->name ?? __('Unassigned') }}">
                            <i class="fal fa-exchange-alt"></i>
                            {{ __('Change Gateway') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.senderids.edit', $senderid->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    @endcan
                    @can('senderids-delete')
                    <li>
                        <a href="{{ route('admin.senderids.destroy', $senderid->id) }}" class="confirm-action" data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="11" class="text-center py-4 text-muted">
            <i class="far fa-inbox fa-2x mb-2 d-block"></i>
            {{ __('No sender IDs found matching the selected filters.') }}
        </td>
    </tr>
@endforelse
