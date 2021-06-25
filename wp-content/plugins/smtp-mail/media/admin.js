/*
 * SMTP Mail: http://photoboxone.com/
 */
(function($){
	$(document).ready(function(){
		$('#smtpmail_options_SMTPSecure').change(function(){
			var self = $(this),
				v = self.val();
			
			var port = 25;
			if( v == 'ssl' ) {
				port = 465; 
			} else if( v == 'tls' ) {
				port = 587;
			}
			
			$('#smtpmail_options_Port').each(function(){ 
				var p = $(this);
				
				if( p.val()!= port ) {
					p.val( port );
				}
			});
			
			
		});
		
		/*
		$('.smtpmail_tabmenu li').each(function(i){
			var li = $(this);
			
			li.click(function(e){
				e.preventDefault();
				
				$('.smtpmail_tabmenu li').removeClass('active').eq(i).addClass('active');
				$('.smtpmail_tabitem').removeClass('active').eq(i).addClass('active');
			});
		});
		*/
	} );
	
	function smtp_mail_get_lipsum()
	{
		var lips = ['Lorem ipsum dolor sit amet, consectetur adipiscing elit.','Ut fermentum magna quis mauris dictum, in elementum diam maximus.','Praesent pulvinar erat in velit tincidunt, quis fermentum mauris maximus.','Cras vulputate metus id ornare vehicula.','Morbi ultricies neque a rutrum euismod.','Sed varius nisi sit amet nunc tincidunt facilisis.','Maecenas consequat tellus sit amet massa facilisis tincidunt.','Etiam at eros congue, feugiat nisl commodo, interdum metus.','Duis iaculis massa sed nisl euismod sollicitudin.','Ut vestibulum ex sit amet odio eleifend bibendum.','Nam ultrices dolor vel ipsum aliquam venenatis.','Fusce vel lacus ac justo sollicitudin vestibulum.','Nullam vel lectus quis libero tempus pharetra maximus sed ipsum.','Nam non arcu sed dui blandit varius eget ac arcu.','Aliquam congue felis in efficitur vulputate.','Curabitur venenatis mauris eget tristique iaculis.','Donec in lectus interdum, rutrum massa nec, malesuada diam.','Mauris tempus odio in ultrices iaculis.','Quisque vitae arcu ornare, volutpat eros porttitor, rutrum purus.','Integer ac mauris rutrum erat luctus consequat.','Sed non nisl nec nibh aliquet dapibus.','Morbi sit amet lacus lacinia, pulvinar quam et, hendrerit diam.','Nunc dapibus lacus id vehicula tempus.','Pellentesque sit amet quam faucibus lacus cursus convallis at sed ipsum.','Nam consectetur massa a semper eleifend.','Proin fringilla ante ut dui aliquam venenatis.','Phasellus accumsan ante sit amet velit imperdiet efficitur.','Vivamus posuere arcu non sem cursus commodo.'];
		
		Math.random();
	}
	
	function smtp_mail_get_random_text( number )
	{
		var t = Math.random().toString(36);
		number = number ? parseInt(number) : 5;
		if( number > t.length-2 )
			number = t.length-1;
		return t.replace(/[^a-z]+/g, '').substr(0, number);
	};
	
	function smtp_mail_get_random_code( number ) {
		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		
		number = number ? parseInt(number) : 5;

		for (var i = 0; i < number; i++)
			text += possible.charAt(Math.floor(Math.random() * possible.length));
		
		return text;
	};

	// notice-dismiss
	$('.smtpmail-notice').each(function(){
		var notice = $(this),
			update = notice.data('update') || '',
			name = notice.data('name') || '';

		//console.log( 'name', name, ajaxurl );
		if( name && name!='' && typeof ajaxurl != 'undefined' ) {
			notice.on('click', '.notice-dismiss', function(e){
				// console.log( 'click', name );

				$.post(
					ajaxurl, 
					{
						'action': name,
						'update' : update
					}, 
					function(response) {
						//console.log('The server responded: ', response);
					}
				);
			});
		}
	});

	
})(jQuery);