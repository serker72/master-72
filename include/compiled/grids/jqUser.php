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
                'width' => 50,
                'align' => 'right',
                'editable' => false, //id is non-editable
            ),

            'username' => array('label' => 'Логин',
                'width' => 100,
                'editrules' => array('required' => true),
            ),

            'realname' => array('label' => 'ФИО',
                'width' => 290,
                'editrules' => array('required' => true),
            ),
            
            'password' => array('label' => 'Пароль',
                'width' => 150,
                'hidden' => true,
                //'editable' => true,
                'editrules' => array('edithidden' => true, 'required' => true),
            ),

            'rang' => array('label' => 'Роль',
                'width' => 140,
                'edittype' => 'select',
                'editoptions' => array('value' => "manager:Менеджер;master:Мастер;operator:Оператор;admin:Администратор"),
                'editrules' => array('required' => true),
                'stype' => 'select',
                'searchoptions' => array('value' => "manager:Менеджер;master:Мастер;operator:Оператор;admin:Администратор"),
            ),
            
            'phone' => array('label' => 'Телефон',
                'width' => 130,
                //'align' => 'center',
                'editrules' => array('required' => true),
            ),

            'sms' => array('label' => 'sms',
                'hidden' => true,
                'editrules' => array('edithidden' => true),
                'edittype' => 'checkbox',
                'editoptions' => array('value' => "1:0"),
            ),

            'dogovor' => array('label' => 'Договор',
                'hidden' => true,
                'editrules' => array('edithidden' => true),
            ),

            'address' => array('label' => 'Адресс',
                'hidden' => true,
                'editrules' => array('edithidden' => true, 'required' => true),
            ),

            'company' => array('label' => 'Компания',
                'hidden' => true,
                'editrules' => array('edithidden' => true),
            ),
            
            'stavka' => array('label' => 'Ставка',
                'width' => 60,
                'formatter' => 'numeric',
                'align' => 'right',
                'editrules' => array('required' => true),
                'formatter' => 'number',
            ),

            'add_order_form' => array('label' => 'Вид формы',
                'width' => 110,
                'edittype' => 'select',
                'editoptions' => array('value' => "1:Полная форма;2:Пошаговая форма"),
                'hidden' => true,
                'editrules' => array('edithidden' => true),
            ),
        );
        
        //
        $this->options = array(
            'rowNum' => 15,
            'height' => 350,
        );

        #Set nav
        $this->nav = array(
            'view' => true, 
            'add' => true, 
            'edit' => true, 
            'del' => true,
            'excel' => true,
            'exceltitle' => 'Экспортировать в Excel',
            
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
    
    /*protected function operData($data)
    {
        $data['password'] = $data['password'] ? md5($data['password']) : null;
        
        //Server side error checking
        if($data['password'] == NULL)
        {
            throw new jqGridException('Не указан пароль пользователя !'); //This message goes directly to server response
        }        

        return $data;
    }*/
    
    protected function opAdd($data)
    {
        $data['password'] = $data['password'] ? md5($data['password'].'@4!@#$%@') : null;
        
        //Server side error checking
        if($data['password'] == NULL)
        {
            throw new jqGridException('Не указан пароль пользователя !'); //This message goes directly to server response
        }
        
        #Save other vars to items table
        $response = $this->DB->insert('user', $data);

        return $response;        
    }
    
    protected function opEdit($id, $data)
    {
        $data['password'] = $data['password'] ? md5($data['password'].'@4!@#$%@') : null;
        
        //Server side error checking
        if($data['password'] == NULL)
        {
            throw new jqGridException('Не указан пароль пользователя !'); //This message goes directly to server response
        }
        
        #Save other vars to items table
        $response = $this->DB->update('user', $data, array('id' => $id));
        //$response = parent::opEdit($id, $data); // exec orginial oper
        //cache::drop($id);                       // after oper
        //$response['cache_dropped'] = 1;         // modify original response

        return $response;        
    }
    
}