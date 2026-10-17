<?php
/**
 * Installable app (PWA): web manifest and service worker served from the site
 * root, so the whole site is in scope. Lessons you open are kept for offline
 * reading; theme files load from cache; an offline page lists saved lessons.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_pwa_path( $file ) {
	return rtrim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' ) . '/' . $file;
}

function vitalstack_pwa_request_path() {
	return isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) : '';
}

/**
 * Serve /site.webmanifest and /sw.js.
 */
function vitalstack_pwa_serve() {
	$path = vitalstack_pwa_request_path();
	if ( vitalstack_pwa_path( 'site.webmanifest' ) === $path ) {
		vitalstack_pwa_manifest();
	}
	if ( vitalstack_pwa_path( 'sw.js' ) === $path ) {
		vitalstack_pwa_worker();
	}
}
add_action( 'init', 'vitalstack_pwa_serve', 0 );

function vitalstack_pwa_manifest() {
	$scope = vitalstack_pwa_path( '' );
	$icon  = function ( $file, $size, $purpose = 'any' ) {
		return array(
			'src'     => vitalstack_brand_url( $file ),
			'sizes'   => $size,
			'type'    => 'image/png',
			'purpose' => $purpose,
		);
	};
	$data = array(
		'name'             => get_bloginfo( 'name' ) . ' – ' . __( 'Learn to code', 'vitalstack' ),
		'short_name'       => get_bloginfo( 'name' ),
		'description'      => get_bloginfo( 'description' ),
		'start_url'        => $scope . '?source=pwa',
		'scope'            => $scope,
		'id'               => $scope,
		'display'          => 'standalone',
		'background_color' => '#ffffff',
		'theme_color'      => '#0a7d4f',
		'categories'       => array( 'education', 'productivity' ),
		'icons'            => array(
			$icon( 'icon-192.png', '192x192' ),
			$icon( 'icon-512.png', '512x512' ),
			$icon( 'icon-maskable-512.png', '512x512', 'maskable' ),
		),
		'shortcuts'        => array_values(
			array_filter(
				array(
					array(
						'name' => __( 'Daily challenge', 'vitalstack' ),
						'url'  => function_exists( 'vitalstack_challenge_url' ) ? vitalstack_challenge_url() : '',
					),
					array(
						'name' => __( 'Tutorials', 'vitalstack' ),
						'url'  => vitalstack_tutorials_url(),
					),
					array(
						'name' => __( 'Brain games', 'vitalstack' ),
						'url'  => function_exists( 'vitalstack_games_url' ) ? vitalstack_games_url() : '',
					),
				),
				function ( $s ) {
					return ! empty( $s['url'] );
				}
			)
		),
	);
	status_header( 200 );
	header( 'Content-Type: application/manifest+json; charset=utf-8' );
	header( 'Cache-Control: public, max-age=86400' );
	echo wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	exit;
}

function vitalstack_pwa_worker() {
	$version = VITALSTACK_VERSION;
	$home    = vitalstack_pwa_path( '' );
	$offline = array(
		'title' => __( "You're offline", 'vitalstack' ),
		'text'  => __( 'No internet connection right now. Lessons you opened before are saved on this device:', 'vitalstack' ),
		'none'  => __( 'Nothing saved yet. Open a few lessons while online and they will be here next time.', 'vitalstack' ),
		'retry' => __( 'Try again', 'vitalstack' ),
		'name'  => get_bloginfo( 'name' ),
	);
	status_header( 200 );
	header( 'Content-Type: text/javascript; charset=utf-8' );
	header( 'Cache-Control: no-cache' );
	header( 'Service-Worker-Allowed: ' . $home );
	?>
/* <?php echo esc_js( get_bloginfo( 'name' ) ); ?> service worker <?php echo esc_js( $version ); ?> */
var V = <?php echo wp_json_encode( $version ); ?>;
var HOME = <?php echo wp_json_encode( $home ); ?>;
var PAGES = 'vs-pages', ASSETS = 'vs-assets-' + V;
var TEXT = <?php echo wp_json_encode( $offline ); ?>;
var SKIP = /\/(wp-admin|wp-login\.php|wp-json|account|certificate|feed|xmlrpc\.php|sw\.js)(\/|$|\?)|[?&](s|preview|vs_verify|unsubscribe)=/;

self.addEventListener('install', function (e) {
	e.waitUntil(caches.open(PAGES).then(function (c) { return c.add(HOME).catch(function () {}); }).then(function () { return self.skipWaiting(); }));
});
self.addEventListener('activate', function (e) {
	e.waitUntil(caches.keys().then(function (keys) {
		return Promise.all(keys.filter(function (k) { return k.indexOf('vs-assets-') === 0 && k !== ASSETS; }).map(function (k) { return caches.delete(k); }));
	}).then(function () { return self.clients.claim(); }));
});

function trim(cache, max) {
	return cache.keys().then(function (keys) { if (keys.length > max) return cache.delete(keys[0]).then(function () { return trim(cache, max); }); });
}
function offlinePage() {
	return caches.open(PAGES).then(function (c) { return c.keys(); }).then(function (keys) {
		var items = keys.map(function (r) { return r.url; }).filter(function (u) { return u.indexOf('/tutorials/') > -1 || u.indexOf('/cheat-sheets/') > -1; });
		var list = items.length ? '<ul>' + items.map(function (u) { var name = decodeURIComponent(u.replace(/\/$/, '').split('/').pop()).replace(/-/g, ' '); return '<li><a href="' + u + '">' + name.replace(/</g, '') + '</a></li>'; }).join('') + '</ul>' : '<p>' + TEXT.none + '</p>';
		var html = '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' + TEXT.title + ' · ' + TEXT.name + '</title>' +
			'<style>body{font-family:system-ui,sans-serif;max-width:640px;margin:0 auto;padding:40px 20px;color:#1b1f27;line-height:1.6}h1{color:#0a7d4f}a{color:#0a7d4f}li{margin:6px 0;text-transform:capitalize}button{padding:10px 18px;border:0;border-radius:8px;background:#0a7d4f;color:#fff;font-size:16px}@media (prefers-color-scheme:dark){body{background:#10141c;color:#e8ecf2}h1,a{color:#34c987}}</style></head>' +
			'<body><p style="font-size:48px;margin:0">📡</p><h1>' + TEXT.title + '</h1><p>' + TEXT.text + '</p>' + list + '<p><button onclick="location.reload()">' + TEXT.retry + '</button></p></body></html>';
		return new Response(html, { headers: { 'Content-Type': 'text/html; charset=utf-8' } });
	});
}

self.addEventListener('fetch', function (e) {
	var req = e.request, url = new URL(req.url);
	if (req.method !== 'GET' || url.origin !== self.location.origin || SKIP.test(url.pathname + url.search)) return;

	if (req.mode === 'navigate') {
		e.respondWith(fetch(req).then(function (res) {
			if (res.ok && res.type === 'basic') {
				var copy = res.clone();
				caches.open(PAGES).then(function (c) { return c.put(req, copy).then(function () { return trim(c, 80); }); });
			}
			return res;
		}).catch(function () {
			return caches.match(req, { ignoreSearch: true }).then(function (hit) { return hit || offlinePage(); });
		}));
		return;
	}

	if (/\/wp-content\/themes\/|\/wp-includes\/|\.(?:woff2|png|svg|ico)$/.test(url.pathname)) {
		e.respondWith(caches.open(ASSETS).then(function (c) {
			return c.match(req).then(function (hit) {
				var net = fetch(req).then(function (res) { if (res.ok) c.put(req, res.clone()); return res; }).catch(function () { return hit; });
				return hit || net;
			});
		}));
	}
});
<?php
	exit;
}

/**
 * Manifest link (replaces the static one) and service worker registration URL.
 */
function vitalstack_pwa_head() {
	printf( '<link rel="manifest" href="%s">' . "\n", esc_url( home_url( '/site.webmanifest' ) ) );
}
add_action( 'wp_head', 'vitalstack_pwa_head', 3 );

add_filter(
	'vitalstack_script_data',
	function ( $data ) {
		$data['sw'] = esc_url_raw( home_url( '/sw.js' ) );
		return $data;
	}
);
