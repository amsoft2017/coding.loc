<?php

class actionLotteryAddPlayer extends cmsAction
{

    public function run()
    {
        $user = cmsUser::getInstance();

        if (!$user->is_admin) {
            cmsCore::error404();
        }

        $errors = false;

        $form = $this->getForm('player');

        $is_submitted = $this->request->has('submit');

        $player = $form->parse($this->request, $is_submitted);

        if ($is_submitted) {

            $errors = $form->validate($this, $player);

            if (!$errors) {
                $is_can = ModelLottery::youCanAddTicket($player['id_users'], $player['id_lotto']);
                if($is_can === true){
                    $add = $this->model->addTicketToUser($player['id_lotto'], $player['id_users']);
                    if($add) cmsUser::addSessionMessage(LANG_LOTTERY_PLAYER_SUCCESS, 'success');
                    else cmsUser::addSessionMessage(LANG_LOTTERY_PLAYER_ERROR, 'error');
                }else {
                    cmsUser::addSessionMessage('У пользователя уже  максимально допустимое количество билетов для данного розыгрыша', 'error');

                }
                $this->redirectToAction('add_player');
            }

            if ($errors) {
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
            }

        }

        $template = cmsTemplate::getInstance();

        return $template->render('backend/form_player', array(
            'do' => 'add',
            'form' => $form,
            'errors' => $errors,
            'player' => $player
        ));

    }

}