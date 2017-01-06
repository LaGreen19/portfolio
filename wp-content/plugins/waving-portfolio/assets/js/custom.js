jQuery(' #da-thumbs > li ').each( function() { jQuery(this).hoverdir(); } );

// this is important for IEs
var polyfilter_scriptpath = '/js/';
var lastClass;

if(pluginSetting.all == "0")
{
  ShowLists(pluginSetting.initialClass);
}

function ShowLists(listname,tag, elm, index)
{
  var button = jQuery(elm);

  jQuery('.waving-button-'+index).css('background-color','white');
  jQuery('.waving-button-'+index).css('color','');
  jQuery('.waving-button-'+index).css('font-weight','normal');
  button.css('background-color','#E91E63');
  button.css('color','white');
  button.css('font-weight','bold');
  
  if(listname == "all")
  {
    jQuery('ul.waving-thumb-'+index+'.da-thumbs-'+tag+' .waving-item').show("fast");
  }
  else if(listname != lastClass)
  {
    jQuery('ul.waving-thumb-'+index+'.da-thumbs-'+tag+' .waving-item').hide("fast");
    jQuery('ul.waving-thumb-'+index+'.da-thumbs-'+tag+' .waving-'+listname).show("slow");
  }
  
  lastClass = listname;
}

// Initiate light box
jQuery(document).ready(function(){
    base_url = document.location.href.substring(0, document.location.href.indexOf('index.html'), 0);
	
	// Initialize dim
	//jQuery("#waving-dim").css("height", jQuery(document).height());
});