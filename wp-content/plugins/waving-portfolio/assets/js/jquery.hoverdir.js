(function(c,b,d){c.HoverDir=function(e,f){this.$el=c(f);this._init(e)};c.HoverDir.defaults={speed:300,easing:"ease",hoverDelay:0,inverse:false};c.HoverDir.prototype={_init:function(e){this.options=c.extend(true,{},c.HoverDir.defaults,e);this.transitionProp="all "+this.options.speed+"ms "+this.options.easing;this.support=Modernizr.csstransitions;this._loadEvents()},_loadEvents:function(){var e=this;this.$el.on("mouseenter.hoverdir, mouseleave.hoverdir",function(i){var g=c(this),f=g.find("div"),j=e._getDir(g,{x:i.pageX,y:i.pageY}),h=e._getStyle(j);if(i.type==="mouseenter"){f.hide().css(h.from);clearTimeout(e.tmhover);e.tmhover=setTimeout(function(){f.show(0,function(){var k=c(this);if(e.support){k.css("transition",e.transitionProp)}e._applyAnimation(k,h.to,e.options.speed)})},e.options.hoverDelay)}else{if(e.support){f.css("transition",e.transitionProp)}clearTimeout(e.tmhover);e._applyAnimation(f,h.from,e.options.speed)}})},_getDir:function(g,k){var f=g.width(),i=g.height(),e=(k.x-g.offset().left-(f/2))*(f>i?(i/f):1),l=(k.y-g.offset().top-(i/2))*(i>f?(f/i):1),j=Math.round((((Math.atan2(l,e)*(180/Math.PI))+180)/90)+3)%4;return j},_getStyle:function(k){var g,l,i={left:"0px",top:"-100%"},e={left:"0px",top:"100%"},h={left:"-100%",top:"0px"},f={left:"100%",top:"0px"},m={top:"0px"},j={left:"0px"};switch(k){case 0:g=!this.options.inverse?i:e;l=m;break;case 1:g=!this.options.inverse?f:h;l=j;break;case 2:g=!this.options.inverse?e:i;l=m;break;case 3:g=!this.options.inverse?h:f;l=j;break}return{from:g,to:l}},_applyAnimation:function(f,e,g){c.fn.applyStyle=this.support?c.fn.css:c.fn.animate;f.stop().applyStyle(e,c.extend(true,[],{duration:g+"ms"}))}};var a=function(e){if(b.console){b.console.error(e)}};c.fn.hoverdir=function(g){var e=c.data(this,"hoverdir");if(typeof g==="string"){var f=Array.prototype.slice.call(arguments,1);this.each(function(){if(!e){a("cannot call methods on hoverdir prior to initialization; attempted to call method '"+g+"'");return}if(!c.isFunction(e[g])||g.charAt(0)==="_"){a("no such method '"+g+"' for hoverdir instance");return}e[g].apply(e,f)})}else{this.each(function(){if(e){e._init()}else{e=c.data(this,"hoverdir",new c.HoverDir(g,this))}})}return e}})(jQuery,window);