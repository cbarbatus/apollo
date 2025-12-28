@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Announcements</h1>

        <x-alert-success />

        <div class="row mb-4">
            <div class="col-md-4">
                {{-- Form action is a GET request, so it doesn't strictly need a <form> wrapper if just a link --}}
                <a href="{{ url('/announcements/create') }}" class="btn btn-warning">New Announcement</a>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-striped table-hover">

            <thead>
            <tr class="fw-bold">
                <td>ID</td>
                <td>Year</td>
                <td>Name</td>
                <td>Summary</td>
                <td>When</td>
                <td>Where</td>
                <td>Notes</td>
                <td colspan="3">Action</td>
                <td>Picture File</td>
            </tr>
            </thead>
            <tbody>
            @foreach($announcements as $announcement)
                <tr>
                    <td>{{$announcement->id}}</td>
                    <td>{{$announcement->year}}</td>
                    <td>{{$announcement->name}}</td>
                    <td>{{substr($announcement['summary'], 0, 60)}}</td>
                    <td>{{$announcement->when}}</td>
                    <td>{{$announcement->venue_name}} </td>
                    <td>{{substr($announcement['notes'], 0, 60)}} </td>
                    <td><form method="get" action="/announcements/{{ $announcement['id']}}/edit" id="edit">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-warning" >Edit</button>
                        </form>
                    </td>
                    <td><form method="get" action="/announcements/{{ $announcement['id']}}/activate" id="edit">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-info" ><strong>Activate</strong></button>
                        </form>
                    </td>
                    <td>
                        <x-delete-button
                            :action="route('announcements.destroy', $announcement->id)"
                            resource="Announcement"
                        />
                    </td>
                    <td>
                    <img src="/img/{{ $announcement->picture_file}}" alt="None" style="max-height:50px">
                    </td>
                    <td><form method="get" action="/announcements/{{ $announcement['id']}}/uploadpic" id="uppic">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-primary px-4 fw-bold border-0 shadow-sm">Upload</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
<br>
@endsection
