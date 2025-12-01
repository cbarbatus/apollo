@extends('layouts.app')

@section('content')
    <h1>Contacts</h1>

    <br>
    <form method="get" action="/contacts/replied/list" id="reply">
        <button type="submit" class="btn btn-go" >Show Replied</button>
    </form>
<br>
        <form method="get" action="/contacts/spam/list" id="reply">
        <button type="submit" class="btn btn-go" >Show Spam</button>
    </form>
<br>
    <form method="get" action="/contacts/all/list" id="reply">
        <button type="submit" class="btn btn-go" >Show All</button>
    </form>
    <br>

    <div class='container' >
        @if ( !($contacts->count()) )
        You have no contacts
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
