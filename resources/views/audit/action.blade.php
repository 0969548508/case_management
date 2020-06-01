@php
    $actionList = $audit->getModified();
@endphp
@if ($audit->tags != 'matter')
    @switch($audit->event)
        @case('created')
            @php
                $name = "'" . $actionList['name']['new'] . "'";

                if ($audit->tags == 'user') {
                    $name = "'" . $actionList['name']['new'] . ' ' . isset($actionList['family_name']['new']) . "'";
                }
            @endphp
            <b class="text-success">Added</b> new {{ $audit->tags }} <b>{{ ucwords($name) }}</b>
        @break

        @case('deleted')
            @php
                $name = "'" . $actionList['name']['old'] . "'";

                if ($audit->tags == 'user') {
                    $name = "'" . $actionList['name']['old'] . ' ' . isset($actionList['family_name']['old']) . "'";
                }
            @endphp
            <b class="text-danger">Deleted</b> {{ $audit->tags }} <b>  {{ ucwords($name) }}</b>
        @break

        @case('updated')
            @if (!empty($actionList))
                @if (count($actionList) > 1 || in_array(array_keys($actionList)[0], array('remember_token')))
                    @php
                    $listFields = array_keys($actionList);
                    foreach ($listFields as &$field) {
                        if (strpos($field, '_'))
                            $field = Lang::get('audit.' . $field);
                    }
                    $listFields = implode(', ', $listFields);
                    @endphp
                    <b class="text-info">Updated</b> <b>{{ ucwords($listFields) }}</b> fields in {{ $audit->tags }} <b class="font-italic">{{ ucwords($name) }}</b>
                @else
                    @php
                        $listField = array_keys($actionList)[0];

                        $field = (strpos($listField, '_')) ? Lang::get('audit.' . $listField) : $listField;

                        $oldVal = $actionList[$listField]['old'];
                        $newVal = $actionList[$listField]['new'];

                        if (in_array($listField, array('status', 'in_trash', 'trash'))) {
                            $oldVal = (strval($oldVal) == '1') ? 'active' : 'inactive';

                            $newVal = (strval($newVal) == '1') ? 'active' : 'inactive';

                        }
                    @endphp
                    @if (in_array($listField, array('in_trash', 'trash')))
                        @if ($newVal == 'active')
                            <b>Move</b> {{ $audit->tags }} <b class="font-italic">{{ ucwords($name) }}</b> to trash
                        @else
                            <b>Restore</b> {{ $audit->tags }} <b class="font-italic">{{ ucwords($name) }}</b>
                        @endif
                    @else
                    <b class="text-info">Updated</b> <b>{{ ucwords($field) }}</b> field from <b>{{ !empty($oldVal) ? $oldVal : '" "' }}</b> to <b>{{ !empty($newVal) ? $newVal : '" "' }}</b> in {{ $audit->tags }} <b class="font-italic">{{ ucwords($name) }}</b>
                    @endif
                @endif
            @endif
        @break

        @default
        @break
    @endswitch
@else
    @switch($audit->event)
        @case('created')
            @php
                $name = "'" . $actionList['case_number']['new'] . "'";
            @endphp
            <b class="text-success">Added</b> new {{ $audit->tags }} <b>{{ ucwords($name) }} </b> with status <b>'not-assigned'</b>
        @break

        @case('deleted')
        @break

        @case('updated')
            @if (!empty($actionList))
                @if (count($actionList) > 1)
                    @php
                    $listFields = array_keys($actionList);
                    foreach ($listFields as &$field) {
                        if (strpos($field, '_'))
                            $field = Lang::get('audit.' . $field);
                    }
                    $listFields = implode(', ', $listFields);
                    @endphp
                    <b class="text-info">Updated</b> <b>{{ ucwords($listFields) }}</b> fields in {{ $audit->tags }} <b class="font-italic">{{ ucwords($name) }}</b>
                @else
                    @php
                        $listField = array_keys($actionList)[0];

                        $field = (strpos($listField, '_')) ? Lang::get('audit.' . $listField) : $listField;

                        $oldVal = $actionList[$listField]['old'];
                        $newVal = $actionList[$listField]['new'];
                    @endphp
                    <b class="text-info">Updated</b> <b>{{ ucwords($field) }}</b> field from <b>{{ !empty($oldVal) ? "'" . $oldVal . "'" : '" "' }}</b> to <b>{{ !empty($newVal) ? "'" . $newVal . "'" : '" "' }}</b> in {{ $audit->tags }} <b class="font-italic">{{ ucwords($name) }} </b>
                @endif
            @endif
        @break

        @default
        @break
    @endswitch
@endif