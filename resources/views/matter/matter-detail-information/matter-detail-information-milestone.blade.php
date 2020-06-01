<?php
    $arrDate = array ('Due Date', 'Internal Due Date', 'Date Received', 'Date Of Referral', 'Date Invoiced', 'Date Report Sent', 'Date File Returned', 'Date Interim Report Sent');
?>

    <div class="card mb-3">
        <div class="card-body tab-content recent-cases">
            <div class="row mb-2">
                <div class="col-lg-12 col-sm-12 col-md-12 mb-0">
                    <h3 class="title mb-0">Milestone</h3>
                </div>
            </div> <hr>
            @php
                $oldArrDate = $arrDate;
                foreach ($milestones as $milestone){
                    foreach ($arrDate as $key => $date){
                        if ($milestone['date_type'] == $key){
                            unset($arrDate[$key]);
                        }
                    }
                }
            @endphp

            @foreach ($oldArrDate as $key => $date)
                @if (isset($milestones) && !empty($milestones))
                    @foreach ($milestones as $milestone)
                        @if ($milestone["date_type"] == $key)
                        <div class="row mb-2">
                            <div class="col-lg-12 col-sm-12 col-md-12 mb-0">
                                <p class="mb-0 custom-text-matter">{{ucfirst($date)}}</p>
                            </div>
                            <div class="col-xl-12 mb-0">
                                <p class="mb-0"><b>Date: </b>{{ucfirst($milestone["date"])}}</p>
                            </div>
                        </div> <hr>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @foreach ($arrDate as $key => $date)
                <div class="row mb-2">
                    <div class="col-lg-12 col-sm-12 col-md-12 mb-0">
                        <p class="mb-0 custom-text-matter">{{ucfirst($date)}}</p>
                    </div>

                    @can(strtolower($date))
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <a href="javascript:void(0);" onclick="showModalAddDate(['{{ $key }}', '{{ $date }}'])">
                                <img src="/images/btn_plus.png">
                                <i class="custom-font">Add date</i>
                            </a>
                        </div>
                    @endcan
                </div> <hr>
            @endforeach
        </div>
    </div>

<div class="modal fade" id="add-date" tabindex="-1" role="dialog" aria-labelledby="addDateTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content edit-add-date">
            <div class="modal-header edit-add-info-header">
                <h5 class="modal-title" id="addDateTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('addDate', $detailMatter['id']) }}" method="POST" id="add-date-form">
                    @csrf
                    <div class="form-group row">
                        <div class="col-xl-12">
                            <div id="div-date"><input hidden type="text" class="date-picker" name="date"></div>
                            <input hidden type="text" id="date-type" name="date-type">
                            <input hidden name="acctive-tab" value="{{ $activeTab }}">
                        </div>
                    </div>

                    <div class="modal-footer edit-add-info-footer pb-0">
                        <div class="form-group mb-0">
                            <button type="button" class="btn btn-cancel-add-modal mb-1" id="btn-cancel-add-investigator-modal" data-dismiss="modal">{{ __('CANCEL') }}</button>
                            <button type="submit" class="btn btn-add-modal" id="btn-add-date">{{ __('SAVE') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>