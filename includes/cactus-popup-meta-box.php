<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 *
 * @link http://metabox.io/docs/registering-meta-boxes/
 */

add_filter( 'rwmb_meta_boxes', 'ct_popup_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * Remember to change "your_prefix" to actual prefix in your project
 *
 * @param array $meta_boxes List of meta boxes
 *
 * @return array
 */
function ct_popup_register_meta_boxes( $meta_boxes ) {

	/**
	 * prefix of meta keys (optional)
	 * Use underscore (_) at the beginning to make keys hidden
	 * Alt.: You also can make prefix empty to disable it
	 * $options = rwmb_meta( 'options',array(), get_the_ID());
	 */

	$prefix = 'cactus_popup_';

	$meta_boxes[] = array(
		'id' => 'ct_popup_content',
		'title' => esc_html__( 'PopUp Content', 'cactus' ),
		'post_types' => 'ct_popup',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(
			array(
				'id' => "{$prefix}content",
				'type' => 'wysiwyg',
				// Set the 'raw' parameter to TRUE to prevent data being passed through wpautop() on save
				'raw' => false,
				// Editor settings, see wp_editor() function: https://codex.wordpress.org/Function_Reference/wp_editor
				'options' => array(
					'textarea_rows' => 10,
					'teeny' => false,
					'media_buttons' => false,
				),
			),
		)
	);

	$meta_boxes[] = array(
		'id' => 'ct_popup_settings',
		'title' => esc_html__( 'PopUp Settings', 'cactus' ),
		'post_types' => 'ct_popup',
		'priority' => 'high',
		'autosave' => true,
		'fields' => array(
			array(
				'id' => "{$prefix}offset",
				'name' => esc_html__( 'Offset', 'cactus' ),
				'desc' => esc_html__( 'Offset (from Bottom) to display PopUp. Default is 1000', 'cactus' ),
				'std' => 1000,
				'type' => 'number',
				'min' => 0,
				'step' => 1,
			),
			array(
				'name' => esc_html__( 'PopUp Width', 'cactus' ),
				'id' => "{$prefix}width",
				'desc' => esc_html__( 'Width of PopUp to display. Default is 600 (px).', 'cactus' ),
				'type' => 'number',
				'std' => 600,
				'min' => 0,
				'step' => 1,
			),
			array(
				'name' => esc_html__( 'PopUp Height', 'cactus' ),
				'id' => "{$prefix}height",
				'desc' => esc_html__( 'Height of PopUp to display. Default is 350 (px).', 'cactus' ),
				'type' => 'number',
				'std' => 350,
				'min' => 0,
				'step' => 1,
			),
			array(
				'name' => esc_html__( 'Content Padding', 'cactus' ),
				'desc' => esc_html__( 'Example: 15px. Default is 15px', 'cactus' ),
				'id' => "{$prefix}content_padding",
				'type' => 'fieldset_text',
				'options' => array(
					'top' => esc_html__( 'Top', 'cactus' ),
					'right' => esc_html__( 'Right', 'cactus' ),
					'bottom' => esc_html__( 'Bottom', 'cactus' ),
					'left' => esc_html__( 'Left', 'cactus' ),
				),
				'class' => 'cactus-popup-content-padding',
			),
			array(
				'name' => esc_html__( 'Popup Background', 'cactus' ),
				'id' => "{$prefix}background",
				'type' => 'image_advanced',
				'size' => 'full',
				'max_file_uploads' => 1,
				'max_status' => false,
				'class' => 'cactus-popup-background',
			),
			array(
				'name' => esc_html__( 'Background Repeat', 'cactus' ),
				'id' => "{$prefix}background_repeat",
				'type' => 'select',
				'options' => array(
					'no-repeat' => esc_html__( 'No Repeat', 'cactus' ),
					'repeat' => esc_html__( 'Repeat All', 'cactus' ),
					'repeat-x' => esc_html__( 'Repeat Horizontally', 'cactus' ),
					'repeat-y' => esc_html__( 'Repeat Vertically', 'cactus' ),
					'space' => esc_html__( 'Space', 'cactus' ),
					'round' => esc_html__( 'Round', 'cactus' ),
					'inherit' => esc_html__( 'Inherit', 'cactus' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple' => false,
				'std' => '',
				'placeholder' => esc_html__( 'Background Repeat', 'cactus' ),
			),
			array(
				'name' => esc_html__( 'Background Position', 'cactus' ),
				'id' => "{$prefix}background_position",
				'type' => 'select',
				'options' => array(
					'left top' => esc_html__( 'Left Top', 'cactus' ),
					'left center' => esc_html__( 'Left Center', 'cactus' ),
					'left bottom' => esc_html__( 'Left Bottom', 'cactus' ),
					'center top' => esc_html__( 'Center Top', 'cactus' ),
					'center center' => esc_html__( 'Center Center', 'cactus' ),
					'center bottom' => esc_html__( 'Center Bottom', 'cactus' ),
					'right top' => esc_html__( 'Right Top', 'cactus' ),
					'right center' => esc_html__( 'Right Center', 'cactus' ),
					'right bottom' => esc_html__( 'Right Bottom', 'cactus' ),
				),
				// Select multiple values, optional. Default is false.
				'multiple' => false,
				'std' => '',
				'placeholder' => esc_html__( 'Background Position', 'cactus' ),
			),
			array(
				'name' => esc_html__( 'Background Size', 'cactus' ),
				'id' => "{$prefix}background_size",
				'type' => 'select',
				'options' => array(
					'auto' => esc_html__( 'Auto', 'cactus' ),
					'cover' => esc_html__( 'Cover', 'cactus' ),
					'contain' => esc_html__( 'Contain', 'cactus' ),
					'initial' => esc_html__( 'Initial', 'cactus' ),
					'inherit' => esc_html__( 'Inherit', 'cactus' ),
				),
				'multiple' => false,
				'std' => '',
				'placeholder' => esc_html__( 'Background Size', 'cactus' ),
			),
			array(
				'name' => esc_html__( 'Number of hours to repeat popup.', 'cactus' ),
				'id' => "{$prefix}repeat_hours",
				'desc' => esc_html__( 'Number of hours to repeat popup. Default is 1', 'cactus' ),
				'type' => 'number',
				'std' => 1,
				'min' => 1,
				'step' => 1,
			),
			array(
				'id' => "{$prefix}display",
				'type' => 'hidden',
				// Hidden field must have predefined value
				'attributes' => array(
					'value' => 0,
				),
				'std' => 0,
			),
			array(
				'id' => "{$prefix}subscribe",
				'type' => 'hidden',
				// Hidden field must have predefined value
				'attributes' => array(
					'value' => 0,
				),
				'std' => 0,
			),
			array(
				'name' => esc_html__( 'PopUp Custom CSS', 'cactus' ),
				'id' => "{$prefix}custom_css",
				'type' => 'textarea',
				'rows' => 10,
			),
			array(
				'name' => esc_html__( 'PopUp Custom Class', 'cactus' ),
				'desc' => esc_html__( 'Add custom Class for PopUp', 'cactus' ),
				'id' => "{$prefix}custom_class",
				'type' => 'text',
				'size' => 50,
			),
		)
	);
	return $meta_boxes;
}