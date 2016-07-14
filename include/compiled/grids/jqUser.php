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
                'width' => 60,
                'align' => 'center',
                'editable' => false, //id is non-editable
            ),

            'username' => array('label' => 'Логин',
                'width' => 100,
                'editrules' => array('required' => true),
            ),

            'realname' => array('label' => 'ФИО',
                'width' => 200,
                'editrules' => array('required' => true),
            ),
            
            'password' => array('label' => 'Пароль',
                'width' => 150,
                'hidden' => true,
                //'editable' => true,
                'editrules' => array('edithidden' => true, 'required' => true),
            ),

            'rang' => array('label' => 'Роль',
                'width' => 80,
                'edittype' => 'select',
                'editoptions' => array('value' => "manager:Менеджер; master:Мастер; admin:Администратор"),
                'editrules' => array('required' => true),
            ),
            
            'phone' => array('label' => 'Телефон',
                'width' => 120,
                'align' => 'center',
                'editrules' => array('required' => true),
            ),

            'sms' => array('label' => 'sms',
                'hidden' => true,
                //'editable' => true,
                'editrules' => array('edithidden' => true),
                'edittype' => 'checkbox',
                'editoptions' => array('value' => "1:0"),
            ),

            'dogovor' => array('label' => 'Договор',
                'hidden' => true,
                //'editable' => true,
                'editrules' => array('edithidden' => true),
            ),

            'address' => array('label' => 'Адресс',
                'hidden' => true,
                //'editable' => true,
                'editrules' => array('edithidden' => true, 'required' => true),
            ),

            'company' => array('label' => 'Компания',
                'width' => 150,
            ),
            
            'stavka' => array('label' => 'Ставка',
                'width' => 60,
                'formatter' => 'numeric',
                'align' => 'right',
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
            'excel' => false,
            
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
    
    protected function operData($data)
    {
        $data['password'] = $data['password'] ? md5($data['password']) : null;
        
        //Server side error checking
        if($data['password'] == NULL)
        {
            throw new jqGridException('Не указан пароль пользователя !'); //This message goes directly to server response
        }        

        return $data;
    }
}