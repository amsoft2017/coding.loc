<?php

class actionLotteryEditPlayer extends cmsAction
{

    public function run($id = false)
    {

        if (!$id) {
            cmsCore::error404();
        }

        $model = cmsCore::getModel($this->name);

        $player = $this->model->getPlayer($id);

        if (!$player) {
            cmsCore::error404();
        }

        $errors = false;

        $form = $this->getForm('player');

        $is_submitted = $this->request->has('submit');

        if ($is_submitted) {

            $player = $form->parse($this->request, $is_submitted);

            $errors = $form->validate($this, $player);

            if (!$errors) {
                $model->updatePlayer($id, $player);
                $this->redirectToAction('players');
            }

            if ($errors) {
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
            }

        }

        $template = cmsTemplate::getInstance();

        return $template->render('backend/form_player', array(
            'do' => 'edit',
            'form' => $form,
            'errors' => $errors,
            'player' => $player
        ));

    }

}