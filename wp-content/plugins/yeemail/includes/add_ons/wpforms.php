<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Wpforms {
    public $form_data;
    public $fields;
    public $entry_id;
    public $notification_id;
    public $notifitions;
	function __construct(){
        add_filter( "wpforms_emails_mailer_message", array($this,"wpforms_emails_mailer_message"),10,2);
        add_filter( "wpforms_emails_notifications_get_content_type", array($this,"wpforms_emails_notifications_get_content_type"),10,2);
        add_filter( "wpforms_emails_notifications_message", array($this,"wpforms_emails_notifications_message"),10,3);
        add_filter( 'wpforms_lite_admin_education_builder_notifications_advanced_settings_content', array($this, 'settings' ), 5, 3 );
		add_filter( 'wpforms_pro_admin_builder_notifications_advanced_settings_content', array( $this, 'settings' ), 5, 3 );
	}
    public function settings( $content, $settings, $id ) {
        $link = get_option( "yemail_pro_id");
        ob_start();
		?>
        <div class="yeemail_addon_3 wpforms-panel-field wpforms-panel-field-email-template-wrap wpforms-panel-field-select">
            <label><?php esc_html_e( "YeeEmail Template Advanced", "yeemail" ) ?></label>
            <a class="button" target="_blank" href="<?php echo esc_url(get_edit_post_link($link)."&add-ons=yeemail-for-wpforms") ?>"><?php esc_attr_e( "Customize with YeeMail", "yeemail") ?></a>
        </div>
        <?php
        $text= ob_get_clean();
        $content = apply_filters( "yeemail_wpforms_settings", $text,$content, $settings, $id);
        return $content;
    }
    function wpforms_emails_mailer_message($message,$notifitions){
		$default_id = Yeemail_Builder_Frontend::get_email_id_template_by_type("default");
		$form_data = $this->form_data;
		$notifitions = $this->notifitions;
		$fields = $this->fields;
		$entry_id = $this->entry_id;
		$notification_id = $this->notification_id;
		$notifications = array();
		if( isset($notifitions->form_data["settings"]["notifications"])){
			$notifications = $notifitions->form_data["settings"]["notifications"];
		}
		$datas = $this->get_datas();
		if($default_id) {
			$message = $this->get_meesage_id_by_notify($notification_id,$notifications);
			$message = $this->replace_shortcode($message,$form_data,$fields,$entry_id);
		}
		$message = apply_filters( "yeemail_wpforms_message",$message,$form_data,$fields,$entry_id,$notification_id,$notifications,$datas,$this );
        return $message; 
    }
    function wpforms_emails_notifications_message($message, $current_template, $notifitions ){
        $this->form_data = $notifitions->form_data;
        $this->fields = $notifitions->fields;
        $this->entry_id = $notifitions->entry_id;
        $this->notification_id = $notifitions->notification_id;
        $this->notifitions = $notifitions;
        return $message;
    }
    public function get_template_id_by_notify($notification_id,$notifitions){
        foreach($notifitions as $id=> $notify){
            if($notification_id == $id){
                if(isset($notify["yeemail"])){
                    return $notify["yeemail"];
                }
            }
        }
        return null;
    }
	public function get_datas($show_empty_fields = true) { 
        $datas = array();
		foreach ( $this->form_data['fields'] as $field_id => $field ) {
			$field_type = ! empty( $field['type'] ) ? $field['type'] : '';
			// Check if the field is empty in $this->fields.
			if ( empty( $this->fields[ $field_id ] ) ) {
				// Check if the field type is in $other_fields, otherwise skip.
				if ( empty( $other_fields ) || ! in_array( $field_type, $other_fields, true ) ) {
					continue;
				}
				// Handle specific field types.
				list( $field_name, $field_val ) = $this->process_special_field_values( $field );
			} else {
				// Handle fields that are not empty in $this->fields.
				if ( ! $show_empty_fields && ( ! isset( $this->fields[ $field_id ]['value'] ) || (string) $this->fields[ $field_id ]['value'] === '' ) ) {
					continue;
				}
				$field_name = isset( $this->fields[ $field_id ]['name'] ) ? $this->fields[ $field_id ]['name'] : '';
				$field_val  = empty( $this->fields[ $field_id ]['value'] ) && ! is_numeric( $this->fields[ $field_id ]['value'] ) ? '' : $this->fields[ $field_id ]['value'];
			}
			// Set a default field name if empty.
			if ( empty( $field_name ) && $field_name !== null ) {
				$field_name = $this->get_default_field_name( $field_id );
			}
			/** This filter is documented in src/SmartTags/SmartTag/FieldHtmlId.php.*/
			$field_val = apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
				'wpforms_html_field_value',
				$field_val,
				isset( $this->fields[ $field_id ] ) ? $this->fields[ $field_id ] : $field,
				$this->form_data,
				'email-raw'
			);
			$field_val = str_replace( [ "\r\n", "\r", "\n" ], '', $field_val );
            $datas["{field_id='".$field_id."'}"] = $field_val;
        }
		return $datas;
	}
    public function replace_shortcode($message,$form_data,$fields,$entry_id){
        if ( strpos( $message, '{all_fields}' ) === false ) {
            // Wrap the message with a table row after processing tags.
            $message = $this->wrap_content_with_table_row( $message,$form_data,$fields,$entry_id );
        } else {
            // If {all_fields} is present, extract content before and after into separate variables.
            list( $before, $after ) = array_map( 'trim', explode( '{all_fields}', $message, 2 ) );
            // Wrap before and after content with <tr> tags if they are not empty to maintain styling.
            // Note that whatever comes after the {all_fields} should be wrapped in a table row to avoid content misplacement.
            $before_tr = ! empty( $before ) ? $this->wrap_content_with_table_row( $before,$form_data,$fields,$entry_id ) : '';
            $after_tr  = ! empty( $after ) ? $this->wrap_content_with_table_row( $after,$form_data,$fields,$entry_id  ) : '';

            // Replace {all_fields} with $this->process_field_values() output.
            $message = $before_tr . $this->process_field_values() . $after_tr;
        }
        return $message;
    }
    public function get_meesage_id_by_notify($notification_id,$notifitions){
        foreach($notifitions as $id=> $notify){
            if($notification_id == $id){
                return $notify["message"];
            }
        }
        return null;
    }
    function wpforms_emails_notifications_get_content_type($type){
        return "text/html";
    }
    public function process_field_values() {

		// If fields are empty, return an empty message.
		if ( empty( $this->fields ) ) {
			return '';
		}

		// If no message was generated, create an empty message.
		$default_message = esc_html__( 'An empty form was submitted.', 'wpforms-lite' );

		/**
		 * Filter whether to display empty fields in the email.
		 *
		 * @since 1.8.5
		 * @deprecated 1.8.5.2
		 *
		 * @param bool $show_empty_fields Whether to display empty fields in the email.
		 */
		$show_empty_fields = apply_filters_deprecated( // phpcs:disable WPForms.Comments.ParamTagHooks.InvalidParamTagsQuantity
			'wpforms_emails_notifications_display_empty_fields',
			[ false ],
			'1.8.5.2 of the WPForms plugin',
			'wpforms_email_display_empty_fields'
		);

		/** This filter is documented in /includes/emails/class-emails.php */
		$show_empty_fields = apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
			'wpforms_email_display_empty_fields',
			false
		);
		$message = $this->process_html_message( $show_empty_fields );

		return empty( $message ) ? $default_message : $message;
	}
    public function process_html_message( $show_empty_fields = false ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity

		$message = '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border-spacing: 0px; padding: 0px; vertical-align: top;">';

		/**
		 * Filter the list of field types to display in the email.
		 *
		 * @since 1.8.5
		 * @deprecated 1.8.5.2
		 *
		 * @param array $other_fields List of field types.
		 * @param array $form_data    Form data.
		 */
		$other_fields = apply_filters_deprecated( // phpcs:disable WPForms.Comments.ParamTagHooks.InvalidParamTagsQuantity
			'wpforms_emails_notifications_display_other_fields',
			[ [], $this->form_data ],
			'1.8.5.2 of the WPForms plugin',
			'wpforms_email_display_other_fields'
		);

		/** This filter is documented in /includes/emails/class-emails.php */
		$other_fields = apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
			'wpforms_email_display_other_fields',
			[],
			$this
		);

        

		foreach ( $this->form_data['fields'] as $field_id => $field ) {
			$field_type = ! empty( $field['type'] ) ? $field['type'] : '';

			// Check if the field is empty in $this->fields.
			if ( empty( $this->fields[ $field_id ] ) ) {
				// Check if the field type is in $other_fields, otherwise skip.
				if ( empty( $other_fields ) || ! in_array( $field_type, $other_fields, true ) ) {
					continue;
				}

				// Handle specific field types.
				list( $field_name, $field_val ) = $this->process_special_field_values( $field );
			} else {
				// Handle fields that are not empty in $this->fields.
				if ( ! $show_empty_fields && ( ! isset( $this->fields[ $field_id ]['value'] ) || (string) $this->fields[ $field_id ]['value'] === '' ) ) {
					continue;
				}

				$field_name = isset( $this->fields[ $field_id ]['name'] ) ? $this->fields[ $field_id ]['name'] : '';
				$field_val  = empty( $this->fields[ $field_id ]['value'] ) && ! is_numeric( $this->fields[ $field_id ]['value'] ) ? '<em>' . esc_html__( '(empty)', 'wpforms-lite' ) . '</em>' : $this->fields[ $field_id ]['value'];
			}
           
			// Set a default field name if empty.
			if ( empty( $field_name ) && $field_name !== null ) {
				$field_name = $this->get_default_field_name( $field_id );
			}

			/** This filter is documented in src/SmartTags/SmartTag/FieldHtmlId.php.*/
			$field_val = apply_filters( // phpcs:ignore WPForms.PHP.ValidateHooks.InvalidHookName
				'wpforms_html_field_value',
				$field_val,
				isset( $this->fields[ $field_id ] ) ? $this->fields[ $field_id ] : $field,
				$this->form_data,
				'email-html'
			);
			// Replace new lines with <br/> tags.
			$field_val = str_replace( [ "\r\n", "\r", "\n" ], '<br/>', $field_val );
			// Append the field item to the message.
            $message .= '<tr style="padding: 0px; vertical-align: top;">';
			$message .= '<td style="overflow-wrap: break-word; vertical-align: top; font-weight: normal; padding: 25px 10px 25px 0px; margin: 0px; font-size: 15px; color: rgb(51, 51, 51); border-bottom: 1px solid rgb(226, 226, 226); min-width: 113px; line-height: 22px; border-collapse: collapse;">
                        <strong style="margin-bottom: 0px;">'.$field_name.'</strong>
                    </td>';
            $message .= '<td valign="middle" style="overflow-wrap: break-word; font-weight: normal; padding: 25px 0px; margin: 0px; font-size: 15px; color: rgb(51, 51, 51);line-height: 20px; border-bottom: 1px solid rgb(226, 226, 226); vertical-align: middle; border-collapse: collapse;"> '.$field_val.' </td>';
            $message .= '</tr>';
        }
		return $message.'</table>';
	}
    public function get_default_field_name( $field_id ) {

		return sprintf( /* translators: %1$d - field ID. */
			esc_html__( 'Field ID #%1$d', 'wpforms-lite' ),
			absint( $field_id )
		);
	}
    public function process_special_field_values( $field ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity

		$field_name = null;
		$field_val  = null;

		// Use a switch-case statement to handle specific field types.
		switch ( $field['type'] ) {
			case 'divider':
				$field_name = ! empty( $field['label'] ) ? str_repeat( '&mdash;', 3 ) . ' ' . $field['label'] . ' ' . str_repeat( '&mdash;', 3 ) : null;
				$field_val  = ! empty( $field['description'] ) ? $field['description'] : '';
				break;

			case 'pagebreak':
				// Skip if position is 'bottom'.
				if ( ! empty( $field['position'] ) && $field['position'] === 'bottom' ) {
					break;
				}

				$title      = ! empty( $field['title'] ) ? $field['title'] : esc_html__( 'Page Break', 'wpforms-lite' );
				$field_name = str_repeat( '&mdash;', 6 ) . ' ' . $title . ' ' . str_repeat( '&mdash;', 6 );
				break;

			case 'html':
				// Skip if the field is conditionally hidden.
				if ( $this->is_field_conditionally_hidden( $field['id'] ) ) {
					break;
				}

				$field_name = ! empty( $field['name'] ) ? $field['name'] : esc_html__( 'HTML / Code Block', 'wpforms-lite' );
				$field_val  = $field['code'];
				break;

			case 'content':
				// Skip if the field is conditionally hidden.
				if ( $this->is_field_conditionally_hidden( $field['id'] ) ) {
					break;
				}

				$field_name = esc_html__( 'Content', 'wpforms-lite' );
				$field_val  = $field['content'];
				break;

			default:
				$field_name = '';
				$field_val  = '';
				break;
		}

		return [ $field_name, $field_val ];
	}
    public function is_field_conditionally_hidden( $field_id ) {

		return ! empty( $this->form_data['fields'][ $field_id ]['conditionals'] ) && ! wpforms_conditional_logic_fields()->field_is_visible( $this->form_data, $field_id );
	}
    public function wrap_content_with_table_row( $content,$form_data,$fields,$entry_id ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		// If the content is empty, return it as is.
		if ( empty( $content ) ) {
			return $content;
		}
        
		// Process the smart tags in the content.
		$processed_content = $this->process_tag( $content,$form_data,$fields,$entry_id );
		// If the content doesn't contain any smart tags, wrap it in a table row, and return early.
		// Don't go beyond this point if the content doesn't contain any smart tags.
		if ( ! preg_match( '/{\w+}/', $processed_content ) ) {
			//return '<tr class="smart-tag"><td class="field-name field-value" colspan="2">' . $processed_content . '</td></tr>';
            return $processed_content;
		}
		// Split the content into lines and remove empty lines.
		$lines = array_filter( explode( "\n", $content ), 'strlen' );

		// Initialize an empty string to store the modified content.
		$modified_content = '';

		// Iterate through each line.
		foreach ( $lines as $line ) {
			// Trim the line.
			$trimmed_line = $this->process_tag( trim( $line ),$form_data,$fields,$entry_id );

			// Extract tags at the beginning of the line.
			preg_match( '/^(?:\{[^}]+}\s*)+/i', $trimmed_line, $before_line_tags );

			if ( ! empty( $before_line_tags[0] ) ) {
				// Include the extracted tags at the beginning to the modified content.
				$modified_content .= trim( $before_line_tags[0] );
				// Remove the extracted tags from the trimmed line.
				$trimmed_line = trim( substr( $trimmed_line, strlen( $before_line_tags[0] ) ) );
			}

			// Extract all smart tags from the remaining content.
			preg_match_all( '/\{([^}]+)}/i', $trimmed_line, $after_line_tags );

			// Remove the smart tags from the content.
			$content_without_smart_tags = str_replace( $after_line_tags[0], '', $trimmed_line );

			if ( ! empty( $content_without_smart_tags ) ) {
				// Wrap the content without the smart tags in a new table row.
				//$modified_content .= '<tr class="smart-tag"><td class="field-name field-value" colspan="2">' . $content_without_smart_tags . '</td></tr>';
				$modified_content .= $content_without_smart_tags;
			}

			if ( ! empty( $after_line_tags[0] ) ) {
				// Move all smart tags to the end of the line after the closing </tr> tag.
				$modified_content .= implode( ' ', $after_line_tags[0] );
			}
		}
		// Return the modified content.
		return $modified_content;
    }
    public function process_tag( $input = '',$form_data='',$fields='',$entry_id='' ) {
		return wpforms_process_smart_tags( $input, $form_data,$fields,$entry_id);
	}
}
new Yeemail_Addons_Wpforms;