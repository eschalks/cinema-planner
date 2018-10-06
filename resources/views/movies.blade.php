@extends('layout')

@section('body')
    <table class="table table--movies">
        <thead>
        <tr>
            <td>Movie</td>
            @foreach ($dates as $date)
                <td>{{ $date->format('D d/m') }}</td>
            @endforeach
        </tr>
        </thead>
        <tbody>
        <?php /** @var \App\Models\Movie $movie */ ?>
        @foreach ($movies as $movie)
            <tr>
                <td>
                    {{ $movie->title }}
                </td>
                @foreach ($dates as $date)
                    <td>
                        <ul>
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
