<?php
/**
 * Contains the settings class for LSX
 *
 * @package lsx-health-plan
 */

namespace lsx_health_plan\classes\admin;

/**
 * Contains the settings for each post type \lsx_health_plan\classes\admin\Recipe().
 */
class Recipe {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx_health_plan\classes\admin\Recipe()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'lsx_hp_settings_page_recipe_top', array( $this, 'settings' ), 1, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx_health_plan\classes\admin\Recipe()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Registers the general settings.
	 *
	 * @param object $cmb new_cmb2_box().
	 * @return void
	 */
	public function settings( $cmb ) {
		$cmb->add_field(
			array(
				'id'          => 'recipe_archive_description',
				'type'        => 'wysiwyg',
				'name'        => __( 'Archive Description', 'lsx-health-plan' ),
				'description' => __( 'This will show up on the post type archive.', 'lsx-health-plan' ),
			)
		);
	}
}
Recipe::get_instance();
