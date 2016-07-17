<?php include template("header"); ?>
<div class="info-block">
        <a href="/" class="btn">Главная</a>
	<?php if(is_manager()){ ?> <a href="/operator.php?action=new_order" class="btn">Добавить заказ</a> <?php } ?>
	<?php if($login_user['rang'] == 'master'){ ?> <a href="/account.php" class="btn">Профиль</a> <?php } ?>
	<a href="/logout.php" class="btn">Выйти</a>
</div>
<div class="info-block" style="float: left; width: 100%; margin-top:10px;">
	<h2>Сообщения</h2>
	<!--div id="messages" style="height:300px; width: 520px; overflow-y: auto;"></div-->
	<div id="messages" style="width: 95%;"></div>
	<textarea rows="3" style="width: 620px;" name="message" id="text-message"></textarea>
	<input type="submit" id="submit" value="Задать вопрос" name="commit" class="btn">
</div>
<script>

function get_messages(){
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_contact_messages&id=<?php echo $login_user['id']; ?>",
		success: function(data){ $('#messages').empty().append($(data)); }
	});		
}

$('#submit').click(function(){
	var message = $('#text-message').val();
	if(message !== ''){
		$.ajax({
			type: "POST",
			data: 'action=add_message&message='+message,
			url: "/ajax/post.php",
			success: function(data){ 
				alert("Сообщение успешно отправлено!"); 
				get_messages();
				$('#text-message').val('');
			}
		});
	}else{
		alert('Сообщение пустое.');
	}	  
});
get_messages();
</script>
<?php include template("footer"); ?>