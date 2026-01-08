@extends('layouts.app')

@section('content')

    <div class="mx-auto" style="max-width: 1000px;">
        <h1 class="mb-4">Rituals Liturgy</h1>

        <table class="table table-hover" style="width: auto !important;">
            <thead class="table-dark">
            <tr>
                <th style="width: 50px;">ID</th>
                {{-- Add a max-width here to stop the 'Name' from stretching --}}
                <th style="min-width: 200px; max-width: 300px;">Name</th>
                <th style="width: 80px; text-align: center;">Year</th>
                <th style="width: 120px;">Culture</th>
                <th style="width: 1%; white-space: nowrap;">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rituals as $ritual)
                <tr>
                    <td class="align-middle">{{ $ritual->id }}</td>
                    <td class="align-middle fw-bold">{{ $ritual->name }}</td>
                    <td class="align-middle text-center">{{ $ritual->year }}</td>
                    <td class="align-middle">{{ $ritual->culture }}</td>
                    <td class="align-middle" style="white-space: nowrap; width: 1%;">
                        {{-- Using the stable 'success' code for BOTH buttons --}}
                        <a href="/liturgy/{{ $ritual->year }}_{{ $ritual->name }}.htm"
                           class="btn btn-success btn-sm shadow-sm"
                           target="_blank">
                            Show Liturgy
                        </a>

                        <a href="/liturgy/{{ $ritual->id }}/downloadSource"
                           class="btn btn-success btn-sm shadow-sm ms-2">
                            Download To Edit
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>@endsection
