<?php

class onLotteryCommentBeforeAdd extends cmsAction
{

    public function run($data)
    {

        $lottos = $this->model->filter('i.start_date >= NOW()')->filterEqual('is_comments', 1)->filterEqual('winner', null)->getLottos();

        if ($lottos) {

            foreach ($lottos as $lotto) {

                $user_model = cmsCore::getModel('users');
                $user = $user_model->getUser($data['user_id']);

                $players = $this->model->filterEqual('id_lotto', $lotto['id'])->filterEqual('id_users', $user['id'])->getPlayers();

                if (!$players) {

                    $user_coms = $this->model->filterEqual('id_lotto', $lotto['id'])->filterEqual('id_users', $user['id'])->getComments();

                    if (!$user_coms) {



                        $com_data['id_lotto'] = $lotto['id'];
                        $com_data['id_users'] = $user['id'];
                        $com_data['count'] = 1;
                        $com_data['nickname'] = $user['nickname'];
                        $com_data['round'] = 1;
                        $com_data_id = $this->model->addComment($com_data);

                        if ($com_data['count'] == $lotto['comments']) {

                            $field['id_lotto'] = $com_data['id_lotto'];
                            $field['id_users'] = $com_data['id_users'];
                            $field['count_content'] = $com_data['count'];
                            $field['nickname'] = $com_data['nickname'];
                            $field['ticket'] = 1;

                            $com_data['round']++;

                            $this->model->updateComment($com_data_id['id'], $com_data);

                            $player_id = $this->model->addPlayer($field);
                            if ($player_id) {
                                $this->model->countIncrement($field['id_lotto'], 1);
                            }

                        }

                    } else {

                        foreach ($user_coms as $user_com) {

                            if ($user_com['count'] < $lotto['comments']) {

                                $user_com['count']++;

                                $this->model->updateComment($user_com['id'], $user_com);
                            }

                            if ($user_com['count'] == $lotto['comments']) {

                                $field['id_lotto'] = $user_com['id_lotto'];
                                $field['id_users'] = $user_com['id_users'];
                                $field['comments'] = $user_com['count'];
                                $field['nickname'] = $user_com['nickname'];
                                $field['ticket'] = 1;

                                $user_com['round']++;

                                $this->model->updateComment($user_com['id'], $user_com);

                                $player_id = $this->model->addPlayer($field);
                                if ($player_id) {
                                    $this->model->countIncrement($field['id_lotto'], 1);
                                }

                            }
                        }
                    }

                }

                if ($players && ($lotto['more_chances'] == 1)) {

                    foreach ($players as $player) {

                        $user_coms = $this->model->filterEqual('id_lotto', $lotto['id'])->filterEqual('id_users', $user['id'])->getComments();

                        if (!$user_coms) {

                            $com_data['id_lotto'] = $lotto['id'];
                            $com_data['id_users'] = $user['id'];
                            $com_data['count'] = 1;
                            $com_data['nickname'] = $user['nickname'];
                            $com_data['round'] = 1;
                            $com_data_id = $this->model->addComment($com_data);

                            if ($com_data['count'] == $lotto['comments']) {

                                $field['id_lotto'] = $com_data['id_lotto'];
                                $field['id_users'] = $com_data['id_users'];
                                $field['count_content'] = $com_data['count'];
                                $field['nickname'] = $com_data['nickname'];
                                $field['ticket'] = $player['ticket'] + 1;

                                $com_data['round']++;

                                $this->model->updateComment($com_data_id['id'], $com_data);

                                $player_id = $this->model->updatePlayer($player['id'], $field);
                                if ($player_id) {
                                    $this->model->countIncrement($field['id_lotto'], 1);
                                }

                            }

                        } else {

                            foreach ($user_coms as $user_com) {

                                $count = $lotto['comments'] * $user_com['round'];

                                if ($user_com['count'] <= ($count - 1)) {

                                    $user_com['count']++;

                                    $this->model->updateComment($user_com['id'], $user_com);
                                }

                                if ($user_com['count'] > ($count - 1)) {

                                    $field['id_lotto'] = $user_com['id_lotto'];
                                    $field['id_users'] = $user_com['id_users'];
                                    $field['comments'] = $user_com['count'];
                                    $field['nickname'] = $user_com['nickname'];
                                    $field['ticket'] = $player['ticket'] + 1;
                                    $user_com['round']++;

                                    $this->model->updateComment($user_com['id'], $user_com);

                                    $player_id = $this->model->updatePlayer($player['id'], $field);
                                    if ($player_id) {
                                        $this->model->countIncrement($field['id_lotto'], 1);
                                    }

                                }
                            }
                        }
                    }


                }

            }

        }

        return $data;

    }

}
