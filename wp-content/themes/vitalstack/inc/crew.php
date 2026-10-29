<?php
/**
 * The VitalStack crew: four pixel characters drawn as inline SVG.
 *
 *  - Dev   (student coder): homepage, roadmaps, cheat sheets.
 *  - Chip  (helper robot): popups, notifications, visualizers, tools.
 *  - Bugsy (ladybug with a magnifier): quizzes, mock tests, DSA problems.
 *  - Byte  (cat with headphones): games and the daily challenge.
 *
 * Sprites are rows of colour letters ('.' is empty). Moods change the eyes and
 * add a small effect (sparkles, !, ?, z); the existing mascot animations
 * (float, hop) apply because the body sits in .m-body-wrap.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_crew() {
	return array(
		'dev' => array(
			'name'   => 'Dev',
			'pal'    => array( 'K' => '#1b1f27', 'H' => '#3a2618', 'h' => '#5a3a24', 'S' => '#f4c7a1', 's' => '#dca27c', 'G' => '#4a5568', 'g' => '#e6f1ff', 'E' => '#1b1f27', 'W' => '#ffffff', 'M' => '#b9534a', 'R' => '#0a7d4f', 'r' => '#075c3a', 'w' => '#e8f6ee', 'L' => '#d5dbe3', 'l' => '#9aa4b2', 'C' => '#34c987', 'P' => '#2b3a55', 'B' => '#1b1f27' ),
			'eyes'   => 'E',
			'rows'   => array(
				'......KKKKK.....',
				'....KKHHHHHKK...',
				'...KHHHhHHHHHK..',
				'...KHhHHHHHhHK..',
				'...KHSHSSSHSHK..',
				'...KSSSSSSSSSK..',
				'...KSGGGSGGGSK..',
				'...KGgEGGGgEGK..',
				'...KSGGGSGGGSK..',
				'...KsSSSSSSSsK..',
				'....KSSSMMSSK...',
				'.....KKSSSKK....',
				'...KRRRwSwRRRK..',
				'..KRRRRwRwRRRRK.',
				'..KRLLLLLLLLLRK.',
				'..KSLLLLCLLLLSK.',
				'..KRLLLCLCLLLRK.',
				'..KRlllllllllRK.',
				'...KRRRRRRRRRK..',
				'...KPPPPKPPPPK..',
				'...KPPPK.KPPPK..',
				'..KBBBBK.KBBBBK.',
			),
			'closed' => array( 7 => '...KGEEGGGEEGK..' ),
			'wide'   => array(  ),
		),
		'chip' => array(
			'name'   => 'Chip',
			'pal'    => array( 'K' => '#1b1f27', 'W' => '#ffffff', 'w' => '#dfe4ea', 'g' => '#b9c1cc', 'V' => '#14213a', 'E' => '#5fd6ff', 'e' => '#bff0ff', 'O' => '#ff7a1a', 'o' => '#ffb36b', 'M' => '#5fd6ff' ),
			'eyes'   => 'Ee',
			'rows'   => array(
				'.......KK.......',
				'......KOOK......',
				'.......KK.......',
				'.......KK.......',
				'...KKKKKKKKKK...',
				'..KWWWWWWWWWwK..',
				'..KWVVVVVVVVwK..',
				'.KOWVeEVVeEVwOK.',
				'.KOWVEEVVEEVwOK.',
				'..KWVVVMMVVVwK..',
				'..KWWWWWWWWWwK..',
				'...KwwwwwwwwK...',
				'....KKKKKKKK....',
				'...KWWWWWWWwK...',
				'..KgKWOOOOWwKgK.',
				'..KgKWWWWWWwKgK.',
				'..KKKWWWWWWwKKK.',
				'....KwwwwwwK....',
				'....KgK..KgK....',
				'...KKKK..KKKK...',
			),
			'closed' => array( 7 => '.KOWVVVVVVVVwOK.' ),
			'wide'   => array( 6 => '..KWVeEVVeEVwK..' ),
		),
		'bugsy' => array(
			'name'   => 'Bugsy',
			'pal'    => array( 'K' => '#1b1f27', 'R' => '#e5484d', 'r' => '#ff7b80', 'D' => '#1b1f27', 'W' => '#ffffff', 'E' => '#1b1f27', 'F' => '#2b2f3a', 'Y' => '#ffc53d', 'y' => '#c98a00', 'g' => '#bfe8ff', 'P' => '#ff8fab' ),
			'eyes'   => 'E',
			'rows'   => array(
				'...K........K.......',
				'....K......K........',
				'.....KKKKKK.........',
				'....KFFFFFFK........',
				'...KFWWFFWWFK..KKK..',
				'...KFWEFFWEFK.KYYYK.',
				'...KFPFFFFPFK.KYggYK',
				'..KKRRrDDRRRKKKYggYK',
				'.KRRrRRDDRRRRKKYggYK',
				'.KRDDRRDDRRDDRKKYYYK',
				'.KRDDRRDDRRDDRKyKKK.',
				'.KRRRRRDDRRRRKyK....',
				'.KRRRDDDDDDRRKK.....',
				'..KRRDDRRDDRRK......',
				'...KRRRDDRRRK.......',
				'..K.KKKKKKKK.K......',
				'.K..K..K..K...K.....',
			),
			'closed' => array( 4 => '...KFFFFFFFFK..KKK..', 5 => '...KFWWFFWWFK.KYYYK.' ),
			'wide'   => array(  ),
		),
		'byte' => array(
			'name'   => 'Byte',
			'pal'    => array( 'K' => '#1b1f27', 'O' => '#f29b38', 'o' => '#d97c1e', 'W' => '#fff4e6', 'E' => '#1b1f27', 'e' => '#ffffff', 'P' => '#ff8fab', 'N' => '#e5677d', 'H' => '#0a7d4f', 'h' => '#34c987' ),
			'eyes'   => 'Ee',
			'rows'   => array(
				'..KK........KK..',
				'.KOOK......KOOK.',
				'.KOPOKKKKKKOPOK.',
				'HHKOOoOOOOoOOKHH',
				'HhKOOOOOOOOOOKhH',
				'HhKOEeOOOOEeOKhH',
				'HHKOEEOOOOEEOKHH',
				'.KOWWOONNOOWWOK.',
				'.KOWWOKOOKOWWOK.',
				'..KOOOOKKOOOOK..',
				'...KKOOOOOOKK...',
				'...KOOWWWWOOK...',
				'..KOoOWWWWOoOK..',
				'..KOOOWWWWOOOK.K',
				'..KOoOOWWOOoOKKO',
				'..KOOOOOOOOOOKoK',
				'...KWWKKKKWWKK..',
			),
			'closed' => array( 5 => 'HhKOOOOOOOOOOKhH' ),
			'wide'   => array(  ),
		),
	);
}
/**
 * One crew member as SVG.
 *
 * @param string $who   dev | chip | bugsy | byte.
 * @param string $mood  happy | cheer | wow | think | sleepy.
 * @param int    $size  Width in pixels.
 * @param string $class Extra classes.
 */
function vitalstack_character( $who = 'chip', $mood = 'happy', $size = 120, $class = '' ) {
	static $cache = array();
	$crew = vitalstack_crew();
	$who  = isset( $crew[ $who ] ) ? $who : 'chip';
	$mood = in_array( $mood, array( 'happy', 'wow', 'think', 'cheer', 'sleepy' ), true ) ? $mood : 'happy';
	$key  = $who . '|' . $mood;
	if ( ! isset( $cache[ $key ] ) ) {
		$cache[ $key ] = vitalstack_character_body( $crew[ $who ], $mood );
	}
	list( $body, $vw, $vh ) = $cache[ $key ];
	// Fit inside the same box the original mascot used (size × 1.12 size).
	$width  = min( $size, $size * 1.12 * $vw / $vh );
	$height = $width * $vh / $vw;
	/* translators: %s: character name */
	$label = sprintf( __( '%s, the VitalStack crew', 'vitalstack' ), $crew[ $who ]['name'] );
	return '<svg class="mascot mascot-crew mascot-' . esc_attr( $who ) . ' mascot-' . esc_attr( $mood ) . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" width="' . (int) round( $width ) . '" height="' . (int) round( $height ) . '" viewBox="0 0 ' . $vw . ' ' . $vh . '" shape-rendering="crispEdges" role="img" aria-label="' . esc_attr( $label ) . '">'
		. $body . '</svg>';
}

/**
 * @return array( svg markup, viewBox width, viewBox height )
 */
function vitalstack_character_body( $c, $mood ) {
	$rows = $c['rows'];
	if ( 'cheer' === $mood || 'sleepy' === $mood ) {
		$rows = array_replace( $rows, $c['closed'] );
	} elseif ( 'wow' === $mood ) {
		$rows = array_replace( $rows, $c['wide'] );
	}
	$w = max( array_map( 'strlen', $rows ) );
	$h = count( $rows );
	// Sprite at (1, 5): room above and to the right for the mood effect, below for the shadow.
	$ox = 1;
	$oy = 5;

	$body = array();
	$eyes = array();
	foreach ( $rows as $y => $row ) {
		$len = strlen( $row );
		for ( $x = 0; $x < $len; ) {
			$ch = $row[ $x ];
			if ( '.' === $ch ) {
				++$x;
				continue;
			}
			$end = $x;
			while ( $end < $len && $row[ $end ] === $ch ) {
				++$end;
			}
			$seg = 'M' . ( $x + $ox ) . ' ' . ( $y + $oy ) . 'h' . ( $end - $x ) . 'v1h-' . ( $end - $x ) . 'z';
			if ( false !== strpos( $c['eyes'], $ch ) ) {
				$eyes[ $ch ][] = $seg;
			} else {
				$body[ $ch ][] = $seg;
			}
			$x = $end;
		}
	}
	$paint = function ( $groups ) use ( $c ) {
		$out = '';
		foreach ( $groups as $ch => $segs ) {
			$out .= '<path fill="' . esc_attr( $c['pal'][ $ch ] ) . '" d="' . implode( '', $segs ) . '"/>';
		}
		return $out;
	};

	// Effects drawn in a 4 × 7 corner box at the top right.
	$fx = array(
		'cheer'  => array( 'vs-fx-spark', 'M2 0h1v1h-1zM1 1h3v1h-3zM2 2h1v1h-1zM0 4h1v1h-1zM-1 5h3v1h-3zM0 6h1v1h-1z' ),
		'wow'    => array( 'vs-fx-alert', 'M1 0h2v4h-2zM1 5h2v2h-2z' ),
		'think'  => array( 'vs-fx-soft', 'M0 0h4v1h-4zM3 1h1v2h-1zM1 3h2v1h-2zM1 4h1v1h-1zM1 6h1v1h-1z' ),
		'sleepy' => array( 'vs-fx-soft', 'M0 0h4v1h-4zM2 1h1v1h-1zM1 2h1v1h-1zM0 3h4v1h-4z' ),
	);
	$extra  = isset( $fx[ $mood ] ) ? '<path class="vs-fx ' . $fx[ $mood ][0] . '" transform="translate(' . $w . ' 0)" d="' . $fx[ $mood ][1] . '"/>' : '';
	$shadow = '<path class="vs-crew-shadow" d="M' . ( $ox + 2 ) . ' ' . ( $oy + $h ) . 'h' . ( $w - 4 ) . 'v1h-' . ( $w - 4 ) . 'z"/>';

	return array(
		$shadow . '<g class="m-body-wrap">' . $paint( $body ) . '<g class="vs-eyes">' . $paint( $eyes ) . '</g></g>' . $extra,
		$w + 5,
		$h + 7,
	);
}
