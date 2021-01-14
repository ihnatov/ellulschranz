<?php

add_filter( 'rwmb_meta_boxes', 'ellul_schranz_register_meta_boxes' );

function ellul_schranz_register_meta_boxes( $meta_boxes ){
	global $ellul_schranz_config, $ellul_schranz_data;
	if( isset( $ellul_schranz_config['meta-box'] ) && is_array( $ellul_schranz_config['meta-box'] ) && is_array( $ellul_schranz_data ) ){
		foreach( $ellul_schranz_config['meta-box'] as $meta_idx => $meta_box ){
			if( isset( $meta_box['fields'] ) && $meta_box['fields'] ){
				foreach( $meta_box['fields'] as $field_idx => $field ){
					if(
						! isset( $ellul_schranz_config['meta-box'][ $meta_idx ]['fields'][ $field_idx ]['std'] ) &&
						array_key_exists( $field['id'], $ellul_schranz_data )
					){
						$ellul_schranz_config['meta-box'][ $meta_idx ]['fields'][ $field_idx ]['std'] = $ellul_schranz_data[ $field['id'] ];
					}
				}
			}
		}
		$meta_boxes = array_merge( $meta_boxes, $ellul_schranz_config['meta-box'] );
	}
	return $meta_boxes;
}
