$(function() {
	/*
	 * Slidshow(mainVisual)
	 */
	
	$('.mainVisual').each(function(){

		var $slides = $(this).find('img'),
		slideCount = $slides.length,
		currentIndex = 0;

		//first img -> fadeIn
		$slides.eq(currentIndex).fadeIn();
		//run function showNextSlide each 6500msec
		setInterval(showNextSlide, 6500);

		function showNextSlide (){
			var nextIndex = (currentIndex + 1) % slideCount;

			$slides.eq(currentIndex).fadeOut();

			$slides.eq(nextIndex).fadeIn();

			currentIndex = nextIndex;
		}

	});

	 /*
	 * Sticky header
	 */
	 $('#pageHeader').each(function () {

        var $window = $(window), 
            $header = $(this), 
            headerOffsetTop = $header.offset().top;

        $window.on('scroll', function () {
            if ($window.scrollTop() > headerOffsetTop) {
                $header.addClass('sticky');
            } else {
                $header.removeClass('sticky');
            }
        });

       // $window.trigger('scroll');
	});

});



