@extends('layouts.app')

@section('content')
@include('navbar')
<div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="title mb-0">Create new role</h1>
</div>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body role-form">
                <form method="POST" action="{{ route('createRole') }}">
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label class="col-md-12 col-xl-1 text-md-left">{{ __('*Role Name') }}</label>
                        <div class="col-md-9 col-xl-4">
                            <input id="role-name" type="text" class="form-control" name="name" required maxlength="255" placeholder="Enter Role Name" pattern=".*\S+.*" value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-12 col-xl-1 text-md-left">{{ __('Description') }}</label>
                        <div class="col-md-9 col-xl-4">
                            <textarea class="form-control" id="role-description" rows="3" placeholder="Enter description" name="description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<label class="col-form-label">Select permission for this role</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									@foreach ($listPermission as $keyPermission => $permission)
										@php
											$permissionSlug = str_replace(' ', '-', $permission['slug']);
										@endphp
										<div class="row @if (!in_array($permission['name'], array('Case Management', 'Settings'))) mb-md-2 @else mb-md-1 mt-md-2 @endif">
											<div class="col-12">
												<label class="col-form-label role-title-header">{{ $permission['name'] }}</label>
											</div>
											<div class="col-12 m-auto">
												<div class="row">
													@include('roles.action-add')
												</div>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						</div>
                    </div>
                    <div class="col-md-12 form-group row mb-0">
	                    <div class="col-md-12 mt-md-4 p-0">
	                        <button type="button" class="btn btn-primary custom-button-cancel mr-2" onclick="goBack()">
	                            {{ __('CANCEL') }}
	                        </button>
	                        <button type="submit" class="btn btn-primary custom-button">
	                            {{ __('SAVE') }}
	                        </button>
	                    </div>
	                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script>
		function sellectAll(source) {
			let parents = document.getElementById('select-all-' + source);
			let div = document.getElementsByClassName('cbox-' + source);

			for (var i = 0; i<div.length; i++) {
				div[i].checked = parents.checked;
			}
		}

		function toggle(source, sourceHeader, actionHeader) {
			var check_all = 0;
			var check_all_milestone = 0;
			let parents = document.getElementById(source);

			if (source == 'edit-matters-milestone') {
				let parents = document.getElementById(source);
				let div = document.getElementsByClassName('sub-cbox-' + source);

				for (var i = 0; i<div.length; i++) {
					div[i].checked = parents.checked;
				}
			}

			if (actionHeader == 'edit-matters-milestone') {
				if(!parents.checked){
	                let div_milestone = document.getElementById(actionHeader);
	                if(div_milestone.checked){div_milestone.checked = false;}
	            }else{
	                let div_row = document.getElementsByClassName('sub-cbox-' + actionHeader);
	                for(var j = 0; j<div_row.length; j++){
	                    if(div_row[j].checked){check_all_milestone++;}
	                }
	                if(check_all_milestone == div_row.length){
	                    let div_milestone = document.getElementById(actionHeader);
	                    if(!div_milestone.checked){div_milestone.checked = true;}
	                }
	            }
			}

			if (sourceHeader != 'password-policy' && sourceHeader != 'audit-log') {
	            if(!parents.checked){
	                let div_sellect_all = document.getElementById('select-all-' + sourceHeader);
	                if(div_sellect_all.checked){div_sellect_all.checked = false;}
	            }else{
	                let div_row = document.getElementsByClassName('cbox-' + sourceHeader);
	                for(var j = 0; j<div_row.length; j++){
	                    if(div_row[j].checked){check_all++;}
	                }
	                if(check_all == div_row.length){
	                    let div_sellect_all = document.getElementById('select-all-' + sourceHeader);
	                    if(!div_sellect_all.checked){div_sellect_all.checked = true;}
	                }
	            }
	        }
		}

		function goBack() {
			window.location.replace("{{ route('showListRoles') }}");
		}
    </script>
@endsection