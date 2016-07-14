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
                'editoptions' => array('dataUrl' => '/ajax/admin.php?action=city_gos'),
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