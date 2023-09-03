<?php 
/*
 *  Courses List
 */
get_header();?>
	<?php
	global $page_title;
	$queried_cat = get_queried_object();
	$term_id = $queried_cat->term_id;
	$name = $queried_cat->name;	
	$slug = $queried_cat->slug;
	$page_title = $name; //overwrite page title
	get_template_part( 'header', 'heading' ); ?>
    <div id="body">
    	<div class="container">
        	<div class="content-pad-3x">
            <?php
			$date_format = get_option('date_format'); ?>
             	<div class="row">
                    <div id="content" class="col-md-9">
						<?php
                            if(function_exists('cop_get')){
                                $slug_pt =  cop_get('u_course_settings','ucourse-slug');
                                $filter =  cop_get('u_course_settings','ucourse-filter');
                            }  
                            if($slug_pt==''){
                                $slug_pt = 'course';
                            }
                            ct_filter_order_lis($post_type='u_course');
                            if($filter==1){
                                ct_filter_bar($taxono='u_course_cat',$slug_pt);
                                ?>
                                <style type="text/css">.courses-list{ padding-top:0}</style>
                                <?php
                            }
                        ?>
                        <div class="courses-list">
                         <?php if ( have_posts() ) : ?>
                         <table class="table course-list-table">
                          <thead class="main-color-1-bg dark-div">
                            <tr>
                              <th><?php _e('ID','cactusthemes'); ?></th>
                              <th><?php _e('Course Name','cactusthemes'); ?></th>
                              <th><?php _e('Duration','cactusthemes'); ?></th>
                              <th><?php _e('Start Date','cactusthemes'); ?></th>
                            </tr>
                          </thead>
                          <tbody>                          
                          <?php
							while ( have_posts() ) : the_post(); 
							$startdate = get_post_meta(get_the_ID(),'u-course-start', true );
							if($startdate){
								$startdate = gmdate("Y-m-d\TH:i:s\Z", $startdate);// convert date ux
								$con_date = new DateTime($startdate);
								$start_datetime = $con_date->format($date_format);
							}
							$time_duration = get_post_meta(get_the_ID(),'u-course-dur', true );
							 ?>
                                <tr>
                                  <td><a href="<?php echo get_permalink(); ?>"><?php echo get_post_meta(get_the_ID(),'u-courseid', true ); ?></a></td>
                                  <td><a href="<?php echo get_permalink(); ?>"><?php the_title() ?></a></td>
                                  <td><?php echo $time_duration;?></td>
                                  <td><?php if($startdate){ echo date_i18n( get_option('date_format'), strtotime($startdate)); } ?></td>
                                </tr>
                            <?php endwhile; ?>
							
							<?php wp_reset_postdata(); ?>
                          </tbody>
                        </table>
						<?php endif; ?>
                        </div><!--/courses-list-->
                         <?php if(function_exists('wp_pagenavi')){
							wp_pagenavi();
						}else{
							cactusthemes_content_nav('paging');
						}?>
                    </div><!--/content-->
                    <?php get_sidebar(); ?>
                 </div><!--/row-->
            </div><!--/content-pad-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>