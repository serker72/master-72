<?php 
    include template("header_admin"); 
?>
	<script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.ru.js"></script>
	<script type="text/javascript" src="/static/js/bootstrap-modal.js"></script>

	<link href="/static/js/datepicker/css/datepicker.css" rel="stylesheet">
	
	<div class="info-block" id="status-bar-add" style="display:block; float: left;"><b>Панель управления</b></div>
	<div style="float:right; margin-right: 5px;"><a href="/logout.php" class="btn">Выйти</a></div>
	<div style="float:right; margin-right: 5px;"><a href="/operator.php" class="btn">Заказы</a></div>
        
	<div class="admin-people" style="margin-top: 20px;">
		<div class="filtering" style="float: left; margin-left: 20px;">
		    <form>
		        Логин: <input type="text" name="username" id="username" />
		        ФИО: <input type="text" name="realname" id="realname" />
		        Ранг: 
		        <select id="rang" name="rang">
		            <option selected="selected" value=""></option>
		            <option value="admin">Администратор</option>
		            <option value="operator">Оператор</option>
		            <option value="manager">Менеджер</option>
		            <option value="master">Мастер</option>        
		        </select><br/>
		        <!--Телефон: <input type="text" name="phone" id="phone" />
		        Договор: <input type="text" name="dogovor" id="dogovor" />
		        Адрес: <input type="text" name="address" id="address" />-->
		        <button type="submit" id="LoadRecordsButton">Фильтровать</button>
		    </form>
		</div>	
		<div id="PeopleTableContainer" style="float: left; margin-right: 20px; margin-left: 20px;"></div>
	</div>
	<div class="info-block" style="float:left; width: 100%;">
		<div id="sms-api-manager">
			<h4>SMS API</h4>
                        <div id="sms-api-manager-table"></div><br>
			<a href="#mySmsApiOptions" role="button"  style="font-weight:normal;" class="btn" data-toggle="modal">Изменить</a>
		</div>
	</div>
	<div class="info-block" style="float:left; width: 100%;">
		<div id="city-manager">
			<h4>Города</h4>
			<a href="#myCity" role="button"  style="font-weight:normal;" class="btn" data-toggle="modal">Добавить</a>
		</div>
	</div>
	<div class="info-block" style="float:left; width: 100%;">
		<div id="punkt-manager">
			<h4>Населенный пункт</h4>
			<a href="#myPunkt" role="button"  style="font-weight:normal;" class="btn" data-toggle="modal">Добавить</a>
		</div>
	</div>	
	<div class="info-block" style="float:left; width: 100%;">
		<div id="street-manager">
			<h4>Улицы</h4>
			<a href="#myStreet" role="button"  style="font-weight:normal;" class="btn" data-toggle="modal">Добавить</a>
		</div>
	</div>		
	<div class="info-block" style="float:left; width=650px;">
		<div id="table-manager">
			<h4>Подсчет з/п - Менеджеров</h4>
			<div class="div-manager-zp">
				<!--Даты: с <input type="text" id="date1" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none; margin-top: 10px;" value="<?php //echo $start_time; ?>"> по <input type="text" id="date2" style="width:100px; border: none; background: transparent; margin-top: 10px; text-decoration: none;cursor: pointer; box-shadow:none;" value="<?php //echo $end_time; ?>"><br/>-->
				Имя: <input type="text" id="manager-zp" name="manager-zp" value="" />
                                <a class="btn" href="#" onClick="onManager(); return false;" style="font-weight:normal; margin-top: -11px; margin-left: 20px;">Показать</a>
				<a class="btn" href="#" onClick="get_xls('manager'); return false;">Скачать</a>
			<table id="table-manager" class="table table-bordered">
                            <thead>
				<tr>
					<td>ФИО</td>
					<td>Рассчитано</td>
					<td>Выплачено</td>
					<td>З/П</td>
				</tr>
                            </thead>
                            <tbody>
				<?php /*foreach ($array_man as $two) { ?>
					<tr>
						<td id="name_<?=$two['id']?>"><?php echo $two['name']; ?></td>
						<td id="zp_calc_<?=$two['id']?>"><?php echo $two['zp_calc']; ?></td>
						<td id="zp__pay<?=$two['id']?>"><?php echo $two['zp_pay']; ?></td>
						<td id="zp_<?=$two['id']?>"><?php echo $two['zp']; ?>
							<!--input type="hidden" name="date_start_<?$two['id']?>" value="<?php //echo $start_time; ?>"-->
							<!--input type="hidden" name="date_end_<?$two['id']?>" value="<?php //echo $end_time; ?>"-->
						</td>
					</tr>
				<?php } */?>
                            </tbody>
			</table>
			</div>
		</div>
	</div>
	<div class="info-block" style="float:left; width=650px;">
		<div id="table-master">
			<h4>Подсчет з/п - Мастеров</h4>
                    <div class="div-master-zp">
			<!--input type="text" id="date3" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none; margin-top: 10px;" value="<?php //echo $start_time; ?>"> по <input type="text" id="date4" style="width:100px; border: none; background: transparent; margin-top: 10px; text-decoration: none;cursor: pointer; box-shadow:none;" value="<?php //echo $end_time; ?>"-->
			<a class="btn" href="#" onClick="onMaster(); return false;" style="font-weight:normal;">Показать</a>
			<a class="btn" href="#" onClick="get_xls('master'); return false;">Скачать</a>
			<table  id="table-master" class="table table-bordered">
                            <thead>
				<tr>
					<td>ФИО</td>
					<td>Рассчитано</td>
					<td>Выплачено</td>
					<td>З/П</td>
				</tr>
                            </thead>
                            <tbody>
			<?php /*foreach ($array_mas as $two) { ?>
				<tr>
					<td><?php echo $two['name']; ?></td>
					<td><?php echo $two['zp_calc']; ?></td>
					<td><?php echo $two['zp_pay']; ?></td>
					<td><?php echo $two['zp']; ?></td>
				</tr>
			<?php }*/ ?>
                            </tbody>
			</table>
                    </div>
		</div>
	</div>	
	<div class="info-block" style="float:left; width: 800px;">
		<div id="table-master">
			<h4>Выплаты с <input type="text" id="pay_date3" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none; margin-top: 10px;" value="<?php echo $start_time; ?>"> по <input type="text" id="pay_date4" style="width:100px; border: none; background: transparent; margin-top: 10px; text-decoration: none;cursor: pointer; box-shadow:none;" value="<?php echo $end_time; ?>"><a class="btn" href="#" onClick="onPay(); return false;" style="font-weight:normal;margin-right:10px;">Показать</a><a href="#myModal" role="button"  style="font-weight:normal;" class="btn" data-toggle="modal">Добавить</a></h4>
			<div id="orders_pay" style="min-height:200px; width: 98%;"></div>
		</div>
	</div>	
	
		<script type="text/javascript">
			function get_xls(rang){
				/*if(rang == 'manager'){
					var date1 = $('#date1').val();
					var date2 = $('#date2').val();
				}else{
					var date1 = $('#date3').val();
					var date2 = $('#date4').val();
				}
				if(date1 == '' || date2 == ''){
					alert('Заполните даты');
				}else{*/
					var a = document.createElement('a');
					//a.href='/get_xls.php?action='+rang+'&date1='+date1+'&date2='+date2;
					a.href='/get_xls.php?action='+rang;
					a.target = '_blank';
					document.body.appendChild(a);
					a.click();
				//}
			}


			function onManager(){
				//var date1 = $('#date1').val();
				//var date2 = $('#date2').val();
				var name = $('#manager-zp').val();
				//if(date1 == '' || date2 == ''){
				//	alert('Заполните даты');
				//}else{
					$.ajax({
						type: "GET",
						//url: "/ajax/admin.php?action=onmanager&date1="+date1+"&date2="+date2+"&name="+name,
						url: "/ajax/admin.php?action=onmanager&name="+name,
						success: function(data){ 
                                                    //$('#table-manager').empty().append($(data)); 
                                                    $('table#table-manager tbody').html($(data)); 
                                                }
					});
				//}		
			}

			function onMaster(){
				//var date3 = $('#date3').val();
				//var date4 = $('#date4').val();
				//if(date3 == '' || date4 == ''){
				//	alert('Заполните даты');
				//}else{
					$.ajax({
						type: "GET",
						//url: "/ajax/admin.php?action=onmaster&date1="+date3+"&date2="+date4,
						url: "/ajax/admin.php?action=onmaster",
						success: function(data){ 
                                                    //$('#table-master').empty().append($(data)); 
                                                    $('table#table-master tbody').html($(data)); 
                                                }
					});	
				//}
				return false;
			}

			function onPay(){
				var pay_date1 = $('#pay_date3').val();
				var pay_date2 = $('#pay_date4').val();
				if(pay_date1 == '' || pay_date2 == ''){
					alert('Заполните даты');
				}else{
					$.ajax({
						type: "GET",
						url: "/ajax/admin.php?action=pay&date1="+pay_date1+"&date2="+pay_date2,
						success: function(data){
                                                    //alert(data);
                                                    $('#orders_pay').empty().append($(data)); 
                                                }
					});
				}		
				return false;
			}

			function onPostadd(){
				var add_date1 = $('#add_date1').val();
				var add_date2 = $('#add_date2').val();
				var user_name = $('#user_name').val();
				var cost = $('#cost').val();
				if(add_date1 == '' || add_date2 == ''){
					alert('Заполните даты');
				}else{
					$.ajax({
						type: "GET",
						url: "/ajax/admin.php?action=post_pay&date1="+add_date1+"&date2="+add_date2+"&user_id="+user_name+"&cost="+cost,
						success: function(data){ alert("Успешно добавлено!"); }
					});
				}		
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
                        
			function onCityadd(){
				var name = $('#name').val();

				if(name == ''){
					alert('Заполните поле');
				}else{
					$.ajax({
						type: "GET",
						url: "/ajax/admin.php?action=city_add&name="+name,
						success: function(data){ alert("Успешно добавлено!"); get_cities(); }
					});
				}		
				return false;
			}

			function onPunktadd(){
				var city_id = $('#city_punkt').val();
				var name_punkt = $('#name_punkt').val();

				if(name_punkt == '' || city_id == ''){
					alert('Заполните поле');
				}else{
					$.ajax({
						type: "GET",
						url: "/ajax/admin.php?action=punkt_add&name="+name_punkt+"&city="+city_id,
						success: function(data){ alert("Успешно добавлено!"); }
					});
				}		
				return false;
			}

			function onStreetadd(){
				var city_id = $('#city_street').val();
				var name_street = $('#name_street').val();
				var punkt = $('#punkt_street').val();

				if(name_street == '' || city_id == ''){
					alert('Заполните поле');
				}else{
					$.ajax({
						type: "GET",
						url: "/ajax/admin.php?action=street_add&name="+name_street+"&city="+city_id+"&punkt="+punkt,
						success: function(data){ alert("Успешно добавлено!"); }
					});
				}		
				return false;
			}

			function get_cities(){
				$.ajax({
					type: "GET",
					url: "/ajax/admin.php?action=city_go",
					success: function(data){ 
						$('select#city_punkt').empty().append($(data)); 
						$('select#city_street').empty().append($(data)); 
					}
				});
			}

			function get_punkts(){
				var city_id = $('#city_street').val();
				$.ajax({
					type: "GET",
					url: "/ajax/admin.php?action=street_go&city="+city_id,
					success: function(data){ 
						$('select#punkt_street').empty().append($(data)); 
					}
				});
			}			

			$(document).ready(function () {
				$('#city_street').change(function() {
				  	get_punkts();
				});

				$('#date1').datepicker({
					format: 'dd.mm.yyyy',
					language: 'ru',
					autoclose: true
				})

				$('#date2').datepicker({
					format: 'dd.mm.yyyy',
					language: 'ru',
					autoclose: true
				})

				$('#date3').datepicker({
					format: 'dd.mm.yyyy',
					language: 'ru',
					autoclose: true
				})

				$('#date4').datepicker({
					format: 'dd.mm.yyyy',
					language: 'ru',
					autoclose: true
				})

				$('#pay_date3').datepicker({
					format: 'dd.mm.yyyy',
					//format: 'yyyy-mm-dd',
					language: 'ru',
					autoclose: true
				})

				$('#pay_date4').datepicker({
					format: 'dd.mm.yyyy',
					//format: 'yyyy-mm-dd',
					language: 'ru',
					autoclose: true
				})				

				$('#add_date1').datepicker({
					format: 'yyyy-mm-dd',
					language: 'ru',
					autoclose: true
				})

				$('#add_date2').datepicker({
					format: 'yyyy-mm-dd',
					language: 'ru',
					autoclose: true
				})

				$('#PeopleTableContainer').jtable({
					title: 'Пользователи',
					sorting: true,
					actions: {
						listAction: '/ajax/admin.php?action=list',
						createAction: '/ajax/admin.php?action=create',
						updateAction: '/ajax/admin.php?action=update',
						deleteAction: '/ajax/admin.php?action=delete'
					},
					fields: {
						id: {
							title: 'Id',
							key: true,
							create: false,
							edit: false,
							list: false
						},
						username: {
							title: 'Логин',
							width: '20%'
						},
						realname: {
							title: 'ФИО',
							width: '20%'
						},
						password: {
							title: 'Пароль',
							create: true,
							edit: true,
							list: false
						},
						rang: {
							title: 'Ранг',
							width: '10%',
							options: { 'admin': 'Администратор', 'operator': 'Оператор', 'manager': 'Менеджер', 'master': 'Мастер' },
						},
						phone: {
							title: 'Телефон',
							width: '30%'
						},
						sms: {
							title: 'СМС',
							width: '5%',
							options: { '0': 'Нет', '1': 'Да' },
							list: false
						},
						dogovor: {
							title: 'Договор',
							width: '10%',
							list: false
						},
						address: {
							title: 'Адрес',
							width: '40%',
							list: false
						},
						company: {
							title: 'Компания',
							width: '40%'
						},
						stavka: {
							title: 'Ставка',
							width: '5%'
						},
					}
				});
				//Load person list from server
				//$('#PeopleTableContainer').jtable('load');
				
				$('#LoadRecordsButton').click(function (e) {
		            e.preventDefault();
		            $('#PeopleTableContainer').jtable('load', {
		                username: $('#username').val(),
		                realname: $('#realname').val(),
		                rang: $('#rang').val(),
		                phone: $('#phone').val(),
		                dogovor: $('#dogovor').val(),
		                address: $('#address').val(),
		                company: $('#company').val(),
		            });
		        });
 
        		//Load all records when page is first shown
        		$('#LoadRecordsButton').click();
                        
                        // Загружаем данные о з/п при входе
                        onManager();
                        onMaster();

			});
		</script>
		 
		<!-- Modal -->
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Добавить</h3>
		  </div>
		  <div class="modal-body">
		    <p>Выбрать:
				<select name="user_name" id="user_name">
					<?php foreach ($user_add as $one) {
						echo '<option value="'.$one['id'].'">'.$one['realname'].'</option>';
					} ?>				
				</select>
		    </p>
		    <p>Даты с <input type="text" id="add_date1" style="width:100px; margin-top: 10px;" value=""> по <input type="text" id="add_date2" style="width:100px;" value=""></p>
		    <p>Выплата <input type="text" id="cost" name="cost"></p>
		  </div>
		  <div class="modal-footer">
		    <button class="btn btn-primary" onClick="onPostadd();">Добавить</button>
		  </div>
		</div>

		<!-- Modal -->
		<div id="myCity" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Добавить</h3>
		  </div>
		  <div class="modal-body">
		    <p>Все города:
				<select name="city" id="city">
					<option value="0">Хочу добавить город</option>
					<?php foreach ($cityes as $one) {
						echo '<option value="'.$one['id'].'">'.$one['name'].'</option>';
					} ?>				
				</select>
		    </p>
		    <p>Название: <input type="text" id="name" name="name"></p>
		  </div>
		  <div class="modal-footer">
		    <button class="btn btn-primary" onClick="onCityadd();">Добавить</button>
		  </div>
		</div>

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


		<!-- Modal -->
		<div id="myPunkt" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Добавить</h3>
		  </div>
		  <div class="modal-body">
		    <p>Выбрать город:
				<select name="city_punkt" id="city_punkt">
					<option value="">Выбрать</option>
					<?php foreach ($cityes as $one) {
						echo '<option value="'.$one['id'].'">'.$one['name'].'</option>';
					} ?>				
				</select>
		    </p>
		    <p>Название: <input type="text" id="name_punkt" name="name_punkt"></p>
		  </div>
		  <div class="modal-footer">
		    <button class="btn btn-primary" onClick="onPunktadd();">Добавить</button>
		  </div>
		</div>	

		<!-- Modal -->
		<div id="myStreet" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Добавить</h3>
		  </div>
		  <div class="modal-body">
		    <p>Выбрать город:
				<select name="city_street" id="city_street">
					<option value="">Выбрать</option>
					<?php foreach ($cityes as $one) {
						echo '<option value="'.$one['id'].'">'.$one['name'].'</option>';
					} ?>				
				</select>
		    </p>
		    <p>Выбрать нас. пункт:
				<select name="punkt_street" id="punkt_street">
					<option value="">Выбрать</option>				
				</select>
		    </p>		    
		    <p>Название: <input type="text" id="name_street" name="name_street"></p>
		  </div>
		  <div class="modal-footer">
		    <button class="btn btn-primary" onClick="onStreetadd();">Добавить</button>
		  </div>
		</div>								
<?php include template("footer"); ?>