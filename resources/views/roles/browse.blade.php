@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-30">
	<h1 class="title mb-0">Role Management</h1>
	@can('create roles')
		<a href="{{ route('viewcreateRole') }}"><button type="button" class="d-none d-sm-inline-block btn btn-create-style">CREATE ROLE</button></a>
	@endcan
</div>
<div class="row">
	<div class="table-responsive mx-20">
		<table width="100%" id="role_table" class="table">
			<thead>
				<tr class="column-name">
					<th width="25%">Role</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody class="column-content bg-white">
				@foreach($listRoles as $role)
					<tr>
						<td class="role-column">
							<a href="@can('edit roles'){{ route('getRoleDetail', $role->id) }}@else javascript:void(0); @endcan">{{ ucwords($role->name) }}</a>
						</td>
						<td class="desc-column">
							{{ $role->description }}
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		<script type="text/javascript">
		$(document).ready(function() {
			$.fn.DataTable.ext.pager.numbers_length = 5;
			$('#role_table').DataTable({
				"pagingType": "full_numbers",
				"language": {
					"paginate": {
						"first": '&Iota;<i class="fa fa-angle-left"></i>',
						"previous": '<i class="fa fa-angle-left"></i>',
						"next": '<i class="fa fa-angle-right"></i>',
						"last": '<i class="fa fa-angle-right"></i>&Iota;'
					},
					"lengthMenu": "Show <b>_MENU_ rows</b>",
					"info": "Total _TOTAL_ entries",
				},
				"sDom": 'Rfrtlip',
				"order": [[0, "asc"]],
			});
		});
		/* Change location of sort icon-datatable */
		var spanSorting = '<span class="arrow-hack sort">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
			spanAsc = '<span class="arrow-hack asc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>',
			spanDesc = '<span class="arrow-hack desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';

		$("#role_table").on('click', 'th', function() {
			$("#role_table thead th").each(function(i, th) {
				$(th).find('.arrow-hack').remove();
				var html = $(th).html(),
				cls = $(th).attr('class');
				switch (cls) {
					case 'sorting_asc' : 
						$(th).html(html+spanAsc); break;
					case 'sorting_desc' :
						$(th).html(html+spanDesc); break;
					default : 
						$(th).html(html+spanSorting); break;
				}
			});
		});

		$("#role_table th").first().click().click();
		/* End */
		</script>
	</div>
</div>
@endsection
