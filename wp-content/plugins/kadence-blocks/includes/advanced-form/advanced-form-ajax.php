<?php

/**
 * Advanced Form Ajax Handing.
 *
 * @package Kadence Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class
 */
class KB_Ajax_Advanced_Form {

	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	private static $redirect = false;

	private static $fields = [];

	private static $captcha_attrs = false;

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_kb_process_advanced_form_submit', array( $this, 'process_ajax' ) );
		add_action( 'wp_ajax_nopriv_kb_process_advanced_form_submit', array( $this, 'process_ajax' ) );
	}

	/**
	 * Process the form submit.
	 */
	public function process_ajax() {
		$final_data = array();

		if ( isset( $_POST['_kb_adv_form_id'] ) && ! empty( $_POST['_kb_adv_form_id'] ) && isset( $_POST['_kb_adv_form_post_id'] ) && ! empty( $_POST['_kb_adv_form_post_id'] ) ) {
			$this->start_buffer();

			if ( apply_filters( 'kadence_blocks_form_verify_nonce', is_user_logged_in() ) && ! check_ajax_referer( 'kb_form_nonce', '_kb_form_verify', false ) ) {
				$this->process_bail( __( 'Submission rejected, invalid security token. Reload the page and try again.', 'kadence-blocks' ), __( 'Token invalid', 'kadence-blocks' ) );
			}
			$post_id = sanitize_text_field( wp_unslash( $_POST['_kb_adv_form_post_id'] ) );

			$form_args = $this->get_form( $post_id );
			$messages  = $this->get_messages( $form_args['attributes'] );

			// Check Recaptcha.
			if ( self::$captcha_attrs !== false ) {
				$captcha_settings = new Kadence_Blocks_Form_Captcha_Settings( self::$captcha_attrs );

				if ( $captcha_settings->is_valid ) {
					$captcha_verify = new Kadence_Blocks_Form_Captcha_Verify( $captcha_settings );

					$valid = false;
					switch ( $captcha_settings->service ) {
						case 'googlev3':
							$token = ! empty( $_POST['recaptcha_response'] ) ? $_POST['recaptcha_response'] : '';
							$valid = $captcha_verify->verify_google( $token );
							break;
						case 'googlev2':
							$token = ! empty( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : '';
							$valid = $captcha_verify->verify_google( $token );
							break;
						case 'turnstile':
							$token = ! empty( $_POST['cf-turnstile-response'] ) ? $_POST['cf-turnstile-response'] : '';
							$valid = $captcha_verify->verify_turnstile( $token );
							break;
						case 'hcaptcha':
							$token = ! empty( $_POST['h-captcha-response'] ) ? $_POST['h-captcha-response'] : '';
							$valid = $captcha_verify->verify_hcaptcha( $token );
							break;
					}

					if ( ! $valid ) {
						$this->process_bail( $messages['recaptchaerror'], __( 'CAPTCHA Failed', 'kadence-blocks' ) );
					}

					unset( $_POST['recaptcha_response'], $_POST['g-recaptcha-response'], $_POST['cf-turnstile-response'], $_POST['h-captcha-response'] );
				}
			}

			$processed_fields = $this->process_fields( $form_args['fields'] );

			do_action( 'kadence_blocks_advanced_form_submission', $form_args, $processed_fields, $post_id );

			$submission_results = $this->after_submit_actions( $form_args, $processed_fields, $post_id );

			if ( self::$redirect ) {
				$final_data['redirect'] = self::$redirect;
			}
			if ( isset( $form_args['attributes']['submitHide'] ) && true == $form_args['attributes']['submitHide'] ) {
				$final_data['hide'] = true;
			}

			$success = isset( $submission_results['success'] ) && $submission_results['success'];
			$final_data['submissionResults'] = $submission_results;

			$success  = apply_filters( 'kadence_blocks_advanced_form_submission_success', $success, $form_args, $processed_fields, $post_id, $submission_results );
			$messages = apply_filters( 'kadence_blocks_advanced_form_submission_messages', $messages, $form_args, $processed_fields, $post_id, $submission_results );

			if ( ! $success ) {
				$this->process_bail( $messages['error'], __( 'Third Party Failed', 'kadence-blocks' ) );
			} else {
				$final_data['html'] = '<div class="kb-adv-form-message kb-adv-form-success">' . $messages['success'] . '</div>';
				$this->send_json( $final_data );
			}
		} else {
			$this->process_bail( __( 'Submission failed', 'kadence-blocks' ), __( 'No Data', 'kadence-blocks' ) );
		}
	}

	/**
	 * Sanitize the field
	 *
	 * @param string $field_type the field type.
	 * @param mixed  $value      the field value.
	 */
	private function sanitize_field( $field_type, $value, $multi_select = false ) {
		switch ( $field_type ) {
			case 'text':
			case 'tel':
			case 'password':
			case 'hidden':
			case 'search':
			case 'select':
				$value = ( $multi_select && is_array( $value ) ? sanitize_text_field( implode( ', ', $value ) ) : sanitize_text_field( $value ) );
				break;
			case 'checkbox':
				$value = ( is_array( $value ) ? sanitize_text_field( implode( ', ', $value ) ) : sanitize_text_field( $value ) );
				break;
			case 'radio':
				$value = ( is_array( $value ) ? sanitize_text_field( implode( ', ', $value ) ) : sanitize_text_field( $value ) );
				break;
			case 'url':
				$value = esc_url_raw( trim( $value ) );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $value );
				break;
			case 'email':
				$value = sanitize_email( trim( $value ) );
				break;
			case 'accept':
				$value = esc_html__( 'Accept', 'kadence-blocks' );
				break;
			default:
				/**
				 * Sanitize field value.
				 * Filters the value of the form field for sanitization purpose.
				 * The dynamic portion of the hook name, `$field_type`, refers to the field type.
				 *
				 * @param string $value The field value.
				 */
				$value = apply_filters( "kadence_blocks_form_sanitize_{$field_type}", $value );
		}

		return $value;
	}
	/**
	 * Process the submit actions.
	 *
	 * @param array $form_args the form args.
	 * @param array $processed_fields the processed fields.
	 * @param int   $post_id the post id.
	 */
	public function after_submit_actions( $form_args, $processed_fields, $post_id ) {

		$submission_results = array( 'success' => true );
		$actions = isset( $form_args['attributes']['actions'] ) ? $form_args['attributes']['actions'] : array( 'email' );

		$submit_actions = new Kadence_Blocks_Advanced_Form_Submit_Actions( $form_args, $processed_fields, $post_id );

		foreach ( $actions as $action ) {
			switch ( $action ) {
				case 'email':
					$submit_actions->email();
					break;
				case 'redirect':
					if ( isset( $form_args['attributes']['redirect'] ) && ! empty( trim( $form_args['attributes']['redirect'] ) ) ) {
						self::$redirect = apply_filters( 'kadence_blocks_advanced_form_redirect', trim( $form_args['attributes']['redirect'] ), $form_args, $processed_fields, $post_id );
					}
					break;
				case 'mailerlite':
					$submit_actions->mailerlite();
					break;
				case 'fluentCRM':
					$submit_actions->fluentCRM();
					break;
			}
		}
		$submission_results = apply_filters( 'kadence_advanced_form_actions', $submission_results, $actions, $form_args, $processed_fields, $post_id );

		return $submission_results;
	}
	/**
	 * Process the fields
	 *
	 * @param array $fields the fields.
	 */
	public function process_fields( $fields ) {

		$processed_fields = array();

		foreach ( $fields as $index => $field ) {
			$expected_field = ! empty( $field['inputName'] ) ? $field['inputName'] : 'field' . $field['uniqueID'];
			// Fail if required field is missing.
			if ( empty( $_POST[ $expected_field ] ) && ! empty( $field['required'] ) && $field['required'] && $field['type'] !== 'file' ) {
				$required_message = ! empty( $field['required_message'] ) ? $field['required_message'] : __( 'Missing a required field', 'kadence-blocks' );
				$this->process_bail( __( 'Submission Failed', 'kadence-blocks' ), $required_message );
			}

			$value = $this->sanitize_field( $field['type'], isset( $_POST[ $expected_field ] ) ? $_POST[ $expected_field ] : '', empty( $field['multiple'] ) ? false : $field['multiple'] );

			// If field is file, verify and process the file.
			if ( $field['type'] === 'file' ) {
				// File required & skipped.
				if ( empty( $_FILES[ $expected_field ]['size'] ) && ! empty( $field['required'] ) && $field['required'] ) {
					$required_message = ! empty( $field['required_message'] ) ? $field['required_message'] : __( 'Missing a required field', 'kadence-blocks' );

					$this->process_bail( __( 'Submission Failed', 'kadence-blocks' ), $required_message );
				} else if ( empty( $_FILES[ $expected_field ]['size'] ) ){
					continue;
				}

				$max_upload_size_mb    = empty( $field['maxSizeMb'] ) ? 10 : $field['maxSizeMb'];
				$max_upload_size_bytes = $max_upload_size_mb * pow( 1024, 2 );

				// Was file too big
				if ( $_FILES[ $expected_field ]['size'] > $max_upload_size_bytes ) {
					$this->process_bail( __( 'Submission Failed. File too Large', 'kadence-blocks' ), __( 'File too large', 'kadence-blocks' ) );
				}

				if ( ! is_uploaded_file( $_FILES[ $expected_field ]['tmp_name'] ) ) {
					$this->process_bail( __( 'Submission Failed. File could not be uploaded', 'kadence-blocks' ), __( 'File was not uploaded', 'kadence-blocks' ) );
				}

				$allowed_file_categories = empty( $field['allowedTypes'] ) ? array( 'images' ) : $field['allowedTypes'];
				$allowed_file_mimes      = $this->get_allowed_mimes( $allowed_file_categories );

				$file_size = filesize( $_FILES[ $expected_field ]['tmp_name'] );

				// Check if multisite has a quota
				if ( is_multisite() ) {
					require_once( ABSPATH . 'wp-includes/ms-functions.php' );
					require_once( ABSPATH . 'wp-admin/includes/ms.php' );

					$space = get_upload_space_available();
					if ( $space < $file_size || upload_is_user_over_quota( false ) ) {
						$this->process_bail( __( 'Submission Failed. Not enough disk quota on this website.', 'kadence-blocks' ), __( 'Not enough disk quota on this website.', 'kadence-blocks' ) );
					}
				}
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				add_filter( 'kb_process_advanced_form_submit_prefilter', array( $this, 'override_upload_directory' ) );
				$file_upload = wp_handle_upload(
					$_FILES[ $expected_field ],
					array(
						'action'                   => 'kb_process_advanced_form_submit',
						'unique_filename_callback' => array( $this, 'set_custom_upload_unique_filename' ),
						'test_form'                => false,
						'test_type'                => true,
						'mimes'                    => $allowed_file_mimes,
					)
				);
				if ( isset( $file_upload['url'] ) ) {
					$this->add_htaccess_to_uploads_root();
					$value = $file_upload['url'];
				} else {
					if ( ! empty( $file_upload['error'] ) ) {
						$this->process_bail( $file_upload['error'], __( 'Failed to upload file', 'kadence-blocks' ) );
					} else {
						$this->process_bail( __( 'Submission Failed. Failed to upload file', 'kadence-blocks' ), __( 'Failed to upload file', 'kadence-blocks' ) );
					}
				}
			}

			$processed_fields[] = array(
				'label'    => ( ! empty( $field['label'] ) ? $field['label'] : '' ),
				'type'     => $field['type'],
				'required' => empty( $field['required'] ) ? false : $field['required'],
				'value'    => $value,
				'uniqueID' => $field['uniqueID'],
				'name'     => $expected_field,
				'file_name'=> !empty( $_FILES[ $expected_field ]['name'] ) ? $_FILES[ $expected_field ]['name'] : '',
			);
		}

		return $processed_fields;
	}

	/**
	 * Bail out something isn't correct.
	 *
	 * @param string $error  Error to display.
	 * @param string $note   Note to show in console.
	 * @param string $action action to take if any.
	 */
	public function process_bail( $error, $note, $required = null ) {
		$notices                  = array();
		$notices['error']['note'] = $error;
		$out                      = array(
			'html'     => $this->html_from_notices( $notices ),
			'console'  => $note,
			'required' => $required,
		);
		$this->send_json( $out, true );
	}


	/**
	 * Create HTML string from notices
	 *
	 * @param array $notices Notices to display.
	 *
	 * @return string
	 */
	public function html_from_notices( $notices = array() ) {
		$html = '';
		foreach ( $notices as $note_type => $notice ) {
			if ( ! empty( $notice['note'] ) ) {
				$html .= '<div class="kb-adv-form-message kb-adv-form-warning">' . $notice['note'] . '</div>';
			}
		}

		return $html;
	}

	/**
	 * Starts a flushable buffer
	 */
	public function start_buffer() {
		if ( ! did_action( 'kadence_blocks_forms_buffer_started' ) ) {

			ob_start();

			/**
			 * Runs when buffer is started
			 *
			 * Used to prevent starting buffer twice
			 */
			do_action( 'kadence_blocks_forms_buffer_started' );
		}
	}

	/**
	 * Wrapper for wp_send_json() with output buffering
	 *
	 * @since 1.8.0
	 *
	 * @param array $data     Data to return
	 * @param bool  $is_error Optional. Is this an error. Default false.
	 */
	public function send_json( $data = array(), $is_error = false ) {
		$buffer = ob_get_clean();
		/**
		 * Runs before Kadence Blocks Forms returns json via wp_send_json() exposes output buffer
		 *
		 * @param string|null $buffer   Buffer contents
		 * @param bool        $is_error If we think this is an error response or not.
		 */
		do_action( 'kadence_blocks_forms_buffer_flushed', $buffer, $is_error );
		$data['headers_sent'] = headers_sent();
		if ( ! $is_error ) {
			//status_header( 200 );
			$data['success']      = true;
			$data['show_message'] = true;
			wp_send_json( $data );
		} else {
			wp_send_json_error( $data );
		}
	}
	/**
	 * Get Allowed Mime Types.
	 *
	 * @param array $categories an array of category names.
	 */
	public function get_allowed_mimes( $categories ) {
		$allowed_mime_types = array();
		$mimtypes = array(
			'image'     => array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
			),
			'images'     => array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
			),
			'pdf'       => array(
				'pdf' => 'application/pdf',
			),
			'video'     => array(
				'mp4|m4v'      => 'video/mp4',
				'mpeg|mpg|mpe' => 'video/mpeg',
				'mov|qt'       => 'video/quicktime',
				'wmv'          => 'video/x-ms-wmv',
			),
			'audio'     => array(
				'mp3|m4a|m4b' => 'audio/mpeg',
				'aac'         => 'audio/aac',
				'wav'         => 'audio/wav',
				'ra|ram'      => 'audio/x-realaudio',
				'mid|midi'    => 'aaudio/midi',
				'mka'         => 'audio/x-matroska',
				'wma'         => 'audio/x-ms-wma',
				'wax'         => 'audio/x-ms-wax',
				'ogg|oga'     => 'audio/ogg',
			),
			'documents' => array(
				'txt|asc|c|cc|h|srt'           => 'text/plain',
				'csv'                          => 'text/csv',
				'doc'                          => 'application/msword',
				'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
				'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
				'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
				'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
				'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
				'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
				'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
				'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
				'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
				'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
				'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
				'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
				'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
				'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
				'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
				'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
				'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
				'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
				'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
				'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
				'oxps'                         => 'application/oxps',
				'xps'                          => 'application/vnd.ms-xpsdocument',
				'odt'                          => 'application/vnd.oasis.opendocument.text',
				'odp'                          => 'application/vnd.oasis.opendocument.presentation',
				'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
				'odg'                          => 'application/vnd.oasis.opendocument.graphics',
				'odc'                          => 'application/vnd.oasis.opendocument.chart',
				'odb'                          => 'application/vnd.oasis.opendocument.database',
				'odf'                          => 'application/vnd.oasis.opendocument.formula',
				'wp|wpd'                       => 'application/wordperfect',
				'key'                          => 'application/vnd.apple.keynote',
				'numbers'                      => 'application/vnd.apple.numbers',
				'pages'                        => 'application/vnd.apple.pages',
			),
			'archive' => array(
				'zip'  => 'application/zip',
			),
		);
		foreach ( $categories as $category ) {
			if ( isset( $mimtypes[ $category ] ) ) {
				$allowed_mime_types = array_merge( $allowed_mime_types, $mimtypes[ $category ] );
			}
		}

		return $allowed_mime_types;
	}
	/**
	 * Add filter to override the upload directory for form submissions.
	 *
	 * @param array $file the file.
	 */
	public function override_upload_directory( $file ) {
		add_filter( 'upload_dir', array( $this, 'set_custom_upload_directory' ), 10 );
		add_filter( 'wp_handle_upload', array( $this, 'remove_set_custom_upload_directory' ), 10, 2 );
		return $file;
	}
	/**
	 * Set the custom upload directory.
	 *
	 * @param array $param the upload directory params.
	 */
	public function set_custom_upload_directory( $param ) {
		$subfolder     = '/kadence_form/' . date( 'Y' ) . '/' . date( 'm' );
		$param['url']  = isset( $param['baseurl'] ) ? $param['baseurl'] . $subfolder : $subfolder;
		$param['path'] = isset( $param['basedir'] ) ? $param['basedir'] . $subfolder : $subfolder;
		return apply_filters( 'kadence_blocks_advanced_form_upload_directory', $param );
	}
	/**
	 * Remove the filter to override the upload directory for form submissions.
	 *
	 * @param array $fileinfo the file info.
	 * @param array $param the upload directory params.
	 */
	public function remove_set_custom_upload_directory( $fileinfo, $param ) {
		remove_filter( 'upload_dir', array( $this, 'set_custom_upload_directory' ), 10 );
		return $fileinfo;
	}
	/**
	 * Set custom file name with timestamp included.
	 *
	 * @param string $dir the upload directory.
	 * @param string $name the file name.
	 * @param string $ext the file extension.
	 */
	public function set_custom_upload_unique_filename( $dir, $name, $ext ) {
		$time_name = apply_filters( 'kadence_blocks_advanced_form_upload_file_name', time() . '_' . wp_generate_password( 16, false ) . '.' . $ext, $dir, $name );
		return wp_unique_filename( $dir, $time_name );
	}


	/**
	 * Get form details
	 *
	 * @param integer $post_id the form post id.
	 * @param string  $form_id the form id.
	 */
	private function get_form( $post_id ) {
		$form_args = array();
		$blocks    = '';

		$post_data = get_post( absint( $post_id ) );
		if ( is_object( $post_data ) ) {
			$blocks = parse_blocks( $post_data->post_content );
		}

		// No form field inner blocks found.
		if ( ! is_array( $blocks ) || empty( $blocks ) || ! isset( $blocks[0]['blockName'] ) || $blocks[0]['blockName'] !== 'kadence/advanced-form' || empty( $blocks[0]['innerBlocks'] ) ) {
			$this->process_bail( __( 'Submission Failed', 'kadence-blocks' ), __( 'Data not found', 'kadence-blocks' ) );
		}

		$post_meta = get_post_meta( $post_id );
		$meta_args = array();

		foreach ( $post_meta as $meta_key => $meta_value ) {
			if ( strpos( $meta_key, '_kad_form_' ) === 0 && isset( $meta_value[0] ) ){
				$meta_args[ str_replace( '_kad_form_', '', $meta_key ) ] = maybe_unserialize( $meta_value[0] );
			}
		}

		$form_args['attributes'] = json_decode( json_encode( $meta_args ), true );
		foreach ( $blocks[0]['innerBlocks'] as $block ) {
			$this->recursively_parse_blocks( $block );
		}
		$form_args['fields'] = self::$fields;

		return $form_args;
	}
	/**
	 * Gets all the field blocks that are in post.
	 *
	 * @access private
	 *
	 * @param string $block The page content content.
	 */
	private function recursively_parse_blocks( $block ) {
		if ( ! is_object( $block ) && is_array( $block ) && isset( $block['blockName'] ) ) {
			if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as $inner_block ) {
					$this->recursively_parse_blocks( $inner_block );
				}
			} else {
				if ( 'kadence/advanced-form-submit' !== $block['blockName'] && strpos( $block['blockName'], 'kadence/advanced-form-' ) === 0 ) {
					self::$fields[] = array_merge(
						$block['attrs'],
						array( 'type' => str_replace( 'kadence/advanced-form-', '', $block['blockName'] ) )
					);

					if ( $block['blockName'] === 'kadence/advanced-form-captcha' ) {
						self::$captcha_attrs = $block['attrs'];
					}
				}
			}
		}
	}
	/**
	 * Get form messages
	 *
	 * @param array $form_attributes the form attributes.
	 */
	private function get_messages( $form_attributes ) {
		$messages = array(
			'success'        => esc_html__( 'Submission Success, Thanks for getting in touch!', 'kadence-blocks' ),
			'error'          => esc_html__( 'Submission Failed', 'kadence-blocks' ),
			'recaptchaerror' => esc_html__( 'Submission Failed, reCaptcha spam prevention. Please reload your page and try again.', 'kadence-blocks' ),
		);

		if ( empty( $form_attributes['messages'] ) || ! is_array( $form_attributes['messages'] ) ) {
			return $messages;
		}

		foreach ( $form_attributes['messages'] as $key => $message ) {
			if ( ! empty( $message ) ) {
				$messages[ $key ] = $message;
			}
		}

		return $messages;
	}

	/**
	 * Create .htaccess in uploads folder to prevent PHP execution.
	 * This security precaution exists in case the server was misconfigured to execute unintended file extensions.
	 *
	 * @return void
	 */
	public function add_htaccess_to_uploads_root() {
		$wp_uploads = wp_upload_dir();
		$kadence_form_root_upload_dir = $this->get_kadence_form_root_upload_dir();

		if ( ! $wp_uploads['error'] ) {
			$htaccess_file = $wp_uploads['basedir'] . '/'. $kadence_form_root_upload_dir .'/.htaccess';
			$content       = '# Prevent PHP execution in this folder for all files in case server is misconfigured to execute unintended file extensions.
<Files *>
SetHandler none
SetHandler default-handler
Options -ExecCGI
RemoveHandler .cgi .php .php3 .php4 .php5 .phtml .pl .py .pyc .pyo
</Files>
<IfModule headers_module>
Header set X-Robots-Tag "noindex"
</IfModule>';
			$content_array = explode( "\n", $content );
			$content_array = apply_filters( 'kadence_blocks_form_upload_htaccess_rules', $content_array );
			insert_with_markers( $htaccess_file, 'Kadence Blocks', $content_array );
		}
	}

	/**
	 * Given the direct link to a file, return the url to the downloader.
	 *
	 * @param $file_url
	 *
	 * @return mixed
	 */
	public function get_downloader_url( $file_url ) {
		$kadence_form_root_upload_dir = $this->get_kadence_form_root_upload_dir();
		$file_path = explode($kadence_form_root_upload_dir, $file_url, 2);

		// Couldn't find the root directory just return the url
		if( !isset( $file_path[1]) ) {
			return $file_url;
		}

		$query_args = array(
			'kadence-form-download' => $kadence_form_root_upload_dir . $file_path[1]
		);

		return add_query_arg( $query_args, home_url() );
	}

	/**
	 * Given the direct link to a file, return the url to the downloader.
	 *
	 * @return mixed|string
	 */
	public function get_kadence_form_root_upload_dir() {
		$root_dir = 'kadence_form';

		$kadence_form_upload_dir = $this->set_custom_upload_directory( array() );
		if( !empty( $kadence_form_upload_dir['path'] ) ){
			$upload_dir_parts = explode( '/', $kadence_form_upload_dir['path'] );
			$upload_dir_parts = array_values( array_filter($upload_dir_parts) );
			$root_dir = $upload_dir_parts[0];
		}

		return $root_dir;
	}
}

KB_Ajax_Advanced_Form::get_instance();
