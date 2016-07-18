<?php include template("header"); ?>
<div class="info-block">
    <a href="/" class="btn">Главная</a>
    <a href="/operator.php?action=new_order" class="btn">Добавить заказ</a>
    <a href="/logout.php" class="btn">Выйти</a>
</div>
<div class="info-block" style="float: left; width: 100%; margin-bottom: 0px;">
	<h2>Написать Сообщение</h2>
	<textarea rows="3" style="width: 520px;" name="message" id="text-message"></textarea><br/>
	<select id="user" name="user">
		<option value="all">Всем</option>
		<?php foreach ($user as $one) { ?>
			<option value="<?=$one['id'];?>"><?=$one['realname'];?>(<?=$one['username'];?>)</option>
		<?php } ?>
	</select>
	<input type="submit" id="submit" value="Отправить" name="commit" class="btn">
</div>
<div class="info-block" style="float: left; width: 100%;">
    <div style="float:left; width: 25%;">
        <h3>Контакты</h3>
        <div id="contacts_messages">&nbsp;</div>
    </div>
    <div style="float:left; width: 70%;">
        <div>
            <h3 id="title_contact_messages">&nbsp;</h3>
            <button id="button-all-messages" class="button-all-messages">Показать всю переписку</button>
        </div>
        <div id="contact_messages">&nbsp;</div>
    </div>
</div>

<script>
function get_contacts_messages(){
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_contacts_messages",
		success: function(data){ $('#contacts_messages').empty().append($(data)); }
	});		
}

function get_contact_messages(id, username, last_message=1){
        get_contacts_messages();
        
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_contact_messages&id=" + id + "&last_message=" + last_message,
                dataType: 'json',
		success: function(data){ 
                    $('#title_contact_messages').html('Переписка с '+username); 
                    $('#contact_messages').empty().append($(data.html));
                    $('#contact_messages').scrollTop($('#contact_messages').prop("scrollHeight"));
                    $("html,body").scrollTop($('#title_contact_messages').offset().top);
                    $('#contacts_messages ul li.li-contacts-messages-active').removeClass('li-contacts-messages-active');
                    $("#href_contact_messages_" + id).addClass('li-contacts-messages-active');
                    if (data.hide_msg) {
                        document.getElementById("button-all-messages").onclick = function () { get_contact_messages(id, username, 0); };
                        $("#button-all-messages").show();
                    } else {
                        $("#button-all-messages").hide();
                    }
                }
	});		
}

function get_messages(){
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_admin_messages",
		success: function(data){ $('#messages').empty().append($(data)); }
	});		
}

function get_all(id){
	$.ajax({
		type: "GET",
		url: "/ajax/main.php?action=get_admin_one_message&id="+id,
		success: function(data){ $('#one_message').empty().append($(data)); }
	});
        //one_message
}

$('#submit').click(function(){
	var message = $('#text-message').val();
	var select = $('#user').val();
	if(message !== ''){
		$.ajax({
			type: "POST",
			data: 'action=add_admin_message&message='+message+'&user='+select,
			url: "/ajax/post.php",
			success: function(data){ 
				alert("Сообщение успешно отправлено!"); 
				//get_messages();
                                if (select !== 'all') {
                                    $("#href_contact_messages_" + select + " a").click();
                                }
			}
		});
	}else{
		alert('Сообщение пустое.');
	}	  
});
//get_messages();
get_contacts_messages();
</script>
<?php include template("footer"); ?>