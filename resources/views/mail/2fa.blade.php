@extends('mail.mail_template')
@section('content')
	<div class="card" style="width: 18rem;">
	  <div class="card-body">
	    <p class="card-text">
			Your token is: <b>{{$token}}</b>
	    	<br><br>
	    	Please enter this token to log in.
	  </div>
	</div>
@endsection