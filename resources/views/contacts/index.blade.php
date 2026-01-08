@extends('layouts.app')

@section('content')
    <style>
        .table-fixed {
            table-layout: fixed;
            width: 100%;
            margin: 0;
        }

        /* Fixed percentages that total exactly 100% */
        .col-id { width: 4%; }
        .col-name { width: 14%; }
        .col-email { width: 18%; }
        .col-message { width: 42%; } /* Message gets the lion's share */
        .col-status { width: 7%; }
        .col-action { width: 15%; }

        .truncate-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            width: 100%;
        }

        .break-word {
            word-wrap: break-word;
            white-space: normal;
            line-height: 1.1;
            font-size: 0.85rem; /* Slightly smaller to prevent vertical bloating */
        }

    </style>

    <div class='container my-5'>
        <h1>Contacts</h1>

        <div class="mb-3">
            <x-apollo-button color="primary" href="/contacts/replied/list">Show Replied</x-apollo-button>
            <x-apollo-button color="primary" href="/contacts/spam/list">Show Spam</x-apollo-button>
            <x-apollo-button color="primary" href="/contacts/all/list">Show All</x-apollo-button>
        </div>

        <div>
            <x-delete-button
                :action="url('/contacts/delete-spam')"
                method="DELETE"
                resource="all messages currently marked as spam"
                color="danger"
            >
                <i class="fa fa-trash"></i> Purge All Spam
            </x-delete-button>
        </div>
    </div>

        <div class='container' >
            @if ( !($contacts->count()) )
                <div class="card p-3 mb-4 fw-bold">
                You have no contacts
                </div>
            @else
                <div class="table-responsive">
                    {{-- Removed table-fixed --}}
                    <table class="table table-hover align-middle">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Status</th>
                            {{-- No width: 1% here --}}
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td class="text-muted small">{{ $contact->id }}</td>
                                <td class="fw-bold">{{ $contact->name }}</td>
                                <td class="text-secondary">{{ $contact->email }}</td>
                                <td>
                                    <div class="truncate-text" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $contact->message }}">
                                        {{ $contact->message }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $contact->status }}</span>
                                </td>
                                {{-- This cell is the key: d-flex and gap keep the buttons together and visible --}}
                                <td class="text-nowrap text-end">
                                    <div class="d-flex justify-content-center gap-2">

                                        <form action="{{ route('contacts.replied', $contact->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <x-apollo-button type="submit" color="warning" size="sm">Mark Replied</x-apollo-button>
                                        </form>

                                        <form action="{{ route('contacts.spam', $contact->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <x-apollo-button type="submit" color="primary" size="sm">Mark Spam</x-apollo-button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

@endsection
