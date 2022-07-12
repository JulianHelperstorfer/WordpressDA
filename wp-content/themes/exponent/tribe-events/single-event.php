<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();

$event_id = get_the_ID();

/**
 * Allows filtering of the single event template title classes.
 *
 * @since 5.8.0
 *
 * @param array  $title_classes List of classes to create the class string from.
 * @param string $event_id The ID of the displayed event.
 */
$title_classes = apply_filters( 'tribe_events_single_event_title_classes', [ 'tribe-events-single-event-title' ], $event_id );
$title_classes = implode( ' ', tribe_get_classes( $title_classes ) );

/**
 * Allows filtering of the single event template title before HTML.
 *
 * @since 5.8.0
 *
 * @param string $before HTML string to display before the title text.
 * @param string $event_id The ID of the displayed event.
 */
$before = apply_filters( 'tribe_events_single_event_title_html_before', '<h1 class="' . $title_classes . '">', $event_id );

/**
 * Allows filtering of the single event template title after HTML.
 *
 * @since 5.8.0
 *
 * @param string $after HTML string to display after the title text.
 * @param string $event_id The ID of the displayed event.
 */
$after = apply_filters( 'tribe_events_single_event_title_html_after', '</h1>', $event_id );

/**
 * Allows filtering of the single event template title HTML.
 *
 * @since 5.8.0
 *
 * @param string $after HTML string to display. Return an empty string to not display the title.
 * @param string $event_id The ID of the displayed event.
 */
$title = apply_filters( 'tribe_events_single_event_title_html', the_title( $before, $after, false ), $event_id );

?>



<div id="tribe-events-content" class="tribe-events-single">

	<p class="tribe-events-back">
		<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"> <?php printf( '&laquo; ' . esc_html_x( 'All %s', '%s Events plural label', 'the-events-calendar' ), $events_label_plural ); ?></a>
	</p>

	<!-- Notices -->
	<?php tribe_the_notices() ?>

	<?php echo $title; ?>


	
	


	<div class="tribe-events-schedule tribe-clearfix">
		<?php echo tribe_events_event_schedule_details( $event_id, '<h2>', '</h2>' ); ?>
		<?php if ( tribe_get_cost() ) : ?>
			<span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>
		<?php endif; ?>
	</div>


	

	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
		<!-- Navigation -->
		<nav class="tribe-events-nav-pagination" aria-label="<?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?>">
			<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
			</ul>
			<!-- .tribe-events-sub-nav -->
		</nav>
	</div>




	<!-- #tribe-events-header -->

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<!-- Event featured image, but exclude link -->
			<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>

			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content">
				<?php the_content(); ?>

				<!-- Sebastian Gojer 11.07.2022+11:44 ---- Button zum amelden -->
				<div  class="tatsu-Hk3Rky84jq tatsu-section  tatsu-bg-overlay   tatsu-clearfix" data-title=""  data-headerscheme="background--dark"><div class='tatsu-section-pad clearfix' data-padding='{"d":"90px 0px 90px 0px"}' data-padding-top='90px'><div class="tatsu-row-wrap  tatsu-wrap tatsu-row-one-col tatsu-row-has-one-cols tatsu-medium-gutter tatsu-reg-cols  tatsu-clearfix tatsu-BkjAkyIVj9" ><div  class="tatsu-row " ><div  class="tatsu-column  tatsu-bg-overlay tatsu-one-col tatsu-column-image-none tatsu-column-effect-none  tatsu-rJ9AkJIEi5"  data-parallax-speed="0" style=""><div class="tatsu-column-inner " ><div class="tatsu-column-pad-wrap"><div class="tatsu-column-pad" ><div  class="tatsu-module tatsu-normal-button tatsu-button-wrap   tatsu-H1VGJL4i5   "><a class="tatsu-shortcode mediumbtn tatsu-button left-icon circular   bg-animation-none  " href="Schulungsanmeldung" style= ""  aria-label="Anmelden" data-gdpr-atts={} >Anmelden</a><style>.tatsu-H1VGJL4i5 .tatsu-button{background-color: rgba(0,205,152,1);color: #ffffff ;}</style></div></div></div><div class = "tatsu-column-bg-image-wrap"><div class = "tatsu-column-bg-image" ></div></div><div class="tatsu-overlay tatsu-column-overlay tatsu-animate-none" ></div></div><style>.tatsu-row > .tatsu-rJ9AkJIEi5.tatsu-column{width: 100%;}.tatsu-rJ9AkJIEi5.tatsu-column > .tatsu-column-inner > .tatsu-column-overlay{mix-blend-mode: normal;}.tatsu-rJ9AkJIEi5 > .tatsu-column-inner > .tatsu-top-divider{z-index: 9999;}.tatsu-rJ9AkJIEi5 > .tatsu-column-inner > .tatsu-bottom-divider{z-index: 9999;}.tatsu-rJ9AkJIEi5 > .tatsu-column-inner > .tatsu-left-divider{z-index: 9999;}.tatsu-rJ9AkJIEi5 > .tatsu-column-inner > .tatsu-right-divider{z-index: 9999;}@media only screen and (max-width:1377px) {.tatsu-row > .tatsu-rJ9AkJIEi5.tatsu-column{width: 100%;}}@media only screen and (min-width:768px) and (max-width: 1024px) {.tatsu-row > .tatsu-rJ9AkJIEi5.tatsu-column{width: 100%;}}@media only screen and (max-width: 767px) {.tatsu-row > .tatsu-rJ9AkJIEi5.tatsu-column{width: 100%;}}</style></div></div></div></div><div class="tatsu-section-background-wrap"><div class = "tatsu-section-background" ></div></div><div class="tatsu-overlay tatsu-section-overlay"></div><style>.tatsu-Hk3Rky84jq .tatsu-section-pad{padding: 90px 0px 90px 0px;}.tatsu-Hk3Rky84jq .tatsu-section-offset-wrap{transform: translateY(-0px);}.tatsu-Hk3Rky84jq > .tatsu-bottom-divider{z-index: 9999;}.tatsu-Hk3Rky84jq > .tatsu-top-divider{z-index: 9999;}.tatsu-Hk3Rky84jq .tatsu-section-overlay{mix-blend-mode: normal;}</style></div>

				
			</div>
			<!-- .tribe-events-single-event-description -->
			<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>

			<!-- Event meta -->
			<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
			<?php tribe_get_template_part( 'modules/meta' ); ?>
			<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
	
	
		</div> <!-- #post-x -->
		<?php if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
	<?php endwhile; ?>

	
	
	
	

	<!-- Event footer -->
	<div id="tribe-events-footer">
		<!-- Navigation -->
		<nav class="tribe-events-nav-pagination" aria-label="<?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?>">
			<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
			</ul>
			<!-- .tribe-events-sub-nav -->
		</nav>
	</div>
	<!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->
