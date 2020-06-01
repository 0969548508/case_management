@if(!empty($valueSearch))
    @if(!empty($items))
        <ul class="ul-sussgestion sussgestion-scroll">
            @foreach($items as $key => $item)
                @if (count($item) > 0)
                    <li class="sussgestion-header">
                        {{ ucwords($key) }}
                    </li>
                @endif
                @switch ($key)
                    @case ('user')
                        @foreach ($item as $user)
                            <li class="sussgestion-item">
                                <a href="@if ($user->in_trash) {{ route('showListTrashUser') }} @else {{ route('showDetailUser', $user->id) }} @endif">
                                    @if (empty($user->image))
                                        <div class="member member-sussgestion float-left">
                                            <span class="avata-client member-initials member-initials-sussgestion">{{ substr($user->name, 0, 1) . '' . substr($user->family_name, 0, 1) }}</span>
                                        </div>
                                    @else
                                        <?php
                                            $url = App\Repositories\Users\UserRepository::loadImageUserLogin($user->id);
                                        ?>
                                        <img src="{{ $url }}" class="avata-sussgestion float-left">
                                    @endif
                                    <div class="sussgestion-title">
                                        {{ ucwords($user->name) . ' ' . ucwords($user->family_name) }}
                                        <span class="sussgestion-status 
                                            @switch($user->status)
                                                @case('inactive') sussgestion-inactive @break
                                                @case('active') sussgestion-active @break
                                                @default sussgestion-pending
                                            @endswitch">
                                            @switch($user->status)
                                                @case('inactive') Inactive @break
                                                @case('active') Active @break
                                                @default Pending
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="sussgestion-content">
                                        {{ $user->email }}
                                    </div>
                                </a>
                            </li>
                        @endforeach
                        @break

                    @case ('matter')
                        @foreach ($item as $matter)
                            <li class="sussgestion-item">
                                <a href="">
                                    <div class="sussgestion-title">
                                        {{ ucwords($matter->case_number) }}
                                        @switch($matter->last_state)
                                            @case('not-assigned')
                                                <span class="sussgestion-status background-status-not-assigned">
                                                    Not assigned
                                                </span>

                                                @break
                                            @case('to-do')
                                                <span class="sussgestion-status background-status-to-do">
                                                    To-do
                                                </span>
                                                @break
                                            @case('in-progress')
                                                <span class="sussgestion-status background-status-in-progress">
                                                    In-progress
                                                </span>
                                                @break
                                            @case('need-review')
                                                <span class="sussgestion-status background-status-in-progress">
                                                    In-progress
                                                </span>
                                                <span class="sussgestion-status background-status-need-review" style="display: inline-block;">Need to review</span>
                                                @break
                                            @case('billing')
                                                <span class="sussgestion-status background-status-in-progress">
                                                    In-progress
                                                </span>
                                                <span class="sussgestion-status background-status-billing">Billing</span>
                                                @break
                                            @case('cancelled')
                                                <span class="sussgestion-status background-status-cancelled">
                                                    Cancelled
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="sussgestion-status background-status-completed">
                                                    Completed
                                                </span>
                                                @break
                                            @case('on-hold')
                                                <span class="sussgestion-status background-status-on-hold">
                                                    On-hold
                                                </span>
                                                @break
                                            @case('invalid')
                                                <span class="sussgestion-status background-status-invalid">
                                                    Invalid
                                                </span>
                                                @break
                                            @case('withdrawn')
                                                <span class="sussgestion-status background-status-withdrawn">
                                                    Withdrawn
                                                </span>
                                                @break
                                            @default
                                                @break
                                        @endswitch
                                    </div>
                                    <div class="sussgestion-content">
                                        @php
                                            $typeName = App\Repositories\SpecificMatters\SpecificMattersRepository::getTypeBySubType($matter->type_id);
                                        @endphp
                                        <span class="font-weight-bold">{{ $typeName->parent[0]->name }}</span>/{{ $typeName->name }}
                                    </div>
                                </a>
                            </li>
                        @endforeach
                        @break

                    @case ('client')
                        @foreach ($item as $client)
                            <li class="sussgestion-item">
                                <a href="@if ($client->in_trash) {{ route('showListTrashClient') }} @else {{ route('showDetailClient', $client->id) }} @endif">
                                    @if (empty($client->image))
                                        <div class="member member-sussgestion float-left">
                                            <span class="avata-client member-initials member-initials-sussgestion">
                                                {{ substr($client->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @else
                                        <?php
                                            $url = App\Repositories\Clients\ClientsRepository::loadImageForClient($client->id, $client->image);
                                        ?>
                                        <img src="{{ $url }}" class="avata-sussgestion float-left">
                                    @endif
                                    <div class="sussgestion-title">
                                        {{ ucwords($client->name) }}
                                        <span class="sussgestion-status @if ($client->status) sussgestion-active @else sussgestion-inactive @endif">
                                            {{ ($client->status) ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <div class="sussgestion-content">
                                        ABN: {{ $client->abn }}
                                    </div>
                                </a>
                            </li>
                        @endforeach
                        @break

                    @default
                        @break

                @endswitch
            @endforeach
        </ul>
    @else
        <ul class="ul-sussgestion">
            <li class="sussgestion-item">
                No result
            </li>
        </ul>
    @endif
@endif
