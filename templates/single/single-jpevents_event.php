<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php
    while ( have_posts() ) : the_post();
        $event_date = get_post_meta(get_the_ID(), 'jpevents_date', true);
        $event_time = get_post_meta(get_the_ID(), 'jpevents_time', true);
        $event_location = get_post_meta(get_the_ID(), 'jpevents_location', true);
        $event_image = get_post_meta(get_the_ID(), 'jpevents_image', true);
    ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header>

            <div class="entry-content">
                <div class="event-image">
                    <?php if ( $event_image ): ?>
                        <img src="<?php echo esc_url($event_image); ?>" alt="<?php the_title_attribute(); ?>" class="featured-image">
                    <?php elseif ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'full', ['class' => 'featured-image'] ); ?>
                    <?php endif; ?>
                </div>
                <div class="event-details">
                    <?php the_content(); ?>

                    <div><strong>Date:</strong> <?php echo esc_html($event_date); ?></div>
                    <div><strong>Time:</strong> <?php echo esc_html($event_time); ?></div>
                    <div><strong>Location:</strong> <?php echo esc_html($event_location); ?></div>

                    <div class="event-categories-tags">
                        <span class="tags-label"><strong>Categories:</strong></span>
                        <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) {
                            foreach ( $categories as $category ) {
                                $cat_link = get_category_link( $category->term_id );
                                echo '<span class="category-tag">' . esc_html( $category->name ) . '</span>';
                            }
                        }
                        ?>
                    </div>

                    <div class="event-categories-tags">
                        <span class="tags-label"><strong>Tags:</strong></span>
                        <?php
                        $tags = get_the_tags();
                        if ( ! empty( $tags ) ) {
                            foreach ( $tags as $tag ) {
                                $tag_link = get_tag_link( $tag->term_id );
                                echo '<span class="event-tag">' . esc_html( $tag->name ) . '</span>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="rsvp-form-container">
                <h3>RSVP for this Event</h3>
                <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')) ?>">
                    <input type="hidden" name="action" value="handle_rsvp">
                    <input type="hidden" name="event_id" value="<?php echo esc_attr($post->ID) ?>">
                    <input type="text" name="email" placeholder="Enter your email">
                    <input type="submit" value="RSVP">
                </form>

                <div class="social-sharing-buttons">
                    <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url(get_permalink()) ?>&text=<?php esc_url(the_title()); ?>" target="_blank">Share on X</a>
                </div>
            </div>
        </article>

    <?php endwhile; ?>

    </main>
</div>

<?php get_footer(); ?>
