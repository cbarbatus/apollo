@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Edit an Announcement</h1>

        <form method='post' action="/announcements/{{ $announcement->id }}/update" id="edit">
            @csrf
            @method('put')

            <div class="col-md-4 mb-3">
                <label for="year" class="form-label">Year:</label>
                <input type="text" name="year" id="year" class="form-control" size="4" value="{{ $announcement->year }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="name" class="form-label">Name:</label>
                <select name="name" class="form-label" id="name">
                    @foreach($rituals as $ritual_name)
                        <option value="{{$ritual_name}}"
                        <?php if($ritual_name==$announcement->name) echo 'selected'; ?>>
                            {{$ritual_name}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="picture_file" class="form-label">Picture File:</label>
                <input type="text" name="picture_file" id="picture_file" class="form-label" size="60" value="{{ $announcement->picture_file }}">
            </div>

            <div class="row">
                <label for="summary">Summary:</label>
                <div class="col-md-8 mb-3">
                <input
                    type="hidden"
                    name="summary"
                    value="{{ html_entity_decode($venue->summary ?? '') }}"
                    >
            <trix-editor input="summary" class="form-control" style="min-height: 200px;"></trix-editor>

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
