<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://club.wpeka.com
 * @since 1.0.0
 *
 * @package    Surveyfunnel_Lite
 * @subpackage Surveyfunnel_Lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Surveyfunnel_Lite
 * @subpackage Surveyfunnel_Lite/admin
 * @author     WPEka Club <support@wpeka.com>
 */
class Surveyfunnel_Lite_Admin {

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
	 * @param string $plugin_name       The name of this plugin.
	 * @param string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/surveyfunnel-lite-admin.css',
			array(),
			time(),
			'all'
		);

		wp_register_style(
			$this->plugin_name . '-mascot',
			plugin_dir_url( __FILE__ ) . 'css/surveyfunnel-lite-mascot.css',
			array(),
			time(),
			'all'
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/surveyfunnel-lite-admin.js',
			array( 'jquery' ),
			$this->version,
			false
		);

		wp_localize_script(
			$this->plugin_name,
			'ajax',
			array(
				'ajaxURL'      => admin_url( 'admin-ajax.php' ),
				'ajaxSecurity' => wp_create_nonce( 'surveySecurity' ),
			)
		);

		wp_register_script(
			$this->plugin_name . '-vue',
			plugin_dir_url( __FILE__ ) . 'js/vue/vue.min.js',
			array(),
			$this->version,
			false
		);

		wp_register_script(
			$this->plugin_name . '-mascot',
			plugin_dir_url( __FILE__ ) . 'js/surveyfunnel-lite-mascot.js',
			array( $this->plugin_name . '-vue' ),
			$this->version,
			false
		);
	}

	/**
	 * Register Survey Funnel Admin Menu.
	 *
	 * @since 1.0.0
	 */
	public function surveyfunnel_lite_admin_menu() {

		// dashboard page is nothing but react one page app to build surveys.
		add_dashboard_page( '', '', 'manage_options', 'surveyfunnel-lite', '' );

		add_menu_page(
			__( 'SurveyFunnel', 'surveyfunnel' ),
			__( 'SurveyFunnel', 'surveyfunnel' ),
			'manage_options',
			'surveyfunnel-lite-dashboard',
			'',
			SURVEYFUNNEL_LITE_PLUGIN_URL . 'images/SF-logo.png'
		);

		// Dashboard.
		add_submenu_page(
			'surveyfunnel-lite-dashboard',
			__( 'Dashboard', 'surveyfunnel' ),
			__( 'Dashboard', 'surveyfunnel' ),
			'manage_options',
			'surveyfunnel-lite-dashboard',
			array( $this, 'surveyfunnel_lite_dashboard' )
		);

		// Settings.
		/*
		 add_submenu_page(
			'surveyfunnel-lite-dashboard',
			__( 'Settings', 'surveyfunnel' ),
			__( 'Settings', 'surveyfunnel' ),
			'manage_options',
			'surveyfunnel-lite-settings',
			array( $this, 'surveyfunnel_lite_settings' )
		); */

		// Help.
		add_submenu_page(
			'surveyfunnel-lite-dashboard',
			__( 'Help', 'surveyfunnel' ),
			__( 'Help', 'surveyfunnel' ),
			'manage_options',
			'surveyfunnel-lite-help',
			array( $this, 'surveyfunnel_lite_help' )
		);
	}

	/**
	 * Settings submenu page callback.
	 *
	 * @since 1.0.0
	 */
	public function surveyfunnel_lite_settings() {
		echo '';
	}

	/**
	 * Help submenu page callback.
	 *
	 * @since 1.0.0
	 */
	public function surveyfunnel_lite_help() {
		wp_enqueue_style( $this->plugin_name );
		?>	
			<div class="surveyfunnel-lite-container-main">
				<div class="surveyfunnel-lite-header">
					<div class="surveyfunnel-lite-logo">
						<img src="<?php echo esc_url( SURVEYFUNNEL_LITE_PLUGIN_URL . 'images/surveyfunnel-lite-help-logo.png' ); ?>" alt="surveyfunnel-lite-main-logo">
					</div>
				</div>
				<div class="surveyfunnel-lite-inner-container">
				<div class="surveyfunnel-lite-section">
					<div class="surveyfunnel-lite-section-heading">
						<div class="surveyfunnel-lite-section-title">
							<p>Welcome‌ ‌to‌ ‌SurveyFunnel!‌</p>
						</div>
						<div class="surveyfunnel-lite-section-subtitle">
							<p>Complete Survey Management Plugin.</p>
						</div>
					</div>
					<div class="surveyfunnel-lite-section-content">
						<p>Thank‌ ‌you‌ ‌for‌ ‌choosing‌ ‌SurveyFunnel‌ ‌plugin.‌ ‌SurveyFunnel‌ ‌lets‌ ‌you‌ ‌create‌ ‌interesting‌ ‌surveys‌ ‌to‌ ‌keep‌ ‌your‌ ‌audience‌ ‌engaged,‌ ‌and‌ ‌collect‌ ‌qualified‌ ‌leads.‌ ‌With‌ ‌drag‌ ‌and‌ ‌drop‌ ‌features‌ ‌you‌ ‌can‌ ‌create‌ ‌a‌ ‌survey‌ ‌in‌ ‌minutes‌ ‌and‌ ‌get‌ ‌better‌ ‌insights‌ ‌about‌ ‌your‌ ‌audience.‌</p>
					</div>
				</div>
				<div class="surveyfunnel-lite-section">
					<a class="surveyfunnel-lite-button" href="<?php echo esc_url( admin_url() . 'admin.php?page=surveyfunnel-lite-dashboard' ); ?>">Create Your First Survey</a>
				</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Dashboard submenu page callback.
	 *
	 * @since 1.0.0
	 */
	public function surveyfunnel_lite_dashboard() {
		wp_enqueue_style( $this->plugin_name );
		// admin display dashboard contains all the html related code.
		include_once plugin_dir_path( __FILE__ ) . 'views/admin-display-dashboard-page.php';
	}

	/**
	 * Initialize surveyfunnel-lite functionalities.
	 *
	 * @since 1.0.0
	 */
	public function surveyfunnel_lite_init() {
		// no labels required.
		$labels = array();

		// register hidden post type 'surveyfunnel-lite'.
		$args = array(
			'label'               => __( 'surveyfunnel-lite', 'surveyfunnel' ),
			'description'         => __( 'surveyfunnel-lite custom post type to generate surveys', 'surveyfunnel' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
		);
		register_post_type( 'wpsf-survey', $args );

		$this->surveyfunnel_lite_update();
	}

	/**
	 * Admin init function.
	 */
	public function surveyfunnel_lite_update() {

		// fix: background image is lost after updating to '1.1.0'.

		$version = version_compare( SURVEYFUNNEL_LITE_VERSION, '1.1.1' );

		if ( $version >= 0 && ! get_option( 'srf-lite-background-update', false ) ) {
			$posts = get_posts(
				array(
					'post_type'   => 'wpsf-survey',
					'post_status' => array( 'draft', 'publish' ),
					'numberposts' => -1,
				)
			);

			foreach ( $posts as $post ) {
				$background_image_meta = get_post_meta( $post->ID, 'surveyfunnel-lite-design-background', true );

				if ( $background_image_meta && ! $this->validate_image_url( $background_image_meta ) ) {
					update_post_meta( $post->ID, 'surveyfunnel-lite-design-background', wp_get_attachment_url( $background_image_meta ) );
				}
			}

			update_option( 'srf-lite-background-update', true );
		}else{
			update_option( 'srf-lite-background-update', false);
		}
	}

	/**
	 * Image url validation.
	 *
	 * @param string $string url string.
	 */
	public function validate_image_url( $string ) {
		return preg_match( '/^http.*\.(jpeg|jpg|png)$/', $string );
	}

	/**
	 * Ajax: new survey.
	 */
	public function surveyfunnel_lite_new_survey() {

		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveySecurity', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
			return;
		}
		// check for validations.
		$type = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : 'basic';
		// create surveyfunnel-lite survey post.
		$post_id = wp_insert_post(
			array(
				'post_type'  => 'wpsf-survey',
				'post_title' => sanitize_text_field( wp_unslash( $_POST['title'] ) ),
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error();
		} else {
			$defaults = $this->surveyfunnel_lite_get_default_save_array();
			update_post_meta( $post_id, 'surveyfunnel-lite-data', $defaults );
			update_post_meta( $post_id, 'surveyfunnel-lite-type', $type );
			// send success if validated.
			wp_send_json_success(
				array(
					'url_to_redirect' => self::surveyfunnel_lite_get_setup_page_url() . $post_id . '#build',
				)
			);
		}
		wp_die();
	}

	/**
	 * Ajax: delete survey.
	 */
	public function surveyfunnel_lite_delete_survey() {

		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveySecurity', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
			return;
		}
		// get the post id.
		$post_id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;

		// delete post and send success json.
		$delete  = wp_delete_post( $post_id );
		if ( ! $delete ) {
			wp_send_json_error();
			wp_die();
			return;
		}

		wp_send_json_success();
		wp_die();
	}

	/**
	 * Setup page for surveyfunnel-lite
	 */
	public function surveyfunnel_lite_survey_setup_page() {
		// if page is not surveyfunnel-lite ? return from this function because we are mainly interested in surveyfunnel-lite page.
		if ( empty( $_GET['page'] ) || 'surveyfunnel-lite' !== $_GET['page'] ) { // phpcs:ignore CSRF ok, input var ok.
			return;
		}

		// Don't load the interface if doing an ajax call.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		set_current_screen();
		// Remove an action in the Gutenberg plugin ( not core Gutenberg ) which throws an error.
		remove_action( 'admin_print_styles', 'gutenberg_block_editor_admin_print_styles' );
		$this->surveyfunnel_lite_survey_page_html();
	}

	/**
	 * Html,CSS and JS of surveyfunnel-lite page.
	 */
	public function surveyfunnel_lite_survey_page_html() {

		// if the page slug is surveyfunnel-lite this function is called to setup the html structure of that page.

		// please note in order to use any script related to this page. register the script first and PRINT it, as enqueue doesnt work since no hooks are present on this page.
		wp_register_script(
			$this->plugin_name . '-main',
			SURVEYFUNNEL_LITE_PLUGIN_URL . 'dist/index.bundle.js',
			array( 'wp-i18n', 'wp-hooks' ),
			time(),
			true
		);

		wp_register_script(
			$this->plugin_name . '-mediaupload',
			plugin_dir_url( __FILE__ ) . 'js/surveyfunnel-lite-mediaupload.js',
			array(),
			$this->version,
			false
		);

		?>
			<!DOCTYPE html>
			<html <?php language_attributes(); ?>>
			<head>
				<meta name="viewport" content="width=device-width"/>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<style>.screen-reader-text {
					display: none;
				}</style>
				<?php wp_print_styles( 'dashicons' ); ?>
				<?php wp_print_styles( 'thickbox' ); ?>
				<?php wp_print_scripts( 'thickbox' ); ?>
				<?php wp_print_scripts( 'media-upload' ); ?>
				<title>WP Survey Funnel</title>
			</head>
			<body class="surveyfunnel-lite-body">
				<!-- div id root is where react app will be mounted on -->
				<div id="root" class="surveyfunnel-lite-root"></div>
				<input type="hidden" id="ajaxURL" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
				<input type="hidden" id="ajaxSecurity" value="<?php echo esc_attr( wp_create_nonce( 'surveyfunnel-lite-security' ) ); ?>">
				<input type="hidden" id="dashboardLink" value="<?php echo esc_url( admin_url() . 'admin.php?page=surveyfunnel-lite-dashboard' ); ?>">
				<input type="hidden" id="exportCSVAction" value="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>?action=export_csv">
				<?php do_action( 'surveyfunnel_lite_survey_page_html' ); ?>
				<?php wp_print_scripts( $this->plugin_name . '-main' ); ?>
			</body>
			</html>
		<?php
		wp_enqueue_media();
		exit;
	}

	/**
	 * Returns the setup page url.
	 */
	public static function surveyfunnel_lite_get_setup_page_url() {
		return get_admin_url() . 'index.php?page=surveyfunnel-lite&post_id=';
	}

	/**
	 * Ajax: get status.
	 */
	public function surveyfunnel_lite_get_status() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// get post and its status.
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$post    = get_post( $post_id );

		$status = $post->post_status;
		wp_send_json_error( $status );
		wp_die();
	}

	/**
	 * Ajax: Change Status.
	 */
	public function surveyfunnel_lite_change_status() {
		// check for security
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// get post and change its status.
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$post    = get_post( $post_id );

		$status      = $post->post_status === 'draft' ? 'publish' : 'draft';
		$post_update = array(
			'ID'          => $post_id,
			'post_status' => $status,
		);

		$is_error = wp_update_post( $post_update );

		if ( is_wp_error( $is_error ) ) {
			wp_send_json_error();
			wp_die();
		}
		wp_send_json_success( $status );
		wp_die();
	}

	/**
	 * Ajax: save build question and answers.
	 */
	public function surveyfunnel_lite_save_build_data() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		$post_id       = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$post_title    = isset( $_POST['post_title'] ) ? sanitize_text_field( wp_unslash( $_POST['post_title'] ) ) : '';
		$defaults      = $this->surveyfunnel_lite_get_default_save_array();
		$has_pro       = isset( $_POST['hasProQuestions'] ) ? sanitize_text_field( wp_unslash( $_POST['hasProQuestions'] ) ) : 0;
		$post_meta     = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
		$data          = wp_parse_args( (array) $post_meta, $defaults );

		// need to replace \\ with \\\\ since json_encode removes all the slashes recursively.
		$data['build'] = str_replace( '\\', '\\\\', json_encode( $data['build'], true ) );
		$data['build'] = isset( $_POST['state'] ) ? sanitize_text_field( $_POST['state'] ) : '';

		// update the post meta and post update.
		update_post_meta( $post_id, 'surveyfunnel-lite-data', $data );
		update_post_meta( $post_id, 'surveyfunnel-lite_hasProQuestions', $has_pro );
		$post_update = array(
			'ID'         => $post_id,
			'post_title' => $post_title,
		);
		wp_update_post( $post_update );
		wp_send_json_success();
		wp_die();
	}

	/**
	 * Ajax: save build question and answers.
	 */
	public function surveyfunnel_lite_save_configuration_data() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		$post_id           = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$defaults          = $this->surveyfunnel_lite_get_default_save_array();
		$post_meta         = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
		$data              = wp_parse_args( (array) $post_meta, $defaults );
		$data['configure'] = isset( $_POST['configuration'] ) ? sanitize_text_field( wp_unslash( $_POST['configuration'] ) ) : '';

		update_post_meta( $post_id, 'surveyfunnel-lite-data', $data );
		wp_send_json_success();
		wp_die();
	}

	/**
	 * Get configuration data for the post id
	 */
	public function surveyfunnel_lite_get_configuration_data() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// get required post meta and echo it back.
		$post_id   = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$post_meta = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
		$data      = array(
			'configure' => $post_meta['configure'],
            'proActive' => apply_filters( 'surveyfunnel_pro_activated', false ),
		);
		wp_send_json_success( $data );
		wp_die();
	}

	/**
	 * Get default array save data in post_content.
	 */
	public static function surveyfunnel_lite_get_default_save_array() {
		// default JSON data.
		return array(
			'build'     => '{"List":{"START_ELEMENTS":[],"CONTENT_ELEMENTS":[],"RESULT_ELEMENTS":[]},"title":"survey test"}',
			'design'    => '{"opacity":0,"fontFamily":null,"fontFamilyValue":"","backgroundColor":{"r":"255","g":"255","b":"255","a":"1"},"buttonColor":{"r":"1","g":"111","b":"222","a":"1"},"buttonTextColor":{"r":"255","g":"255","b":"255","a":"1"},"answersHighlightBoxColor":{"r":"232","g":"238","b":"244","a":"1"},"answerBorderColor":{"r":"180","g":"220","b":"255","a":"1"},"backgroundContainerColor":{"r":"255","g":"255","b":"255","a":"1"},"fontColor":{"r":"0","g":"0","b":"0","a":"1"}}',
			'configure' => '{"metaInfo":{"title":"","description":""},"companyBranding":true}',
			'share'     => '{"popup":{"active":false,"targettingOptions":{"devices":[{"name":"Desktop","checked":true,"id":"desktop"},{"name":"Mobile","checked":true,"id":"mobile"},{"name":"Tablet","checked":true,"id":"tablet"}],"triggerPage":"triggerOnSpecific","selectedPagesAndPosts":[]},"behaviourOptions":{"launchOptions":{"launchWhen":"afterPageLoads","afterTimeDelay":5,"afterExitIntent":"low","afterScrollPercentage":20},"frequencyOptions":{"frequency":"alwaysShow","hideFor":3,"dontShowAgain":false}}}}',
			'reports'   => '',
		);
	}

	/**
	 * Get Build data for the post id
	 */
	public function surveyfunnel_lite_get_build_data() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// get the build data.
		$post_id    = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$post_meta  = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
		$post_title = get_the_title( $post_id );
		$post_type  = get_post_meta( $post_id, 'surveyfunnel-lite-type', true );
		$data       = array(
			'build'       => $post_meta['build'],
			'post_title'  => $post_title,
			'survey_type' => $post_type,
			'proActive'   => apply_filters( 'surveyfunnel_pro_activated', false ),
		);
		wp_send_json_success( $data );
		wp_die();
	}

	/**
	 * Ajax: Save design data for the post id
	 */
	public function surveyfunnel_lite_save_design_data() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// extract data from $_POST.
		$post_id        = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$defaults       = $this->surveyfunnel_lite_get_default_save_array();
		$post_meta      = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
		$data           = wp_parse_args( (array) $post_meta, $defaults );
		$data['design'] = isset( $_POST['state'] ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '';
		update_post_meta( $post_id, 'surveyfunnel-lite-data', $data );

		// if background image is set.
		if ( isset( $_POST['selectedImageUrl'] ) ) {
			$url        = esc_url_raw( wp_unslash( $_POST['selectedImageUrl'] ) );
			$image_type = explode( '.', $url ); // Explode the string.
			$image_type = end( $image_type );
			if ( 'jpeg' === $image_type || 'jpg' === $image_type || 'png' === $image_type ) {
				update_post_meta( $post_id, 'surveyfunnel-lite-design-background', $url );
			} else {
				update_post_meta( $post_id, 'surveyfunnel-lite-design-background', false );
			}
		}

		wp_send_json_success();
		wp_die();
	}

	/**
	 * Ajax: get design data.
	 */
	public function surveyfunnel_lite_get_design_data() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// get design data.
		$post_id               = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$post_meta             = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
		$background_image_meta = get_post_meta( $post_id, 'surveyfunnel-lite-design-background', true );
		$data                  = array(
			'design'          => $post_meta['design'],
			'backgroundImage' => $background_image_meta,
		);
		wp_send_json_success( $data );
		wp_die();
	}

	/**
	 * Ajax: Get reports data.
	 */
	public function surveyfunnel_lite_get_reports_data() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// get start date and end date of reports data.
		global $wpdb;
		$table_name = $wpdb->prefix . 'srf_entries';
		$post_id    = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$start_date = isset( $_POST['startDate'] ) ? sanitize_text_field( wp_unslash( $_POST['startDate'] ) ) : '';
		$end_date   = isset( $_POST['endDate'] ) ? sanitize_text_field( wp_unslash( $_POST['endDate'] ) ) : '';

		// get all rows between specified start date and end date.
		$rows       = $wpdb->get_results(
			$wpdb->prepare(
				'
					SELECT * 
					FROM ' . $table_name . '
					WHERE date_created BETWEEN %s and %s AND
					survey_id = %d
				',
				$start_date,
				$end_date,
				$post_id
			)
		);
		// return array which will be echoed.
		$return_arr = array();
		if ( is_array( $rows ) && count( $rows ) ) {

			// loop through each row and create data set specified below.
			foreach ( $rows as $row ) {
				$temp_arr = array(
					'lead'         => 'Unknown',
					'fields'       => $row->fields,
					'userLocaleID' => $row->user_locale_id,
					'time_created' => $row->time_created,
					'userMeta'     => $row->user_meta,
					'checked'      => false,
				);

				// if email is present change the lead from Unknownn to that lead.

				preg_match( '/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)/', $row->fields, $matches );

				if ( count( $matches ) ) {
					$temp_arr['lead'] = $matches[0];
				}
				array_push( $return_arr, $temp_arr );
			}
		}

		wp_send_json_success( $return_arr );
		wp_die();
	}

	/**
	 * Get insights data.
	 *
	 * @param int $post_id post id.
	 */
	public static function surveyfunnel_lite_get_insights_data( $post_id ) {
		// get the reports data for provided post id.
		global $wpdb;
		$table_name = $wpdb->prefix . 'srf_entries';
		$rows       = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT * 
				FROM ' . $table_name . '
				WHERE survey_id = %d
			',
				$post_id
			)
		);
		// initializing required variables.
		$view_count      = 0;
		$completed_count = 0;
		$contacts_count  = 0;
		$total_count     = count( $rows );
		$completion_rate = 0;
		$contacts_count  = 0;
		$completed_count = 0;

		// generate appropriate data using question list and id from reports page.
		if ( count( $rows ) ) {
			$data             = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
			$build            = json_decode( $data['build'] );
			$content_elements = $build->List->CONTENT_ELEMENTS;//phpcs:ignore
			foreach ( $content_elements as $content ) {
				$id      = $content->id;
				$pattern = '/' . $id . '/';
				foreach ( $rows as $row ) {
					if ( preg_match( $pattern, $row->fields ) ) {
						$view_count++;
					}
				}
			}
			foreach ( $rows as $row ) {
				if ( preg_match( '/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)/', $row->fields ) ) {
					$contacts_count++;
				}
				$completed_count += intval( $row->user_meta );
			}
			$completion_rate = $completed_count / $total_count * 100;
			$completion_rate = number_format( (float) $completion_rate, 2, '.', '' );
		}
		// return views, contacts and completion rate.
		return array(
			'views'          => $view_count,
			'contacts'       => $contacts_count,
			'completionRate' => $completion_rate,
		);
	}

	/**
	 * Export CSV string.
	 */
	public function surveyfunnel_lite_export_csv() {
		// exporting csv string by specifying header and its types.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'exportSecurity', 'security' );
		}
		$filename       = $this->plugin_name . '-stats';
		$generated_date = gmdate( 'd-m-Y His' );
		$csv_string     = isset( $_POST['csv_data'] ) ? sanitize_textarea_field( wp_unslash( $_POST['csv_data'] ) ) : '';
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . ' ' . $generated_date . '.csv";' );
		echo wp_kses_data( $csv_string );
		wp_die();

	}

	/**
	 * Mascot on all pages.
	 */
	public function surveyfunnel_lite_mascot_on_pages() {
		if ( empty( $_GET['page'] ) || ('surveyfunnel-lite-dashboard' !== $_GET['page'] && 'surveyfunnel-lite-help' !== $_GET['page']) ) {//phpcs:ignore
			return;
		}

		$is_pro = get_option( 'surveyfunnel_pro_activated' );
		if ( $is_pro ) {
			$support_url = 'https://club.wpeka.com/my-account/orders/?utm_source=surveyfunnel&utm_medium=help-mascot&utm_campaign=link&utm_content=support';
		} else {
			$support_url = 'https://wordpress.org/support/plugin/surveyfunnel-lite/';
		}

		$return_array = array(
			'menu_items'       => array(
				'support_text'       => __( 'Support', 'surveyfunnel' ),
				'support_url'        => $support_url,
				'documentation_text' => __( 'Documentation', 'surveyfunnel' ),
				'documentation_url'  => 'https://docs.wpeka.com/survey-funnel/v/master/',
				'faq_text'           => __( 'FAQ', 'surveyfunnel' ),
				'faq_url'            => 'https://docs.wpeka.com/survey-funnel/v/master/faqs',
				'upgrade_text'       => __( 'Upgrade to Pro &raquo;', 'surveyfunnel' ),
				'upgrade_url'        => 'https://club.wpeka.com/product/survey-funnel/?utm_source=survey-funnel&utm_medium=help-mascot&utm_campaign=link&utm_content=upgrade-to-pro',
			),
			'is_pro'           => $is_pro,
			'quick_links_text' => __( 'See Quick Links', 'surveyfunnel' ),
		);
		wp_enqueue_script( $this->plugin_name . '-mascot' );
		wp_enqueue_style( $this->plugin_name . '-mascot' );
		wp_localize_script( $this->plugin_name . '-mascot', 'mascot', $return_array );

		?>
			<div id="surveyfunnel-lite-mascot-app"></div>
		<?php
	}

	/**
	 * Ajax: save share data.
	 */
	public function surveyfunnel_lite_save_share_data() {
		// saving popup settings data.

		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// save share data.
		$post_id       = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$defaults      = $this->surveyfunnel_lite_get_default_save_array();
		$post_meta     = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
		$data          = wp_parse_args( (array) $post_meta, $defaults );
		$data['share'] = isset( $_POST['share'] ) ? sanitize_text_field( wp_unslash( $_POST['share'] ) ) : '';

		update_post_meta( $post_id, 'surveyfunnel-lite-data', $data );
		wp_send_json_success();
		wp_die();
	}

	/**
	 * Get share data for the post id
	 */
	public function surveyfunnel_lite_get_share_data() {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		// save share data.
		$post_id   = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$post_meta = get_post_meta( $post_id, 'surveyfunnel-lite-data', true );
		$data      = array(
			'share' => $post_meta['share'],
		);
		wp_send_json_success( $data );
		wp_die();
	}

	/**
	 * Ajax: get posts and pages for async select.
	 */
	public function surveyfunnel_lite_get_posts_pages( $flag = false ) {
		// check for security.
		if ( isset( $_POST['action'] ) ) {
			check_admin_referer( 'surveyfunnel-lite-security', 'security' );
		} else {
			wp_send_json_error();
			wp_die();
		}

		$flag = isset( $_POST['links'] ) ? true : false;
		// get posts and if flag for links is set provide links or else provide ids..
		$args = array(
			'post_type'   => 'post',
			'post_status' => 'publish',
			'numberposts' => -1,
		);

		$posts                = get_posts( $args );
		$post_object          = new stdClass();
		$post_object->label   = 'Posts';
		$post_object->options = array();

		foreach ( $posts as $post ) {
			$obj        = new stdClass();
			$obj->label = $post->post_title;
			if ( $flag ) {
				$obj->value = get_permalink( $post->ID );
			} else {
				$obj->value = $post->ID;
			}
			array_push( $post_object->options, $obj );
		}

		// get pages and if flag for links is set provide links or else provide ids..

		$args = array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => -1,
		);

		$pages                = get_posts( $args );
		$page_object          = new stdClass();
		$page_object->label   = 'Pages';
		$page_object->options = array();

		foreach ( $pages as $page ) {
			$obj        = new stdClass();
			$obj->label = $page->post_title;
			if ( $flag ) {
				$obj->value = get_permalink( $page->ID );
			} else {
				$obj->value = $page->ID;
			}
			array_push( $page_object->options, $obj );
		}

		$data = array( $post_object, $page_object );

		wp_send_json_success( $data );
		wp_die();
	}

	/**
	 * Registers gutenberg block for surveys.
	 *
	 * @since 1.0.0
	 */
	public function surveyfunnel_lite_register_gutenberg_blocks() {

		wp_register_script(
			'surveyfunnel-lite-gutenberg-single-survey',
			plugin_dir_url( __DIR__ ) . 'admin/js/gutenberg-blocks/surveyfunnel-lite-gutenberg-singlesurvey.js',
			array( 'wp-blocks', 'wp-api-fetch', 'wp-components', 'wp-i18n' ),
			$this->version,
			false
		);
		if ( function_exists( 'register_block_type' ) ) {
			register_block_type(
				'surveyfunnel/single-survey',
				array(
					'editor_script'   => 'surveyfunnel-lite-gutenberg-single-survey',
					'attributes'      => array(
						'survey_id'            => array(
							'type' => 'number',
						),
						'survey_name'          => array(
							'type' => 'string',
						),
						'survey_embed_type'    => array(
							'type' => 'string',
						),
						'survey_custom_width'  => array(
							'type' => 'string',
						),
						'survey_custom_height' => array(
							'type' => 'string',
						),
					),
					'render_callback' => array( $this, 'surveyfunnel_lite_gutenberg_display_single_survey' ),
				)
			);
		}
	}

    /**
     * Ajax: get API key.
     */
    public function surveyfunnel_lite_get_api_key() {

        // check for security.
        if ( isset( $_POST['action'] ) ) {
            check_admin_referer( 'surveyfunnel-lite-security', 'security' );
        } else {
            wp_send_json_error();
            wp_die();
        }

        $apiData = get_option('wc_am_client_surveyfunnel_pro');
        $data = array( 'apikey' => $apiData['wc_am_client_surveyfunnel_pro_api_key']);
        wp_send_json_success( $data );
        wp_die();
    }

	/**
	 * Display surveys added by gutenberg block.
	 *
	 * @param array $attributes survey attributes.
	 */
	public function surveyfunnel_lite_gutenberg_display_single_survey( $attributes ) {
		$survey_atts = array(
			'id'     => 0,
			'type'   => 'responsive',
			'width'  => '100%',
			'height' => '700px',
		);
		$shortcode   = '[surveyfunnel_lite_survey';

		if ( isset( $attributes['survey_id'] ) ) {
			$survey_atts['id'] = $attributes['survey_id'];
		}
		$shortcode .= ' id="' . $survey_atts['id'] . '"';

		if ( isset( $attributes['survey_embed_type'] ) ) {
			$survey_atts['type'] = $attributes['survey_embed_type'];
		}
		$shortcode .= ' type="' . $survey_atts['type'] . '"';

		if ( isset( $attributes['survey_custom_width'] ) ) {
			$survey_atts['width'] = $attributes['survey_custom_width'];
		}
		$shortcode .= ' width="' . $survey_atts['width'] . '"';

		if ( isset( $attributes['survey_custom_height'] ) ) {
			$survey_atts['height'] = $attributes['survey_custom_height'];
		}
		$shortcode .= ' height="' . $survey_atts['height'] . '"';

		$shortcode .= ']';
		return $shortcode;
	}

	/**
	 * Registers gutenberg block categories.
	 *
	 * @param array $categories contains categories of gutenberg block.
	 *
	 * @since 1.0.0
	 */
	public function surveyfunnel_lite_gutenberg_block_categories( $categories ) {

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'surveyfunnel-lite',
					'title' => __( 'SurveyFunnel', 'surveyfunnel' ),
				),
			)
		);
	}


}
