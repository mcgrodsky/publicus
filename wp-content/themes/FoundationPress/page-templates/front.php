<?php
/*
Template Name: Front
*/
get_header(); ?>

<header id="front-hero" role="banner">
  <div class="marketing">
    <div class="front-text">
      <h4 class="front-hero-text">Publicus is an open platform for researchers to instantly share and collectively review new work. With Publicus, research will be reviewed by a community of peers and evaluated based on its own merits, not its affiliations. <strong>Welcome to research for the 21st century.</strong></h4>
    </div>
    <div class="front-img">
    <img width="512" alt="Carina Nebula in visible light (captured by the Hubble Space Telescope)" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/07/Carina_Nebula_in_visible_light_%28captured_by_the_Hubble_Space_Telescope%29.jpg/512px-Carina_Nebula_in_visible_light_%28captured_by_the_Hubble_Space_Telescope%29.jpg"/></a>
    </div>
    <!-- <div class="tagline">
      <h1><?php bloginfo( 'name' ); ?></h1>
      <h4 class="subheader"><?php bloginfo( 'description' ); ?></h4>
    </div> -->
  </div>
</header>


<?php do_action( 'foundationpress_before_content' ); ?>
<?php while ( have_posts() ) : the_post(); ?>
<section class="intro" role="main">
  <div class="fp-intro">

    <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
      <?php do_action( 'foundationpress_page_before_entry_content' ); ?>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <footer>
        <?php wp_link_pages( array('before' => '<nav id="page-nav"><p>' . __( 'Pages:', 'foundationpress' ), 'after' => '</p></nav>' ) ); ?>
        <p><?php the_tags(); ?></p>
      </footer>
      <?php do_action( 'foundationpress_page_before_comments' ); ?>
      <?php comments_template(); ?>
      <?php do_action( 'foundationpress_page_after_comments' ); ?>
    </div>

  </div>

</section>
<?php endwhile;?>
<?php do_action( 'foundationpress_after_content' ); ?>


<?php get_footer();
