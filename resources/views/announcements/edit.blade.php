@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Edit an Announcement</h1>
        <br><br>
        <form method='post' action="/announcements/{{ $announcement->id }}/update" id="edit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            @method('put')
            <br>

            <label for="year">Year:</label>
            <input type="text" name="year" id="year" size="4" value="{{ $announcement->year }}">
            <label for="name">Name:</label>
            <select name="name" id="name">
                @foreach($rituals as $ritual_name)
                    <option value="{{$ritual_name}}"
                    <?php if($ritual_name==$announcement->name) echo 'selected'; ?>>
                        {{$ritual_name}}
                    </option>
                @endforeach
            </select>
            <br>

            <label for="picture_file">Picture File:</label>
            <input type="text" name="picture_file" id="picture_file" size="60" value="{{ $announcement->picture_file }}">
            <br>
            <label for="summary">Summary:</label>
            <textarea id="notes" name="summary" rows="15" cols="60" value="{{ $announcement->summary }}">
            {{ html_entity_decode($announcement->summary) }}
            </textarea>
            <br>
            <label for="when">When:</label>
            <input type="datetime-local" name="when" id="when" value="{{ $announcement->when }}">
            <br>
            <label for="venue">Venue:</label>
            <select name="venue_name" id="venue_name">
                @foreach($locations as $location)
                    <option value="{{$location->name}}"
                    <?php if($location->name==$announcement->venue_name) echo 'selected'; ?>>
                        {{$location->name}}
                    </option>
                @endforeach
            </select>
            <br>
            <label for="notes">Summary:</label>
            <textarea id="notes" name="notes" rows="15" cols="60" value="{{ $announcement->notes }}">
            {{ html_entity_decode($announcement->notes) }}
            </textarea>
            <br>

        </form>
        <button type="submit" form='edit' class="btn btn-go">Submit</button>

    </div>
@endsection
