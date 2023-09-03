// JavaScript Document
(function() {
    tinymce.PluginManager.add('shortcode_post_grid', function(editor, url) {
		editor.addButton('shortcode_post_grid', {
			text: '',
			tooltip: 'Post Grid',
			id: 'shortcode_post_grid',
			icon: 'icon-post-grid',
			onclick: function() {
				// Open window
				editor.windowManager.open({
					title: 'Post Grid',
					body: [
						{type: 'textbox', name: 'title', label: 'Title'},
						{type: 'textbox', name: 'description', label: 'Description'},
						{type: 'textbox', name: 'link_text', label: 'Link text (ex: All events)'},
						{type: 'textbox', name: 'link_url', label: 'Link URL'},
						{type: 'listbox', 
							name: 'show_header', 
							label: 'Show Grid Header?', 
							'values': [
								{text: 'Yes', value: '1'},
								{text: 'No', value: '0'}
							]
						},
						{type: 'textbox', name: 'header_background', label: 'Grid Header Background', value:"#", id: 'newcolorpicker_header_backgroundbg'},
						{type: 'textbox', name: 'column', label: 'Grid Columns Number'},
						{type: 'listbox', 
							name: 'post_type', 
							label: 'Post Type', 
							'values': [
								{text: 'Post', value: 'post'},
								{text: 'Event', value: 'u_event'},
								{text: 'Course', value: 'u_course'},
								{text: 'Attachment', value: 'attachment'},
								{text: 'LearnDash Course', value: 'sfwd-courses'}
							]
						},
						{type: 'textbox', name: 'cat', label: 'Category (List of cat ID or slug)'},
						{type: 'textbox', name: 'tag', label: 'Tags (List of tags, separated by a comma)'},
						{type: 'textbox', name: 'ids', label: 'Ids (Specify post IDs to retrieve)'},
						{type: 'textbox', name: 'number', label: 'Number of posts to show.'},
						{type: 'listbox', 
							name: 'order', 
							label: 'Order', 
							'values': [
								{text: 'DESC', value: 'DESC'},
								{text: 'ASC', value: 'ASC'}
							]
						},
						{type: 'listbox', 
							name: 'orderby', 
							label: 'Order by', 
							'values': [
								{text: 'Date', value: 'date'},
								{text: 'ID', value: 'ID'},
								{text: 'Author', value: 'author'},
								{text: 'Title', value: 'title'},
								{text: 'Name', value: 'name'},
								{text: 'Modified', value: 'modified'},
								{text: 'Parent', value: 'parent'},
								{text: 'Random', value: 'rand'},
								{text: 'Comment count', value: 'comment_count'},
								{text: 'Menu order', value: 'menu_order'},
								{text: 'Meta value', value: 'meta_value'},
								{text: 'Meta value num', value: 'meta_value_num'},
								{text: 'Post__in', value: 'post__in'},
								{text: 'None', value: 'none'}
							]
						},
						{type: 'textbox', name: 'meta_key', label: 'Meta key (Name of meta key for ordering)'},
						{type: 'listbox', 
							name: 'show_date', 
							label: 'Show date', 
							'values': [
								{text: 'Show', value: '1'},
								{text: 'Hide', value: '0'}
							]
						},
						{type: 'listbox', 
							name: 'show_price', 
							label: 'Show price', 
							'values': [
								{text: 'Show', value: '1'},
								{text: 'Hide', value: '0'}
							]
						},
						{type: 'listbox', 
							name: 'show_venue', 
							label: 'Show venue', 
							'values': [
								{text: 'Show', value: '1'},
								{text: 'Hide', value: '0'}
							]
						},
						{type: 'listbox', 
							name: 'show_time', 
							label: 'Show time', 
							'values': [
								{text: 'Show', value: '1'},
								{text: 'Hide', value: '0'}
							]
						},
						{type: 'listbox', 
							name: 'gallery', 
							label: 'Enable Gallery Popup', 
							'values': [
								{text: 'No', value: '0'},
								{text: 'Yes', value: '1'}
							]
						},
						{type: 'listbox', 
							name: 'css_animation', 
							label: 'CSS Animation', 
							'values': [
								{text: 'No', value: ''},
								{text: 'Top to bottom', value: 'top-to-bottom'},
								{text: 'Bottom to top', value: 'bottom-to-top'},
								{text: 'Left to right', value: 'left-to-right'},
								{text: 'Right to left', value: 'right-to-left'},
								{text: 'Appear from center', value: 'appear'}
							]
						},
						{type: 'textbox', name: 'animation_delay', label: 'Animation Delay'},
					],
					onsubmit: function(e) {
						var uID =  Math.floor((Math.random()*100)+1);
						editor.insertContent('[u_post_grid title="'+e.data.title+'" description="'+e.data.description+'" link_text="'+e.data.link_text+'" link_url="'+e.data.link_url+'" show_header="'+e.data.show_header+'" header_background="'+e.data.header_background+'" column="'+e.data.column+'" post_type="'+e.data.post_type+'" cat="'+e.data.cat+'" tag="'+e.data.tag+'" ids="'+e.data.ids+'" count="'+e.data.number+'" order="'+e.data.order+'" orderby="'+e.data.orderby+'" meta_key="'+e.data.meta_key+'" show_date="'+e.data.show_date+'" show_price="'+e.data.show_price+'" show_venue="'+e.data.show_venue+'" show_time="'+e.data.show_time+'" gallery="'+e.data.gallery+'" css_animation="'+e.data.css_animation+'" animation_delay="'+e.data.animation_delay+'"]<br class="nc"/>');
					}
				});
			}
		});
	});
})();
