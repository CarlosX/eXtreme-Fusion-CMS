$(function() {

	$('#messages_form form').submit(function() {

		var new_message = $('#message_subject').length;

		if (new_message) {
			new_message = $('#message_subject').val();
			if (new_message == '') {
				alert('Nie podano tematu wiadomości');
				return false;
			}
		} else {
			new_message = '';
		}

		var to_user = $('input[name*="to"]', this).val();

		$.post(addr_site+'pages/ajax/messages.php', {
			message: $('input[name*="message"]', this).val(),
			to: to_user,
			item_id: $('input[name*="item_id"]', this).val(),
			send: true,
			message_subject: new_message,
			action: 'send'
		},
		function(data) {
			$('input[name*="message"]').val('');
			if (new_message) {
				$('#message_subject').parent().remove();
				$('input[name*="item_id"]').val(data);
			}
			// co to? zakomentować
			//else
			//{
			//	$('#form_request').html(data);
			//}
			refresh_pw();
		});

		return false;
	});

	// Wyszukiwanie adresata wiadomości
	$('#send_to').after('<div id="defenders"></div>');

	$('#send_to').keyup(function() {
		var value = $(this).val();
		var $object = $(this);
		$.ajax({
			url: addr_site+'ajax/search_users.php',
			type: 'POST',
			dataType: 'json',
			data: {to: value},
			success: function(oJsonObject, sTextstatus, oXMLHttpRequest){
				$('#send_to').css({'borderRadius':'5px 5px 0 0','border-bottom-width':'0'});
				if (parseInt(oJsonObject.status) == 0) {
					var users = '<ul>';
					for (i = 0; i < oJsonObject.users.length; i++)
					{
						var users = users + '<li class="defender" id="def-'+ oJsonObject.users[i].id +'">' + oJsonObject.users[i].username + '</li>';
					}
					var users = users + '</ul>';
					$('#defenders').html(users);
				} else if (parseInt(oJsonObject.status) == 1) {
					$('#defenders').html('<div class="defender_error">Brak wyników wyszukiwania</div>');
				} else {
					$('#defenders').html('<div class="defender_error">'+oJsonObject.error_msg+'</div>');
				}
			},
			error: function(oXMLHttpRequest, sTextstatus, oErrorThrown) {
				alert(sTextstatus);
			},
		});
	});
	// end of Wyszukiwanie adresata wiadomości

	// Wybieranie adresata wiadomości
	$('body').on('click', '.defender', function() {
		var id = $(this).attr('id').split('-')[1];
		var username = $(this).text();

		$('#message_to').val(id);
		$('#defenders').before('<div id="defender_user">'+username+' <img src="../admin/templates/images/icons/cross.png" alt="Cross"></div>');

		$('#defenders').html('');
		$('#send_to').hide();
	});
	// end of Wybieranie adresata wiadomości

	// Odświeżanie okna rozmowy

	function refresh_pw() {
		var posts = $('.pw_message_item').length;
		var item_id = $('input[name*="item_id"]').val();

		$.ajax({
			url: addr_site+'pages/ajax/messages.php', data: 'item_id='+item_id, type: 'GET', success: function (html) {
				$('#messages_frame section').html(html);
				setTimeout(function(){
					var posts2 = $('.pw_message_item').length;
					if (posts != posts2) {
						var scrollh = $('#messages_frame section').height();
						$('#messages_frame').animate({ scrollTop: scrollh }, 800);
					}
				}, 400);
			}, error: function(){
				$('#messages_frame section').html('Wystąpił błąd! Odśwież stronę.');
			}
		});
	}

	var refresh = false;
	$('#messages_frame').hover(function() {
		if (!refresh) {
			refresh_pw();
			refresh = true;
		}
	}, function() {
		refresh = false;
	});

	setInterval(function() {
		refresh_pw();
	}, 30000);

	refresh_pw();

	// end of Odświeżania okna rozmowy
});