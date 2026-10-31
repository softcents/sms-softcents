@foreach($senderids as $senderid)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $senderid->sender }}</td>
        <td>{{ ($senderid->created_at)->format('d M Y') }}</td>
        <td>
            <div class="badge bg-{{ $senderid->type == 'Non-Masking' ? 'success' : 'danger' }}">
                {{ $senderid->type }}
            </div>
        </td>
    </tr>
@endforeach
