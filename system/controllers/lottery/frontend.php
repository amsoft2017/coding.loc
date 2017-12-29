<?php

class lottery extends cmsFrontend
{

    protected $useOptions = true;

    /** Возвращает массив с призами
     * @param string $distribution строка из лотереи с названиями призов
     * @return array
     */
    static public function arrayPrizes($distribution)
    {
       return preg_split("/\\r\\n?|\\n/", $distribution);
    }

    /**
     *
     */
    static public function runWinner()
    {
        $model = cmsCore::getModel('lottery');
        $lottos = $model->getListLotteriesForDrawing();
        if(empty($lottos)) return;

        while($lotto = array_shift($lottos))
        {
            $tickets = $model->getTicketNumbers($lotto['id']); // Получаем билеты
            //Проверяем условия по билетам, если не проходит переходим на следующую иттерацию
            if(empty(self::conditionsOnTickets($tickets, $model, $lotto))) continue;
            //выбираем победителей и формируем  массив победители => призы
            $winners_prizes = self::getArrayWinnersPrizes($lotto, $tickets);
            if(empty($winners_prizes)) continue; // TODO Протестировать необходимость проверки
            //Записываем выигрышные билеты и возвращаем оставшиеся билеты
            $unresolved = self::setWinners($winners_prizes, $tickets , $lotto['name']);
            //Устанавливаем статус Лотерее РОЗЫГРЫШ ЗАВЕРШЕН
            $model->setStatusCompleted($lotto);
            if(empty($unresolved)) continue;
            //Проверям включены ли утешительные призы, если нет переходим на следующую иттерацию
            if(empty(self::onConsolationPrize($lotto, $unresolved, $model))) continue;
            //Выбираем билеты для утешительных призов и возвращаем массив с номерами id билетов
            $list_prize = self::getArrayConsolationPrize($lotto, $unresolved);
            //Раздаем утешительные призы и возвращаем оставшиеся билеты
            $unresolved = self::setConsolationPrize($unresolved, $list_prize, $lotto['rating'], $lotto['name']);
            //если билеты остались устанавливаем им статус БИЛЕТ НЕ ВЫИГАЛ
            if(!empty($unresolved) && is_array($unresolved))$set = $model->setTicketsStatusNotwin($unresolved);
        }

        return;
    }

    /**Записывает в билеты утешительные призы, добовляет рейтинг
     * меняет статус на УТЕШИТЕЛЬНЫЙ ПРИЗ и отправляет письмо
     * @param $unresolved
     * @param $rating
     * @param array $list_prize
     * @param $lotto_name
     * @return array массив с неразыгранными билетами
     */
    static public function setConsolationPrize($unresolved, $list_prize, $rating, $lotto_name)
    {
        $model = cmsCore::getModel('lottery');
        foreach($list_prize as $value){
            $item = $unresolved[$value];
            $item['status'] = 4;
            $item['prize'] = "$rating очков рейтинга";
            $item['prize_place'] = 0;
            $model->updateTicket($item['id'], $item);
            $model->updateUserRating($item['id_user'], $rating);
            self::sendEmailWinner($lotto_name, $item,'distribution_message');
            unset($unresolved[$item['id']]);
        }
        return $unresolved;
    }

    /** Записывает в выигрышные билеты номер места, приз
     * меняет статус на БИЛЕТ ВЫИГРАЛ и отправляет имейл
     * @param array $winners_prizes  массив с победителями и призами
     * @param  array $tickets массив с билетами лотереи
     * @param string $lotto_name название лотереи для отправки в письме
     * @return array массив с неразыгранными билетами
     */
    static public function setWinners($winners_prizes, $tickets, $lotto_name)
    {
        $model = cmsCore::getModel('lottery');
        $prize_place = 0;
        foreach($winners_prizes as $winner => $prize){
            $ticket = $tickets[$winner];
            $ticket['status'] = 1;
            $ticket['prize'] = $prize;
            $ticket['prize_place'] = ++$prize_place;
            $id = $ticket['id'];
            $model->updateTicket($ticket['id'], $ticket);
            self::sendEmailWinner($lotto_name, $ticket, 'win_message');
            unset($tickets[$id]);
        }
        return $tickets;
    }

    /** Отправляет письмо победителю
     * @param string $lotto_name название лотереи
     * @param array $ticket данные выигрышного билета
     * @param string $name_latter Имя шаблона письма
     * @return boolean
     */
    static public function sendEmailWinner($lotto_name, $ticket, $name_latter = 'win_message')
    {
        $model = cmsCore::getModel('lottery');
        $winner = $model->getNicknameAndEmailWinner($ticket['id_user']);
        $messenger = cmsCore::getController('messages');
        $to = array('email' => $winner['email'], 'name' => $winner['nickname']);
        $letter = array('name' => $name_latter);

        return $messenger->sendEmail($to, $letter, array(
                    'prize' => $ticket['prize'],
                    'nickname' => $winner['nickname'],
                    'lotto' => $lotto_name,
                    'place' => $ticket['prize_place'],
                ));
    }

    /**Проверяет условия по билетам для проведения розыгрыша:
     * если билетов нету
     * то устанавливает статус "розыгрыш не состоялся" и возвращает false
     * если кол-во билетов < мин.необходимых
     * то устанавливает статус "розыгрыш не состоялся", анулирует билеты и возвращает false
     * @param array $tickets
     * @param object $model
     * @param array $lotto
     * @return boolean
     */
    private static function conditionsOnTickets($tickets, $model, $lotto){
        if(empty($tickets)){
            $model->setStatusFailed($lotto);
            return false;
        }
        if(count($tickets) < $lotto['min_count_tickets']){
            $model->setStatusFailed($lotto);
            $model->setTicketsStatusCancelled($tickets);
            return false;
        }
        return true;
    }

    /**Выбираем победителей и Формируем массив ['id победителя' => 'приз']
     * @param $lotto
     * @param $tickets
     * @return array|boolean
     */
    private static function getArrayWinnersPrizes($lotto, $tickets){
        $prizes = self::arrayPrizes($lotto['distribution']);
        $winners = array_rand($tickets, count($prizes));
        if(empty($winners)) return false;
        if(!is_array($winners)) $winners_array[] = $winners;
        else $winners_array = $winners;
        $winners_prizes = array_combine($winners_array, $prizes);

        return $winners_prizes;
    }

    /**Проверяет включены ли утешительные призы
     * Если не включены устанавливаем оставшимся билетам статус БИЛЕТ НЕ ВЫИГРАЛ
     * @param array $lotto
     * @param array $unresolved
     * @param object $model
     * @return boolean
     */
    private static function onConsolationPrize($lotto, $unresolved, $model){
        if($lotto['is_consolation_prize'] == 0){
            if(!empty($unresolved) && is_array($unresolved)) $model->setTicketsStatusNotwin($unresolved);
            return false;
        }
        return true;
    }

    /**Выбираем билеты для утешительных призов и возвращаем массив с номерами id билетов
     * @param $lotto
     * @param $unresolved
     * @return array|mixed $list_prize
     */
    private static function getArrayConsolationPrize($lotto, $unresolved){
        if(!($lotto['number_tickets'] == 0) && (count($unresolved) > $lotto['number_tickets'])){
            $prize = array_rand($unresolved, $lotto['number_tickets']);
            if(!is_array($prize)) $list_prize[] = $prize;
            else $list_prize = $prize;
        }else if(($lotto['number_tickets'] == 0) || (count($unresolved) < $lotto['number_tickets'])){
            $list_prize = array_rand($unresolved, count($unresolved));
        }else if($lotto['number_tickets'] == count($unresolved)){
            $list_prize = array_keys($unresolved);
        }

        /** @var array $list_prize */
        return $list_prize;
    }

    /**
     * @param object $form
     */
    public function setFieldsSeo($form)
    {
        $fieldset_id = $form->addFieldset(LANG_LOTTERY_LOTTO_SEO);

        $form->addField($fieldset_id, new fieldString('seo_title', array(
            'title' => LANG_LOTTERY_LOTTO_SEO_TITLE,
            'options'=>array(
                'max_length'=> 256,
                'show_symbol_count'=>true
            ),
            'defaul' => '',
        )));
        $form->addField($fieldset_id, new fieldString('seo_keys', array(
            'title' => LANG_LOTTERY_LOTTO_SEO_KEYS,
            'hint' => LANG_LOTTERY_LOTTO_SEO_KEYS_HINT,
            'options'=>array(
                'max_length'=> 256,
                'show_symbol_count'=>true
            ),
            'defaul' => '',
        )));

        $form->addField($fieldset_id, new fieldText('seo_desc', array(
            'title' => LANG_LOTTERY_LOTTO_SEO_DESC,
            'hint' => LANG_LOTTERY_LOTTO_SEO_DESC_HINT,
            'options'=>array(
                'max_length'=> 256,
                'show_symbol_count'=>true
            ),
            'defaul' => '',
        )));
    }


}


