<link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>

<select class="form-control selectpicker custom-users-assign-select" name="assign_users[]" id="assign-users" data-live-search="true" data-selected-text-format="count" title="Select User" @if(count($listUsers) == 0) disabled @endif multiple>
    @foreach ($listUsers as $user)
    	<option value="{{ $user->id }}">{{ $user->name }}</option>
    @endforeach
</select>

<script>
	$.fn.selectpicker.Constructor.BootstrapVersion = '4';

	$('.custom-users-assign-select').selectpicker({
	    style: 'border-btn-select',
	    liveSearchPlaceholder: 'Search',
	    tickIcon: 'checkbox-select checkmark-select',
	    size: 5
	});
</script>