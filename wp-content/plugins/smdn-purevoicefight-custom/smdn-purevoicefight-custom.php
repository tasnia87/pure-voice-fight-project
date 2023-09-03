<?php
/**
 * Plugin Name:       Samadhan Pure Voice Fight Custom
 * Plugin URI:        http://samadhan.com.au
 * Description:       This plugin create WPML Course Reports.
 * Version:           1.0.0
 * Author:            Samadhan
 * Author URI:        http://samadhan.com.au
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/************* custom override Select2 **********/


add_filter('cmb_field_types', 'smdn_select2_dropdown_change', 99, 1);
function smdn_select2_dropdown_change($select)

{
    $screen = get_current_screen();
    if(in_array($screen->id, array('sfwd-courses')))
    {       return '';    }
    return $select;
}