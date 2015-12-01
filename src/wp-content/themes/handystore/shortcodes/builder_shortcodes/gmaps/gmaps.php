<?php


if ( ! class_exists( 'IG_Gmaps' ) ) {

	class IG_Gmaps extends IG_Pb_Shortcode_Parent {

		public function __construct() {
			parent::__construct();
		}

		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['name']      = __( 'Google Maps',  'plumtree' );
			$this->config['cat']       = __( 'Typography',  'plumtree' );
			$this->config['icon']      = 'icon-paragraph-text';
			$this->config['exception'] = array(
				'default_content'  => __( 'Google Maps',  'plumtree' ),
				'require_js'       => array( ),
				'data-modal-title' => __( 'Google Map',  'plumtree' )
			);
            $this->config['edit_using_ajax'] = true;
		}

		public function element_items() {
			$this->items = array(
				'content' => array(
					array(
						'name'    => __( 'Element Title',  'plumtree' ),
						'id'      => 'el_title',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => __( '',  'plumtree' ),
						'role'    => 'title',
						'tooltip' => __( 'Set title for current element for identifying easily',  'plumtree' )
					),


                    array(
                        'name'       => __( 'Width', IGPBL ),
                        'id'         => 'm_width',
                        'type'       => 'text_field',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '100%',

                        'tooltip'    => __( 'Set maps width', IGPBL )
                    ),

                    array(
                        'name'       => __( 'Height', IGPBL ),
                        'id'         => 'm_height',
                        'type'       => 'text_field',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '513px',

                        'tooltip'    => __( 'Set maps width', IGPBL )
                    ),


                    array(
                        'name'       => __( 'Latitude', IGPBL ),
                        'id'         => 'gm_lat',
                        'type'       => 'text_append',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '41.895465',
                        'validate'   => 'number',
                        'tooltip'    => __( 'Set Latitude coordinate', IGPBL )
                    ),

                    array(
                        'name'       => __( 'Longitude', IGPBL ),
                        'id'         => 'gm_long',
                        'type'       => 'text_append',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '12.482324',
                        'validate'   => 'number',
                        'tooltip'    => __( 'Set Longitude coordinate', IGPBL )
                    ),

                    array(
                        'id'      => 'b_text',
                        'name'    => __( 'Bubble Text',  'plumtree' ),
                        'type'    => 'text_field',
                        'class'   => 'jsn-input-medium-fluid',
                        'std'     => __( '',  'plumtree' ),
                        'tooltip' => __( 'Set the pricing table info',  'plumtree' )
                    ),

                    array(
                        'name'       => __( 'Default Zoom', IGPBL ),
                        'id'         => 'd_zoom',
                        'type'       => 'text_append',
                        'type_input' => 'number',
                        'class'      => 'input-mini',
                        'std'        => '15',
                        'validate'   => 'number',
                        'tooltip'    => __( 'Set defaul zoom value coordinate', IGPBL )
                    ),


                    array(
                        'name'       => __( 'Zoom Control ON/OFF', IGPBL ),
                        'id'         => 'z_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),

                    ),

                    array(
                        'name'       => __( 'Pan Control ON/OFF', IGPBL ),
                        'id'         => 'p_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),

                    ),

                    array(
                        'name'       => __( 'Stree View Control ON/OFF', IGPBL ),
                        'id'         => 'st_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),

                    ),

                    array(
                        'name'       => __( 'Map Type Control ON/OFF', IGPBL ),
                        'id'         => 'mt_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),

                    ),

                    array(
                        'name'       => __( 'Overview Map Control ON/OFF', IGPBL ),
                        'id'         => 'om_onoff',
                        'type'       => 'radio',
                        'std'        => 'off',
                        'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),

                    ),

                    array(
                        'id'      => 'address',
                        'name'    => __( 'Address',  'plumtree' ),
                        'type'    => 'text_field',
                        'class'   => 'jsn-input-medium-fluid',
                        'std'     => __( '',  'plumtree' ),
                        'tooltip' => __( 'Set desired address to show on the map',  'plumtree' )
                    ),



				),
				'styling' => array(
					
					
					
					
					

					
															
					
					
					
				)
			);
		}

		public function element_shortcode_full( $atts = null, $content = null ) {
			$html_element = '';
			$arr_params   = shortcode_atts( $this->config['params'], $atts );
			extract( $arr_params );

            $zoomControl = $z_onoff == 'on' ? 'true' : 'false';

            $panControl = $p_onoff == 'on' ? 'true' : 'false';

            $streetViewControl = $st_onoff == 'on' ? 'true' : 'false';

            $mapTypeControl = $mt_onoff == 'on' ? 'true' : 'false';

            $overviewMapControl = $om_onoff == 'on' ? 'true' : 'false';

            $html_element = "[gmap data=\"lat: {$gm_lat}, lng: {$gm_long}, bubbletext:{$b_text}, zoom: {$d_zoom}, zoomControl : {$zoomControl}, panControl : {$panControl}, streetViewControl : {$streetViewControl}, mapTypeControl: {$mapTypeControl}, overviewMapControl: {$overviewMapControl}, address:{$address} \" width=\"{$m_width}\" height=\"{$m_height}\"]";

            return $html_element;

		}

	}

}