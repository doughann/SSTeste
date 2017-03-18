var wishlist_combo = {
	'add': function(product_id) {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: 'product_id=' + product_id,
			dataType: 'json',
			success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();
				if (json['success']) {
					$('#notification').append('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					$('.success').fadeIn('slow');
				}

				if (json['information']) {
					$('#notification').append('<div class="information" style="display: none;">' + json['information'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				}

				$('#wishlist-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		});
	}
}

var cart_combo = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			success: function(json) {
				$('#cart > button').button('reset');
				if (json['redirect']) {
					$('#notification').append('<div class="attention" style="display: none;">' + json['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					$('.attention').fadeIn('slow');
					$('html, body').animate({ scrollTop: 0 }, 'slow');
				}
				if (json['success']) {
					$('#notification').append('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
					$('.success').fadeIn('slow');

					$('#cart-total').html(json['total']);

					$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}