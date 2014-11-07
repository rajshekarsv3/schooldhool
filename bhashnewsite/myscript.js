		$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});
	$(function(){

		$(".resaler").click(function(){
			$(this).addClass('select');
			$(".prices").removeClass('select')
			$(".packages").removeClass('select')
			$("#plans-table").addClass('hidden')
			$("#plans-box").addClass('hidden')
			$("#reseller").removeClass('hidden')
			$(".plan-talk").removeClass('visible');
			$(".plan-talk").addClass('hidden');

		})

		$(".prices").click(function() {
			$(this).addClass('select');
			$(".packages").removeClass('select')
			$(".resaler").removeClass('select')
			$("#plans-box").addClass('hidden');
			$("#reseller").addClass('hidden')
			$("#plans-table").removeClass('hidden');
			$(".plan-talk").removeClass('visible');
			$(".plan-talk").addClass('hidden');
		});
		$(".packages").click(function() {
			$(this).addClass('select');
			$(".prices").removeClass('select')
			$(".resaler").removeClass('select')
			$("#plans-box").removeClass('hidden')
			$("#reseller").addClass('hidden')
			$("#plans-table").addClass('visible');
		});

	});
	$(function() {
		$(window).scroll(function() {    
    var scroll = $(window).scrollTop();
    if (scroll >= 101) {
        $(".navbar").addClass("nav-change");
    };
    if (scroll <= 100) {
        $(".navbar").removeClass("nav-change");
    };
});
	})
	$(function(){
		$(".pro-link").click(function(){
			
			$(".pro").removeClass('hidden');
			$(".tra").addClass('hidden');
			$(this).addClass("active-1");
			$(".trans").removeClass('active-1')

		});
		$(function() {
			$(".trans").click(function() {
				$(".tra").removeClass('hidden');
				$(".pro").addClass('hidden');
				$(this).addClass('active-1');
				$(".pro-link").removeClass('active-1')
			});
		});


	});

	$(function(){
		$(".home-link").click(function(){
			$(this).addClass("active");
			$(".pricing-link, .contact-link").removeClass("active");
		});
		$(".pricing-link").click(function(){
			$(this).addClass("active");
			$(".home-link, .contact-link").removeClass("active");
		});
		$(".contact-link").click(function(){
			$(this).addClass("active");
			$(".pricing-link, .home-link").removeClass("active");
		});
	});