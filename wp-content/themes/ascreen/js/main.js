jQuery(document).ready(function($){

	//headerNav();
	
	function headerNavMobile(){
		var header_nav = $(".header-nav");
		var header_nav_list = header_nav.children('ul');
		$("#header-nav-btn").click(function(){
			$(this).toggleClass("active");//add and remove active class for #header-nav-btn
			$(this).parents("#header").toggleClass("headerbg");
			header_nav_list.toggleClass("show");
		})
	}
	headerNavMobile();
	
	
	//go top
    var toolitembar = $("#toolitembar");
    var gotop = $("#back-top");

    $(window).scroll(function () {
        var scroll_y = $(window).scrollTop();
        if (scroll_y > 500) {
            gotop.addClass('show');
        } else {
            gotop.removeClass('show');
        }
    });
    gotop.click(function (event) {
        $("body,html").animate({ scrollTop: 0 }, 600);
    });

	//check header
	
	// contact form
	jQuery("form.contact-form #submit").click(function(){
		//alert('ok');
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var obj = jQuery(this).parents(".contact-form");
		var Name    = obj.find("input#name").val();
		var Email   = obj.find("input#email").val();
		var Message = obj.find("textarea#message").val();
		var sendto  = obj.find("input#sendto").val();
		Name    = Name.replace('Name','');
		Email   = Email.replace('Email','');
		Message = Message.replace('Message','');
		
		//alert('Email:'+Email);
		if( !obj.find(".noticefailed").length ){
			obj.append('<div class="noticefailed"></div>');
			}
		obj.find(".noticefailed").text("");
		
		//alert('Email:'+Email);
		if(Name ===""){
			obj.find(".noticefailed").text("Please enter your name.");
			return false;
		}	
	
		if( !(emailReg.test( Email )) || Email ==='' ) {		
			obj.find(".noticefailed").text("Please enter valid email.");
			return false;
		}
	
		if(Message === ""){
			obj.find(".noticefailed").text("Message is required.");
			return false;
		}
		
		//alert(ascreen_params.themeurl+'/custom/images/loading.gif');
	
		obj.find(".noticefailed").html("");
		obj.find(".noticefailed").append("<img alt='loading' class='loading' src='"+ascreen_params.themeurl+"/images/loading.gif' />");
		
		//alert(Message);
		
		 jQuery.ajax({
					 type:"POST",
					 dataType:"json",
					 url:ascreen_params.ajaxurl,
					 data:"Name="+Name+"&Email="+Email+"&Message="+Message+"&sendto="+sendto+"&action=ascreen_contact",
					 success:function(data){ 
						 if(data.error==0){
							 obj.find(".noticefailed").addClass("noticesuccess").removeClass("noticefailed");
							 obj.find(".noticesuccess").html(data.msg);
							 }else{
								 obj.find(".noticefailed").html(data.msg);	
								 }
								 jQuery('.loading').remove();obj[0].reset();
						},
						error:function(){
							obj.find(".noticefailed").html("Error.");
							obj.find('.loading').remove();
							}
			});
	});
	// contact form end	
	
	
	// video resize
	var height = jQuery(window).height();
	jQuery(".ascreen_video_background").css("height",height);
	var height_content_video = jQuery(".section_content_video").height();
	var top = (height-height_content_video)/2-70;
	
	if(top<0)
	{
		jQuery(".section_content_video").css("padding-top",0); 
	}else{
		jQuery(".section_content_video").css("padding-top",top); 
	}
	
	$(window).resize(function() {
		var height = jQuery(window).height();
		jQuery(".ascreen_video_background").css("height",height);
		var height_content_video = jQuery(".section_content_video").height();
		var top = (height-height_content_video)/2;
		
		if(top<0)
		{
			jQuery(".section_content_video").css("padding-top",0); 
		}else{
			jQuery(".section_content_video").css("padding-top",top); 
		}
	
	});
	
	//hide video buuton
	window.onscroll=function(){ 
		if ($(document).scrollTop() > height ) 
		{ 
			$("#video_button_wrapper .goto").css({display:"none"});
		}else{
			
			$("#video_button_wrapper .goto").css({display:"block"});
		}
	}
	// video resize end	
	
	
	
});

//video
var myPlayer;
jQuery(function () {
	myPlayer = jQuery(".ascreen_video_background").YTPlayer({align:"center,left"});
});

// @param state
function changeLabel(state){
	jQuery("#togglePlay").html(state != 1 ? "&raquo;" : "&times;");

	jQuery("#togglePlay").removeClass(state != 1 ? "play" : "pause");
	jQuery("#togglePlay").addClass(state != 1 ? "pause" : "play");
}
//video end
