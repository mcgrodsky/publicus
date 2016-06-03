<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<div id="single-paper" role="main">

<?php do_action( 'foundationpress_before_content' ); ?>
<?php while ( have_posts() ) : the_post(); ?>
  <article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
    <?php do_action( 'foundationpress_post_before_entry_content' ); ?>
    <div class="entry-content">
    <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages( array('before' => '<nav id="page-nav"><p>' . __( 'Pages:', 'foundationpress' ), 'after' => '</p></nav>' ) ); ?>
      <p><?php the_tags(); ?></p>
    </footer>
    <?php do_action( 'foundationpress_post_before_comments' ); ?>
    <?php comments_template(); ?>
    <?php do_action( 'foundationpress_post_after_comments' ); ?>
  </article>
<?php endwhile;?>

<?php do_action( 'foundationpress_after_content' ); ?>
</div>
<?php get_footer();
