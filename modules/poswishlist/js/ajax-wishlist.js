var wishlistProductsIds = [];
$(document).ready(function(){	
	posCopyLink();
});

function posCopyLink() {
    var copyText = $('#posCopyLink'),
	    copied_text = copyText.data('text-copied'),
	    copy_text = copyText.data('text-copy');
	copyText.click(function() {
	    $('.js-to-clipboard').select();
	    document.execCommand('copy');
        copyText.text(copied_text);
        setTimeout(function(){
        	copyText.text(copy_text);
        }, 5000);
	})
}

function WishlistCart(id, action, id_product, id_product_attribute, quantity, id_wishlist, product_name , product_image)
{
	$.ajax({
		type: 'GET',
		url: baseDir + 'modules/poswishlist/cart.php?rand=' + new Date().getTime(),
		headers: { "cache-control": "no-cache" },
		async: true,
		cache: false,
		data: 'action=' + action + '&id_product=' + id_product + '&quantity=' + quantity + '&token=' + static_token + '&id_product_attribute=' + id_product_attribute + '&id_wishlist=' + id_wishlist,
		success: function(data)
		{	
			if (action == 'add')
			{	
				if (isLogged == true) {

                    $('.wishlist-top-count').html(data);
                    $('#qmwishlist-count').html(data);

					var html = '';
					html += '<div class="modal fade" id="wishlistModal">';
					html += '<div class="modal-dialog"><div class="modal-content">';
						html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons close">close</i></button>';
						html += '<div class="modal-body">';
							html += '<div class="wishlist-content">';
								html += '<img src="' + product_image + '" alt="' + product_name + '" />';
								html += '<div class="wishlist-data">';
									html += '<h5>' + product_name + '</h5>';
									html += added_to_wishlist;
								html += '</div>'; 
							html += '</div>'; 
							html += '<a class="btn-primary wishlist-timer" href="' + wishlist_url + '"><span></span>' + wishlist_text + '</a>';
						html += '</div>';
					html += '</div></div></div>';
					$("body").append(html);
					$('.quickview').modal('hide')
					$('#wishlistModal').modal('show');
					$("body").removeClass('modal-open');
					$(".modal-backdrop.in").hide();
					var timer = setTimeout(function(){
	                    $('#wishlistModal').remove();
	                }, 5000);
	                $("#wishlistModal .modal-content").hover(
	                    function(){
	                        clearTimeout(timer);
	                        $('#wishlistModal a.btn-primary').removeClass('wishlist-timer');
	                    }, function(){
	                        $('#wishlistModal a.btn-primary').addClass('wishlist-timer');
	                        timer= setTimeout(function() { 
	                            $('#wishlistModal').remove();
	                        }, 5000);
	                    }
	                );
					$('#wishlistModal').on('hidden.bs.modal', function () {
			        	$('#wishlistModal').remove();
			        	clearTimeout(timer);
			      	});
				}else{
					var html = '';
					html += '<div class="modal fade" id="wishlistModalLogin">';
					html += '<div class="modal-dialog"><div class="modal-content">';
						html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons close">close</i></button>';
						html += '<div class="modal-body">';
						html += loggin_required;
						html += '<a class="btn-primary" href="'+ loggin_url +'" class="login_text">'+ loggin_text +'</a>'
						html += '</div>';
					html += '</div></div></div>';
					$("body").append(html);
					$('.quickview').modal('hide');
					$('#wishlistModalLogin').modal('show');

					$('#wishlistModalLogin').on('hidden.bs.modal', function () {
			        	$('#wishlistModalLogin').remove();
			      	});
				}
			}
			
			if($('#' + id).length != 0)
			{
				$('#' + id).slideUp('normal');
				document.getElementById(id).innerHTML = data;
				$('#' + id).slideDown('normal');
			}
		}
	});
}

/**
* Delete product wish list
*
* @return void
*/
function deleteProductWishlist(id_product, id_product_attribute)
{
	$.ajax({
		type: 'GET',
		async: true,
		url: baseDir + 'module/poswishlist/mywishlist?rand=' + new Date().getTime() + '&action=delete',
		headers: { "cache-control": "no-cache" },
		data: 'id_product=' + id_product + '&id_product_attribute=' + id_product_attribute,
		cache: false,
		dataType: 'json',
		success: function(data)
		{	
			$('#wlp_' + data.id_product + '_' + data.id_product_attribute).fadeOut('fast');
			$('.wishtlist_top .cart-wishlist-number').html(data.current_number);
		}
	});
}
