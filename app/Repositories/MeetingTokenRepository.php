<?php 
namespace App\Repositories;

use App\Models\MeetingToken;
use App\Class\AgoraDynamicKey\RtcTokenBuilder;

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

    public function get($id = null)
    {
        if($id){
           return $this->meetingToken->find($id);
        }
        return $this->meetingToken->select(['appointment_id', 'app_id', 'channel', 'token'])->get();
    }

    public function update($id, $meetingTokenData)
    {
        $meetingToken = $this->meetingToken->find($id);
        $meetingToken->update($meetingTokenData);
        return $meetingToken;
    }

    public function delete($id)
    {
        $meetingToken = $this->meetingToken->find($id);
        $meetingToken->delete();
        return $meetingToken;
    }

    public function generateMeetingToken($patient, $appointment_number, $is_video){
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $appointment_number;
        $user = $patient->first_name . " ". $patient->last_name;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        return $token;
    }

}