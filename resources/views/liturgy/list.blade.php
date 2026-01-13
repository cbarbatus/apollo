@extends('layouts.app')

@section('content')

    <div class="mx-auto" style="max-width: 1000px;">
        <h1 class="mb-4">Rituals Liturgy</h1>

        {{-- Added table-layout: fixed and a smaller total width --}}
        {{-- This wrapper is the secret sauce to killing the white space --}}
        <div style="display: inline-block; min-width: 500px; border: 1px solid #dee2e6; border-radius: 4px;">
            <table class="table table-hover mb-0" style="width: auto !important; table-layout: auto;">
                <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">ID</th>
                    {{-- Now this 120px should actually be respected --}}
                    <th style="width: 120px;">Name</th>
                    <th style="width: 80px; text-align: center;">Year</th>
                    <th style="width: 120px;">Culture</th>
                    <th style="width: 1%; white-space: nowrap;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rituals as $ritual)
                    <tr>
                        <td class="align-middle">{{ $ritual->id }}</td>
                        {{-- Ensure the name doesn't push the width back out --}}
                        <td class="align-middle fw-bold" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $ritual->name }}
                        </td>
                        <td class="align-middle text-center">{{ $ritual->year }}</td>
                        <td class="align-middle">{{ $ritual->culture }}</td>
                        <td class="align-middle">
                        @php
                            $fileNameBase = "{$ritual->year}_{$ritual->name}";
                            $hasHtm = file_exists(public_path("liturgy/{$fileNameBase}.htm"));
                            $docxPath = storage_path("app/grove/{$fileNameBase}.docx");
                            $hasDocx = file_exists($docxPath);
                        @endphp

                        <div class="btn-group shadow-sm">
                            @if($hasHtm)
                                <a href="/liturgy/{{ $fileNameBase }}.htm"
                                   class="btn btn-success btn-sm"
                                   style="width: 85px;" target="_blank">Liturgy</a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled style="width: 85px;">No htm</button>
                            @endif

                            @if($hasDocx)
                                <a href="{{ route('liturgy.downloadSource', $ritual->id) }}"
                                   class="btn btn-info btn-sm"
                                   style="width: 85px;">Source</a>
                            @else
                                <button class="btn btn-outline-secondary btn-sm" disabled style="width: 85px;">No docx</button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
