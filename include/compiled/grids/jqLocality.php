<?php

class jqLocality extends jqGrid
{
    protected function init()
    {
        #The complex query
        $this->query = "
            SELECT {fields}
            FROM (
                SELECT
                    c.id, 
                    c1.name as city_name,
                    c.name,
                    c.parent_id
                    
                FROM city c
                    LEFT JOIN city c1 ON (c.parent_id = c1.id)
                WHERE
                    c.parent_id > 0
            ) AS a
            WHERE {where}
        ";
        
        #Make all columns editable by default
        $this->cols_default = array('editable' => true);

        #Set columns
        $this->cols = array(
            'id' => array('label' => 'ID',
                'width' => 60,
                'align' => 'center',
                'editable' => false, //id is non-editable
            ),

            'parent_id' => array('label' => 'Название города',
                'hidden' => true,
                'edittype' => 'select',
                'editoptions' => array('dataUrl' => WEB_ROOT . 'ajax/admin.php?action=city_gos'),
                'editrules' => array('edithidden' => true, 'required' => true),
            ),

            'city_name' => array('label' => 'Название города',
                'width' => 300,
                'editable' => false,
            ),

            'name' => array('label' => 'Название населенного пункта',
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
    
    protected function opEdit($id, $upd)
    {
        $st = $this->DB->query('SELECT * FROM street WHERE city_id = '.$id);
        #Save other vars to items table
        $this->DB->update('city', $upd, array('id' => $id));
    }
    
    protected function opDel($id)
    {
        $st = $this->DB->query('SELECT * FROM city WHERE parent_id = '.$id);
        $st_cnt = $this->DB->rowCount($st);
        
        if($st_cnt > 0)
        {
            throw new jqGridException('Невозможно удалить населенный пункт: имеются связанные пункты !'); //This message goes directly to server response
        } 
        
        $st = $this->DB->query('SELECT * FROM street WHERE city_id = '.$id);
        $st_cnt = $this->DB->rowCount($st);
        
        if($st_cnt > 0)
        {
            throw new jqGridException('Невозможно удалить населенный пункт: имеются связанные улицы !'); //This message goes directly to server response
        } 
        
        # Delete records
        $this->DB->delete('city', array('id' => $id));
    }
}