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
 * every place that shows the mascot works unchanged.
 *
 * Drawn on a 30 × 34 pixel grid. Each part is painted into a grid, given a
 * one-pixel dark outline, and written out as one <path> per colour, so the
 * whole sprite stays around 3 KB.
 */
function vitalstack_mascot_pixel( $mood, $size, $class ) {
	static $cache = array();
	if ( ! isset( $cache[ $mood ] ) ) {
		$cache[ $mood ] = vitalstack_mascot_pixel_body( $mood );
	}
	return '<svg class="mascot mascot-pixel mascot-' . esc_attr( $mood ) . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" width="' . (int) $size . '" height="' . (int) round( $size * 1.12 ) . '" viewBox="0 0 30 34" shape-rendering="crispEdges" role="img" aria-label="' . esc_attr__( 'Bit the pixel robot', 'vitalstack' ) . '">'
		. $cache[ $mood ] . '</svg>';
}

/**
 * Paint helper for the sprite: a grid of colour codes.
 */
class VitalStack_Pixel_Grid {
	public $cells = array();

	public function rect( $x, $y, $w, $h, $c ) {
		for ( $j = $y; $j < $y + $h; $j++ ) {
			for ( $i = $x; $i < $x + $w; $i++ ) {
				$this->cells[ $j ][ $i ] = $c;
			}
		}
		return $this;
	}

	public function dots( $points, $c ) {
		foreach ( $points as $p ) {
			$this->cells[ $p[1] ][ $p[0] ] = $c;
		}
		return $this;
	}

	/** Rectangle with its corner pixels cut, for a rounded pixel look. */
	public function round( $x, $y, $w, $h, $c, $cut = 1 ) {
		for ( $j = 0; $j < $h; $j++ ) {
			$in = 0;
			if ( $j < $cut ) {
				$in = $cut - $j;
			} elseif ( $j >= $h - $cut ) {
				$in = $j - ( $h - $cut ) + 1;
			}
			$this->rect( $x + $in, $y + $j, $w - 2 * $in, 1, $c );
		}
		return $this;
	}

	public function filled( $x, $y ) {
		return isset( $this->cells[ $y ][ $x ] );
	}

	/** Dark outline on every empty cell next to a filled one, unless $under is filled there. */
	public function outline( $c = 'k', $under = null ) {
		$add = array();
		foreach ( $this->cells as $y => $row ) {
			foreach ( $row as $x => $v ) {
				foreach ( array( array( 1, 0 ), array( -1, 0 ), array( 0, 1 ), array( 0, -1 ) ) as $d ) {
					$nx = $x + $d[0];
					$ny = $y + $d[1];
					if ( $nx < 0 || $ny < 0 || $nx > 29 || $ny > 33 || $this->filled( $nx, $ny ) || ( $under && $under->filled( $nx, $ny ) ) ) {
						continue;
					}
					$add[ $ny ][ $nx ] = $c;
				}
			}
		}
		foreach ( $add as $y => $row ) {
			foreach ( $row as $x => $v ) {
				$this->cells[ $y ][ $x ] = $v;
			}
		}
		return $this;
	}

	/** One path per colour; equal runs on following rows are merged into rectangles. */
	public function svg() {
		$runs = array();
		ksort( $this->cells );
		foreach ( $this->cells as $y => $row ) {
			ksort( $row );
			$start = null;
			$prev  = null;
			$last  = null;
			foreach ( $row as $x => $c ) {
				if ( null !== $start && ( $c !== $prev || $x !== $last + 1 ) ) {
					$runs[ $prev ][] = array( $start, $y, $last - $start + 1 );
					$start           = null;
				}
				if ( null === $start ) {
					$start = $x;
				}
				$prev = $c;
				$last = $x;
			}
			if ( null !== $start ) {
				$runs[ $prev ][] = array( $start, $y, $last - $start + 1 );
			}
		}
		$out = '';
		foreach ( $runs as $c => $list ) {
			$rects = array();
			foreach ( $list as $r ) {
				$key = $r[0] . ':' . $r[2];
				if ( isset( $rects[ $key ] ) && $rects[ $key ][1] + $rects[ $key ][3] === $r[1] ) {
					++$rects[ $key ][3];
					continue;
				}
				if ( isset( $rects[ $key ] ) ) {
					$done[] = $rects[ $key ];
				}
				$rects[ $key ] = array( $r[0], $r[1], $r[2], 1 );
			}
			$all = array_merge( isset( $done ) ? $done : array(), array_values( $rects ) );
			unset( $done );
			$d = '';
			foreach ( $all as $r ) {
				$d .= 'M' . $r[0] . ' ' . $r[1] . 'h' . $r[2] . 'v' . $r[3] . 'h-' . $r[2] . 'z';
			}
			$out .= '<path class="px-' . $c . '" d="' . $d . '"/>';
		}
		return $out;
	}
}

function vitalstack_mascot_pixel_body( $mood ) {
	$g = function () {
		return new VitalStack_Pixel_Grid();
	};

	// Body: head, ear bolts, neck, torso, legs.
	$body = $g();
	$body->round( 3, 6, 24, 17, 'g', 3 )
		->rect( 1, 12, 2, 5, 'd' )->rect( 27, 12, 2, 5, 'd' )->dots( array( array( 1, 12 ), array( 27, 12 ) ), 'l' )
		->rect( 5, 7, 8, 1, 'l' )->rect( 4, 9, 1, 5, 'l' )->rect( 5, 8, 2, 1, 'l' )
		->rect( 6, 21, 18, 1, 'd' )->rect( 25, 10, 1, 10, 'd' )->rect( 24, 20, 1, 1, 'd' )
		->round( 6, 9, 18, 11, 'k', 2 )->round( 7, 10, 16, 9, 's', 2 )
		->dots( array( array( 8, 11 ), array( 9, 11 ), array( 8, 12 ) ), 'x' )
		->rect( 12, 23, 6, 1, 'd' )
		->round( 8, 24, 14, 7, 't', 1 )->rect( 9, 30, 12, 1, 'u' )
		->dots( array( array( 11, 26 ), array( 10, 27 ), array( 11, 28 ), array( 16, 26 ), array( 15, 27 ), array( 14, 28 ), array( 18, 26 ), array( 19, 27 ), array( 18, 28 ) ), 'c' )
		->rect( 10, 31, 3, 1, 'f' )->rect( 17, 31, 3, 1, 'f' );
	$body->outline( 'k' );

	// Antenna on its own so it can sway.
	$ant = $g();
	$ant->rect( 14, 4, 2, 2, 'd' )->round( 13, 1, 4, 3, 'y', 1 )->dots( array( array( 14, 1 ) ), 'w' )->dots( array( array( 15, 3 ) ), 'z' );
	$ant->outline( 'k', $body );

	// Face.
	$eyes = array(
		'happy'  => array( array( 10, 12, 3, 4 ), array( 18, 12, 3, 4 ) ),
		'think'  => array( array( 11, 11, 3, 4 ), array( 19, 11, 3, 4 ) ),
		'wow'    => array( array( 9, 11, 4, 5 ), array( 18, 11, 4, 5 ) ),
	);
	$face = $g();
	if ( isset( $eyes[ $mood ] ) ) {
		foreach ( $eyes[ $mood ] as $e ) {
			$face->rect( $e[0], $e[1], $e[2], $e[3], 'e' )->dots( array( array( $e[0], $e[1] ) ), 'w' );
		}
	} elseif ( 'cheer' === $mood ) {
		$face->dots( array( array( 10, 14 ), array( 11, 13 ), array( 12, 14 ), array( 18, 14 ), array( 19, 13 ), array( 20, 14 ) ), 'e' );
	} else {
		$face->rect( 10, 14, 3, 1, 'e' )->rect( 18, 14, 3, 1, 'e' );
	}
	$mouths = array(
		'happy'  => array( array( 13, 17 ), array( 14, 18 ), array( 15, 18 ), array( 16, 17 ) ),
		'cheer'  => array( array( 13, 17 ), array( 14, 17 ), array( 15, 17 ), array( 16, 17 ), array( 14, 18 ), array( 15, 18 ) ),
		'wow'    => array( array( 14, 17 ), array( 15, 17 ), array( 14, 18 ), array( 15, 18 ) ),
		'think'  => array( array( 13, 18 ), array( 14, 18 ), array( 15, 17 ), array( 16, 17 ) ),
		'sleepy' => array( array( 14, 17 ), array( 15, 17 ) ),
	);
	$face->dots( $mouths[ $mood ], 'm' );
	if ( 'cheer' === $mood ) {
		$face->dots( array( array( 14, 18 ), array( 15, 18 ) ), 'p' );
	}
	$face->dots( array( array( 8, 16 ), array( 9, 16 ), array( 21, 16 ), array( 22, 16 ) ), 'p' );

	// Arms: [ class, painter ] pairs.
	$arm = function ( $paint ) use ( $g, $body ) {
		$a = $g();
		$paint( $a );
		return $a->outline( 'k', $body );
	};
	$down_l = function ( $a ) {
		$a->rect( 5, 24, 2, 5, 'g' )->rect( 4, 29, 3, 2, 'l' );
	};
	$down_r = function ( $a ) {
		$a->rect( 23, 24, 2, 5, 'g' )->rect( 23, 29, 3, 2, 'l' );
	};
	$up_r = function ( $a ) {
		$a->rect( 23, 24, 2, 2, 'g' )->rect( 25, 22, 2, 2, 'g' )->rect( 26, 19, 3, 3, 'l' );
	};
	$up_l = function ( $a ) {
		$a->rect( 5, 24, 2, 2, 'g' )->rect( 3, 22, 2, 2, 'g' )->rect( 1, 19, 3, 3, 'l' );
	};
	$out_l = function ( $a ) {
		$a->rect( 2, 25, 5, 2, 'g' )->rect( 0, 24, 2, 4, 'l' );
	};
	$out_r = function ( $a ) {
		$a->rect( 23, 25, 5, 2, 'g' )->rect( 28, 24, 2, 4, 'l' );
	};
	$chin_r = function ( $a ) {
		$a->rect( 23, 24, 2, 2, 'g' )->rect( 24, 21, 2, 3, 'g' )->rect( 21, 19, 4, 3, 'l' );
	};
	$sets = array(
		'happy'  => array( array( '', $down_l ), array( 'm-wave', $up_r ) ),
		'cheer'  => array( array( 'm-wave px-wave-l', $up_l ), array( 'm-wave', $up_r ) ),
		'wow'    => array( array( '', $out_l ), array( '', $out_r ) ),
		'think'  => array( array( '', $down_l ), array( '', $chin_r ) ),
		'sleepy' => array( array( '', $down_l ), array( '', $down_r ) ),
	);

	$extra = '';
	if ( 'think' === $mood ) {
		$extra = '<g class="m-think px-bubbles">' . $g()->rect( 25, 6, 1, 1, 'b' )->round( 26, 2, 3, 3, 'b', 1 )->svg() . '</g>';
	} elseif ( 'cheer' === $mood ) {
		$extra = '<g class="m-sparks px-sparks">' . $g()->dots( array( array( 2, 4 ), array( 1, 5 ), array( 2, 5 ), array( 3, 5 ), array( 2, 6 ), array( 26, 3 ), array( 25, 4 ), array( 26, 4 ), array( 27, 4 ), array( 26, 5 ), array( 22, 0 ), array( 22, 1 ), array( 21, 1 ), array( 23, 1 ), array( 22, 2 ) ), 'y' )->svg() . '</g>';
	} elseif ( 'sleepy' === $mood ) {
		$extra = '<g class="m-think px-bubbles">' . $g()->rect( 23, 1, 4, 1, 'b' )->dots( array( array( 25, 2 ), array( 24, 3 ) ), 'b' )->rect( 23, 4, 4, 1, 'b' )->svg() . '</g>';
	}

	$out = '<path class="px-shadow" d="M8 33h14v1h-14z"/><g class="m-body-wrap">'
		. '<g class="m-antenna">' . str_replace( 'class="px-y"', 'class="px-y m-bulb"', $ant->svg() ) . '</g>'
		. $body->svg()
		. '<g class="m-eyes">' . $face->svg() . '</g>';
	foreach ( $sets[ $mood ] as $set ) {
		$svg  = $arm( $set[1] )->svg();
		$out .= $set[0] ? '<g class="' . $set[0] . '">' . $svg . '</g>' : $svg;
	}
	return $out . $extra . '</g>';
}
