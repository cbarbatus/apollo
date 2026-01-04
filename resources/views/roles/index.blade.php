@extends('layouts.app')

@section('content')
    <style>
        /* 1. RESTORE CABIN FONT */
        @import url('https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600;700&display=swap');

        #roles-management-context,
        #roles-management-context h1,
        #roles-management-context h3,
        #roles-management-context table {
            font-family: 'Cabin', sans-serif !important;
        }

        /* 2. THE HOVER SHIELD: White text on green background */
        #roles-management-context .btn-outline-success:hover {
            background-color: #198754 !important;
            color: #ffffff !important;
            border-color: #198754 !important;
        }

        /* 3. THE WASTELAND KILLER: Snap card to table width */
        #roles-management-context .card {
            display: table !important;
            min-width: 700px;
            border: 1px solid #dee2e6 !important;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
    </style>

    <div id="roles-management-context" style="margin-left: 20px !important; margin-top: 20px !important;">

        <h1 style="margin-bottom: 20px !important; font-weight: 700; font-size: 2.2rem;">Roles & Permissions Management</h1>

        {{-- 'New' Buttons --}}
        <div style="display: flex !important; gap: 12px !important; margin-bottom: 30px !important;">
            <a href="{{ url('/roles/create') }}"
               style="background-color: #0d6efd !important; color: #ffffff !important; padding: 10px 22px !important; border-radius: 6px !important; text-decoration: none !important; font-weight: 600;">
                + New Role
            </a>
            <a href="{{ url('/roles/pcreate') }}"
               style="background-color: #008080 !important; color: #ffffff !important; padding: 10px 22px !important; border-radius: 6px !important; text-decoration: none !important; font-weight: 600;">
                + New Permission
            </a>
        </div>

        {{-- ROLES SECTION --}}
        <div class="card mb-5">
            <div class="card-header" style="background-color: #f8f9fa !important; padding: 18px 20px;">
                {{-- Section Headers: Larger and Bold --}}
                <h3 style="margin: 0; font-size: 1.5rem !important; font-weight: 700; color: #212529;">Current Roles</h3>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="width: auto !important; min-width: 700px;">
                    <thead>
                    <tr style="border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 15px 20px; font-weight: 700; color: #495057;">Role Name</th>
                        <th style="width: 220px; padding: 15px 20px; font-weight: 700; color: #495057;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td class="px-4 py-3 align-middle fw-medium">{{ $role->name }}</td>
                            <td class="px-4 py-3 align-middle">
                                <div class="d-flex align-items-center gap-2">
                                    {{-- 1. Standardized Edit --}}
                                    <x-apollo-button
                                        href="{{ url('/roles/' . $role->name . '/edit') }}"
                                        color="warning"
                                        size="sm"
                                        class="px-3"
                                    >
                                        Edit
                                    </x-apollo-button>

                                    {{-- 2. Standardized Role Delete --}}
                                    <x-delete-button
                                        :action="url('/roles/' . $role->name . '/destroy')"
                                        resource="role"
                                    >
                                        Delete
                                    </x-delete-button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PERMISSIONS SECTION --}}
        <div class="card">
            <div class="card-header" style="background-color: #f8f9fa !important; padding: 18px 20px;">
                <h3 style="margin: 0; font-size: 1.5rem !important; font-weight: 700; color: #212529;">System Permissions</h3>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="width: auto !important; min-width: 700px;">
                    <thead>
                    <tr style="border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 15px 20px; font-weight: 700; color: #495057;">Permission Name</th>
                        <th style="width: 220px; padding: 15px 20px; font-weight: 700; color: #495057;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td style="padding: 15px 20px; vertical-align: middle; font-weight: 500;">{{ $permission->name }}</td>
                            <td style="padding: 15px 20px; vertical-align: middle;">
                                <div class="d-inline-flex align-items-center shadow-sm" style="height: 38px; border-radius: 8px; overflow: hidden;">
                                    {{-- Changed $pname to $permission to match your loop --}}
                                    <x-delete-button
                                        :action="url('/roles/' . $permission->name . '/pdestroy')"
                                        resource="permission"
                                    />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
