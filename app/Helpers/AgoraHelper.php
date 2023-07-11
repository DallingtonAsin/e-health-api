<?php
namespace App\Helpers;

use Willywes\AgoraSDK\RtcTokenBuilder;

class AgoraHelper
{
    public static function GetToken($user_id, $channelName){
    
        $appID = config('app.AGORA_APP_ID');
        $appCertificate = config('app.AGORA_APP_CERTIFICATE');
        $uid = $user_id;
        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 10800;
        $currentTimestamp = (new \DateTime("now", new \DateTimeZone('UTC')))->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;
    
        return RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);
    }
}