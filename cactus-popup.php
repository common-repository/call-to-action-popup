<?php

	/*
	Plugin Name: Cactus PopUp
	Description: Call To Action PopUp
	Author: LamPD
	Version: 1.0.2
	Author URI: https://lamphandinh.info
	*/

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly

	if ( ! function_exists( 'cactus_popup_get_plugin_url' ) ) {
		function cactus_popup_get_plugin_url() {
			return plugin_dir_url( __FILE__ );
		}
	}

	if ( ! function_exists( 'cactus_popup_get_plugin_path' ) ) {
		function cactus_popup_get_plugin_path() {
			return plugin_dir_path( __FILE__ );
		}
	}

	if ( ! class_exists( 'Cactus_PopUp' ) ) {

		require_once( 'cactus-popup-shortcode.php' );

		class Cactus_PopUp {

			function __construct() {
				$this->cactus_popup_includes();
				add_action( 'admin_init', array( $this, 'admin_init' ), 0 );
				add_action( 'admin_menu', array( $this, 'admin_menu' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'cactus_popup_admin_enqueue_scripts' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action( 'init', array( $this, 'cactus_popup_register_post_type' ) );
				add_action( 'admin_menu', array( $this, 'admin_menu' ) );
				add_filter( 'manage_ct_popup_posts_columns', array( $this, 'cactus_popup_posts_custom_columns' ) );
				add_action( 'manage_ct_popup_posts_custom_column', array(
					$this,
					'cactus_popup_posts_custom_columns_content'
				) );
				add_action( 'wp_ajax_cactus_get_popup_metadata', array( $this, 'cactus_get_popup_metadata' ) );
				add_action( 'wp_ajax_nopriv_cactus_get_popup_metadata', array( $this, 'cactus_get_popup_metadata' ) );
				add_filter( 'plugin_action_links', array( $this, 'cactus_popup_add_action_plugin' ), 10, 5 );

			}

			//Update View + Subscribe via Ajax
			function cactus_get_popup_metadata() {

				$nonce     = $_REQUEST['popupNonce'];
				$popupId   = $_REQUEST['popupId'];
				$popupType = $_REQUEST['popupType'];

				if ( ! wp_verify_nonce( $nonce, 'cactus_get_popup_metadata_nonce_' . $popupId ) ) {
					exit( 'Oops! something went wrong.' . $nonce );
				}

				if ( $popupType == 'display' ) {
					$cactus_popup_display = get_post_meta( $popupId, 'cactus_popup_display', true );
					$cactus_popup_display = ( $cactus_popup_display != '' ) ? $cactus_popup_display : '';
					if ( $cactus_popup_display != '' ) {
						$new_cactus_popup_display = $cactus_popup_display + 1;
						$popup_display_response   = update_post_meta( $popupId, 'cactus_popup_display', $new_cactus_popup_display );
					}
				}

				if ( $popupType == 'subscribed' ) {
					$cactus_popup_subscribe = get_post_meta( $popupId, 'cactus_popup_subscribe', true );
					$cactus_popup_subscribe = ( $cactus_popup_subscribe != '' ) ? $cactus_popup_subscribe : '';
					if ( $cactus_popup_subscribe != '' ) {
						$new_cactus_popup_subscribe = $cactus_popup_subscribe + 1;
						$popup_display_subscribe    = update_post_meta( $popupId, 'cactus_popup_subscribe', $new_cactus_popup_subscribe );
					}

				}

				if ( $popup_display_response === false || $popup_display_subscribe === false ) {
					$result = 'error';
				} else {
					$result = 'success';
				}

				echo $result;

				die();

			}

			//add Custom Columns to PopUp admin Page
			function cactus_popup_posts_custom_columns( $columns ) {
				$columns['cactus_popup_id']        = 'ID';
				$columns['cactus_popup_display']   = 'Display';
				$columns['cactus_popup_subscribe'] = 'Subscribe';
				unset( $columns['date'] );

				return $columns;
			}

			//add Content for Custom Columns in PopUp admin Page
			function cactus_popup_posts_custom_columns_content( $columns ) {
				global $post;
				if ( $columns == 'cactus_popup_display' ) {
					echo get_post_meta( $post->ID, "cactus_popup_display", true );
				}
				if ( $columns == 'cactus_popup_subscribe' ) {
					echo get_post_meta( $post->ID, "cactus_popup_subscribe", true );
				}
				if ( $columns == 'cactus_popup_id' ) {
					echo $post->ID;
				}

			}

			function cactus_popup_includes() {
				// add custom meta boxes https://metabox.io
				include cactus_popup_get_plugin_path() . 'includes/meta-box/meta-box.php';
				include cactus_popup_get_plugin_path() . 'includes/cactus-popup-meta-box.php';
			}

			function cactus_popup_register_post_type() {

				//$label contain text realated post's name
				$label = array(
					'menu_name'     => esc_html__( 'CactusThems PopUp', 'cactus' ),
					'all_items'     => esc_html__( 'PopUps', 'cactus' ),
					'name'          => esc_html__( 'CactusThems PopUp', 'cactus' ),
					'singular_name' => esc_html__( 'CactusThems PopUp', 'cactus' ),
					'add_new_item'  => esc_html__( 'Add New PopUp', 'cactus' ),
					'edit_item'     => esc_html__( 'Edit PopUp', 'cactus' ),
				);
				//args for custom post type
				$args = array(
					'labels'              => $label,
					'description'         => __( 'Post Type for CactusThems PopUp', 'cactus' ),
					'supports'            => array(
						'title',
					),
					//Các tính năng được hỗ trợ trong post type
					'taxonomies'          => array(),
					//Các taxonomy được phép sử dụng để phân loại nội dung
					'hierarchical'        => false,
					//Cho phép phân cấp, nếu là false thì post type này giống như Post, true thì giống như Page
					'public'              => true,
					//Kích hoạt post type
					'show_ui'             => true,
					//Hiển thị khung quản trị như Post/Page
					'show_in_menu'        => true,
					//Hiển thị trên Admin Menu (tay trái)
					'show_in_nav_menus'   => false,
					//Hiển thị trong Appearance -> Menus
					'show_in_admin_bar'   => false,
					//Hiển thị trên thanh Admin bar màu đen.
					'menu_position'       => 5,
					//Thứ tự vị trí hiển thị trong menu (tay trái)
					'menu_icon'           => cactus_popup_get_plugin_url() . 'admin/icon.png',
					//Đường dẫn tới icon sẽ hiển thị
					'can_export'          => true,
					//Có thể export nội dung bằng Tools -> Export
					'has_archive'         => true,
					//Cho phép lưu trữ (month, date, year)
					'exclude_from_search' => true,
					//Loại bỏ khỏi kết quả tìm kiếm
					'publicly_queryable'  => true,
					//Hiển thị các tham số trong query, phải đặt true
					'capability_type'     => 'post',
					//
					'publicly_queryable'  => false,
				);

				//register post type
				register_post_type( 'ct_popup', $args );
			}

			function admin_menu() {
				add_submenu_page( 'edit.php?post_type=ct_popup', esc_html__( 'PopUp Settings', 'cactus' ), esc_html__( 'Settings', 'cactus' ), 'manage_options', 'cactus-popup-settings', array(
					$this,
					'cactus_popup_settings_page'
				) );
			}

			function admin_init() {
				// register settings
				register_setting( 'cactus-popup-group', 'cactus-popup-test-mode' );
				register_setting( 'cactus-popup-group', 'cactus-popup-click-anywhere-to-close' );
				register_setting( 'cactus-popup-group', 'cactus-popup-disable-scroll' );
			}

			function enqueue_scripts() {
				wp_enqueue_style( 'cactus-popup-style', plugins_url( '/css/popup.css', __FILE__ ) );
				wp_enqueue_script( 'bootstrap', plugins_url( '/js/bootstrap.min.js', __FILE__ ), array( 'jquery' ), '3.3.7', true );
				//https://github.com/carhartl/jquery-cookie
				wp_enqueue_script( 'cookie-master', plugins_url( '/js/jquery-cookie-master/jquery.cookie.js', __FILE__ ), array( 'jquery' ), '1.4.1', true );
				wp_enqueue_script( 'cactus-popup', plugins_url( '/js/popup.js', __FILE__ ), array( 'jquery' ), '20172501', true );
				wp_localize_script( 'cactus-popup', 'cactus_popup', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

			}

			function cactus_popup_admin_enqueue_scripts() {
				wp_enqueue_style( 'cactus-popup-css-admin', plugins_url( '/admin/css/admin.css', __FILE__ ) );
				wp_enqueue_script( 'cactus-popup-admin', plugins_url( '/admin/js/admin.js', __FILE__ ), array(), '20171301', true );
			}

			function cactus_popup_settings_page() {
				?>
                <div class="wrap">
                    <div class="cactus-popup-page">
                        <h2 class="page-title">
                            <span class="dashicons-before dashicons-admin-tools"></span><?php esc_html_e( 'CactusThemes - PopUp', 'cactus' ) ?>
                        </h2>

                        <form method="post" action="options.php" class="popup-options">

                            <p class="intro"><?php esc_html_e( 'Use shortcode: ', 'cactus' ) ?><b><i><code>[cta_popup
                                            id=""]</code></i></b><?php esc_html_e( ' to display PopUp', 'cactus' ) ?>
                                <br/>
                                <span class="desc"><?php esc_html_e( 'id: ID of PopUp. Dashboard > CactusThemes PopUp > PopUps', 'cactus' ) ?></span><br/>
                            </p>


							<?php settings_fields( 'cactus-popup-group' ); ?>

                            <p>
                                <label> <span class="label"><?php esc_html_e( 'Test Mode', 'cactus' ) ?></span><br/>
                                    <select name="cactus-popup-test-mode">
                                        <option value="off" <?php echo get_option( 'cactus-popup-test-mode', 'off' ) == 'off' ? 'selected="selected"' : '' ?> ><?php esc_html_e( 'Off', 'cactus' ) ?></option>
                                        <option value="on" <?php echo get_option( 'cactus-popup-test-mode', 'off' ) == 'on' ? 'selected="selected"' : '' ?>><?php esc_html_e( 'On', 'cactus' ) ?></option>
                                    </select> </label>
                            </p>

                            <p>
                                <label>
                                    <span class="label"><?php esc_html_e( 'Click Anywhere to close PopUp', 'cactus' ) ?></span><br/>

                                    <select name="cactus-popup-click-anywhere-to-close">
                                        <option value="off" <?php echo get_option( 'cactus-popup-click-anywhere-to-close', 'off' ) == 'off' ? 'selected="selected"' : '' ?> ><?php esc_html_e( 'Off', 'cactus' ) ?></option>
                                        <option value="on" <?php echo get_option( 'cactus-popup-click-anywhere-to-close', 'off' ) == 'on' ? 'selected="selected"' : '' ?>><?php esc_html_e( 'On', 'cactus' ) ?></option>
                                    </select> </label>
                            </p>

                            <p>
                                <label>
                                    <span class="label"><?php esc_html_e( 'Enable/ Disable scroll when PopUp displayed', 'cactus' ) ?></span><br/>

                                    <select name="cactus-popup-disable-scroll">
                                        <option value="enable" <?php echo get_option( 'cactus-popup-disable-scroll', 'enable' ) == 'enable' ? 'selected="selected"' : '' ?> ><?php esc_html_e( 'Enable', 'cactus' ) ?></option>
                                        <option value="disable" <?php echo get_option( 'cactus-popup-disable-scroll', 'enable' ) == 'disable' ? 'selected="selected"' : '' ?>><?php esc_html_e( 'Disable', 'cactus' ) ?></option>
                                    </select> </label>
                            </p>


							<?php submit_button(); ?>
                        </form>
                    </div>
                </div>

				<?php
			}

			/**
			 *  add Settings link in Plugin
			 */
			function cactus_popup_add_action_plugin( $actions, $plugin_file ) {
				static $plugin;

				if ( ! isset( $plugin ) ) {
					$plugin = plugin_basename( __FILE__ );
				}
				if ( $plugin == $plugin_file ) {
					$settings = array( 'settings' => '<a href="edit.php?post_type=ct_popup&page=cactus-popup-settings">' . __( 'Settings', 'cactus' ) . '</a>' );
					$actions  = array_merge( $settings, $actions );
				}

				return $actions;
			}

		}
	}

	$cactus_popup = new Cactus_PopUp();