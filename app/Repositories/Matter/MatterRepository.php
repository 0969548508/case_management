<?php

namespace App\Repositories\Matter;

use App\Repositories\Repository;
use App\User;
use App\Models\Clients;
use App\Models\Matter;
use App\Models\Offices;
use App\Models\SpecificMatters;
use App\Models\MattersUsers;
use App\Models\MatterAccountInstructingClient;
use App\Models\MatterAccountInstructingClientLocation;
use App\Models\MatterAccountInstructingContact;
use App\Models\Insurers;
use App\Models\Roles;
use App\Models\Milestones;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Auth;
use DB;
use Log;

class MatterRepository extends Repository {

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \App\Models\Matter::class;
    }

    public function getListMatter()
    {
        $listMatter = Matter::where('user_id', auth()->user()->id)
                        ->orWhereHas('mattersusers', function($relation) {
                            $relation->where('cases_users.user_id', auth()->user()->id);
                        })
                        ->with('clients')
                        ->with('locations')
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return $listMatter;
    }

    public function 
    createMatter($data)
    {
        $response = [
            'alert-type'    => 'error',
            'message'       => 'Create matter error!',
        ];

        $columns = array('states.name', 'states.state_code');
        $stateDetail = Offices::select($columns)
                    ->join('states', 'offices.state', 'states.id')
                    ->where('offices.id', $data['office_id'])
                    ->first();

        // To get next id in case_increment
        $statement = DB::select("SHOW TABLE STATUS LIKE 'case_increment'");
        $caseId = $statement[0]->Auto_increment;

        $data['case_number'] = Carbon::now()->format('Ym') . $0 . (!empty($stateDetail) ? $stateDetail->state_code : '');
        $data['user_id'] = auth()->user()->id;
        $data['location_id'] = isset($data['location_id']) ? $data['location_id'] : '';

        $result = $this->store($data);
        if ($result) {
            $response = [
                'alert-type'    => 'success',
                'message'       => 'Create matter successfully!',
            ];

            // Insert to case_increment
            DB::table('case_increment')->insert(['case_id' => $result->id]);

            if (isset($data['assign_users'])) {
                $case = Matter::find($result->id);
                $case->transition('to-do');
                $case->save();

                foreach ($data['assign_users'] as $userId) {
                    $roleId = DB::table("model_has_roles")->where("model_id", $userId)->take(1)->get();
                    if (!empty($roleId)){
                        $roleId = $roleId[0]->role_id;
                    }
                    $dataMapping = array(
                        'case_id' => $result->id,
                        'user_id' => $userId,
                        'role_id' => $roleId
                    );

                    DB::table('cases_users')->insert($dataMapping);
                }
            }
        }

        return $response;
    }

    public function detailMatter($matterId)
    {
        $detailMatter = Matter::where('id', $matterId)->with(['clients', 'locations', 'office', 'type'])->get()->first();
        return $detailMatter;
    }

    public static function countMatterByStatus($status = null)
    {
        $count = 0;

        if (!empty($status)) {
            switch ($status) {
                case 'closed':
                    $status = array('cancelled', 'completed', 'on-hold', 'invalid', 'withdrawn');
                    $count = Matter::where(function($query) {
                                $query->where('user_id', auth()->user()->id)
                                ->orWhereHas('mattersusers', function($relation) {
                                    $relation->where('cases_users.user_id', auth()->user()->id);
                                });
                            })->whereIn('last_state', $status)->count();
                    break;

                case 'in-progress':
                    $status = array('in-progress', 'need-review', 'billing');
                    $count = Matter::where(function($query) {
                                $query->where('user_id', auth()->user()->id)
                                ->orWhereHas('mattersusers', function($relation) {
                                    $relation->where('cases_users.user_id', auth()->user()->id);
                                });
                            })->whereIn('last_state', $status)->count();
                    break;

                default:
                    $count = Matter::where(function($query) {
                                $query->where('user_id', auth()->user()->id)
                                ->orWhereHas('mattersusers', function($relation) {
                                    $relation->where('cases_users.user_id', auth()->user()->id);
                                });
                            })->where('last_state', $status)->count();
                    break;
            }
        } else {
            $count = Matter::where(function($query) {
                        $query->where('user_id', auth()->user()->id)
                        ->orWhereHas('mattersusers', function($relation) {
                            $relation->where('cases_users.user_id', auth()->user()->id);
                        });
                    })->count();
        }

        return $count;
    }

    public function editGeneralInfo($data, $matterId)
    {
        $response = [
            'alert-type'    => 'success',
            'message'       => 'Update matter successfully!',
        ];

        $dataToEdit = Matter::find($matterId);
        $dataToEdit['location_id'] = $data['edit-location'];
        // $dataToEdit['type_id'] = $data['edit-type'];
        // $dataToEdit['office_id'] = $data['edit-office'];

        if (!$dataToEdit->save()) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Update matter error!',
            ];
        }
        return $response;
    }

    public function assignInvestigator($data, $matterId)
    {
        $response = [
            'alert-type'    => 'error',
            'message'       => 'Assign error!',
        ];

        $dataToAdd = array();
        if ($data['assign-to-me'] == 0) {
            if ($data['radio-group'] == 1 && !empty($data['list-user'])) {
                foreach ($data['list-user'] as $value) {
                    $info['case_id'] = $matterId;
                    $info['user_id'] = $value;
                    $info['role_id'] = $data['role-id'];
                    array_push($dataToAdd, $info);
                }
            }
        } else {
            $roleId = DB::table('model_has_roles')
                ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                ->select('model_has_roles.role_id')
                ->where('users.id', auth()->user()->id)->get()->toArray();

            if (!empty($roleId)) {
                $roleId = $roleId[0]->role_id;
            }

            if ($data['assign-to-me'] == 1) {
                $dataToAdd = [
                    [
                        'case_id'  => $matterId,
                        'user_id'  => auth()->user()->id,
                        'role_id'  => $roleId
                    ],
                ];
            } else {
                $resultDelete = MattersUsers::where([
                    'case_id'  => $matterId,
                    'user_id'  => auth()->user()->id,
                    'role_id'  => $roleId
                ])->delete();

                if ($resultDelete) {
                    $response = [
                        'alert-type'    => 'success',
                        'message'       => 'Unassign successfully!',
                    ];
                } else {
                    $response = [
                        'alert-type'    => 'error',
                        'message'       => 'Unassign error!',
                    ];
                }

                return $response;
            }
        }

        $result = MattersUsers::insert($dataToAdd);

        if ($result) {
            $case = Matter::find($matterId);
            if (isset($case->last_state) && ($case->last_state == 'not-assigned')) {
                $case->transition('to-do');
                if ($case->save()) {
                    $response = [
                        'alert-type'    => 'success',
                        'message'       => 'Assign successfully!',
                    ];

                    return $response;
                }
            }

            $response = [
                'alert-type'    => 'success',
                'message'       => 'Assign successfully!',
            ];
        }

        return $response;
    }

    public function handleAccountInstructingInformation($data, $matterId)
    {
        if ($data['account-client-type'] == 0) {
            $result = $this->createAccountClientInformation($data, $matterId);
        } else {
            $result = $this->createInstructingClientInformation($data, $matterId);
        }

        return $result;
    }

    public function createAccountClientInformation($data, $matterId)
    {
        $response = [
            'alert-type'    => 'success',
            'message'       => 'Save successfully!',
        ];

        $accountClientInfo = new MatterAccountInstructingClient;
        $accountClientInfo = [
            'matter_id'         => $matterId,
            'name'              => $data['account-client-name'],
            'abn'               => $data['new-client-abn'],
            'billing_number'    => $data['billing-number']
        ];

        $resultAccountClientInfo = MatterAccountInstructingClient::create($accountClientInfo);

        if (!empty($resultAccountClientInfo)) {
            $accountClientLocationInfo = [
                'matter_id'         => $matterId,
                'client_id'         => $resultAccountClientInfo->id,
                'location_name'     => $data['location-name'],
                'abn'               => $data['new-location-abn'],
                'address_1'         => $data['address-1'],
                'address_2'         => $data['address-2'],
                'country'           => $data['select-country'],
                'state'             => $data['select-state'],
                'city'              => $data['select-city'],
                'postcode'          => $data['new-location-postcode'],
                'primary_phone'     => $data['primary-phone'],
                'secondary_phone'   => $data['secondary-phone'],
                'fax'               => $data['fax']
            ];

            $resultAccountClientLocationInfo = MatterAccountInstructingClientLocation::create($accountClientLocationInfo);

            if (!empty($resultAccountClientLocationInfo)) {
                $accountClientContactInfo = [
                    'matter_id'         => $matterId,
                    'client_id'         => $resultAccountClientInfo->id,
                    'location_id'       => $resultAccountClientLocationInfo->id,
                    'name'              => $data['account-contact-name'],
                    'job_title'         => $data['account-contact-job-title'],
                    'email'             => $data['account-contact-email'],
                    'phone'             => $data['account-contact-phone'],
                    'mobile'            => $data['account-contact-mobile'],
                    'fax'               => $data['account-contact-fax'],
                    'note'              => $data['account-contact-note']
                ];

                $resultAccountClientContactInfo = MatterAccountInstructingContact::create($accountClientContactInfo);
                if (empty($resultAccountClientContactInfo)) {
                    $deleteAccountClientInfo = MatterAccountInstructingClient::find($resultAccountClientInfo->id);
                    $deleteAccountClientInfo->delete();
                    $deleteAccountClientLocationInfo = MatterAccountInstructingClientLocation::find($resultAccountClientLocationInfo->id);
                    $deleteAccountClientLocationInfo->delete();
                    $response = [
                        'alert-type'    => 'error',
                        'message'       => 'Save error!',
                    ];

                    return $response;
                }
            } else {
                $deleteAccountClientInfo = MatterAccountInstructingClient::find($resultAccountClientInfo->id);
                $deleteAccountClientInfo->delete();
                $response = [
                    'alert-type'    => 'error',
                    'message'       => 'Save error!',
                ];

                return $response;
            }

        } else {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Save error!',
            ];

            return $response;
        }

        return $response;
    }

    public function getAccountClientInformation($matterId)
    {
        $result = MatterAccountInstructingClient::where(['matter_id'=>$matterId, 'is_account'=>0])->get()->toArray();
        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    public function getAccountClientLocationInformation($matterId)
    {
        $result = MatterAccountInstructingClientLocation::where(['matter_id'=>$matterId, 'is_account'=>0])->get()->toArray();
        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    public function getAccountContactInformation($matterId)
    {
        $result = MatterAccountInstructingContact::where(['matter_id'=>$matterId, 'is_account'=>0])->get()->toArray();
        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    public function createInstructingClientInformation($data, $matterId)
    {
        $response = [
            'alert-type'    => 'success',
            'message'       => 'Save successfully!',
        ];

        $instructingClientInfo = array();
        $instructingClientLocationInfo = array();
        $instructingContactInfo = array();


        $instructingClientInfo = new MatterAccountInstructingClient;
        $instructingClientInfo = [
            'matter_id'         => $matterId,
            'name'              => $data['instructing-account-client-name'],
            'abn'               => $data['instructing-new-client-abn'],
            'billing_number'    => $data['instructing-billing-number'],
            'is_account'        => 1
        ];

        $resultInstructingClientInfo = MatterAccountInstructingClient::create($instructingClientInfo);

        if (!empty($resultInstructingClientInfo)) {
            $instructingClientLocationInfo = [
                'matter_id'         => $matterId,
                'client_id'         => $resultInstructingClientInfo->id,
                'location_name'     => $data['instructing-location-name'],
                'abn'               => $data['instructing-new-location-abn'],
                'address_1'         => $data['instructing-address-1'],
                'address_2'         => $data['instructing-address-2'],
                'country'           => $data['instructing-select-country'],
                'state'             => $data['instructing-select-state'],
                'city'              => $data['instructing-select-city'],
                'postcode'          => $data['instructing-postcode'],
                'primary_phone'     => $data['instructing-primary-phone'],
                'secondary_phone'   => $data['instructing-secondary-phone'],
                'fax'               => $data['instructing-fax'],
                'is_account'        => 1
            ];

            $resultInstructingClientLocationInfo = MatterAccountInstructingClientLocation::create($instructingClientLocationInfo);

            if (!empty($resultInstructingClientLocationInfo)) {
                $instructingContactInfo = [
                    'matter_id'         => $matterId,
                    'client_id'         => $resultInstructingClientInfo->id,
                    'location_id'       => $resultInstructingClientLocationInfo->id,
                    'name'              => $data['instructing-account-contact-name'],
                    'job_title'         => $data['instructing-account-contact-job-title'],
                    'email'             => $data['instructing-account-contact-email'],
                    'phone'             => $data['instructing-account-contact-phone'],
                    'mobile'            => $data['instructing-account-contact-mobile'],
                    'fax'               => $data['instructing-account-contact-fax'],
                    'note'              => $data['instructing-account-contact-note'],
                    'is_account'        => 1
                ];

                $resultInstructingContactInfo = MatterAccountInstructingContact::create($instructingContactInfo);
                if (empty($resultInstructingContactInfo)) {
                    $deleteInstructingClientInfo = MatterAccountInstructingClient::find($resultInstructingClientInfo->id);
                    $deleteInstructingClientInfo->delete();
                    $deleteInstructingClientLocationInfo = MatterAccountInstructingClientLocation::find($resultInstructingClientLocationInfo->id);
                    $deleteInstructingClientLocationInfo->delete();
                    $response = [
                        'alert-type'    => 'error',
                        'message'       => 'Save error!',
                    ];

                    return $response;
                }
            } else {
                $deleteInstructingClientInfo = MatterAccountInstructingClient::find($resultInstructingClientInfo->id);
                $deleteInstructingClientInfo->delete();
                $response = [
                    'alert-type'    => 'error',
                    'message'       => 'Save error!',
                ];

                return $response;
            }

        } else {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Save error!',
            ];

            return $response;
        }

        return $response;
    }

    public function getInstructingClientInformation($matterId)
    {
        $result = MatterAccountInstructingClient::where(['matter_id'=>$matterId, 'is_account'=>1])->get()->toArray();
        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    public function getInstructingClientLocationInformation($matterId)
    {
        $result = MatterAccountInstructingClientLocation::where(['matter_id'=>$matterId, 'is_account'=>1])->get()->toArray();
        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    public function getInstructingContactInformation($matterId)
    {
        $result = MatterAccountInstructingContact::where(['matter_id'=>$matterId, 'is_account'=>1])->get()->toArray();
        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    public function createInsurerInformation($data, $matterId)
    {
        $response = [
            'alert-type'    => 'success',
            'message'       => 'Save successfully!',
        ];

        $insurerInfo = array();
        $insurerInfo = [
            'matter_id'         => $matterId,
            'name'              => $data['insurer-client-name'],
            'policy_number'     => $data['insurer-policy-number'],
            'abn'               => $data['insurer-abn']
        ];

        $resultinsurerInfo = Insurers::create($insurerInfo);

        if (empty($resultinsurerInfo)) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Save error!',
            ];
        }
        
        return $response;
    }

    public function editInsurerInformation($data, $insurerId)
    {
        $response = [
            'alert-type'    => 'success',
            'message'       => 'Update successfully!',
        ];

        $insurerInfo = Insurers::find($insurerId);
        $insurerInfo->name              = $data['edit-insurer-client-name'];
        $insurerInfo->policy_number     = $data['edit-insurer-policy-number'];
        $insurerInfo->abn               = $data['edit-insurer-abn'];

        if (!$insurerInfo->save()) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Update error!',
            ];
        }
        
        return $response;
    }

    public function getInsurerInformation($matterId)
    {
        $result = Insurers::where(['matter_id'=>$matterId])->get()->toArray();
        if (!empty($result)) {
            $result = $result[0];
        }

        return $result;
    }

    public function handleEditAccountInstructingInformation($data, $id)
    {
        if ($data['account-client-type'] == 0) {
            $result = $this->editAccountClientInformation($data, $id);
        } else {
            $result = $this->editInstructingClientInformation($data, $id);
        }

        return $result;
    }

    public function editAccountClientInformation($data, $id)
    {
        $response = [
            'alert-type'    => 'success',
            'message'       => 'Update successfully!',
        ];

        $accountClientInfo = MatterAccountInstructingClient::find($id);
        $accountClientInfo->name            = $data['edit-account-client-name'];
        $accountClientInfo->abn             = $data['edit-new-client-abn'];
        $accountClientInfo->billing_number  = $data['edit-billing-number'];

        if (!$accountClientInfo->save()) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Save error!',
            ];

            return $response;
        }

        $accountClientLocationInfo = MatterAccountInstructingClientLocation::find($data['account-client-location-id']);
        $accountClientLocationInfo->location_name   = $data['edit-location-name'];
        $accountClientLocationInfo->abn             = $data['edit-new-location-abn'];
        $accountClientLocationInfo->address_1       = $data['edit-address-1'];
        $accountClientLocationInfo->address_2       = $data['edit-address-2'];
        $accountClientLocationInfo->country         = $data['edit-select-country'];
        $accountClientLocationInfo->state           = $data['edit-select-state'];
        $accountClientLocationInfo->city            = $data['edit-select-city'];
        $accountClientLocationInfo->postcode        = $data['edit-new-location-postcode'];
        $accountClientLocationInfo->primary_phone   = $data['edit-primary-phone'];
        $accountClientLocationInfo->secondary_phone = $data['edit-secondary-phone'];
        $accountClientLocationInfo->fax             = $data['edit-fax'];

        if (!$accountClientLocationInfo->save()) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Save error!',
            ];

            return $response;
        }

        $accountClientContactInfo = MatterAccountInstructingContact::find($data['account-client-contact-id']);
        $accountClientContactInfo->name              = $data['edit-account-contact-name'];
        $accountClientContactInfo->job_title         = $data['edit-account-contact-job-title'];
        $accountClientContactInfo->email             = $data['edit-account-contact-email'];
        $accountClientContactInfo->phone             = $data['edit-account-contact-phone'];
        $accountClientContactInfo->mobile            = $data['edit-account-contact-mobile'];
        $accountClientContactInfo->fax               = $data['edit-account-contact-fax'];
        $accountClientContactInfo->note              = $data['edit-account-contact-note'];

        if (!$accountClientContactInfo->save()) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Save error!',
            ];

            return $response;
        }

        return $response;
    }

    public function editInstructingClientInformation($data, $id)
    {
        $response = [
            'alert-type'    => 'success',
            'message'       => 'Update successfully!',
        ];

        $instructingClientInfo = MatterAccountInstructingClient::find($id);
        $instructingClientInfo->name            = $data['edit-instructing-account-client-name'];
        $instructingClientInfo->abn             = $data['edit-instructing-new-client-abn'];
        $instructingClientInfo->billing_number  = $data['edit-instructing-billing-number'];

        if (!$instructingClientInfo->save()) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Save error!',
            ];

            return $response;
        }

        $instructingClientLocationInfo = MatterAccountInstructingClientLocation::find($data['account-client-location-id']);
        $instructingClientLocationInfo->location_name   = $data['edit-instructing-location-name'];
        $instructingClientLocationInfo->abn             = $data['edit-instructing-new-location-abn'];
        $instructingClientLocationInfo->address_1       = $data['edit-instructing-address-1'];
        $instructingClientLocationInfo->address_2       = $data['edit-instructing-address-2'];
        $instructingClientLocationInfo->country         = $data['edit-instructing-select-country'];
        $instructingClientLocationInfo->state           = $data['edit-instructing-select-state'];
        $instructingClientLocationInfo->city            = $data['edit-instructing-select-city'];
        $instructingClientLocationInfo->postcode        = $data['edit-instructing-postcode'];
        $instructingClientLocationInfo->primary_phone   = $data['edit-instructing-primary-phone'];
        $instructingClientLocationInfo->secondary_phone = $data['edit-instructing-secondary-phone'];
        $instructingClientLocationInfo->fax             = $data['edit-instructing-fax'];

        if (!$instructingClientLocationInfo->save()) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Save error!',
            ];

            return $response;
        }

        $instructingClientContactInfo = MatterAccountInstructingContact::find($data['account-client-contact-id']);
        $instructingClientContactInfo->name              = $data['edit-instructing-account-contact-name'];
        $instructingClientContactInfo->job_title         = $data['edit-instructing-account-contact-job-title'];
        $instructingClientContactInfo->email             = $data['edit-instructing-account-contact-email'];
        $instructingClientContactInfo->phone             = $data['edit-instructing-account-contact-phone'];
        $instructingClientContactInfo->mobile            = $data['edit-instructing-account-contact-mobile'];
        $instructingClientContactInfo->fax               = $data['edit-instructing-account-contact-fax'];
        $instructingClientContactInfo->note              = $data['edit-instructing-account-contact-note'];

        if (!$instructingClientContactInfo->save()) {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Save error!',
            ];

            return $response;
        }

        return $response;
    }

    public function getAssignToMe($matterId)
    {
        $result = false;
        if (!empty(MattersUsers::where(['case_id'=>$matterId, 'user_id'=>auth()->user()->id])->get()->toArray())) {
            $result = true;
        }

        return $result;
    }

    public static function countStatusMatterByUser($status = null, $userId)
    {
        $count = 0;

        if (!empty($status)) {
            $caseUsers = Matter::with('mattersusers')->where('last_state', $status)
                        ->get()->toArray();
            foreach($caseUsers as $case) {
                if(!empty($case['mattersusers']) && $case['mattersusers']['user_id'] == $userId) {
                    $count = $count + 1;
                }
            }
        }

        return $count;
    }

    public function getListTypeIdByMatter()
    {
        $listTypeIdByMatter = Matter::where('user_id', auth()->user()->id)
                        ->orWhereHas('mattersusers', function($relation) {
                            $relation->where('cases_users.user_id', auth()->user()->id);
                        })
                        ->groupBy('type_id')
                        ->pluck('type_id');

        if (!$listTypeIdByMatter->isEmpty())
            $listTypeIdByMatter = $listTypeIdByMatter->toArray();
        else
            $listTypeIdByMatter = array();

        return $listTypeIdByMatter;
    }

    public function getListLocationByMatter()
    {
        $listLocationByMatter = array();
        $listClientIdByMatter = Matter::where('user_id', auth()->user()->id)
                        ->orWhereHas('mattersusers', function($relation) {
                            $relation->where('cases_users.user_id', auth()->user()->id);
                        })
                        ->groupBy('client_id')
                        ->pluck('client_id');

        $listLocationIdByMatter = Matter::where('user_id', auth()->user()->id)
                        ->orWhereHas('mattersusers', function($relation) {
                            $relation->where('cases_users.user_id', auth()->user()->id);
                        })
                        ->groupBy('location_id')
                        ->pluck('location_id');

        if (!$listClientIdByMatter->isEmpty() && !$listLocationIdByMatter->isEmpty()) {
            $listClientIdByMatter = $listClientIdByMatter->toArray();
            $listLocationIdByMatter = $listLocationIdByMatter->toArray();

            $listLocationByMatter = Clients::whereIn('id', $listClientIdByMatter)
                                    ->with(array('locations' => function($relation) use($listLocationIdByMatter) {
                                        $relation->whereIn('locations.id',  $listLocationIdByMatter);
                                    }))->get();
        }

        return $listLocationByMatter;
    }

    public function getListAssigneeByMatter()
    {
        $listAssigneeByMatter = array();

        $listMatterByUserIdLogin = Matter::where('user_id', auth()->user()->id)
                                ->orWhereHas('mattersusers', function($relation) {
                                    $relation->select('case_id AS id')
                                            ->where('cases_users.user_id', auth()->user()->id);
                                })
                                ->groupBy('id')
                                ->pluck('id');

        $listUserIdByMatter = MattersUsers::whereIn('case_id', $listMatterByUserIdLogin)
                        ->groupBy('user_id')
                        ->pluck('user_id');

        if (!$listUserIdByMatter->isEmpty()) {
            $listUserIdByMatter = $listUserIdByMatter->toArray();

            $listAssigneeByMatter = User::select('id', 'name', 'family_name')->whereIn('id', $listUserIdByMatter)->get();
        }

        return $listAssigneeByMatter;
    }

    public function deleteUserMatter($roleId, $matterId, $userId)
    {
        $count = count(MattersUsers::where(['case_id' => $matterId, 'role_id' => $roleId])->get()->toArray());
        if ($count > 1) {
            MattersUsers::where(['case_id' => $matterId, 'user_id' => $userId, 'role_id' => $roleId])->delete();
        }
    }

    public function addMilestone($req, $matterId)
    {
        $response = [
            'alert-type'    => 'error',
            'message'       => 'Save error!',
        ];

        $data = $req->all();
        $dataToInsert = array();
        if (!empty($data)) {
            $dataToInsert = [
                "case_id"       => $matterId,
                "date_type"     => empty($data["date-type"]) ? '' : $data["date-type"],
                "date"          => empty($data["date"]) ? '' : $data["date"]
            ];
        }

        if (Milestones::create($dataToInsert)) {
            $response = [
                'alert-type'    => 'success',
                'message'       => 'Save successfully!',
            ];
        }

        return $response;
    }

    public function getMilestones($matterId)
    {
        $result = Milestones::where(['case_id'=>$matterId])->get()->toArray();

        return $result;
    }

    public function getFileManagers($matterId, $path, $checkParrent = true)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        if ($checkParrent == true) {
            if(!$disk->exists($path . 'file manager/')) {
                $disk->makeDirectory($path . 'file manager/', 0775, true); //creates directory
            }
        }
        $listFolders = $disk->directories($path . ($checkParrent == true ? 'file manager/' : ''));
        $listFiles = $disk->files($path . ($checkParrent == true ? 'file manager/' : ''));

        $listFolders = $this->getDetailForFolders($listFolders, $matterId);
        $listFiles = $this->getDetailForFiles($disk, $listFiles, $matterId);

        $fileManagers = array_merge($listFolders, $listFiles);

        return $fileManagers;
    }

    protected function getDetailForFolders($folders, $matterId)
    {
        $listFolders = array();

        foreach ($folders as $key => $folder) {
            $folderInfo = DB::table('file_manager')
                            ->where('case_id', $matterId)
                            ->where('path', $folder)
                            ->get()->first();
            if (!empty($folderInfo)) {
                $listFolders[$key]['type'] = 'folder';
                $listFolders[$key]['path'] = $folder;
                $listFolders[$key]['name'] = $folderInfo->name;
                $listFolders[$key]['info'] = $folderInfo;
            } else {
                $this->deleteFolder($folder);
            }
        }

        return $listFolders;
    }

    public function getDetailForFiles($disk, $files, $matterId)
    {
        $listFiles = array();
        $disk = Storage::disk(env('DISK_STORAGE'));

        $tenantDetail = tenancy()->getTenant();
        $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";

        foreach ($files as $key => $file) {
            $fileInfo = DB::table('file_manager')
                            ->where('case_id', $matterId)
                            ->where('path', $file)
                            ->get()->first();
            if (!empty($fileInfo)) {
                $listFiles[$key]['type'] = 'file';
                $listFiles[$key]['path'] = $disk->url($pathTenant . $file);
                $listFiles[$key]['name'] = $fileInfo->name;
                $listFiles[$key]['size'] = $this->formatBytes($disk->size($file));
                $listFiles[$key]['info'] = $fileInfo;
            } else {
                $this->deleteFile($file);
            }
        }

        return $listFiles;
    }

    public function getPathToBack($path)
    {
        $pathToBack = '';
        $folderName = explode("/", $path);
        if ($folderName[count($folderName) - 1] == 'file manager') {
            return $pathToBack;
        }

        unset($folderName[count($folderName) - 1]);
        $pathToBack = implode("/", $folderName);

        return $pathToBack;
    }

    public function createFolder($request)
    {
        $response = [
            'alert-type'    => 'error',
            'message'       => 'Please enter another folder name.'
        ];

        if (trim($request->folderName) == '') {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Please input folder name.'
            ];

            return $response;
        }

        if ($this->isValidFolderName($request->folderName, $request->path . '/' . $request->folderName, $request->matterId)) {
            $response = [
                'alert-type'    => 'success',
                'message'       => 'Save successfully!'
            ];

            $path = $request->path . '/' . $request->folderName . '/';

            $disk = Storage::disk(env('DISK_STORAGE'));
            $disk->makeDirectory($path, 0775, true); //creates directory
            DB::table('file_manager')->insert([
                'case_id'     => $request->matterId,
                'name'        => $request->folderName,
                'user_id'     => auth()->user()->id,
                'path'        => $request->path . '/' . $request->folderName,
                'description' => $request->description,
                'created_at'  => now(),
                'updated_at'  => now()
            ]);
        }

        return $response;
    }

    public function isValidFolderName($folderName, $path, $matterId, $folderId = null)
    {
        $count = DB::table('file_manager')
                ->where('case_id', $matterId)
                ->where('path', $path);
        if (!empty($folderId)) {
            $count = $count->where('id', '<>', $folderId);
        }
        $count = $count->count();

        return (strpbrk($folderName, '/?%*:|"<>.') === false && $count == 0) ? true : false;
    }

    public function uploadFiles($request)
    {
        $listFiles = $request->file;
        $countFile = count($listFiles);
        $successTime = 0;
        $path = $request->path;

        foreach ($listFiles as $file) {
            $uploadResponse = $this->uploadFile($file, $path);
            if($uploadResponse->status() == 200) {
                $fileName = str_replace(" ", "_", $file->getClientOriginalName());
                $fileInfo['case_id'] = $request->matterId;
                $fileInfo['name']    = $fileName;
                $fileInfo['user_id'] = auth()->user()->id;
                $fileInfo['path']    = $path . '/' . $fileName;
                $fileInfo['created_at'] = now();
                $fileInfo['updated_at'] = now();
                if (DB::table('file_manager')->insert($fileInfo)) {
                    $successTime ++;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        if ($countFile == $successTime) {
            return true;
        }

        return false;
    }

    private function uploadFile(UploadedFile $file, $path) {
        $fileName = str_replace(" ", "_", $file->getClientOriginalName());
        $disk = Storage::disk(env('DISK_STORAGE'));

        //create new file
        $disk->putFileAs($path, $file, $fileName);

        return response()->json();
    }

    public function deleteFolder($path, $matterId = null, $checkDB = false)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $disk->deleteDirectory($path);

        if ($checkDB && !empty($matterId)) {
            $response = $this->deleteFileManager($path, $matterId);

            return $response;
        }
    }

    public function deleteFile($path, $matterId = null, $checkDB = false)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $disk->delete($path);

        if ($checkDB && !empty($matterId)) {
            $response = $this->deleteFileManager($path, $matterId, 'file');

            return $response;
        }
    }

    protected function deleteFileManager($path, $matterId, $type = 'folder')
    {
        $response = [
            'alert-type'    => 'error',
            'message'       => 'Delete fail'
        ];

        if (DB::table('file_manager')->where('case_id', $matterId)
                                    ->where('path', $path)
                                    ->delete()) {
            if ($type === 'folder') {
                DB::table('file_manager')->where('case_id', $matterId)
                                    ->where('path', 'like', $path . '%')
                                    ->delete();
            }

            $response = [
                'alert-type'    => 'success',
                'message'       => 'Delete successfully!'
            ];
        }

        return $response;
    }

    public function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }

    public function editFolder($request)
    {
        $response = [
            'alert-type'    => 'error',
            'message'       => 'Please enter another folder name.'
        ];

        if (trim($request->name) == '') {
            $response = [
                'alert-type'    => 'error',
                'message'       => 'Please input folder name.'
            ];

            return $response;
        }

        $folderDetail = DB::table('file_manager')
                    ->where('case_id', $request->matterId)
                    ->where('id', $request->id)
                    ->first();

        if (!empty($folderDetail) && $request->name === $folderDetail->name) {
            $response = [
                'alert-type'    => 'success',
                'message'       => 'Save successfully!'
            ];

            DB::table('file_manager')
                        ->where('case_id', $request->matterId)
                        ->where('path', $request->path)
                        ->update([
                            'description' => $request->description,
                            'updated_at'  => now()
                        ]);

            return $response;
        }

        if ($this->isValidFolderName($request->name, $request->currentPath . '/' . $request->name, $request->matterId, $request->id)) {
            $response = [
                'alert-type'    => 'success',
                'message'       => 'Save successfully!'
            ];

            $disk = Storage::disk(env('DISK_STORAGE'));

            // move folders/files

            $directories = $disk->allDirectories($request->path);
            foreach ($directories as $key => $directory) {
                $newPath = str_replace($request->path, $request->currentPath . '/' . $request->name, $directory);
                $disk->makeDirectory($newPath, 0775, true);

                DB::table('file_manager')
                        ->where('case_id', $request->matterId)
                        ->where('path', $directory)
                        ->update([
                            'path'        => $newPath,
                            'updated_at'  => now()
                        ]);
            }

            $files = $disk->allFiles($request->path);
            foreach ($files as $key => $file) {
                $newPath = str_replace($request->path, $request->currentPath . '/' . $request->name, $file);
                $disk->move($file, $newPath);

                DB::table('file_manager')
                        ->where('case_id', $request->matterId)
                        ->where('path', $file)
                        ->update([
                            'path'        => $newPath,
                            'updated_at'  => now()
                        ]);
            }

            $this->deleteFolder($request->path);

            DB::table('file_manager')
                        ->where('case_id', $request->matterId)
                        ->where('path', $request->path)
                        ->update([
                            'path'        => $request->currentPath . '/' . $request->name,
                            'name'        => $request->name,
                            'description' => $request->description,
                            'updated_at'  => now()
                        ]);

            // end move folders/files
        }

        return $response;
    }

    public function editFile($request)
    {
        DB::table('file_manager')
                ->where('case_id', $request->matterId)
                ->where('path', $request->path)
                ->update([
                    'description' => $request->description,
                    'updated_at'  => now()
                ]);
    }

    // load image for notation
    public static function loadFileForNotation($matterId, $fileName)
    {
        $disk = Storage::disk(env('DISK_STORAGE'));
        $tenantDetail = tenancy()->getTenant();
        $pathTenant = (!empty($tenantDetail)) ? "tenant/" . $tenantDetail->data['id'] . "/" : "";

        $path = $pathTenant . "matters/$matterId/notations/";
        $url = $disk->url($path . $fileName);

        return $url;
    }
}