@php
    $typeEmail = array('work', 'personal', 'others');
@endphp
@foreach($emailInfo as $email)
    <div class="row col-md-12 col-sm-12 col-form-label text-md-left">
        <b>{{ ucwords($email['type_name']) }}:</b> &nbsp; <span id="email-{{ $email['id'] }}">{{ $email['email'] }}</span> &nbsp;
        <span class="cursor-pointer" data-toggle="collapse" data-target="#update-email-{{ $email['id'] }}">
            <img src="/images/btn_pen.png">
        </span>
        <span class="cursor-pointer" onclick="deleteEmail('{{ route('deleteUserEmail', $email['id']) }}')">
            @if(isset($email['email']))
                &nbsp;&nbsp;
                <img src="/images/img-delete.png" id="delete-email-{{ $email['id'] }}">
            @endif
        </span>
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
                    <button type="submit" class="btn btn-primary m-auto collapse-btn-save pt-0" onclick="editMail('{{ $detailUser["id"] }}', '{{ $email["id"] }}')">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    @if (isset($response['alert-type']) && $response['alert-type'] == 'errors')
        toastr.error("{{ $response['message'] }}");
    @else
        $("#btn-cancel-save-email").trigger('click');
        toastr.success("{{ $response['message'] }}");
    @endif
</script>