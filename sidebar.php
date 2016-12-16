<?php if ( is_active_sidebar( 'home_right_sidebar' ) ) : ?>
    <?php dynamic_sidebar( 'home_right_sidebar'); ?>
<?php else: ?>
    <h2>.Upcoming Events</h2>
    <table class="table table-striped">
            <tbody>
            <?php 
            // the query
            $the_query = new WP_Query('category_name=Upcoming Events'); ?>

            <?php if ( $the_query->have_posts() ) : ?>
              <!-- the loop -->
              <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <?php title(); ?>
              <?php endwhile; ?>
              <!-- end of the loop -->
              <?php wp_reset_postdata(); ?>

            <?php else:  ?>
                <tr>
                        <td colspan="2">Monday, January 20</td>
                </tr>
                <tr>
                        <td>6:00pm</td>
                        <td><a href="#">ECE Course Selection Info Session</a></td>
                </tr>
                <tr>
                <td colspan="2">Wednesday, January 22</td>
                </tr>
                <tr>
                        <td>12:00pm</td>
                        <td><a href="https://www.facebook.com/events/216769335177865/?ref_dashboard_filter=upcoming">ECE1T6 Class Photo</a></td>
                </tr>
                <tr>
                <td colspan="2">Thursday, January 23</td>
                </tr>
                <tr>
                        <td>5:00pm</td>
                        <td><a href="#">Iron Ring Session</a></td>
                </tr>
                <tr>
                <td colspan="2">Friday, February 14</td>
                </tr>
                <tr>
                        <td>9:00am</td>
                        <td><a href="http://ece.skule.ca/dinnerdance">ECE Dinner Dance</a></td>
                </tr>
                <tr>
                <td colspan="2">Saturday, March 1</td>
                </tr>
                <tr>
                        <td>11:00am</td>
                        <td><a href="#">Iron Ring Ceremony</a></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                        <td colspan="2"><i>Showing Events until 03/01/2014</i></td>
                </tr>
            </tfoot>
            <?php endif; ?>
    </table>
<?php endif; ?>