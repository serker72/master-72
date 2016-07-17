<?php
    global $INI;
    //
    $jq_loader = new jqGridLoader;
    #Set grid directory
    $jq_loader->set("grid_path", dirname(__FILE__) . '/grids/');
    
    //set encoding
    $jq_loader->set('encoding', 'utf-8');

    #Use PDO for database connection
    //$jq_loader->set("db_driver", "Pdo");
    $jq_loader->set("db_driver", "Mysql");

    #Set PDO-specific settings
    //$jq_loader->set("pdo_dsn"  , "Mysql:dbname=".$INI['db']['name'].";host=".$INI['db']['host']);
    //$jq_loader->set("pdo_user" , $INI['db']['user']);
    //$jq_loader->set("pdo_pass" , $INI['db']['pass']);
    
    $jq_loader->set("db_host"  , $INI['db']['host']);
    $jq_loader->set("db_name"  , $INI['db']['name']);
    $jq_loader->set("db_user" , $INI['db']['user']);
    $jq_loader->set("db_pass" , $INI['db']['pass']);
    
    #Turn on debug output
    $jq_loader->set('debug_output', true);
    $jq_loader->autorun();
    //
    
    include template("header_admin"); 
?>
	<!--script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.js"></script-->
	<!--script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.ru.js"></script-->
	<script type="text/javascript" src="/static/js/bootstrap-modal.js"></script>
	<script type="text/javascript" src="/static/js/jquery.comiseo.daterangepicker.js"></script>
	<script type="text/javascript" src="/static/js/moment.min.js"></script>

	<!--link href="/static/js/datepicker/css/datepicker.css" rel="stylesheet"-->
	<link href="/static/css/jquery.comiseo.daterangepicker.css" rel="stylesheet">
	
	<div class="info-block" id="status-bar-add" style="display:block; float: left;">
            <strong>Панель управления</strong>
        </div>
	<div style="float:right; margin-right: 5px;"><a href="/logout.php" class="btn">Выйти</a></div>
	<div style="float:right; margin-right: 5px;"><a href="/operator.php" class="btn">Заказы</a></div>
	<div style="float:right; margin-right: 15px;"><a href="/" class="btn">Главная</a></div>
        
        <!------------------ Tabs ------------------------->
        <div style="clear: both;"></div>
        <div id="tabs" style="width: 98%; margin: 0 auto;">
            <ul>
		<li><a href="#tabs-1">Пользователи</a></li>
		<li><a href="#tabs-2">Города</a></li>
		<li><a href="#tabs-3">Населенные пункты</a></li>
		<li><a href="#tabs-4">Улицы</a></li>
		<li><a href="#tabs-5">Тип работ</a></li>
		<li><a href="#tabs-6">Специализация мастера</a></li>
		<li><a href="#tabs-7">З/П менеджеры</a></li>
		<li><a href="#tabs-8">З/П мастера</a></li>
		<li><a href="#tabs-9">Выплаты</a></li>
		<li><a href="#tabs-10">Настройки</a></li>
            </ul>
            <div id="tabs-1">
                <div>
                    <script>
                        <?php echo $jq_loader->render('jqUser'); ?>
                    </script>
                </div>
            </div>
            <div id="tabs-2">
                <div>
                    <script>
                        <?php echo $jq_loader->render('jqCity'); ?>
                    </script>
                </div>
            </div>
            <div id="tabs-3">
                <div>
                    <script>
                        <?php echo $jq_loader->render('jqLocality'); ?>
                    </script>
                </div>
            </div>
            <div id="tabs-4">
                <div>
                    <form id="street_xls_form" enctype="multipart/form-data" method="post">
                        <input type="file" id="street_xls" name="street_xls" accept="application/excel,application/vnd.ms-excel,application/x-excel,application/x-msexcel">
                        <button onclick="onStreetImport(); return false;" style="">Импорт улиц из файла Excel</button>
                    </form>
                    <label id="street_xls_status"></label>
                </div>
                <div>
                    <script>
                        <?php echo $jq_loader->render('jqStreet'); ?>
                    </script>
                </div>
            </div>
            <div id="tabs-5">
                <div>
                    <script>
                        <?php echo $jq_loader->render('jqWorkType'); ?>
                    </script>
                </div>
            </div>
            <div id="tabs-6">
                <div>
                    <script>
                        <?php echo $jq_loader->render('jqMaster'); ?>
                    </script>
                </div>
            </div>
            <div id="tabs-7">
                <button onclick="onUserStatUpdate(); return false;" style="margin-bottom: 10px;">Пересчитать</button>
                <div>
                    <script>
                        <?php echo $jq_loader->render('jqManagerStat'); ?>
                    </script>
                </div>
            </div>
            <div id="tabs-8">
                <button onclick="onUserStatUpdate(); return false;" style="margin-bottom: 10px;">Пересчитать</button>
                <div>
                    <script>
                        <?php echo $jq_loader->render('jqMasterStat'); ?>
                    </script>
                </div>
            </div>
            <div id="tabs-9">
                <div>
                    <script>
                        <?php echo $jq_loader->render('jqPay'); ?>
                    </script>
                </div>
            </div>
            <div id="tabs-10">
		<div id="sms-api-manager">
			<h4>SMS API</h4>
                        <div id="sms-api-manager-table"></div><br>
			<a href="#mySmsApiOptions" role="button"  style="font-weight:normal;" class="btn" data-toggle="modal">Изменить</a>
		</div>
            </div>
        </div>
        <!------------------------------------------------->
        
	
		<script type="text/javascript">
			function onUserStatUpdate(){
                            $.ajax({
                                    type: "GET",
                                    url: "<?php echo WEB_ROOT; ?>/ajax/admin.php?action=user_stat_update",
                                    success: function(data){ 
                                        //$('#table-master').empty().append($(data)); 
                                        //$('table#table-master tbody').html($(data)); 
                                    }
                            });	
                            
                            $("#jqManagerStat").trigger('reloadGrid');
                            $("#jqMasterStat").trigger('reloadGrid');
                            
                            return false;
			}
                        
                        function onStreetImport() {
                            //action="<?php echo WEB_ROOT . 'ajax/admin.php?action=street_xls_import'; ?>"
                            //$('#street_xls_form').ajaxSubmit();
                            var formData = new FormData($('#street_xls_form')[0]);
                            
                            $.ajax({
                                url: '<?php echo WEB_ROOT . 'ajax/admin.php?action=street_xls_import'; ?>',
                                type: 'POST',
                                data: formData,
                                async: false,
                                success: function (data) {
                                    alert(data);
                                    $("#jqStreet").trigger('reloadGrid');
                                },
                                cache: false,
                                contentType: false,
                                processData: false
                            });

                            return false;                            
                        }
                        
                        function onSmsApiOptionsChange() {
				var sms_api_username = $('#sms_api_username').val();
				var sms_api_password = $('#sms_api_password').val();
				var sms_api_phone = $('#sms_api_phone').val();

				if ((sms_api_username == '') || (sms_api_password == '') || (sms_api_phone == '')) {
					alert('Заполните все поля');
				}else{
					$.ajax({
						type: "GET",
						url: "/ajax/admin.php?action=sms_api_options_change&sms_api_username="+sms_api_username+'&sms_api_password='+sms_api_password+'&sms_api_phone='+sms_api_phone,
						success: function(data){ 
                                                    //alert("Успешно изменены параметры SMS API !");
                                                    $("#sms-api-manager-table").html($(data));
                                                }
					});
                                        $("#mySmsApiOptions").modal('hide');
				}
				return false;
                        }
                        


			$(document).ready(function () {
                            // Tab initialization
                            $('#tabs').tabs({
                                activate: function(event, ui){
                                    var tabNumber = $('#tabs').tabs('option', 'active');
                                    var tabName = ui.newPanel.attr('id');

                                    //console.log('Tab number ' + tabNumber + ' - ' + tabName + ' - clicked');
                                    switch(tabNumber) {
                                        case 0:
                                            $("#jqUser").trigger('reloadGrid');
                                            break;
                                        case 1:
                                            $("#jqCity").trigger('reloadGrid');
                                            break;
                                        case 2:
                                            $("#jqLocality").trigger('reloadGrid');
                                            break;
                                        case 3:
                                            $("#jqStreet").trigger('reloadGrid');
                                            break;
                                        case 4:
                                            $("#jqWorkType").trigger('reloadGrid');
                                            break;
                                        case 5:
                                            $("#jqMaster").trigger('reloadGrid');
                                            break;
                                        case 6:
                                            $("#jqManagerStat").trigger('reloadGrid');
                                            break;
                                        case 7:
                                            $("#jqMasterStat").trigger('reloadGrid');
                                            break;
                                        case 8:
                                            $("#jqPay").trigger('reloadGrid');
                                            break;
                                        case 9:
                                            break;
                                        default:
                                            break;
                                    }
                                }
                            });
                            
                            //
                            $("#jqPay").bind('jqGridAddEditBeforeShowForm', function($form) {
                                var $f = $('#date_start');
                                if ($('#date_start').val() == '') {
                                    $('#date_start').val('<?php echo date('d.m.Y'); ?>');
                                }
                            }); 

                        onUserStatUpdate();
                    });
		</script>
		 
		<!-- Modal -->
		<div id="mySmsApiOptions" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h4 id="myModalLabel">Изменить настройки API для отправки SMS</h4>
		  </div>
		  <div class="modal-body">
                      <p>Логин: <input type="text" id="sms_api_username" name="sms_api_username" value="<?php echo $sms_api_options['sms_api_username']; ?>"></p>
		    <p>Пароль: <input type="text" id="sms_api_password" name="sms_api_password" value="<?php echo $sms_api_options['sms_api_password']; ?>"></p>
		    <p>Номер телефона: <input type="text" id="sms_api_phone" name="sms_api_phone" value="<?php echo $sms_api_options['sms_api_phone']; ?>"></p>
		  </div>
		  <div class="modal-footer">
		    <button class="btn btn-primary" onClick="onSmsApiOptionsChange();">Изменить</button>
		  </div>
		</div>


<?php include template("footer"); ?>