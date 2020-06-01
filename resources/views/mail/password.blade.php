@extends('mail.mail_template')
@section('content')
	<div class="card" style="width: 18rem;">
	  <div class="card-body">
	    <p class="card-text">
			Your password is: <b>{{$password}}</b>
	    	<br><br>
	    	Please enter this password to log in. Please be advised that you will need to change your password when you log in for the first time.
	  </div>
	</div>
@endsection