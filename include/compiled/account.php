<?php include template("header"); ?>
<script type="text/javascript" src="/static/js/bootstrap-modal.js"></script>
<script type="text/javascript" src="/static/js/jquery.form.js"></script>
<link href="/static/js/datepicker/css/datepicker.css" rel="stylesheet">
<script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.ru.js"></script>
<div class="info-block">
	<?php if(is_manager()){ ?> 
    <a href="/operator.php" class="btn">Добавить заказ</a> <!--?action=new_order-->
            <!--div style="float:right; margin-right: 5px;"-->
            <a id="download" href="/ajax/main.php?action=download<?php if(is_manager()){ echo '&user_id='.$login_user['id']; } ?>" target="_blank" class="btn">Скачать файл заказов</a><!--/div-->
        <?php } ?>
	<a href="/message.php" class="btn">Сообщения</a>
	<a href="/logout.php" class="btn">Выйти</a>
</div>
<form method="post" action="/ajax/post.php" class="validator" id="form">
	<div class="info-block" style="margin-top: 100px;">
		<input type="hidden" id="action" name="action" value="update_account" />
		ФИО: <input id="fio" name="fio" style="width:200px; background: transparent; text-decoration: none;cursor: pointer;" value="<?=$login_user['realname'];?>"> &nbsp;&nbsp;&nbsp;
		Телефон: <input id="phone" name="phone" style="width:200px; background: transparent; text-decoration: none;cursor: pointer;" value="<?=$login_user['phone'];?>"> <br/><br/>
		Адрес: <input id="address" name="address" style="width:200px; background: transparent; text-decoration: none;cursor: pointer;" value="<?=$login_user['address'];?>"> &nbsp;&nbsp;&nbsp;
                <!--Пароль: <input type="password" id="password" name="password" style="width:200px; background: transparent; text-decoration: none;cursor: pointer;" value=""> <br/><br/-->
		<a href="#" onClick="update_account(); return false;" class="btn">Обновить</a>
                <a href="#myChangePassword" role="button"  style="font-weight:normal;" class="btn" data-toggle="modal">Изменить пароль</a>
	</div>	
</form>
<div class="info-block">
	№ договора: <?=$login_user['dogovor'];?> <br/>
	Процентная ставка: <?=$login_user['stavka'];?>% <br/>
	<div id="zp_dates">
		З/П с <input id="date1" name="date1" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none;" value="<?php echo $start_time; ?>" > по <input id="date2" name="date2" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none;" value="<?php echo $end_time; ?>" > <a hre="#" onClick="get_zp(false); return false;" class="btn">Показать</a><br/>
                      <input type="hidden" id="date3" name="date3" value="<?php echo $start_year_time; ?>" />
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

<!-- Modal -->
<div id="myChangePassword" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 id="myModalLabel">Изменить пароль пользователя</h4>
  </div>
  <div class="modal-body">
    <p>Текущий пароль: <input type="text" id="old_password" name="old_password" value=""></p>
    <p>Новый пароль: <input type="text" id="new_password1" name="new_password1" value=""></p>
    <p>Повтор нового пароля: <input type="text" id="new_password2" name="new_password2" value=""></p>
    <p id="change_password_status"></p>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" onClick="onChangePassword();">Изменить</button>
  </div>
</div>


<script type="text/javascript">  
//Отправка формы
$(document).ready(function(){
	$('#date1').datepicker({
		format: 'dd.mm.yyyy',
		language: 'ru',
		autoclose: true
	});
        
	$('#date2').datepicker({
		format: 'dd.mm.yyyy',
		language: 'ru',
		autoclose: true
	});

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

	get_zp(true);
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
				url: "<?php echo WEB_ROOT; ?>/ajax/main.php?action=get_orders_done&user_id=<?=$login_user['id'];?>&place=account&filter_date="+filter_date+"&filter_date2="+filter_date2,
			<?php }else{ ?>
				url: "<?php echo WEB_ROOT; ?>/ajax/main.php?action=get_orders_master&user_id=<?=$login_user['id'];?>&place=account&filter_date="+filter_date+"&filter_date2="+filter_date2,
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
			url: "<?php echo WEB_ROOT; ?>/ajax/main.php?action=get_orders&user_id=<?=$login_user['id'];?>&place=account",
			success: function(data){ $('#orders').empty().append($(data)); }
		});		
	}

	function get_order_done(){
		$.ajax({
			type: "GET",
			url: "<?php echo WEB_ROOT; ?>/ajax/main.php?action=get_orders_done&user_id=<?=$login_user['id'];?>&place=account",
			success: function(data){ $('#orders_done').empty().append($(data)); }
		});		
	}
<?php }else{ ?>
	function get_order_master(){
		$.ajax({
			type: "GET",
			url: "<?php echo WEB_ROOT; ?>/ajax/main.php?action=get_orders_master&user_id=<?=$login_user['id'];?>&place=account",
			success: function(data){ $('#orders_done').empty().append($(data)); }
		});		
	}
<?php } ?>

function give_img(id){
	$("#orders_done table tr td").removeClass("activetd");
	$("#orders_done table tr#tr_id_"+id+" td").addClass("activetd");
	$.ajax({
		type: "GET",
		url: "<?php echo WEB_ROOT; ?>/ajax/main.php?action=get_imgs&id="+id,
		success: function(data){ $('#img_done').empty().append($(data)); $('#acts').show(); }
	});		
}

function get_zp(dt3_flag){
	date_one = $('#date1').val();
	date_two = $('#date2').val();
        if (dt3_flag) date_three = $('#date3').val();
        else date_three = $('#date1').val();
        
	if(date_one == '' || date_two == ''){
		alert('Заполните даты');
	}else{
		$.ajax({
			type: "GET",
			url: "<?php echo WEB_ROOT; ?>/ajax/main.php?action=get_zp&user_id=<?=$login_user['id'];?>&date_one="+date_one+"&date_two="+date_two+"&date_three="+date_three,
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
	var password = $('#password').val();
	$.ajax({
		type: "POST",
		data: "action=update_account&user_id=<?=$login_user['id'];?>&fio="+fio+"&phone="+phone+"&address="+address+"&password="+password,
		url: "<?php echo WEB_ROOT; ?>/ajax/post.php",
		success: function(data){
			alert('Информация успешно обновлена!');
		}
	});	
	return false;	
}

function onChangePassword() {
    var old_password = $('#old_password').val();
    var new_password1 = $('#new_password1').val();
    var new_password2 = $('#new_password2').val();

    if ((old_password == '') || (new_password1 == '') || (new_password2 == '')) {
        $("#change_password_status").html('Заполните все поля !');
        $("#change_password_status").removeClass('text-success');
        $("#change_password_status").addClass('text-error');
        //alert('Заполните все поля !');
    } else if (new_password1 !== new_password2) {
        $("#change_password_status").html('Укажите одинаковые значения нового пароля в обоих полях !');
        $("#change_password_status").removeClass('text-success');
        $("#change_password_status").addClass('text-error');
        //alert('Укажите одинаковые значения нового пароля в обоих полях !');
    } else {
        $.ajax({
            type: "POST",
            data: "action=change_password&user_id=<?=$login_user['id'];?>&old_password="+old_password+"&new_password1="+new_password1+"&new_password2="+new_password2,
            url: "<?php echo WEB_ROOT; ?>/ajax/post.php",
            dataType: 'json',
            success: function(data){ 
                //alert("Успешно изменены параметры SMS API !");
                if (data.status) {
                    $("#change_password_status").html(data.msg);
                    $("#change_password_status").removeClass('text-error');
                    $("#change_password_status").addClass('text-success');
                    alert(data.msg);
                    $("#myChangePassword").modal('hide');
                } else {
                    $("#change_password_status").html(data.msg);
                    $("#change_password_status").removeClass('text-success');
                    $("#change_password_status").addClass('text-error');
                }
            }
        });
    }
    return false;
}
                        

</script>
<?php include template("footer"); ?>