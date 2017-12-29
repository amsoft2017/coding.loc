<?php

class actionLotteryEditPlayer extends cmsAction
{

    public function run($id = false)
    {

        if (!$id) {
            cmsCore::error404();
        }

        $player = $this->model->getPlayer($id);

        if (!$player) {
            cmsCore::error404();
        }

        $user = cmsUser::getInstance();

        if (!$user->is_admin) {
            cmsCore::error404();
        }

        $is_can_edit = true;

        if (!$is_can_edit) {
            cmsCore::error404();
        }

        $errors = false;

        $form = $this->getForm('player');

        $is_submitted = $this->request->has('submit');

        if ($is_submitted) {

            $player = $form->parse($this->request, $is_submitted);

            $errors = $form->validate($this, $player);

            if (!$errors) {
                $this->model->updatePlayer($id, $player);
                $this->redirectToAction('player', array($id));
            }

            if ($errors) {
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
            }

        }

        $template = cmsTemplate::getInstance();

        return $template->render('form_player', array(
            'do' => 'edit',
            'form' => $form,
            'errors' => $errors,
            'player' => $player
        ));

    }

}