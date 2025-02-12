<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Everest_Forms {
    public $message_raw;
    public $form_data;
    public $fields;
    public $entry_id;
	function __construct(){
        add_filter( "everest_forms_email_message", array($this,"everest_forms_email_message"),10,2);
        add_filter( "everest_forms_entry_email__message", array($this,"everest_forms_entry_email__message"),10,2);
	}
    function everest_forms_entry_email__message($message, $notification){
        $this->message_raw = $message;
        $this->form_data = $notification->form_data;
        $this->fields = $notification->fields;
        $this->entry_id = $notification->entry_id;
        return $message; 
    }
    function everest_forms_email_message($message, $notification){
        $message = $this->message_raw;
        $message = $this->process_tag( $message, false );
		$message = nl2br( $message );
		$message = str_replace( '{all_fields}', $this->everest_forms_html_field_value( true ), $message );
		$message = make_clickable( $message );
        return $message; 
    }
    public function process_tag( $string = '', $sanitize = true, $linebreaks = false ) {
		$tag = apply_filters( 'everest_forms_process_smart_tags', $string, $this->form_data, $this->fields, $this->entry_id );
		$tag = evf_decode_string( $tag );
		if ( $sanitize ) {
			if ( $linebreaks ) {
				$tag = evf_sanitize_textarea_field( $tag );
			} else {
				$tag = sanitize_text_field( $tag );
			}
		}
		return $tag;
	}
    public function everest_forms_html_field_value( $html = true ) {
		if ( empty( $this->fields ) ) {
			return '';
		}
		// Make sure we have an entry id.
		if ( ! empty( $this->entry_id ) ) {
			$this->form_data['entry_id'] = (int) $this->entry_id;
		}
		$message = '';
		if ( $html ) {
			/*
			 * HTML emails.
			 */
            $message = '<table border="0" cellpadding="0" cellspacing="0" width="100%" />';
			ob_start();
			$empty_message  = '<em>' . __( '(empty)', 'everest-forms' ) . '</em>';
			$field_iterator = 1;
			foreach ( $this->fields as $meta_id => $field ) {
				if (
					! apply_filters( 'everest_forms_email_display_empty_fields', false ) &&
					( empty( $field['value'] ) && '0' !== $field['value'] )
				) {
					continue;
				}
				// If empty value is provided for select field, don't send email.
				if ( 'select' === $field['type'] && empty( $field['value'][0] ) ) {
					continue;
				}
				if ( ( 'radio' === $field['type'] && empty( $field['value']['label'] ) ) || ( 'payment-multiple' === $field['type'] && empty( $field['value']['label'] ) ) ) {
					continue;
				}
				if ( ( 'checkbox' === $field['type'] && empty( $field['value']['label'][0] ) ) || ( 'payment-checkbox' === $field['type'] && empty( $field['value']['label'] ) ) ) {
					continue;
				}
				$field_val   = empty( $field['value'] ) && '0' !== $field['value'] ? $empty_message : $field['value'];
				$field_name  = isset( $field_val['name'] ) ? $field_val['name'] : $field['name'];
				$field_label = ! empty( $field_val['label'] ) ? $field_val['label'] : $field_val;
				$field_type  = $field['type'];
				// If empty label is provided for choice field, don't store their data nor send email.
				if ( in_array( $field_type, array( 'radio', 'payment-multiple' ), true ) ) {
					if ( isset( $field_val['label'] ) && '' === $field_val['label'] ) {
						continue;
					}
				} elseif ( in_array( $field_type, array( 'checkbox', 'payment-checkbox' ), true ) ) {
					if ( isset( $field_val['label'] ) && ( empty( $field_val['label'] ) || '' === current( $field_val['label'] ) ) ) {
						continue;
					}
				}
				if ( isset( $field['value'], $field['value_raw'] ) && is_string( $field['value'] ) && in_array( $field_type, array( 'image-upload', 'file-upload' ), true ) ) {
					$field['value'] = $field;
				}
				if ( isset( $field_val['type'] ) && in_array( $field['type'], array( 'image-upload', 'file-upload', 'rating' ), true ) ) {
					if ( 'rating' === $field_val['type'] ) {
						$value           = ! empty( $field_val['value'] ) ? $field_val['value'] : 0;
						$number_of_stars = ! empty( $field_val['number_of_rating'] ) ? $field_val['number_of_rating'] : 5;
						$field_val       = $value . '/' . $number_of_stars;
					} else {
						$field_val = empty( $field_val['file_url'] ) ? $empty_message : $field_val;
					}
				}
				if ( 'rating' !== $field_type ) {
					if ( is_array( $field_label ) ) {
						$field_html = array();
						foreach ( $field_label as $meta_val ) {
							$field_html[] = esc_html( $meta_val );
						}
						$field_val = implode( ', ', $field_html );
					} else {
						$field_val = esc_html( $field_label );
					}
				}
				if ( empty( $field_name ) ) {
					$field_name = sprintf(
						/* translators: %d - field ID. */
						esc_html__( 'Field ID #%d', 'everest-forms' ),
						absint( $field['id'] )
					);
				}
                $field_item = '<tr><td><strong>{field_name}</td></strong></tr>';
                $field_item .= '<tr><td style="padding-bottom: 30px;">{field_value}</td></tr>';
				$field_item  = str_replace( '{field_name}', $field_name, $field_item );
				$field_value = apply_filters( 'everest_forms_html_field_value', evf_decode_string( $field_val ), $field['value'], $this->form_data, 'email-html' );
				//$field_value = apply_filters( 'everest_forms_html_field_value', evf_decode_string( $field_val ), $field['value'], $this->form_data, 'email-html', $field );
				$field_item  = str_replace( '{field_value}', $field_value, $field_item );
				$message .= wpautop( $field_item );
				++$field_iterator;
			}
            $message .='</table>';
		} 
		if ( empty( $message ) ) {
			$empty_message = esc_html__( 'An empty form was submitted.', 'everest-forms' );
			$message       = $html ? wpautop( $empty_message ) : $empty_message;
		}
		return $message;
	}
}
new Yeemail_Addons_Everest_Forms;