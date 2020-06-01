<div id="nav-bar-active">
	@switch($slug)
		@case('user-create')
	        <a class="nav-btn-back" href="{{ route('showListUser') }}">
	            Users management
	        </a> / <span class="nav-title">Create user</span>
	        @break

	    @case('user-detail')
	        <a class="nav-btn-back" href="{{ route('showListUser') }}">
	            Users management
	        </a> / <span class="nav-title">User detail</span>
	        @break

	    @case('user-trash')
	        <a class="nav-btn-back" href="{{ route('showListUser') }}">
	            Users management
	        </a> / <span class="nav-title">Deleted Users</span>
	        @break

	    @case('client-create')
	        <a class="nav-btn-back" href="{{ route('showListClient') }}">
	            Clients management
	        </a> / <span class="nav-title">Create Client</span>
	        @break

	    @case('client-detail')
	        <a class="nav-btn-back" href="{{ route('showListClient') }}">
	            Clients management
	        </a> / <span class="nav-title">{{ ucwords($clientDetail['name']) }}</span>
	        <div class="d-sm-flex align-items-center justify-content-between float-md-right dropdown-menu-right">
				<a href="{{ route('showListTrashLocation', $clientDetail['id']) }}" class="in-trash-user">View Deleted Locations</a>
				@can('create clients and client locations')
				    <a href="{{ route('showCreateLocation', $clientDetail['id']) }}" class="mg-right-20">
						<button type="button" class="d-none d-sm-inline-block btn btn-create-style">NEW LOCATION</button>
				    </a>
			    @endcan
				@php
					$checkShow = (DB::table('cases')->where('client_id', $clientDetail['id'])->count() == 0) ? true : false;
				@endphp
				@if ($checkShow)
				    <button type="button" class="d-sm-inline-block btn btn-create-style btn-action-user dropdown-toggle" data-toggle="dropdown">ACTION</button>
		            <div class="dropdown-menu dropdown-menu-right">
						@can('edit client and client locations information')
							<a class="dropdown-item cursor-pointer change-status">
			                    {{ ($clientDetail['status'] == 0) ? 'Activate Client' : 'Deactivate Client' }}
			                </a>
			                <input type="hidden" value="{{ $clientDetail['status'] }}" id="status">
		                @endcan
		                @can('delete clients and client locations')
							<a href="javascript:void(0);" class="dropdown-item text-danger font-weight-bold" onclick="deleteClient()">Delete Client</a>
		                @endcan
		            </div>
	            @endif
			</div>
	        @break

	    @case('client-trash')
	        <a class="nav-btn-back" href="{{ route('showListClient') }}">
	            CLients management
	        </a> / <span class="nav-title">Deleted Clients</span>
	        @break

	    @case('location-create')
	        <a class="nav-btn-back" href="{{ route('showListClient') }}">
	            Clients management
	        </a> /
	        <a class="nav-btn-back" href="{{ route('showDetailClient', $clientDetail['id']) }}">
	            {{ ucwords($clientDetail['name']) }}
	        </a> / <span class="nav-title">New location</span>
	        @break

	    @case('location-detail')
	        <a class="nav-btn-back" href="{{ route('showListClient') }}">
	            Clients management
	        </a> /
	        <a class="nav-btn-back" href="{{ route('showDetailClient', $clientDetail['id']) }}">
	            {{ ucwords($clientDetail['name']) }}
	        </a> / <span class="nav-title">{{ ucwords($locationDetail['name']) }}</span>
	        <div class="d-sm-flex align-items-center justify-content-between float-md-right">
		        <button type="button" class="d-sm-inline-block btn btn-create-style btn-action-user dropdown-toggle" data-toggle="dropdown">ACTION</button>
	            <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item action-location view-cases-location">View Cases</a>
                    <a href="#" class="dropdown-item action-location view-invoices-location">View Invoices</a>
                    <a href="#" class="dropdown-item action-location create-invoice-location mb-0">Create Invoice</a>
                    @php
						$checkShow = false;
						if (DB::table('locations')->where('id', $locationDetail['id'])->where('trash', 0)->count() == 1) {
							$checkShow = true;
							if (DB::table('cases')->where('location_id', $locationDetail['id'])->count() > 0) {
								$checkShow = false;
							}
						}

						$checkDeleteLocation = DB::table('locations')->where('id', $clientDetail['id'])->where('trash', 0)->count();
					@endphp
					@if ($checkShow)
						<hr class="mt-0 mb-0">
						@can('edit client and client locations information')
							<a href="#" class="dropdown-item action-location deactivate-location" style="color: {{ ($locationDetail['status'] == 0) ? '#23282c' : '#FF0000' }}">{{ ($locationDetail['status'] == 0) ? 'Activate Location' : 'Deactivate Location' }}</a>
							<input type="hidden" value="{{ $locationDetail['status'] }}" id="status-location">
	                    @endcan

	                    @if ($checkDeleteLocation >= 1)
							@can('delete clients and client locations')
								<a href="#" class="dropdown-item action-location move-location-to-trash" style="color:#FF0000;">Delete Location</a>
		                    @endcan
	                    @endif
                    @endif
	            </div>
			</div>
	        @break

	    @case('location-trash')
	        <a class="nav-btn-back" href="{{ route('showListClient') }}">
	            CLients management
	        </a> /
	        <a class="nav-btn-back" href="{{ route('showDetailClient', $clientDetail['id']) }}">
	            {{ ucwords($clientDetail['name']) }}
	        </a>
	        @break

	    @case('role-create')
	        <a class="nav-btn-back" href="{{ route('showListRoles') }}">
	            Roles management
	        </a> / <span class="nav-title">Create Role</span>
	        @break

	    @case('role-detail')
	        <a class="nav-btn-back" href="{{ route('showListRoles') }}">
	            Roles management
	        </a> / <span class="nav-title">Role Detail</span>
	        @break

	    @case('rate-create')
	        <a class="nav-btn-back" href="{{ route('showListRate') }}">
	            Rates management
	        </a> / <span class="nav-title">Create Rate</span>
	        @break

	    @case('rate-detail')
	        <a class="nav-btn-back" href="{{ route('showListRate') }}">
	            Rates management
	        </a> / <span class="nav-title">Rate Detail</span>
	        @can('delete rates')
		        <div class="d-sm-flex align-items-center justify-content-between float-md-right dropdown-menu-right">
			        <button type="button" class="d-sm-inline-block btn btn-create-style btn-action-user dropdown-toggle" data-toggle="dropdown">ACTION</button>
		            <div class="dropdown-menu dropdown-menu-right">
	                    <a href="javascript:void(0);" class="dropdown-item text-danger font-weight-bold" onclick="deleteRate()">Delete Rate</a>
		            </div>
		        </div>
            @endcan
	        @break

	    @case('matter-detail-information')
	        <a class="nav-btn-back" href="{{ route('getListMatter') }}">
	            Matter management
	        </a> / <span class="nav-title">Matter detail</span>
	        @break


	    @default
	        <span></span>
	@endswitch
</div>