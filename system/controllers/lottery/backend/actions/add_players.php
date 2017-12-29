<?php

class actionLotteryAddPlayers extends cmsAction
{

    public function run()
    {

        $us = cmsUser::getInstance();

        if (!$us->is_admin) {
            cmsCore::error404();
        }

        $errors = false;

        $form = $this->getForm('add_players');


        $is_submitted = $this->request->has('submit');

        $add = $form->parse($this->request, $is_submitted);

        if ($is_submitted) {

            $errors = $form->validate($this, $add);

            if (!$errors) {

                $groups = $add['id_groups'];

                $user_model = cmsCore::getModel('users');

                $users = $user_model->filterGroups($groups)->getUsers();

                $count = 0;

                foreach ($users as $user) {

                    $players = $this->model->filterEqual('id_lotto', $add['id_lotto'])->filterEqual('id_users', $user['id'])->getPlayers();

                    if ($players) {
                        continue;
                    }
                    if (!$players) {

                        $player['nickname'] = $user['nickname'];
                        $player['id_users'] = $user['id'];
                        $player['id_lotto'] = $add['id_lotto'];
                        $player['ticket'] = 1;

                        $player_id = $this->model->addPlayer($player);
                        if ($player_id) {

                            $this->model->countIncrement($player['id_lotto'], 1);
                            $count++;

                        }


                    }
                }

                if ($count !== 0) {
                    cmsUser::addSessionMessage(LANG_LOTTERY_PLAYERS_SUCCESS, 'success');
                    $this->redirectToAction('add_players');
                } else {
                    cmsUser::addSessionMessage(LANG_LOTTERY_PLAYERS_ERROR, 'error');
                    $this->redirectToAction('add_players');
                }


            }

            if ($errors) {
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
            }

        }


        $template = cmsTemplate::getInstance();

        return $template->render('backend/form_add_players', array(
            'do' => 'add',
            'form' => $form,
            'errors' => $errors,
            'add' => $add,

        ));

    }

}
