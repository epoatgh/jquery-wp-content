<?php

remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'rel_canonical' );

// Remove shortlink <head> and header.
remove_action( 'wp_head',             'wp_shortlink_wp_head', 10 );
remove_action( 'template_redirect',   'wp_shortlink_header',  11 );

add_action( 'init', function() {
	global $events;

	$events = array(
		'future' => array(),
		'past' => array(),
		'year' => array(),
	);

	$eventsFile = gw_resources_dir(
			(JQUERY_STAGING ? JQUERY_STAGING_PREFIX : '') . 'events.jquery.org'
		) . '/events.json';
	$allEvents = file_exists( $eventsFile ) ?
		json_decode( file_get_contents( $eventsFile ) ) :
		array();

	$now = time();
	foreach ( $allEvents as $event ) {
		$event->end = strtotime( $event-> end );
		$year = Date('Y', $event->end);

		$events[ 'year' ][ $year ][] = $event;
		if ( $event->end > $now ) {
			$events[ 'future' ][] = $event;
		} else {
			$events[ 'past' ][ $year ][] = $event;
		}
	}

	$events[ 'past' ] = array_reverse( $events[ 'past' ], true );
});
