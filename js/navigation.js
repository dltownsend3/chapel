/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */

( function() {
	const siteNavigation = document.getElementById( 'site-navigation' );

	// Return early if the navigation doesn't exist.
	if ( ! siteNavigation ) {
		return;
	}

	const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

	// Return early if the button doesn't exist.
	if ( 'undefined' === typeof button ) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		siteNavigation.classList.toggle( 'toggled' );

		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	// document.addEventListener( 'click', function( event ) {
	// 	const isClickInside = siteNavigation.contains( event.target );

	// 	if ( ! isClickInside ) {
	// 		siteNavigation.classList.remove( 'toggled' );
	// 		button.setAttribute( 'aria-expanded', 'false' );
	// 	}
	// } );

	// Get all the link elements within the menu.
	const links = menu.getElementsByTagName( 'a' );

	// Get all the link elements with children within the menu.
	const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

	// Toggle focus each time a menu link is focused or blurred.
	for ( const link of links ) {
		link.addEventListener( 'focus', toggleFocus, true );
		link.addEventListener( 'blur', toggleFocus, true );
	}

	// Toggle focus each time a menu link with children receive a touch event.
	for ( const link of linksWithChildren ) {
		link.addEventListener( 'touchstart', toggleFocus, false );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus(event) {
		if ( event.type === 'focus' || event.type === 'blur' ) {
			let self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( ! self.classList.contains( 'nav-menu' ) ) {
				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					self.classList.toggle( 'focus' );
				}
				self = self.parentNode;
			}
		}

		if ( event.type === 'touchstart' ) {
			const menuItem = this.parentNode;
			event.preventDefault();
			for ( const link of menuItem.parentNode.children ) {
				if ( menuItem !== link ) {
					link.classList.remove( 'focus' );
				}
			}
			menuItem.classList.toggle( 'focus' );
		}
	}
}() );

var $ = jQuery;

function enableDesktopMenuHover() {
  if (window.innerWidth >= 641) {
	$('.sub-menu.open').removeClass('open');
    $('#menu-ul > li.menu-item-has-children:not(.open)').hover(
      function () {
        $(this).children('.sub-menu').stop().addClass('open');
      },
      function () {
        $(this).children('.sub-menu').stop().removeClass('open');
      }
    );
  }else{$('.sub-menu').stop().addClass('open');}
}
$(document).ready(enableDesktopMenuHover);
$(window).on('resize', function () {
  $('#menu-ul > li.menu-item-has-children').off('mouseenter mouseleave');
  enableDesktopMenuHover();
});

$('.menu-toggle').on('click', function(event){
	event.preventDefault();
	console.log('menu toggle clicked');
	if ( $('.main-navigation').hasClass('toggled') ){
		console.log('yep');
		$('body').css('overflow-y','hidden');
		$('#primary-menu').stop().fadeIn(300);
	}else{
        $('#primary-menu').stop().fadeOut(300, function(){
			$('#primary-menu').removeAttr('style');
		});
		$('body').css('overflow-y','unset');
    }
});

$(document).ready(function () {
	const $nav = $('.main-navigation');
	function handleScroll() {
		$nav.toggleClass('scrolled', $(window).scrollTop() > 50);
	}
	$(window).on('scroll', handleScroll);
	handleScroll();
});

$('.search-open').on('click', function(event){
	event.preventDefault();
	event.stopPropagation();
  const $box = $('.search-box');
  $box.stop().show();
  setTimeout(() => {
    $box.stop().addClass('shown');
    $box.find('.search-text').focus();
  }, 20);
});

$(document).on('keydown', function(e) {
  if (e.key === 'Escape') {
    $('.search-box').stop().removeClass('shown');
  }
});

$('.search-close').on('click', function() {
  $('.search-box').stop().removeClass('shown');
});

$('.search-submit').on('click', function() {
  const query = $('.search-text').val().trim();
  if (query.length > 0) {
    window.location.href = `${window.location.origin}/?s=${encodeURIComponent(query)}`;
  }
});

$('.search-text').on('keypress', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    $('.search-submit').click();
  }
});

$('.search-trigger').on('click', function(){
	$('.search-open').trigger('click');
});

$(document).on('click', function(event) {
  const $target = $(event.target);
  const $searchBox = $('.search-box');
  const $searchOpenBtn = $('.search-open');
  if ($searchBox.is(':visible') &&
      !$target.closest('.search-box').length && 
      !$target.closest('.search-open').length) {
    $searchBox.stop().removeClass('shown');
  }
});