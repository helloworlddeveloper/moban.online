/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 27/01/2011, 9:36
 */


// Ham xu ly khi nhap chuot vao 1 file (Chuot trai lan chuot phai)
function fileMouseup( file, e ){
	// Set shift offset
	if( e.which != 3 && ! KEYPR.isShift ){
		// Reset shift offset
		KEYPR.shiftOffset = 0;

		$.each( $('.imgcontent'), function(k, v){
			if( v == file ){
				KEYPR.shiftOffset = k;
				return false;
			}
		});
	}
	
	// e.which: 1: Left Mouse, 2: Center Mouse, 3: Right Mouse
	if( KEYPR.isCtrl ){
		if( $(file).is('.imgsel') && e.which != 3 ){
			$(file).removeClass('imgsel');
		}else{
			$(file).addClass('imgsel');
		}
	}else if( KEYPR.isShift && e.which != 3 ){
		var clickOffset = -1;
		$('.imgcontent').removeClass('imgsel');
		
		$.each( $('.imgcontent'), function(k, v){
			if( v == file ){
				clickOffset = k;
			}
			
			if( ( clickOffset == -1 && k >= KEYPR.shiftOffset ) || ( clickOffset != -1 && k <= KEYPR.shiftOffset ) || v == file ){
				if( ! $(v).is('.imgsel') ){
					$(v).addClass('imgsel');
				}
			}
		});
	}else{
		if( e.which != 3 || ( e.which == 3 && ! $(file).is('.imgsel') ) ){
			$('.imgsel').removeClass('imgsel');
			$(file).addClass('imgsel');
		}
	}
}

/* Keypress, Click Handle */
var KEYPR = {
	isCtrl : false,
	isShift : false,
	shiftOffset : 0,
	allowKey : [ 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123 ],
	init : function(){
		$('body').keydown(function(e){
			// Ctrl key press
			if( e.keyCode == 17 /* Ctrl */ ){
				KEYPR.isCtrl = true;
			}else if( e.keyCode == 27 /* ESC */ ){
				// Unselect all file
				$(".imgsel").removeClass("imgsel");
				
				// Hide contextmenu
				NVCMENU.hide();
				
				// Reset shift offset
				KEYPR.shiftOffset = 0;
			}else if( e.keyCode == 65 /* A */ && e.ctrlKey === true ){
				// Select all file
				$(".imgcontent").addClass("imgsel");
				
				// Hide contextmenu
				NVCMENU.hide();
			}else if( e.keyCode == 16 /* Shift */ ){
				KEYPR.isShift = true;
			}else if( e.keyCode == 46 /* Del */ ){
				// Delete file
				if( $('.imgsel').length && $("span#delete_file").attr("title") == '1' ){
					filedelete();
				}
			}else if( e.keyCode == 88 /* X */ ){
				// Move file
				if( $('.imgsel').length && $("span#move_file").attr("title") == '1' ){
					move();
				}
			}
		});
		
		// Unselect file when click on wrap area
		$('#imglist, .filebrowse').click(function(e){
			if( $(e.target).is('.filebrowse') || $(e.target).is('#imglist') ){
				$(".imgsel").removeClass("imgsel");
			}
		});
	},
};

// Init functions
KEYPR.init();
// Disable select text
$('#imglist').attr('unselectable','on').css({
	'-moz-user-select':'-moz-none',
	'-moz-user-select':'none',
	'-o-user-select':'none',
	'-khtml-user-select':'none',
	'-webkit-user-select':'none',
	'-ms-user-select':'none',
	'user-select':'none'
}).bind('selectstart', function(){ return false; });