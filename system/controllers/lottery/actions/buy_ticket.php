<?php

class actionLotteryBuyTicket extends cmsAction
{

    public function run($id_users = false, $id_lotto = false, $nickname = false, $price = false)
    {

        $us = cmsUser::getInstance();

        if (!$us->is_logged) {
            cmsCore::error404();
        }

        if (!$id_lotto || !$id_users || !$nickname) {
            cmsCore::error404();
        }

        $user_model = cmsCore::getModel('users');
        $user = $user_model->getUser($id_users);

        $lotto = $this->model->getLotto($id_lotto);
        if (isset($lotto['winner'])) {
            cmsUser::addSessionMessage(LANG_LOTTERY_LOTTO_BUY_ERROR, 'error');

            $this->redirectToAction('lotto', array($id_lotto));
            return;
        }


        if ($user['rating'] > $price) {

            $rating = $this->model->updateUserRating($user['id'], -$price);

            if ($rating) {
                cmsUser::addSessionMessage(sprintf(LANG_LOTTERY_LOTTO_BUY_RATING_SUCCESS, $price), 'success');
            }

            $players = $this->model->filterEqual('id_lotto', $id_lotto)->filterEqual('id_users', $user['id'])->getPlayers();

            $player['nickname'] = $user['nickname'];
            $player['id_users'] = $user['id'];
            $player['id_lotto'] = $id_lotto;

            if (!$players) {
                $player['ticket'] = 1;

                $player_id = $this->model->addPlayer($player);
                if ($player_id) {
                    $this->model->countIncrement($player['id_lotto'], 1);

                    cmsUser::addSessionMessage(LANG_LOTTERY_LOTTO_BUY_TICKET_SUCCESS, 'success');
                    $this->redirectToAction('lotto', array($player['id_lotto']));
                }
            }

            if ($players) {

                foreach ($players as $pl) {

                    $player['ticket'] = $pl['ticket'] + 1;

                    $player_id = $this->model->updatePlayer($pl['id'], $player);
                    if ($player_id) {
                        $this->model->countIncrement($player['id_lotto'], 1);
                        cmsUser::addSessionMessage(LANG_LOTTERY_LOTTO_BUY_TICKET_SUCCESS, 'success');
                        $this->redirectToAction('lotto', array($player['id_lotto']));
                    }

                }

            }


        }

        if ($user['rating'] < $price) {

            cmsUser::addSessionMessage(LANG_LOTTERY_LOTTO_BUY_RATING_ERROR, 'error');
            $this->redirectToAction('lotto', array($id_lotto));
        }

    }

}
