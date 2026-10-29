<?php
/**
 * Stacky, the VitalStack mascot: a small friendly robot drawn in inline SVG
 * and animated with CSS (blinking eyes, bobbing antenna, waving arm).
 *
 * The same markup is built in assets/js/fun.js for popups, games and the
 * live visualizer, so keep the two in sync.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mascot SVG.
 *
 * @param string $mood  happy | wow | think | cheer | sleepy.
 * @param int    $size  Width in pixels.
 * @param string $class Extra classes.
 */
function vitalstack_mascot( $mood = 'happy', $size = 120, $class = '' ) {
	$mood  = in_array( $mood, array( 'happy', 'wow', 'think', 'cheer', 'sleepy' ), true ) ? $mood : 'happy';
	$mouth = array(
		'happy'  => '<path class="m-mouth" d="M48 60 Q60 70 72 60" />',
		'cheer'  => '<path class="m-mouth m-open" d="M47 58 Q60 74 73 58 Z" />',
		'wow'    => '<ellipse class="m-mouth m-open" cx="60" cy="62" rx="5" ry="6" />',
		'think'  => '<path class="m-mouth" d="M52 63 L68 60" />',
		'sleepy' => '<path class="m-mouth" d="M52 62 Q60 66 68 62" />',
	);
	$eyes  = 'sleepy' === $mood
		? '<path class="m-eye-closed" d="M40 46 Q46 50 52 46 M68 46 Q74 50 80 46" />'
		: '<g class="m-eyes"><ellipse class="m-eye" cx="46" cy="46" rx="5.5" ry="7" /><ellipse class="m-eye" cx="74" cy="46" rx="5.5" ry="7" /><circle class="m-shine" cx="48" cy="43" r="1.8" /><circle class="m-shine" cx="76" cy="43" r="1.8" /></g>';
	$arms  = 'cheer' === $mood
		? '<path class="m-arm m-arm-l" d="M24 96 Q12 84 14 70" /><path class="m-arm m-arm-r m-wave" d="M96 96 Q108 84 106 70" /><circle class="m-hand" cx="14" cy="68" r="5" /><circle class="m-hand m-wave" cx="106" cy="68" r="5" />'
		: ( 'think' === $mood
			? '<path class="m-arm m-arm-l" d="M24 98 Q14 106 18 116" /><path class="m-arm m-arm-r" d="M96 98 Q90 84 78 74" /><circle class="m-hand" cx="18" cy="118" r="5" /><circle class="m-hand" cx="76" cy="72" r="5" />'
			: '<path class="m-arm m-arm-l" d="M24 98 Q14 106 18 116" /><g class="m-wave"><path class="m-arm m-arm-r" d="M96 98 Q110 92 110 78" /><circle class="m-hand" cx="110" cy="75" r="5" /></g><circle class="m-hand" cx="18" cy="118" r="5" />' );
	$extra = 'think' === $mood ? '<g class="m-think"><circle cx="100" cy="22" r="3" /><circle cx="108" cy="12" r="4.5" /></g>' : '';
	$extra .= 'cheer' === $mood ? '<g class="m-sparks"><path d="M10 30 l4 0 M12 28 l0 4" /><path d="M104 36 l4 0 M106 34 l0 4" /><path d="M96 14 l4 0 M98 12 l0 4" /></g>' : '';

	$svg = '<svg class="mascot mascot-' . esc_attr( $mood ) . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" width="' . (int) $size . '" height="' . (int) round( $size * 1.12 ) . '" viewBox="0 0 120 134" role="img" aria-label="' . esc_attr__( 'Stacky the robot', 'vitalstack' ) . '">'
		. '<g class="m-body-wrap">'
		. '<g class="m-antenna"><line x1="60" y1="16" x2="60" y2="6" /><circle class="m-bulb" cx="60" cy="5" r="4.5" /></g>'
		. '<rect class="m-head" x="18" y="16" width="84" height="66" rx="22" />'
		. '<rect class="m-screen" x="27" y="26" width="66" height="46" rx="15" />'
		. $eyes . $mouth[ $mood ]
		. '<circle class="m-cheek" cx="36" cy="60" r="4" /><circle class="m-cheek" cx="84" cy="60" r="4" />'
		. $arms
		. '<rect class="m-layer m-layer-1" x="28" y="86" width="64" height="12" rx="6" />'
		. '<rect class="m-layer m-layer-2" x="32" y="100" width="56" height="12" rx="6" />'
		. '<rect class="m-layer m-layer-3" x="36" y="114" width="48" height="12" rx="6" />'
		. $extra
		. '</g></svg>';
	return $svg;
}
