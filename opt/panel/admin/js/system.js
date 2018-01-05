$(document).ready(function(){$('#page_wrap').delay(250).slideDown(500);$('#menu-wrap li a:not(#droplink)').click(function(){$('#page_wrap').slideUp(500);$(this).delay(599,function(){window.location=$(this).attr('href')});return false});$('li.dropdown').hover(function() { $('ul', this).css('display', 'block'); },function() { $('ul', this).css('display', 'none'); });});function get_player_list(){$.post("online_users.php",{"":""},function(data){$("#online").html(data)});setTimeout(function(){get_player_list()},1000)}function kick_player(nick){jPrompt('Provide a reason:','','Kick Player',function(r){if(r!=null)$.post("user_process.php",{"act":"kick","reason":r,"nick":nick})})}function power_control(action){$.post("actions.php",{"act":action},function(data){$("#output").html(data)})}var play=true;var count=0;function rotate(){var elem4=document.getElementById('div4');elem4.style.MozTransform='scale(0.5) rotate('+count+'deg)';elem4.style.WebkitTransform='scale(0.5) rotate('+count+'deg)';if(count==360){count=0}count+=45;if(play)setTimeout(rotate,100)}function ban_player(nick,ip){jAlert('Feature Not Working Yet (must do in-game)','Coming Soon')}var item_list="";$().ready(function(){$.post("user_process.php",{"act":"items"},function(data){item_list=data},"json")});var inv_show=0;var cur_name="";var inv_c=0;function inv_player(nick){inv_show=1;inv_c++;cur_name=nick;inv_player_rep(nick,inv_c)}function inv_player_rep(nick,c){if(inv_show!=0&&cur_name==nick&&inv_c==c){$.post("user_process.php",{"act":"inventory","nick":nick},function(data){if(inv_show!=0){$("#inventory").html(data);$("#user").html(nick);$("#online_wrap").fadeOut(function(){$("#inventory_wrap").fadeIn();$("#item_complete").autocomplete(item_list)})}});setTimeout(function(){if(inv_show!=0){inv_player_rep(nick,c)}},2000)}}function hide_inv(){inv_show=0;$("#inventory_wrap").fadeOut(function(){$("#online_wrap").fadeIn()})}function remove_slot(nick,slot,amount,item_inf){jConfirm('Are you sure you want to remove <b>'+amount+'</b> "<b>'+item_inf+'</b>" from slot <b>'+slot+'</b>?','Please confirm action!',function(r){if(r){$.post("user_process.php",{"act":"rem_slot","nick":nick,"slot":slot,"amount":amount},function(data){if(data=="1"){$.jGrowl("Item removed from "+nick+"'s inventory!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}else{$.jGrowl("Could not remove item from "+nick+"'s inventory!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}else{$.jGrowl("Action cancelled!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}function give_item(){var nick=cur_name;var item_name=$("#item_complete").val();var amount=$("#item_amount").val();$("#item_complete").val("");$("#item_amount").val("");if(parseInt(amount)!=0&&item_name!=""){jConfirm('Are you sure you want to give <b>'+amount+'</b> "<b>'+item_name+'</b>" to '+nick+'!','Please confirm action!',function(r){if(r){$.post("user_process.php",{"act":"give","nick":nick,"item":item_name,"amount":amount},function(data){if(data=="1"){$.jGrowl("Item given to "+nick+"!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}else{$.jGrowl("Could not give item to "+nick+"!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}else{$.jGrowl("Action cancelled!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}else{$.jGrowl("Provide item name and quantity!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}}function delete_user(nick,id){jConfirm('Delete '+nick+'\'s from users list!','Please confirm action!',function(r){if(r){$.post("edit_user.php?save=2&uid="+id,{"":""},function(data){if(data=="1"){$.jGrowl("Deleted "+nick+" from users list!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}});setTimeout(function(){$.jGrowl("Reloading users list!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}});setTimeout(function(){location.href="users.php"},900)},1000)}else{$.jGrowl("Could not delete "+nick+"!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}else{$.jGrowl("Action cancelled!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}function clear_inv(){var nick=cur_name;jConfirm('Clear '+nick+'\'s inventory ( Action cannot be reversed )!','Please confirm action!',function(r){if(r){$.post("user_process.php",{"act":"clear_inv","nick":nick},function(data){if(data=="1"){$.jGrowl("Cleared "+nick+"'s inventory!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}else{$.jGrowl("Could not clear "+nick+"'s inventory!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}else{$.jGrowl("Action cancelled!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}function msg_player(nick){jPrompt('Type a message:','','Send message to player!',function(r){if(r!=null){$.post("user_process.php",{"act":"msg","nick":nick,"message":r});$.jGrowl("Sent message to "+nick+"!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}else{$.jGrowl("Action cancelled!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}function delete_group(groupname,id){jConfirm('Delete '+groupname+'\'s from groups list!','Please confirm action!',function(r){if(r){$.post("edit_group.php?save=2&gid="+id,{"":""},function(data){if(data=="1"){$.jGrowl("Deleted "+groupname+" from group list!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}});setTimeout(function(){$.jGrowl("Reloading group list!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}});setTimeout(function(){location.href="groups.php"},900)},1000)}else{$.jGrowl("Could not delete "+groupname+"!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}else{$.jGrowl("Action cancelled!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}function default_group(groupname,id){jConfirm('Set '+groupname+' as default group!','Please confirm action!',function(r){if(r){$.post("edit_group.php?save=3&gid="+id,{"":""},function(data){if(data=="1"){$.jGrowl(groupname+" is now the default group!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}});setTimeout(function(){$.jGrowl("Reloading group list!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}});setTimeout(function(){location.href="groups.php"},900)},1000)}else{$.jGrowl("Could not set "+groupname+" as default group!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}else{$.jGrowl("Action cancelled!",{glue:'before',speed:500,animateOpen:{height:"show",width:"show"},animateClose:{height:"hide",width:"show"}})}})}

function ajaxOpenPage(url, id)
{
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET",url,false);
	xmlhttp.send(null);
	document.getElementById(id).innerHTML=xmlhttp.responseText;
	eval();
}

function ajaxPostForm(url, id, form)
{
	var i, postvar
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("POST",url,false);

	for (i=0; i < form.elements.length; i++)
	{
		object = form.elements[i];
		if(object.name != "")
		{
			if(postvar == "")
			{
				postvar = object.name +"=" + object.value;
			} else {
				postvar += "&" + object.name +"=" + object.value;
			}
		}
	}

	xmlhttp.send(postvar);
	document.getElementById(id).innerHTML=xmlhttp.responseText;
	eval();
}
