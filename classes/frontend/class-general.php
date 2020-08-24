<?php
namespace lsx_health_plan\classes\frontend;

/**
 * Holds the functions and actions which are shared accross the post types.
 *
 * @package lsx-health-plan
 */
class General {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx_health_plan\classes\frontend\General()
	 */
	protected static $instance = null;

	/**
	 * Contructor
	 */
	public function __construct() {
		// Before Output
		add_filter( 'wp_kses_allowed_html', array( $this, 'allow_html_tags_attributes' ), 100, 2 );

		// Output
		add_action( 'body_class', array( $this, 'body_classes' ) );
		add_filter( 'lsx_global_header_title',  array( $this, 'single_title' ), 200, 1 );
		add_action( 'wp_head', array( $this, 'remove_single_footer' ), 99 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx_health_plan\classes\frontend\General()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Adds the iframe and the progress HTML tags to the allowed WordPress list.
	 */
	public function allow_html_tags_attributes( $tags, $context ) {
		if ( 'post' === $context ) {
			$tags['iframe'] = array(
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
			);
		}
		$tags['progress'] = array(
			'id'    => true,
			'value' => true,
			'max'   => true,
		);
		return $tags;
	}

	/**
	 * Add body classes to body.
	 *
	 * @param array $classes
	 * @return void
	 */
	public function body_classes( $classes = array() ) {
		global $post;

		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'lsx_health_plan_my_profile_block' ) ) {
			$classes[] = 'my-plan-shortcode';
		}

		if ( is_single() && is_singular( 'plan' ) ) {
			$args = array(
				'post_parent' => get_the_ID(),
				'post_type'   => 'plan',
			);

			$post_id      = get_the_ID();
			$has_children = get_children( $args );
			$has_parent   = wp_get_post_parent_id( $post_id );

			if ( ! empty( $has_children ) ) {
				$plan_type_class = 'parent-plan-page';
				if ( 0 !== $has_parent ) {
					$plan_type_class = 'parent-sub-plan-page';
				}
			} else {
				$plan_type_class = 'unique-plan-page';
				if ( 0 !== $has_parent ) {
					$plan_type_class = 'child-plan-page';
				}
			}
			$classes[] = $plan_type_class;
		}
		return $classes;
	}

	/**
	 * Remove the single recipe and exercise title
	 */
	public function single_title( $title ) {

		if ( is_single() && is_singular( 'recipe' ) ) {

			$title = __( 'Recipe', 'lsx-health-plan' );
		}

		if ( is_single() && is_singular( 'exercise' ) ) {

			$title = __( 'Exercise', 'lsx-health-plan' );
		}

		return $title;
	}

	/**
	 * Removing footer for HP single pages.
	 *
	 * @return void
	 */
	public function remove_single_footer() {
		if ( is_single() && is_singular( array( 'exercise', 'recipe', 'workout', 'meal' ) ) ) {
			remove_action( 'lsx_footer_before', 'lsx_add_footer_sidebar_area' );
		}
	}
}
