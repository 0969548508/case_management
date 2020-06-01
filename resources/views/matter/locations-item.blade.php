<link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>

<select class="form-control selectpicker custom-location-select" name="location_id" id="location-add" data-live-search="true" title="Select Location" required @if(count($listLocation) == 0) disabled @endif>
    @foreach ($listLocation as $location)
    	<option value="{{ $location->id }}">{{ $location->name }}</option>
    @endforeach
</select>

<script>
	$.fn.selectpicker.Constructor.BootstrapVersion = '4';

	$('.custom-location-select').selectpicker({
	    style: 'border-btn-select',
	    liveSearchPlaceholder: 'Search',
	    tickIcon: 'checkbox-select checkmark-select',
	    size: 5
	});
</script>