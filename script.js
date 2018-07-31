function change(state) {
    if(state === null) { // initial page
        $("#content").load('index_content.php');
		$('#home').parent().attr('style', 'background-color: #6D071A');
    } else { // page added with pushState
        $("#content").load('https://cvlcondorcet.fr'+state.url);
    }
} /* Loading url */

$('document').ready(function() {
change(null); /*Displaying home*/
$(window).on("popstate", function(e) { /* Managing history */
    change(e.originalEvent.state);
});

var $loading = $('#loadingDiv').hide(); /* Displaying spinner while ajax request runs */
$(document)
  .ajaxStart(function () {
    $loading.show();
  })
  .ajaxStop(function () {
    $loading.hide();
  });

(function(original) { // overwrite history.pushState so that it also calls
                      // the change function when called
    history.pushState = function(state) {
        change(state);
        return original.apply(this, arguments);
    };
})(history.pushState);

	$('nav a').click(function() { /* Handling clicks */
		$('nav li').removeAttr('style');
		$(this).parent().attr('style', 'background-color: #6D071A');
	});

	$('#posts').click(function(e) {
		e.preventDefault();
		//$('#content').load('posts_display.php');
		history.pushState({ url: "/posts_display.php" }, "", "");
	});
	$('#home').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/index_content.php"}, "", "");
		
	});
	$('#posts_add').click(function(e) {
		e.preventDefault();
		//$('#content').load('posts_add.php');
		history.pushState({ url: '/posts_add.php' }, "", "");
	});
	$('#posts_draft').click(function(e) {
		e.preventDefault();
		//$('#content').load('posts_display.php?draft');
		history.pushState({ url: "/posts_display.php?draft" }, "", "");
	});
	$('#posts_deleted').click(function(e) {
		e.preventDefault();
		//$('#content').load('posts_display.php?deleted');
		history.pushState({ url: "/posts_display.php?deleted" }, "", "");
	});
	$('#absences').click(function(e) {
		e.preventDefault();
		//$('#content').load('teachers_display.php');
		history.pushState({ url: "/teachers_display.php" }, "", "");
	});
	$('#absences_add').click(function(e) {
		e.preventDefault();
		//$('#content').load('teachers_add.php');
		history.pushState({ url: "/teachers_add.php" }, "", "");
	});
	$('#absences_deleted').click(function(e) {
		e.preventDefault();
		//$('#content').load('teachers_display.php?deleted=1');
		history.pushState({ url: "/teachers_display.php?deleted=1" }, "", "");
	});
	$('#canteen').click(function(e) {
		e.preventDefault();
		//$('#content').load('canteen.php');
		history.pushState({ url: "/canteen.php" }, "", "");
	});
	$('#categories').click(function(e) {
		e.preventDefault();
		//$('#content').load('categories.php');
		history.pushState({ url: "/categories.php" }, "", "");
	});
	$('#general').click(function(e) {
		e.preventDefault();
		//$('#content').load('general_settings.php');
		history.pushState({ url: "/general_settings.php" }, "", "");
	});
	$('#categories').click(function(e) {
		e.preventDefault();
		//$('#content').load('categories.php');
		history.pushState({ url: "/categories.php" }, "", "");
	});
	$('#users').click(function(e){
		e.preventDefault();
		//$('#content').load('users_display.php');
		history.pushState({ url: "/users_display.php" }, "", "");
	});
	$('#users_add').click(function(e) {
		e.preventDefault();
		//$('#content').load('users_add.php');
		history.pushState({ url: "/users_add.php" }, "", "");
	});
	$('#top_secret').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/emergency.php"}, "", "");
	});
	$('#top_secret2').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/clients.php"}, "", "");
	});
	$('#logs').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/log.php"}, "", "");
	});
	$('#maps').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/maps_display.php"}, "", "");
	});
	$('#maps_add').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/maps_add.php"}, "", "");
	});
	$('#events').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/events_display.php"}, "", "");
	});
	$('#events_add').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/event_add.php"}, "", "");
	});
	$('#cvl').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/cvl.php"}, "", "");
	});
	$('#social_networks').click(function(e) {
		e.preventDefault();
		history.pushState({url: "/social_networks.php"}, "", "");
	});
	$(document).on('click', '.display_link, .modify_post, .modify_map, .event_modify', function(e) { /* Handling clicks from loaded contents */
		e.preventDefault();
		//$('#content').load($(this).attr('href'));
		history.pushState({ url: "/"+$(this).attr('href') }, "", "");
	});
	$(document).on('click', '.delete_post', function(e) {
		e.preventDefault();
		$.get($(this).attr('href')).done(function(data) {
			$('#posts')[0].click();
		});
	});
	$(document).on('click', '.delete_map', function(e) {
		e.preventDefault();
		$.get($(this).attr('href')).done(function(data) {
			$('#maps')[0].click();
		});
	});
	$(document).on('click', '.delete_absence', function(e) {
		e.preventDefault();
		$.get($(this).attr('href')).done(function(data) {
			$('#absences')[0].click();
		});
	});
	$(document).on('click', '.delete_categorie', function(e) {
		e.preventDefault();
		$.get($(this).attr('href')).done(function(data) {
			$('#categories')[0].click();
		});
	});
	$(document).on('click', '.delete_event', function(e) {
		e.preventDefault();
		$.get($(this).attr('href')).done(function(date) {
			$('#events')[0].click();
		});
	});
	$(document).on('submit', '#form_add_post', function(e) { /*Handling forms */
		e.preventDefault();
		if($('#state').val() == 'published') {
			if(confirm('Êtes-vous sûr·e de vouloir publier cet article ?\nAprès publication, l\'article sera synchronisé sur les appareils clients et vous ne pourrez plus revenir en arrière.')) {
				$.post('handle_posts.php', $(this).serialize()).done(function(data) {
					$('#posts')[0].click();
				});
			}
		} else {
				$.post('handle_posts.php', $(this).serialize()).done(function(data) {
					$('#posts')[0].click();
				});
		}
	});
	$(document).on('submit', '#absence_form', function(e) {
		e.preventDefault();
		$.post('handle_teachers.php', $(this).serialize()).done(function(data) {
			$('#absences')[0].click();
		});
	});
	$(document).on('submit', '#canteen_form', function(e) {
		e.preventDefault();
		$.post('canteen.php', $(this).serialize()).done(function(data) {
			$('#content').replaceWith("<div id=\"content\">"+data+"</div>");
		});
	});

	$(document).on('submit', '#categorie_form', function(e) {
		e.preventDefault();
		$.post('categories.php', $(this).serialize()).done(function(data) {
			$('#content').replaceWith("<div id=\"content\">"+data+"</div>");
		});
	});
	$(document).on('submit', '#general_form', function(e) {
		e.preventDefault();
		$.post('general_settings.php', $(this).serialize()).done(function(data) {
			$('#content').replaceWith("<div id=\"content\">"+data+"</div>");
		});
	});
	$(document).on('click', '.delete_user', function(e) {
		e.preventDefault();
		$.get($(this).attr('href')).done(function(data) {
			$('#content').replaceWith("<div id=\"content\">"+data+"</div>");
		});
	});
	$(document).on('submit', '#form_delete', function(e) {
		e.preventDefault();
		$.post('users_delete.php', $(this).serialize()).done(function(data) {
			$('#content').replaceWith("<div id=\"content\">"+data+"</div>");
		});
	});
	$(document).on('click', '.modify_user', function(e) {
		e.preventDefault();
		//$('#content').load($(this).attr('href'));
		history.pushState({ url: "/"+$(this).attr('href') }, "", "");
	});
	$(document).on('submit', '#add_user_form', function(e) {
		e.preventDefault();
		$.post('users_add.php', $(this).serialize()).done(function(data) {
			$('#users')[0].click();
		});
	});
	$(document).on('submit', "#emergency_form", function(e) {
		e.preventDefault();
		$.post('emergency.php', $(this).serialize()).done(function(data) {
			$('#content').replaceWith("<div id=\"content\">"+data+"</div>");
		});
	});
	$(document).on('submit', '#add_map_form', function(e) {
		e.preventDefault();
		$.post('handle_map.php', $(this).serialize()).done(function(data) {
			$('#maps')[0].click();
		});
	});
	$(document).on('submit', '#form_add_event', function(e) {
		e.preventDefault();
		if($('#state').val() == 'published') {
			if(confirm('Êtes-vous sûr·e de vouloir publier cet article ?\nAprès publication, l\'article sera synchronisé sur les appareils clients et vous ne pourrez plus revenir en arrière.')) {
				$.post('handle_events.php', $(this).serialize()).done(function(data) {
					$('#events')[0].click();
				});
			}
		} else {
			$.post('handle_events.php', $(this).serialize()).done(function(data) {
					$('#events')[0].click();
			});
		}
	});
	$(document).on('submit', '#form_cvl', function(e) {
		e.preventDefault();
		$.post('handle_cvl.php', $(this).serialize()).done(function(data) {
			$('#cvl')[0].click();
		});
	});
	$(document).on('mouseenter', '.table tr td:first-child', function() {
		$(this).find('div').show();
	});
		$(document).on('mouseleave', '.table tr td:first-child', function() {
		$(this).find('div').hide();
	});
window.onbeforeunload = function (e) { /* Prevent user refreshes the page accidentaly */
    var e = e || window.event;

    // For IE and Firefox
    if (e) {
        e.returnValue = 'Leaving the page';
    }

    // For Safari
    return 'Leaving the page';
};
});