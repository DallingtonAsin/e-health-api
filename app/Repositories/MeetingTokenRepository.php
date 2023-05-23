<?php

namespace App\Repositories;

use App\Models\MeetingToken;
use App\Agora\AgoraDynamicKey\RtcTokenBuilder;
use Illuminate\Broadcasting\Channel;

class MeetingTokenRepository
{
    protected $meetingToken;

    public function __construct(MeetingToken $meetingToken)
    {
        $this->meetingToken = $meetingToken;
    }

    public function create($meetingTokenData)
    {
        return $this->meetingToken->create($meetingTokenData);
    }

    public function find($id)
    {
        return $this->meetingToken->find($id);
    }

    public function get()
    {
        $meeting_details = $this->meetingToken->select(['appointment_id', 'app_id', 'channel', 'token'])
            ->with('medicalAppointment')
            ->get();
        return $meeting_details;
    }

    public function update($id, $meetingTokenData)
    {
        $meetingToken = $this->meetingToken->find($id);
        $meetingToken->update($meetingTokenData);
        return $meetingToken;
    }


    public function getMeetingDetails($appointment_id)
    {
        return $this->meetingToken->where('appointment_id', $appointment_id)->with(['appointment' => function ($query) {
            $query->select(['id', 'patient_id', 'doctor_id', 'appointment_number', 'appointment_date']);
        }])->select('app_id as appId', 'channel', 'token')
            ->get();
    }

    public function delete($id)
    {
        $meetingToken = $this->meetingToken->find($id);
        $meetingToken->delete();
        return $meetingToken;
    }

    public function generateMeetingToken($patient, $appointment_number, $is_video)
    {

        $appID = config('app.AGORA_APP_ID');
        $appCertificate = config('app.AGORA_APP_CERTIFICATE');
        $token = config('app.AGORA_TEMP_TOKEN');

        $channelName = $appointment_number;
        $user = $patient->first_name . " " . $patient->last_name;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 86400;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        if (is_null($token)) {
            $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);
        }

        return $token;
    }
}
