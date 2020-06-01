@extends('layouts.app')

@section('content')
@include('navbar')
<?php
	$count = DB::table('model_has_roles')
				->where('role_id', $roleId)
				->count();

	$checkDelete = ($count > 0) ? false : true;
?>
<div class="row d-sm-flex align-items-center mb-3">
	<div class="col-8 col-xl-8">
		<h1 class="content-name title mb-0">
			<span id="content-name">{{ $roleDetails['name'] }}</span>
			<img id="content-name-icon" class="img-responsive content-name cursor-pointer ml-4" src="{{ asset('images/btn_pen_x2.png') }}" alt="icon edit">
		</h1>
	    <div id="form-edit-name" class="col-12 col-xl-12 p-0">
			<div class="col-md-7 col-xl-4 float-left p-0">
		        <input id="role-name" type="text" class="form-control" name="name" required maxlength="255" placeholder="Enter Role Name" value="{{ $roleDetails['name'] }}" pattern=".*\S+.*">
		    </div>
	        <div class="col-xl-8 float-left p-0">
	            <button id="cancel-edit-name" type="button" class="btn btn-primary custom-button-cancel ml-xl-5 mr-2 mt-2 mt-xl-0">
	                {{ __('CANCEL') }}
	            </button>
	            <button id="edit-name" type="submit" class="btn btn-primary custom-button mt-2 mt-xl-0">
	                {{ __('SAVE') }}
	            </button>
	        </div>
	    </div>
    </div>

    <div class="col-4 col-xl-4">
		@can('delete roles')
		    @if ($checkDelete)
				<button type="button" class="d-sm-inline-block btn btn-create-style btn-action-user dropdown-toggle float-right" data-toggle="dropdown">ACTION</button>
			    <div class="dropdown-menu dropdown-menu-right">
			        <a href="javascript:void(0);" class="dropdown-item text-danger font-weight-bold" onclick="deleteRole()">Delete Role</a>
			    </div>
		    @endif
	    @endcan
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-xl-12">
        <div class="card form-group description-role mb-3">
        	<div class="col-12 col-xl-12 float-left">
	            <label class="col-xl-12 col-form-label text-md-left">{{ __('Description') }}</label>
	            <div class="col-xl-11">
	            	<p id="content-description" class="content-description">{{ ucfirst($roleDetails['description']) }}</p>
	                <textarea class="form-control edit-description mb-3" id="role-description" rows="3" placeholder="Enter description" name="description" required="">{{ ucfirst($roleDetails['description']) }}</textarea>
	            </div>
	            <div id="form-edit-description" class="edit-description col-xl-12 mb-3">
            		<button id="cancel-edit-description" type="button" class="btn btn-primary custom-button-cancel mr-2">
		                {{ __('CANCEL') }}
		            </button>
		            <button id="edit-description" type="submit" class="btn btn-primary custom-button">
		                {{ __('SAVE') }}
		            </button>
            	</div>

	            <div class="icon-pencil-right">
					<img id="content-description-icon" class="img-responsive content-description" src="{{ asset('images/btn_pen_x2.png') }}" alt="icon edit">
            	</div>
            </div>
    	</div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body role-form">
                <form method="POST" action="{{ route('updateRole', $roleId) }}">
                    {{ csrf_field() }}
                    <div class="form-group row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									@php $checkSellectAllList = array(); @endphp
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
													@foreach ($permission['list_sub'] as $keySub => $sub)
														@php
															$subSlug = str_replace(' ', '-', $sub['slug']);
															$checkSellectAllList[$subSlug]['count_all'] = count($sub['action']);
															$checkSellectAllList[$subSlug]['count'] = 0;
														@endphp
														<div class="col-md-2 col-xl-2">
															<label class="col-form-label role-sub-title-header">{{ $sub['name'] }}</label>
															@if ($sub['slug'] == 'reports')
																<br><label class="col-8 col-form-label role-sub-title-header note-tbd">Note: TBD</label>
															@endif
														</div>

														<div class="col-md-10 col-xl-10 @if (!in_array($sub['slug'], array('roles managements', 'rates managements', 'offices', 'types subtypes', 'audit log', 'password policy'))) mb-4 @endif">
															<div class="row mr-2 @if (in_array($sub['slug'], array('roles managements', 'rates managements', 'offices', 'types subtypes', 'audit log', 'password policy'))) border-bottom-action @endif">
																@if (!in_array($sub['slug'], array('roles managements', 'rates managements', 'offices', 'types subtypes', 'audit log', 'password policy')))
																	<label class="col-12 border-bottom-action">
																		<input id="select-all-{{$subSlug}}" class="role-checkbox" type="checkbox" onClick="sellectAll('{{$subSlug}}')"><span class="checkmark"></span> <span>Select All</span>
																	</label>
																@else
																	@if (!in_array($sub['slug'], array('audit log', 'password policy')))
																		<label class="col-12 col-md-4 col-xl-2 sub-action">
																			<input id="select-all-{{$subSlug}}" class="role-checkbox" type="checkbox" onClick="sellectAll('{{$subSlug}}')"><span class="checkmark"></span> <span>Select All</span>
																		</label>
																	@endif
																@endif
																@foreach ($sub['action'] as $keyAction => $action)
																	@php
																		$actionName = str_replace(' ', '-', $action['name']);
																		$title = Lang::get('roles.titles.' . $action['name']);
																		$note = Lang::get('roles.notes.' . $action['name']);
																	@endphp
																	@if ($action['name'] == 'edit matters milestone')
																		<label class="col-12 border-bottom-action">
																			<input id="{{$actionName}}" class="role-checkbox cbox-{{$subSlug}}" type="checkbox" name="permission[]" value="{{$action['name']}}"
																			<?php
																				if (in_array($action['id'], $listPermissionAssigned)) {
																					$checkSellectAllList[$subSlug]['count']++;
																					echo 'checked';
																				}
																			?>
																			onClick="toggle('{{$actionName}}', '{{$subSlug}}')"><span class="checkmark"></span>
																			<span class="role-sub-title">
																				{{ ucwords($title) }}
																			</span><br>
																			<span class="ml-2 role-sub-note">
																				{{ $note }}
																			</span><br>
																			<div class="row ml-2">
																				@foreach ($action['sub_action'] as $subAction)
																					<label class="col-12 col-md-4 col-xl-3 sub-action">
																						<input id="{{ $subAction['name'] }}" class="role-checkbox cbox-{{$subSlug}} sub-cbox-{{$actionName}}" type="checkbox" name="permission[]" value="{{ $subAction['name'] }}"
																						<?php
																							if (in_array($subAction['id'], $listPermissionAssigned)) {
																								$checkSellectAllList[$subSlug]['count']++;
																								echo 'checked';
																							}
																						?>
																						onClick="toggle('{{$subAction['name']}}', '{{$subSlug}}', '{{$actionName}}')"><span class="checkmark"></span>
																						<span class="role-sub-title">
																							{{ ucwords(Lang::get('roles.titles.' . $subAction['name'])) }}
																						</span>
																					</label>
																				@endforeach
																			</div>
																		</label>
																	@else
																		@if (in_array($sub['slug'], array('roles managements', 'rates managements', 'offices', 'types subtypes')))
																			<label class="col-12 col-md-4 col-xl-2 sub-action">
																				<input id="{{$actionName}}" class="role-checkbox cbox-{{$subSlug}}" type="checkbox" name="permission[]" value="{{$action['name']}}"
																				<?php
																					if (in_array($action['id'], $listPermissionAssigned)) {
																						$checkSellectAllList[$subSlug]['count']++;
																						echo 'checked';
																					}
																				?>
																				onClick="toggle('{{$actionName}}', '{{$subSlug}}')"><span class="checkmark"></span>
																				<span class="role-sub-title">
																					{{ ucwords(Lang::get('roles.titles.' . $action['name'])) }}
																				</span>
																			</label>
																		@else
																			@if (in_array($sub['slug'], array('audit log', 'password policy')))
																				<label class="col-12 col-md-4 col-xl-2 sub-action">
																					<input id="{{$actionName}}" class="role-checkbox cbox-{{$subSlug}}" type="checkbox" name="permission[]" value="{{$action['name']}}"
																					<?php
																						if (in_array($action['id'], $listPermissionAssigned)) {
																							$checkSellectAllList[$subSlug]['count']++;
																							echo 'checked';
																						}
																					?>
																					onClick="toggle('{{$actionName}}', '{{$subSlug}}')"><span class="checkmark"></span>
																					<span class="role-sub-title">
																						{{ ucwords(Lang::get('roles.titles.' . $action['name'])) }}
																					</span>
																				</label>
																			@else
																				<label class="col-12 border-bottom-action">
																					<input id="{{$actionName}}" class="role-checkbox cbox-{{$subSlug}}" type="checkbox" name="permission[]" value="{{$action['name']}}"
																					<?php
																						if (in_array($action['id'], $listPermissionAssigned)) {
																							$checkSellectAllList[$subSlug]['count']++;
																							echo 'checked';
																						}
																					?>
																					onClick="toggle('{{$actionName}}', '{{$subSlug}}')"><span class="checkmark"></span>
																					<span class="role-sub-title">
																						{{ ucwords($title) }}
																					</span>
																					@if (!empty($note))
																						<br>
																						<span class="ml-2 role-sub-note">
																							{{ $note }}
																						</span>
																					@endif
																				</label>
																			@endif
																		@endif
																	@endif
																@endforeach
															</div>
														</div>
													@endforeach
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

<!-- custom modal -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTitle">{{ __('Delete role') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete this role?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <form action="{{ route('deleteRole', $roleId) }}" method="POST">
					{{ csrf_field() }}
					<button type="submit" class="btn btn-delete-modal">{{ __('DELETE') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
    <script>
		$('#form-edit-name').hide();
		$('.edit-description').hide();

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

		// edit name
		$('#content-name-icon').click(function() {
			$('#edit-name').attr("disabled", false);
			$('.content-name').hide();
			$('#form-edit-name').show();
			$('#cancel-edit-description').trigger('click');
		});


		$('#cancel-edit-name').click(function() {
			$('input#role-name').val($('span#content-name').text());
			$('#form-edit-name').hide();
			$('.content-name').show();
		});


		$('#edit-name').click(function() {
			$('#edit-name').attr("disabled", true);
			var url = "{{ route('updateContentRole', $roleId) }}";
			var data = {
				"name": $('input#role-name').val(),
				"column" : "name"
			};
			var result = sendAjax(url, data);

			if (result == true) {
				$('span#content-name').text($('input#role-name').val());
				$('#form-edit-name').hide();
				$('.content-name').show();
			} else {
				$('#edit-name').attr("disabled", false);
			}
		});

		// edit description
		$('#content-description-icon').click(function() {
			$('#edit-description').attr("disabled", false);
			$('.content-description').hide();
			$('.edit-description').show();
			$('#cancel-edit-name').trigger('click');
		});

		$('#cancel-edit-description').click(function() {
			$('#role-description').val($('p#content-description').text());
			$('.edit-description').hide();
			$('.content-description').show();
		});

		$('#edit-description').click(function() {
			$('#edit-description').attr("disabled", true);
			var url = "{{ route('updateContentRole', $roleId) }}";
			var data = {
				"description" : $('#role-description').val(),
				"column" : "description"
			};
			var result = sendAjax(url, data);

			if (result == true) {
				$('p#content-description').text($('#role-description').val().substr(0,1).toUpperCase() + $('#role-description').val().substr(1));
				$('.edit-description').hide();
				$('.content-description').show();
			} else {
				$('#edit-description').attr("disabled", false);
			}
		});

		function sendAjax(url, data) {
			var result;
			$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
            });
            jQuery.ajax({
                url: url,
                data: data,
                method: 'get',
                async: false,
                success: function(response){
                    if (typeof (response.errors) !== 'undefined') {
                        result = false;
                        toastr.error(response.errors);
                    } else {
	                    result = true;
	                    toastr.success(response.success);
	                }
                }
            });

            return result;
		}

		function goBack() {
			window.location.replace("{{ route('showListRoles') }}");
		}

		function deleteRole() {
			$('#modalDelete').modal('show');
		}

		init();

        function init() {
        	var checkSellectAllList = {!! json_encode($checkSellectAllList) !!};

        	for (var key in checkSellectAllList) {
				if (key != 'password-policy' && key != 'audit-log') {
	                let count = document.getElementsByClassName('cbox-' + key).length;

	                if(count == checkSellectAllList[key].count){
	                    let div_sellect_all = document.getElementById('select-all-' + key);
	                    if(!div_sellect_all.checked){
	                        div_sellect_all.checked = true;
	                    }
	                }
	            }
            }
        }
    </script>
@endsection