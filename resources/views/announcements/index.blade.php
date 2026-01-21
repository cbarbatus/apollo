@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Announcements</h1>

        <div class="row mb-4">
            <div class="col-md-4">
                <x-apollo-button href="{{ url('/announcements/create') }}">
                    <i class="bi bi-plus-lg me-2"></i>New Announcement
                </x-apollo-button>
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
                    <td>
                        <x-apollo-button
                            href="/announcements/{{ $announcement['id'] }}/edit"
                            color="warning"
                            size="sm"
                        >
                            Edit
                        </x-apollo-button>
                    </td>
                    <td>
                        <x-apollo-button
                            href="/announcements/{{ $announcement['id'] }}/activate"
                            style="background-color: #008080 !important; border-color: #008080 !important; color: white !important;"
                            size="sm"
                        >
                            Activate
                        </x-apollo-button>
                    </td>
                    <td>
                        <x-delete-button
                            :action="route('announcements.destroy', $announcement->id)"
                            resource="Announcement"
                        />
                    </td>
                    <td>
                        <img src="/img/{{ $announcement->picture_file }}?t={{ $announcement->updated_at->timestamp }}"
                             alt="None"
                             style="max-height:50px">
                    </td>
                    <td>
                        <x-apollo-button
                            href="/announcements/{{ $announcement['id'] }}/uploadpic"
                            color="warning"
                            size="sm"
                        >
                            Upload
                        </x-apollo-button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
<br>
@endsection
