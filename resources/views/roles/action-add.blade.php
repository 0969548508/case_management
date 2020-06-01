@foreach ($permission['list_sub'] as $keySub => $sub)
	@php
		$subSlug = str_replace(' ', '-', $sub['slug']);
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
						<input id="{{$actionName}}" class="role-checkbox cbox-{{$subSlug}}" type="checkbox" name="permission[]" value="{{$action['name']}}" onClick="toggle('{{$actionName}}', '{{$subSlug}}')"><span class="checkmark"></span>
						<span class="role-sub-title">
							{{ ucwords($title) }}
						</span><br>
						<span class="ml-2 role-sub-note">
							{{ $note }}
						</span><br>
						<div class="row ml-2">
							@foreach ($action['sub_action'] as $subAction)
								<label class="col-12 col-md-4 col-xl-3 sub-action">
									<input id="{{ $subAction['name'] }}" class="role-checkbox cbox-{{$subSlug}} sub-cbox-{{$actionName}}" type="checkbox" name="permission[]" value="{{ $subAction['name'] }}" onClick="toggle('{{$subAction['name']}}', '{{$subSlug}}', '{{$actionName}}')"><span class="checkmark"></span>
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
							<input id="{{$actionName}}" class="role-checkbox cbox-{{$subSlug}}" type="checkbox" name="permission[]" value="{{$action['name']}}" onClick="toggle('{{$actionName}}', '{{$subSlug}}')"><span class="checkmark"></span>
							<span class="role-sub-title">
								{{ ucwords(Lang::get('roles.titles.' . $action['name'])) }}
							</span>
						</label>
					@else
						@if (in_array($sub['slug'], array('audit log', 'password policy')))
							<label class="col-12 col-md-4 col-xl-2 sub-action">
								<input id="{{$actionName}}" class="role-checkbox cbox-{{$subSlug}}" type="checkbox" name="permission[]" value="{{$action['name']}}" onClick="toggle('{{$actionName}}', '{{$subSlug}}')"><span class="checkmark"></span>
								<span class="role-sub-title">
									{{ ucwords(Lang::get('roles.titles.' . $action['name'])) }}
								</span>
							</label>
						@else
							<label class="col-12 border-bottom-action">
								<input id="{{$actionName}}" class="role-checkbox cbox-{{$subSlug}}" type="checkbox" name="permission[]" value="{{$action['name']}}" onClick="toggle('{{$actionName}}', '{{$subSlug}}')"><span class="checkmark"></span>
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