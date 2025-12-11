@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Contacts</h1>

        <div class="mt-4">
        <form method="get" action="/contacts/replied/list" id="reply">
            <button type="submit" class="btn btn-go" >Show Replied</button>
        </form>
        </div>

        <div class="mt-4">
            <form method="get" action="/contacts/spam/list" id="reply">
            <button type="submit" class="btn btn-go" >Show Spam</button>
        </form>
        </div>
        <div class="mt-4">
        <form method="get" action="/contacts/all/list" id="reply">
            <button type="submit" class="btn btn-go" >Show All</button>
        </form>
        </div>
    </div>

        <div class='container' >
            @if ( !($contacts->count()) )
                <div class="card p-3 mb-4 fw-bold">
                You have no contacts
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                    <tr style="font-weight:bold">
                        <td>ID</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>Message</td>
                        <td>Status</td>
                        <td>Received</td>
                        <td>Responded</td>
                        <td colspan="2">Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contacts as $contact)
                        <tr>
                            <td>{{$contact->id}} </td>
                            <td>{{$contact->name}} </td>
                            <td>{{$contact->email}}</td>
                            <td>{{$contact->message}}</td>
                            <td>{{$contact->status}}</td>
                            <td>{{$contact->created_at}}</td>
                            <td>{{$contact->when_replied}}</td>

                            <td>
                                    <form method="get" action="/contacts/{{ $contact['id']}}/reply" id="reply">
                                        <button type="submit" class="btn btn-warning" >Mark Replied</button>
                                    </form>
                            </td>
                            <td>
                                    <form method="get" action="/contacts/{{ $contact['id']}}/spam" id="spam">
                                        <button type="submit" class="btn btn-danger" >Mark Spam</button>
                                    </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

<br>
@endsection
