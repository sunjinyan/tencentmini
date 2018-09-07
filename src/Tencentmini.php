<?php

namespace Ttmn\Tencentmini;

class Tencentmini
{
    //
    protected $MINI_APPID =   '';
    protected $APP_SECRET =   '' ;
    protected $IM_ACCOUNT_TYPE =   '';
    protected $SDK_APP_ID =   '';
    protected $LIVE_APP_ID =   '';
    protected $BIZID =   '';
    protected $PUSH_SECRET_KEY =   '';
    protected $API_KEY =   '';
    protected $ADMINISTRATOR =   '';

    private $user_id    =   null;


    public function __construct()
    {
            $this->MINI_APPID    =   config('tencentmini.tencent_live.wechat_appid');
            $this->APP_SECRET    =   config('tencentmini.tencent_live.wechat_secret');
            $this->IM_ACCOUNT_TYPE    =   config('tencentmini.tencent_live.tencent_account_type');
            $this->SDK_APP_ID    =   config('tencentmini.tencent_live.tencent_sdk_app_id');
            $this->LIVE_APP_ID    =   config('tencentmini.tencent_live.tencent_app_id');
            $this->BIZID    =   config('tencentmini.tencent_live.tencent_bizid');
            $this->PUSH_SECRET_KEY    =   config('tencentmini.tencent_live.push_secret_key');
            $this->API_KEY    =   config('tencentmini.tencent_live.api_key');
            $this->ADMINISTRATOR    =   config('tencentmini.tencent_live.administrator');
    }


    public function room_list($roomList){

        return [
            "rooms"=>$roomList
        ];
    }

    public function get_user_id($u_id)
    {
        $this->user_id  =   $u_id;
        $user_id    =   $this->set_user_id();
        return  [
            "userID"=>$user_id
        ];
    }

    public function get_login_info($user_id){

        return  [
            "sdkAppID"=>$this->SDK_APP_ID,
            "accountType"=>$this->IM_ACCOUNT_TYPE,
            "userSig"=>$this->get_user_sig($user_id),
            "userID"=>$user_id,
        ];
    }


    public function create_room($userID,$roomInfo,$roomID){

        return [
            "roomID"=>$roomID,
            "roomInfo"=>$roomInfo,
            "privateMapKey"=>$this->get_private_map_key($userID,$roomID),
            "userID"=>$userID,
        ];
    }


    private function set_user_id(){

        return  config('tencentmini.user_prefer').$this->user_id."_".mt_rand(10000,99999);
    }

    private function get_user_sig($user_id){
        try{
            $sdkappid = $this->SDK_APP_ID;
            $userid = $user_id;


            $api = new WebRTCSigApi();

            $api->setSdkAppid($sdkappid);


            $private = file_get_contents(public_path().DIRECTORY_SEPARATOR.'private_key');
            $api->SetPrivateKey($private);


            $public = file_get_contents(public_path().DIRECTORY_SEPARATOR.'public_key');
            $api->SetPublicKey($public);



            $userSig = $api->genUserSig($userid);
            $result = $api->verifyUserSig($userSig, $userid, $init_time, $expire_time, $error_msg);


            if(!$result){$this->response->error("this is bad user's sig",422);}
            return  $userSig;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    private function get_private_map_key($user_id,$roomid){
        try{
            $sdkappid = $this->SDK_APP_ID;
            $roomid = $roomid;
            $userid = $user_id;


            $api = new WebRTCSigApi();

            $api->setSdkAppid($sdkappid);


            $private = file_get_contents(public_path().DIRECTORY_SEPARATOR.'private_key');
            $api->SetPrivateKey($private);

            $public = file_get_contents(public_path().DIRECTORY_SEPARATOR.'public_key');
            $api->SetPublicKey($public);


            $privateMapKey = $api->genPrivateMapKey($userid, $roomid);
            $result = $api->verifyPrivateMapKey($privateMapKey, $userid, $init_time, $expire_time, $userbuf, $error_msg);

            if(!$result)$this->response->error("this is bad rome's sig",422);
            return $privateMapKey;
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}