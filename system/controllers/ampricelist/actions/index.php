<?php

class actionAmpricelistIndex extends cmsAction{

    public function run(){

    if(isset($_POST['send'])){

        $is_captcha_valid = cmsEventsManager::hook('captcha_validate', $this->request);

        if (!$is_captcha_valid){

            cmsUser::addSessionMessage(LANG_CAPTCHA_ERROR, 'error');
            $this->redirectBack();
        }

        $email_to = $_POST['email_to'];
        if(isset($_POST['email_reply_to'])){
            $mail = $_POST['email_reply_to'];
        }
        if(isset($_POST['phone'])){
            $tel = $_POST['phone'];
        }
        $summ = 0;

        for($i = 1;$i <= $_POST['count'];$i++){
            $col = $_POST["col$i"];
            if($col<=0) continue;
            $cost = $col * $_POST["cena$i"];
            $send_list[] = array(
                'name' => $_POST["name$i"],
                'cena' => LANG_AMPRICELIST_TABLE_THEAD_CENA.$_POST["cena$i"],
                'col' => LANG_AMPRICELIST_TABLE_THEAD_COL.$col,
                'cost' => LANG_AMPRICELIST_TABLE_THEAD_COST.$cost);
            $summ += $cost;
        }

        if($summ == 0){
            cmsUser::addSessionMessage(LANG_AMPRICELIST_MESSAGE_ERROR, 'error');
            $this->redirectBack();
        }
        $str = "";
        foreach($send_list as $list){
            $send_str = implode("   ",$list);
            $str = $str."\n".$send_str;
        }

        $messenger = cmsCore::getController('messages');
        $to = array('email' => $email_to, 'email_reply_to' => $mail);
        $letter = array('name' => 'ampricelist_letter');


        $send = $messenger->sendEmail($to, $letter, array(
            'message' => $str,
            'summ' => $summ,
            'mail' => $mail,
            'tel' => $tel,
        ));

        if ($send){
            cmsUser::addSessionMessage(LANG_AMPRICELIST_MESSAGE_SUCCESS, 'success');
            $this->redirectBack();
        }



    }


    }

}
