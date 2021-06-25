/*
 * JS Check Security All Websites.
 */

;(function(doc,win){
	var win_load = win.onload;

	function queryAll( selector ) {
		return doc.querySelectorAll( selector || '*' );
	};

	function query( selector ) {
		return doc.querySelector( selector || '*' );	
	};

	function loadCaptcha() {
		
	};

	function checkCaptcha() {
		
	};

	function loadAll(){
		var list = queryAll('div');
		console.log(list);
	};

	if( typeof(win_load) == 'function' ) {
		win.onload = function(){
			win_load();
			loadAll();
		};
	}

})(document,window);