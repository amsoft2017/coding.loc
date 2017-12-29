<?php

class actionLotteryLottosAjax extends cmsAction
{

    public function run()
    {

        if (!$this->request->isAjax()) {
            cmsCore::error404();
        }

        $grid = $this->loadDataGrid('lottos');

        $model = cmsCore::getModel($this->name);

        $model->setPerPage(admin::perpage);

        $filter = array();
        $filter_str = $this->request->get('filter', '');
        $filter_str = cmsUser::getUPSActual('lottery.lottos_list', $filter_str);

        if ($filter_str) {
            parse_str($filter_str, $filter);
            $model->applyGridFilter($grid, $filter);
        }

        $total = $model->getLottosCount();
        $perpage = isset($filter['perpage']) ? $filter['perpage'] : admin::perpage;
        $pages = ceil($total / $perpage);

        $lottos = $model->getLottos();

        $i = 0;

        foreach ($lottos as $lotto) {

            if ($lotto['status'] == 0) {
                $lotto['status'] = LANG_LOTTERY_STATUS_0;
            }
            if ($lotto['status'] == 1) {
                $lotto['status'] = LANG_LOTTERY_STATUS_1;
            }


            $lot[$i] = $lotto;

            $i++;


        }

        cmsTemplate::getInstance()->renderGridRowsJSON($grid, $lot, $total, $pages);

        $this->halt();

    }

}
