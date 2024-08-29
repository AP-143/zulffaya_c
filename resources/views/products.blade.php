<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Product List</title>
	<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<style>
		body {
			font-family: 'Roboto', sans-serif;
			background-color: #f5f5f5;
			margin: 0;
			padding: 0;
			color: #333;
		}

		footer,
		footer a,
		footer p,
		footer h4,
		footer ul,
		footer li {
			color: black !important;
		}

		.navbar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 10px 20px;
			background-color: white;
			color: black;
			position: fixed;
			width: 100%;
			top: 0;
			left: 0;
			z-index: 1000;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		}

		.navbar .logo {
			font-family: 'Pacifico', cursive;
			font-size: 1.5em;
		}

		.navbar .menu {
			display: flex;
			align-items: center;
		}

		.navbar .menu a {
			color: black;
			text-decoration: none;
			margin-left: 20px;
			font-size: 1em;
		}

		.navbar .menu a:hover {
			text-decoration: underline;
		}

		.navbar .menu .cart {
			position: relative;
			margin-right: 20px;
		}

		.navbar .menu .cart .badge {
			position: absolute;
			top: -5px;
			right: -10px;
			background-color: red;
			color: white;
			border-radius: 50%;
			padding: 2px 5px;
			font-size: 0.8em;
			display: none;
		}

		.navbar .menu .logout {
			margin-right: 40px;
		}

		.navbar .menu .orders {
			margin-right: 20px;
			color: black;
			text-decoration: none;
			font-size: 1em;
		}

		.navbar .menu .orders:hover {
			text-decoration: underline;
		}

		.container {
			max-width: 1400px;
			margin: 100px auto 20px;
			padding: 20px;
			background-color: #fff;
			border-radius: 10px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}

		.product-list {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
			gap: 20px;
		}

		.product-card {
			display: flex;
			flex-direction: column;
			align-items: center;
			background-color: #fff;
			border-radius: 10px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			overflow: hidden;
			transition: transform 0.3s, box-shadow 0.3s;
			position: relative;
		}

		.product-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}

		.product-image {
			width: 100%;
			height: 450px;
			object-fit: cover;
		}

		.product-actions {
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 10px;
			width: 100%;
			box-sizing: border-box;
			opacity: 0;
			transition: opacity 0.3s;
		}

		.product-card:hover .product-actions {
			opacity: 1;
		}

		.product-actions button {
			background-color: #333;
			color: white;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s;
		}

		.product-actions button:hover {
			background-color: #555;
		}

		.product-stock {
			color: #333;
			font-size: 0.9em;
			position: absolute;
			bottom: 10px;
			right: 10px;
		}

		.product-details {
			text-align: left;
			padding: 15px;
			width: 100%;
		}


		.product-name {
			display: block;
			font-size: 1.1em;
			margin-bottom: 5px;
		}

		.product-price {
			color: #e74c3c;
			font-size: 0.9em;
			margin: 0;
		}

		.cart-sidebar {
			position: fixed;
			right: 0;
			top: 0;
			width: 300px;
			height: 100%;
			background-color: white;
			box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
			transform: translateX(100%);
			transition: transform 0.3s ease-in-out;
			z-index: 1001;
			padding: 20px;
			overflow-y: auto;
		}

		.cart-sidebar.open {
			transform: translateX(0);
		}

		.cart-sidebar .close-btn {
			position: absolute;
			top: 10px;
			right: 10px;
			cursor: pointer;
			font-size: 1.5em;
		}

		.cart-item {
			display: flex;
			align-items: center;
			margin-bottom: 15px;
			position: relative;
		}

		.cart-item img {
			width: 50px;
			height: 50px;
			object-fit: cover;
			margin-right: 10px;
			border-radius: 5px;
		}

		.cart-item-details {
			flex-grow: 1;
		}

		.cart-item-details .item-name {
			font-size: 1em;
			margin: 0;
			font-weight: bold;
		}

		.cart-item-details .item-price {
			font-size: 0.9em;
			color: #e74c3c;
			margin: 5px 0;
		}

		.item-quantity {
			display: flex;
			align-items: center;
			border: 1px solid #ddd;
			border-radius: 5px;
			overflow: hidden;
			width: fit-content;
		}

		.item-quantity button {
			background-color: white;
			color: #333;
			border: none;
			padding: 5px 10px;
			cursor: pointer;
			font-size: 1.2em;
			width: 30px;
			height: 30px;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.item-quantity span {
			font-size: 1em;
			padding: 0 10px;
			display: flex;
			align-items: center;
			justify-content: center;
			width: 30px;
			height: 30px;
			background-color: #f9f9f9;
		}

		.remove-item {
			position: absolute;
			right: 0;
			top: 50%;
			transform: translateY(-50%);
			background: none;
			border: none;
			cursor: pointer;
			color: #e74c3c;
			font-size: 1.2em;
		}

		.cart-total {
			font-size: 1.2em;
			margin-top: 20px;
			text-align: right;
			font-weight: bold;
		}

		.cart-actions {
			display: flex;
			justify-content: flex-end;
			margin-top: 20px;
		}

		.cart-actions button {
			background-color: #333;
			color: white;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s;
		}

		.cart-actions button:hover {
			background-color: #555;
		}

		.badge {
			display: none;
		}

		.customer-details {
			margin-top: 20px;
		}

		.customer-details input {
			width: 100%;
			padding: 10px;
			margin-bottom: 10px;
			border: 1px solid #ddd;
			border-radius: 5px;
		}
	</style>
</head>

<body>
	<div class="navbar">
		<div class="logo">Z-collection</div>
		<div class="menu">
			<a href="#" class="cart"><i class="fa fa-shopping-cart"></i> <span class="badge">2</span></a>
			<a href="#" class="logout">Logout</a>

		</div>
	</div>
	<div class="container">
		<div class="product-list">
			@foreach($products as $product)
			<div class="product-card" data-product="{{ json_encode($product) }}">
				<div class="product-item">
					@php
					$imagePath = asset('storage/' . $product->image);
					Log::info('Image Path: ' . $imagePath);
					@endphp
					<img src="{{ $imagePath }}" alt="{{ $product->name }}" class="product-image">
					<div class="product-actions">
						<button>Add to Cart</button>
					</div>
					<span class="product-stock">Stock: {{ $product->stock }}</span>
				</div>
				<div class="product-details">
					<span class="product-name">{{ $product->name }}</span>
					<span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
				</div>
			</div>
			@endforeach
		</div>
	</div>

	<!-- Footer -->
	<footer class="bg3 p-t-75 p-b-32">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						Categories
					</h4>

					<ul>
						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Women
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Men
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Shoes
							</a>
						</li>

						<li class="p-b-10">
							<a href="#" class="stext-107 cl7 hov-cl1 trans-04">
								Watches
							</a>
						</li>
					</ul>
				</div>


				<div class="col-sm-6 col-lg-3 p-b-50">
					<h4 class="stext-301 cl0 p-b-30">
						GET IN TOUCH
					</h4>

					<p class="stext-107 cl7 size-201">
						Jl. Gempol Raya No.6, Gempol, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281
					</p>
				</div>
			</div>

			<div class="p-t-40">
				<div class="flex-c-m flex-w p-b-18">
					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-02.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-03.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-04.png" alt="ICON-PAY">
					</a>

					<a href="#" class="m-all-1">
						<img src="images/icons/icon-pay-05.png" alt="ICON-PAY">
					</a>
				</div>

				<p class="stext-107 cl6 txt-center">
					Copyright &copy; 2024 All rights reserved | made with <i class="fa fa-heart-o" aria-hidden="true"></i> Z-collection
				</p>
			</div>
		</div>
	</footer>


	<div class="cart-sidebar" id="cart-sidebar">
		<span class="close-btn" id="close-cart">&times;</span>
		<h2>Your Cart</h2>
		<div id="cart-items"></div>
		<div class="cart-total" id="cart-total"></div>
		<div class="customer-details">
			<input type="text" id="customer-name" placeholder="Name">
			<input type="email" id="customer-email" placeholder="Email">
			<input type="text" id="customer-address" placeholder="Address">
			<input type="text" id="customer-phone" placeholder="Phone Number">
		</div>
		<div class="cart-actions">
			<button id="checkout">Check Out</button>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$('.product-actions button').click(function() {
				var product = $(this).closest('.product-card').data('product');
				$.ajax({
					url: '{{ route("cart.add") }}',
					method: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						product: product
					},
					success: function(response) {
						if (response.success) {
							updateCartCount(response.cart_count);
							loadCart();
						}
					}
				});
			});
			$('.cart').click(function() {
				loadCart();
			});


			function loadCart() {
				$.ajax({
					url: '{{ route("cart.get") }}',
					method: 'GET',
					success: function(response) {
						var cartItems = response.cart;
						var cartItemsHtml = '';
						var total = 0;

						cartItems.forEach(function(item) {
							var imagePath = `{{ asset("storage") }}/${item.image}`;
							cartItemsHtml += `
                            <div class="cart-item" data-id="${item.product_id}">
                                <img src="${imagePath}" alt="${item.product.name}">
                                <div class="cart-item-details">
                                    <p class="item-name">${item.product.name}</p>
                                    <p class="item-price">Rp ${item.product.price.toLocaleString('id-ID')}</p>
                                    <div class="item-quantity">
                                        <button class="decrease-quantity">-</button>
                                        <span>${item.quantity}</span>
                                        <button class="increase-quantity">+</button>
                                    </div>
                                </div>
                                <button class="remove-item"><i class="fas fa-trash"></i></button>
                            </div>
                        `;
							total += item.product.price * item.quantity;
						});

						$('#cart-items').html(cartItemsHtml);
						$('#cart-total').text('Total: Rp ' + total.toLocaleString('id-ID'));
						$('#cart-sidebar').addClass('open');
						updateCartCount(response.cart_count);
					}
				});
			}

			$('#close-cart').click(function() {
				$('#cart-sidebar').removeClass('open');
			});

			$(document).on('click', '.increase-quantity', function() {
				var itemId = $(this).closest('.cart-item').data('id');
				updateCartItem(itemId, 'increase');
			});

			$(document).on('click', '.decrease-quantity', function() {
				var itemId = $(this).closest('.cart-item').data('id');
				updateCartItem(itemId, 'decrease');
			});

			$(document).on('click', '.remove-item', function() {
				var itemId = $(this).closest('.cart-item').data('id');
				updateCartItem(itemId, 'remove');
			});

			function updateCartItem(itemId, action) {
				$.ajax({
					url: '{{ route("cart.update") }}',
					method: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						id: itemId,
						action: action
					},
					success: function(response) {
						if (response.success) {
							updateCartCount(response.cart_count);
							loadCart();
						}
					}
				});
			}

			function updateCartCount(count) {
				if (count > 0) {
					$('.badge').text(count).show();
				} else {
					$('.badge').hide();
				}
			}

			$('#checkout').click(function() {
				var customerName = $('#customer-name').val();
				var customerEmail = $('#customer-email').val();
				var customerAddress = $('#customer-address').val();
				var customerPhone = $('#customer-phone').val();

				if (!customerName || !customerEmail || !customerAddress || !customerPhone) {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Please fill in all customer details',
					});
					return;
				}

				$.ajax({
					url: '{{ route("checkout.store") }}',
					method: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						customer_name: customerName,
						customer_email: customerEmail,
						customer_address: customerAddress,
						customer_phone: customerPhone,
						cart: getCartItems() // Function to get cart items
					},
					success: function(response) {
						if (response.snap_token) {
							snap.pay(response.snap_token, {
								onSuccess: function(result) {
									handlePaymentResult(result);
								},
								onPending: function(result) {
									handlePaymentResult(result);
								},
								onError: function(result) {
									handlePaymentResult(result);
								},
								onClose: function() {
									Swal.fire({
										icon: 'warning',
										title: 'Payment not completed',
										text: 'You closed the popup without finishing the payment.',
									});
								}
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Failed to initiate payment. Please try again.',
							});
						}
					}
				});
			});

			function getCartItems() {
				var cartItems = [];
				$('#cart-items .cart-item').each(function() {
					var item = {
						product_id: $(this).data('id'),
						quantity: $(this).find('.item-quantity span').text()
					};
					cartItems.push(item);
				});
				return cartItems;
			}

			function handlePaymentResult(result) {
				$.ajax({
					url: '{{ route("checkout.finish") }}',
					method: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						result_data: JSON.stringify(result)
					},
					success: function(response) {
						if (response.success) {
							Swal.fire({
								icon: 'success',
								title: 'Payment successful',
								text: 'Thank you for your purchase!',
							});
							clearCart();
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Failed to process payment result. Please contact support.',
							});
						}
					}
				});
			}

			function clearCart() {
				// Clear cart items in the frontend
				$('#cart-sidebar').removeClass('open');
				updateCartCount(0);
				$('#cart-items').html('');
				$('#cart-total').text('Total: Rp 0');

				// Optionally, clear cart items in the backend (e.g., session or database)
				$.ajax({
					url: '{{ route("cart.clear") }}',
					method: 'POST',
					data: {
						_token: '{{ csrf_token() }}'
					},
					success: function(response) {
						console.log('Cart cleared in backend');
					}
				});
			}
			$('.logout').click(function(event) {
				event.preventDefault(); // Mencegah aksi default dari link
				window.location.href = '/'; // Ganti '/' dengan URL halaman utama Anda
			});
		});
	</script>
	<!-- CSS Links -->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/linearicons-v1.0.0/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="vendor/slick/slick.css">
	<link rel="stylesheet" type="text/css" href="vendor/MagnificPopup/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">

	<!-- JS Scripts -->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".js-select2").each(function() {
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		});
	</script>
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<script src="vendor/slick/slick.min.js"></script>
	<script src="js/slick-custom.js"></script>
	<script src="vendor/parallax100/parallax100.js"></script>
	<script>
		$('.parallax100').parallax100();
	</script>
	<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
	<script>
		$('.gallery-lb').each(function() { // the containers for all your galleries
			$(this).magnificPopup({
				delegate: 'a', // the selector for gallery item
				type: 'image',
				gallery: {
					enabled: true
				},
				mainClass: 'mfp-fade'
			});
		});
	</script>
	<script src="vendor/isotope/isotope.pkgd.min.js"></script>
	<script src="vendor/sweetalert/sweetalert.min.js"></script>
	<script>
		$('.js-addwish-b2, .js-addwish-detail').on('click', function(e) {
			e.preventDefault();
		});

		$('.js-addwish-b2').each(function() {
			var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
			$(this).on('click', function() {
				swal(nameProduct, "is added to wishlist !", "success");

				$(this).addClass('js-addedwish-b2');
				$(this).off('click');
			});
		});

		$('.js-addwish-detail').each(function() {
			var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

			$(this).on('click', function() {
				swal(nameProduct, "is added to wishlist !", "success");

				$(this).addClass('js-addedwish-detail');
				$(this).off('click');
			});
		});

		/*---------------------------------------------*/

		$('.js-addcart-detail').each(function() {
			var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
			$(this).on('click', function() {
				swal(nameProduct, "is added to cart !", "success");
			});
		});
	</script>
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script>
		$('.js-pscroll').each(function() {
			$(this).css('position', 'relative');
			$(this).css('overflow', 'hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function() {
				ps.update();
			});
		});
	</script>
	<script src="js/main.js"></script>
</body>

</html>