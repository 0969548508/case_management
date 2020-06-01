@extends('mail.mail_template')
@section('content')
	<div class="card w-100">
	  <div class="card-body">
	    <p class="card-text">
			Link reset password is: <b>{{ route('showViewForgotPassword', $token) }}</b>
	    	<br><br>
	    	Please enter this password to log in. Please be advised that you will need to change your password when you log in for the first time.
	  </div>
	</div>
@endsection