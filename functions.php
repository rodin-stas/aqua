<?php
// Add custom Theme Functions here
// Подключаем свой js файл
function add_child() {
    wp_register_script('child', home_url() . '/wp-content/themes/aquaphor-store-child/js/child.js', array( 'jquery' ));
    wp_enqueue_script('child');
}
add_action( 'wp_enqueue_scripts', 'add_child' );

// Стили и родительские стилт
function true_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
    // wp_enqueue_style( 'css-hero', get_stylesheet_directory_uri() . '/wp-content/uploads/2021/01/csshero-static-style-aquaphor-store-child.css', array('parent-style') );
}
 
add_action( 'wp_enqueue_scripts', 'true_enqueue_styles' );



// Страница корзины
// Отключаем купоны
// add_filter( 'woocommerce_coupons_enabled', 'truemisha_coupon_field_on_checkout' );
 
// function truemisha_coupon_field_on_checkout( $enabled ) {
// 		$enabled = false; // купоны отключены
// 	return $enabled;
 
// }

// убираем доставку
function delshipping_calc_in_cart( $show_shipping ) {
    if( is_cart() ) {
        return false;
    }
    return $show_shipping;
}
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'delshipping_calc_in_cart', 99 );

// Автообновление при смне кол-ва товаров
add_action( 'wp_footer', 'cart_refresh_update_qty', 100 );

function cart_refresh_update_qty() {
    if ( is_cart() ) {
        ?>
        <script type="text/javascript">
            jQuery('div.woocommerce').on('change', 'input.qty', function(){
                setTimeout(function() {
                    jQuery('[name="update_cart"]').trigger('click');
                }, 100 );
            });
        </script>
        <?php
    }
}
// Переименвывем Подытог
add_filter('gettext', 'translate_text');
add_filter('ngettext', 'translate_text');
 
function translate_text($translated) {
$translated = str_ireplace('Возможно Вас также заинтересует&hellip;', 'Сопутствующие товары', $translated);
$translated = str_ireplace('Просмотр корзины', 'Открыть корзину', $translated);
$translated = str_ireplace('Подытог', 'Сумма', $translated);
$translated = str_ireplace('View more', 'Больше товаров', $translated);
$translated = str_ireplace('Shipping', 'Доставка', $translated);
$translated = str_ireplace('Search locations…', 'Выберите адрес', $translated);
$translated = str_ireplace('Please choose a pickup location', 'Выберите адрес пункта самовывоза', $translated);
$translated = str_ireplace('Сумма', 'Итого:', $translated);
return $translated;
}
// Страница Аккаунта
function my_woocommerce_account_menu_items($items) {
    unset($items['dashboard']);         // убрать вкладку Консоль
    // unset($items['orders']);             // убрать вкладку Заказы
    unset($items['downloads']);         // убрать вкладку Загрузки
    // unset($items['edit-address']);         // убрать вкладку Адреса
    // unset($items['edit-account']);         // убрать вкладку Детали учетной записи
    // unset($items['customer-logout']);     // убрать вкладку Выйти
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'my_woocommerce_account_menu_items', 10 );



// Стпница товара

// woocommerce_single_product_summary hook
// *
// * @hooked woocommerce_template_single_title - 5
// * @hooked woocommerce_template_single_rating - 10
// * @hooked woocommerce_template_single_price - 10
// * @hooked woocommerce_template_single_excerpt - 20
// * @hooked woocommerce_template_single_add_to_cart - 30
// * @hooked woocommerce_template_single_meta - 40
// * @hooked woocommerce_template_single_sharing - 50

/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	

// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);


add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 15);

add_action( 'woocommerce_after_add_to_cart_button', 'add_trans', 20);
function add_trans(){
	echo '<div class="add_trans">
			<div class="add_trans_onse">
			<span class="add_trans_onse-img"></span>
			<span>Оплата при получении или картой онлайн</span>
			</div>

			<div class="add_trans_two">
			<span class="add_trans_two-img"></span>
			<span>Бесплатная доставка в пункты самовывоза и домой</span>
			</div>
		</div>';
} 

add_action( 'woocommerce_after_add_to_cart_form', 'add_title', 50);
function add_title(){
	echo '<h5 class="uppercase mt custom">Характеристики</h5>';
} 

add_filter('gettext', 'change_rp_text', 10, 3);
add_filter('ngettext', 'change_rp_text', 10, 3);

function change_rp_text($translated, $text, $domain)
{
     if ($text === 'Related products' && $domain === 'woocommerce') {
         $translated = esc_html__('аааааааааааааааааааааа', $domain);
     }
     return $translated;
}


// Bitrix24
function aquaphor_custom_tracking( $order_id ) {
	// Получаем информации по заказу
	$order = wc_get_order( $order_id );
	$order_data = $order->get_data();
	// Получаем базовую информация по заказу
	$order_id = $order_data['id'];
	$order_currency = $order_data['currency'];
	$order_payment_method_title = $order_data['payment_method_title'];
	$order_shipping_totale = $order_data['shipping_total'];
	$order_total = $order_data['total'];
	$order_base_info = "<hr><strong>Общая информация по заказу</strong><br>
	ID заказа: $order_id<br>
	Валюта заказа: $order_currency<br>
	Метода оплаты: $order_payment_method_title<br>
	Стоимость доставки: $order_shipping_totale<br>
	Итого с доставкой: $order_total<br>";
	// Получаем информация по клиенту
	$order_customer_id = $order_data['customer_id'];
	$order_customer_ip_address = $order_data['customer_ip_address'];
	$order_billing_first_name = $order_data['billing']['first_name'];
	$order_billing_last_name = $order_data['billing']['last_name'];
	$order_billing_email = $order_data['billing']['email'];
	$order_billing_phone = $order_data['billing']['phone'];
	$order_client_info = "<hr><strong>Информация по клиенту</strong><br>
	ID клиента = $order_customer_id<br>
	IP адрес клиента: $order_customer_ip_address<br>
	Имя клиента: $order_billing_first_name<br>
	Фамилия клиента: $order_billing_last_name<br>
	Email клиента: $order_billing_email<br>
	Телефон клиента: $order_billing_phone<br>";
	echo $order_client_info;
	// Получаем информацию по доставке
	$order_shipping_address_1 = $order_data['shipping']['address_1'];
	$order_shipping_address_2 = $order_data['shipping']['address_2'];
	$order_shipping_city = $order_data['shipping']['city'];
	$order_shipping_state = $order_data['shipping']['state'];
	$order_shipping_postcode = $order_data['shipping']['postcode'];
	$order_shipping_country = $order_data['shipping']['country'];
	$order_shipping_info = "<hr><strong>Информация по доставке</strong><br>
	Страна доставки: $order_shipping_state<br>
	Город доставки: $order_shipping_city<br>
	Индекс: $order_shipping_postcode<br>
	Адрес доставки 1: $order_shipping_address_1<br>
	Адрес доставки 2: $order_shipping_address_2<br>";
	// Получаем информации по товару
	$order->get_total();
	$line_items = $order->get_items();
	foreach ( $line_items as $item ) {
	  $product = $order->get_product_from_item( $item );
	  $sku = $product->get_sku(); // артикул товара
	  $id = $product->get_id(); // id товара
	  $name = $product->get_name(); // название товара
	  $description = $product->get_description(); // описание товара
	  $stock_quantity = $product->get_stock_quantity(); // кол-во товара на складе
	  $qty = $item['qty']; // количество товара, которое заказали
	  $total = $order->get_line_total( $item, true, true ); // стоимость всех товаров, которые заказали, но без учета доставки
	  $product_info[] = "<hr><strong>Информация о товаре</strong><br>
	  Название товара: $name<br>
	  ID товара: $id<br>
	  Артикул: $sku<br>
	  Описание: $description<br>
	  Заказали (шт.): $qty<br>
	  Наличие (шт.): $stock_quantity<br>
	  Сумма заказа (без учета доставки): $total;";
	}
	$product_base_infо = implode('<br>', $product_info);
	$subject = "Заказ с сайта № $order_id";
	// Формируем URL в переменной $queryUrl для отправки сообщений в лиды Битрикс24
	$queryUrl = 'https://workwater.bitrix24.ru/rest/350/df6z0ht7a2kcveee/crm.lead.add.json';
	//$queryUrl = 'https://workwater.bitrix24.ru/rest/596/am0ypoebtnyl0gxj/crm.lead.add.json';
	// Формируем параметры для создания лида в переменной $queryData
	$queryData = http_build_query(array(
	  'fields' => array(
		'TITLE' => $subject,
		'COMMENTS' => $order_base_info.' '.$order_client_info.' '.$order_shipping_info.' '.$product_base_infо
	  ),
	  'params' => array("REGISTER_SONET_EVENT" => "Y")
	));
	// Обращаемся к Битрикс24 при помощи функции curl_exec
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_SSL_VERIFYPEER => 0,
	  CURLOPT_POST => 1,
	  CURLOPT_HEADER => 0,
	  CURLOPT_RETURNTRANSFER => 1,
	  CURLOPT_URL => $queryUrl,
	  CURLOPT_POSTFIELDS => $queryData,
	));
	$result = curl_exec($curl);
	curl_close($curl);
	$result = json_decode($result, 1);
	if (array_key_exists('error', $result)) echo "Ошибка при сохранении лида: ".$result['error_description']."<br>";
  }
  
  add_action( 'woocommerce_thankyou', 'aquaphor_custom_tracking' );

  /* чтобы вставить код php в статьях/страницах WordPress, поставьте шоркод: [exec]код[/exec] */
function exec_php($matches){
    eval('ob_start();'.$matches[1].'$inline_execute_output = ob_get_contents();ob_end_clean();');
    return $inline_execute_output;
}
function inline_php($content){
    $content = preg_replace_callback('/\[exec\]((.|\n)*?)\[\/exec\]/', 'exec_php', $content);
    $content = preg_replace('/\[exec off\]((.|\n)*?)\[\/exec\]/', '$1', $content);
    return $content;
}
add_filter('the_content', 'inline_php', 0);


/* Изменяет символ валюты на буквы */
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case 'RUB': $currency_symbol = ' руб.'; break;
     }
     return $currency_symbol;
}

// Страница пароля

add_shortcode( 'custom_passreset', 'render_pass_reset_form' ); // шорткод [custom_passreset]
 
function render_pass_reset_form() {
 
 	// если пользователь авторизован, просто выводим сообщение и выходим из функции
	if ( is_user_logged_in() ) {
		return sprintf( "Вы уже авторизованы на сайте. <a href='%s'>Выйти</a>.", wp_logout_url() );
	}
 
	$return = ''; // переменная, в которую всё будем записывать
 
	// обработка ошибок, если вам нужны такие же стили уведомлений, как в видео, CSS-код прикладываю чуть ниже
	if ( isset( $_REQUEST['errno'] ) ) {
		$errors = explode( ',', $_REQUEST['errno'] );
 
		foreach ( $errors as $error ) {
			switch ( $error ) {
				case 'empty_username':
					$return .= '<p class="errno">Вы не забыли указать свой email?</p>';
					break;
				case 'password_reset_empty':
					$return .= '<p class="errno">Укажите пароль!</p>';
					break;
				case 'password_reset_mismatch':
					$return .= '<p class="errno">Пароли не совпадают!</p>';
					break;
				case 'invalid_email':
				case 'invalidcombo':
					$return .= '<p class="errno">На сайте не найдено пользователя с указанным email.</p>';
					break;
			}
		}
	}
 
	// тем, кто пришёл сюда по ссылке из email, показываем форму установки нового пароля
	if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
 
		$return .= '<h3>Укажите новый пароль</h3>
			<form name="resetpassform" id="resetpassform" action="' . site_url( 'wp-login.php?action=resetpass' ) . '" method="post" autocomplete="off">
				<input type="hidden" id="user_login" name="login" value="' . esc_attr( $_REQUEST['login'] ) . '" autocomplete="off" />
				<input type="hidden" name="key" value="' . esc_attr( $_REQUEST['key'] ) . '" />
         			<p>
					<label for="pass1">Новый пароль</label>
					<input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
				</p>
				<p>
					<label for="pass2">Повторите пароль</label>
					<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
				</p>
 
				<p class="description">' . wp_get_password_hint() . '</p>
 
				<p class="resetpass-submit">
					<input type="submit" name="submit" id="resetpass-button" class="button" value="Сбросить" />
				</p>
			</form>';
 
		// возвращаем форму и выходим из функции
		return $return;
	}
 
	// всем остальным - обычная форма сброса пароля (1-й шаг, где указываем email)
	$return .= '
	<div>
		<h3 class="title-psws">Востановление пароля</h3>
		<p class="text-pswd">Если не удается вспомнить пароль, пожалуйста введите ниже свой Email. Мы отправим на него ссылку для создания нового пароля от вашей учетной записи.</p>
		<form id="lostpasswordform" action="' . wp_lostpassword_url() . '" method="post">
			<p class="form-row">
				
				<input type="text"  placeholder="Введите почту"  name="user_login" id="user_login">
			</p>
 			<p class="lostpassword-submit">
				<input type="submit" name="submit" class="lostpassword-button" value="Отправить" />
			</p>
		</form>
		</div>';
	
	// возвращаем форму и выходим из функции
	return $return;
}

/*
 * перенаправляем стандартную форму
 */
add_action( 'login_form_lostpassword', 'pass_reset_redir' );
 
function pass_reset_redir() {
	// если используете другой ярлык страницы сброса пароля, укажите здесь
	$forgot_pass_page_slug = '/lost-pswd';
	// если используете другой ярлык страницы входа, укажите здесь
	$login_page_slug = '/login';
	// если кто-то перешел на страницу сброса пароля
	// (!) именно перешел, а не отправил формой,
	// тогда перенаправляем на нашу кастомную страницу сброса пароля
	if ( 'GET' == $_SERVER['REQUEST_METHOD'] && !is_user_logged_in() ) {
		wp_redirect( site_url( $forgot_pass_page_slug ) );
		exit;
	} else if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
    		// если же напротив, была отправлена форма
    		// юзаем retrieve_password()
    		// которая отправляет на почту ссылку сброса пароля
    		// пользователю, который указан в $_POST['user_login']
		$errors = retrieve_password();
		if ( is_wp_error( $errors ) ) {
            		// если возникли ошибки, возвращаем пользователя назад на форму
            		$to = site_url( $forgot_pass_page_slug );
            		$to = add_query_arg( 'errno', join( ',', $errors->get_error_codes() ), $to );
        	} else {
            		// если ошибок не было, перенаправляем на логин с сообщением об успехе
            		$to = site_url( $login_page_slug );
            		$to = add_query_arg( 'errno', 'confirm', $to );
        	}
 
		// собственно сам редирект
        	wp_redirect( $to );
        	exit;
	}
}
 
/*
 * Манипуляции уже после перехода по ссылке из письма
 */
add_action( 'login_form_rp', 'custom_password_reset' );
add_action( 'login_form_resetpass', 'custom_password_reset' );
 
function custom_password_reset(){
 
	$key = $_REQUEST['key'];
	$login = $_REQUEST['login'];
	// если используете другой ярлык страницы сброса пароля, укажите здесь
	$forgot_pass_page_slug = '/forgot-pass';
	// если используете другой ярлык страницы входа, укажите здесь
	$login_page_slug = '/login';
 
	// проверку соответствия ключа и логина проводим в обоих случаях
	if ( ( 'GET' == $_SERVER['REQUEST_METHOD'] || 'POST' == $_SERVER['REQUEST_METHOD'] )
		&& isset( $key ) && isset( $login ) ) {
 
		$user = check_password_reset_key( $key, $login );
 
		if ( ! $user || is_wp_error( $user ) ) {
			if ( $user && $user->get_error_code() === 'expired_key' ) {
				wp_redirect( site_url( $login_page_slug . '?errno=expiredkey' ) );
			} else {
				wp_redirect( site_url( $login_page_slug . '?errno=invalidkey' ) );
			}
			exit;
		}
 
	}
 
	if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
 
		$to = site_url( $forgot_pass_page_slug );
		$to = add_query_arg( 'login', esc_attr( $login ), $to );
		$to = add_query_arg( 'key', esc_attr( $key ), $to );
 
		wp_redirect( $to );
		exit;
 
	} elseif ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
 
		if ( isset( $_POST['pass1'] ) ) {
 
 			if ( $_POST['pass1'] != $_POST['pass2'] ) {
				// если пароли не совпадают
				$to = site_url( $forgot_pass_page_slug );
 
				$to = add_query_arg( 'key', esc_attr( $key ), $to );
				$to = add_query_arg( 'login', esc_attr( $login ), $to );
				$to = add_query_arg( 'errno', 'password_reset_mismatch', $to );
 
				wp_redirect( $to );
				exit;
			}
 
			if ( empty( $_POST['pass1'] ) ) {
				// если поле с паролем пустое
 				$to = site_url( $forgot_pass_page_slug );
 
				$to = add_query_arg( 'key', esc_attr( $key ), $to );
				$to = add_query_arg( 'login', esc_attr( $login ), $to );
				$to = add_query_arg( 'errno', 'password_reset_empty', $to );
 
				wp_redirect( $to );
				exit;
			}
 
			// тут кстати вы можете задать и свои проверки, например, чтобы длина пароля была 8 символов
			// с паролями всё окей, можно сбрасывать
			reset_password( $user, $_POST['pass1'] );
			wp_redirect( site_url( $login_page_slug . '?errno=changed' ) );
 
		} else {
			// если что-то пошло не так
			echo "Что-то пошло не так.";
		}
 
		exit;
 
	}
 
}

/* Меняет текст кнопки "В корзину" на "Отложить" */
function my_theme_cart_button_text() {
	return 'Отложить';
  }
  add_filter( 'woocommerce_product_single_add_to_cart_text', 'my_theme_cart_button_text' );

  
/* Убираем ненужные строки во вкладке адреса на странице Детали заказа */
function wpbl_remove_some_fields( $array ) {
 
    //unset( $array['billing']['billing_first_name'] ); // Имя
    //unset( $array['billing']['billing_last_name'] ); // Фамилия
    //unset( $array['billing']['billing_email'] ); // Email
    //unset( $array['order']['order_comments'] ); // Примечание к заказу
 
    unset( $array['billing']['billing_phone'] ); // Телефон
    unset( $array['billing']['billing_company'] ); // Компания
    unset( $array['billing']['billing_country'] ); // Страна
    //unset( $array['billing']['billing_address_1'] ); // 1-ая строка адреса 
    //unset( $array['billing']['billing_address_2'] ); // 2-ая строка адреса 
    unset( $array['billing']['billing_city'] ); // Населённый пункт
    unset( $array['billing']['billing_state'] ); // Область / район
    //unset( $array['billing']['billing_postcode'] ); // Почтовый индекс
     
    // Возвращаем обработанный массив
    return $array;
}

// // колличество товаров на странице корзины
// function wc_remove_all_quantity_fields( $return, $product ) {
//     return true;
// }
// add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );


// Оформление заказа


// add_filter( 'woocommerce_checkout_fields' , 'wpbl_show_fields' );
 
// function wpbl_show_fields( $array ) {
    
//     // Выводим список полей, но только если пользователь имеет права админа
//     if( current_user_can( 'manage_options' ) ){
    
//         echo '<pre>';
//         print_r( $array);
//         echo '</pre>';
//     }
    
//     return $array;
// }

add_filter( 'woocommerce_checkout_fields', 'wplb_reorder', 9999 );
 
function wplb_reorder( $array ) {
    
    // Меняем приоритет
    $array['billing']['billing_email']['priority'] = 30;
    $array['billing']['billing_phone']['priority'] = 40;
    
    // Назначаем CSS классы
    $array['billing']['billing_email']['class'][0] = 'form-row-first';
    $array['billing']['billing_phone']['class'][0] = 'form-row-last';
    
    // Возвращаем обработанный массив
    return $array;
}

//
// Чекбокс "я согласен с условиями..." всегда выбран "да"
function set_checked_wc_terms( $terms_is_checked ) {   
	return true;   
  }   
  add_filter( 'woocommerce_terms_is_checked_default', 'set_checked_wc_terms', 10 );