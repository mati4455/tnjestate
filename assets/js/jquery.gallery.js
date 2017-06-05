/**
 * Author: Mateusz Pacholec
 * E-mail: mateuszpacholec@gmail.com
 */

(function ($) {

    $.fn.gallery = function (u) {
		var gallery = $(this);
		var preview = gallery.find('.preview img');
		var loader = gallery.find('.loader');

		var first = gallery.find(".img:first");
		var last = gallery.find(".img:last");
		var current = first;

		var next_button = gallery.find('.nav .next');
		var prev_button = gallery.find('.nav .prev');

		var url = gallery.attr('data-url');

		var defualt = {
			animationTime    :   300,
			transitionTime   :   2000
		};

		var options = $.extend({}, defualt, u);

		if (first.index() == last.index()) {
			first.removeClass('.img');
			gallery.find('.nav').remove();
			return false;
		}

		var settings = function () {
			gallery.addClass('simple-gallery');
			first.addClass('active');
		};


		var nextImage = function (it) {
			if (current.index() === last.index()) {
				setCurrent(first);
			} else {
				if (!(it > 0)) it = 1;

				for (i=0; i<it; i++) {
					if (current.index() === last.index()) {
						setCurrent(first);
						break;
					}
					setCurrent($(current).next('.img'));
				}
			}
			setImage();
		};

		var prevImage = function (it) {
			if (current.index() === first.index()) {
				setCurrent(last);
			} else {
				if (!(it > 0)) it = 1;

				for (i=0; i<it; i++) {
					if (current.index() === first.index()) {
						setCurrent(last);
						break;
					}
					setCurrent(current.prev('.img'));
				}
			}
			setImage();
		};

		var setCurrent = function (elem) {
			if (current.index() == $(elem).index()) return ;

			current.removeClass('active');
			current = elem;
			current.addClass('active');

		};

		var setImage = function() {
			var href = current.children('a').attr('data-url');
			var img = current.children('img');
			var time = options.animationTime / 2;

			loader.fadeIn(100);
			var ajax_load_url = url + 'max/' + href;
			$.ajax({
				url: ajax_load_url,
				success: function(){
					var load = new Image(); load.src = ajax_load_url;
					preview.attr('src', ajax_load_url);
					loader.stop().fadeOut(200);
			    }
			});
		};

		gallery.find('.img').on('click', function(e) {
			e.preventDefault();
			setCurrent($(this));
			setImage();
			$('html, body').animate({
		        scrollTop: preview.offset().top - 50
		    }, 200);
		}).on('mouseenter', function() {
			$(this).addClass('hover');
		}).on('mouseleave', function() {
			$(this).removeClass('hover');
		});

		prev_button.on('click', function(e) {
			e.preventDefault();
			prevImage();
		});

		next_button.on('click', function(e) {
			e.preventDefault();
			nextImage();
		});

		$(document).keydown(function(e) {
			 switch(e.keyCode) {
				case 37: // left
				prevImage();
				break;

				case 39: // right
				nextImage();
				break;

				case 38: // down
				prevImage(3);
				break;

				case 40: // up
				nextImage(3);
				break;

				default: return;
			 }
			 e.preventDefault();
		});

		preview.touchwipe({
			 wipeLeft: function() { nextImage(); },
		    wipeRight: function() { prevImage(); },
		    min_move_x: 50,
		    min_move_y: 50,
		    preventDefaultEvents: false
		});

		settings();
	};
})(jQuery);
