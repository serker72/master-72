<?php include template("header"); ?>
<script type="text/javascript" src="/static/js/jquery.form.js"></script>
<link href="/static/js/datepicker/css/datepicker.css" rel="stylesheet">
<script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.ru.js"></script>
<div class="info-block">
	<?php if(is_manager()){ ?> 
            <a href="/operator.php?action=new_order" class="btn">Добавить заказ</a> 
            <!--div style="float:right; margin-right: 5px;"-->
            <a id="download" href="/ajax/main.php?action=download<?php if(is_manager()){ echo '&user_id='.$login_user['id']; } ?>" target="_blank" class="btn">Скачать файл заказов</a><!--/div-->
        <?php } ?>
	<a href="/message.php" class="btn">Сообщения</a>
	<a href="/logout.php" class="btn">Выйти</a>
</div>
<form method="post" action="/ajax/post.php" class="validator" id="form">
	<div class="info-block" style="width: 254;">
		<input type="hidden" id="action" name="action" value="update_account" />
		ФИО: <input id="fio" name="fio" style="width:200px; border: none; background: transparent; text-decoration: none;cursor: pointer;" value="<?=$login_user['realname'];?>"> <br/>
		Телефон: <input id="phone" name="phone" style="width:200px; border: none; background: transparent; text-decoration: none;cursor: pointer;" value="<?=$login_user['phone'];?>"> <br/>
		Адрес: <input id="address" name="address" style="width:200px; border: none; background: transparent; text-decoration: none;cursor: pointer;" value="<?=$login_user['address'];?>"> <br/>
		<a href="#" onClick="update_account(); return false;" class="btn">Обновить</a>
	</div>	
</form>
<div class="info-block">
	№ договора: <?=$login_user['dogovor'];?> <br/>
	Процентная ставка: <?=$login_user['stavka'];?>% <br/>
	<div id="zp_dates">
		З/П с <input id="date1" name="date1" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none;" value="<?php echo $start_time; ?>" > по <input id="date2" name="date2" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none;" value="<?php echo $end_time; ?>" > <a hre="#" onClick="get_zp(); return false;" class="btn">Показать</a><br/>
		З/П: <span id="zp"><?=$zp;?></span> руб. <br/> 
	</div>
</div>
		<div class="info-block">
			<div>
				Дата с <input type="text" id="filter-datetime-done" name="filter-date-done" style="width: 100px;"> по <input type="text" id="filter-datetime2-done" name="filter-date2-done" style="width: 100px;">
				<a id="filter-order-done" href="#" onClick="dofilter('order-done'); return false;" class="btn">Поиск</a>
			</div>
		</div>
<?php if($login_user['rang'] != 'master'){ ?>
<div class="info-block">
	Последние обработанные 25 заказов
</div>
<div id="orders_done"></div>
<div class="info-block">
	Последние необработанные 25 заказов
</div>
<div id="orders"></div>
<?php }else{ ?>
<div class="info-block">
	Последние 25 заказов
</div>
<div id="orders_done"></div>
<?php } ?>

<div id="acts" style="display:none;">
	<div class="info-block">
		Акт выполненых работ:	
	</div>
	<div id="img_done"></div>
</div>
<script type="text/javascript">  
//Отправка формы
$(document).ready(function(){
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

	$('#filter-datetime-done').datepicker({
		format: 'dd.mm.yyyy',
		language: 'ru',
		autoclose: true
	});
	
	$('#filter-datetime2-done').datepicker({
		format: 'dd.mm.yyyy',
		language: 'ru',
		autoclose: true
	});

	get_zp();
<?php if($login_user['rang'] != 'master'){ ?>
	get_order();
	get_order_done();
<?php }else{ ?>
	get_order_master();
<?php } ?>
});

//Загружаем последние 25 записей.

function dofilter(type){
		var filter_date = $('#filter-datetime-done').val();
		var filter_date2 = $('#filter-datetime2-done').val();
		$.ajax({
			type: "GET",
			<?php if($login_user['rang'] != 'master'){ ?>
				url: "/ajax/main.php?action=get_orders_done&user_id=<?=$login_user['id'];?>&place=account&filter_date="+filter_date+"&filter_date2="+filter_date2,
			<?php }else{ ?>
				url: "/ajax/main.php?action=get_orders_master&user_id=<?=$login_user['id'];?>&place=account&filter_date="+filter_date+"&filter_date2="+filter_date2,
			<?php } ?>
			success: function(data){ 
				$('#orders_done').empty().append($(data)); 
			}
		});			
	return false;	
}

<?php if($login_user['rang'] != 'master'){ ?>
	function get_order(){
		$.ajax({
			type: "GET",
			url: "/ajax/main.php?action=get_orders&user_id=<?=$login_user['id'];?>&place=account",
			success: function(data){ $('#orders').empty().append($(data)); }
		});		
	}

	function get_order_done(){
		$.ajax({
			type: "GET",
			url: "/ajax/main.php?action=get_orders_done&user_id=<?=$login_user['id'];?>&place=account",
			success: function(data){ $('#orders_done').empty().append($(data)); }
		});		
	}
<?php }else{ ?>
	function get_order_master(){
		$.ajax({
			type: "GET",
			url: "/ajax/main.php?action=get_orders_master&user_id=<?=$login_user['id'];?>&place=account",
			success: function(data){ $('#orders_done').empty().append($(data)); }
		});		
	}
<?php } ?>

function give_img(id){
	$("#orders_done table tr td").removeClass("activetd");
	$("#orders_done table tr#tr_id_"+id+" td").addClass("activetd");
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_imgs&id="+id,
		success: function(data){ $('#img_done').empty().append($(data)); $('#acts').show(); }
	});		
}

function get_zp(){
	date_one = $('#date1').val();
	date_two = $('#date2').val();
	if(date_one == '' || date_two == ''){
		alert('Заполните даты');
	}else{
		$.ajax({
			type: "GET",
			url: "/ajax/main.php?action=get_zp&user_id=<?=$login_user['id'];?>&date_one="+date_one+"&date_two="+date_two,
			success: function(data){ $('#zp_dates').empty().append($(data));}
		});		
	}
	return false;
}

//Сохраняем информацию
function update_account(){
	var fio = $('#fio').val();
	var phone = $('#phone').val();
	var address = $('#address').val();
	$.ajax({
		type: "POST",
		data: "action=update_account&user_id=<?=$login_user['id'];?>&fio="+fio+"&phone="+phone+"&address="+address,
		url: "/ajax/post.php",
		success: function(data){
			alert('Информация успешно обновлена!');
		}
	});	
	return false;	
}
</script>
<?php include template("footer"); ?>