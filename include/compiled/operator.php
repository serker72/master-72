<?php 
function PrintEditForm($img_fields_flag) {
    global $login_user, $work_types, $master, $city, $users_master;
    
    if ($img_fields_flag) {
        echo '<div id="edit-form-wrapper" style="display: none;">';
    } else {
        echo '<div id="add-form-wrapper">';
    }
    ?>
    <form method="post" action="/ajax/post.php" class="validator" id="form" style="float:left;">	
	<input type="hidden" id="action" name="action" value="add" />
	<input type="hidden" id="order_id" name="order_id" value="0" />
	<table style="width: 816px; float:left;">
		<tr>
			<td style="width:280px;">
				<div style="float:left;">
					<div class="title">Дата</div>
					<input type="text" id="datetime" name="date" style="width: 100px;" <?php if(is_manager()){ echo 'disabled'; } ?>>
				</div>
				<div style="float:left; margin-left: 5px;">
					<div class="title">Время</div>
					<input type="text" id="time" name="time" style="width: 60px;" <?php if(is_manager()){ echo 'disabled'; } ?>>
				</div>
			</td>
			<td>
				<div class="title">Тип работ</div>
				<select name="work_type" id="work_type">
					<option value="" selected="selected">Выбрать</option>
					<?php foreach ($work_types as $one) {
						echo '<option value="'.$one['id'].'">'.$one['name'].'</option>';
					} ?>
				</select>
			</td>
			<td>
				<div class="title">Мастер</div>
				<select name="master" id="master">
					<option value="" selected="selected">Выбрать</option>
					<?php foreach ($master as $one) {
						echo '<option value="'.$one['id'].'">'.$one['name'].'</option>';
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<div id="hope">
					<div style="width:280px; float:left;">Желаемое время</div>
					<div style="float:left;">
						<div class="title">Дата</div>
						<input type="text" id="datetime_hope" name="date_hope" style="width: 100px;">
					</div>
					<div style="float:left; margin-left: 5px;">
						<div class="title">Время</div>
						<input type="text" id="time_hope" name="time_hope" style="width: 60px;">
					</div>
				</div>
			</td>
			<td>
				<div class="title">Город</div>
				<select name="city" id="city">
					<option value="" selected="selected">Выбрать</option>
					<?php foreach ($city as $one) {
						echo '<option value="'.$one['id'].'">'.$one['name'].'</option>';
					} ?>
				</select>
			</td>
			<td>
				<div class="title">Населенный пункт</div>
				<select name="city2" id="city2">
					<option value="" selected="selected">Не выбран город</option>
				</select>
			</td>		
		</tr>
		<tr>
			<td>
				<div class="title">Улица</div>
				<input type="text" id="street" name="street">
			</td>
			<td>
				<table style="width:100%; margin:0;">
					<tr>
						<td><div class="title">Дом</div></td>
						<td><div class="title">Корп.</div></td>
						<td><div class="title">Кв.</div></td>
					</tr>
					<tr>
						<td><input type="text" id="house" name="house" style="width: 50px;"></td>
						<td><input type="text" id="corpus" name="corpus" style="width: 50px;"></td>
						<td><input type="text" id="flat" name="flat" style="width: 50px;"></td>
					</tr>
				</table>
			</td>
			<td>
				<div class="title">Особые отметки</div>
				<textarea cols="38" rows="4" id="customer-details" name="customer-details" style="margin-left: 0px; margin-right: 0px; width: 250px; margin-top: 0px; margin-bottom: 0px; height: 59px; padding: 3px;"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<div class="title">Телефон заказчика</div>
				<input type="text" class="custom-phone" id="customer-phone" name="customer-phone">
				<div class="title">Телефон заказчика 2</div>
				<input type="text" class="custom-phone" id="customer-phone2" name="customer-phone2">
				смс <input type="checkbox" id="sms2" name="sms2" style="margin-top: -16px;" />
				<div class="title">Телефон заказчика 3</div>
				<input type="text" class="custom-phone" id="customer-phone3" name="customer-phone3">
				смс <input type="checkbox" id="sms3" name="sms3" style="margin-top: -16px;" />
			</td>
			<td>
				<div class="title">ФИО заказчика</div>
				<input type="text" id="customer-name" name="customer-name">
				<div class="title">ФИО заказчика 2</div>
				<input type="text" id="customer-name2" name="customer-name2">
				<div class="title">ФИО заказчика 3</div>
				<input type="text" id="customer-name3" name="customer-name3">
			</td>
			<td>
				<div <?php if(is_manager()){ echo 'style="display:none;"'; } ?>>
					<div class="title">ФИО мастера 1</div>
					<select id="master-name" name="master-name" <?php if(is_manager()){ echo 'disabled'; } ?>>
						<option value="" selected="selected">Выбрать</option>
						<?php foreach($users_master as $one){
							echo '<option value="'.$one['id'].'">'.$one['realname'].'</option>';
						} ?>
					</select>
					<div class="title">ФИО мастера 2</div>
					<select id="master-name-two" name="master-name-two" <?php if(is_manager()){ echo 'disabled'; } ?>>
						<option value="" selected="selected">Выбрать</option>
						<?php foreach($users_master as $one){
							echo '<option value="'.$one['id'].'">'.$one['realname'].'</option>';
						} ?>
					</select>	
					<div class="title">ФИО мастера 2</div>
					<select id="master-name-th" name="master-name-th" <?php if(is_manager()){ echo 'disabled'; } ?>>
						<option value="" selected="selected">Выбрать</option>
						<?php foreach($users_master as $one){
							echo '<option value="'.$one['id'].'">'.$one['realname'].'</option>';
						} ?>
					</select>										
				</div>

			</td>
		</tr>
		<tr>
			<td>
				<div class="title">Номер акта</div>
				<input type="text" id="offer-number" name="offer-number" <?php if(is_manager()){ echo 'disabled'; } ?>>
			</td>
			<td>
				<div class="title">Сумма заказа</div>
				<input type="text" id="cost" name="cost" <?php if(is_manager()){ echo 'disabled'; } ?>>
			</td>
			<td>
                            <?php if ($img_fields_flag) { ?>
                                <div class="title">Статус</div>
				<select name="status" id="status">
					<option value="0" selected="selected">Выбрать</option>
					<option value="1">Выполнен</option>
					<option value="2">Отменен</option>
					<option value="3">Отказ</option>
					<option value="4">Отсутствие заказчика</option>
				</select>
                            <?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				<div id="note_div" <?php if(is_manager()){ echo 'style="display:none;"'; } ?>>
					<div class="title">Примечания</div>
					<input type="text" id="note" name="note" <?php if(is_manager()){ echo 'disabled'; } ?>>
				</div>				
			</td>
			<td>
			</td>
			<td>
			</td>
		</tr>
		<?php if ($img_fields_flag && ($login_user['rang'] == 'admin' || $login_user['rang'] == 'operator')) { ?>
		<tr>
			<td>
				<div id="upload_img">
					<div class="title" style="margin-bottom: 5px;">Акт выполненных работ</div>
					<div id="upload" class="btn img-upload-button"><span>Загрузить страницу 1<span></div>
					<span id="status" ></span>
					<ul id="files" class="img-files-files" style="margin-top:5px;" ></ul>
					<input type="hidden" name="img" id="img" value="">

					<div id="upload1" class="btn img-upload-button"><span>Загрузить страницу 2<span></div>
					<span id="status1" ></span>
					<ul id="files1" class="img-files-files" style="margin-top:5px;" ></ul>				
					<input type="hidden" name="img1" id="img1" value="">

					<div id="upload2" class="btn img-upload-button"><span>Загрузить страницу 3<span></div>
					<span id="status2" ></span>
					<ul id="files2" class="img-files-files" style="margin-top:5px;" ></ul>				
					<input type="hidden" name="img2" id="img2" value="">	

					<div id="upload3" class="btn img-upload-button"><span>Загрузить страницу 4<span></div>
					<span id="status3" ></span>
					<ul id="files3" class="img-files-files" style="margin-top:5px;" ></ul>				
					<input type="hidden" name="img3" id="img3" value="">

					<div id="upload4" class="btn img-upload-button"><span>Загрузить страницу 5<span></div>
					<span id="status4" ></span>
					<ul id="files4" class="img-files-files" style="margin-top:5px;" ></ul>				
					<input type="hidden" name="img4" id="img4" value="">
					
					<div id="upload5" class="btn"><span>Загрузить страницу 6<span></div>
					<span id="status5" ></span>
					<ul id="files5" class="img-files-files" style="margin-top:5px;" ></ul>				
					<input type="hidden" name="img5" id="img5" value="">					

					<div id="upload6" class="btn"><span>Загрузить страницу 7<span></div>
					<span id="status6" ></span>
					<ul id="files6" class="img-files-files" style="margin-top:5px;" ></ul>				
					<input type="hidden" name="img6" id="img6" value="">					
					
					<div id="upload7" class="btn"><span>Загрузить страницу 8<span></div>
					<span id="status7" ></span>
					<ul id="files7" class="img-files-files" style="margin-top:5px;" ></ul>				
					<input type="hidden" name="img7" id="img7" value="">										
				</div>							
			</td>
			<td>
				<img id="ajax_load" src="/static/img/ajax.gif" style="display:none; margin-top: 130px;" />
			</td>
			<td>
				<div id="img_done"></div>
			</td>
		</tr>
		<?php } ?>
	</table>
    <?php if ($img_fields_flag) { ?>
	<div id="output" style="background: #FFF; width:300px; margin-top: 10px;"></div>
	<div class="info-block" style="width: 816px; float:left;">
			<!--a id="search-form" href="#" onClick="dosearch();" class="btn">Поиск</a-->
			<!--a id="btnadd-form" href="#" onClick="doadd();" class="btn" style="display:none;">Добавить запись</a-->
			<a id="clear-form" href="#" onClick="clearall();" class="btn">Очистить</a>
			<div style="float: right;">
				<a id="copy-order" href="#" onClick="copythis();" class="btn" style="display:none;">Копировать</a>
				<a id="delete-order" href="#" onClick="deletethis();" class="btn" style="display:none;">Удалить</a>
				<input type="submit" id="submit" value="Сохранить" name="commit" class="btn"/>
			</div>
	</div>
    <?php } ?>
    </form>
    </div>
<?php    
}
?>

<?php include template("header"); ?>
<script type="text/javascript" src="/static/js/jquery.form.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="/static/css/time/date/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="/static/css/time/date/jquery-ui-1.8.16.custom.min.js"></script>

<script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/static/js/datepicker/js/bootstrap-datepicker.ru.js"></script>
<script type="text/javascript" src="/static/js/bootstrap-modal.js"></script>
<link href="/static/js/datepicker/css/datepicker.css" rel="stylesheet">

<script type="text/javascript" src="/static/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="/static/css/jquery.autocomplete.css" />
<script type="text/javascript" src="/static/js/uploader/ajaxupload.3.5.js" ></script>
<link rel="stylesheet" type="text/css" href="/static/js/uploader/styles.css" />
<script type="text/javascript" src="/static/js/jquery.md5.js" ></script>
<style type="text/css"> 
	#ui-datepicker-div, .ui-datepicker{ font-size: 80%; }
	.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
	.ui-timepicker-div dl { text-align: left; }
	.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
	.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
	.ui-timepicker-div td { font-size: 90%; }
	.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }

	.autocomplete-w1 { background:url(img/shadow.png) no-repeat bottom right; position:absolute; top:0px; left:0px; margin:6px 0 0 6px; /* IE6 fix: */ _background:none; _margin:1px 0 0 0; }
	.autocomplete { border:1px solid #999; background:#FFF; cursor:default; text-align:left; max-height:350px; overflow:auto; margin:-6px 6px 6px -6px; /* IE6 specific: */ _height:350px;  _margin:0; _overflow-x:hidden; }
	.autocomplete .selected { background:#F0F0F0; }
	.autocomplete div { padding:2px 5px; white-space:nowrap; overflow:hidden; }
	.autocomplete strong { font-weight:normal; color:#3399FF; }
	.f-input{
		font-size: 14px;
		padding: 3px 4px;
		border-color: #89B4D6;
		border-style: solid;
		border-width: 1px;
	}
</style>
<div style="float:right; margin-right: 5px;"><a href="/logout.php" class="btn">Выйти</a></div>
<?php if($login_user['rang'] == 'admin'){ ?><div style="float:right; margin-right: 5px;"><a href="/admin.php" class="btn">Админка</a></div><?php } ?>
	<div style="float:right; margin-right: 5px;"><a href="/message.php" class="btn">Сообщения</a></div>
<?php if($login_user['rang'] == 'manager'){ ?><div style="float:right; margin-right: 5px;"><a href="/account.php" class="btn">Личный кабинет</a></div><?php } ?>
<div style="float:right; margin-right: 5px;"><a id="mySearchModalButton" href="#mySearchModal" class="btn" role="button" data-toggle="modal">Поиск</a></div>
<div style="float:right; margin-right: 5px;"><a id="myAddModalButton" href="#myAddModal" class="btn" role="button" data-toggle="modal">Добавить заказ</a></div>
<div class="info-block" id="status-bar-add" style="display:none; float: left;"><b>Добавление нового заказа (нажмите на "Сохранить" для добавления)</b></div>
<div class="info-block" id="status-bar-search" style="display:none; float: left;"><b>Поиск заказа</b></div>
<div class="info-block" id="status-bar-edit" style="display:none; float: left;"><b>Редактирование заказа</b></div>
<input type="hidden" id="rang_user" value="<?=$login_user['rang']?>" />

<?php PrintEditForm(true); ?>

	<div class="info-block" style="width: 816px; float:left;">
		<p id="last-post" style="display:block;">Последние не обработанные 25 записей:</p>
		<p id="last-search" style="display:none;">Результат поиска</p>
	</div>
	<div id="orders" style="width: 816px; float:left;"></div>

	<?php if($login_user['rang'] == 'admin' || $login_user['rang'] == 'operator' || $login_user['rang'] == 'manager'){ ?>
		<div class="info-block" style="width: 816px; float:left;">
			<div>Дата с <input type="text" id="filter-datetime-done" name="filter-date-done" style="width: 100px;"> по <input type="text" id="filter-datetime2-done" name="filter-date2-done" style="width: 100px;"></div>
			<?php if($login_user['rang'] == 'admin' || $login_user['rang'] == 'operator'){ ?>
			<div>Фильтр по мастерам: 
				<select id="filter-master-done" name="filter-master-done">
						<option value="" selected="selected">Выбрать</option>
						<?php foreach($users_master as $one){
							echo '<option value="'.$one['id'].'">'.$one['realname'].'</option>';
						} ?>
				</select>
				<a id="filter-order-done" href="#" onClick="dofilter('order-done'); return false;" class="btn">Поиск</a>			
			</div>
			<?php }else{ ?>
			<div>
				<a id="filter-order-done" href="#" onClick="dofilter('order-done'); return false;" class="btn">Поиск</a>			
			</div>				
			<?php } ?>	
		</div>
	<?php } ?>	
	<div class="info-block" style="width: 816px; float:left;">
		<p id="last-post" style="display:block;">Последние обработанные 25 записей:</p>
	</div>
	<div id="orders_done" style="width: 816px; float:left;"></div>

<!--------------------------------------------------------------------------------------->
    <!-- Modal -->
    <div id="mySearchModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 600px; margin-left: -300px;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Поиск заказов</h3>
        </div>
        <div class="modal-body">
            <form method="post" action="/ajax/post.php" class="validator" id="form" style="float:left;">	
                <input type="hidden" id="action" name="action" value="search" />
                <input type="hidden" id="order_id" name="order_id" value="0" />
                <table style="float:left;">
                    <tr>
			<td style="width:270px;">
                            <!--div style="width:260px; float:left;">Дата регистрации</div-->
                            <div style="float:left;">
                                    <div class="title">Дата</div>
                                    <input type="text" id="datetime" name="date" style="width: 100px;" <?php if(is_manager()){ echo 'disabled'; } ?>>
                            </div>
                            <div style="float:left; margin-left: 5px;">
                                    <div class="title">Время</div>
                                    <input type="text" id="time" name="time" style="width: 60px;" <?php if(is_manager()){ echo 'disabled'; } ?>>
                            </div>
			</td>
			<td>
                            <!--div style="width:260px; float:left;">Желаемое время</div>
                            <div style="float:left;">
                                    <div class="title">Дата</div>
                                    <input type="text" id="datetime_hope" name="date_hope" style="width: 100px;">
                            </div>
                            <div style="float:left; margin-left: 5px;">
                                    <div class="title">Время</div>
                                    <input type="text" id="time_hope" name="time_hope" style="width: 60px;">
                            </div-->
				<div class="title">Тип работ</div>
				<select name="work_type" id="work_type">
					<option value="" selected="selected">Выбрать</option>
					<?php foreach ($work_types as $one) {
						echo '<option value="'.$one['id'].'">'.$one['name'].'</option>';
					} ?>
				</select>
			</td>
                    </tr>
                    <tr>
			<td>
				<div class="title">Мастер</div>
				<select name="master" id="master">
					<option value="" selected="selected">Выбрать</option>
					<?php foreach ($master as $one) {
						echo '<option value="'.$one['id'].'">'.$one['name'].'</option>';
					} ?>
				</select>
			</td>
			<td>
                            <div <?php if(is_manager()){ echo 'style="display:none;"'; } ?>>
                                    <div class="title">ФИО мастера 1</div>
                                    <select id="master-name" name="master-name" <?php if(is_manager()){ echo 'disabled'; } ?>>
                                            <option value="" selected="selected">Выбрать</option>
                                            <?php foreach($users_master as $one){
                                                    echo '<option value="'.$one['id'].'">'.$one['realname'].'</option>';
                                            } ?>
                                    </select>
                                    <!--div class="title">ФИО мастера 2</div>
                                    <select id="master-name-two" name="master-name-two" <?php if(is_manager()){ echo 'disabled'; } ?>>
                                            <option value="" selected="selected">Выбрать</option>
                                            <?php //foreach($users_master as $one){
                                                    //echo '<option value="'.$one['id'].'">'.$one['realname'].'</option>';
                                            //} ?>
                                    </select>	
                                    <div class="title">ФИО мастера 2</div>
                                    <select id="master-name-th" name="master-name-th" <?php if(is_manager()){ echo 'disabled'; } ?>>
                                            <option value="" selected="selected">Выбрать</option>
                                            <?php //foreach($users_master as $one){
                                                    //echo '<option value="'.$one['id'].'">'.$one['realname'].'</option>';
                                            //} ?>
                                    </select-->										
                            </div>
			</td>
                    </tr>
                    <!--tr>
			<td>
				<div class="title">Город</div>
				<select name="city" id="city">
					<option value="" selected="selected">Выбрать</option>
					<?php /*foreach ($city as $one) {
						echo '<option value="'.$one['id'].'">'.$one['name'].'</option>';
					}*/ ?>
				</select>
			</td>
			<td>
				<div class="title">Населенный пункт</div>
				<select name="city2" id="city2">
					<option value="" selected="selected">Не выбран город</option>
				</select>
			</td>		
                    </tr-->
                    <tr>
			<td>
				<div class="title">Улица</div>
				<input type="text" id="street" name="street">
			</td>
			<td>
				<table style="width:100%; margin:0;">
					<tr>
						<td><div class="title">Дом</div></td>
						<td><div class="title">Корп.</div></td>
						<td><div class="title">Кв.</div></td>
					</tr>
					<tr>
						<td><input type="text" id="house" name="house" style="width: 50px;"></td>
						<td><input type="text" id="corpus" name="corpus" style="width: 50px;"></td>
						<td><input type="text" id="flat" name="flat" style="width: 50px;"></td>
					</tr>
				</table>
			</td>
                    </tr>
                    <tr>
			<td>
				<div class="title">Статус</div>
				<select name="status" id="status">
					<option value="0" selected="selected">Выбрать</option>
					<option value="1">Выполнен</option>
					<option value="2">Отменен</option>
					<option value="3">Отказ</option>
					<option value="4">Отсутствие заказчика</option>
				</select>
			</td>
			<td>
				<div class="title">Сумма заказа</div>
				<input type="text" id="cost" name="cost" <?php if(is_manager()){ echo 'disabled'; } ?>>
			</td>
                    </tr>
                    <tr>
			<td rowspan="2">
				<div class="title">Особые отметки</div>
				<textarea cols="35" rows="8" id="customer-details" name="customer-details" 
                                          style="margin-left: 0px; margin-right: 0px; width: 250px; margin-top: 0px; margin-bottom: 0px; height: 100px; padding: 3px;"></textarea>
			</td>
			<td>
				<div class="title">Телефон заказчика</div>
				<input type="text" class="custom-phone" id="customer-phone" name="customer-phone">
			</td>
                    </tr>
                    <tr>
			<td>
				<div class="title">ФИО заказчика</div>
				<input type="text" id="customer-name" name="customer-name">
			</td>
                    </tr>
                </table>
            </form>
			<!--td>
                            <div id="note_div" <?php //if(is_manager()){ echo 'style="display:none;"'; } ?>>
                                    <div class="title">Примечания</div>
                                    <input type="text" id="note" name="note" <?php //if(is_manager()){ echo 'disabled'; } ?>>
                            </div>				
			</td-->
			<!--td>
				<div class="title">Номер акта</div>
				<input type="text" id="offer-number" name="offer-number" <?php if(is_manager()){ echo 'disabled'; } ?>>
			</td-->
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onClick="ksk_onSearch();">Поиск</button>
        </div>
    </div>
<!--------------------------------------------------------------------------------------->
    <!-- Modal -->
    <div id="myAddModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 96%; margin-left: -48%; top: 3%;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Добавление нового заказа</h3>
        </div>
        <div class="modal-body" style="max-height: 450px;">
            <?php PrintEditForm(false); ?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onClick="ksk_onAdd();">Добавить заказ</button>
        </div>
    </div>
<!--------------------------------------------------------------------------------------->

<script type="text/javascript">
    
    function ksk_onSearch() {
        $('#edit-form-wrapper').hide();
        $("#mySearchModal #form").ajaxSubmit({
            target: "#orders",
            beforeSubmit: showRequest,
            timeout: 3000,
            success: function() {
                showResponse();
            }
        });
        $("#mySearchModal").modal('hide');
    }
    
    function ksk_onAdd() {
        $('#edit-form-wrapper').hide();
        var action = $('#action').val();
        var options = { 
            target: "#output",
            //beforeSubmit: showRequest,
            beforeSubmit: function () { $('#ajax_load').show(); },
            success: showResponse,
            timeout: 3000
        };
                
        if(action == 'add'){
            <?php if($login_user['rang'] != 'admin' && $login_user['rang'] != 'operator'){ ?>
                if($('#myAddModal #datetime_hope').val() == '' || $('#myAddModal #street').val() == '' || $('#myAddModal #customer-phone').val() == '' || ($('#myAddModal #master-name').val() == '')){
                    alert('Перед отправкой заполните все обязательные поля!');
                    return false;
                }else{
                    $('#myAddModal #form').ajaxSubmit(options); 
                    $("#myAddModal").modal('hide');
                    return false;
                }
            <?php }else{ ?>
                if ($('#myAddModal #master-name').val() == '') {
                    alert('Необходимо выбрать мастера');
                    return false;
		}else{
                    $('#myAddModal #form').ajaxSubmit(options); 
                    $("#myAddModal").modal('hide');
                    return false;
                }
            <?php } ?>
        }
    }
   
$(function() {  
	// Телефоны
	$(".custom-phone").mask("+79999999999");

	//Датапикер
	$('#datetime').datepicker({
		format: 'dd.mm.yyyy',
		language: 'ru',
		autoclose: true
	});
	//Датапикер
	$('#datetime_hope').datepicker({
		format: 'dd.mm.yyyy',
		language: 'ru',
		autoclose: true
	});

	$('#filter-datetime').datepicker({
		format: 'dd.mm.yyyy',
		language: 'ru',
		autoclose: true
	});
	
	$('#filter-datetime2').datepicker({
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

	//подгружаем селения под город.
	$('select#city').change(function () {
		var city_id = $('#city').val();
		$.ajax({
			type: "GET",
			url: "/ajax/main.php?action=get_cities&city_id="+city_id,
			success: function(data){ $('select#city2').empty().append($(data)); }
		});
	});	
});


function dofilter(type){
	if(type === 'order-done'){
		var filter_date = $('#filter-datetime-done').val();
		var filter_date2 = $('#filter-datetime2-done').val();
		var filter_master = $('#filter-master-done').val();
		$.ajax({
			type: "GET",
			<?php if($login_user['rang'] != 'manager'){ ?>
				url: "/ajax/main.php?action=get_orders_done&filter_date="+filter_date+"&filter_date2="+filter_date2+"&filter_master="+filter_master,
			<?php }else{ ?>
				url: "/ajax/main.php?action=get_orders_done&user_id=<?=$login_user['id'];?>&place=account&filter_date="+filter_date+"&filter_date2="+filter_date2,
			<?php } ?>
			success: function(data){ $('#orders_done').empty().append($(data)); }
		});			
	}else{
		var filter_date = $('#filter-datetime').val();
		var filter_date2 = $('#filter-datetime2').val();
		var filter_master = $('#filter-master').val();
		$.ajax({
			type: "GET",
			url: "/ajax/main.php?action=get_orders&filter_date="+filter_date+"&filter_date2="+filter_date2+"&filter_master="+filter_master,
			success: function(data){ $('#orders').empty().append($(data)); }
		});	
	}
	return false;	
}


//Отправка формы
$(document).ready(function(){

    // #mySearchModal datepicker
    $('#mySearchModal #datetime, #datetime_hope').datepicker({
            format: 'dd.mm.yyyy',
            language: 'ru',
            autoclose: true
    });

    // #myAddModal datepicker
    $('#myAddModal #datetime, #datetime_hope').datepicker({
            format: 'dd.mm.yyyy',
            language: 'ru',
            autoclose: true
    });
    
    $('#mySearchModalButton').on('click', function() { dosearch(); });

	timer_update();

		var options = { 
		  	target: "#output",
		    beforeSubmit: showRequest,
		    success: showResponse,
		    timeout: 3000
		};
		
		var options_search = { 
		  	target: "#orders",
		    beforeSubmit: showRequest,
		    success: showResponse,
		    timeout: 3000
		};	

	$('#form').submit(function() { 
		var action = $('#action').val();
		if(action == 'add' || action == 'edit'){
			if(action == 'add'){
                            <?php if($login_user['rang'] != 'admin' && $login_user['rang'] != 'operator'){ ?>
				if($('#datetime_hope').val() == '' || $('#street').val() == '' || $('#customer-phone').val() == ''){
                                    alert('Перед отправкой заполните все обязательные поля!');
                                    return false;
				}else{
                                    $(this).ajaxSubmit(options); 
                                    $("#myAddModal").modal('hide');
                                    return false;
				}
                            <?php }else{ ?>
				$(this).ajaxSubmit(options); 
                                $("#myAddModal").modal('hide');
				return false;
                            <?php } ?>
			}else{
				$(this).ajaxSubmit(options); 
                                $('#edit-form-wrapper').hide();
				return false;
			}
		}else if(action == 'search'){
			$(this).ajaxSubmit(options_search);
			return false;
		}
	});

	//Автодополнение
	$('#street, #myAddModal #street, #mySearchModal #street').autocomplete("/ajax/main.php?action=get_streets", {
		width: 206,
		matchContains: true,
		selectFirst: false
	});

});

function timer_update(){
	get_order();
	get_order_done();
	setTimeout(timer_update, 600000);
}

function get_cur_city(city_id, city_id2){
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_cities&city_id="+city_id,
		success: function(data){ 
			$('select#city2').empty().append($(data)); 
			$("select#city2 [value='"+city_id2+"']").attr("selected", "selected");
		}
	});
}

function give_img(id){
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_imgs&id="+id,
		success: function(data){ $('#img_done').empty().append($(data)); }
	});		
}
		
function showRequest(formData, jqForm, options) { 
	var queryString = $.param(formData);
	var action = $('#action').val();
	var rang = $('#rang_user').val();	
		if(action == 'add' || action == 'edit'){
			if(rang == "admin" || rang == "operator") {
			   if ($('#master-name').val() == '') {
			   		alert('Необходимо выбрать мастера');
			   		return false;
			   }else{
			   		$('#ajax_load').show();
			   		return true;
			   }
			}else{
				$('#ajax_load').show();
				return true;
			}
		} else {
			$('#ajax_load').show();
    		return true;
		}
} 
		 
function showResponse(responseText, statusText)  {
	var action = $('#action').val();
	if(action === 'add'){
		get_order();
		get_order_done();
		$('#ajax_load').hide();
		alert("Запись успешно добавлена!");
		clearall();
	}else if(action === 'edit'){
		get_order();
		get_order_done();
		$('#ajax_load').hide();
		doadd();
		alert("Запись успешно обновлена!");
	} else {
            $('#ajax_load').hide();
        }
}
//Закончилась отправка формы


function resetForm(selector) {
	$('#img_done').empty();
	$('.img-files-files').empty();
	$('.img-upload-button').show();
	$(':text, :password, :file, textarea', selector).val('');
	$(':input, select option', selector)
		.removeAttr('checked')
		.removeAttr('selected');
	$('select option:first', selector).attr('selected',true);
	$('#upload_img input[type=hidden]').val('');
}

//Очистить форму
function clearall(){
	resetForm("form[id='form']");
	var action = $('#action').val();
	$('#img_done').empty();
	if(action === 'add' || action === 'edit'){
		doadd();
	}
	return false;
}


//Удалить запись
function deletethis(id){
	if (confirm("Вы уверены что хотите удалить заказ?")) {
		$('#ajax_load').show();
		$.ajax({
			type: "POST",
			data: 'action=delete_order&order_id='+id,
			url: "/ajax/post.php",
			success: function(data){ 
				doadd();
				alert("Запись успешно удалена!"); 
				$('#ajax_load').hide();
			}
		});		   
     	  
    }
    return false;
}

function copythis(id){
	if (confirm("Скопировать заказ?")) {
		$('#ajax_load').show();
		$.ajax({
			type: "POST",
			data: 'action=copy_order&order_id='+id,
			url: "/ajax/post.php",
			success: function(data){ 
				doadd();
				alert("Запись успешно скопированна!"); 
				$('#ajax_load').hide();
			}
		});		   
     	  
    }
    return false;
}

function getOfferNumber(){
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_offer_number",
		success: function(data){ 
			$('#offer-number').empty().val(data); 
		}
	});	
}

//Нажать добавить
function doadd(){
	resetForm("form[id='form']");
	get_order();
	get_order_done();
	$('#action').val('add');
	$('#submit').show();
	$('#submit').val('Сохранить');
	$('#btnadd-form').hide();
	$('#delete-order').hide();
	$('#copy-order').hide();
	$('#search-form').show();
	<?php if(!is_manager()){ ?>
		$('#note_div').show();
		getOfferNumber();
	<?php } ?>
	$('#hope').show();
	//$('#status-bar-add').show();
	$('#status-bar-search').hide();
	$('#status-bar-edit').hide();
	$('#last-search').hide();
	$('#last-post').show();
	$('#upload_img').show();
}

//Нажать на поиск
function dosearch(){
	resetForm("form[id='form']");
	$('#orders').empty();
	$('#action').val('search');
	$('#submit').show();
	$('#submit').val('Найти');
	$('#btnadd-form').show();
	$('#search-form').hide();
	$('#delete-order').hide();
	$('#note_div').hide();
	$('#hope').hide();
	$('#copy-order').hide();
	//$('#status-bar-add').hide();
	$('#status-bar-search').show();
	$('#last-search').show();
	$('#last-post').hide();
	$('#upload_img').hide();
}

//Загружаем последние 25 записей.
function get_order(){
	<?php if(!is_manager()){ ?>
		$.ajax({
			type: "GET",
			url: "/ajax/main.php?action=get_orders",
			success: function(data){ $('#orders').empty().append($(data)); }
		});	
		getOfferNumber();
	<?php }else{ ?>
		$.ajax({
				type: "GET",
				url: "/ajax/main.php?action=get_orders&user_id=<?=$login_user['id'];?>",
				success: function(data){ $('#orders').empty().append($(data)); }
		});		
	<?php } ?>
}

function get_order_done(){
	<?php if(!is_manager()){ ?>
		$.ajax({
			type: "GET",
			url: "/ajax/main.php?action=get_orders_done",
			success: function(data){ $('#orders_done').empty().append($(data)); }
		});	
	<?php }else{ ?>
		$.ajax({
				type: "GET",
				url: "/ajax/main.php?action=get_orders_done&user_id=<?=$login_user['id'];?>",
				success: function(data){ $('#orders_done').empty().append($(data)); }
		});		
	<?php } ?>
}

//Редактирование формы
function editpost(id, type){
	$("#orders table tr td").removeClass("activetd");
	$("#orders table tr#tr_id_"+id+" td").addClass("activetd");
	$("#orders_done table tr td").removeClass("activetd");
	$("#orders_done table tr#tr_id_"+id+" td").addClass("activetd");
	$('#ajax_load').show();	
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_edit_order&id="+id,
		success: function(data){
			give_img(id);
			$('#ajax_load').hide();
			resetForm("form[id='form']");
			var oData = JSON.parse(data);
			$("#datetime").val(oData.time_date);
			$("#time").val(oData.time_time);
			$("#datetime_hope").val(oData.time_date_hope);
			$("#time_hope").val(oData.time_time_hope);
			$("#offer-number").val(oData.offer_number);
			if(oData.work_type == 0){
				$("select#work_type :first").attr("selected", "selected");
			}else{
				$("select#work_type [value='"+oData.work_type+"']").attr("selected", "selected");
			}			
			if(oData.master == 0){
				$("select#master :first").attr("selected", "selected");
			}else{
				$("select#master [value='"+oData.master+"']").attr("selected", "selected");
			}
			if(oData.master_name == 0){
				$("select#master-name :first").attr("selected", "selected");
			}else{
				$("select#master-name [value='"+oData.master_name+"']").attr("selected", "selected");
			}
			$("#cost").val(oData.cost);
			$("#customer-name").val(oData.client_fio);
			$("#customer-name2").val(oData.client_fio2);
			$("#customer-name3").val(oData.client_fio3);
			$("#customer-phone").val(oData.phone);
			$("#customer-phone2").val(oData.phone2);
			$("#customer-phone3").val(oData.phone3);
			$("#sms2").val(oData.sms2);
			$("#sms3").val(oData.sms3);
			//Добавить выбор города
			if(oData.city_id == 0){
				$("select#city :first").attr("selected", "selected");
				$("select#city2 :first").attr("selected", "selected");
			}else{
				$("select#city [value='"+oData.city_id+"']").attr("selected", "selected");
				get_cur_city(oData.city_id, oData.city_id2);
			}				
			//Добавить второе поле - населенный пункт
			$("#street").val(oData.street);
			$("#house").val(oData.house);
			$("#corpus").val(oData.corpus);
			$("#flat").val(oData.flat);
			$("#customer-details").val(oData.details);
			$("#note").val(oData.note);
			if(oData.status == 0){
				$("select#status :first").attr("selected", "selected");
			}else{
				$("select#status [value='"+oData.status+"']").attr("selected", "selected");
			}			
			$("#img").val(oData.img);
			$("#img1").val(oData.img1);
			$("#img2").val(oData.img2);
			$("#img3").val(oData.img3);	
			$("#img4").val(oData.img4);
			$("#img5").val(oData.img5);
			$("#img6").val(oData.img6);
			$("#img7").val(oData.img7);						

			//Работа с формой
			$('#action').val('edit');
			$('#order_id').val(oData.id);
			//$('#status-bar-add').hide();
			$('#status-bar-search').hide();
			$('#status-bar-edit').show();
			$('#upload_img').show();
                        
                        // Кол-во img
                        img_count = 0;
                        if ($("#img").val() != '') img_count++;
                        if ($("#img1").val() != '') img_count++;
                        if ($("#img2").val() != '') img_count++;
                        if ($("#img3").val() != '') img_count++;
                        if ($("#img4").val() != '') img_count++;
                        if ($("#img5").val() != '') img_count++;
                        if ($("#img6").val() != '') img_count++;
                        if ($("#img7").val() != '') img_count++;
                        
                        //alert('img_count='+img_count);
                        if (img_count < 7) {
                            $("#upload").show();
                            for(i=1;i <= img_count;i++) 
                                $("#upload"+i).show();
                            
                            for(i=img_count+1;i < 8;i++) 
                                $("#upload"+i).hide();
                        } else {
                            $("#upload").show();
                            for(i=1;i < 8;i++) 
                                $("#upload"+i).show();
                        }
			
			if(type != 'manager'){
				$('#submit').show();
				$('#submit').val('Обновить запись');
				$('#delete-order').show();
				$('#delete-order').attr('onClick','deletethis('+oData.id+')');
			}else{
				$('#submit').hide();
				$('#delete-order').hide();
			}

			$('#copy-order').show();
			$('#copy-order').attr('onClick','copythis('+oData.id+')');

			$('#btnadd-form').show();
			$('#search-form').show();
                        
                    $('#edit-form-wrapper').show();
		}
	});
}

$(function(){
		var btnUpload=$('#upload');
		var status=$('#status');
		new AjaxUpload(btnUpload, {
			action: '/ajax/upload-file.php',
			name: 'uploadfile',
			onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                   // extension is not allowed 
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Загрузка...');
		},
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			//Add uploaded file to list
			var obj = $.parseJSON(response);
			if(obj.sta==="success"){
				var name = obj.name;
				$('#img').val(name);
				$('<li></li>').appendTo('#files').html(' Файл '+file+' успешно загружен!').addClass('success');
				$('#upload').hide();
                                $("#upload1").show();
                                for(i=2;i < 8;i++) 
                                    $("#upload"+i).hide();
			} else{
				$('<li></li>').appendTo('#files').text(file).addClass('error');
			}
		}
	});
});

$(function(){
		var btnUpload=$('#upload1');
		var status=$('#status1');
		new AjaxUpload(btnUpload, {
			action: '/ajax/upload-file.php',
			name: 'uploadfile1',
			onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                   // extension is not allowed 
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Загрузка...');
		},
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			//Add uploaded file to list
			var obj = $.parseJSON(response);
			if(obj.sta==="success"){
				var name = obj.name;
				$('#img1').val(name);
				$('<li></li>').appendTo('#files1').html(' Файл '+file+' успешно загружен!').addClass('success');
				$('#upload1').hide();
                                $("#upload2").show();
                                for(i=3;i < 8;i++) 
                                    $("#upload"+i).hide();
			} else{
				$('<li></li>').appendTo('#files1').text(file).addClass('error');
			}
		}
	});
});

$(function(){
		var btnUpload=$('#upload2');
		var status=$('#status2');
		new AjaxUpload(btnUpload, {
			action: '/ajax/upload-file.php',
			name: 'uploadfile2',
			onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                   // extension is not allowed 
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Загрузка...');
		},
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			//Add uploaded file to list
			var obj = $.parseJSON(response);
			if(obj.sta==="success"){
				var name = obj.name;
				$('#img2').val(name);
				$('<li></li>').appendTo('#files2').html(' Файл '+file+' успешно загружен!').addClass('success');
				$('#upload2').hide();
                                $("#upload3").show();
                                for(i=4;i < 8;i++) 
                                    $("#upload"+i).hide();
			} else{
				$('<li></li>').appendTo('#files2').text(file).addClass('error');
			}
		}
	});
});

$(function(){
		var btnUpload=$('#upload3');
		var status=$('#status3');
		new AjaxUpload(btnUpload, {
			action: '/ajax/upload-file.php',
			name: 'uploadfile3',
			onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                   // extension is not allowed 
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Загрузка...');
		},
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			//Add uploaded file to list
			var obj = $.parseJSON(response);
			if(obj.sta==="success"){
				var name = obj.name;
				$('#img3').val(name);
				$('<li></li>').appendTo('#files3').html(' Файл '+file+' успешно загружен!').addClass('success');
				$('#upload3').hide();
                                $("#upload4").show();
                                for(i=5;i < 8;i++) 
                                    $("#upload"+i).hide();
			} else{
				$('<li></li>').appendTo('#files3').text(file).addClass('error');
			}
		}
	});
});

$(function(){
		var btnUpload=$('#upload4');
		var status=$('#status4');
		new AjaxUpload(btnUpload, {
			action: '/ajax/upload-file.php',
			name: 'uploadfile4',
			onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                   // extension is not allowed 
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Загрузка...');
		},
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			//Add uploaded file to list
			var obj = $.parseJSON(response);
			if(obj.sta==="success"){
				var name = obj.name;
				$('#img4').val(name);
				$('<li></li>').appendTo('#files4').html(' Файл '+file+' успешно загружен!').addClass('success');
				$('#upload4').hide();
                                $("#upload5").show();
                                for(i=6;i < 8;i++) 
                                    $("#upload"+i).hide();
			} else{
				$('<li></li>').appendTo('#files4').text(file).addClass('error');
			}
		}
	});
});

$(function(){
		var btnUpload=$('#upload5');
		var status=$('#status5');
		new AjaxUpload(btnUpload, {
			action: '/ajax/upload-file.php',
			name: 'uploadfile5',
			onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                   // extension is not allowed 
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Загрузка...');
		},
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			//Add uploaded file to list
			var obj = $.parseJSON(response);
			if(obj.sta==="success"){
				var name = obj.name;
				$('#img5').val(name);
				$('<li></li>').appendTo('#files5').html(' Файл '+file+' успешно загружен!').addClass('success');
				$('#upload5').hide();
                                $("#upload6").show();
                                for(i=7;i < 8;i++) 
                                    $("#upload"+i).hide();
			} else{
				$('<li></li>').appendTo('#files5').text(file).addClass('error');
			}
		}
	});
});

$(function(){
		var btnUpload=$('#upload6');
		var status=$('#status6');
		new AjaxUpload(btnUpload, {
			action: '/ajax/upload-file.php',
			name: 'uploadfile6',
			onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                   // extension is not allowed 
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Загрузка...');
		},
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			//Add uploaded file to list
			var obj = $.parseJSON(response);
			if(obj.sta==="success"){
				var name = obj.name;
				$('#img6').val(name);
				$('<li></li>').appendTo('#files6').html(' Файл '+file+' успешно загружен!').addClass('success');
				$('#upload6').hide();
                                $("#upload7").show();
			} else{
				$('<li></li>').appendTo('#files6').text(file).addClass('error');
			}
		}
	});
});

$(function(){
		var btnUpload=$('#upload7');
		var status=$('#status7');
		new AjaxUpload(btnUpload, {
			action: '/ajax/upload-file.php',
			name: 'uploadfile7',
			onSubmit: function(file, ext){
			 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
                   // extension is not allowed 
				status.text('Only JPG, PNG or GIF files are allowed');
				return false;
			}
			status.text('Загрузка...');
		},
		onComplete: function(file, response){
			//On completion clear the status
			status.text('');
			//Add uploaded file to list
			var obj = $.parseJSON(response);
			if(obj.sta==="success"){
				var name = obj.name;
				$('#img7').val(name);
				$('<li></li>').appendTo('#files7').html(' Файл '+file+' успешно загружен!').addClass('success');
				$('#upload7').hide();
			} else{
				$('<li></li>').appendTo('#files7').text(file).addClass('error');
			}
		}
	});
});
</script>
<?php include template("footer"); ?>