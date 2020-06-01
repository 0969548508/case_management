@can('view matter assignments')
    <div class="card mb-3">
        <div class="card-body tab-content recent-cases">
            <div class="row mb-2">
                <div class="col-lg-12 col-sm-12 col-md-12 mb-0">
                    <h3 class="title mb-4">Assignees</h3>
                    @can('assign users to matters')
                        @if ($assignToMe)
                            <label class="col-lg-12 mb-0 pb-0">
                                <input class="role-checkbox" type="checkbox" checked id="edit-input-assign-to-me">
                                <span class="checkmark" id="edit-checkmark-assign-to-me"></span>Assign to me
                            </label>
                        @else
                            <label class="col-lg-12 mb-0 pb-0">
                                <input id="input-assign-to-me" class="role-checkbox" type="checkbox" name="assign-to-me">
                                <span class="checkmark" id="checkmark-assign-to-me"></span>Assign to me
                            </label>
                        @endif
                    @endcan
                </div>
            </div>
            @can('assign users to matters')<hr>@endcan
            @cannot('assign users to matters')<hr style="margin-top: -16px;">@endcan

            @foreach ($listRole as $role)
                <div class="row mb-2">
                    <div class="col-xl-12 mb-0">
                        <div class="row m-0">
                            <p class="mb-1 custom-text-matter">{{ ucfirst($role->name) }}</p>
                        </div>
                    </div>

                    @foreach ($listMatterUser as $matterUser)
                        @if (!empty($matterUser['rolesuser']) && $matterUser['role_id'] == $role->id)
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row ml-0">
                                    @if (!empty($matterUser['userofmatter'][0]['image']))
                                        <img src="{{ $matterUser['userofmatter'][0]['image'] }}" width="32.53" height="32.53" style="border-radius: 50%;" class="mr-3" alt="AVT">
                                    @else
                                        <button class="mr-3 custom-img-assignees">{{ strtoupper(substr($matterUser['userofmatter'][0]['name'], 0, 1)) }}</button>
                                    @endif
                                    <div class="col-lg-9 col-md-9 pt-0 pl-0">
                                        <span style="color: #333333; font-weight: bold;">{{ ucwords($matterUser['userofmatter'][0]['name']) }}</span>
                                        <div class="position-user created-at-matter"><b>Matter created date: </b>{{ date('d/m/Y', strtotime($matterUser['matter']['created_at'])) }}</div>&nbsp;&nbsp;
                                    </div>
                                    @can('assign users to matters')
                                        <span class="cursor-pointer position-absolute-pen" onclick="showModalDeleteAssign(['{{ $role->id }}', '{{ $matterUser['userofmatter'][0]['id'] }}']);">
                                            <img src="/images/img-delete-black.png" width="15" height="16">
                                        </span>
                                    @endcan
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @can('assign users to matters')
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <a href="javascript:void(0);" onclick="showModal(['{{ $role->id }}', '{{ $role->name }}'])">
                                <img src="/images/btn_plus.png">
                                <i class="custom-font">Add assignee</i>
                            </a>
                        </div>
                    @endcan
                </div> @can('assign users to matters')<hr>@endcan
            @endforeach
        </div>
    </div>
@endcan

<div class="modal fade" id="add-investigator" tabindex="-1" role="dialog" aria-labelledby="addInvestigatorTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content edit-add-info">
            <div class="modal-header edit-add-info-header">
                <h5 class="modal-title" id="addInvestigatorTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-close-add-investigator">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('assignInvestigator', $detailMatter['id']) }}" method="POST" id="assign-investigator-form">
                    @csrf
                    <input hidden name="assign-to-me" value="0" id="assign-to-me">
                    <input hidden name="role-id" value="" id="role-id">
                    <input hidden name="acctive-tab" value="{{ $activeTab }}">
                    <div class="form-group row">
                        <div class="col-md-12 col-lg-12">
                            <div class="row ml-0">
                                <p class="pr-3">
                                    <input type="radio" name="radio-group" id="select-specific-user" checked="checked">
                                    <label for="select-specific-user" id="slt-specific-user">{{ __('Select specific user') }}</label>
                                </p>

                                <p>
                                    <input type="radio" name="radio-group" id="assign-to-all-available-user">
                                    <label for="assign-to-all-available-user" id="slt-assign-to-all-available-user">{{ __('Assign to all available user') }}</label>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="div-dropdown-user">
                        <label for="slt-investigator" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('User Name') }}</label>

                        <div class="col-md-12 col-lg-9" id="load-user-by-role">
                            <!-- code -->
                        </div>
                    </div>

                    <div class="form-group row" id="div-selected-user">
                        <div class="col-md-12 col-lg-3 col-form-label text-md-left"></div>

                        <div class="col-md-12 col-lg-9">
                            <div class="row">
                                <div class="col-xl-4">
                                    <i id="selected-user">Selected user:</i>
                                </div>

                                <div class="col-xl-8 ml-0 pl-0" id="show-sub-text-selected-user">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer edit-add-info-footer pb-0">
                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-cancel-add-modal" id="btn-cancel-add-investigator-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                            <button type="submit" class="btn btn-add-modal" id="btn-add-investigator-modal">{{ __('SAVE') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-assign-to-me-investigator" tabindex="-1" role="dialog" aria-labelledby="addAssignToMeInvestigatorTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content edit-add-info">
            <div class="modal-header edit-add-info-header mb-4">
                <h5 class="modal-title" id="addAssignToMeInvestigatorTitle">{{ __('Assign To Me') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-close-add-assign-to-me">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="mb-5">
                    {{ __('Are you sure you want to assign this matter for you?') }}
                </div>
                <form action="{{ route('assignInvestigator', $detailMatter['id']) }}" method="POST" id="assign-to-me-form">
                    @csrf
                    <input hidden name="assign-to-me" value="1" id="assign-to-me">
                    <input hidden name="role-id" value="" id="role-id">
                    <input hidden name="acctive-tab" value="{{ $activeTab }}">

                    <div class="modal-footer edit-add-info-footer pb-0">
                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-cancel-add-modal" id="btn-cancel-add-assign-to-me-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                            <button type="submit" class="btn btn-add-modal" id="btn-add-assign-to-me-modal">{{ __('SAVE') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-investigator" tabindex="-1" role="dialog" aria-labelledby="editInvestigatorTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content edit-add-info">
            <div class="modal-header edit-add-info-header">
                <h5 class="modal-title" id="editInvestigatorTitle">{{ __('Edit Investigator') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="edit-btn-close-add-investigator">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="#" method="POST" id="edit-assign-investigator-form">
                    @csrf
                    <input hidden name="edit-role-id" value="" id="edit-role-id">
                    <div class="form-group row">
                        <div class="col-md-12 col-lg-12">
                            <div class="row ml-0">
                                <p class="pr-3">
                                    <input type="radio" name="edit-radio-group" id="edit-select-specific-user" checked="checked">
                                    <label for="edit-select-specific-user" id="edit-slt-specific-user">{{ __('Select specific user') }}</label>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="edit-div-dropdown-user">
                        <label for="slt-investigator" class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('User Name') }}</label>

                        <div class="col-md-12 col-lg-9" id="edit-load-user-by-role">
                            <!-- code -->
                        </div>
                    </div>

                    <div class="modal-footer edit-add-info-footer pb-0">
                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-cancel-add-modal" id="edit-btn-cancel-add-investigator-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                            <button type="submit" class="btn btn-add-modal" id="edit-btn-add-investigator-modal">{{ __('SAVE') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeleteUserMatter" tabindex="-1" role="dialog" aria-labelledby="deleteTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title delete-info" id="deleteTitle">Delete user matter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body content-delete">
                <p>Are you sure you want to delete this user matter?</p>
                <b>Note: Do not allow to remove all.</b>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <button type="button" class="btn btn-delete-modal" onclick="sendDelete();">{{ __('DELETE') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-assign-to-me-investigator" tabindex="-1" role="dialog" aria-labelledby="editAssignToMeInvestigatorTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAssignToMeInvestigatorTitle">{{ __('Unassign') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-close-add-assign-to-me">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body content-delete">
                <div class="mb-5">
                    {{ __('Are you sure you want to unassign this matter for you?') }}
                </div>
                <form action="{{ route('assignInvestigator', $detailMatter['id']) }}" method="POST" id="assign-to-me-form">
                    @csrf
                    <input hidden name="assign-to-me" value="2" id="assign-to-me">
                    <input hidden name="role-id" value="" id="role-id">
                    <input hidden name="acctive-tab" value="{{ $activeTab }}">

                    <div class="modal-footer edit-add-info-footer pb-0">
                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-cancel-modal" id="edit-btn-cancel-add-assign-to-me-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                            <button type="submit" class="btn btn-delete-modal" id="edit-btn-add-assign-to-me-modal">{{ __('SAVE') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>