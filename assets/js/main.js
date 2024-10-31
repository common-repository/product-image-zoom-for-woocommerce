(function($) {
	"use strict";
	// plugin options
	var settings = pizfwc_localize;

	// general	
	var zoom_type = settings.zoom_type ? settings.zoom_type : 'window';
	var zoom_window_width = settings.zoom_window_width ? parseInt(settings.zoom_window_width) : 400;
	var zoom_window_height = settings.zoom_window_height ? parseInt(settings.zoom_window_height) : 400;
	var zoom_window_offsetx = settings.zoom_window_offsetx ? parseInt(settings.zoom_window_offsetx) : 0;
	var zoom_window_offsety = settings.zoom_window_offsety ? parseInt(settings.zoom_window_offsety) : 0;
	var zoom_window_position = settings.zoom_window_position ? parseInt(settings.zoom_window_position) : 1;
	var cursor = settings.cursor ? settings.cursor : 'crosshair';
	var zoom_lens = settings.zoom_lens == 'true' ? true : false;
	var lens_shape = settings.lens_shape ? settings.lens_shape : 'round';
	var lens_size = settings.lens_size ? parseInt(settings.lens_size) : 250;
	var scroll_zoom = settings.scroll_zoom == 'true' ? true : false;
	var tint = settings.tint == 'true' ? true : false;
	var contain_lens_zoom = settings.contain_lens_zoom == 'true' ? true : false;

	// styling
	var border_size = settings.border_size ? parseInt(settings.border_size) : 1;
	var border_colour = settings.border_colour ? settings.border_colour : 'rgba(255,255,255,.5)';
	var lens_border = settings.lens_border ? parseInt(settings.border_size) : 10;
	var lens_colour = settings.lens_colour ? settings.lens_colour : '#fff';
	var lens_opacity = settings.lens_opacity ? parseFloat(settings.lens_opacity) : 0.3;
	var tint_colour = settings.tint_colour ? settings.tint_colour : '#fff';
	var tint_opacity = settings.tint_opacity ? parseFloat(settings.tint_opacity) : 0.2;

	// animation
	var easing = settings.easing == 'true' ? true : false;
	var lens_fade_in = settings.lens_fade_in ? parseInt(settings.lens_fade_in) : false;
	var lens_fade_out = settings.lens_fade_out ? parseInt(settings.lens_fade_out) : false;
	var zoom_window_fade_in = settings.zoom_window_fade_in ? parseInt(settings.zoom_window_fade_in) : false;
	var zoom_window_fade_out = settings.zoom_window_fade_out ? parseInt(settings.zoom_window_fade_out) : false;
	var zoom_tint_fade_in = settings.zoom_tint_fade_in ? parseInt(settings.zoom_tint_fade_in) : false;
	var zoom_tint_fade_out = settings.zoom_tint_fade_out ? parseInt(settings.zoom_tint_fade_out) : false;


	$('.wp-post-image').elevateZoom({
		zoomType: zoom_type,
		zoomWindowWidth: zoom_window_width,
		zoomWindowHeight: zoom_window_height,
		zoomWindowOffetx: zoom_window_offsetx,
		zoomWindowOffety: zoom_window_offsety,
		zoomWindowPosition: zoom_window_position,
		cursor: cursor,
		lensShape: lens_shape,
		lensSize: lens_size,
		scrollZoom: scroll_zoom,
		tint: tint,
		containLensZoom: contain_lens_zoom,

		// styling
		borderSize: border_size,
		borderColour: border_colour,
		lensBorder: lens_border,
		lensColour: lens_colour,
		lensOpacity: lens_opacity,
		tintColour: tint_colour,
		tintOpacity: tint_opacity,

		// animation
		easing: easing, 
		lensFadeIn: lens_fade_in,
		lensFadeOut: lens_fade_out,
		zoomWindowFadeIn: zoom_window_fade_in,
		zoomWindowFadeOut: zoom_window_fade_out,
		zoomTintFadeIn: zoom_tint_fade_in,
		zoomTintFadeOut: zoom_tint_fade_out,
	});

	$(".pizfwc-control-thumbs img").on('click', function(e){
		e.preventDefault();
		var large_src = $(this).attr('data-large-image');

		// change main image attributes
		$('.wp-post-image').attr('src', large_src );
		$('.wp-post-image').attr('srcset', large_src );
		$('.wp-post-image').attr('data-src', large_src );
		$('.wp-post-image').attr('data-large_image', large_src );
		$('.woocommerce-product-gallery__image a').attr('href', large_src);

		// change zoom image
		$('div.zoomWindowContainer div').css({'background-image': 'url('+ large_src +')'});

		// fix tint lens image
		$('.zoomLens img').attr('src', large_src );

		// fix lens zoom image after click on tab
		$('.pizfwc_zoom_type--lens div.zoomLens').css({'background-image': 'url('+ large_src +')'});

		// for image zoom
		$('.pswp__img').attr('src', large_src);
	});


	// variation image
	$(document).on('found_variation', 'form.variations_form', function (event, variation) {
		var large_src = variation.image.url;

		$('div.zoomWindowContainer div').css({'background-image': 'url('+ large_src +')'});
		$('.zoomLens img').attr('src', large_src );
		$('.pizfwc_zoom_type--lens div.zoomLens').css({'background-image': 'url('+ large_src +')'});
		$('.pswp__img').attr('src', large_src);
	}).on('reset_image', function (event) {
		$('.wp-post-image').elevateZoom({
			zoomType: zoom_type,
			zoomWindowWidth: zoom_window_width,
			zoomWindowHeight: zoom_window_height,
			zoomWindowOffetx: zoom_window_offsetx,
			zoomWindowOffety: zoom_window_offsety,
			zoomWindowPosition: zoom_window_position,
			cursor: cursor,
			lensShape: lens_shape,
			lensSize: lens_size,
			scrollZoom: scroll_zoom,
			tint: tint,
			containLensZoom: contain_lens_zoom,

			// styling
			borderSize: border_size,
			borderColour: border_colour,
			lensBorder: lens_border,
			lensColour: lens_colour,
			lensOpacity: lens_opacity,
			tintColour: tint_colour,
			tintOpacity: tint_opacity,

			// animation
			easing: easing, 
			lensFadeIn: lens_fade_in,
			lensFadeOut: lens_fade_out,
			zoomWindowFadeIn: zoom_window_fade_in,
			zoomWindowFadeOut: zoom_window_fade_out,
			zoomTintFadeIn: zoom_tint_fade_in,
			zoomTintFadeOut: zoom_tint_fade_out,
		});
	});
})(jQuery);