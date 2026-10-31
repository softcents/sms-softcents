@foreach ($templates as $template)
    <tr>
        <td>{{ $loop->index+1 }}</td>
        <td>{{ $template->name }}</td>
        <td>{{ $template->text }}</td>
        <td>
            <a href="javascript:void(0)" class="btn btn-sm btn-dark text-light use-template" data-text="{{ $template->text }}">Use</a>
        </td>
    </tr>
@endforeach
