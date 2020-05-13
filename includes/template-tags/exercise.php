<?php
/**
 * LSX Health Plan Template Tags.
 *
 * @package lsx-health-plan
 */

/**
 * A function to call the play button
 *
 * @param string  $m A unique ID for the modal.
 * @param array   $group The current workout set being looped through.
 * @param boolean $echo
 * @param array   $args
 * @return void
 */
function lsx_health_plan_workout_exercise_button( $m, $group, $echo = true, $args = array() ) {
	$defaults    = array(
		'modal_trigger' => 'button',
	);
	$args        = wp_parse_args( $args, $defaults );
	$exercise_id = '';
	if ( isset( $group['connected_exercises'] ) && '' !== $group['connected_exercises'] ) {
		$exercise_id     = esc_html( $group['connected_exercises'] );
		$content         = get_post_field( 'post_content', $exercise_id );
		$content         = wp_trim_words( $content, 40 );
		$url             = get_permalink( $exercise_id );
		$equipment_group = get_the_term_list( $exercise_id, 'equipment', '', ', ' );
		$muscle_group    = get_the_term_list( $exercise_id, 'muscle-group', '', ', ' );

		if ( 'link' ) {
			$play_button = '<a data-toggle="modal" href="#workout-exercise-modal-' . $m . '">' . get_the_title( $exercise_id ) . '</a>';
		} else {
			$play_button = '<button data-toggle="modal" data-target="#workout-exercise-modal-' . $m . '"><span class="fa fa-play-circle"></span></button>';
		}

		$modal_body  = '';
		$modal_body .= '<div class="modal-image"/>' . get_the_post_thumbnail( $exercise_id, 'lsx-thumbnail-single' ) . '</div>';
		$modal_body .= '<div class="title-lined exercise-modal"><h5 class="modal-title">' . get_the_title( $exercise_id ) . '</h5>';
		$modal_body .= '<span class="equipment-terms">Equipment: ' . $equipment_group . '</span>';
		$modal_body .= '<span class="muscle-terms">Muscle Group: ' . $muscle_group . '</span></div>';
		$modal_body .= '<div class="modal-excerpt"/>' . $content . '</div>';
		$modal_body .= '<a class="moretag" target="_blank" href="' . $url . '">' . __( 'Read More', 'lsx-heal-plan' ) . '</a>';
		\lsx_health_plan\functions\register_modal( 'workout-exercise-modal-' . $m, '', $modal_body );

		if ( true === $echo ) {
			echo wp_kses_post( $play_button );
		} else {
			return $play_button;
		}
	}
}

/**
 * Outputs the modal button and registers the exercise modal to show.
 *
 * @param int $m
 * @param array $group
 * @return void
 */
function lsx_health_plan_shortcode_exercise_button( $m, $content = true ) {
	$equipment_group = get_the_term_list( $m, 'equipment', '', ', ' );
	$muscle_group    = get_the_term_list( $m, 'muscle-group', '', ', ' );
	$title           = get_the_title();
	$button     = '<a data-toggle="modal" href="#exercise-modal-' . $m . '" data-target="#exercise-modal-' . $m . '"></a>';

	if ( true === $content ) {
		$content = get_the_content();
	}

	$modal_body = '';
	$modal_body .= '<div class="modal-image"/>' . get_the_post_thumbnail( $m, 'lsx-thumbnail-single' ) . '</div>';
	$modal_body .= '<div class="title-lined exercise-modal"><h5 class="modal-title">' . $title . '</h5>';
	$modal_body .= '<span class="equipment-terms">Equipment: ' . $equipment_group . '</span>';
	$modal_body .= '<span class="muscle-terms">Muscle Group: ' . $muscle_group . '</span></div>';
	$modal_body .= $content;
	\lsx_health_plan\functions\register_modal( 'exercise-modal-' . $m, '', $modal_body );

	return wp_kses_post( $button );
}
