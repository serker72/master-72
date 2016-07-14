<?php

class jqCity extends jqGrid
{
    protected function init()
    {
        #Set database table
        $this->table = 'city';
        
        $this->where[] = "parent_id = 0";

        #Make all columns editable by default
        $this->cols_default = array('editable' => true);

        #Set columns
        $this->cols = array(
            'id' => array('label' => 'ID',
                'width' => 60,
                'align' => 'center',
                'editable' => false, //id is non-editable
            ),

            'name' => array('label' => 'Название города',
                'width' => 300,
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
}