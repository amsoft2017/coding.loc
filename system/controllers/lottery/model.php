<?php

class modelLottery extends cmsModel
{

    /*{comgen-model-methods}*/

    /* Players */

    public function addPlayer($player)
    {
        return $this->insert('lottery_players', $player);
    }

    public function updatePlayer($id, $player)
    {
        return $this->update('lottery_players', $id, $player);
    }

    public function deletePlayer($id)
    {
        return $this->delete('lottery_players', $id);
    }

    public function getPlayer($id)
    {
        return $this->getItemById('lottery_players', $id);
    }

    public function getPlayersCount()
    {
        return $this->getCount('lottery_players');
    }

    public function getPlayers()
    {
        return $this->get('lottery_players');
    }

    public function reorderPlayers($ids_list)
    {
        $this->reorderByList('lottery_players', $ids_list);
        return true;
    }

    /** ВОЗВРАЩАЕТ УЧАСТНИКОВ РОЗЫГРЫША
     * @param $id_lotto
     *
     * @return array|boolean
     */
    public function getParticipants($id_lotto)
    {
        $tickets = $this->joinUser('id_user')->getTicketNumbers($id_lotto);
        if(!(empty($tickets))) return $this->generatedPlayers($tickets);
        return false;
    }

    public function generatedPlayers($tickets){
        $players = [];
        while($ticket = array_shift($tickets)){
            if(empty($players)){
                $players[$ticket['id_user']] = [
                    'id_user' => $ticket['id_user'],
                    'count_tickets' => 1,
                    'user_nickname' => $ticket['user_nickname'],
                    'user_avatar' => $ticket['user_avatar']
                ];
                continue;
            }
            if(!empty($players[$ticket['id_user']])){
                $players[$ticket['id_user']]['count_tickets']++;
                continue;
            }else{
                $players[$ticket['id_user']] = [
                    'id_user' => $ticket['id_user'],
                    'count_tickets' => 1,
                    'user_nickname' => $ticket['user_nickname'],
                    'user_avatar' => $ticket['user_avatar']
                ];
                continue;
            }
        }
        return $players;
    }

    /* Tickets */

    public function addTicket($ticket)
    {
        return $this->insert('lottery_tickets', $ticket);
    }

    public function updateTicket($id, $ticket)
    {
        return $this->update('lottery_tickets', $id, $ticket);
    }

    public function deleteTicket($id)
    {
        return $this->delete('lottery_tickets', $id);
    }

    public function getTicket($id)
    {
        return $this->getItemById('lottery_tickets', $id);
    }

    /** Устанавливает статус "АНУЛИРОВАН" билетам
     * @param array $tickets массив билетов
     * @return bool в случае успеха возвращает true
     */
    public function setTicketsStatusCancelled($tickets)
    {
        foreach($tickets as $ticket){
            $ticket['status'] = 3;
            $ticket['prize_place'] = 0;
            $ticket['prize'] = '';
            $this->updateTicket($ticket['id'], $ticket);
        }
        return true;
    }
    /** Устанавливает статус "НЕ ВЫИГРАЛ" билетам
     * @param array $tickets массив билетов
     * @return bool в случае успеха возвращает true
     */
    public function setTicketsStatusNotwin($tickets)
    {
        foreach($tickets as $ticket){
            $ticket['status'] = 2;
            $ticket['prize_place'] = 0;
            $ticket['prize'] = '';
            $this->updateTicket($ticket['id'], $ticket);
        }
        return true;
    }

    /** Возвращает количество билетов в лотерее
     * @return int
     */
    public function getCountTicketLotto($id_lotto)
    {
        return $this->getCount('lottery_tickets', $id_lotto);
    }

    public function getTicketsByIdLotto($id_lotto)
    {
        return $this->filterEqual('id_lotto', $id_lotto)->get('lottery_tickets');
    }

    /** Возвращает количество билетов у пользователя
     * @param $id_user
     * @param $id_lotto
     * @return int
     */
    public function getCountTicketsUser($id_user, $id_lotto)
    {
        return $this->filterEqual('id_lotto', $id_lotto)->
            filterEqual('id_user', $id_user)->
            getCount('lottery_tickets', 'id_user');
    }

    /** Возвращает минимальное количество билетов для проведения розыгрыша
     * @param $id_lotto
     * @return string
     */
    public function minCountTickets($id_lotto)
    {
        return $this->getField('lottery_lottos', $id_lotto,'min_count_tickets');
    }

    /** Возвращает максимальное количество билетов в лотереи на одного пользователя
     * @param $id_lotto
     * @return string
     */
    public function maxCountTicketsUser($id_lotto)
    {
        return $this->getField('lottery_lottos', $id_lotto, 'max_tickets_user');
    }

    /** Провкеряет можно ли добавлять билеты по условию
     * (кол-во билетов пользователя < максимально допустимого в лотереи)
     * @param $id_user
     * @param $id_lotto
     * @return bool
     */
    static public function youCanAddTicket($id_user, $id_lotto)
    {
        $model = cmsCore::getModel('lottery');
        $max = $model->maxCountTicketsUser($id_lotto);
        $count = $model->getCountTicketsUser($id_user, $id_lotto);
        if($count < $max) return true;
        return false;
    }


    /** Получаем массив с билетами лотереи
     * @param int $id_lotto
     * @return array
     */
    public function getTicketNumbers($id_lotto)
    {
        $tickets = $this->filterEqual('id_lotto', $id_lotto)->
            get('lottery_tickets');
        return $tickets;
    }

    /** Добавляет билет пользователю
     * @param string $id_lotto
     * @param string  $id_user
     * @return bool
     */
    public function addTicketToUser($id_lotto, $id_user)
    {
        $is_exists = $this->isLotteryAndUser($id_lotto, $id_user);
        if($is_exists === false) return false;
        $ticket_information = [
            'id_user' => $id_user,
            'id_lotto' => $id_lotto,
            'what_action' => 0,
            'status' => 0
        ];
        return $this->addTicket($ticket_information);
    }

    /** Возвращает Имя и имейл победителя
     * @param int $id Принимат id пользователя выигрышного билета
     * @return array mixed
     */
    public function getNicknameAndEmailWinner($id)
    {
        $user_model = cmsCore::getModel('users');
        $winner['nickname'] = $user_model->getField('users', $id, 'nickname');
        $winner['email'] = $user_model->getField('users', $id, 'email');
        return $winner;
    }

    /** Проверяет на существование лотереи и пользователя
     * @param int $lotto_id id лотереи
     * @param int $user_id id пользователя
     * @return bool
     */
    public function isLotteryAndUser($lotto_id, $user_id)
    {
        $lotto = $this->getLotto($lotto_id);
        $user_model = cmsCore::getModel('users');
        $user = $user_model->getUser($user_id);
        if(!($lotto === false) && !($user === false)) return true;
        return false;
    }




    /* Lottos */

    public function addLotto($lotto)
    {
        return $this->insert('lottery_lottos', $lotto);
    }

    public function updateLotto($id, $lotto)
    {
        return $this->update('lottery_lottos', $id, $lotto);
    }

    public function deleteLotto($id)
    {
        return $this->delete('lottery_lottos', $id);
    }

    public function getLotto($id)
    {
        $lotto = $this->getItemById('lottery_lottos', $id);

        return $lotto;
    }

    public function getLottosCount()
    {
        return $this->getCount('lottery_lottos');
    }

    public function getLottos()
    {
        return $this->get('lottery_lottos');
    }

    public function reorderLottos($ids_list)
    {
        $this->reorderByList('lottery_lottos', $ids_list);
        return true;
    }

    /** Устанавливает лотерее статус РОЗЫГРЫШ НЕ СОСТОЯЛСЯ
     * @param $lotto
     * @return mixed
     */
    public function setStatusFailed($lotto)
    {
        $lotto['status'] = 2;
        return $this->updateLotto($lotto['id'], $lotto);
    }

    /** Устанавливает лотерее статус РОЗЫГРЫШ ЗАВЕРШЕН
     * @param $lotto
     * @return mixed
     */
    public function setStatusCompleted($lotto)
    {
        $lotto['status'] = 1;
        return $this->updateLotto($lotto['id'], $lotto);
    }

    /**Возвращает массив лотерей для розыгрыша
     * @return array
     */
    public function getListLotteriesForDrawing(){
       return $this->filter('i.start_date <= NOW()')->
        filterEqual('status', 0)->
        getLottos();

    }



    /*Comments_count*/

    public function addComment($player)
    {
        return $this->insert('lottery_count_comments', $player);
    }

    public function updateComment($id, $player)
    {
        return $this->update('lottery_count_comments', $id, $player);
    }

    public function deleteComment($id)
    {
        return $this->delete('lottery_count_comments', $id);
    }

    public function getComment($id)
    {
        return $this->getItemById('lottery_count_comments', $id);
    }


    public function getCommentsCount()
    {
        return $this->getCount('lottery_count_comments');
    }

    public function getComments()
    {
        return $this->get('lottery_count_comments');
    }


    public function countIncrement($lotto_id, $step = 1)
    {

        return $this->filterEqual('id', $lotto_id)->increment('lottery_lottos', 'count_player', $step);
    }

    /*Content_count*/

    public function addContent($player)
    {
        return $this->insert('lottery_count_content', $player);
    }

    public function updateContent($id, $player)
    {
        return $this->update('lottery_count_content', $id, $player);
    }

    public function deleteContent($id)
    {
        return $this->delete('lottery_count_content', $id);
    }

    public function getContent($id)
    {
        return $this->getItemById('lottery_count_content', $id);
    }


    public function getContentsCount()
    {
        return $this->getCount('lottery_count_content');
    }

    public function getContents()
    {
        return $this->get('lottery_count_content');
    }

    public function updateUserRating($user_id, $score)
    {

        $this->filterEqual('id', $user_id);

        if ($score > 0) {
            $this->increment('{users}', 'rating', abs($score));
        }
        if ($score < 0) {
            $this->decrement('{users}', 'rating', abs($score));
        }

        cmsCache::getInstance()->clean("users.list");
        cmsCache::getInstance()->clean("users.user.{$user_id}");

        return true;

    }


}
