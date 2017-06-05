jQuery.fn.a = function(test) {
	if (test == 1)
		$(this).animate({right: "0px"}, 400);
	else
		$(this).animate({right: "-600px"}, 400);
};
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
			 var val = $(this).val();
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


$(document).ready(function() {

	$(document).on('click', '#addText', function() {
		var lp = $('#text_form tr').length + 1;
		var html = '<tr><td><input type="hidden" name="dane['+lp+'][id]" value="0" />'
		+ '<input type="text" name="dane['+lp+'][short]" /></td>' 
		+ '<td><input type="text" name="dane['+lp+'][pl]" /></td>'
		+ '<td><input type="text" name="dane['+lp+'][en]" /></td></tr>';
		$('#text_form').append(html);
	});

	$('.lazy').attr('src', '/assets/img/loader_min.gif').addClass('loading');
	/* opoznione ladowanie zdjec */
	$('.lazy').Lazy({
		afterLoad: function(element) { 
			element.removeClass("loading");
		},
		enableThrottle: true,
		throttle: 250,
		effect: "fadeIn",
		effectTime: 500
	});

	$("#img_upload").dropzone({
		maxFilesize: 3,
		dictDefaultMessage: "Przeciągnij i upuść zdjęcia tutaj lub kliknij, aby wybrać je ręcznie",
		init: function () {
			var totalFiles = 0, completeFiles = 0;

			this.on("addedfile", function (file) {
				totalFiles += 1;
			});
			this.on("removed file", function (file) {
				totalFiles -= 1;
			});
			this.on("complete", function (file) {
				completeFiles += 1;
				if (completeFiles === totalFiles) {
					location.reload();               
				}
			});
		}
	});

	/* komunikat pop-up prawy, dolny róg */
	function kom(tekst) {
		$('#info').animate({right: "0px"}, 300).html(tekst).delay(2000).animate({right: "-600px"}, 300);
	}
	function komunikat(tekst) {
		kom(tekst);
		window.setTimeout(function(){location.reload()},2600);
	}
	function ladowanie() {
		var el = $('#progress');
		if (el.css('display') == 'block')
			el.delay(200).fadeOut(200);
		else
			el.fadeIn(200);
	}

	if ($('#accordion').length > 0) {
		var anchor = window.location.hash.replace("#", "");
		if (anchor.length > 0) {
		    $(".in").removeClass('in');
		    $(".collapse").collapse('hide');
		    $("#" + anchor).collapse('show');	
		    $('html, body').animate({
		        scrollTop: $("#" + anchor).offset().top - 20
		    }, 0);
		}
	}

	$(document).on('click', '.addHash', function(e) {
		e.preventDefault();
		window.location.hash = $(this).attr('href');
	});

	$(document).on('click', '.confirm', function(e) {
		if (!confirm($(this).attr('data-confirm'))) {
			e.preventDefault();			
		}
	});

	$(document).on('click', '.prevent', function(e) {
		e.preventDefault();
	});

	$(document).on('click', '.delete', function() {
		if (!confirm($(this).attr('data-confirm'))) return false;

		var url = '/admin/lokalizacje/' + $(this).attr('data-type') + '/usun/i/' + $(this).attr('data-id');
		window.location.href = url;
	})

	function stripQuery(url) {
		return url.split("?")[0].split("#")[0];
	}

	$(document).on('click', '.answer', function() {
		location.reload();
	});

	function showMessage(div, message) {
		div.fadeIn(200).html(message);
		setTimeout(function() {
			div.fadeOut(200);
		}, 5000);
	}

	$('[data-toggle="tabajax"]').click(function(e) {
	    var $this = $(this),
			loadurl = $this.attr('data-href'),
			targ = $this.attr('data-target'),
			hash = $(this).attr('href');

		window.location.hash = hash;
		
		//$(targ).html('<div class="center"><h2>Ładowanie...</h2></div>');		
		$(targ).html('').addClass('loading');
	    
	    $.get(loadurl, function(data) {
			$(targ).removeClass('loading');
	        $(targ).html(data);


	        var counter = $('#counter_messages');
			if (counter.length > 0)
				$('.counter_wiadomosci').text(counter.text());

			zaladowane = 0;
			zostalo = $('.com').length;
			if (zostalo > 0)
				showComments();

		});
	    $this.parent().parent().parent().removeClass('open');
	    $this.tab('show');
	    return false;
	});


	$(document).on('click', '.deleteImg', function(e) {
		e.preventDefault();
		var $this = $(this);
		var kom = $this.attr('data-confirm');

		if (confirm(kom)) {
			ladowanie();
			$.ajax({
				type: 'GET',
				url: $this.attr('href'),
				cache: false,
				success: function(result) {
					var id = $this.attr('data-id');
					if ($('#galeria_kolejnosc img').length == 1) {
						$('#galeria_kolejnosc').fadeOut(300);
						setTimeout(function() {$('#galeria_kolejnosc').remove();}, 300);
					}						
					$('#' + id).fadeOut(300);
					setTimeout(function() {$('#' + id).remove();}, 300);						
					ladowanie();
					kom('Zdjęcie zostało usunięte');
				}
			});
		}
	});

	

	$("#galeria_sort").disableSelection().sortable();


	$(document).on('click', '#zapiszKolejnosc', function() {
		ladowanie();
		var order = $('#galeria_sort').sortable('serialize');
		var formularz = $('#galeria_kolejnosc').serialize({ checkboxesAsBools: true });
		$('#hidden').load("/admin/oferty/zapisz-kolejnosc/?"+order+"&"+formularz, function() {
			ladowanie();
			kom('Kolejność zdjęć została zaktualizowana!');
		});
	});



	$(document).on('click', 'button.ajax', function(e) {
		var button = $(this);
		var row = button.parent().parent();
		var url = $(this).attr('href');
		
		$.ajax({
			url: url,
			type: 'post',
			datatype: 'json',
			success: function(result) {				
				if (result.status == true) {
					row.fadeOut(200, function() { $(this).remove() });
				}
		    }
		});
	})

	function setTab(hash) {		
		$('a[href="' + hash + '"]').trigger('click');
	}

	if(window.location.hash != "") {
		setTab(window.location.hash);
	}
	/*
	$('[placeholder]').focus(function() {
	  var input = $(this);
	  if (input.val() == input.attr('placeholder')) {
	    input.val('');
	    input.removeClass('placeholder');
	  }
	}).blur(function() { 
	  var input = $(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
	    input.addClass('placeholder');
	    input.val(input.attr('placeholder'));
	  }
	}).blur().parents('form').submit(function(e) {
	  	$(this).find('[placeholder]').each(function() {
	   		var input = $(this);
	    	if (input.val() == input.attr('placeholder')) {
	    		input.val('');
	  		}
	  	});
	});*/

	$('select').change(function() {
	 if ($(this).children('option:first-child').is(':selected')) {
	   $(this).addClass('placeholder');
	 } else {
	  $(this).removeClass('placeholder');
	 }
	});

	$('select').change(); 

	updateWidth();

	$('.affix').each(function() { 
		$(this).removeClass('affix');
		var tmp = $(this).offset();
		var top = $(this).attr('data-offset-top');
		$(this).addClass('affix');
		$(this).affix({
		  offset: {
		    top: tmp.top - top,
		    bottom: function () {
		      return (this.bottom = $('.footer').outerHeight(true));
		    }
		  }
		});
	});

});


function fixImages() {
	$('.c16by9').each(function() {
		$(this).css('height', ( (9/16) * $(this).width() ) + 'px');
	});
}

var stan = false;

function setElements() {
	var width = $(document).width();
	if (width > 767) {

	} else {
		

	}
}


function updateWidth() {
	$('.affix').each(function() {
       	var tmp = $(this);
       	$(this).css('width', tmp.parent().width() + 'px');
    });
}

function ustawieniaMobilne() {
    $(".search-move").insertBefore('.search-align');
    $('.search-box').addClass('collapse');
}
function ustawieniaDesktop() {
	$(".search-move").insertAfter('.search-align');
    $('.search-box').removeClass('collapse').css('height', 'auto');
}


(function($, viewport){

	var stan = viewport.is('xs');

	if ( stan ) {
		ustawieniaMobilne();
    }

    $(window).bind('resize', function() {
		fixImages();

        viewport.changed(function(){
        	updateWidth();
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


$(function() {
    var cache = {};
    $( "#users" ).autocomplete({
      minLength: 2,
      source: function( request, response ) {
        var term = request.term;
        if ( term in cache ) {
          response( cache[ term ] );
          return;
        }
        $.getJSON( "/profil/users/" + term, function( data, status, xhr ) {
          cache[ term ] = data;
          response( data );
        });
      }
    });
  });