<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link  https://club.wpeka.com
 * @since 1.0.0
 *
 * @package    Surveyfunnel_Lite
 * @subpackage Surveyfunnel_Lite/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Surveyfunnel_Lite
 * @subpackage Surveyfunnel_Lite/public
 * @author     WPEka Club <support@wpeka.com>
 */
class Surveyfunnel_Lite_Public {


	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name       The name of the plugin.
	 * @param string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style(
			$this->plugin_name . '-public',
			plugin_dir_url( __FILE__ ) . 'css/surveyfunnel-lite-public.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/surveyfunnel-lite-public.js',
			array( 'jquery' ),
			$this->version,
			false
		);

		wp_localize_script(
			$this->plugin_name,
			'ajaxData',
			array(
				'ajaxURL'      => admin_url( 'admin-ajax.php' ),
				'ajaxSecurity' => wp_create_nonce( 'surveyfunnel-lite-security' ),
			)
		);

		wp_register_script(
			$this->plugin_name . '-survey',
			SURVEYFUNNEL_LITE_PLUGIN_URL . 'dist/survey.bundle.js',
			array( 'wp-hooks' ),
			time(),
			false
		);
	}

	/**
	 * Register scripts for gutenberg block.
	 *
	 * @since 1.0.0
	 */
	public function surveyfunnel_lite_register_gutenberg_scripts() {
		wp_enqueue_style(
			$this->plugin_name . 'public',
			plugin_dir_url( __FILE__ ) . 'css/surveyfunnel-lite-public.css',
			array(),
			$this->version,
			'all'
		);

	}

	/**
	 * Public init of wpsf.
	 * Public init of surveyfunnel-lite.
	 */
	public function surveyfunnel_lite_public_init() {
		add_shortcode( 'surveyfunnel_lite_survey', array( $this, 'surveyfunnel_lite_survey_shortcode_render' ) );
	}

	/**
	 * Display survey at the frontend.
	 */
	public function surveyfunnel_lite_survey_shortcode_render( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'     => 0,
				'type'   => 'responsive',
				'width'  => '100%',
				'height' => '700px',
			),
			$atts
		);
		return $this->surveyfunnel_lite_display_survey( $atts );
	}

	/**
	 * Display function of survey.
	 *
	 * Can be called from shortcode or gutenberg blocks.
	 * this is where survey will be displayed in the frontend.
	 */
	public function surveyfunnel_lite_display_survey( $atts ) {
		// all of the null checks.
		if ( intval( $atts['id'] ) === 0 ) {
			return '';
		}

		// if page is not singular.
		if ( ! ( is_singular() ) ) {
			return '';
		}

		// if the survey is not published.
		if ( get_post_status( $atts['id'] ) !== 'publish' ) {
			return '';
		}

		// if survey is completed at the users end.
		if ( isset( $_COOKIE['surveyfunnel-lite-completed'] ) ) {
			$match = '/' . $atts['id'] . '/';
			if ( preg_match( $match, sanitize_text_field( wp_unslash( $_COOKIE['surveyfunnel-lite-completed'] ) ) ) ) {
				return '';
			}
		}

		// if survey was dismissed by the user.
		if ( isset( $_COOKIE['surveyfunnel-lite-dismiss'] ) ) {
			$match = '/' . $atts['id'] . '/';
			if ( preg_match( $match, sanitize_text_field( wp_unslash( $_COOKIE['surveyfunnel-lite-dismiss'] ) ) ) ) {
				return '';
			}
		}

		$survey_type = get_post_meta( $atts['id'], 'surveyfunnel-lite-type', true );
		$flag        = apply_filters( 'surveyfunnel_pro_activated', false );
		// if pro is not activated and current survey type is scoring or outcome.
		if ( $survey_type !== 'basic' && ! $flag ) {
			return '';
		}

		// get survey data.
		$defaults  = Surveyfunnel_Lite_Admin::surveyfunnel_lite_get_default_save_array();
		$meta_data = get_post_meta( $atts['id'], 'surveyfunnel-lite-data', true );
		$meta_data = wp_parse_args( $meta_data, $defaults );
		$share     = json_decode( $meta_data['share'] );
		if ( ! $share->popup->active && $atts['type'] === 'popup' ) {
			return '';
		}
		$ip        = $_SERVER['REMOTE_ADDR'];//phpcs:ignore
		$m_time = time() * 1000000;

		$unique_id = md5( $ip . $m_time . wp_rand( 0, time() ) );
		// generate unique id which will be used in reports page.
		$time      = time();
		// set the data.
		$data      = array(
			'ajaxURL'         => admin_url( 'admin-ajax.php' ),
			'ajaxSecurity'    => wp_create_nonce( 'surveyfunnel-lite-security' ),
			'post_id'         => $atts['id'],
			'time'            => $time,
			'userLocalID'     => $unique_id,
			'styleSurveyLink' => SURVEYFUNNEL_LITE_PLUGIN_URL . 'dist/survey.css',
			'type'            => $atts['type'],
			'surveyType'      => get_post_meta( $atts['id'], 'surveyfunnel-lite-type', true ),
			'width'           => $atts['width'],
			'height'          => $atts['height'],
			'share'           => $meta_data['share'],
		);

		$design_image_id = get_post_meta( $atts['id'], 'surveyfunnel-lite-design-background', true );
		if ( $design_image_id ) {
			$data['designImageUrl'] = $design_image_id;
		} else {
			$data['designImageUrl'] = null;
		}

		// get the styles and scripts which will be used inside the iframe.
		$configure_data = $atts['type'] === 'popup' ? $data['share'] : '';
		$data           = wp_json_encode( $data );
		$script_string  = SURVEYFUNNEL_LITE_PLUGIN_URL . 'dist/survey.bundle.js';
		$style_string   = plugin_dir_url( __FILE__ ) . 'css/surveyfunnel-lite-public.css';
		$hooks_string   = get_site_url() . '/wp-includes/js/dist/hooks.js?ver=' . time();
		wp_enqueue_style( $this->plugin_name . '-public' );
		$survey_style_string = SURVEYFUNNEL_LITE_PLUGIN_URL . 'dist/survey.css';
		$pro_script_string   = '';
		$pro_script_string   = apply_filters( 'surveyfunnel_lite_display_survey', $pro_script_string );
		$return_string       = '';
		if ( $atts['type'] === 'custom' ) {
			$return_string .= '<style>#surveyfunnel-lite-survey-' . $unique_id . ' iframe { max-width: 100%; height: ' . $atts['height'] . '; width: ' . $atts['width'] . ';  }</style>';
		}
		// this return string contains iframewrapper and html content which will be used in the js file to create iframe in the frontend.
		$return_string .= '<div class="iframewrapper intrinsic-ignore" post_id="' . $atts['id'] . '" id="surveyfunnel-lite-survey-' . $unique_id . '" survey-type="' . $atts['type'] . '" config-settings=\'' . $configure_data . '\' data-content=\'<!DOCTYPE html><html><head><script src="' . $hooks_string . '"></script>' . $pro_script_string . '<style>*{margin: 0; padding:0; box-sizing: border-box;}</style><script>var data = ' . $data . ';</script><link rel="stylesheet" href="' . $survey_style_string . '"><link rel="stylesheet" href="' . $style_string . '"></head><body><div id="surveyfunnel-lite-survey-' . $unique_id . '" style="width: 100vw; height: 100vh;"><script src="' . $script_string . '"></script></div></body></html>\'></div>';
		return $return_string;
	}

	/**
	 * Ajax call to get display data.
	 */
	public function surveyfunnel_lite_get_display_data() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		$post_id   = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$defaults  = Surveyfunnel_Lite_Admin::surveyfunnel_lite_get_default_save_array();
		$meta_data = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
		$meta_data = wp_parse_args( $meta_data, $defaults );
		$data      = array(
			'build'     => $meta_data['build'],
			'design'    => $meta_data['design'],
			'share'     => $meta_data['share'],
			'configure' => $meta_data['configure'],
			'proActive' => apply_filters( 'surveyfunnel_pro_activated', false ),
		);
		wp_send_json_success( $data );
		wp_die();
	}

	/**
	 * Ajax when new survey lead is generated in front end
	 */
	public function surveyfunnel_lite_new_survey_lead() {

		// every time user interacts with the survey i.e. on answering the question and clicking on next this action gets fired.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// get the appropriate data from $_POST with user_locale_id and time_created.
		$survey_id      = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$user_locale_id = isset( $_POST['userLocalID'] ) ? sanitize_text_field( wp_unslash( $_POST['userLocalID'] ) ) : 0;
		$time           = isset( $_POST['time'] ) ? intval( $_POST['time'] ) : 0;
		$tab_count      = isset( $_POST['completed'] ) ? intval( $_POST['completed'] ) : 0;
		$user_id        = get_current_user_id();

		$fields = $this->surveyfunnel_lite_sanitize_survey_lead( $_POST['data'] );//phpcs:ignore
		// $fields = wp_json_encode( array( $fields->_id => $fields ) );
		global $wpdb;
		$table_name = $wpdb->prefix . 'srf_entries';
		// get field value from database if exist.
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT * 
				FROM ' . $table_name . '
				WHERE user_locale_id = %s AND time_created = %d
			',
				$user_locale_id,
				$time
			)
		);
		$flag = false;
		if ( is_array( $rows ) && count( $rows ) ) {
			$data          = json_decode( $rows[0]->fields );
			$id            = $fields->_id;
			$data->$id     = $fields;
			$current_count = count( (array) $data );

			if ( $current_count !== $tab_count ) {
				$completed = 0;
			} else {
				$completed = 1;
			}

			$fields = wp_json_encode( $data );
			$flag   = true;
		}
		if ( $flag ) {
			$rows = $wpdb->query(
				$wpdb->prepare(
					'
					UPDATE ' . $table_name . ' SET `fields` = %s, `user_meta` = %s
					WHERE user_locale_id = %s AND time_created = %d
				',
					$fields,
					$completed,
					$user_locale_id,
					$time
				)
			);

			if ( ! $rows ) {
				wp_send_json_error();
				wp_die();
			}
		} else {
			$fields = wp_json_encode( array( $fields->_id => $fields ) );
			$date   = gmdate( 'Y-m-d' );
			$rows   = $wpdb->query(
				$wpdb->prepare(
					'
					INSERT INTO ' . $table_name . ' ( `survey_id`, `user_id`, `fields`, `user_locale_id`, `time_created`, `date_created`, `user_meta` )
					VALUES (%d, %d, %s, %s, %d, %s, 0)
				',
					$survey_id,
					$user_id,
					$fields,
					$user_locale_id,
					$time,
					$date
				)
			);

			if ( ! $rows ) {
				wp_send_json_error();
				wp_die();
			}
		}

		wp_send_json_success();
		wp_die();
	}

	/**
	 * Sanitize survey leads.
	 *
	 * @param string $data json data.
	 */
	public function surveyfunnel_lite_sanitize_survey_lead( $data ) {
		$data = json_decode( stripslashes( $data ) );
		foreach ( $data as $key => $value ) {
			switch ( $key ) {
				case '_id':
				case 'status':
				case 'question':
					$value = sanitize_text_field( wp_unslash( $value ) );
					break;
				case 'answer':
					if ( is_array( $value ) ) {
						foreach ( $value as $row ) {
							$row = sanitize_text_field( wp_unslash( $row->name ) );
						}
					} else {
						$value = sanitize_text_field( wp_unslash( $value ) );
					}
					break;
				default:
					break;
			}
		}
		return $data;
	}

	/**
	 * The content hook to add popup.
	 *
	 * @param string $content html content of the frontend.
	 */
	public function surveyfunnel_lite_the_content( $content ) {

		if ( ! Surveyfunnel_Lite::is_request( 'frontend' ) ) {
			return;
		}

		global $wp_query;
		$post_id = $wp_query->post->ID;

		$args = array(
			'post_type'   => 'wpsf-survey',
			'post_status' => 'publish',
			'numberposts' => -1,
		);

		$surveys = get_posts( $args );

		foreach ( $surveys as $survey ) {
			$meta_data = get_post_meta( $survey->ID, 'surveyfunnel-lite-data', true );
			$share     = json_decode( $meta_data['share'] );
			if ( ! $share ) {
				continue;
			}

			// check for conditions.
			$show_popup = $this->surveyfunnel_lite_check_popup_conditions( $share, $post_id );

			if ( $show_popup ) {
				$content .= '[surveyfunnel_lite_survey id=' . $survey->ID . ' type="popup"]';
			}
		}

		return $content;
	}


	/**
	 * The content hook to add popup.
	 *
	 * @param object  $share Share Settings.
	 * @param integer $post_id current post id.
	 */
	public function surveyfunnel_lite_check_popup_conditions( $share, $post_id ) {
		$flag = false;
		switch ( $share->popup->targettingOptions->triggerPage ) {
			case 'triggerOnAll':
				$flag = true;
				break;
			case 'triggerOnSpecific':
				foreach ( $share->popup->targettingOptions->selectedPagesAndPosts as $posts ) {
					if ( $posts->value === $post_id ) {
						$flag = true;
						break;
					}
				}
				break;
			default:
				$flag = false;
				break;
		}
		// check for devices.
		$devices = array();
		if ( $flag ) {
			foreach ( $share->popup->targettingOptions->devices as $device ) {
				if ( $device->checked ) {
					array_push( $devices, $device->id );
				}
			}
			$flag = self::surveyfunnel_lite_verify_device( $devices );
		}
		return $flag;
	}

	/**
	 * Verifies if the survey should be displayed on the device.
	 *
	 * @param array $devices contains device names.
	 *
	 * @since 1.0.0
	 *
	 * @return bool return true or false depending upon the device.
	 */
	public static function surveyfunnel_lite_verify_device( $devices ) {

		if ( ! class_exists( 'Mobile_Detect' ) ) {
			/**
			 * The class responsible for devices detection.
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';
		}

		$detect = new Mobile_Detect();
		if ( ! $detect->isMobile() && ! $detect->isTablet() && in_array( 'desktop', $devices, true ) ||
					$detect->isTablet() && in_array( 'tablet', $devices, true ) ||
					$detect->isMobile() && ! $detect->isTablet() && in_array( 'mobile', $devices, true ) ) {
			if ( ! $detect->isMobile() && ! $detect->isTablet() ) {
				if ( in_array( 'desktop', $devices, true ) ) {
					return true;
				} else {
					return false;
				}
			} elseif ( $detect->isTablet() ) {
				if ( in_array( 'tablet', $devices, true ) ) {
					return true;
				} else {
					return false;
				}
			} elseif ( $detect->isMobile() && ! $detect->isTablet() ) {
				if ( in_array( 'mobile', $devices, true ) ) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}

	}
	public function surveyfunnel_lite_register_rest_api(){
		$rest_api_class=new Surveyfunnel_Lite_Rest_Api();
		$rest_api_class->init();
	}
}
