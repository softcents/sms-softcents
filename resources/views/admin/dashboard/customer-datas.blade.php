@forelse($users as $user)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td><img class="table-img rounded-circle" src="{{ asset($user->image ?? 'assets/images/icons/default-user.png') }}" alt=""></td>
        <td>
            <div class="fw-bold">{{ ucwords($user->name) }}</div>
            @if($user->username)
                <small class="text-primary font-monospace">@<span>{{ $user->username }}</span></small>
            @endif
        </td>
        <td>{{ $user->email }}</td>
        <td>
            <p>{{ optional($user->plan)->title ?? 'N/A' }} (<small>{{ optional($user->plan)->duration ?? 'N/A' }}</small>)</p>
        </td>
        <td>{{ formatted_date($user->will_expire) }}</td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted py-3">
            {{ __('No customers registered in this period') }}
        </td>
    </tr>
@endforelse
