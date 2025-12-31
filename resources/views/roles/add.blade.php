@extends('layouts.app')

@section('content')
    {{-- Master Wrapper to kill the wasteland --}}
    <div style="max-width: 600px !important; margin-left: 20px !important; margin-top: 20px !important; font-family: 'Cabin', sans-serif !important;">

        <h1 style="margin-bottom: 20px !important; font-weight: 700;">Add Permission to '{{$role->name}}'</h1>

        <div class="card shadow-sm" style="border: 1px solid #dee2e6 !important;">
            <div class="card-header" style="background-color: #f8f9fa !important; padding: 15px; border-bottom: 1px solid #dee2e6 !important;">
                <h3 style="margin: 0; font-size: 1.3rem !important; font-weight: 700; color: #333;">Select Permission</h3>
            </div>

            <div class="card-body" style="padding: 25px;">
                {{-- Changed to POST for security --}}
                <form method="POST" action="/roles/{{ $role->name }}/set" id="pname">
                    @csrf {{-- Proper Laravel CSRF Directive --}}

                    <div class="form-group mb-4">
                        <label for="permission_name" style="font-weight: 600; display: block; margin-bottom: 10px;">Permission Name to Add:</label>
                        <select name="permission_name" id="permission_name" class="form-control" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
                            @foreach($permissions as $permission)
                                <option value="{{$permission->name}}">{{$permission->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 30px;">
                        {{-- The Success Button --}}
                        <button type="submit" class="btn" style="background-color: #198754 !important; color: #ffffff !important; padding: 10px 25px !important; border-radius: 6px !important; font-weight: 700; border: none !important;">
                            Save Permission
                        </button>

                        {{-- Cancel Link --}}
                        <a href="{{ url('/roles') }}" style="background-color: #6c757d !important; color: #ffffff !important; padding: 10px 25px !important; border-radius: 6px !important; text-decoration: none !important; font-weight: 600; display: flex; align-items: center;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
