<?php

class actionLotteryEditLotto extends cmsAction
{

    public function run($id = false)
    {

        if (!$id) {
            cmsCore::error404();
        }

        $lotto = $this->model->getLotto($id);

        if (!$lotto) {
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

        $form = $this->getForm('lotto');

        $this->setFieldsSeo($form);

        $is_submitted = $this->request->has('submit');

        if ($is_submitted) {

            $lotto = $form->parse($this->request, $is_submitted);

            $errors = $form->validate($this, $lotto);

            if (!$errors) {
                $this->model->updateLotto($id, $lotto);
                $this->redirectToAction('lotto', array($id));
            }

            if ($errors) {
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
            }

        }

        $template = cmsTemplate::getInstance();

        return $template->render('form_lotto', array(
            'do' => 'edit',
            'form' => $form,
            'errors' => $errors,
            'lotto' => $lotto
        ));

    }

}