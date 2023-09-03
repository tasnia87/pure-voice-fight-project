<?php
function parse_calendar_func($atts, $content){
		$post_type 		=isset($atts['post_type']) ? $atts['post_type'] : 'u_event';
		$cat 		=isset($atts['cat']) ? $atts['cat'] : '';
		$tag 	= isset($atts['tag']) ? $atts['tag'] : '';
		$exclude 	= isset($atts['exclude']) ? $atts['exclude'] : '';
		$ignore 	= isset($atts['ignore']) ? $atts['ignore'] : '';
		$month 		=isset($atts['month']) ? $atts['month'] : '';
		$years 	= isset($atts['year']) ? $atts['year'] : '';
		$date_sttp ='';
		if($month!=''){
			if($years!=''){
				$date_sttp = $date = date( $years.'-'.$month.'-d');
			}else{
				$date_sttp = $date = date( 'Y-'.$month.'-d');
			}
			$mon_c1 = (int)date("m");
			if($mon_c1 < $month){
				if($ignore ==''){
					$date_sttp = $date = date( 'Y-'.$month.'-01');
				}
			}
		}else{
			$date_sttp = 'none';
			$date = date('Y-m-d');
		}
		
	wp_reset_postdata();
	//$data_rs = str_replace('\/', '/', json_encode($data_rs));
	wp_enqueue_style('style-calendar', U_SHORTCODE_PLUGIN_URL . 'shortcodes/css/style-calendar.css');
	wp_enqueue_style('owl-carousel', U_SHORTCODE_PLUGIN_URL . 'shortcodes/calendar-js/owl-carousel/owl.carousel.css');
	wp_enqueue_style('owl-carousel-theme', U_SHORTCODE_PLUGIN_URL . 'shortcodes/calendar-js/owl-carousel/owl.theme.css');
	wp_enqueue_style('owl-carousel-transitions', U_SHORTCODE_PLUGIN_URL . 'shortcodes/calendar-js/owl-carousel/owl.transitions.css');
	wp_enqueue_script( 'calendar', U_SHORTCODE_PLUGIN_URL . 'shortcodes/calendar-js/calendar.js' , array(), '', true );
	wp_enqueue_script( 'format-datetime', U_SHORTCODE_PLUGIN_URL . 'shortcodes/calendar-js/format-datetime-master/jquery.formatDateTime.js' , array(), '', true );
	
	$calendar_params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
	$calendar_params['m0']  = date_i18n( 'F', strtotime( 'January' )); 
	$calendar_params['m1']  = date_i18n( 'F', strtotime( 'February' ));
	$calendar_params['m2']  = date_i18n( 'F', strtotime( 'March' ));
	$calendar_params['m3']  = date_i18n( 'F', strtotime( 'April' ));
	$calendar_params['m4']  = date_i18n( 'F', strtotime( 'May' ));
	$calendar_params['m5']  = date_i18n( 'F', strtotime( 'June' ));
	$calendar_params['m6']  = date_i18n( 'F', strtotime( 'July' ));
	$calendar_params['m7']  = date_i18n( 'F', strtotime( 'August' ));
	$calendar_params['m8']  = date_i18n( 'F', strtotime( 'September' ));
	$calendar_params['m9']  = date_i18n( 'F', strtotime( 'October' ));
	$calendar_params['m10'] = date_i18n( 'F', strtotime( 'November' ));
	$calendar_params['m11'] = date_i18n( 'F', strtotime( 'December' ));
	
	$calendar_params['ms0']  = date_i18n( 'M', strtotime( 'January' )); 
	$calendar_params['ms1']  = date_i18n( 'M', strtotime( 'February' ));
	$calendar_params['ms2']  = date_i18n( 'M', strtotime( 'March' ));
	$calendar_params['ms3']  = date_i18n( 'M', strtotime( 'April' ));
	$calendar_params['ms4']  = date_i18n( 'M', strtotime( 'May' ));
	$calendar_params['ms5']  = date_i18n( 'M', strtotime( 'June' ));
	$calendar_params['ms6']  = date_i18n( 'M', strtotime( 'July' ));
	$calendar_params['ms7']  = date_i18n( 'M', strtotime( 'August' ));
	$calendar_params['ms8']  = date_i18n( 'M', strtotime( 'September' ));
	$calendar_params['ms9']  = date_i18n( 'M', strtotime( 'October' ));
	$calendar_params['ms10'] = date_i18n( 'M', strtotime( 'November' ));
	$calendar_params['ms11'] = date_i18n( 'M', strtotime( 'December' ));
	
	$calendar_params['d0'] = date_i18n( 'D', strtotime( 'Sun' )); 
	$calendar_params['d1'] = date_i18n( 'D', strtotime( 'Mon' ));
	$calendar_params['d2'] = date_i18n( 'D', strtotime( 'Tue' ));
	$calendar_params['d3'] = date_i18n( 'D', strtotime( 'Wed' ));
	$calendar_params['d4'] = date_i18n( 'D', strtotime( 'Thursday' ));
	$calendar_params['d5'] = date_i18n( 'D', strtotime( 'Fri' ));
	$calendar_params['d6'] = date_i18n( 'D', strtotime( 'Sat' ));
	
	$calendar_params['buy_text'] = __('BUY TICKET ','cactusthemes');
	$calendar_params['st_text'] = __('Start: ','cactusthemes');
	$calendar_params['en_text'] = __('End: ','cactusthemes');
	$calendar_params['loca_text'] = __('Location: ','cactusthemes');
	wp_localize_script( 'calendar', 'calendar_date_trans', $calendar_params  );
	wp_localize_script( 'format-datetime', 'calendar_date_trans', $calendar_params  );

    ob_start();
	?>

          <div id="calendar-box" class="<?php if($post_type=='u_course'){echo ' cactus-calendar-course ';}else{ echo ' cactus-calendar-event ';} ?>"> 
          <style type="text/css">
		  #main-nav .main-menu.affix{ z-index:9999}
		  </style>          	  
              <div class="header-content">
                  <button class="btn btn-primary" data-calendar-nav="prev"><i class="fa fa-chevron-left"></i></button>
                  <h3></h3>          
                  <button class="btn btn-primary" data-calendar-nav="next"><i class="fa fa-chevron-right"></i></button>
              </div>
              <div id="stm-calendar-id"></div>
              <input type="hidden" id="check-id-carousel" value="-1"> <!--Giá trị ban đầu bằng -1 đích đến khi chọn event -> sẽ tới slide carousel-->
              <input type="hidden" id="check-event-slidedown" value="0"> <!--Giá trị ban đầu bằng 0 / bằng 0 cho mở carousel / 1 tắt-->
              <input type="hidden" id="check-event-slidedown-1" value="0"> <!--Giá trị ban đầu bằng 0 / ngày tháng khi click mở carousel-->
              <input type="hidden" id="check-monthdata" value="<?php echo $date;?>">
			  <input type="hidden" id="month-url" value="<?php echo plugins_url('/monthview/', __FILE__); ?>"> 	
              <input type="hidden" id="check-jsondata" value="<?php echo admin_url( 'admin-ajax.php' )?>">
              <input type="hidden" id="action_data" value="1">
              <input type="hidden" id="action_post_type" value="<?php echo $post_type;?>">
              <input type="hidden" id="action_cat" value="<?php echo $cat;?>">
              <input type="hidden" id="action_tag" value="<?php echo $tag;?>">
              <input type="hidden" id="action_exclude" value="<?php echo $exclude;?>">
              <input type="hidden" id="action_ignore" value="<?php echo $ignore;?>">
              <input type="hidden" id="action_dt_stt" value="<?php echo $date_sttp;?>">
              <?php
			  $cr_language ='';
			  if (class_exists('SitePress')) {
				  global $wpdb,$sitepress;
				  $cr_language = $sitepress->get_current_language();
			  }?>
              <input type="hidden" id="action_language" value="<?php echo $cr_language;?>">
              <div id="calendar-loading">
              	<div class="windows8">
                    <div class="wBall" id="wBall_1">
                        <div class="wInnerBall"></div>
                    </div>
                    <div class="wBall" id="wBall_2">
                        <div class="wInnerBall"></div>
                    </div>
                    <div class="wBall" id="wBall_3">
                        <div class="wInnerBall"></div>
                    </div>
                    <div class="wBall" id="wBall_4">
                        <div class="wInnerBall"></div>
                    </div>
                    <div class="wBall" id="wBall_5">
                        <div class="wInnerBall"></div>
                    </div>
                </div>
              </div>
          </div> 
      	<!--Khung chứa bảng ngày tháng-->
        
        <!--Thanh điều hướng, có thể bỏ đi mà ko gây lỗi cho các khung trên-->        
          <div id="calendar-options">
              <div class="left-options">
                <a href="javascript:;" class="active" id="monthview-calendar"><?php echo __('Calendar View','cactusthemes'); ?></a> &nbsp; | &nbsp; <a href="javascript:;" id="listview-calendar"><?php echo __('List View','cactusthemes'); ?></a>
              </div>
              <div class="right-options" style="display:none">
                <ul>
                    <li><a href="javascript:;">All Speakers <i class="fa fa-angle-down"></i></a> &nbsp; &nbsp;| 
                        <ul>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                        </ul>
                    </li>
                    <li> &nbsp; &nbsp; <a href="javascript:;">All Location <i class="fa fa-angle-down"></i></a>
                        <ul>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                            <li><a href="javascript:;">Test test test test</a></li>
                        </ul>
                    </li>
                </ul>
              </div>
          </div>
      	<!--Thanh điều hướng-->
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}
add_shortcode( 'u-calendar', 'parse_calendar_func' );
add_action( 'after_setup_theme', 'reg_u_calendar' );
function reg_u_calendar(){
	if(function_exists('vc_map')){
		$current_year = date("Y");
		$years = array();
		for($i=-11; $i<=10; $i++){
			if($i==-11){
				$years[''] = '';
			}else{
				$years[$current_year+$i] = ($current_year+$i);
			}
		}
		$month = array();
		for($i=0; $i<=12; $i++){
			if($i==0){
				$month[''] = '';
			}else{
				if($i<10){
					$month['0'.$i] = ('0'.$i);
				}else{
					$month[$i] = ($i);
				}
			}
		}
		vc_map( array(
		   "name" => __("Calendar",'cactusthemes'),
		   "base" => "u-calendar",
		   "class" => "",
		   "controls" => "full",
		   "category" => 'Content',
		   "icon" => "icon-calendar",
		   "params" => array(
				array(
				 "type" => "dropdown",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Post Type", "cactusthemes"),
				 "param_name" => "post_type",
				 "value" => array(
					__('Event', 'cactusthemes') => 'u_event',
					__('Course', 'cactusthemes') => 'u_course',
				 ),
				 "description" => __('Choose post type','cactusthemes')
				),
			   array(
				  "type" => "dropdown",
				  "holder" => "div",
				  "heading" => __("Month", "cactusthemes"),
				  "param_name" => "month",
				  "value" => $month,
				  "description" => ""
			  ),
			  array(
				  "type" => "dropdown",
				  "holder" => "div",
				  "heading" => __("Year", "cactusthemes"),
				  "param_name" => "year",
				  "value" => $years,
				  "description" => ""
			  ),
			  array(
				"type" => "textfield",
				"heading" => __("Categories", "cactusthemes"),
				"param_name" => "cat",
				"value" => "",
				"description" => __("", "cactusthemes"),
			  ),
			  array(
				"type" => "textfield",
				"heading" => __("Tags", "cactusthemes"),
				"param_name" => "tag",
				"value" => "",
				"description" => __("", "cactusthemes"),
			  ),
			  array(
				"type" => "textfield",
				"heading" => __("Exclude", "cactusthemes"),
				"param_name" => "exclude",
				"value" => "",
				"description" => __("", "cactusthemes"),
			  ),
			  array(
				 "type" => "dropdown",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Ignore", 'cactusthemes'),
				 "param_name" => "ignore",
				 "value" => array(
				 	__('', 'cactusthemes') => '',
					__('Upcoming', 'cactusthemes') => 'upcoming',
					__('Recent', 'cactusthemes') => 'recent',
				 ),
				 "description" => __('','cactusthemes')
			  ),
		   )
		) );
	}
}