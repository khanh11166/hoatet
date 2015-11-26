<?php

if ( ! class_exists( 'IG_Item_Contact' ) ) {
	/**
	 * Create child Tab element
	 *
	 * @package  IG PageBuilder Shortcodes
	 * @since    1.0.0
	 */
	class IG_Item_Contact extends IG_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'Configure social Button', IGPBL ),

			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'  => __( 'Title', IGPBL ),
						'id'    => 'title',
						'type'  => 'text_field',
						'class' => 'input-sm',
						'role'  => 'title',
						'std'   => __( IG_Pb_Utils_Placeholder::add_placeholder( 'Button %s', 'index' ), IGPBL ),
                        'tooltip' => __( 'Set text for tooltip on hover', IGPBL ),
					),
					array(
						'name'    => __( 'URL for button', IGPBL ),
						'id'      => 'url',
						'type'    => 'text_field',
						'role'    => 'url',
						'class'   => 'input-sm',
						'std'     => __( 'http://', IGPBL ),
						'tooltip' => __( 'Enter an url', IGPBL )
					),
					array(
						'name'    => __( 'Icon for button', IGPBL ),
						'id'      => 'icon',
						'type'    => 'text_field',
						'role'    => 'icon',
						'class'   => 'input-sm',
						'std'     => __( 'Example: facebook, twitter, gplus', IGPBL ),
						'tooltip' => __( 'Enter name of icon according to FontAwesome icons', IGPBL )
					),
				)
			);
		}

		/**
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode_full( $atts = null, $content = null ) {
            $arr_params = ( shortcode_atts( $this->config['params'], $atts ) );
			extract( $arr_params );
			return "<a href='{$url}' target='_blank' rel='nofollow' title='{$title}'><i class='fa fa-{$icon}'></i></a><!--separate-->";
		}

	}

}
