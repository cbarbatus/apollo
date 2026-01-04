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
                <div style="overflow-x: hidden;">
                    <table class="table table-hover table-fixed">
                        <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-name">Name</th>
                            <th class="col-email">Email</th>
                            <th class="col-message">Message</th>
                            <th class="col-status">Status</th>
                            <th class="col-action">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contacts as $contact)
                            <tr class="align-middle">
                                <td class="text-muted small">{{ $contact->id }}</td>
                                <td class="break-word fw-bold">{{ $contact->name }}</td>
                                <td class="break-word text-secondary">{{ $contact->email }}</td>
                                <td class="col-message">
                <span class="truncate-text" title="{{ $contact->message }}">
                    {{ $contact->message }}
                </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border p-1">{{ $contact->status }}</span>
                                </td>
                                <td class="col-action text-nowrap">
                                    <div class="d-flex gap-1">
                                        {{-- Mark Replied Form --}}
                                        <form action="/contacts/{{ $contact->id }}/reply" method="POST">
                                            @csrf
                                            <x-apollo-button type="submit" class="btn-warning btn-sm">
                                                Mark Replied
                                            </x-apollo-button>
                                        </form>

                                        {{-- Mark Spam Form --}}
                                        <form action="/contacts/{{ $contact->id }}/spam" method="POST">
                                            @csrf
                                            <x-apollo-button type="submit" class="btn-danger btn-sm">
                                                Mark Spam
                                            </x-apollo-button>
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
