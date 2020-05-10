<?php
/*
Plugin Name: Woocommerce Admin Orders Frequent Customer Flash Notice
Version: 0.2
Author: Byron Dokimakis
Author URI: https://b.dokimakis.gr
*/

add_action('manage_shop_order_posts_custom_column', 'woocommerce_admin_orders_frequent_customer_flash_notice_flash_notice_in_order_number_column', 10, 2);

function woocommerce_admin_orders_frequent_customer_flash_notice_flash_notice_in_order_number_column($column) {
    global $post;
    switch ($column) {
        case 'order_number' :
			$dateFrom = date('Y-m-d', strtotime("-1 days")) . " 12:00";
			$dateTo = date('Y-m-d') . " 12:00";

			$query = new WP_Query(  array(
	        	'numberposts' => -1,
				'meta_key'    => '_billing_email',
				'meta_value'  => get_post_meta($post->ID, '_billing_email'),
				'post_type'   => wc_get_order_types(),
				'post_status' => array_keys( wc_get_order_statuses() ),
				'date_query' => array(array('before' => $dateTo, 'after' => $dateFrom), 'inclusive' => true)
			)  );
			
			$count = $query->found_posts;
						
			if ($count > 1) {
				$count -= 1;
				echo "<span style='white-space: nowrap' class='redblink'>$count other orders between 12PM yesterday and 12PM today</span>";
			}
        break;
    }
}

add_action('admin_head', 'woocommerce_admin_orders_frequent_customer_flash_notice_admin_styles');
function woocommerce_admin_orders_frequent_customer_flash_notice_admin_styles() {
  echo '<style>
    .post-type-shop_order tr.type-shop_order td.order_number { position: relative; }
    .post-type-shop_order tr.type-shop_order td.order_number .redblink { color: red; position: absolute; bottom: 0; animation: blinker 1s linear infinite; }
    @keyframes blinker {
          50% {
            opacity: 0;
          }
        }
  </style>';
}