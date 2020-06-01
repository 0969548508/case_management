@extends('mail.mail_template')
@section('content')
	<div class="card" style="width: 18rem;">
	  <div class="card-body">
	    <p class="card-text">Enter this code to login <b>{{$passcode}}</b>. This code is valid for 15 minutes.</p>
	  </div>
	</div>
@endsection