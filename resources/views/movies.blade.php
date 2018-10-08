@extends('layout')

@section('content')
    <table id="movie-showing-table">
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
                                <li data-showing="{{ $showing->id }}">
                                    <span class="time-list__item__time">
                                    {{ $showing->starts_at->format('H:i') }}
                                    </span>

                                    <span class="time-list__item__info">
                                        {{ __('movies.quality.'.$showing->quality ) }}
                                    @if ($showing->is_3d) 3D @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>

    @component('components.dialog', ['id' => 'movie'])
        <form method="post" action="{{ route('joinShowing') }}">
            {{ csrf_field() }}

            <input type="hidden" data-var="showingId" name="showing">

            Do you want to go to <span data-var="movieTitle"></span>
            at <span data-var="time"></span> on <span data-var="date"></span>?

            <div class="dialog__buttons">
                <button type="submit">Yes</button>
            </div>
        </form>
    @endcomponent
@endsection
