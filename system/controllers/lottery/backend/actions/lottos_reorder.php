<?php

class actionLotteryLottosReorder extends cmsAction
{

    public function run()
    {

        $items = $this->request->get('items');

        if (!$items) {
            cmsCore::error404();
        }

        $model = cmsCore::getModel($this->name);

        $model->reorderLottos($items);

        $this->redirectBack();

    }

}
