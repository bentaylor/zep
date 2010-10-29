$(document).ready( function() {
	$('a[rel~=external]').click(function()
	{
		window.open( $(this).attr('href') );
		return false;
	});
	$('.message-box').click(function()
	{
		$(this).fadeOut('slow');
	});
});

function get_gravatar()
{
	$('#gravatar-options').slideToggle('slow', function(){
		if ( $('#gravatar-options:visible').length )
		{
			if ( $('#id_gravatar_email').val() == '' )
				$('#id_gravatar_email').val( $('#id_email') );
				
			$.get('profile.php', { gravatar_email: $('#id_gravatar_email').val() }, function(data){
				var gravatar_url = 'http://www.gravatar.com/avatar.php?gravatar_id=' + data;
				$('#id_gravatar_url').val(gravatar_url);
				$('#gravatar_image').attr('src', gravatar_url).fadeIn('slow');
			});
		}
	});
}