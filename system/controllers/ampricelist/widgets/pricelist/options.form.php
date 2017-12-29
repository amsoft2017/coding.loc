<?php

	class formWidgetAmpricelistPricelistOptions extends cmsForm {

		public function init() {
			return array(
				array(
					'type' => 'fieldset',
					'title' => LANG_OPTIONS, 
					'childs' => array(
						new fieldText('options:text', array(
							'title' => LANG_WD_PRICELIST_PRODUCTS_OR_SERVICES,
							'hint' => LANG_WD_PRICELIST_PRODUCTS_OR_SERVICES_HINT,
                            'rules' => array(
                                array('required')
                            )
						)),
                        new fieldString('options:email', array(
                            'title' => LANG_WD_PRICELIST_EMAIL_TO,
                            'rules' => array(

                            )
                        )),
                        new fieldCheckbox('options:email_reply_to', array(
                            'title' => LANG_WD_PRICELIST_EMAIL_REPLY_TO,
                            'default' => true,
                            'rules' => array(

                            )
                        )),
                        new fieldCheckbox('options:tel_reply_to', array(
                            'title' => LANG_WD_PRICELIST_TEL_REPLY_TO,
                            'default' => true,
                            'rules' => array(

                            )
                        )),
                        new fieldUrl('options:page_agreement', array(
                            'title' => "Ссылка на страницу с соглашением об обработке персональных данных",
                            'rules' => array(

                            )
                        )),
                        new fieldColor('options:color_thead', array(
                            'title' => LANG_WD_PRICELIST_COLOR_THEAD,
                            'default' => '#ff9614',
                            'rules' => array(

                            )
                        )),
                        new fieldColor('options:color_blok', array(
                            'title' => LANG_WD_PRICELIST_COLOR_BLOK,
                            'default' => '#bdc3c7',
                            'rules' => array(

                            )
                        )),
					)
				),
			);
		}
		
	}
