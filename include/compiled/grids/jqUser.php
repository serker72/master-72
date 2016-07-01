<?php

class jqUser extends jqGrid
{
    protected function init()
    {
        #Set database table
        $this->table = 'user';

        #Make all columns editable by default
        $this->cols_default = array('editable' => true);

        #Set columns
        $this->cols = array(
            'id' => array('label' => 'ID',
                'width' => 30,
                'align' => 'center',
                'editable' => false, //id is non-editable
            ),

            'username' => array('label' => 'Логин',
                'width' => 100,
                'editrules' => array('required' => true),
            ),

            'realname' => array('label' => 'ФИО',
                'width' => 150,
                'editrules' => array('required' => true),
            ),
            
            'password' => array('label' => 'Пароль',
                'width' => 150,
                'hidden' => true,
                'editable' => true,
            ),

            'rang' => array('label' => 'Роль',
                'width' => 80,
                'editrules' => array('email' => true),
            ),
            
            'phone' => array('label' => 'Телефон',
                'width' => 100,
                'align' => 'center',
                'editrules' => array('required' => true),
            ),

            'sms' => array('label' => 'sms',
                'width' => 10,
            ),

            'dogovor' => array('label' => 'Договор',
                'width' => 50,
            ),

            'address' => array('label' => 'Адресс',
                'width' => 90,
                'editrules' => array('required' => true),
            ),

            'company' => array('label' => 'Компания',
                'width' => 90,
            ),
            
            'stavka' => array('label' => 'Ставка',
                'width' => 40,
                'formatter' => 'numeric',
                'align' => 'center',
                'editrules' => array('required' => true),
            ),
        );
        
        //
        $this->options = array(
            'height' => 500,
        );

        #Set nav
        $this->nav = array(
            'view' => true, 
            'add' => true, 
            'edit' => true, 
            'del' => true,
            'excel' => true,
            
            /*'viewtext' => 'См',
            'addtext' => 'Доб',
            'edittext' => 'Изм',
            'deltext' => 'Удал',
            'exceltext' => 'Excel',
            */
            //'prmAdd' => array('width' => 600),
            //'prmEdit' => array('width' => 720),
        );

        #Add filter toolbar
        $this->render_filter_toolbar = true;
    }
}