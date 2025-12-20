@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Edit an Announcement</h1>

        <form method="post" action="{{ route('announcements.update', $announcement) }}" id="edit">
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
                <div class="col-md-8 mb-3">
                    <label for="summary" class="form-label">Summary:</label>

                    <input
                        type="hidden"
                        name="summary"
                        id="trix-summary-input"
                        value="{{ $announcement->summary }}"
                        >
                    <trix-editor
                        input="trix-summary-input"
                        class="form-control"
                        style="min-height: 200px;"
                    ></trix-editor>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <label for="when" class="form-label">When:</label>
                <input type="datetime-local" name="when" id="when" class="form-control" value="{{ $announcement->when }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="venue_name" class="form-label">Venue:</label>
                <select name="venue_name" class="form-control" id="venue_name">
                    @foreach($locations as $location)
                        <option value="{{$location->name}}"
                        <?php if($location->name==$announcement->venue_name) echo 'selected'; ?>>
                            {{$location->name}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="notes">Notes:</label>
                    <label for="notes" class="form-label">Notes:</label>
                    <input
                        type="hidden"
                        name="notes"
                        id="trix-notes-input"
                        value="{{ $announcement->notes }}"
                    >
                    <trix-editor
                        input="trix-notes-input"
                        class="form-control"
                        style="min-height: 200px;"
                    ></trix-editor>
                </div>
            </div>
            <button type="submit" form='edit' class="btn btn-go">Submit</button>
        </form>
    </div>
@endsection
