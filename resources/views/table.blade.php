@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-center">{{ $title }}</h1>
        <input id="search-input" type="text" placeholder="Search" class="form-control" style="width: 300px;">
    </div>
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
            <tr>
                @foreach ($columns as $key)
                <th>{{ \App\Helpers\DataFormatter::formatColumnHeader($key) }}</th>
                @endforeach
                <th>{{ \App\Helpers\DataFormatter::formatColumnHeader('actions') }}</th>
            </tr>
            </thead>
            <tbody id="tableBody">
            @foreach ($data as $item)
            <tr>
                @foreach ($columns as $key)
                <td data-search="{{ \App\Helpers\DataFormatter::formatColumnValue($key, $item->$key ?? '-') }}">
                    @if ($key === 'psw')
                    <div class="psw-container">
                        <input type="password" class="form-control psw-input" value="{{ $item->$key }}" readonly>
                        <button type="button" class="btn btn-sm toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @else
                    {{ \App\Helpers\DataFormatter::formatColumnValue($key, $item->$key ?? '-') }}
                    @endif
                </td>
                @endforeach
                <td class="text-center">
                    <button class="btn btn-success btn-sm me-2" title="Salva">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" title="Cancella">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#search-input').on('keyup', function () {
            var value = $(this).val().toLowerCase();

            $('table tbody tr').each(function () {
                var rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(value) > -1)
                    $(this).show();
                else
                    $(this).hide();
            });
        });

        $('.toggle-password').on('click', function () {
            var input = $(this).siblings('.psw-input');
            var icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
@endpush