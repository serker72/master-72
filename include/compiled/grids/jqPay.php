<?php

class jqPay extends jqGrid
{
    protected function init()
    {
        #The complex query
        $this->query = "
            SELECT {fields}
            FROM (
                SELECT
                    p.id,
                    p.date_start,
                    p.user_id,
                    u.username, 
                    u.realname,
                    p.cost
                    
                FROM pay p
                    LEFT JOIN user u ON (p.user_id = u.id)
                
            ) AS a
            WHERE {where}
        ";
        
        #Make all columns editable by default
        $this->cols_default = array('editable' => true);

        #Set columns
        $this->cols = array(
            'id' => array('label' => 'ID',
                'width' => 60,
                'hidden' => true,
                'align' => 'center',
                'editable' => false, //id is non-editable
            ),

            'date_start' => array('label' => 'Дата',
                'width' => 150,
                'formatter' => 'date',
                'formatoptions' => array('newformat' => 'd.m.Y'),
                'editoptions' => array(
                    'size' => 10,
                    'maxlengh' => 10,
                    'dataInit' => new jqGrid_Data_Raw("function(element) {
                        $(element).datepicker({
                            showOn: 'button',
                            dateFormat: 'dd.mm.yy',
                            //setDate: null,
                        }); 
                    }"),
                ),
                'searchoptions' => array(
                    'size' => 10,
                    'dataInit' => $this->initDatepicker(array(
                        'showOn' => 'button',
                        'dateFormat' => 'yy-mm-dd',
                        'onSelect' => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                     )),
                ),
                'search_op' => 'equal',                
            ),

            'realname' => array('label' => 'ФИО получателя',
                'width' => 300,
                'editable' => false, //id is non-editable
            ),

            'user_id' => array('label' => 'ФИО получателя',
                'hidden' => true,
                'edittype' => 'select',
                'editoptions' => array('dataUrl' => WEB_ROOT . 'ajax/admin.php?action=user_list'),
                'editrules' => array('edithidden' => true, 'required' => true),
            ),

            'cost' => array('label' => 'Сумма',
                'width' => 100,
                'align' => 'right',
                'formatter' => 'number',
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
    
    protected function opEdit($id, $data)
    {
        $date = new DateTime($data['date_start']);
        $data['date_end'] = $date->format('Y-m-d');
        $data['date_start'] = $date->format('Y-m-d');
        $data['admin_id'] = $_SESSION['user_id'];
        
        #Save other vars to items table
        $this->DB->update('pay', $data, array('id' => $id));
    }
    
    protected function opAdd($data)
    {
        $date = new DateTime($data['date_start']);
        $data['date_end'] = $date->format('Y-m-d');
        $data['date_start'] = $date->format('Y-m-d');
        $data['admin_id'] = $_SESSION['user_id'];
        
        #Save other vars to items table
        $this->DB->insert('pay', $data);
    }
    
    protected function opDel($id)
    {
        # Delete record from table
        $this->DB->delete('pay', array('id' => $id));
    }
    
    protected function searchOpDateRange($c, $val)
    {
        //--------------
        // Date range
        //--------------

        if(strpos($val, ' - ') !== false)
        {
            list($start, $end) = explode(' - ', $val, 2);

            $start = strtotime(trim($start));
            $end = strtotime(trim($end));

            if(!$start or !$end)
            {
                throw new jqGrid_Exception('Invalid date format');
            }

            #Stap dates if start is bigger than end
            if($start > $end)
            {
                list($start, $end) = array($end, $start);
            }

            $start = date('Y-m-d', $start);
            $end = date('Y-m-d', $end);

            return $c['db'] . " BETWEEN '$start' AND '$end'";
        }

        //------------
        // Single date
        //------------

        $val = strtotime(trim($val));

        if(!$val)
        {
            throw new jqGrid_Exception('Invalid date format');
        }

        $val = date('Y-m-d', $val);

        return "DATE({$c['db']}) = '$val'";
    }

    protected function initDatepicker($options = null)
    {
        $options = is_array($options) ? $options : array();

        return new jqGrid_Data_Raw('function(el){$(el).datepicker(' . jqGrid_Utils::jsonEncode($options) . ');}');
    }

    protected function initDateRangePicker($options = null)
    {
        $options = is_array($options) ? $options : array();

        return new jqGrid_Data_Raw('function(el){$(el).daterangepicker(' . jqGrid_Utils::jsonEncode($options) . ');}');
    }    
}