@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Contact Us</h1>

        <form name="frmContact" id="frmContact" method="post"
            action="/contacts/submit" enctype="multipart/form-data"
            onsubmit="return validateContactForm()">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <label for="name">Name</label>
                <input type="text" name="name" id="name" size="40" required/>
            <br>
            <label for="email">Email</label>
                <input type="email" name="email" id="email" size="60" required/>
            <br>
            <label for="message">Message</label>
                <textarea name="message" id="content"
                    cols="60" rows="6" required></textarea>
            <br>
            <div class="form-group" style="display: none;">
                <label for="faxonly">Fax Only
                    <input type="checkbox" name="faxonly" id="faxonly" />
                </label>
            </div>
            <br>
        </form>
                <button type="submit" form='frmContact' class="btn btn-go" >Submit</button>
        <br><br>
                <div id="statusMessage">
                        <?php
                        if (! empty($message)) {
                            ?>
                            <p class='<?php echo $type; ?>Message'><?php echo $message; ?></p>
                        <?php
                        }
                        ?>
                    </div>
        </div>

    <script src="https://code.jquery.com/jquery-2.1.1.min.js"
        type="text/javascript"></script>
    <script type="text/javascript">
        function validateContactForm() {
            var valid = true;

            $(".info").html("");
            $(".input-field").css('border', '#e0dfdf 1px solid');
            var userName = $("#userName").val();
            var userEmail = $("#userEmail").val();
            var subject = $("#subject").val();
            var content = $("#content").val();

            if (userName == "") {
                $("#userName-info").html("Required.");
                $("#userName").css('border', '#e66262 1px solid');
                valid = false;
            }
            if (userEmail == "") {
                $("#userEmail-info").html("Required.");
                $("#userEmail").css('border', '#e66262 1px solid');
                valid = false;
            }
            if (!userEmail.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/))
            {
                $("#userEmail-info").html("Invalid Email Address.");
                $("#userEmail").css('border', '#e66262 1px solid');
                valid = false;
            }

            if (subject == "") {
                $("#subject-info").html("Required.");
                $("#subject").css('border', '#e66262 1px solid');
                valid = false;
            }
            if (content == "") {
                $("#userMessage-info").html("Required.");
                $("#content").css('border', '#e66262 1px solid');
                valid = false;
            }
            return valid;
        }
    </script>

    </div>
    <br>
@endsection
