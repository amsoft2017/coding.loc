<?php

class actionLotteryAddLotto extends cmsAction
{

    public function run()
    {

        $user = cmsUser::getInstance();

        if (!$user->is_admin) {
            cmsCore::error404();
        }

        $errors = false;

        $model = cmsCore::getModel($this->name);

        $form = $this->getForm('lotto');

        $is_submitted = $this->request->has('submit');

        $lotto = $form->parse($this->request, $is_submitted);

        if ($is_submitted) {

            $errors = $form->validate($this, $lotto);

            if (!$errors) {
                $lotto_id = $model->addLotto($lotto);
                $this->redirectToAction('lottos');
            }

            if ($errors) {
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
            }

        }

        $template = cmsTemplate::getInstance();

        return $template->render('backend/form_lotto', array(
            'do' => 'add',
            'form' => $form,
            'errors' => $errors,
            'lotto' => $lotto
        ));

    }

}