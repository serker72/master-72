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
                'width' => 70,
                'align' => 'right',
                'editable' => false, //id is non-editable
            ),

            'name' => array('label' => 'Название города',
                'width' => 300,
                'editrules' => array('required' => true),
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
    
    protected function opDel($id)
    {
        $st = $this->DB->query('SELECT * FROM city WHERE parent_id = '.$id);
        $st_cnt = $this->DB->rowCount($st);
        
        if($st_cnt > 0)
        {
            throw new jqGridException('Невозможно удалить город: имеются связанные населенные пункты !'); //This message goes directly to server response
        } 
        
        $st = $this->DB->query('SELECT * FROM street WHERE city_id = '.$id);
        $st_cnt = $this->DB->rowCount($st);
        
        if($st_cnt > 0)
        {
            throw new jqGridException('Невозможно удалить город: имеются связанные улицы !'); //This message goes directly to server response
        } 
        
        # Delete records
        $this->DB->delete('city', array('id' => $id));
    }
    
}