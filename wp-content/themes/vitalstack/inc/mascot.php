<?php
/**
 * The VitalStack mascot, drawn in inline SVG and animated with CSS
 * (blinking eyes, bobbing antenna, waving arm). Two looks, picked in
 * Customizer → VitalStack: Fun: "Bit", a pixel-art robot (default), or
 * "Stacky", the original rounded robot.
 *
 * assets/js/fun.js gets its popup mascots from this file (cfg.mascot).
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
	$mood = in_array( $mood, array( 'happy', 'wow', 'think', 'cheer', 'sleepy' ), true ) ? $mood : 'happy';
	if ( 'classic' !== get_theme_mod( 'vitalstack_mascot_style', 'pixel' ) ) {
		return vitalstack_mascot_pixel( $mood, $size, $class );
	}
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

/**
 * Bit: the pixel-art look. Same moods, size and outer classes as Stacky so
 * every place that shows the mascot works unchanged. Drawn on an 8px grid
 * (4px for small details) with crisp edges.
 */
function vitalstack_mascot_pixel( $mood, $size, $class ) {
	$r = function ( $x, $y, $w, $h, $cls = '' ) {
		return '<rect' . ( $cls ? ' class="' . $cls . '"' : '' ) . ' x="' . $x . '" y="' . $y . '" width="' . $w . '" height="' . $h . '" />';
	};

	$eyes = 'sleepy' === $mood
		? $r( 42, 56, 12, 4, 'px-eye-closed' ) . $r( 66, 56, 12, 4, 'px-eye-closed' )
		: '<g class="m-eyes">' . $r( 44, 46, 8, 12, 'm-eye px-eye' ) . $r( 68, 46, 8, 12, 'm-eye px-eye' ) . $r( 44, 46, 4, 4, 'px-shine' ) . $r( 68, 46, 4, 4, 'px-shine' ) . '</g>';

	$mouths = array(
		'happy'  => $r( 48, 62, 4, 4, 'px-mouth' ) . $r( 52, 66, 16, 4, 'px-mouth' ) . $r( 68, 62, 4, 4, 'px-mouth' ),
		'cheer'  => $r( 48, 62, 24, 4, 'px-mouth' ) . $r( 52, 66, 16, 4, 'px-mouth' ) . $r( 56, 70, 8, 2, 'px-tongue' ),
		'wow'    => $r( 56, 62, 8, 8, 'px-mouth' ),
		'think'  => $r( 52, 66, 8, 4, 'px-mouth' ) . $r( 60, 64, 8, 4, 'px-mouth' ),
		'sleepy' => $r( 54, 66, 12, 4, 'px-mouth' ),
	);

	$arms = array(
		'happy'  => $r( 16, 92, 12, 8, 'px-arm' ) . $r( 12, 100, 8, 12, 'px-arm' )
			. '<g class="m-wave">' . $r( 92, 92, 12, 8, 'px-arm' ) . $r( 100, 72, 8, 20, 'px-arm' ) . $r( 98, 64, 12, 10, 'px-hand' ) . '</g>',
		'cheer'  => '<g class="m-wave">' . $r( 16, 92, 12, 8, 'px-arm' ) . $r( 10, 72, 8, 24, 'px-arm' ) . $r( 8, 64, 12, 10, 'px-hand' ) . '</g>'
			. '<g class="m-wave">' . $r( 92, 92, 12, 8, 'px-arm' ) . $r( 102, 72, 8, 24, 'px-arm' ) . $r( 100, 64, 12, 10, 'px-hand' ) . '</g>',
		'wow'    => $r( 4, 92, 24, 8, 'px-arm' ) . $r( 0, 86, 8, 10, 'px-hand' ) . $r( 92, 92, 24, 8, 'px-arm' ) . $r( 112, 86, 8, 10, 'px-hand' ),
		'think'  => $r( 16, 92, 12, 8, 'px-arm' ) . $r( 12, 100, 8, 12, 'px-arm' ) . $r( 92, 92, 12, 8, 'px-arm' ) . $r( 96, 80, 8, 12, 'px-arm' ) . $r( 84, 74, 14, 10, 'px-hand' ),
		'sleepy' => $r( 16, 92, 12, 8, 'px-arm' ) . $r( 12, 100, 8, 12, 'px-arm' ) . $r( 92, 92, 12, 8, 'px-arm' ) . $r( 100, 100, 8, 12, 'px-arm' ),
	);

	$extra = '';
	if ( 'think' === $mood ) {
		$extra = '<g class="m-think px-think">' . $r( 100, 20, 6, 6 ) . $r( 106, 6, 10, 10 ) . '</g>';
	} elseif ( 'cheer' === $mood ) {
		$extra = '<g class="m-sparks px-sparks">' . $r( 4, 30, 8, 4 ) . $r( 6, 28, 4, 8 ) . $r( 106, 34, 8, 4 ) . $r( 108, 32, 4, 8 ) . $r( 96, 10, 8, 4 ) . $r( 98, 8, 4, 8 ) . '</g>';
	} elseif ( 'sleepy' === $mood ) {
		$extra = '<g class="m-think px-zzz">' . $r( 98, 12, 12, 3 ) . $r( 104, 15, 3, 3 ) . $r( 101, 18, 3, 3 ) . $r( 98, 21, 12, 3 ) . '</g>';
	}

	return '<svg class="mascot mascot-pixel mascot-' . esc_attr( $mood ) . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" width="' . (int) $size . '" height="' . (int) round( $size * 1.12 ) . '" viewBox="0 0 120 134" shape-rendering="crispEdges" role="img" aria-label="' . esc_attr__( 'Bit the pixel robot', 'vitalstack' ) . '">'
		. $r( 30, 126, 60, 6, 'px-shadow' )
		. '<g class="m-body-wrap">'
		. '<g class="m-antenna">' . $r( 56, 10, 8, 14, 'px-stem' ) . $r( 52, 2, 16, 10, 'm-bulb px-bulb' ) . '</g>'
		// Head with stepped "pixel" corners and a darker rim.
		. $r( 24, 22, 72, 68, 'px-rim' ) . $r( 16, 30, 88, 52, 'px-rim' )
		. $r( 24, 26, 72, 60, 'px-body' ) . $r( 20, 34, 80, 44, 'px-body' )
		. $r( 28, 30, 16, 4, 'px-light' ) . $r( 24, 34, 4, 12, 'px-light' )
		// Face screen.
		. $r( 32, 40, 56, 36, 'px-screen' ) . $r( 28, 44, 64, 28, 'px-screen' )
		. $eyes . $mouths[ $mood ]
		. $r( 34, 62, 6, 4, 'px-cheek' ) . $r( 80, 62, 6, 4, 'px-cheek' )
		// Body with a </> badge, and legs.
		. $r( 28, 90, 64, 26, 'px-rim' ) . $r( 32, 90, 56, 22, 'px-torso' )
		. $r( 46, 96, 4, 4, 'px-code' ) . $r( 42, 100, 4, 4, 'px-code' ) . $r( 46, 104, 4, 4, 'px-code' )
		. $r( 58, 96, 4, 4, 'px-code' ) . $r( 56, 100, 4, 4, 'px-code' ) . $r( 54, 104, 4, 4, 'px-code' )
		. $r( 70, 96, 4, 4, 'px-code' ) . $r( 74, 100, 4, 4, 'px-code' ) . $r( 70, 104, 4, 4, 'px-code' )
		. $r( 36, 116, 14, 10, 'px-leg' ) . $r( 70, 116, 14, 10, 'px-leg' )
		. $arms[ $mood ] . $extra
		. '</g></svg>';
}
