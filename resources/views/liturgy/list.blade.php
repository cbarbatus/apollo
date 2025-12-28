@extends('layouts.app')

@section('content')
    <h1>Rituals Liturgy</h1>

    <div class="container">

        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Culture</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rituals as $ritual)
                    <tr>
                        <td>{{ $ritual->id }}</td>
                        <td>{{ $ritual->name }}</td>
                        <td>{{ $ritual->year }}</td>
                        <td>{{ $ritual->culture }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="/rituals/{{ $ritual->id }}/liturgy" class="btn btn-sm btn-success">Show</a>
                                <a href="/liturgy/{{ $ritual->id }}/get" class="btn btn-sm btn-success">Get</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
