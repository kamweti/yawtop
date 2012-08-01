<?php  

/* Builds all features of the theme options panel
* 
*  There are lots of stuff here, we register theme options page
*  and proceed to add settings fields
*/
class UclaneOptions {

	var $options = array();
	var $defaults = array();


	function __construct() {

		//load options
		$this->load_options();

		/* Filters
		*
		*
		* @template add_filter( '' , array(&$this, '') );
		*/
		//add_filter( '' , array(&$this, '') );


		/* Actions
		*
		*
		*
		* @template add_action( '' , array(&$this, '') );
		*/
		add_action( 'admin_init' , array(&$this, 'firstrun') );
		add_action( 'admin_menu', array( &$this, 'add_admin_options' ) );
		add_action( 'admin_init', array( &$this, 'register_admin_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_admin_options_sections' ) );

	}


	/*
	 * Update Options
	 *
	 * Gets current options and returns them if found
	 * options variable is set to default option fields
	 * if there are no existing fields,record that theme is
	 * activated and user hasn't arrived at options page
	 */
	function update_options() {	
		return update_option('uclane_theme_options' , $this->options );
	}

	/* First run
	 *
	 * 	This method is fired on every call,it checks the 
	 *	$options array to see whether the theme was activated,
	 * 	to make sure this runs only once.
	 */
	function firstrun() {
 		if(!isset($this->options['theme-activated']) || ! $this->options['theme-activated'] ){
 			$this->options['theme-activated'] = true;
 			$this->options['theme-options-visited'] = false;
 			$this->update_options();
 		}
	}


	/*
	 * Register Settings
	 *
	 * Fired during admin_init, this function registers the settings used
	 * in the Theme options section, as well as attaches a validator to
	 * clean up the icoming data.
	 *
	 */
	function register_admin_settings() {
		register_setting( 'uclane-options', 'uclane-options', array( &$this, 'validate_options' ) );
	}


	/* 
	 * Register admin options sections
	 *
	 * Will output sections based on the current tab
	 * the user is on
	 */
	function register_admin_options_sections(){
		global $pagenow;
		if( $pagenow == 'themes.php' && isset( $_GET['page']) && $_GET['page'] == 'uclane-panel' ) {
			$tab = isset( $_GET['tab']) ? $_GET['tab'] : 'general';
			switch( $tab ) {
				case 'general':
					$this->register_general_tab_sections();
				break;

				case 'layout':
					$this->register_layout_tab_sections();
				break;

				case 'design':
					$this->register_design_tab_sections();
				break;

				case 'typography':
					$this->register_typography_tab_sections();
				break;

				default:
					$this->register_general_tab_sections();

			}
		}
	}


	/* 
	 * General tab sectionss
	 *
	 * Registers sections for the general tab
	 */
	function register_general_tab_sections(){

		//define homepage section plus fields for it
		add_settings_section( 'section_homepage', __( 'Homepage', 'uclane' ), create_function( '', 'echo "<hr/>";' ), 'uclane-panel' );
		add_settings_field( 'uclane_home_layout', __( 'Homepage layout', 'uclane' ), array( &$this, 'setting_homepage_layout' ), 'uclane-panel', 'section_homepage', 'uclane_homepage_layout' );


		add_settings_section( 'section_footer', __( 'Footer', 'uclane' ), create_function( '', 'echo "<hr/>";' ), 'uclane-panel' );
		add_settings_field( 'uclane_footer_note', __( 'Footer Note', 'uclane' ), array( &$this, 'setting_footer_note' ), 'uclane-panel', 'section_footer' );


	}	

	/* 
	 * Layout tab sections
	 *
	 * Registers sections for the layout tab
	 */
	function register_layout_tab_sections(){

	}

	/* 
	 * Design tab sections
	 *
	 * Registers sections for the design tab
	 */
	function register_design_tab_sections(){

	}

	/* 
	 * Typography tab sections
	 *
	 * Registers sections for the typography tab
	 */
	function register_typography_tab_sections(){

	}


	/*
	 * Options Validation
	 *
	 * This function is used to validate the incoming options, mostly from
	 * the Theme Options admin page. We make sure that the 'activated' array
	 * is untouched and then verify the rest of the options.
	 *
	 */
	function validate_options($options) {
		// Mandatory.
		$options['activated'] = true;
		
		return $options;
	}


	/* Load Options
	 *
	 * Gets current options and returns them if found
	 * options variable is set to default option fields
	 * if there are no existing fields,record that theme is
	 * activated and user hasn't arrived at options page
	 */
	function load_options() {
		$this->options = (array) get_option('uclane_theme_options');
		$this->options =  array_merge( $this->default_options(), $this->options);
	}

	/* Default Options
	 * 
	 * creates an array of theme default options
	 * and returns ithttp://localhost/wordpress/wp-admin/themes.php?page=uclane-panel&tab=general
	 */
	function default_options(){

		$this->defaults=array(
			/* general tab */
			'uclane_homepage_layout' => 'grid',
			'home_posts_to_show'     => 4,
			'footer_show_copyright'  => true,
			'footer_show_credit'     => true,
			
			/* layout tab */
			'homepage_layout'        => 'content-sidebar',
			'blog_layout'            => 'content-sidebar',
			
			
			/* design tab */
			'color_scheme'           => '',
			'custom_css'             => __( '/* Type your custom styles here */', 'uclane' ),			
			
			/* typography tab */
			'default_font_fam'       => 'Open Sans',
			'headings_font_fam'      => 'PT Sans',
			'body_copy_font_fam'     => 'Open Sans',
			'body_copy_font_size'    => '16px',
			
			'theme_version'          => '0.0.1'
		);

		return $this->defaults;
	}

	/* Option tabs
	 * 
	 * populates an array of option tabs
	 * and returns it
	 */
	function get_option_tabs() {
		$tabs = array(
			array(
				'name'  => 'general',
				'label' => __( 'General', 'uclane')
			),
			array(
				'name'  => 'layout',
				'label' => __( 'Layout', 'uclane')
			),
			array(
				'name'  => 'design',
				'label' => __( 'Design', 'uclane')
			),
			array(
				'name'  => 'typography',
				'label' => __( 'Typography', 'uclane')
			)
		);

		return $tabs;
	}

	/*
	 * Add Menu Options
	 *
	 * Registers a Theme Options page that appears under the Appearance
	 * menu in the WordPress dashboard. Uses the theme_options to render
	 * the page contents, requires edit_theme_options capabilities.
	 *
	 */
	function add_admin_options() {
		add_theme_page( __( 'Theme Options', 'uclane' ), __('Theme Options', 'uclane' ), 'edit_theme_options', 'uclane-panel', array( &$this, 'theme_options' ) );
	}


	/*
	 * Theme Options
	 *
	 * This is the function that renders the Theme Options page under
	 * the Appearence menu in the admin section. Upon visiting this the
	 * first time we make sure that a state (options-visited) is saved
	 * to our options array.
	 *
	 * The rest is handled by Settings API and some HTML magic.
	 *
	 */
	function theme_options() {
	
		if ( ! isset( $this->options['options-visited'] ) || ! $this->options['options-visited'] ) {
			$this->options['options-visited'] = true;
			$this->update_options();
		}
	?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"><br></div>
			<h2><?php _e( 'San Fran Options', 'sanfran' ); ?></h2>
			<h2 class="nav-tab-wrapper">
				<?php $this->options_page_tabs(); ?>
			</h2>
			<?php if( isset( $_GET['settings_updated']) ) ?>
			<form method="post" act ion="options.php">
				<?php settings_fields( 'uclane-options' ); ?>
				<?php do_settings_sections( 'uclane-panel' ); ?>
				<p class="submit">
					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'sanfran'); ?>" />
				</p >
			</form>
		</div>
	<?php
	}


	/*
	 * Options Page Tabs
	 *
	 * Gets an array of tabs looping and creating 
	 * and array links for display in the options page
	 *
	 * Get the active tab or default to the first tab as active
	 * 
	 */
	function options_page_tabs(){
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

		$tabs = $this->get_option_tabs();

		$links = array();
		foreach($tabs as $tab) {
			$current = ($tab['name'] == $current) ?  ' nav-tab-active' : '';
			$links[] = '<a class="nav-tab '.$current.'" href="?page=uclane-panel&tab='.$tab['name'].'">'.$tab['label'].'</a>';
		}

	    foreach ( $links as $link ) echo $link;
	}


	/* ***********************************************************************
								SETTINGS FIELDS
	**************************************************************************/

	/*
	 * Setting: Footer Note
	 *
	 * Outputs a textarea for the footer note under Theme Options in
	 * Appearance. Populates the textarea with the currently set
	 * footer note from the $options array.
	 *
	 */
	function setting_footer_note() {
	?>
		<textarea rows="5" class="large-text code" name="sanfran-options[footer-note]"><?php echo esc_textarea( $this->options['footer-note'] ); ?></textarea><br />
		<span class="description"><?php _e( 'This is the text that appears at the bottom of every page, right next to the copyright notice.', 'sanfran' ); ?></span>
	<?php
	}


	/*
	 * Setting: Homepage layout
	 *
	 * Appearance settings for the homepage
	 * can be either blog / grid layout.
	 *
	 */
	function setting_homepage_layout($fieldid) {  ?>
		<label class="description">
			<input name="uclane-options[<?php echo $fieldid; ?>]" type="radio" <?php checked( 'grid', $this->options[$fieldid] ); ?> value="grid" />
			<span><?php _e( 'Grid Layout', 'esplanade' ); ?></span>
		</label><br />
		<label class="description">
			<input name="uclane-options[<?php echo $fieldid; ?>]" type="radio" <?php checked( 'blog', $this->options[$fieldid] ); ?> value="blog" />
			<span><?php _e( 'Blog Layout', 'esplanade' ); ?></span>
		</label>
	<?php
	}


}

new UclaneOptions;
