var stan = false;


(function ($) {
	 $.fn.serialize = function (options) {
		 return $.param(this.serializeArray(options));
	 };

	 $.fn.serializeArray = function (options) {
		 var o = $.extend({
		 checkboxesAsBools: false
	 }, options || {});

	 var rselectTextarea = /select|textarea/i;
	 var rinput = /text|hidden|password|search/i;

	 return this.map(function () {
		 return this.elements ? $.makeArray(this.elements) : this;
	 })
	 .filter(function () {
		 return this.name && !this.disabled &&
			 (this.checked
			 || (o.checkboxesAsBools && this.type === 'checkbox')
			 || rselectTextarea.test(this.nodeName)
			 || rinput.test(this.type));
		 })
		 .map(function (i, elem) {
			 var val = jQuery(this).val();
			 return val == null ?
			 null :
			 $.isArray(val) ?
			 $.map(val, function (val, i) {
				 return { name: elem.name, value: val };
			 }) :
			 {
				 name: elem.name,
				 value: (o.checkboxesAsBools && this.type === 'checkbox') ? //moar ternaries!
						(this.checked ? '1' : '0') :
						val
			 };
		 }).get();
	 };
})(jQuery);

(function($, viewport){

	var stan = viewport.is('xs');

	if ( stan ) {
		ustawieniaMobilne();
    }

    jQuery(window).bind('resize', function() {
		fixImages();

        viewport.changed(function(){

        	var mobile = viewport.is('xs');
            if (mobile  && !stan) {
            	stan = true;
            	ustawieniaMobilne();
            } else if (!mobile && stan) {
            	stan = false;
            	ustawieniaDesktop();
            }
        });
    });

})(jQuery, ResponsiveBootstrapToolkit);


function setupLogo() {
	var item = jQuery('#header .logo_link');
	if (item.length > 0) {
		if (jQuery(document).width() >= 1366) {
			item.addClass('pull-to-left');
		} else {
			item.removeClass('pull-to-left');
		}
	}
}

function fixImages() {
	jQuery('.c16by9').each(function() {
		jQuery(this).css('height', ( (9/16) * jQuery(this).width() ) + 'px');
	});
}

function setElements() {
	var width = jQuery(document).width();
	if (width > 767) {

	} else {


	}
}

function updateWidth() {
	jQuery('.affix').each(function() {
       	var tmp = jQuery(this);
       	jQuery(this).css('width', tmp.parent().width() + 'px');
    });
}

function ustawieniaMobilne() {
    jQuery(".search-move").insertBefore('.search-align');
    jQuery('.search-box').addClass('collapse');
}

function ustawieniaDesktop() {
	jQuery(".search-move").insertAfter('.search-align');
    jQuery('.search-box').removeClass('collapse').css('height', 'auto');
}

/* komunikat pop-up prawy, dolny róg */
function kom(tekst) {
	jQuery('#info').animate({right: "0px"}, 300).html(tekst).delay(2000).animate({right: "-600px"}, 300);
}

function komunikat(tekst) {
	kom(tekst);
	window.setTimeout(function(){location.reload()},2600);
}

function stripQuery(url) {
	return url.split("?")[0].split("#")[0];
}

function showMessage(div, message) {
	div.fadeIn(200).html(message);
	setTimeout(function() {
		div.fadeOut(200);
	}, 5000);
}

function setTab(hash) {
	jQuery('a[href="' + hash + '"]').trigger('click');
}


jQuery(document).ready(function() {

	jQuery('[placeholder]').focus(function() {
	  var input = jQuery(this);
	  if (input.val() == input.attr('placeholder')) {
	    input.val('');
	    input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = jQuery(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
	    input.addClass('placeholder');
	    input.val(input.attr('placeholder'));
	  }
	}).blur().parents('form').submit(function() {
	  jQuery(this).find('[placeholder]').each(function() {
	    var input = jQuery(this);
	    if (input.val() == input.attr('placeholder')) {
	      input.val('');
	    }
	  })
	});

	/* opoznione ladowanie zdjec */
	jQuery('.lazy').attr('src', '/assets/img/loader_min.gif').addClass('loading');
	jQuery('.lazy').Lazy({
		afterLoad: function(element) {
			element.removeClass("loading");
		},
		enableThrottle: true,
		throttle: 250,
		effect: "fadeIn",
		effectTime: 500
	});

	jQuery('select').change(function() {
		if (jQuery(this).children('option:first-child').is(':selected')) {
	 		jQuery(this).addClass('placeholder');
		} else {
			jQuery(this).removeClass('placeholder');
		}
	});

	jQuery('select').change();

	jQuery('.gallery').gallery();

	jQuery('.affix').each(function() {
		jQuery(this).removeClass('affix');
		var tmp = jQuery(this).offset();
		jQuery(this).addClass('affix');
		var outter = jQuery('.footer').outerHeight(true) + jQuery('.bottom-line').outerHeight(true) + jQuery('.bottom-line + div').outerHeight(true) + 10;
		 
		jQuery(this).affix({
		  offset: {
		    top: tmp.top - 20,
		    bottom: function () {
		      return (this.bottom = outter);
		    }
		  }
		});
	});


	if (jQuery('.home-box').length > 0) {
		jQuery('.footer_resizer').addClass('col-lg-11 col-lg-offset-1 home_state');
	}

	jQuery(document).on('click', '#cookies_accept', function(e) {
		e.preventDefault();
		var button = jQuery(this);
		$.ajax({
			url: button.attr('href'),
			datatype: 'json',
			success: function(result){
				if (result.status) {
					jQuery('#cookies').fadeOut(400);
				}
		    }
		});
	});

	jQuery('#search-form').submit(function(e) {
		var r = jQuery('#rodzaj');
		var t = jQuery('#typ');

		var url = jQuery(this).attr('action');

		jQuery("form#search-form :input").each(function(){
			var input = jQuery(this);
			if (input.attr('name') != undefined) {
				url += input.val() + '-';
				input.prop('disabled', true);
			}
		});
		r.prop('disabled', true);
		t.prop('disabled', true);
		jQuery(this).attr('action', url.slice(0,-1));

	});

	jQuery('a, .dynamic-hover').mouseenter(function() {
		jQuery(this).stop().attr('style', '').addClass('hover', 200);
	}).mouseleave(function() {
		jQuery(this).stop().attr('style', '').removeClass('hover', 200);
	});

	jQuery(document).on('click', '.confirm', function(e) {
		if (!confirm(jQuery(this).attr('data-confirm'))) {
			e.preventDefault();
		}
	});

	jQuery(document).on('click', '.answer', function() {
		location.reload();
	});

	jQuery(document).on('change', '#menuSort', function() {
		var q = jQuery(this).attr('data-query');
		var s = jQuery(this).find(":selected").val();
		var url = q + 'sort=' + s;
		window.location = url;
	});

	jQuery(document).on('click', 'button.ajax', function(e) {
		var button = jQuery(this);
		var row = button.parent().parent();
		var url = jQuery(this).attr('href');

		$.ajax({
			url: url,
			type: 'post',
			datatype: 'json',
			success: function(result) {
				if (result.status == true) {
					row.fadeOut(200, function() { jQuery(this).remove() });
				}
		    }
		});
	})

	if (window.location.hash != "") {
		setTab(window.location.hash);
	}

	jQuery(window).resize();
});


jQuery(window).resize(function() {
	setupLogo();
});

