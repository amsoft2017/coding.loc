<?php

class onLotteryAfterAddForumTopic extends cmsAction
{

    public function run($data)
    {

        $lottos = $this->model->filter('i.start_date >= NOW()')->filterEqual('is_forum', 1)->filterEqual('winner', null)->getLottos();

        if ($lottos) {

            foreach ($lottos as $lotto) {

                $user_model = cmsCore::getModel('users');
                $user = $user_model->getUser($data['user_id']);

                $players = $this->model->filterEqual('id_lotto', $lotto['id'])->filterEqual('id_users', $user['id'])->getPlayers();

                if (!$players) {

                    $user_coms = $this->model->filterEqual('id_lotto', $lotto['id'])->filterEqual('id_users', $user['id'])->getTopics();

                    if (!$user_coms) {

                        $com_data['id_lotto'] = $lotto['id'];
                        $com_data['id_users'] = $user['id'];
                        $com_data['count'] = 1;
                        $com_data['nickname'] = $user['nickname'];
                        $com_data['round'] = 1;
                        $this->model->addTopic($com_data);

                    } else {

                        foreach ($user_coms as $user_com) {

                            if ($user_com['count'] < $lotto['count_topic']) {

                                $user_com['count']++;

                                $this->model->updateTopic($user_com['id'], $user_com);
                            }

                            if ($user_com['count'] == $lotto['count_topic']) {

                                $field['id_lotto'] = $user_com['id_lotto'];
                                $field['id_users'] = $user_com['id_users'];
                                $field['count_content'] = $user_com['count'];
                                $field['nickname'] = $user_com['nickname'];
                                $field['ticket'] = 1;

                                $user_com['round']++;

                                $this->model->updateTopic($user_com['id'], $user_com);

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

                        $user_coms = $this->model->filterEqual('id_lotto', $lotto['id'])->filterEqual('id_users', $user['id'])->getTopics();

                        if (!$user_coms) {

                            $com_data['id_lotto'] = $lotto['id'];
                            $com_data['id_users'] = $user['id'];
                            $com_data['count'] = 1;
                            $com_data['nickname'] = $user['nickname'];
                            $com_data['round'] = 1;
                            $this->model->addTopic($com_data);

                        } else {

                            foreach ($user_coms as $user_com) {

                                $count = $lotto['count_topic'] * $user_com['round'];

                                if ($user_com['count'] <= ($count - 1)) {

                                    $user_com['count']++;

                                    $this->model->updateTopic($user_com['id'], $user_com);
                                }

                                if ($user_com['count'] > ($count - 1)) {

                                    $field['id_lotto'] = $user_com['id_lotto'];
                                    $field['id_users'] = $user_com['id_users'];
                                    $field['count_topic'] = $user_com['count'];
                                    $field['nickname'] = $user_com['nickname'];
                                    $field['ticket'] = $player['ticket'] + 1;
                                    $user_com['round']++;

                                    $this->model->updateTopic($user_com['id'], $user_com);

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
