if (typeof flip_expand_collapse == 'undefined') {
	function flip_expand_collapse(what, id) {
	    //console.log(id + ':' + document.getElementById( 'list_arrow.flippable-'+id).className);
	    if (jQuery( what + '-' + id + ' .list_arrow.flippable' ).hasClass( 'expand' ) ) {
	        jQuery( what + '-' + id + ' .list_arrow.flippable' ).removeClass( 'expand' );
	        jQuery( what + '-' + id + ' .list_arrow.flippable' ).addClass( 'collapse' );
	        jQuery( what + '-' + id + ' .flip' ).slideUp();
	    } else {
	        jQuery( what + '-' + id + ' .list_arrow.flippable' ).removeClass( 'collapse' );
	        jQuery( what + '-' + id + ' .list_arrow.flippable' ).addClass( 'expand' );
	        jQuery( what + '-' + id + ' .flip' ).slideDown();
	    }
	    return false;
	}
}

if (typeof flip_expand_all == 'undefined') {
	function flip_expand_all(what) {
	    jQuery( what + ' .list_arrow.flippable' ).removeClass( 'collapse' );
	    jQuery( what + ' .list_arrow.flippable' ).addClass( 'expand' );
	    jQuery( what + ' .flip' ).slideDown();
	    return false;
	}
}

if (typeof flip_collapse_all == 'undefined') {
	function flip_collapse_all(what) {
	    jQuery( what + ' .list_arrow.flippable' ).removeClass( 'expand' );
	    jQuery( what + ' .list_arrow.flippable' ).addClass( 'collapse' );
	    jQuery( what + ' .flip' ).slideUp();
	    return false;
	}
}

String.prototype.toHHMMSS = function() {
    sec_numb = parseInt( this, 10 );
    var hours = Math.floor( sec_numb / 3600 );
    var minutes = Math.floor( ( sec_numb - ( hours * 3600 ) ) / 60 );
    var seconds = sec_numb - ( hours * 3600 ) - ( minutes * 60 );
    if ( hours < 10 ) {
        hours = '0' + hours;
    }
    if ( minutes < 10 ) {
        minutes = '0' + minutes;
    }
    if ( seconds < 10 ) {
        seconds = '0' + seconds;
    }
    var time = hours + ':' + minutes + ':' + seconds;
    return time;
}

function learndash_timer() {
    document.getElementById( 'learndash_mark_complete_button' ).disabled = true;
    learndash_forced_lesson_time = learndash_forced_lesson_time - 1;
    document.getElementById( 'learndash_timer' ).innerHTML = learndash_forced_lesson_time.toString().toHHMMSS();
    if ( learndash_forced_lesson_time <= 0 ) {
        clearInterval( learndash_timer_var );
        document.getElementById( 'learndash_mark_complete_button' ).disabled = false;
        document.getElementById( 'learndash_timer' ).innerHTML = '';
    }
}

jQuery( function() {
    function force_max_12px_font_size() {
        var f1 = jQuery( '#course_navigation .learndash_navigation_lesson_topics_list a' ).css( 'font-size' );
        var f2 = jQuery( '#course_navigation .learndash_navigation_lesson_topics_list a span' ).css( 'font-size' );
        if ( f1 != undefined && f1.replace( 'px', '' ) > 12 || f2 != undefined && f2.replace( 'px', '' ) > 12 ) {
            jQuery( '#course_navigation .learndash_navigation_lesson_topics_list a, #course_navigation .learndash_navigation_lesson_topics_list a span' ).css( 'font-size', 12 );
        }
    }
    force_max_12px_font_size();
});

jQuery(document).ready(function() {
	
	if ( jQuery( '#learndash_timer' ).length ) {
		var timer_el 		= jQuery( '#learndash_timer' );
		var timer_seconds 	= timer_el.attr('data-timer-seconds');

		var timer_button_el = jQuery( timer_el.attr('data-button') );

		if ( ( typeof timer_seconds !== 'undefined' ) && ( typeof timer_button_el !== 'undefined' ) ) {

	        timer_button_el.attr('disabled', true);
			
			timer_seconds = parseInt( timer_seconds );


			var cookie_key 		= timer_el.attr('data-cookie-key');
			if (typeof cookie_key !== 'undefined') {
				var cookie_name = 'learndash_timer_cookie_'+cookie_key;
			} else {
				var cookie_name = 'learndash_timer_cookie';
			}
			cookie_timer_seconds = jQuery.cookie(cookie_name);
			if (typeof cookie_timer_seconds !== 'undefined') {
				timer_seconds = parseInt( cookie_timer_seconds );
			}
			jQuery.removeCookie( cookie_name );
			
			if ( timer_seconds >= 1 ) {
				var learndash_timer_var = setInterval( function() {
				    timer_seconds = timer_seconds - 1;
				
					var time_display = timer_seconds.toString().toHHMMSS();
				    timer_el.html(time_display);
				    if ( timer_seconds <= 0 ) {
						
				        clearInterval( learndash_timer_var );
				        timer_button_el.attr('disabled', false);
				        timer_el.html('');
						jQuery.cookie(cookie_name, 0);
						//jQuery.removeCookie( cookie_name );
						
						timer_button_el.trigger('learndash-time-finished');
				    }
					// Store the timer state (value) into a cookie. This is done if the page reloads the student can resume
					// the time instead of restarting.
					jQuery.cookie(cookie_name, timer_seconds);
				},1000);	
			} else {
		        timer_button_el.attr('disabled', false);
		        timer_el.html('');
				jQuery.cookie(cookie_name, 0);
				//jQuery.removeCookie( cookie_name );
			}
		}
	}
});


jQuery(document).ready(function(){

	if (typeof sfwd_data !== 'undefined') {
		if ( typeof sfwd_data.json !== 'undefined' ) {
			sfwd_data = sfwd_data.json.replace(/&quot;/g, '"');
			sfwd_data = jQuery.parseJSON( sfwd_data );
		}
	}

	// load essay in the modal
	jQuery(document).on('click','.wpProQuiz_sortable a', function(e) {
		var _this = jQuery(this);
		var addressValue = jQuery(this).attr("href");
		var substring = 'essay';

		if(addressValue.indexOf(substring) !== -1){
			e.preventDefault();

			var dataVars = {
				essayUrl: addressValue,
				userId: userId
			};
			console.log(dataVars);
			// Rest api call to activate/deactivate plugins
			jQuery.ajax({

				method: "POST",
				data: dataVars,
				url: groupLeaderCourseReportSetup.root + 'get_user_essay/',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', groupLeaderCourseReportSetup.nonce);
				},
				success: function (response) {

					var essay = '<div style="background:white;padding:10px;">'+response+'</div>';
					_this.parent().append(essay);
					_this.hide();

				},
				fail: function (response) {
					alert('Rest Call Failed');
					console.log(response);
				}

			});

		}

	});

    var userId 				= 	jQuery(this).data('user_id');

	jQuery(document).on('click','a.user_statistic', function(e) {
		e.preventDefault();
		
		var refId 				= 	jQuery(this).data('ref_id');
		var quizId 				= 	jQuery(this).data('quiz_id');
		userId 				= 	jQuery(this).data('user_id');
		var statistic_nonce 	= 	jQuery(this).data('statistic_nonce');
		var post_data = {
			'action': 'wp_pro_quiz_admin_ajax',
			'func': 'statisticLoadUser',
			'data': {
				'quizId': quizId,
            	'userId': userId,
            	'refId': refId,
				'statistic_nonce': statistic_nonce,
            	'avg': 0
			}
		}
		
		jQuery('#wpProQuiz_user_overlay, #wpProQuiz_loadUserData').show();
		var content = jQuery('#wpProQuiz_user_content').hide();

		jQuery.ajax({
			type: "POST",
			url: sfwd_data.ajaxurl,
			dataType: "json",
			cache: false,
			data: post_data,
			error: function(jqXHR, textStatus, errorThrown ) {
			},
			success: function(reply_data) {

				if ( typeof reply_data.html !== 'undefined' ) {
					content.html(reply_data.html);
					jQuery('a.wpProQuiz_update', content).remove();
					jQuery('a#wpProQuiz_resetUserStatistic', content).remove();
					
					
					jQuery('#wpProQuiz_user_content').show();

					jQuery('#wpProQuiz_loadUserData').hide();
				
					content.find('.statistic_data').click(function() {
						jQuery(this).parents('tr').next().toggle('fast');
			
						return false;
					});
				}
			}
		});
				
		jQuery('#wpProQuiz_overlay_close').click(function() {
			jQuery('#wpProQuiz_user_overlay').hide();
		});
	});
	
});
