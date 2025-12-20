@extends('layouts.app')

@section('content')

    @if (session('status'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('status') }}
        </div>
    @endif

    @if (session('message'))
        <div style="background-color: #cce5ff; color: #004085; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #b8daff;">
            {{ session('message') }}
        </div>
    @endif

    <div class='container'>
        <h1>Front Page Sections</h1>

        <x-alert-success />

        <div class="row mb-4">
            <div class="col-md-4">
                <a href="{{ url('/sections/create') }}" class="btn btn-warning">New Section</a>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
            <tr style="font-weight:bold">
                <td>ID</td>
                <td>Name</td>
                <td>Title</td>
                <td>Sequence</td>
                <td colspan="2">Action</td>
            </tr>
            </thead>
            <tbody>
            @foreach($sections as $section)
                <tr>
                    <td>{{$section->id}}</td>
                    <td>{{$section->name}}</td>
                    <td>{{$section->title}}</td>
                    <td>{{$section->sequence}}</td>
                    <td><form method="get" action="/sections/{{ $section['id']}}/edit" id="edit">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-warning" >Edit</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
