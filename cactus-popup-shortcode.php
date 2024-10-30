<?php
class cactus_popup_shortcode{
	function __construct() {
		if ( !shortcode_exists( 'cta_popup' ) ) {
			add_shortcode( 'cta_popup', array($this, 'cactus_popup_create_shortcode') );
		}
	}
	function cactus_popup_create_shortcode( $atts ) {
		$id = isset( $atts['id'] ) ? $atts['id'] : '';
		$prefix = 'cactus_popup_';
		ob_start();
		if(isset($id) && $id != ''):
		
			$offset = get_post_meta($id, $prefix.'offset', true );
			$content_width = get_post_meta($id, $prefix.'width', true );
			$content_height = get_post_meta($id, $prefix.'height', true );
			$content_padding = get_post_meta($id, $prefix.'content_padding', true );
		
			$padding_top = isset($content_padding) && $content_padding != '' ? $content_padding['top'] : '';
			$padding_right = isset($content_padding) && $content_padding != '' ? $content_padding['right'] : '';
			$padding_bottom = isset($content_padding) && $content_padding != '' ? $content_padding['bottom'] : '';
			$padding_left = isset($content_padding) && $content_padding != '' ? $content_padding['left'] : '';
			$background = get_post_meta($id, $prefix.'background', true);
			if($background != ''){
				$background = wp_get_attachment_url( $background );
			}
			$background_repeat = get_post_meta($id, $prefix.'background_repeat', true );
			$background_position = get_post_meta($id, $prefix.'background_position', true );
			$background_size = get_post_meta($id, $prefix.'background_size', true );
			$repeat_hours = get_post_meta($id, $prefix.'repeat_hours', true );
			$content = get_post_meta($id, $prefix.'content', true );
			$custom_css = get_post_meta($id, $prefix.'custom_css', true );
			$custom_class = get_post_meta($id, $prefix.'custom_class', true );
		
			$test_mode = get_option('cactus-popup-test-mode','off');
			$click_to_close = get_option('cactus-popup-click-anywhere-to-close','off');
			$nonce = wp_create_nonce('cactus_get_popup_metadata_nonce_'.$id);
			$disable_scroll = get_option('cactus-popup-disable-scroll','enable');
			
			?>			
				<div id="cactus-popup" class="cactus-popup modal show fade <?php echo $custom_class != '' ? esc_attr($custom_class) : '';?>" role="dialog" data-offset-bottom="<?php echo $offset != '' ? esc_attr($offset) : 1000;?>" data-repeat-hours="<?php echo $repeat_hours != '' ? esc_attr($repeat_hours) : 1; ?>" data-test-mode="<?php echo esc_attr($test_mode);?>" data-click-anywhere="<?php echo esc_attr($click_to_close);?>" data-id="<?php echo esc_attr($id);?>" data-nonce="<?php echo esc_attr($nonce);?>" data-disable-scroll="<?php echo esc_attr($disable_scroll);?>">
					<div class="cactus-popup-inner modal-dialog <?php echo $click_to_close != 'off' ? 'click-anywhere' : '';?>">
						<div class="modal-content popup-content-wrap">
							<div class="modal-body popup-body" <?php echo $background != '' ? 'style="background-image: url('.esc_url($background).')"': ''; ?>>
								<div class="popup-close" data-dismiss="modal">
									<button type="button" class="close" data-dismiss="modal">
										<span></span>
										<span></span>
									</button>
								</div>
								<?php echo do_shortcode($content);?>
							</div>
						</div>
					</div>
				</div>
				<?php if( $content_height != '' || $content_width != '' || $padding_top != '' || $padding_right != '' || $padding_bottom != '' || $padding_left != '' || $background_repeat != '' || $background_position != '' || $background_size != ''):?>
				<style scoped>
					<?php if($content_height != '' || $content_width != ''):?>
					@media (min-width: 768px){
						#cactus-popup .cactus-popup-inner{
							<?php echo $content_height != '' ? 'height:'.esc_html($content_height).'px;' : '';?>
							<?php echo $content_width != '' ? 'width:'.esc_html($content_width).'px;' : '';?>
						}
					}
					<?php endif;?>
					<?php if($padding_top != '' || $padding_right != '' || $padding_bottom != '' || $padding_left != '' || $background_repeat != '' || $background_position != '' || $background_size != '') :?>
						#cactus-popup .popup-body{
							<?php echo $padding_top != '' ? 'padding-top:'.esc_html($padding_top).';' : '';?>
							<?php echo $padding_right != '' ? 'padding-right:'.esc_html($padding_right).';' : '';?>
							<?php echo $padding_bottom != '' ? 'padding-bottom:'.esc_html($padding_bottom).';' : '';?>
							<?php echo $padding_left != '' ? 'padding-left:'.esc_html($padding_left).';' : '';?>
							<?php echo $background_repeat != '' ? 'background-repeat:'.esc_html($background_repeat).';' : '';?>
							<?php echo $background_position != '' ? 'background-position:'.esc_html($background_position).';' : '';?>
							<?php echo $background_size != '' ? 'background-size:'.esc_html($background_size).';' : '';?>
						}
					<?php endif;?>
					<?php if($custom_css != ''):?>
						<?php echo esc_html($custom_css); ?>
					<?php endif;?>
				</style>
				<?php endif;?>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		endif;
	}
}
$cactus_popup_shortcode = new cactus_popup_shortcode();