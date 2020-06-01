<!-- modal add folder -->
<div class="modal fade" id="modal-add-folder" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <form method="get" id="add-folder-form" action="">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="addTitle">{{ __('Create Folder') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Name *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Folder Name" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Description') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <textarea id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" autocomplete="description" placeholder="Input Description" pattern=".*\S+.*"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-bg-white" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="button" class="btn btn-add-modal" id="btn-add-folder-modal" onclick="createFolder('{{ $detailMatter['id'] }}')">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- modal edit folder -->
<div class="modal fade" id="modal-edit-folder" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <form method="get" id="edit-folder-form" action="">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="addTitle">{{ __('Edit Folder') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id-edit">
                    <input type="hidden" id="path-edit">
                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Name *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Folder Name" pattern=".*\S+.*">
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Description') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <textarea id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" autocomplete="description" placeholder="Input Description" pattern=".*\S+.*"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-bg-white" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="button" class="btn btn-add-modal" id="btn-edit-folder-modal" onclick="editFolder('{{ $detailMatter['id'] }}')">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- modal edit file -->
<div class="modal fade" id="modal-edit-file" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <form method="get" id="edit-file-form" action="">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="addTitle">{{ __('Edit File') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id-edit">
                    <input type="hidden" id="path-edit">
                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Name *') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="File Name" pattern=".*\S+.*" readonly>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-12 col-lg-3 col-form-label text-md-left">{{ __('Description') }}</label>

                        <div class="col-md-12 col-lg-9">
                            <textarea id="description" type="text" class="form-control" name="description" value="{{ old('description') }}" autocomplete="description" placeholder="Input Description" pattern=".*\S+.*"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-bg-white" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="button" class="btn btn-add-modal" id="btn-edit-file-modal" onclick="editFile('{{ $detailMatter['id'] }}')">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- modal upload files -->
<div class="modal fade" id="modal-upload-files" tabindex="-1" role="dialog" aria-labelledby="addTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <form method="get" id="upload-files-form" action="" enctype="multipart/form-data">
            @csrf
            <div class="modal-content edit-add-info">
                <div class="modal-header edit-add-info-header">
                    <h5 class="modal-title" id="addTitle">{{ __('Upload Files') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-12 col-lg-12">
                            <div class="container">
                                <div class="row">
                                    <div id="dropzone" class="col-xl-12 p-0">
                                        <div class="needsclick p-0" id="upload-files-for-matter" enctype="multipart/form-data">
                                            <div class="dz-message needsclick pt-4"><h3>Drop files here or click to upload.<h3><br><span class="note needsclick"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="preview-template" style="display: none;">
                                        <div class="dz-preview dz-file-preview">
                                            <div class="dz-image"><img data-dz-thumbnail=""></div>
                                            <div class="dz-details">
                                                <div class="dz-size"><span data-dz-size=""></span></div>
                                                <div class="dz-filename"><span data-dz-name=""></span></div>
                                            </div>
                                            <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                                            <div><img data-dz-remove /></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer edit-add-info-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-bg-white" data-dismiss="modal">{{ __('CANCEL') }}</button>
                        <button type="button" class="btn btn-add-modal" id="btn-upload-files-modal" onclick="uploadFiles()">{{ __('SAVE') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- modal delete folder -->
<div class="modal fade" id="modal-delete-folder" tabindex="-1" role="dialog" aria-labelledby="deleteFolderTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFolderTitle">{{ __('Delete Folder') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('All folder or files in this folder also will be deleted and this action cannot be undone. Are you sure you want to delete this folder ?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <button type="button" class="btn btn-delete-modal" onclick="deleteFolder('{{ $detailMatter['id'] }}')">{{ __('DELETE') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- modal delete file -->
<div class="modal fade" id="modal-delete-file" tabindex="-1" role="dialog" aria-labelledby="deleteFileTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFileTitle">{{ __('Delete File') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete this file ?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                <button type="button" class="btn btn-delete-modal" onclick="deleteFile('{{ $detailMatter['id'] }}')">{{ __('DELETE') }}</button>
            </div>
        </div>
    </div>
</div>