@extends('layouts.app')
<?php
    $typeEmail = array('work', 'personal', 'others');
    $typePhone = array('work', 'home', 'mobile', 'fax', 'others');

    $primaryPhone['id'] = '';
    $primaryPhone['phone_number'] = '';
    $primaryPhone['type_name'] = '';
    $primaryPhone['is_primary'] = '';
    foreach ($phoneInfo as $phone) {
        if ($phone['is_primary'] == 1){
            $primaryPhone = $phone;
        }
    }
?>

@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            @include('navbar')
            <button type="button" class="d-sm-inline-block btn btn-create-style btn-action-user dropdown-toggle" data-toggle="dropdown" id="btn-action-user">ACTION</button>
            <div class="dropdown-menu dropdown-menu-right dropdown-action-user">
                <a class="dropdown-item cursor-pointer">View Cases</a>
                <a class="dropdown-item cursor-pointer">Price List</a>
                <hr class="m-auto w-75 mg-top-5 mg-bottom-5">
                <a class="dropdown-item cursor-pointer change-status {{ ($detailUser['id'] == Auth::id()) ? 'disabled custum-opacity' : '' }} {{ ($detailUser['status'] == 'active') ? 'text-danger font-weight-bold' : '' }}">{{ ($detailUser['status'] == 'inactive' || $detailUser['status'] == null) ? 'Activate User' : 'Deactivate User' }}
                </a>
                <input type="hidden" value="{{ $detailUser['status'] }}" id="status">
                @can('delete users')
                    @if (auth()->user()->id != $detailUser['id'] && DB::table('cases')->where('user_id', $detailUser['id'])->count() == 0 && DB::table('cases_users')->where('user_id', $detailUser['id'])->count() == 0)
                        <a href="javascript:void(0);" class="dropdown-item text-danger font-weight-bold" onclick="deleteUser()">Delete User</a>
                    @endif
                @endcan
            </div>
        </div>
        <div class="row avartar">
            <form id="upload-image-form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="imgUp">
                    <div class="avataPreview member-client-detail text-center float-left">
                        <span class="member-initials-client-detail">{{ substr($detailUser['name'], 0, 1) . '' . substr($detailUser['family_name'], 0, 1) }}</span>
                        @can('edit users')
                            <label class="btn btn-upload-avata-client">
                                <span id="title-upload">
                                    Edit
                                </span>
                                <input id="upload-image" type="file" class="uploadFile img w-0 h-0 overflow-hidden" name="image">
                            </label>
                        @endcan
                    </div>
                </div>
            </form>
            <div class="pt-2">
                <span class="title user-name" style="padding-right: 12px;">{{ $detailUser['name'] . ' ' . $detailUser['family_name'] }}</span>
                <div class="position-user">Investigator</div>&nbsp;&nbsp;
                <div class="status-user
                    @switch($detailUser['status'])
                        @case('inactive') inactive-user @break
                        @case('active') active-user @break
                        @default pending-user
                    @endswitch">
                    @switch($detailUser['status'])
                        @case('inactive') Inactive @break
                        @case('active') Active @break
                        @default Pending
                    @endswitch
                </div>
                <table class="table-style" width="100%">
                    <tr class="align-content-center">
                        <td class="min-width-120">
                            <div class="card left-line-purple p-2 mr-2 bg-white cursor-pointer show-matter-list">
                                <span class="text-act">To-do</span><br/>
                                <div class="text-number-purple">{{ App\Repositories\Matter\MatterRepository::countStatusMatterByUser('to-do', $detailUser['id']) }}</div>
                            </div>
                        </td>

                        <td class="min-width-120">
                            <div class="card left-line-green p-2 mr-2 bg-white cursor-pointer show-matter-list">
                                <span class="text-act">In-progress</span><br/>
                                <div class="text-number-green">{{ App\Repositories\Matter\MatterRepository::countStatusMatterByUser('in-progress', $detailUser['id']) }}</div>
                            </div>
                        </td>

                        <td class="min-width-120">
                            <div class="card left-line-red p-2 mr-2 bg-white cursor-pointer show-matter-list">
                                <span class="text-act">Waiting Reviews</span><br/>
                                <div class="text-number-red">{{ App\Repositories\Matter\MatterRepository::countStatusMatterByUser('need-review', $detailUser['id']) }}</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-8 col-sm-12 col-md-12">
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="title-detail-user mb-0">Personal Information</h3>
                <hr>

                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12">
                        <div class="row">
                            <label for="name" class="col-md-12 col-sm-12 col-form-label text-md-left custum-opacity">{{ __('Personal Details') }}</label>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                                <b>{{ __('Name: ') }}</b> <span id="show-name">{{ $detailUser['name'] }}</span> &nbsp;
                                @can('edit users')
                                    <a href="" data-toggle="collapse" data-target="#add-name" onclick="saveOldName()">
                                        <img src="/images/btn_pen.png">
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="row collapse bg-collapse mb-2" id="add-name">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="row pt-2">
                                            <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                                                <span class="custum-opacity">{{ __('Give name*') }}</span>
                                                <input id="given-name" type="text" class="form-control" name="name" required autocomplete="given-name" autofocus value="@if($detailUser['name']){{ $detailUser['name'] }}@endif">
                                            </div>

                                            <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                                                <span class="custum-opacity">{{ __('Middle name') }}</span>
                                                <input id="middle-name" type="text" class="form-control" name="middle_name" required autocomplete="middle-name" value="@if($detailUser['middle_name']){{ $detailUser['middle_name'] }}@endif">
                                            </div>

                                            <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                                                <span class="custum-opacity">{{ __('Family name*') }}</span>
                                                <input id="family-name" type="text" class="form-control" name="family_name" required autocomplete="family-name" value="@if($detailUser['family_name']){{ $detailUser['family_name'] }}@endif">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-right">
                                                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-name">
                                                    {{ __('Cancel') }}
                                                </button>
                                                <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-name">
                                                    {{ __('Save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                            $date_of_birth = date('d/m/Y', strtotime($detailUser['date_of_birth']));
                        ?>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <b>{{ __('Date of Birth: ') }}</b> <span id="show-birthdate">{{ isset(($detailUser['date_of_birth'])) ? $date_of_birth : $detailUser['date_of_birth'] }}</span> &nbsp;
                                @can('edit users')
                                    <a href="" data-toggle="collapse" data-target="#add-birthdate">
                                        <img src="/images/btn_pen.png">
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 col-sm-5 col-lg-5 mt-1">
                                <div class="row pt-3 pb-3 collapse bg-collapse mb-2" id="add-birthdate">
                                    <div class="col-md-6 col-sm-6 col-lg-6 inner-addon right-addon">
                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                        <input id="date-of-birth" type="text" class="form-control date-picker" name="date_of_birth" autocomplete="off" placeholder="DD/MM/YYYY" value="{{ isset(($detailUser['date_of_birth'])) ? $date_of_birth : $detailUser['date_of_birth'] }}">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 pt-2">
                                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-birthdate">
                                            {{ __('Cancel') }}
                                        </button>
                                        <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-birthdate">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label for="name" class="col-md-12 col-sm-12 col-form-label text-md-left mg-top-30 custum-opacity pl-0">{{ __('Emails') }}</label>
                                <div class="row col-md-12 col-sm-12 col-form-label text-md-left">
                                    <b>{{ __('Primary email:') }}</b> &nbsp;{{ $detailUser['email'] }}
                                </div>
                                <div id="show-mails">
                                    @foreach($emailInfo as $email)
                                        <div class="row col-md-12 col-sm-12 col-form-label text-md-left">
                                            <b>{{ ucfirst($email['type_name']) }}:</b> &nbsp; <span id="email-{{ $email['id'] }}">{{ $email['email'] }}</span> &nbsp;
                                            @can('edit users')
                                                <span class="cursor-pointer" data-toggle="collapse" data-target="#update-email-{{ $email['id'] }}">
                                                    <img src="/images/btn_pen.png">
                                                </span>
                                                <span class="cursor-pointer" onclick="deleteEmail('{{ route('deleteUserEmail', $email['id']) }}')">
                                                    @if(isset($email['email']))
                                                        &nbsp;&nbsp;
                                                        <img src="/images/img-delete.png" id="delete-email-{{ $email['id'] }}">
                                                    @endif
                                                </span>
                                            @endcan
                                        </div>

                                        <div class="row collapse bg-collapse mt-2" id="update-email-{{ $email['id'] }}">
                                            <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                                                        <select class="form-control" name="type-email-update-{{ $email['id'] }}" id="type-email-update-{{ $email['id'] }}" onchange="changeTypeEmail('{{ $email['id'] }}')">
                                                            @foreach($typeEmail as $key => $type)
                                                                @if (!in_array(strtolower($email['type_name']), $typeEmail) && $key == 0)
                                                                <option selected>{{ ucwords($email['type_name']) }}</option>
                                                                @endif
                                                                <option @if (strtolower($email['type_name']) == $type) selected @endif>{{ ucwords($type) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" id="input-type-email-update-{{ $email['id'] }}" class="form-control mt-3 d-none" required value="" autocomplete="off" placeholder="Input name type">
                                                    </div>

                                                    <div class="col-lg-8 col-md-12 col-sm-12 col-form-label text-md-left">
                                                        <input id="email-update-{{ $email['id'] }}" type="email" class="form-control" name="email-update-{{ $email['id'] }}" required autocomplete="email" autofocus placeholder="Input email" value="{{ isset($email['email']) ? $email['email'] : '' }}">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-right">
                                                        <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-edit-email" onclick="cancelEditEmail('{{ $email["id"] }}')">
                                                            {{ __('Cancel') }}
                                                        </button>
                                                        <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editMail('{{ $detailUser["id"] }}', '{{ $email["id"] }}');">
                                                            {{ __('Save') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @can('edit users')
                                    <div class="row col-md-12 col-sm-12">
                                        <a href="" data-toggle="collapse" data-target="#add-email">
                                            <img src="/images/btn_plus.png">
                                            <i class="custom-font">Add email</i>
                                        </a>
                                    </div>
                                @endcan

                                <div class="row collapse bg-collapse mt-2" id="add-email">
                                    <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                                                <select class="form-control" name="type-email" id="type-email">
                                                    @foreach($typeEmail as $type)
                                                        <option>{{ ucwords($type) }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" id="input-type-email" class="form-control mt-3 d-none" required value="" autocomplete="off" placeholder="Input name type">
                                            </div>

                                            <div class="col-lg-8 col-md-12 col-sm-12 col-form-label text-md-left">
                                                <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus placeholder="Input email">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-form-label text-md-right">
                                                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-email">
                                                    {{ __('Cancel') }}
                                                </button>
                                                <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-email">
                                                    {{ __('Save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12" id="save-phone">
                                <label for="name" class="col-md-6 col-sm-12 col-form-label text-md-left mg-top-30 custum-opacity pl-0">{{ __('Phones') }}</label>
                                <div id="show-phones">
                                    <div class="row col-md-12 col-sm-12 col-form-label text-md-left">
                                        <b>
                                            @if($primaryPhone['phone_number'] != '') {{ ucwords($primaryPhone['type_name']) }}
                                            @else {{ __('Mobile') }}
                                            @endif <span id="primary-{{ $primaryPhone['id'] }}" class="all-primary">{{ __('(Primary)') }}</span> :&nbsp;
                                        </b>
                                        <span id="phone-number-{{ $primaryPhone['id'] }}">{{ $primaryPhone['phone_number'] }}</span> &nbsp;

                                        @if($primaryPhone['phone_number'] != '')
                                            @can('edit users')
                                                <span class="cursor-pointer" data-toggle="collapse" data-target="#update-phone-{{ $primaryPhone['id'] }}">
                                                    <img src="/images/btn_pen.png">
                                                </span>
                                                <span class="cursor-pointer" onclick="deletePhone('{{ route('deleteUserPhone', $primaryPhone['id']) }}')">
                                                        &nbsp;&nbsp;
                                                    <img src="/images/img-delete.png" id="delete-phone-{{ $primaryPhone['id'] }}">
                                                </span>
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="row collapse bg-collapse mt-2" id="update-phone-{{ $primaryPhone['id'] }}">
                                        <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                                                    <select class="form-control" name="type-phone" id="type-phone-update-{{ $primaryPhone['id'] }}" onchange="changeTypePhone('{{ $primaryPhone['id'] }}')">
                                                    @foreach($typePhone as $key => $type)
                                                        @if (!in_array(strtolower($primaryPhone['type_name']), $typePhone) && $key == 0)
                                                            <option selected>{{ ucwords($primaryPhone['type_name']) }}</option>
                                                            <option id="type-phone-hide-{{$primaryPhone['id']}}" hidden value="{{ $primaryPhone['type_name'] }}"></option>
                                                        @endif
                                                        @if(strtolower($primaryPhone['type_name']) == $type)
                                                            <option selected>{{ ucwords($type) }}</option>
                                                            <option id="type-phone-hide-{{$primaryPhone['id']}}" hidden value="{{ $type }}"></option>
                                                        @else
                                                            <option>{{ ucwords($type) }}</option>
                                                        @endif
                                                    @endforeach
                                                    </select>
                                                    <input type="text" id="input-type-phone-update-{{ $primaryPhone['id'] }}" class="form-control mt-3 d-none" required value="" autocomplete="off" placeholder="Input phone type">
                                                </div>

                                                <div class="col-lg-8 col-md-12 col-sm-12 col-form-label text-md-left">
                                                    <input id="phone-update-{{ $primaryPhone['id'] }}" type="text" class="form-control" required autocomplete="phone" autofocus placeholder="Input number" value="{{ isset($primaryPhone['phone_number']) ? $primaryPhone['phone_number'] : '' }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-left">
                                                    <label class="col-lg-12">
                                                        <input id="set-primary-phone-{{ $primaryPhone['id'] }}" class="role-checkbox number-phone" type="checkbox" name="set-primary-phone-{{ $primaryPhone['id'] }}" value="{{ $primaryPhone['is_primary'] }}" onclick="$(this).attr('value', this.checked ? 1 : 0)"  {{ ($primaryPhone['is_primary'] == 1) ? 'checked' : '' }}><span class="checkmark"></span> <span>Set as primary</span>
                                                        <input id="set-primary-phone-hide-{{ $primaryPhone['id'] }}" value="{{ $primaryPhone['is_primary'] }}" hidden {{ ($primaryPhone['is_primary'] == 1) ? 'checked' : '' }}>
                                                    </label>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-right">
                                                    <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditPhone('{{ $primaryPhone["id"] }}')">
                                                        {{ __('Cancel') }}
                                                    </button>
                                                    <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editPhone('{{ $primaryPhone["id"] }}')">
                                                        {{ __('Save') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($phoneInfo as $phone)
                                        @if($phone['is_primary'] != 1)
                                            <div class="row col-md-12 col-sm-12 col-form-label text-md-left">
                                                <b>{{ ucwords($phone['type_name']) }} <span id="primary-{{ $phone['id'] }}" class="all-primary"></span> : </b> &nbsp; <span id="phone-number-{{ $phone['id'] }}">{{ $phone['phone_number'] }}</span> &nbsp;
                                                <span class="cursor-pointer" data-toggle="collapse" data-target="#update-phone-{{ $phone['id'] }}">
                                                    <img src="/images/btn_pen.png">
                                                </span>
                                                <span class="cursor-pointer" onclick="deletePhone('{{ route('deleteUserPhone', $phone['id']) }}')">
                                                    @if(isset($phone['phone_number']))
                                                        &nbsp;&nbsp;
                                                        <img src="/images/img-delete.png" id="delete-phone-{{ $phone['id'] }}">
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="row collapse bg-collapse mt-2" id="update-phone-{{ $phone['id'] }}">
                                                <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                                                            <select class="form-control" name="type-phone" id="type-phone-update-{{ $phone['id'] }}" onchange="changeTypePhone('{{ $phone['id'] }}')">
                                                            @foreach($typePhone as $key => $type)
                                                                @if (!in_array(strtolower($phone['type_name']), $typePhone) && $key == 0)
                                                                    <option selected>{{ ucwords($phone['type_name']) }}</option>
                                                                    <option id="type-phone-hide-{{$phone['id']}}" hidden value="{{ $phone['type_name'] }}"></option>
                                                                @endif
                                                                @if(strtolower($phone['type_name']) == $type)
                                                                    <option selected>{{ ucwords($type) }}</option>
                                                                    <option id="type-phone-hide-{{$phone['id']}}" hidden value="{{ $type }}"></option>
                                                                @else
                                                                    <option>{{ ucwords($type) }}</option>
                                                                @endif
                                                            @endforeach
                                                            </select>
                                                            <input type="text" id="input-type-phone-update-{{ $phone['id'] }}" class="form-control mt-3 d-none" required value="" autocomplete="off" placeholder="Input phone type">
                                                        </div>

                                                        <div class="col-lg-8 col-md-12 col-sm-12 col-form-label text-md-left">
                                                            <input id="phone-update-{{ $phone['id'] }}" type="text" class="form-control" required autocomplete="phone" autofocus placeholder="Input number" value="{{ isset($phone['phone_number']) ? $phone['phone_number'] : '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-left">
                                                            <label class="col-lg-12">
                                                                <input id="set-primary-phone-{{ $phone['id'] }}" class="role-checkbox number-phone" type="checkbox" name="set-primary-phone-{{ $phone['id'] }}" value="{{ $phone['is_primary'] }}" onclick="@if($primaryPhone['phone_number'] != '') changeValueUpdatePhone('{{ $phone["id"] }}'); @else $(this).attr('value', this.checked ? 1 : 0) @endif"  {{ ($phone['is_primary'] == 1) ? 'checked' : '' }}><span class="checkmark"></span> <span>Set as primary</span>
                                                                <input id="set-primary-phone-hide-{{ $phone['id'] }}" value="{{ $phone['is_primary'] }}" hidden {{ ($phone['is_primary'] == 1) ? 'checked' : '' }}>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-right">
                                                            <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" onclick="cancelEditPhone('{{ $phone["id"] }}');">
                                                                {{ __('Cancel') }}
                                                            </button>
                                                            <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editPhone('{{ $phone["id"] }}')">
                                                                {{ __('Save') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                @can('edit users')
                                    <div class="row col-md-12 col-sm-12 text-md-left">
                                        <a href="" data-toggle="collapse" data-target="#add-phone">
                                            <img src="/images/btn_plus.png">
                                            <i class="custom-font">Add phone</i>
                                        </a>
                                    </div>
                                @endcan

                                <div class="row collapse bg-collapse mt-2" id="add-phone">
                                    <div class="col-md-12 col-sm-12 col-form-label text-md-left">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12 col-sm-12 col-form-label text-md-left">
                                                <select class="form-control" name="type-phone" id="type-phone">
                                                    @foreach($typePhone as $type)
                                                        <option>{{ ucwords($type) }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" id="input-type-phone" class="form-control mt-3 d-none" required value="" autocomplete="off" placeholder="Input phone type">
                                            </div>

                                            <div class="col-lg-8 col-md-12 col-sm-12 col-form-label text-md-left">
                                                <input id="phone" type="text" class="form-control" name="phone_number" required autocomplete="phone" autofocus placeholder="Input number">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-left">
                                                <label class="col-lg-12">
                                                    <input id="set-primary-phone" class="role-checkbox" type="checkbox" name="set-primary-phone" value="0" onclick="@if($primaryPhone['phone_number'] != '') changeValuePhone(); @else $(this).attr('value', this.checked ? 1 : 0) @endif"><span class="checkmark"></span> <span>Set as primary</span>
                                                </label>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12 col-form-label text-md-right">
                                                <button type="button" class="btn btn-primary m-auto collapse-btn-cancel pt-0" id="btn-cancel-save-phone">
                                                    {{ __('Cancel') }}
                                                </button>
                                                <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" id="btn-save-phone">
                                                    {{ __('Save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-12 col-sm-12 col-form-label text-md-left mg-top-30 custum-opacity">{{ __('Home/ Office Address') }}</label>
                        </div>

                        <div id="show-address">
                            @include('users.show-address-user')
                        </div>

                        <div class="row">
                            <label for="name" class="col-md-12 col-sm-12 col-form-label text-md-left mg-top-30 custum-opacity">{{ __('Licences') }}</label>
                        </div>

                        <div id="show-license">
                            @include('users.show-license-user')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-sm-12 col-md-12 pl-lg-0">
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="title-detail-user mb-0 edit-matter">Matter Information</h3>
                <span class="cursor-pointer position-pen-matter">
                    <img src="/images/btn_pen.png">
                </span>
                <hr>
                <div id="show-matters">
                    @include('users.show-matter-user')
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h3 class="title-detail-user mb-0">Accreditations</h3>
                <hr>
                <div id="show-accreditations">
                    @include('users.show-accreditation-user')
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="title-detail-user mb-0">Equipment</h3>
                <hr>

                <div id="show-equipments">
                    @include('users.show-equipments-user')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- custom modal -->
<div class="modal fade" id="modalDeleteUser" tabindex="-1" role="dialog" aria-labelledby="deleteTitleUser" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTitleUser">{{ __('Delete User') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete this user?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <form action="{{ route('deleteUser', $detailUser['id']) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-delete-modal">{{ __('DELETE') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title delete-info" id="deleteTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body content-delete"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <button type="button" class="btn btn-delete-modal" onclick="sendDelete();">{{ __('DELETE') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalShow" tabindex="-1" role="dialog" aria-labelledby="showTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content edit-add-info">
            <div class="modal-header edit-add-info-header">
                <h5 class="modal-title set-primary-modal" id="deleteTitle"></h5>
                <button type="button" class="close btn-x-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body content-set-primary"></div>
            <div class="modal-footer edit-add-info-footer">
                <button type="button" class="btn btn-cancel-add-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <button type="button" id="btn-ok-modal" class="btn btn-add-modal btn-ok-activate">{{ __('OK') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeactivate" tabindex="-1" role="dialog" aria-labelledby="deactivateTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deactivateTitle">Deactivate User</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Are you sure you want to deactivate this user?</div>
            <div class="modal-footer">
                <button id="btn-cancel-deact" type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <button id="btn-ok-deact" type="button" class="btn btn-delete-modal">{{ __('OK') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete-license" tabindex="-1" role="dialog" aria-labelledby="deleteLicense" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLicense">Delete license</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete the license?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal" id="btn-cancel-license">{{ __('CANCEL') }}</button>
                <button type="button" class="btn btn-delete-modal" id="btn-delete-license">{{ __('DELETE') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete-equipment" tabindex="-1" role="dialog" aria-labelledby="deleteEquipment" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEquipment">{{ __('Delete equipment') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete this equipment?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal" id="btn-cancel-equipment">{{ __('CANCEL') }}</button>
                <button type="button" class="btn btn-delete-modal" id="btn-delete-equipment">{{ __('DELETE') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
    <script type="text/javascript">
        var urlDel = "";
        var checkDel = "";
        var givenName, middleName, familyName;
        var oldPhone, oldPrimaryPhone;

        @if (!empty($detailUser['image']))
            $(".imgUp").find('.member-client-detail').css("background-image", "url({{ $detailUser['image'] }})");
            $('label.btn-upload-avata-client').css({'margin-top' : '92px'});
            $('span.member-initials-client-detail').hide();
        @endif

        $(function() {
            $(document).on("change", ".uploadFile", function() {
                var uploadFile = $(this);
                var files = !!this.files ? this.files : [];
                if (!files.length || !window.FileReader) return;

                if (/^image/.test( files[0].type)) {
                    var reader = new FileReader();
                    reader.readAsDataURL(files[0]);

                    reader.onloadend = function(){
                        uploadFile.closest(".imgUp").find('.member-client-detail').css("background-image", "url(" + this.result + ")");
                    }

                    $('label.btn-upload-avata-client').css({'margin-top' : '92px'});
                    $('span.member-initials-client-detail').hide();

                    $('form#upload-image-form').trigger('submit');
                }
            });

            $('form#upload-image-form').submit(function(e) {
                e.preventDefault();
                var url = "{{ route('updateImageUser', $detailUser['id']) }}";
                var formData = new FormData(this);
                formData.append("column", "image");

                sendAjaxEditImage(url, formData);
            });
        });

        function sendAjaxEditImage(url, data) {
            var result;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: url,
                data: data,
                method: 'post',
                async: false,
                cache: false,
                contentType: false,
                processData: false,
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

        $(document).on("click", "button.custom-close" , function() {
            $(this).parent().remove();
        });

        function deletePhone(urlPhone)
        {
            $('.delete-info').text('{{ __('Delete phone number') }}');
            $('.content-delete').text('{{ __('Are you sure you want to delete this phone number?') }}');
            $('#modalDelete').modal('show');
            urlDel = urlPhone;
            checkDel = "phone";
        }

        function deleteEmail(urlEmail)
        {
            $('.delete-info').text('{{ __('Delete email') }}');
            $('.content-delete').text('{{ __('Are you sure you want to delete this email?') }}');
            $('#modalDelete').modal('show');
            urlDel = urlEmail;
            checkDel = "email";
        }

        function deleteAddress(urlAddress)
        {
            $('.delete-info').text('{{ __('Delete address') }}');
            $('.content-delete').text('{{ __('Are you sure you want to delete this address?') }}');
            $('#modalDelete').modal('show');
            urlDel = urlAddress;
            checkDel = "address";
        }

        function deleteAccreditation(urlAccred)
        {
            $('.delete-info').text('{{ __('Delete accreditation') }}');
            $('.content-delete').text('{{ __('Are you sure you want to delete this accreditation?') }}');
            $('#modalDelete').modal('show');
            urlDel = urlAccred;
            checkDel = "accreditation";
        }

        function sendDelete()
        {
            var data = {
                'user_id' : "{{ $detailUser['id'] }}"
            };
            switch(checkDel) {
                case 'email': sendAjaxEmail(urlDel, data); break;
                case 'phone': sendAjaxPhone(urlDel, data); break;
                case 'address': sendAjaxAddress(urlDel, data); break;
                case 'accreditation': sendAjaxAccreditation(urlDel, data); break;
            }
        }

        $('#btn-save-name').click(function()
        {
            var url = "{{ route('updateUser', $detailUser['id']) }}";
            var data = {
                'name': $('input#given-name').val(),
                'middle_name': $('input#middle-name').val(),
                'family_name': $('input#family-name').val(),
            };
            sendAjaxName(url, data);
        });

        $("#btn-cancel-save-name").click(function(){
            $("input#given-name").val(givenName);
            $("input#middle-name").val(middleName);
            $("input#family-name").val(familyName);

            $("div#add-name").removeClass('show');
        });

        $("#btn-cancel-save-birthdate").click(function(){
            $("div#add-birthdate").removeClass('show');
            $("input#date-of-birth").val($("span#show-birthdate").text());
        });

        $("#btn-cancel-save-email").click(function(){
            $("input#email").val('');
            $("div#add-email").removeClass('show');
        });

        function cancelEditEmail(id)
        {
            var oldEmail = $("span#email-" + id).text();
            $("input#email-update-" + id).val(oldEmail);
            $("div#update-email-" + id).removeClass('show');
        }

        $("#btn-cancel-save-phone").click(function(){
            $("input#phone").val('');
            $("#set-primary-phone").prop("checked", false);
            $("#set-primary-phone").attr('value', 0);
            $("div#add-phone").removeClass('show');
        });

        function cancelEditPhone(id)
        {
            oldTypePhone = $("#type-phone-hide-" + id).val();
            $("select#type-phone-update-" + id).val(oldTypePhone);

            oldPhone = $("span#phone-number-" + id).text();
            $("input#phone-update-" + id).val(oldPhone);

            oldPrimaryPhone = $("input#set-primary-phone-hide-" + id).attr('value');

            if(oldPrimaryPhone == 1) {
                $("input#set-primary-phone-" + id).prop("checked", true);
                $("input#set-primary-phone-" + id).attr('value', 1);
            } else {
                $("input#set-primary-phone-" + id).prop("checked", false);
                $("input#set-primary-phone-" + id).attr('value', 0);
            }

            $("span.all-primary").text("");
            $("span#primary-{{ $primaryPhone['id'] }}").text("(Primary)");
            $("div#update-phone-" + id).removeClass('show');
        }

        function saveOldName()
        {
            givenName = $("input#given-name").val();
            middleName = $("input#middle-name").val();
            familyName = $("input#family-name").val();
        }

        $('#btn-save-birthdate').click(function()
        {
            var url = "{{ route('updateUser', $detailUser['id']) }}";
            var data = {
                'date_of_birth': $('input#date-of-birth').val(),
            };
            sendAjaxBirthdate(url, data);
        });

        function changeTypeEmail(emailId) {
            if ($("select#type-email-update-" + emailId).val().toLowerCase() == 'others') {
                $('input#input-type-email-update-' + emailId).removeClass('d-none');
            } else {
                $('input#input-type-email-update-' + emailId).addClass('d-none');
            }
        }

        $('select#type-email').change(function(){
            if ($(this).val().toLowerCase() == 'others') {
                $('input#input-type-email').removeClass('d-none');
            } else {
                $('input#input-type-email').addClass('d-none');
            }
        });

        function changeTypePhone(phoneId) {
            if ($("select#type-phone-update-" + phoneId).val().toLowerCase() == 'others') {
                $('input#input-type-phone-update-' + phoneId).removeClass('d-none');
            } else {
                $('input#input-type-phone-update-' + phoneId).addClass('d-none');
            }
        }

        $('select#type-phone').change(function(){
            if ($(this).val().toLowerCase() == 'others') {
                $('input#input-type-phone').removeClass('d-none');
            } else {
                $('input#input-type-phone').addClass('d-none');
            }
        });

        $('#btn-save-email').click(function()
        {
            var url = "{{ route('storeEmail', $detailUser['id']) }}";
            var data = {
                'email': $('input#email').val(),
                'type-email': ($('select#type-email').val().toLowerCase() == 'others') ? $('input#input-type-email').val() : $('select#type-email').val(),
            };

            if (!data['type-email'].trim()) {
                toastr.error('Input type email.');
            } else {
                data['type-email'] = data['type-email'].toLowerCase();
                sendAjaxEmail(url, data);
            }
        });

        function editMail(userId, emailId)
        {
            var url = "{{ route('updateUserEmail') }}";
            var data = {
                'id': emailId,
                'user_id': userId,
                'email': $('input#email-update-' + emailId).val(),
                'type-name': ($('select#type-email-update-' + emailId).val().toLowerCase() == 'others') ? $('input#input-type-email-update-' + emailId).val() : $('select#type-email-update-' + emailId).val(),
            };

            if (!data['type-name'].trim()) {
                toastr.error('Input type email.');
            } else {
                data['type-name'] = data['type-name'].toLowerCase();
                sendAjaxEmail(url, data);
            }
        }

        $('#btn-save-phone').click(function()
        {
            var url = "{{ route('storePhone', $detailUser['id']) }}";
            var data = {
                'phone_number': $('input#phone').val(),
                'type-phone': ($('select#type-phone').val().toLowerCase() == 'others') ? $('input#input-type-phone').val() : $('select#type-phone').val(),
                'is_primary': $('input#set-primary-phone').val(),
            };

            if (!data['type-phone'].trim()) {
                toastr.error('Input type phone.');
            } else {
                data['type-phone'] = data['type-phone'].toLowerCase();
                sendAjaxPhone(url, data);
            }
        });

        function changeValuePhone()
        {
            if($("#set-primary-phone").attr('value') == 0)
            {
                $('.set-primary-modal').text('{{ __('Set phone number as primary') }}');
                $('.modal-body.content-set-primary').text('{{ __('Do you want to set this phone number as primary?') }}');
                showModalPhone();
            } else {
                $("#set-primary-phone").attr('value', 0);
            }
        }

        function showModalPhone()
        {
            $('#modalShow').modal('show');
            $('.btn-cancel-add-modal').click(function() {
                $("#set-primary-phone").prop("checked", false);
            });
            $('.btn-x-modal').click(function() {
                $("#set-primary-phone").prop("checked", false);
            });
            $('#btn-ok-modal').click(function() {
                $('#modalShow').modal('hide');
                $("#set-primary-phone").prop("checked", true);
                $("#set-primary-phone").attr('value', 1);
            });
        }

        function changeValueUpdatePhone(phoneId)
        {
            if($("#set-primary-phone-" + phoneId).attr('value') == 0)
            {
                $('.set-primary-modal').text('{{ __('Set phone number as primary') }}');
                $('.modal-body.content-set-primary').text('{{ __('Do you want to set this phone number as primary?') }}');
                showModalPhoneUpdate(phoneId);
            } else {
                $("#set-primary-phone-" + phoneId).attr('value', 0);
            }
        }

        function showModalPhoneUpdate(phoneId)
        {
            $('#modalShow').modal('show');
            $('.btn-cancel-add-modal').click(function() {
                $("#set-primary-phone-" + phoneId).prop("checked", false);
            });
            $('.btn-x-modal').click(function() {
                $("#set-primary-phone-" + phoneId).prop("checked", false);
            });
            $('#btn-ok-modal').click(function() {
                $('#modalShow').modal('hide');
                $("#set-primary-phone-" + phoneId).prop("checked", true);
                $("#set-primary-phone-" + phoneId).attr('value', 1);
                $("span.all-primary").text("");
                $("span#primary-" + phoneId).text("(Primary)");
            });
        }

        function editPhone(phoneId)
        {
            var url = "{{ route('updateUserPhone') }}";
            var data = {
                'id': phoneId,
                'user_id': "{{ $detailUser['id'] }}",
                'phone-number': $('input#phone-update-' + phoneId).val(),
                'type-name': ($('select#type-phone-update-' + phoneId).val().toLowerCase() == 'others') ? $('input#input-type-phone-update-' + phoneId).val() : $('select#type-phone-update-' + phoneId).val(),
                'is_primary': $('input#set-primary-phone-' + phoneId).val(),
            };
            $("input.number-phone").prop("checked", false);
            if ($("#set-primary-phone-" + phoneId).attr('value') == 1) {
                $("#set-primary-phone-" + phoneId).prop("checked", true);
                $("#set-primary-phone-" + phoneId).attr('value', 1);
            }

            if (!data['type-name'].trim()) {
                toastr.error('Input type phone.');
            } else {
                data['type-name'] = data['type-name'].toLowerCase();
                sendAjaxPhone(url, data);
            }
        }

        function sendAjaxName(url, data) {
            var oldName = $("span#show-name").text();

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
                    if (response['alert-type'] == 'errors') {
                        toastr.error(response['message']);
                        $("span#show-name").text(oldName);
                    } else {
                        toastr.success(response['message']);
                        $("span#show-name").text($("input#given-name").val());
                        $("div#add-name").removeClass('show');
                    }
            }});
        }

        function sendAjaxBirthdate(url, data) {
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
                    if (response['alert-type'] == 'errors') {
                        toastr.error(response['message']);
                        $("span#show-birthdate").text($("span#show-birthdate").text());
                    } else {
                        toastr.success(response['message']);
                        $("span#show-birthdate").text($("input#date-of-birth").val());
                        $("div#add-birthdate").removeClass('show');
                    }
            }});
        }

        function sendAjaxEmail(url, data) {
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
                    $("div#show-mails").html(response);
                    $('#modalDelete').modal('hide');
            }});
        }

        function sendAjaxPhone(url, data) {
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
                    $("div#show-phones").html(response);
                    $('#modalDelete').modal('hide');
            }});
        }

        $(function () {
            $("input.date-picker").datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom"
            });

            $("input#date-of-birth").datepicker({
                startDate: $("input#date-of-birth").val(),
            });

            $('#btn-action-user').on('click', function(event) {
                $('#btn-action-user').toggleClass('show-column-clicked');
            });

            $(document).click(function(){
                $('#btn-action-user').removeClass('show-column-clicked');
            });
        });

        // Change status user
        var urlStatus;
        var dataStatus;
        $('.change-status').click(function() {
            var status = $('input#status').val();
            urlStatus = "{{ route('changeStatusUser', $detailUser['id']) }}";
            dataStatus = {
                'status': $('input#status').val(),
            };

            if(status == 'active') {
                $('#modalDeactivate').modal('show');
            } else {
                $('.set-primary-modal').text('{{ __('Activate user') }}');
                $('.modal-body.content-set-primary').text('{{ __('Are you sure you want to activate this user?') }}');
                $('#modalShow').modal('show');
            }
        });

        $('#btn-ok-deact').click(function(event) {
            sendAjaxStatus(urlStatus, dataStatus);
            $('#modalDeactivate').modal('hide');
        });

        $('.btn-ok-activate').click(function(event) {
            sendAjaxStatus(urlStatus, dataStatus);
            $('#modalShow').modal('hide');
        });

        function sendAjaxStatus(url, data) {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
            });
            jQuery.ajax({
                url: url,
                data: data,
                method: 'get',
                async: false,
                cache: true,
                success: function(response){
                    if (response['alert-type'] == 'errors') {
                        toastr.error(response['message']);
                    } else {
                        var status = response['status'];
                        toastr.success(response['message']);
                        if (status == 'active') {
                            $("a.change-status").text("Activate User");
                            $("div.status-user").text("Inactive");
                            $("div.status-user").addClass('inactive-user');
                            $("div.status-user").removeClass('active-user');
                            $("a.change-status").removeClass("text-danger font-weight-bold");
                            $('input#status').val('inactive');
                        } else {
                            $("a.change-status").text("Deactivate User");
                            $("div.status-user").text("Active");
                            $("div.status-user").addClass('active-user');
                            $("div.status-user").removeClass('inactive-user');
                            $("div.status-user").removeClass('pending-user');
                            $("a.change-status").addClass("text-danger font-weight-bold");
                            $('input#status').val('active');
                        }
                    }
                }
            });
        }

        // for license
        // delete license
        $(document).on('click', "span#span-delete-license img", function() {
            var options = {
              'backdrop': 'static'
            };
            $('#modal-delete-license').modal(options);

            licenseId = $(this).attr('id');
        });

        $('#btn-delete-license').click(function () {
            url = "{{ route('deleteLicense', $detailUser['id']) }}";
            let data = {
                'license_id': licenseId
            }

            sendAjaxDeleteLicense(url, data);
        });

        function sendAjaxDeleteLicense(url, data) {
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
                    $("div#show-license").html(response);
                    $('#modal-delete-license').modal('hide');
            }});
        }
        // end for license

        var equipmentIdForDelete = '';
        function deleteEquipment(equipmentId) {
            $("#modal-delete-equipment").modal("show");
            equipmentIdForDelete = equipmentId;
        }

        $("#btn-delete-equipment").click(function () {
            let url = "{{ route('deleteUserEquipment', $detailUser['id']) }}";
            let data = {
                "id": equipmentIdForDelete,
            }

            sendAjaxToDeleteEditEquipment(url, data);
        });

        function sendAjaxToDeleteEditEquipment(url, data) {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            jQuery.ajax({
                url: url,
                data: data,
                method: 'post',
                async: false,
                success: function(response){
                    $("div#show-equipments").html(response.html);
                    $('#modal-delete-equipment').modal('hide');
                    $('.modal-backdrop.fade.show').hide();
                }
            });
        }

        $('.show-matter-list').click(function () {
            window.location.replace("{{ route('getListMatter') }}");
        });
    </script>
@stop