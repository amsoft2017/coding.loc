<?php

class actionLotteryEditLotto extends cmsAction
{

    public function run($id = false)
    {

        if (!$id) {
            cmsCore::error404();
        }

        $model = cmsCore::getModel($this->name);

        $lotto = $this->model->getLotto($id);

        if (!$lotto) {
            cmsCore::error404();
        }

        $errors = false;

        $form = $this->getForm('lotto');

        $is_submitted = $this->request->has('submit');

        if ($is_submitted) {

            $lotto = $form->parse($this->request, $is_submitted);

            $errors = $form->validate($this, $lotto);

            if (!$errors) {
                $model->updateLotto($id, $lotto);
                $this->redirectToAction('lottos');
            }

            if ($errors) {
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
            }

        }

        $template = cmsTemplate::getInstance();

        return $template->render('backend/form_lotto', array(
            'do' => 'edit',
            'form' => $form,
            'errors' => $errors,
            'lotto' => $lotto
        ));

    }

}