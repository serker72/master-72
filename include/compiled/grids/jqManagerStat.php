<?php

class jqManagerStat extends jqGrid
{
    protected function init()
    {
        #Set database table
        $this->table = 'user_stat';

        //$this->where[] = "rang = 'manager'";
        
        #Make all columns editable by default
        //$this->cols_default = array('editable' => false);

        #Set columns
        $this->cols = array(
            'id' => array('label' => 'ID',
                'width' => 50,
                'align' => 'right',
            ),

            'username' => array('label' => 'Логин',
                'width' => 100,
            ),

            'realname' => array('label' => 'ФИО',
                'width' => 300,
            ),
            
            'stavka' => array('label' => 'Ставка',
                'width' => 60,
            ),
            
            'zp_calc_sum' => array('label' => 'Сумма счетов',
                'width' => 80,
            ),
            
            'zp_calc' => array('label' => 'Рассчитано',
                'width' => 80,
            ),
            
            'zp_pay' => array('label' => 'Выплачено',
                'width' => 80,
            ),
            
            'zp' => array('label' => 'З/П',
                'width' => 80,
            ),
        );
        
        //
        $this->options = array(
            'height' => 500,
        );

        #Set nav
        $this->nav = array(
            'view' => true, 
            'add' => false, 
            'edit' => false, 
            'del' => false,
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
}