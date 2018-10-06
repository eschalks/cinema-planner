@extends('layout')

@section('content')
    <table>
        <thead>
        <tr>
            <th>Movie</th>
            @foreach ($dates as $date)
                <th>{{ $date->format('D d/m') }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <?php /** @var \App\Models\Movie $movie */ ?>
        @foreach ($movies as $movie)
            <tr>
                <td class="table__movie-title">
                    {{ $movie->title }}
                </td>
                @foreach ($dates as $date)
                    <td>
                        <ul class="time-list">
                            @foreach ($movie->getShowingsForDate($date) as $showing)
                                <li>{{ $showing->starts_at->format('H:i') }}</li>
                            @endforeach
                        </ul>
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
