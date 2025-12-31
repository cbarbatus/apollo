@extends('layouts.app')

@section('content')
    {{-- Master Wrapper to kill the wasteland and enforce Cabin --}}
    <div style="max-width: 900px !important; margin-left: 20px !important; margin-top: 20px !important; font-family: 'Cabin', sans-serif !important;">

        {{-- Role Heading and Top Action --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h1 style="margin: 0; font-weight: 700;">Role: '{{ $role->name }}'</h1>

            <a href="{{ url('/roles/' . $role->name . '/add') }}"
               style="background-color: #008080 !important; color: #ffffff !important; padding: 10px 20px !important; border-radius: 6px !important; text-decoration: none !important; font-weight: 700;">
                + Add Permission
            </a>
        </div>

        {{-- Permissions Table Card --}}
        <div class="card shadow-sm mb-4" style="border: 1px solid #dee2e6 !important;">
            <div class="card-header" style="background-color: #f8f9fa !important; padding: 15px; border-bottom: 1px solid #dee2e6 !important;">
                <h3 style="margin: 0; font-size: 1.4rem !important; font-weight: 700; color: #333;">Assigned Permissions</h3>
            </div>

            <div class="card-body p-0">
                @if ($pnames->isEmpty())
                    <div style="padding: 20px; color: #666; font-style: italic;">This role has no permissions assigned.</div>
                @else
                    <table class="table mb-0" style="width: 100%; table-layout: fixed;">
                        <thead>
                        <tr style="border-bottom: 2px solid #dee2e6;">
                            <th style="padding: 12px 15px; width: 75%; font-weight: 700;">Permission Name</th>
                            <th style="padding: 12px 15px; width: 25%; font-weight: 700;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pnames as $pname)
                            <tr>
                                <td style="padding: 12px 15px; vertical-align: middle; font-weight: 500;">{{ $pname->name }}</td>
                                <td style="padding: 12px 15px; vertical-align: middle;">
                                    {{-- Use POST since we updated the routes, or DELETE if your controller supports it --}}
                                    <form method="POST" action="{{ url('/roles/' . $role->name . '/' . $pname->name . '/remove') }}" style="margin: 0;">
                                        @csrf
                                        <button type="submit"
                                                style="background-color: #ff0000 !important; color: #000000 !important; padding: 6px 14px !important; border-radius: 4px !important; border: none !important; font-weight: 700; font-size: 0.9rem !important; cursor: pointer;">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Navigation Footer --}}
        <div style="margin-top: 20px;">
            <a href="{{ url('/roles') }}"
               style="background-color: #198754 !important; color: #ffffff !important; padding: 12px 30px !important; border-radius: 6px !important; text-decoration: none !important; font-weight: 700; display: inline-block;">
                Done
            </a>
        </div>
    </div>
@endsection
