/*! lazysizes - v5.3.0-beta1 */
!function(e){var t=function(u,D,f){"use strict";var k,H;if(function(){var e;var t={lazyClass:"lazyload",loadedClass:"lazyloaded",loadingClass:"lazyloading",preloadClass:"lazypreload",errorClass:"lazyerror",autosizesClass:"lazyautosizes",fastLoadedClass:"ls-is-cached",iframeLoadMode:0,srcAttr:"data-src",srcsetAttr:"data-srcset",sizesAttr:"data-sizes",minSize:40,customMedia:{},init:true,expFactor:1.5,hFac:.8,loadMode:2,loadHidden:true,ricTimeout:0,throttleDelay:125};H=u.lazySizesConfig||u.lazysizesConfig||{};for(e in t){if(!(e in H)){H[e]=t[e]}}}(),!D||!D.getElementsByClassName){return{init:function(){},cfg:H,noSupport:true}}var O=D.documentElement,i=u.HTMLPictureElement,P="addEventListener",$="getAttribute",q=u[P].bind(u),I=u.setTimeout,U=u.requestAnimationFrame||I,o=u.requestIdleCallback,j=/^picture$/i,r=["load","error","lazyincluded","_lazyloaded"],a={},G=Array.prototype.forEach,J=function(e,t){if(!a[t]){a[t]=new RegExp("(\\s|^)"+t+"(\\s|$)")}return a[t].test(e[$]("class")||"")&&a[t]},K=function(e,t){if(!J(e,t)){e.setAttribute("class",(e[$]("class")||"").trim()+" "+t)}},Q=function(e,t){var a;if(a=J(e,t)){e.setAttribute("class",(e[$]("class")||"").replace(a," "))}},V=function(t,a,e){var i=e?P:"removeEventListener";if(e){V(t,a)}r.forEach(function(e){t[i](e,a)})},X=function(e,t,a,i,r){var n=D.createEvent("Event");if(!a){a={}}a.instance=k;n.initEvent(t,!i,!r);n.detail=a;e.dispatchEvent(n);return n},Y=function(e,t){var a;if(!i&&(a=u.picturefill||H.pf)){if(t&&t.src&&!e[$]("srcset")){e.setAttribute("srcset",t.src)}a({reevaluate:true,elements:[e]})}else if(t&&t.src){e.src=t.src}},Z=function(e,t){return(getComputedStyle(e,null)||{})[t]},s=function(e,t,a){a=a||e.offsetWidth;while(a<H.minSize&&t&&!e._lazysizesWidth){a=t.offsetWidth;t=t.parentNode}return a},ee=function(){var a,i;var t=[];var r=[];var n=t;var s=function(){var e=n;n=t.length?r:t;a=true;i=false;while(e.length){e.shift()()}a=false};var e=function(e,t){if(a&&!t){e.apply(this,arguments)}else{n.push(e);if(!i){i=true;(D.hidden?I:U)(s)}}};e._lsFlush=s;return e}(),te=function(a,e){return e?function(){ee(a)}:function(){var e=this;var t=arguments;ee(function(){a.apply(e,t)})}},ae=function(e){var a;var i=0;var r=H.throttleDelay;var n=H.ricTimeout;var t=function(){a=false;i=f.now();e()};var s=o&&n>49?function(){o(t,{timeout:n});if(n!==H.ricTimeout){n=H.ricTimeout}}:te(function(){I(t)},true);return function(e){var t;if(e=e===true){n=33}if(a){return}a=true;t=r-(f.now()-i);if(t<0){t=0}if(e||t<9){s()}else{I(s,t)}}},ie=function(e){var t,a;var i=99;var r=function(){t=null;e()};var n=function(){var e=f.now()-a;if(e<i){I(n,i-e)}else{(o||r)(r)}};return function(){a=f.now();if(!t){t=I(n,i)}}},e=function(){var v,m,c,h,e;var y,z,g,p,C,b,A;var n=/^img$/i;var d=/^iframe$/i;var E="onscroll"in u&&!/(gle|ing)bot/.test(navigator.userAgent);var _=0;var w=0;var M=0;var N=-1;var L=function(e){M--;if(!e||M<0||!e.target){M=0}};var x=function(e){if(A==null){A=Z(D.body,"visibility")=="hidden"}return A||!(Z(e.parentNode,"visibility")=="hidden"&&Z(e,"visibility")=="hidden")};var W=function(e,t){var a;var i=e;var r=x(e);g-=t;b+=t;p-=t;C+=t;while(r&&(i=i.offsetParent)&&i!=D.body&&i!=O){r=(Z(i,"opacity")||1)>0;if(r&&Z(i,"overflow")!="visible"){a=i.getBoundingClientRect();r=C>a.left&&p<a.right&&b>a.top-1&&g<a.bottom+1}}return r};var t=function(){var e,t,a,i,r,n,s,o,l,u,f,c;var d=k.elements;if((h=H.loadMode)&&M<8&&(e=d.length)){t=0;N++;for(;t<e;t++){if(!d[t]||d[t]._lazyRace){continue}if(!E||k.prematureUnveil&&k.prematureUnveil(d[t])){R(d[t]);continue}if(!(o=d[t][$]("data-expand"))||!(n=o*1)){n=w}if(!u){u=!H.expand||H.expand<1?O.clientHeight>500&&O.clientWidth>500?500:370:H.expand;k._defEx=u;f=u*H.expFactor;c=H.hFac;A=null;if(w<f&&M<1&&N>2&&h>2&&!D.hidden){w=f;N=0}else if(h>1&&N>1&&M<6){w=u}else{w=_}}if(l!==n){y=innerWidth+n*c;z=innerHeight+n;s=n*-1;l=n}a=d[t].getBoundingClientRect();if((b=a.bottom)>=s&&(g=a.top)<=z&&(C=a.right)>=s*c&&(p=a.left)<=y&&(b||C||p||g)&&(H.loadHidden||x(d[t]))&&(m&&M<3&&!o&&(h<3||N<4)||W(d[t],n))){R(d[t]);r=true;if(M>9){break}}else if(!r&&m&&!i&&M<4&&N<4&&h>2&&(v[0]||H.preloadAfterLoad)&&(v[0]||!o&&(b||C||p||g||d[t][$](H.sizesAttr)!="auto"))){i=v[0]||d[t]}}if(i&&!r){R(i)}}};var a=ae(t);var S=function(e){var t=e.target;if(t._lazyCache){delete t._lazyCache;return}L(e);K(t,H.loadedClass);Q(t,H.loadingClass);V(t,B);X(t,"lazyloaded")};var i=te(S);var B=function(e){i({target:e.target})};var T=function(e,t){var a=e.getAttribute("data-load-mode")||H.iframeLoadMode;if(a==0){e.contentWindow.location.replace(t)}else if(a==1){e.src=t}};var F=function(e){var t;var a=e[$](H.srcsetAttr);if(t=H.customMedia[e[$]("data-media")||e[$]("media")]){e.setAttribute("media",t)}if(a){e.setAttribute("srcset",a)}};var s=te(function(t,e,a,i,r){var n,s,o,l,u,f;if(!(u=X(t,"lazybeforeunveil",e)).defaultPrevented){if(i){if(a){K(t,H.autosizesClass)}else{t.setAttribute("sizes",i)}}s=t[$](H.srcsetAttr);n=t[$](H.srcAttr);if(r){o=t.parentNode;l=o&&j.test(o.nodeName||"")}f=e.firesLoad||"src"in t&&(s||n||l);u={target:t};K(t,H.loadingClass);if(f){clearTimeout(c);c=I(L,2500);V(t,B,true)}if(l){G.call(o.getElementsByTagName("source"),F)}if(s){t.setAttribute("srcset",s)}else if(n&&!l){if(d.test(t.nodeName)){T(t,n)}else{t.src=n}}if(r&&(s||l)){Y(t,{src:n})}}if(t._lazyRace){delete t._lazyRace}Q(t,H.lazyClass);ee(function(){var e=t.complete&&t.naturalWidth>1;if(!f||e){if(e){K(t,H.fastLoadedClass)}S(u);t._lazyCache=true;I(function(){if("_lazyCache"in t){delete t._lazyCache}},9)}if(t.loading=="lazy"){M--}},true)});var R=function(e){if(e._lazyRace){return}var t;var a=n.test(e.nodeName);var i=a&&(e[$](H.sizesAttr)||e[$]("sizes"));var r=i=="auto";if((r||!m)&&a&&(e[$]("src")||e.srcset)&&!e.complete&&!J(e,H.errorClass)&&J(e,H.lazyClass)){return}t=X(e,"lazyunveilread").detail;if(r){re.updateElem(e,true,e.offsetWidth)}e._lazyRace=true;M++;s(e,t,r,i,a)};var r=ie(function(){H.loadMode=3;a()});var o=function(){if(H.loadMode==3){H.loadMode=2}r()};var l=function(){if(m){return}if(f.now()-e<999){I(l,999);return}m=true;H.loadMode=3;a();q("scroll",o,true)};return{_:function(){e=f.now();k.elements=D.getElementsByClassName(H.lazyClass);v=D.getElementsByClassName(H.lazyClass+" "+H.preloadClass);q("scroll",a,true);q("resize",a,true);q("pageshow",function(e){if(e.persisted){var t=D.querySelectorAll("."+H.loadingClass);if(t.length&&t.forEach){U(function(){t.forEach(function(e){if(e.complete){R(e)}})})}}});if(u.MutationObserver){new MutationObserver(a).observe(O,{childList:true,subtree:true,attributes:true})}else{O[P]("DOMNodeInserted",a,true);O[P]("DOMAttrModified",a,true);setInterval(a,999)}q("hashchange",a,true);["focus","mouseover","click","load","transitionend","animationend"].forEach(function(e){D[P](e,a,true)});if(/d$|^c/.test(D.readyState)){l()}else{q("load",l);D[P]("DOMContentLoaded",a);I(l,2e4)}if(k.elements.length){t();ee._lsFlush()}else{a()}},checkElems:a,unveil:R,_aLSL:o}}(),re=function(){var a;var n=te(function(e,t,a,i){var r,n,s;e._lazysizesWidth=i;i+="px";e.setAttribute("sizes",i);if(j.test(t.nodeName||"")){r=t.getElementsByTagName("source");for(n=0,s=r.length;n<s;n++){r[n].setAttribute("sizes",i)}}if(!a.detail.dataAttr){Y(e,a.detail)}});var i=function(e,t,a){var i;var r=e.parentNode;if(r){a=s(e,r,a);i=X(e,"lazybeforesizes",{width:a,dataAttr:!!t});if(!i.defaultPrevented){a=i.detail.width;if(a&&a!==e._lazysizesWidth){n(e,r,i,a)}}}};var e=function(){var e;var t=a.length;if(t){e=0;for(;e<t;e++){i(a[e])}}};var t=ie(e);return{_:function(){a=D.getElementsByClassName(H.autosizesClass);q("resize",t)},checkElems:t,updateElem:i}}(),t=function(){if(!t.i&&D.getElementsByClassName){t.i=true;re._();e._()}};return I(function(){H.init&&t()}),k={cfg:H,autoSizer:re,loader:e,init:t,uP:Y,aC:K,rC:Q,hC:J,fire:X,gW:s,rAF:ee}}(e,e.document,Date);e.lazySizes=t,"object"==typeof module&&module.exports&&(module.exports=t)}("undefined"!=typeof window?window:{});
/* == Page scroll to id == Version: 1.5.6, License: MIT License (MIT) */
!function(e,t,a){var l,n,s,i,o,r,c,u,h,g,f,d,p="mPageScroll2id",_="mPS2id",C=".m_PageScroll2id,a[rel~='m_PageScroll2id'],.page-scroll-to-id,a[rel~='page-scroll-to-id'],._ps2id",v={scrollSpeed:1e3,autoScrollSpeed:!0,scrollEasing:"easeInOutQuint",scrollingEasing:"easeOutQuint",pageEndSmoothScroll:!0,layout:"vertical",offset:0,highlightSelector:!1,clickedClass:_+"-clicked",targetClass:_+"-target",highlightClass:_+"-highlight",forceSingleHighlight:!1,keepHighlightUntilNext:!1,highlightByNextTarget:!1,disablePluginBelow:!1,clickEvents:!0,appendHash:!1,onStart:function(){},onComplete:function(){},defaultSelector:!1,live:!0,liveSelector:!1},m=0,S={init:function(r){var r=e.extend(!0,{},v,r);if(e(a).data(_,r),n=e(a).data(_),!this.selector){var c="__"+_;this.each(function(){var t=e(this);t.hasClass(c)||t.addClass(c)}),this.selector="."+c}n.liveSelector&&(this.selector+=","+n.liveSelector),l=l?l+","+this.selector:this.selector,n.defaultSelector&&("object"==typeof e(l)&&0!==e(l).length||(l=C)),n.clickEvents&&e(a).undelegate("."+_).delegate(l,"click."+_,function(t){if(I._isDisabled.call(null))return void I._removeClasses.call(null);var a=e(this),l=a.attr("href"),n=a.prop("href");l&&-1!==l.indexOf("#/")||(I._reset.call(null),g=a.data("ps2id-offset")||0,I._isValid.call(null,l,n)&&I._findTarget.call(null,l)&&(t.preventDefault(),i="selector",o=a,I._setClasses.call(null,!0),I._scrollTo.call(null)))}),e(t).unbind("."+_).bind("scroll."+_+" resize."+_,function(){if(I._isDisabled.call(null))return void I._removeClasses.call(null);var t=e("._"+_+"-t");t.each(function(a){var l=e(this),n=l.attr("id"),s=I._findHighlight.call(null,n);I._setClasses.call(null,!1,l,s),a==t.length-1&&I._extendClasses.call(null)})}),s=!0,I._setup.call(null),I._live.call(null)},scrollTo:function(t,a){if(I._isDisabled.call(null))return void I._removeClasses.call(null);if(t&&"undefined"!=typeof t){I._isInit.call(null);var l={layout:n.layout,offset:n.offset,clicked:!1},a=e.extend(!0,{},l,a);I._reset.call(null),u=a.layout,h=a.offset,t=-1!==t.indexOf("#")?t:"#"+t,I._isValid.call(null,t)&&I._findTarget.call(null,t)&&(i="scrollTo",o=a.clicked,o&&I._setClasses.call(null,!0),I._scrollTo.call(null))}},destroy:function(){e(t).unbind("."+_),e(a).undelegate("."+_).removeData(_),e("._"+_+"-t").removeData(_),I._removeClasses.call(null,!0)}},I={_isDisabled:function(){var e=t,l="inner",s=n.disablePluginBelow instanceof Array?[n.disablePluginBelow[0]||0,n.disablePluginBelow[1]||0]:[n.disablePluginBelow||0,0];return"innerWidth"in t||(l="client",e=a.documentElement||a.body),e[l+"Width"]<=s[0]||e[l+"Height"]<=s[1]},_isValid:function(e,a){if(e){a=a?a:e;var l=-1!==a.indexOf("#/")?a.split("#/")[0]:a.split("#")[0],n=t.location.toString().split("#")[0];return"#"!==e&&-1!==e.indexOf("#")&&(""===l||l===n)}},_setup:function(){var t=I._highlightSelector(),a=1,l=0;return e(t).each(function(){var s=e(this),i=s.attr("href"),o=s.prop("href");if(I._isValid.call(null,i,o)){var r=-1!==i.indexOf("#/")?i.split("#/")[1]:i.split("#")[1],c=e("#"+r);if(c.length>0){n.highlightByNextTarget&&c!==l&&(l?l.data(_,{tn:c}):c.data(_,{tn:"0"}),l=c),c.hasClass("_"+_+"-t")||c.addClass("_"+_+"-t"),c.data(_,{i:a}),s.hasClass("_"+_+"-h")||s.addClass("_"+_+"-h");var u=I._findHighlight.call(null,r);I._setClasses.call(null,!1,c,u),m=a,a++,a==e(t).length&&I._extendClasses.call(null)}}})},_highlightSelector:function(){return n.highlightSelector&&""!==n.highlightSelector?n.highlightSelector:l},_findTarget:function(t){var a=-1!==t.indexOf("#/")?t.split("#/")[1]:t.split("#")[1],l=e("#"+a);if(l.length<1||"fixed"===l.css("position")){if("top"!==a)return;l=e("body")}return r=l,u||(u=n.layout),h=I._setOffset.call(null),c=[(l.offset().top-h[0]).toString(),(l.offset().left-h[1]).toString()],c[0]=c[0]<0?0:c[0],c[1]=c[1]<0?0:c[1],c},_setOffset:function(){h||(h=n.offset?n.offset:0),g&&(h=g);var t,a,l,s;switch(typeof h){case"object":case"string":t=[h.y?h.y:h,h.x?h.x:h],a=[t[0]instanceof jQuery?t[0]:e(t[0]),t[1]instanceof jQuery?t[1]:e(t[1])],a[0].length>0?(l=a[0].height(),"fixed"===a[0].css("position")&&(l+=a[0][0].offsetTop)):l=!isNaN(parseFloat(t[0]))&&isFinite(t[0])?parseInt(t[0]):0,a[1].length>0?(s=a[1].width(),"fixed"===a[1].css("position")&&(s+=a[1][0].offsetLeft)):s=!isNaN(parseFloat(t[1]))&&isFinite(t[1])?parseInt(t[1]):0;break;case"function":t=h.call(null),t instanceof Array?(l=t[0],s=t[1]):l=s=t;break;default:l=s=parseInt(h)}return[l,s]},_findHighlight:function(a){var l=t.location,n=l.toString().split("#")[0],s=l.pathname;return e("._"+_+"-h[href='#"+a+"'],._"+_+"-h[href='"+n+"#"+a+"'],._"+_+"-h[href='"+s+"#"+a+"'],._"+_+"-h[href='#/"+a+"'],._"+_+"-h[href='"+n+"#/"+a+"'],._"+_+"-h[href='"+s+"#/"+a+"']")},_setClasses:function(t,a,l){var s=n.clickedClass,i=n.targetClass,r=n.highlightClass;t&&s&&""!==s?(e("."+s).removeClass(s),o.addClass(s)):a&&i&&""!==i&&l&&r&&""!==r&&(I._currentTarget.call(null,a)?(a.addClass(i),l.addClass(r)):(!n.keepHighlightUntilNext||e("."+r).length>1)&&(a.removeClass(i),l.removeClass(r)))},_extendClasses:function(){var t=n.targetClass,a=n.highlightClass,l=e("."+t),s=e("."+a),i=t+"-first",o=t+"-last",r=a+"-first",c=a+"-last";e("._"+_+"-t").removeClass(i+" "+o),e("._"+_+"-h").removeClass(r+" "+c),n.forceSingleHighlight?n.keepHighlightUntilNext&&l.length>1?(l.slice(0,1).removeClass(t),s.slice(0,1).removeClass(a)):(l.slice(1).removeClass(t),s.slice(1).removeClass(a)):(l.slice(0,1).addClass(i).end().slice(-1).addClass(o),s.slice(0,1).addClass(r).end().slice(-1).addClass(c))},_removeClasses:function(t){e("."+n.clickedClass).removeClass(n.clickedClass),e("."+n.targetClass).removeClass(n.targetClass+" "+n.targetClass+"-first "+n.targetClass+"-last"),e("."+n.highlightClass).removeClass(n.highlightClass+" "+n.highlightClass+"-first "+n.highlightClass+"-last"),t&&(e("._"+_+"-t").removeClass("_"+_+"-t"),e("._"+_+"-h").removeClass("_"+_+"-h"))},_currentTarget:function(a){var l=n["target_"+a.data(_).i],s=a.data("ps2id-target"),i=s&&e(s)[0]?e(s)[0].getBoundingClientRect():a[0].getBoundingClientRect();if("undefined"!=typeof l){var o=a.offset().top,r=a.offset().left,c=l.from?l.from+o:o,u=l.to?l.to+o:o,h=l.fromX?l.fromX+r:r,g=l.toX?l.toX+r:r;return i.top>=u&&i.top<=c&&i.left>=g&&i.left<=h}var f=e(t).height(),d=e(t).width(),p=s?e(s).height():a.height(),C=s?e(s).width():a.width(),v=1+p/f,m=v,S=f>p?v*(f/p):v,I=1+C/d,O=I,M=d>C?I*(d/C):I,b=[i.top<=f/m,i.bottom>=f/S,i.left<=d/O,i.right>=d/M];if(n.highlightByNextTarget){var y=a.data(_).tn;if(y){var w=y[0].getBoundingClientRect();"vertical"===n.layout?b=[i.top<=f/2,w.top>f/2,1,1]:"horizontal"===n.layout&&(b=[1,1,i.left<=d/2,w.left>d/2])}}return b[0]&&b[1]&&b[2]&&b[3]},_scrollTo:function(){d=I._scrollSpeed.call(null),c=n.pageEndSmoothScroll?I._pageEndSmoothScroll.call(null):c;var a=e("html,body"),l=n.autoScrollSpeed?I._autoScrollSpeed.call(null):d,s=a.is(":animated")?n.scrollingEasing:n.scrollEasing,i=e(t).scrollTop(),o=e(t).scrollLeft();switch(u){case"horizontal":o!=c[1]&&(I._callbacks.call(null,"onStart"),a.stop().animate({scrollLeft:c[1]},l,s).promise().then(function(){I._callbacks.call(null,"onComplete")}));break;case"auto":if(i!=c[0]||o!=c[1])if(I._callbacks.call(null,"onStart"),navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/)){var r;a.stop().animate({pageYOffset:c[0],pageXOffset:c[1]},{duration:l,easing:s,step:function(e,a){"pageXOffset"==a.prop?r=e:"pageYOffset"==a.prop&&t.scrollTo(r,e)}}).promise().then(function(){I._callbacks.call(null,"onComplete")})}else a.stop().animate({scrollTop:c[0],scrollLeft:c[1]},l,s).promise().then(function(){I._callbacks.call(null,"onComplete")});break;default:i!=c[0]&&(I._callbacks.call(null,"onStart"),a.stop().animate({scrollTop:c[0]},l,s).promise().then(function(){I._callbacks.call(null,"onComplete")}))}},_pageEndSmoothScroll:function(){var l=e(a).height(),n=e(a).width(),s=e(t).height(),i=e(t).width();return[l-c[0]<s?l-s:c[0],n-c[1]<i?n-i:c[1]]},_scrollSpeed:function(){var t=n.scrollSpeed;return o&&o.length&&o.add(o.parent()).each(function(){var a=e(this);if(a.attr("class")){var l=a.attr("class").split(" ");for(var n in l)if(l[n].match(/^ps2id-speed-\d+$/)){t=l[n].split("ps2id-speed-")[1];break}}}),parseInt(t)},_autoScrollSpeed:function(){var l=e(t).scrollTop(),n=e(t).scrollLeft(),s=e(a).height(),i=e(a).width(),o=[d+d*Math.floor(Math.abs(c[0]-l)/s*100)/100,d+d*Math.floor(Math.abs(c[1]-n)/i*100)/100];return Math.max.apply(Math,o)},_callbacks:function(e){if(n)switch(this[_]={trigger:i,clicked:o,target:r,scrollTo:{y:c[0],x:c[1]}},e){case"onStart":if(n.appendHash&&t.history&&t.history.pushState&&o&&o.length){var a="#"+o.attr("href").split("#")[1];a!==t.location.hash&&history.pushState("","",a)}n.onStart.call(null,this[_]);break;case"onComplete":n.onComplete.call(null,this[_])}},_reset:function(){u=h=g=!1},_isInit:function(){s||S.init.apply(this)},_live:function(){f=setTimeout(function(){n.live?e(I._highlightSelector()).length!==m&&I._setup.call(null):f&&clearTimeout(f),I._live.call(null)},1e3)},_easing:function(){function t(e){var t=7.5625,a=2.75;return 1/a>e?t*e*e:2/a>e?t*(e-=1.5/a)*e+.75:2.5/a>e?t*(e-=2.25/a)*e+.9375:t*(e-=2.625/a)*e+.984375}e.easing.easeInQuad=e.easing.easeInQuad||function(e){return e*e},e.easing.easeOutQuad=e.easing.easeOutQuad||function(e){return 1-(1-e)*(1-e)},e.easing.easeInOutQuad=e.easing.easeInOutQuad||function(e){return.5>e?2*e*e:1-Math.pow(-2*e+2,2)/2},e.easing.easeInCubic=e.easing.easeInCubic||function(e){return e*e*e},e.easing.easeOutCubic=e.easing.easeOutCubic||function(e){return 1-Math.pow(1-e,3)},e.easing.easeInOutCubic=e.easing.easeInOutCubic||function(e){return.5>e?4*e*e*e:1-Math.pow(-2*e+2,3)/2},e.easing.easeInQuart=e.easing.easeInQuart||function(e){return e*e*e*e},e.easing.easeOutQuart=e.easing.easeOutQuart||function(e){return 1-Math.pow(1-e,4)},e.easing.easeInOutQuart=e.easing.easeInOutQuart||function(e){return.5>e?8*e*e*e*e:1-Math.pow(-2*e+2,4)/2},e.easing.easeInQuint=e.easing.easeInQuint||function(e){return e*e*e*e*e},e.easing.easeOutQuint=e.easing.easeOutQuint||function(e){return 1-Math.pow(1-e,5)},e.easing.easeInOutQuint=e.easing.easeInOutQuint||function(e){return.5>e?16*e*e*e*e*e:1-Math.pow(-2*e+2,5)/2},e.easing.easeInExpo=e.easing.easeInExpo||function(e){return 0===e?0:Math.pow(2,10*e-10)},e.easing.easeOutExpo=e.easing.easeOutExpo||function(e){return 1===e?1:1-Math.pow(2,-10*e)},e.easing.easeInOutExpo=e.easing.easeInOutExpo||function(e){return 0===e?0:1===e?1:.5>e?Math.pow(2,20*e-10)/2:(2-Math.pow(2,-20*e+10))/2},e.easing.easeInSine=e.easing.easeInSine||function(e){return 1-Math.cos(e*Math.PI/2)},e.easing.easeOutSine=e.easing.easeOutSine||function(e){return Math.sin(e*Math.PI/2)},e.easing.easeInOutSine=e.easing.easeInOutSine||function(e){return-(Math.cos(Math.PI*e)-1)/2},e.easing.easeInCirc=e.easing.easeInCirc||function(e){return 1-Math.sqrt(1-Math.pow(e,2))},e.easing.easeOutCirc=e.easing.easeOutCirc||function(e){return Math.sqrt(1-Math.pow(e-1,2))},e.easing.easeInOutCirc=e.easing.easeInOutCirc||function(e){return.5>e?(1-Math.sqrt(1-Math.pow(2*e,2)))/2:(Math.sqrt(1-Math.pow(-2*e+2,2))+1)/2},e.easing.easeInElastic=e.easing.easeInElastic||function(e){return 0===e?0:1===e?1:-Math.pow(2,10*e-10)*Math.sin((10*e-10.75)*(2*Math.PI/3))},e.easing.easeOutElastic=e.easing.easeOutElastic||function(e){return 0===e?0:1===e?1:Math.pow(2,-10*e)*Math.sin((10*e-.75)*(2*Math.PI/3))+1},e.easing.easeInOutElastic=e.easing.easeInOutElastic||function(e){return 0===e?0:1===e?1:.5>e?-(Math.pow(2,20*e-10)*Math.sin((20*e-11.125)*(2*Math.PI/4.5)))/2:Math.pow(2,-20*e+10)*Math.sin((20*e-11.125)*(2*Math.PI/4.5))/2+1},e.easing.easeInBack=e.easing.easeInBack||function(e){return 2.70158*e*e*e-1.70158*e*e},e.easing.easeOutBack=e.easing.easeOutBack||function(e){return 1+2.70158*Math.pow(e-1,3)+1.70158*Math.pow(e-1,2)},e.easing.easeInOutBack=e.easing.easeInOutBack||function(e){return.5>e?Math.pow(2*e,2)*(7.189819*e-2.5949095)/2:(Math.pow(2*e-2,2)*(3.5949095*(2*e-2)+2.5949095)+2)/2},e.easing.easeInBounce=e.easing.easeInBounce||function(e){return 1-t(1-e)},e.easing.easeOutBounce=e.easing.easeOutBounce||t,e.easing.easeInOutBounce=e.easing.easeInOutBounce||function(e){return.5>e?(1-t(1-2*e))/2:(1+t(2*e-1))/2}}};I._easing.call(),e.fn[p]=function(t){return S[t]?S[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist"):S.init.apply(this,arguments)},e[p]=function(t){return S[t]?S[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?void e.error("Method "+t+" does not exist"):S.init.apply(this,arguments)},e[p].defaults=v}(jQuery,window,document);
/*!
 * @name        easyzoom
 * @version     2.5.2
 */
!function(t,e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],function(t){e(t)}):"object"==typeof module&&module.exports?module.exports=t.EasyZoom=e(require("jquery")):t.EasyZoom=e(t.jQuery)}(this,function(i){"use strict";var c,d,l,p,u,f,o={loadingNotice:"Loading image",errorNotice:"The image could not be loaded",errorDuration:2500,linkAttribute:"href",preventClicks:!0,beforeShow:i.noop,beforeHide:i.noop,onShow:i.noop,onHide:i.noop,onMove:i.noop};function s(t,e){this.$target=i(t),this.opts=i.extend({},o,e,this.$target.data()),void 0===this.isOpen&&this._init()}return s.prototype._init=function(){this.$link=this.$target.find("a"),this.$image=this.$target.find("img"),this.$flyout=i('<div class="easyzoom-flyout" />'),this.$notice=i('<div class="easyzoom-notice" />'),this.$target.on({"mousemove.easyzoom touchmove.easyzoom":i.proxy(this._onMove,this),"mouseleave.easyzoom touchend.easyzoom":i.proxy(this._onLeave,this),"mouseenter.easyzoom touchstart.easyzoom":i.proxy(this._onEnter,this)}),this.opts.preventClicks&&this.$target.on("click.easyzoom",function(t){t.preventDefault()})},s.prototype.show=function(t,e){var o=this;if(!1!==this.opts.beforeShow.call(this)){if(!this.isReady)return this._loadImage(this.$link.attr(this.opts.linkAttribute),function(){!o.isMouseOver&&e||o.show(t)});this.$target.append(this.$flyout);var i=this.$target.outerWidth(),s=this.$target.outerHeight(),h=this.$flyout.width(),n=this.$flyout.height(),a=this.$zoom.width(),r=this.$zoom.height();(c=a-h)<0&&(c=0),(d=r-n)<0&&(d=0),l=c/i,p=d/s,this.isOpen=!0,this.opts.onShow.call(this),t&&this._move(t)}},s.prototype._onEnter=function(t){var e=t.originalEvent.touches;this.isMouseOver=!0,e&&1!=e.length||(t.preventDefault(),this.show(t,!0))},s.prototype._onMove=function(t){this.isOpen&&(t.preventDefault(),this._move(t))},s.prototype._onLeave=function(){this.isMouseOver=!1,this.isOpen&&this.hide()},s.prototype._onLoad=function(t){t.currentTarget.width&&(this.isReady=!0,this.$notice.detach(),this.$flyout.html(this.$zoom),this.$target.removeClass("is-loading").addClass("is-ready"),t.data.call&&t.data())},s.prototype._onError=function(){var t=this;this.$notice.text(this.opts.errorNotice),this.$target.removeClass("is-loading").addClass("is-error"),this.detachNotice=setTimeout(function(){t.$notice.detach(),t.detachNotice=null},this.opts.errorDuration)},s.prototype._loadImage=function(t,e){var o=new Image;this.$target.addClass("is-loading").append(this.$notice.text(this.opts.loadingNotice)),this.$zoom=i(o).on("error",i.proxy(this._onError,this)).on("load",e,i.proxy(this._onLoad,this)),o.style.position="absolute",o.src=t},s.prototype._move=function(t){if(0===t.type.indexOf("touch")){var e=t.touches||t.originalEvent.touches;u=e[0].pageX,f=e[0].pageY}else u=t.pageX||u,f=t.pageY||f;var o=this.$target.offset(),i=f-o.top,s=u-o.left,h=Math.ceil(i*p),n=Math.ceil(s*l);if(n<0||h<0||c<n||d<h)this.hide();else{var a=-1*h,r=-1*n;this.$zoom.css({top:a,left:r}),this.opts.onMove.call(this,a,r)}},s.prototype.hide=function(){this.isOpen&&!1!==this.opts.beforeHide.call(this)&&(this.$flyout.detach(),this.isOpen=!1,this.opts.onHide.call(this))},s.prototype.swap=function(t,e,o){this.hide(),this.isReady=!1,this.detachNotice&&clearTimeout(this.detachNotice),this.$notice.parent().length&&this.$notice.detach(),this.$target.removeClass("is-loading is-ready is-error"),this.$image.attr({src:t,srcset:i.isArray(o)?o.join():o}),this.$link.attr(this.opts.linkAttribute,e)},s.prototype.teardown=function(){this.hide(),this.$target.off(".easyzoom").removeClass("is-loading is-ready is-error"),this.detachNotice&&clearTimeout(this.detachNotice),delete this.$link,delete this.$zoom,delete this.$image,delete this.$notice,delete this.$flyout,delete this.isOpen,delete this.isReady},i.fn.easyZoom=function(e){return this.each(function(){var t=i.data(this,"easyZoom");t?void 0===t.isOpen&&t._init():i.data(this,"easyZoom",new s(this,e))})},s}); 

/**
 * Slick Lightbox JS
 */
(function(f){"function"===typeof define&&define.amd?define(["jquery"],f):"undefined"!==typeof exports?module.exports=f(require("jquery")):f(jQuery)})(function(f){var g=window.Slick||{};g=function(){var a=0;return function(b,c){this.defaults={accessibility:!0,adaptiveHeight:!1,appendArrows:f(b),appendDots:f(b),arrows:!0,asNavFor:null,prevArrow:'<button class="slick-prev" aria-label="Previous" type="button">Previous</button>',nextArrow:'<button class="slick-next" aria-label="Next" type="button">Next</button>',
autoplay:!1,autoplaySpeed:3E3,centerMode:!1,centerPadding:"50px",cssEase:"ease",customPaging:function(d,h){return f('<button type="button" />').text(h+1)},dots:!1,dotsClass:"slick-dots",draggable:!0,easing:"linear",edgeFriction:.35,fade:!1,focusOnSelect:!1,focusOnChange:!1,infinite:!0,initialSlide:0,lazyLoad:"ondemand",mobileFirst:!1,pauseOnHover:!0,pauseOnFocus:!0,pauseOnDotsHover:!1,respondTo:"window",responsive:null,rows:1,rtl:!1,slide:"",slidesPerRow:1,slidesToShow:1,slidesToScroll:1,speed:500,
swipe:!0,swipeToSlide:!1,touchMove:!0,touchThreshold:5,useCSS:!0,useTransform:!0,variableWidth:!1,vertical:!1,verticalSwiping:!1,waitForAnimate:!0,zIndex:1E3};this.initials={animating:!1,dragging:!1,autoPlayTimer:null,currentDirection:0,currentLeft:null,currentSlide:0,direction:1,$dots:null,listWidth:null,listHeight:null,loadIndex:0,$nextArrow:null,$prevArrow:null,scrolling:!1,slideCount:null,slideWidth:null,$slideTrack:null,$slides:null,sliding:!1,slideOffset:0,swipeLeft:null,swiping:!1,$list:null,
touchObject:{},transformsEnabled:!1,unslicked:!1};f.extend(this,this.initials);this.animProp=this.animType=this.activeBreakpoint=null;this.breakpoints=[];this.breakpointSettings=[];this.interrupted=this.focussed=this.cssTransitions=!1;this.hidden="hidden";this.paused=!0;this.respondTo=this.positionProp=null;this.rowCount=1;this.shouldClick=!0;this.$slider=f(b);this.transitionType=this.transformType=this.$slidesCache=null;this.visibilityChange="visibilitychange";this.windowWidth=0;this.windowTimer=
null;var e=f(b).data("slick")||{};this.options=f.extend({},this.defaults,c,e);this.currentSlide=this.options.initialSlide;this.originalSettings=this.options;"undefined"!==typeof document.mozHidden?(this.hidden="mozHidden",this.visibilityChange="mozvisibilitychange"):"undefined"!==typeof document.webkitHidden&&(this.hidden="webkitHidden",this.visibilityChange="webkitvisibilitychange");this.autoPlay=f.proxy(this.autoPlay,this);this.autoPlayClear=f.proxy(this.autoPlayClear,this);this.autoPlayIterator=
f.proxy(this.autoPlayIterator,this);this.changeSlide=f.proxy(this.changeSlide,this);this.clickHandler=f.proxy(this.clickHandler,this);this.selectHandler=f.proxy(this.selectHandler,this);this.setPosition=f.proxy(this.setPosition,this);this.swipeHandler=f.proxy(this.swipeHandler,this);this.dragHandler=f.proxy(this.dragHandler,this);this.keyHandler=f.proxy(this.keyHandler,this);this.instanceUid=a++;this.htmlExpr=/^(?:\s*(<[\w\W]+>)[^>]*)$/;this.registerBreakpoints();this.init(!0)}}();g.prototype.activateADA=
function(){this.$slideTrack.find(".slick-active").attr({"aria-hidden":"false"}).find("a, input, button, select").attr({tabindex:"0"})};g.prototype.addSlide=g.prototype.slickAdd=function(a,b,c){if("boolean"===typeof b)c=b,b=null;else if(0>b||b>=this.slideCount)return!1;this.unload();"number"===typeof b?0===b&&0===this.$slides.length?f(a).appendTo(this.$slideTrack):c?f(a).insertBefore(this.$slides.eq(b)):f(a).insertAfter(this.$slides.eq(b)):!0===c?f(a).prependTo(this.$slideTrack):f(a).appendTo(this.$slideTrack);
this.$slides=this.$slideTrack.children(this.options.slide);this.$slideTrack.children(this.options.slide).detach();this.$slideTrack.append(this.$slides);this.$slides.each(function(e,d){f(d).attr("data-slick-index",e)});this.$slidesCache=this.$slides;this.reinit()};g.prototype.animateHeight=function(){if(1===this.options.slidesToShow&&!0===this.options.adaptiveHeight&&!1===this.options.vertical){var a=this.$slides.eq(this.currentSlide).outerHeight(!0);this.$list.animate({height:a},this.options.speed)}};
g.prototype.animateSlide=function(a,b){var c={},e=this;e.animateHeight();!0===e.options.rtl&&!1===e.options.vertical&&(a=-a);!1===e.transformsEnabled?!1===e.options.vertical?e.$slideTrack.animate({left:a},e.options.speed,e.options.easing,b):e.$slideTrack.animate({top:a},e.options.speed,e.options.easing,b):!1===e.cssTransitions?(!0===e.options.rtl&&(e.currentLeft=-e.currentLeft),f({animStart:e.currentLeft}).animate({animStart:a},{duration:e.options.speed,easing:e.options.easing,step:function(d){d=
Math.ceil(d);c[e.animType]=!1===e.options.vertical?"translate("+d+"px, 0px)":"translate(0px,"+d+"px)";e.$slideTrack.css(c)},complete:function(){b&&b.call()}})):(e.applyTransition(),a=Math.ceil(a),c[e.animType]=!1===e.options.vertical?"translate3d("+a+"px, 0px, 0px)":"translate3d(0px,"+a+"px, 0px)",e.$slideTrack.css(c),b&&setTimeout(function(){e.disableTransition();b.call()},e.options.speed))};g.prototype.getNavTarget=function(){var a=this.options.asNavFor;a&&null!==a&&(a=f(a).not(this.$slider));return a};
g.prototype.asNavFor=function(a){var b=this.getNavTarget();null!==b&&"object"===typeof b&&b.each(function(){var c=f(this).slick("getSlick");c.unslicked||c.slideHandler(a,!0)})};g.prototype.applyTransition=function(a){var b={};b[this.transitionType]=!1===this.options.fade?this.transformType+" "+this.options.speed+"ms "+this.options.cssEase:"opacity "+this.options.speed+"ms "+this.options.cssEase;!1===this.options.fade?this.$slideTrack.css(b):this.$slides.eq(a).css(b)};g.prototype.autoPlay=function(){this.autoPlayClear();
this.slideCount>this.options.slidesToShow&&(this.autoPlayTimer=setInterval(this.autoPlayIterator,this.options.autoplaySpeed))};g.prototype.autoPlayClear=function(){this.autoPlayTimer&&clearInterval(this.autoPlayTimer)};g.prototype.autoPlayIterator=function(){var a=this.currentSlide+this.options.slidesToScroll;this.paused||this.interrupted||this.focussed||(!1===this.options.infinite&&(1===this.direction&&this.currentSlide+1===this.slideCount-1?this.direction=0:0===this.direction&&(a=this.currentSlide-
this.options.slidesToScroll,0===this.currentSlide-1&&(this.direction=1))),this.slideHandler(a))};g.prototype.buildArrows=function(){!0===this.options.arrows&&(this.$prevArrow=f(this.options.prevArrow).addClass("slick-arrow"),this.$nextArrow=f(this.options.nextArrow).addClass("slick-arrow"),this.slideCount>this.options.slidesToShow?(this.$prevArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),this.$nextArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"),this.htmlExpr.test(this.options.prevArrow)&&
this.$prevArrow.prependTo(this.options.appendArrows),this.htmlExpr.test(this.options.nextArrow)&&this.$nextArrow.appendTo(this.options.appendArrows),!0!==this.options.infinite&&this.$prevArrow.addClass("slick-disabled").attr("aria-disabled","true")):this.$prevArrow.add(this.$nextArrow).addClass("slick-hidden").attr({"aria-disabled":"true",tabindex:"-1"}))};g.prototype.buildDots=function(){var a;if(!0===this.options.dots&&this.slideCount>this.options.slidesToShow){this.$slider.addClass("slick-dotted");
var b=f("<ul />").addClass(this.options.dotsClass);for(a=0;a<=this.getDotCount();a+=1)b.append(f("<li />").append(this.options.customPaging.call(this,this,a)));this.$dots=b.appendTo(this.options.appendDots);this.$dots.find("li").first().addClass("slick-active")}};g.prototype.buildOut=function(){this.$slides=this.$slider.children(this.options.slide+":not(.slick-cloned)").addClass("slick-slide");this.slideCount=this.$slides.length;this.$slides.each(function(a,b){f(b).attr("data-slick-index",a).data("originalStyling",
f(b).attr("style")||"")});this.$slider.addClass("slick-slider");this.$slideTrack=0===this.slideCount?f('<div class="slick-track"/>').appendTo(this.$slider):this.$slides.wrapAll('<div class="slick-track"/>').parent();this.$list=this.$slideTrack.wrap('<div class="slick-list"/>').parent();this.$slideTrack.css("opacity",0);if(!0===this.options.centerMode||!0===this.options.swipeToSlide)this.options.slidesToScroll=1;f("img[data-lazy]",this.$slider).not("[src]").addClass("slick-loading");this.setupInfinite();
this.buildArrows();this.buildDots();this.updateDots();this.setSlideClasses("number"===typeof this.currentSlide?this.currentSlide:0);!0===this.options.draggable&&this.$list.addClass("draggable")};g.prototype.buildRows=function(){var a,b,c;var e=document.createDocumentFragment();var d=this.$slider.children();if(0<this.options.rows){var h=this.options.slidesPerRow*this.options.rows;var k=Math.ceil(d.length/h);for(a=0;a<k;a++){var m=document.createElement("div");for(b=0;b<this.options.rows;b++){var l=
document.createElement("div");for(c=0;c<this.options.slidesPerRow;c++){var n=a*h+(b*this.options.slidesPerRow+c);d.get(n)&&l.appendChild(d.get(n))}m.appendChild(l)}e.appendChild(m)}this.$slider.empty().append(e);this.$slider.children().children().children().css({width:100/this.options.slidesPerRow+"%",display:"inline-block"})}};g.prototype.checkResponsive=function(a,b){var c,e,d=!1;var h=this.$slider.width();var k=window.innerWidth||f(window).width();"window"===this.respondTo?e=k:"slider"===this.respondTo?
e=h:"min"===this.respondTo&&(e=Math.min(k,h));if(this.options.responsive&&this.options.responsive.length&&null!==this.options.responsive){h=null;for(c in this.breakpoints)this.breakpoints.hasOwnProperty(c)&&(!1===this.originalSettings.mobileFirst?e<this.breakpoints[c]&&(h=this.breakpoints[c]):e>this.breakpoints[c]&&(h=this.breakpoints[c]));if(null!==h)if(null!==this.activeBreakpoint){if(h!==this.activeBreakpoint||b)this.activeBreakpoint=h,"unslick"===this.breakpointSettings[h]?this.unslick(h):(this.options=
f.extend({},this.originalSettings,this.breakpointSettings[h]),!0===a&&(this.currentSlide=this.options.initialSlide),this.refresh(a)),d=h}else this.activeBreakpoint=h,"unslick"===this.breakpointSettings[h]?this.unslick(h):(this.options=f.extend({},this.originalSettings,this.breakpointSettings[h]),!0===a&&(this.currentSlide=this.options.initialSlide),this.refresh(a)),d=h;else null!==this.activeBreakpoint&&(this.activeBreakpoint=null,this.options=this.originalSettings,!0===a&&(this.currentSlide=this.options.initialSlide),
this.refresh(a),d=h);a||!1===d||this.$slider.trigger("breakpoint",[this,d])}};g.prototype.changeSlide=function(a,b){var c=f(a.currentTarget);c.is("a")&&a.preventDefault();c.is("li")||(c=c.closest("li"));var e=0!==this.slideCount%this.options.slidesToScroll?0:(this.slideCount-this.currentSlide)%this.options.slidesToScroll;switch(a.data.message){case "previous":c=0===e?this.options.slidesToScroll:this.options.slidesToShow-e;this.slideCount>this.options.slidesToShow&&this.slideHandler(this.currentSlide-
c,!1,b);break;case "next":c=0===e?this.options.slidesToScroll:e;this.slideCount>this.options.slidesToShow&&this.slideHandler(this.currentSlide+c,!1,b);break;case "index":e=0===a.data.index?0:a.data.index||c.index()*this.options.slidesToScroll,this.slideHandler(this.checkNavigable(e),!1,b),c.children().trigger("focus")}};g.prototype.checkNavigable=function(a){var b=this.getNavigableIndexes();var c=0;if(a>b[b.length-1])a=b[b.length-1];else for(var e in b){if(a<b[e]){a=c;break}c=b[e]}return a};g.prototype.cleanUpEvents=
function(){this.options.dots&&null!==this.$dots&&(f("li",this.$dots).off("click.slick",this.changeSlide).off("mouseenter.slick",f.proxy(this.interrupt,this,!0)).off("mouseleave.slick",f.proxy(this.interrupt,this,!1)),!0===this.options.accessibility&&this.$dots.off("keydown.slick",this.keyHandler));this.$slider.off("focus.slick blur.slick");!0===this.options.arrows&&this.slideCount>this.options.slidesToShow&&(this.$prevArrow&&this.$prevArrow.off("click.slick",this.changeSlide),this.$nextArrow&&this.$nextArrow.off("click.slick",
this.changeSlide),!0===this.options.accessibility&&(this.$prevArrow&&this.$prevArrow.off("keydown.slick",this.keyHandler),this.$nextArrow&&this.$nextArrow.off("keydown.slick",this.keyHandler)));this.$list.off("touchstart.slick mousedown.slick",this.swipeHandler);this.$list.off("touchmove.slick mousemove.slick",this.swipeHandler);this.$list.off("touchend.slick mouseup.slick",this.swipeHandler);this.$list.off("touchcancel.slick mouseleave.slick",this.swipeHandler);this.$list.off("click.slick",this.clickHandler);
f(document).off(this.visibilityChange,this.visibility);this.cleanUpSlideEvents();!0===this.options.accessibility&&this.$list.off("keydown.slick",this.keyHandler);!0===this.options.focusOnSelect&&f(this.$slideTrack).children().off("click.slick",this.selectHandler);f(window).off("orientationchange.slick.slick-"+this.instanceUid,this.orientationChange);f(window).off("resize.slick.slick-"+this.instanceUid,this.resize);f("[draggable!=true]",this.$slideTrack).off("dragstart",this.preventDefault);f(window).off("load.slick.slick-"+
this.instanceUid,this.setPosition)};g.prototype.cleanUpSlideEvents=function(){this.$list.off("mouseenter.slick",f.proxy(this.interrupt,this,!0));this.$list.off("mouseleave.slick",f.proxy(this.interrupt,this,!1))};g.prototype.cleanUpRows=function(){if(0<this.options.rows){var a=this.$slides.children().children();a.removeAttr("style");this.$slider.empty().append(a)}};g.prototype.clickHandler=function(a){!1===this.shouldClick&&(a.stopImmediatePropagation(),a.stopPropagation(),a.preventDefault())};g.prototype.destroy=
function(a){this.autoPlayClear();this.touchObject={};this.cleanUpEvents();f(".slick-cloned",this.$slider).detach();this.$dots&&this.$dots.remove();this.$prevArrow&&this.$prevArrow.length&&(this.$prevArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display",""),this.htmlExpr.test(this.options.prevArrow)&&this.$prevArrow.remove());this.$nextArrow&&this.$nextArrow.length&&(this.$nextArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display",
""),this.htmlExpr.test(this.options.nextArrow)&&this.$nextArrow.remove());this.$slides&&(this.$slides.removeClass("slick-slide slick-active slick-center slick-visible slick-current").removeAttr("aria-hidden").removeAttr("data-slick-index").each(function(){f(this).attr("style",f(this).data("originalStyling"))}),this.$slideTrack.children(this.options.slide).detach(),this.$slideTrack.detach(),this.$list.detach(),this.$slider.append(this.$slides));this.cleanUpRows();this.$slider.removeClass("slick-slider");
this.$slider.removeClass("slick-initialized");this.$slider.removeClass("slick-dotted");this.unslicked=!0;a||this.$slider.trigger("destroy",[this])};g.prototype.disableTransition=function(a){var b={};b[this.transitionType]="";!1===this.options.fade?this.$slideTrack.css(b):this.$slides.eq(a).css(b)};g.prototype.fadeSlide=function(a,b){var c=this;!1===c.cssTransitions?(c.$slides.eq(a).css({zIndex:c.options.zIndex}),c.$slides.eq(a).animate({opacity:1},c.options.speed,c.options.easing,b)):(c.applyTransition(a),
c.$slides.eq(a).css({opacity:1,zIndex:c.options.zIndex}),b&&setTimeout(function(){c.disableTransition(a);b.call()},c.options.speed))};g.prototype.fadeSlideOut=function(a){!1===this.cssTransitions?this.$slides.eq(a).animate({opacity:0,zIndex:this.options.zIndex-2},this.options.speed,this.options.easing):(this.applyTransition(a),this.$slides.eq(a).css({opacity:0,zIndex:this.options.zIndex-2}))};g.prototype.filterSlides=g.prototype.slickFilter=function(a){null!==a&&(this.$slidesCache=this.$slides,this.unload(),
this.$slideTrack.children(this.options.slide).detach(),this.$slidesCache.filter(a).appendTo(this.$slideTrack),this.reinit())};g.prototype.focusHandler=function(){var a=this;a.$slider.off("focus.slick blur.slick").on("focus.slick blur.slick","*",function(b){b.stopImmediatePropagation();var c=f(this);setTimeout(function(){a.options.pauseOnFocus&&(a.focussed=c.is(":focus"),a.autoPlay())},0)})};g.prototype.getCurrent=g.prototype.slickCurrentSlide=function(){return this.currentSlide};g.prototype.getDotCount=
function(){var a=0,b=0,c=0;if(!0===this.options.infinite)if(this.slideCount<=this.options.slidesToShow)++c;else for(;a<this.slideCount;)++c,a=b+this.options.slidesToScroll,b+=this.options.slidesToScroll<=this.options.slidesToShow?this.options.slidesToScroll:this.options.slidesToShow;else if(!0===this.options.centerMode)c=this.slideCount;else if(this.options.asNavFor)for(;a<this.slideCount;)++c,a=b+this.options.slidesToScroll,b+=this.options.slidesToScroll<=this.options.slidesToShow?this.options.slidesToScroll:
this.options.slidesToShow;else c=1+Math.ceil((this.slideCount-this.options.slidesToShow)/this.options.slidesToScroll);return c-1};g.prototype.getLeft=function(a){var b=0;this.slideOffset=0;var c=this.$slides.first().outerHeight(!0);!0===this.options.infinite?(this.slideCount>this.options.slidesToShow&&(this.slideOffset=this.slideWidth*this.options.slidesToShow*-1,b=-1,!0===this.options.vertical&&!0===this.options.centerMode&&(2===this.options.slidesToShow?b=-1.5:1===this.options.slidesToShow&&(b=
-2)),b*=c*this.options.slidesToShow),0!==this.slideCount%this.options.slidesToScroll&&a+this.options.slidesToScroll>this.slideCount&&this.slideCount>this.options.slidesToShow&&(a>this.slideCount?(this.slideOffset=(this.options.slidesToShow-(a-this.slideCount))*this.slideWidth*-1,b=(this.options.slidesToShow-(a-this.slideCount))*c*-1):(this.slideOffset=this.slideCount%this.options.slidesToScroll*this.slideWidth*-1,b=this.slideCount%this.options.slidesToScroll*c*-1))):a+this.options.slidesToShow>this.slideCount&&
(this.slideOffset=(a+this.options.slidesToShow-this.slideCount)*this.slideWidth,b=(a+this.options.slidesToShow-this.slideCount)*c);this.slideCount<=this.options.slidesToShow&&(b=this.slideOffset=0);!0===this.options.centerMode&&this.slideCount<=this.options.slidesToShow?this.slideOffset=this.slideWidth*Math.floor(this.options.slidesToShow)/2-this.slideWidth*this.slideCount/2:!0===this.options.centerMode&&!0===this.options.infinite?this.slideOffset+=this.slideWidth*Math.floor(this.options.slidesToShow/
2)-this.slideWidth:!0===this.options.centerMode&&(this.slideOffset=0,this.slideOffset+=this.slideWidth*Math.floor(this.options.slidesToShow/2));c=!1===this.options.vertical?a*this.slideWidth*-1+this.slideOffset:a*c*-1+b;!0===this.options.variableWidth&&(b=this.slideCount<=this.options.slidesToShow||!1===this.options.infinite?this.$slideTrack.children(".slick-slide").eq(a):this.$slideTrack.children(".slick-slide").eq(a+this.options.slidesToShow),c=!0===this.options.rtl?b[0]?-1*(this.$slideTrack.width()-
b[0].offsetLeft-b.width()):0:b[0]?-1*b[0].offsetLeft:0,!0===this.options.centerMode&&(b=this.slideCount<=this.options.slidesToShow||!1===this.options.infinite?this.$slideTrack.children(".slick-slide").eq(a):this.$slideTrack.children(".slick-slide").eq(a+this.options.slidesToShow+1),c=!0===this.options.rtl?b[0]?-1*(this.$slideTrack.width()-b[0].offsetLeft-b.width()):0:b[0]?-1*b[0].offsetLeft:0,c+=(this.$list.width()-b.outerWidth())/2));return c};g.prototype.getOption=g.prototype.slickGetOption=function(a){return this.options[a]};
g.prototype.getNavigableIndexes=function(){var a=0,b=0,c=[];if(!1===this.options.infinite)var e=this.slideCount;else a=-1*this.options.slidesToScroll,b=-1*this.options.slidesToScroll,e=2*this.slideCount;for(;a<e;)c.push(a),a=b+this.options.slidesToScroll,b+=this.options.slidesToScroll<=this.options.slidesToShow?this.options.slidesToScroll:this.options.slidesToShow;return c};g.prototype.getSlick=function(){return this};g.prototype.getSlideCount=function(){var a=this,b,c;var e=!0===a.options.centerMode?
a.slideWidth*Math.floor(a.options.slidesToShow/2):0;return!0===a.options.swipeToSlide?(a.$slideTrack.find(".slick-slide").each(function(d,h){if(h.offsetLeft-e+f(h).outerWidth()/2>-1*a.swipeLeft)return c=h,!1}),b=Math.abs(f(c).attr("data-slick-index")-a.currentSlide)||1):a.options.slidesToScroll};g.prototype.goTo=g.prototype.slickGoTo=function(a,b){this.changeSlide({data:{message:"index",index:parseInt(a)}},b)};g.prototype.init=function(a){f(this.$slider).hasClass("slick-initialized")||(f(this.$slider).addClass("slick-initialized"),
this.buildRows(),this.buildOut(),this.setProps(),this.startLoad(),this.loadSlider(),this.initializeEvents(),this.updateArrows(),this.updateDots(),this.checkResponsive(!0),this.focusHandler());a&&this.$slider.trigger("init",[this]);!0===this.options.accessibility&&this.initADA();this.options.autoplay&&(this.paused=!1,this.autoPlay())};g.prototype.initADA=function(){var a=this,b=Math.ceil(a.slideCount/a.options.slidesToShow),c=a.getNavigableIndexes().filter(function(h){return 0<=h&&h<a.slideCount});
a.$slides.add(a.$slideTrack.find(".slick-cloned")).attr({"aria-hidden":"true",tabindex:"-1"}).find("a, input, button, select").attr({tabindex:"-1"});null!==a.$dots&&(a.$slides.not(a.$slideTrack.find(".slick-cloned")).each(function(h){var k=c.indexOf(h);f(this).attr({role:"tabpanel",id:"slick-slide"+a.instanceUid+h,tabindex:-1});-1!==k&&(h="slick-slide-control"+a.instanceUid+k,f("#"+h).length&&f(this).attr({"aria-describedby":h}))}),a.$dots.attr("role","tablist").find("li").each(function(h){var k=
c[h];f(this).attr({role:"presentation"});f(this).find("button").first().attr({role:"tab",id:"slick-slide-control"+a.instanceUid+h,"aria-controls":"slick-slide"+a.instanceUid+k,"aria-label":h+1+" of "+b,"aria-selected":null,tabindex:"-1"})}).eq(a.currentSlide).find("button").attr({"aria-selected":"true",tabindex:"0"}).end());for(var e=a.currentSlide,d=e+a.options.slidesToShow;e<d;e++)a.options.focusOnChange?a.$slides.eq(e).attr({tabindex:"0"}):a.$slides.eq(e).removeAttr("tabindex");a.activateADA()};
g.prototype.initArrowEvents=function(){!0===this.options.arrows&&this.slideCount>this.options.slidesToShow&&(this.$prevArrow.off("click.slick").on("click.slick",{message:"previous"},this.changeSlide),this.$nextArrow.off("click.slick").on("click.slick",{message:"next"},this.changeSlide),!0===this.options.accessibility&&(this.$prevArrow.on("keydown.slick",this.keyHandler),this.$nextArrow.on("keydown.slick",this.keyHandler)))};g.prototype.initDotEvents=function(){if(!0===this.options.dots&&this.slideCount>
this.options.slidesToShow&&(f("li",this.$dots).on("click.slick",{message:"index"},this.changeSlide),!0===this.options.accessibility))this.$dots.on("keydown.slick",this.keyHandler);if(!0===this.options.dots&&!0===this.options.pauseOnDotsHover&&this.slideCount>this.options.slidesToShow)f("li",this.$dots).on("mouseenter.slick",f.proxy(this.interrupt,this,!0)).on("mouseleave.slick",f.proxy(this.interrupt,this,!1))};g.prototype.initSlideEvents=function(){this.options.pauseOnHover&&(this.$list.on("mouseenter.slick",
f.proxy(this.interrupt,this,!0)),this.$list.on("mouseleave.slick",f.proxy(this.interrupt,this,!1)))};g.prototype.initializeEvents=function(){this.initArrowEvents();this.initDotEvents();this.initSlideEvents();this.$list.on("touchstart.slick mousedown.slick",{action:"start"},this.swipeHandler);this.$list.on("touchmove.slick mousemove.slick",{action:"move"},this.swipeHandler);this.$list.on("touchend.slick mouseup.slick",{action:"end"},this.swipeHandler);this.$list.on("touchcancel.slick mouseleave.slick",
{action:"end"},this.swipeHandler);this.$list.on("click.slick",this.clickHandler);f(document).on(this.visibilityChange,f.proxy(this.visibility,this));if(!0===this.options.accessibility)this.$list.on("keydown.slick",this.keyHandler);if(!0===this.options.focusOnSelect)f(this.$slideTrack).children().on("click.slick",this.selectHandler);f(window).on("orientationchange.slick.slick-"+this.instanceUid,f.proxy(this.orientationChange,this));f(window).on("resize.slick.slick-"+this.instanceUid,f.proxy(this.resize,
this));f("[draggable!=true]",this.$slideTrack).on("dragstart",this.preventDefault);f(window).on("load.slick.slick-"+this.instanceUid,this.setPosition);f(this.setPosition)};g.prototype.initUI=function(){!0===this.options.arrows&&this.slideCount>this.options.slidesToShow&&(this.$prevArrow.show(),this.$nextArrow.show());!0===this.options.dots&&this.slideCount>this.options.slidesToShow&&this.$dots.show()};g.prototype.keyHandler=function(a){a.target.tagName.match("TEXTAREA|INPUT|SELECT")||(37===a.keyCode&&
!0===this.options.accessibility?this.changeSlide({data:{message:!0===this.options.rtl?"next":"previous"}}):39===a.keyCode&&!0===this.options.accessibility&&this.changeSlide({data:{message:!0===this.options.rtl?"previous":"next"}}))};g.prototype.lazyLoad=function(){function a(m){f("img[data-lazy]",m).each(function(){var l=f(this),n=f(this).attr("data-lazy"),p=f(this).attr("data-srcset"),r=f(this).attr("data-sizes")||b.$slider.attr("data-sizes"),q=document.createElement("img");q.onload=function(){l.animate({opacity:0},
100,function(){p&&(l.attr("srcset",p),r&&l.attr("sizes",r));l.attr("src",n).animate({opacity:1},200,function(){l.removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading")});b.$slider.trigger("lazyLoaded",[b,l,n])})};q.onerror=function(){l.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error");b.$slider.trigger("lazyLoadError",[b,l,n])};q.src=n})}var b=this;if(!0===b.options.centerMode)if(!0===b.options.infinite){var c=b.currentSlide+(b.options.slidesToShow/
2+1);var e=c+b.options.slidesToShow+2}else c=Math.max(0,b.currentSlide-(b.options.slidesToShow/2+1)),e=2+(b.options.slidesToShow/2+1)+b.currentSlide;else c=b.options.infinite?b.options.slidesToShow+b.currentSlide:b.currentSlide,e=Math.ceil(c+b.options.slidesToShow),!0===b.options.fade&&(0<c&&c--,e<=b.slideCount&&e++);var d=b.$slider.find(".slick-slide").slice(c,e);if("anticipated"===b.options.lazyLoad){--c;for(var h=b.$slider.find(".slick-slide"),k=0;k<b.options.slidesToScroll;k++)0>c&&(c=b.slideCount-
1),d=d.add(h.eq(c)),d=d.add(h.eq(e)),c--,e++}a(d);b.slideCount<=b.options.slidesToShow?(d=b.$slider.find(".slick-slide"),a(d)):b.currentSlide>=b.slideCount-b.options.slidesToShow?(d=b.$slider.find(".slick-cloned").slice(0,b.options.slidesToShow),a(d)):0===b.currentSlide&&(d=b.$slider.find(".slick-cloned").slice(-1*b.options.slidesToShow),a(d))};g.prototype.loadSlider=function(){this.setPosition();this.$slideTrack.css({opacity:1});this.$slider.removeClass("slick-loading");this.initUI();"progressive"===
this.options.lazyLoad&&this.progressiveLazyLoad()};g.prototype.next=g.prototype.slickNext=function(){this.changeSlide({data:{message:"next"}})};g.prototype.orientationChange=function(){this.checkResponsive();this.setPosition()};g.prototype.pause=g.prototype.slickPause=function(){this.autoPlayClear();this.paused=!0};g.prototype.play=g.prototype.slickPlay=function(){this.autoPlay();this.options.autoplay=!0;this.interrupted=this.focussed=this.paused=!1};g.prototype.postSlide=function(a){this.unslicked||
(this.$slider.trigger("afterChange",[this,a]),this.animating=!1,this.slideCount>this.options.slidesToShow&&this.setPosition(),this.swipeLeft=null,this.options.autoplay&&this.autoPlay(),!0===this.options.accessibility&&(this.initADA(),this.options.focusOnChange&&f(this.$slides.get(this.currentSlide)).attr("tabindex",0).focus()))};g.prototype.prev=g.prototype.slickPrev=function(){this.changeSlide({data:{message:"previous"}})};g.prototype.preventDefault=function(a){a.preventDefault()};g.prototype.progressiveLazyLoad=
function(a){a=a||1;var b=this,c=f("img[data-lazy]",b.$slider);if(c.length){var e=c.first();var d=e.attr("data-lazy");var h=e.attr("data-srcset");var k=e.attr("data-sizes")||b.$slider.attr("data-sizes");c=document.createElement("img");c.onload=function(){h&&(e.attr("srcset",h),k&&e.attr("sizes",k));e.attr("src",d).removeAttr("data-lazy data-srcset data-sizes").removeClass("slick-loading");!0===b.options.adaptiveHeight&&b.setPosition();b.$slider.trigger("lazyLoaded",[b,e,d]);b.progressiveLazyLoad()};
c.onerror=function(){3>a?setTimeout(function(){b.progressiveLazyLoad(a+1)},500):(e.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"),b.$slider.trigger("lazyLoadError",[b,e,d]),b.progressiveLazyLoad())};c.src=d}else b.$slider.trigger("allImagesLoaded",[b])};g.prototype.refresh=function(a){var b=this.slideCount-this.options.slidesToShow;!this.options.infinite&&this.currentSlide>b&&(this.currentSlide=b);this.slideCount<=this.options.slidesToShow&&(this.currentSlide=
0);b=this.currentSlide;this.destroy(!0);f.extend(this,this.initials,{currentSlide:b});this.init();a||this.changeSlide({data:{message:"index",index:b}},!1)};g.prototype.registerBreakpoints=function(){var a=this,b,c,e=a.options.responsive||null;if("array"===f.type(e)&&e.length){a.respondTo=a.options.respondTo||"window";for(b in e){var d=a.breakpoints.length-1;if(e.hasOwnProperty(b)){for(c=e[b].breakpoint;0<=d;)a.breakpoints[d]&&a.breakpoints[d]===c&&a.breakpoints.splice(d,1),d--;a.breakpoints.push(c);
a.breakpointSettings[c]=e[b].settings}}a.breakpoints.sort(function(h,k){return a.options.mobileFirst?h-k:k-h})}};g.prototype.reinit=function(){this.$slides=this.$slideTrack.children(this.options.slide).addClass("slick-slide");this.slideCount=this.$slides.length;this.currentSlide>=this.slideCount&&0!==this.currentSlide&&(this.currentSlide-=this.options.slidesToScroll);this.slideCount<=this.options.slidesToShow&&(this.currentSlide=0);this.registerBreakpoints();this.setProps();this.setupInfinite();this.buildArrows();
this.updateArrows();this.initArrowEvents();this.buildDots();this.updateDots();this.initDotEvents();this.cleanUpSlideEvents();this.initSlideEvents();this.checkResponsive(!1,!0);if(!0===this.options.focusOnSelect)f(this.$slideTrack).children().on("click.slick",this.selectHandler);this.setSlideClasses("number"===typeof this.currentSlide?this.currentSlide:0);this.setPosition();this.focusHandler();this.paused=!this.options.autoplay;this.autoPlay();this.$slider.trigger("reInit",[this])};g.prototype.resize=
function(){var a=this;f(window).width()!==a.windowWidth&&(clearTimeout(a.windowDelay),a.windowDelay=window.setTimeout(function(){a.windowWidth=f(window).width();a.checkResponsive();a.unslicked||a.setPosition()},50))};g.prototype.removeSlide=g.prototype.slickRemove=function(a,b,c){a="boolean"===typeof a?!0===a?0:this.slideCount-1:!0===b?--a:a;if(1>this.slideCount||0>a||a>this.slideCount-1)return!1;this.unload();!0===c?this.$slideTrack.children().remove():this.$slideTrack.children(this.options.slide).eq(a).remove();
this.$slides=this.$slideTrack.children(this.options.slide);this.$slideTrack.children(this.options.slide).detach();this.$slideTrack.append(this.$slides);this.$slidesCache=this.$slides;this.reinit()};g.prototype.setCSS=function(a){var b={};!0===this.options.rtl&&(a=-a);var c="left"==this.positionProp?Math.ceil(a)+"px":"0px";var e="top"==this.positionProp?Math.ceil(a)+"px":"0px";b[this.positionProp]=a;!1!==this.transformsEnabled&&(b={},b[this.animType]=!1===this.cssTransitions?"translate("+c+", "+e+
")":"translate3d("+c+", "+e+", 0px)");this.$slideTrack.css(b)};g.prototype.setDimensions=function(){!1===this.options.vertical?!0===this.options.centerMode&&this.$list.css({padding:"0px "+this.options.centerPadding}):(this.$list.height(this.$slides.first().outerHeight(!0)*this.options.slidesToShow),!0===this.options.centerMode&&this.$list.css({padding:this.options.centerPadding+" 0px"}));this.listWidth=this.$list.width();this.listHeight=this.$list.height();!1===this.options.vertical&&!1===this.options.variableWidth?
(this.slideWidth=this.listWidth/this.options.slidesToShow,this.$slideTrack.width(Math.ceil(this.slideWidth*this.$slideTrack.children(".slick-slide").length))):!0===this.options.variableWidth?this.$slideTrack.width(5E3*this.slideCount):(this.slideWidth=Math.ceil(this.listWidth),this.$slideTrack.height(Math.ceil(this.$slides.first().outerHeight(!0)*this.$slideTrack.children(".slick-slide").length)));var a=this.$slides.first().outerWidth(!0)-this.$slides.first().width();!1===this.options.variableWidth&&
this.$slideTrack.children(".slick-slide").width(this.slideWidth-a)};g.prototype.setFade=function(){var a=this,b;a.$slides.each(function(c,e){b=a.slideWidth*c*-1;!0===a.options.rtl?f(e).css({position:"relative",right:b,top:0,zIndex:a.options.zIndex-2,opacity:0}):f(e).css({position:"relative",left:b,top:0,zIndex:a.options.zIndex-2,opacity:0})});a.$slides.eq(a.currentSlide).css({zIndex:a.options.zIndex-1,opacity:1})};g.prototype.setHeight=function(){if(1===this.options.slidesToShow&&!0===this.options.adaptiveHeight&&
!1===this.options.vertical){var a=this.$slides.eq(this.currentSlide).outerHeight(!0);this.$list.css("height",a)}};g.prototype.setOption=g.prototype.slickSetOption=function(a,b,c){var e=this,d,h=!1;if("object"===f.type(a)){var k=a;h=b;var m="multiple"}else if("string"===f.type(a)){k=a;var l=b;h=c;"responsive"===a&&"array"===f.type(b)?m="responsive":"undefined"!==typeof b&&(m="single")}if("single"===m)e.options[k]=l;else if("multiple"===m)f.each(k,function(n,p){e.options[n]=p});else if("responsive"===
m)for(d in l)if("array"!==f.type(e.options.responsive))e.options.responsive=[l[d]];else{for(a=e.options.responsive.length-1;0<=a;)e.options.responsive[a].breakpoint===l[d].breakpoint&&e.options.responsive.splice(a,1),a--;e.options.responsive.push(l[d])}h&&(e.unload(),e.reinit())};g.prototype.setPosition=function(){this.setDimensions();this.setHeight();!1===this.options.fade?this.setCSS(this.getLeft(this.currentSlide)):this.setFade();this.$slider.trigger("setPosition",[this])};g.prototype.setProps=
function(){var a=document.body.style;this.positionProp=!0===this.options.vertical?"top":"left";"top"===this.positionProp?this.$slider.addClass("slick-vertical"):this.$slider.removeClass("slick-vertical");void 0===a.WebkitTransition&&void 0===a.MozTransition&&void 0===a.msTransition||!0!==this.options.useCSS||(this.cssTransitions=!0);this.options.fade&&("number"===typeof this.options.zIndex?3>this.options.zIndex&&(this.options.zIndex=3):this.options.zIndex=this.defaults.zIndex);void 0!==a.OTransform&&
(this.animType="OTransform",this.transformType="-o-transform",this.transitionType="OTransition",void 0===a.perspectiveProperty&&void 0===a.webkitPerspective&&(this.animType=!1));void 0!==a.MozTransform&&(this.animType="MozTransform",this.transformType="-moz-transform",this.transitionType="MozTransition",void 0===a.perspectiveProperty&&void 0===a.MozPerspective&&(this.animType=!1));void 0!==a.webkitTransform&&(this.animType="webkitTransform",this.transformType="-webkit-transform",this.transitionType=
"webkitTransition",void 0===a.perspectiveProperty&&void 0===a.webkitPerspective&&(this.animType=!1));void 0!==a.msTransform&&(this.animType="msTransform",this.transformType="-ms-transform",this.transitionType="msTransition",void 0===a.msTransform&&(this.animType=!1));void 0!==a.transform&&!1!==this.animType&&(this.transformType=this.animType="transform",this.transitionType="transition");this.transformsEnabled=this.options.useTransform&&null!==this.animType&&!1!==this.animType};g.prototype.setSlideClasses=
function(a){var b=this.$slider.find(".slick-slide").removeClass("slick-active slick-center slick-current").attr("aria-hidden","true");this.$slides.eq(a).addClass("slick-current");if(!0===this.options.centerMode){var c=0===this.options.slidesToShow%2?1:0;var e=Math.floor(this.options.slidesToShow/2);if(!0===this.options.infinite){if(a>=e&&a<=this.slideCount-1-e)this.$slides.slice(a-e+c,a+e+1).addClass("slick-active").attr("aria-hidden","false");else{var d=this.options.slidesToShow+a;b.slice(d-e+1+
c,d+e+2).addClass("slick-active").attr("aria-hidden","false")}0===a?b.eq(b.length-1-this.options.slidesToShow).addClass("slick-center"):a===this.slideCount-1&&b.eq(this.options.slidesToShow).addClass("slick-center")}this.$slides.eq(a).addClass("slick-center")}else 0<=a&&a<=this.slideCount-this.options.slidesToShow?this.$slides.slice(a,a+this.options.slidesToShow).addClass("slick-active").attr("aria-hidden","false"):b.length<=this.options.slidesToShow?b.addClass("slick-active").attr("aria-hidden",
"false"):(e=this.slideCount%this.options.slidesToShow,d=!0===this.options.infinite?this.options.slidesToShow+a:a,this.options.slidesToShow==this.options.slidesToScroll&&this.slideCount-a<this.options.slidesToShow?b.slice(d-(this.options.slidesToShow-e),d+e).addClass("slick-active").attr("aria-hidden","false"):b.slice(d,d+this.options.slidesToShow).addClass("slick-active").attr("aria-hidden","false"));"ondemand"!==this.options.lazyLoad&&"anticipated"!==this.options.lazyLoad||this.lazyLoad()};g.prototype.setupInfinite=
function(){var a;!0===this.options.fade&&(this.options.centerMode=!1);if(!0===this.options.infinite&&!1===this.options.fade){var b=null;if(this.slideCount>this.options.slidesToShow){var c=!0===this.options.centerMode?this.options.slidesToShow+1:this.options.slidesToShow;for(a=this.slideCount;a>this.slideCount-c;--a)b=a-1,f(this.$slides[b]).clone(!0).attr("id","").attr("data-slick-index",b-this.slideCount).prependTo(this.$slideTrack).addClass("slick-cloned");for(a=0;a<c+this.slideCount;a+=1)b=a,f(this.$slides[b]).clone(!0).attr("id",
"").attr("data-slick-index",b+this.slideCount).appendTo(this.$slideTrack).addClass("slick-cloned");this.$slideTrack.find(".slick-cloned").find("[id]").each(function(){f(this).attr("id","")})}}};g.prototype.interrupt=function(a){a||this.autoPlay();this.interrupted=a};g.prototype.selectHandler=function(a){a=f(a.target).is(".slick-slide")?f(a.target):f(a.target).parents(".slick-slide");(a=parseInt(a.attr("data-slick-index")))||(a=0);this.slideCount<=this.options.slidesToShow?this.slideHandler(a,!1,!0):
this.slideHandler(a)};g.prototype.slideHandler=function(a,b,c){var e=null,d=this;if(!0!==d.animating||!0!==d.options.waitForAnimate)if(!0!==d.options.fade||d.currentSlide!==a){!1===(b||!1)&&d.asNavFor(a);var h=a;e=d.getLeft(h);b=d.getLeft(d.currentSlide);d.currentLeft=null===d.swipeLeft?b:d.swipeLeft;if(!1===d.options.infinite&&!1===d.options.centerMode&&(0>a||a>d.getDotCount()*d.options.slidesToScroll))!1===d.options.fade&&(h=d.currentSlide,!0!==c&&d.slideCount>d.options.slidesToShow?d.animateSlide(b,
function(){d.postSlide(h)}):d.postSlide(h));else if(!1===d.options.infinite&&!0===d.options.centerMode&&(0>a||a>d.slideCount-d.options.slidesToScroll))!1===d.options.fade&&(h=d.currentSlide,!0!==c&&d.slideCount>d.options.slidesToShow?d.animateSlide(b,function(){d.postSlide(h)}):d.postSlide(h));else{d.options.autoplay&&clearInterval(d.autoPlayTimer);var k=0>h?0!==d.slideCount%d.options.slidesToScroll?d.slideCount-d.slideCount%d.options.slidesToScroll:d.slideCount+h:h>=d.slideCount?0!==d.slideCount%
d.options.slidesToScroll?0:h-d.slideCount:h;d.animating=!0;d.$slider.trigger("beforeChange",[d,d.currentSlide,k]);a=d.currentSlide;d.currentSlide=k;d.setSlideClasses(d.currentSlide);d.options.asNavFor&&(b=d.getNavTarget(),b=b.slick("getSlick"),b.slideCount<=b.options.slidesToShow&&b.setSlideClasses(d.currentSlide));d.updateDots();d.updateArrows();!0===d.options.fade?(!0!==c?(d.fadeSlideOut(a),d.fadeSlide(k,function(){d.postSlide(k)})):d.postSlide(k),d.animateHeight()):!0!==c&&d.slideCount>d.options.slidesToShow?
d.animateSlide(e,function(){d.postSlide(k)}):d.postSlide(k)}}};g.prototype.startLoad=function(){!0===this.options.arrows&&this.slideCount>this.options.slidesToShow&&(this.$prevArrow.hide(),this.$nextArrow.hide());!0===this.options.dots&&this.slideCount>this.options.slidesToShow&&this.$dots.hide();this.$slider.addClass("slick-loading")};g.prototype.swipeDirection=function(){var a=Math.round(180*Math.atan2(this.touchObject.startY-this.touchObject.curY,this.touchObject.startX-this.touchObject.curX)/
Math.PI);0>a&&(a=360-Math.abs(a));return 45>=a&&0<=a||360>=a&&315<=a?!1===this.options.rtl?"left":"right":135<=a&&225>=a?!1===this.options.rtl?"right":"left":!0===this.options.verticalSwiping?35<=a&&135>=a?"down":"up":"vertical"};g.prototype.swipeEnd=function(a){this.swiping=this.dragging=!1;if(this.scrolling)return this.scrolling=!1;this.interrupted=!1;this.shouldClick=10<this.touchObject.swipeLength?!1:!0;if(void 0===this.touchObject.curX)return!1;!0===this.touchObject.edgeHit&&this.$slider.trigger("edge",
[this,this.swipeDirection()]);if(this.touchObject.swipeLength>=this.touchObject.minSwipe){a=this.swipeDirection();switch(a){case "left":case "down":var b=this.options.swipeToSlide?this.checkNavigable(this.currentSlide+this.getSlideCount()):this.currentSlide+this.getSlideCount();this.currentDirection=0;break;case "right":case "up":b=this.options.swipeToSlide?this.checkNavigable(this.currentSlide-this.getSlideCount()):this.currentSlide-this.getSlideCount(),this.currentDirection=1}"vertical"!=a&&(this.slideHandler(b),
this.touchObject={},this.$slider.trigger("swipe",[this,a]))}else this.touchObject.startX!==this.touchObject.curX&&(this.slideHandler(this.currentSlide),this.touchObject={})};g.prototype.swipeHandler=function(a){if(!(!1===this.options.swipe||"ontouchend"in document&&!1===this.options.swipe||!1===this.options.draggable&&-1!==a.type.indexOf("mouse")))switch(this.touchObject.fingerCount=a.originalEvent&&void 0!==a.originalEvent.touches?a.originalEvent.touches.length:1,this.touchObject.minSwipe=this.listWidth/
this.options.touchThreshold,!0===this.options.verticalSwiping&&(this.touchObject.minSwipe=this.listHeight/this.options.touchThreshold),a.data.action){case "start":this.swipeStart(a);break;case "move":this.swipeMove(a);break;case "end":this.swipeEnd(a)}};g.prototype.swipeMove=function(a){var b=void 0!==a.originalEvent?a.originalEvent.touches:null;if(!this.dragging||this.scrolling||b&&1!==b.length)return!1;var c=this.getLeft(this.currentSlide);this.touchObject.curX=void 0!==b?b[0].pageX:a.clientX;this.touchObject.curY=
void 0!==b?b[0].pageY:a.clientY;this.touchObject.swipeLength=Math.round(Math.sqrt(Math.pow(this.touchObject.curX-this.touchObject.startX,2)));b=Math.round(Math.sqrt(Math.pow(this.touchObject.curY-this.touchObject.startY,2)));if(!this.options.verticalSwiping&&!this.swiping&&4<b)return this.scrolling=!0,!1;!0===this.options.verticalSwiping&&(this.touchObject.swipeLength=b);b=this.swipeDirection();void 0!==a.originalEvent&&4<this.touchObject.swipeLength&&(this.swiping=!0,a.preventDefault());var e=(!1===
this.options.rtl?1:-1)*(this.touchObject.curX>this.touchObject.startX?1:-1);!0===this.options.verticalSwiping&&(e=this.touchObject.curY>this.touchObject.startY?1:-1);a=this.touchObject.swipeLength;this.touchObject.edgeHit=!1;!1===this.options.infinite&&(0===this.currentSlide&&"right"===b||this.currentSlide>=this.getDotCount()&&"left"===b)&&(a=this.touchObject.swipeLength*this.options.edgeFriction,this.touchObject.edgeHit=!0);this.swipeLeft=!1===this.options.vertical?c+a*e:c+a*(this.$list.height()/
this.listWidth)*e;!0===this.options.verticalSwiping&&(this.swipeLeft=c+a*e);if(!0===this.options.fade||!1===this.options.touchMove)return!1;if(!0===this.animating)return this.swipeLeft=null,!1;this.setCSS(this.swipeLeft)};g.prototype.swipeStart=function(a){var b;this.interrupted=!0;if(1!==this.touchObject.fingerCount||this.slideCount<=this.options.slidesToShow)return this.touchObject={},!1;void 0!==a.originalEvent&&void 0!==a.originalEvent.touches&&(b=a.originalEvent.touches[0]);this.touchObject.startX=
this.touchObject.curX=void 0!==b?b.pageX:a.clientX;this.touchObject.startY=this.touchObject.curY=void 0!==b?b.pageY:a.clientY;this.dragging=!0};g.prototype.unfilterSlides=g.prototype.slickUnfilter=function(){null!==this.$slidesCache&&(this.unload(),this.$slideTrack.children(this.options.slide).detach(),this.$slidesCache.appendTo(this.$slideTrack),this.reinit())};g.prototype.unload=function(){f(".slick-cloned",this.$slider).remove();this.$dots&&this.$dots.remove();this.$prevArrow&&this.htmlExpr.test(this.options.prevArrow)&&
this.$prevArrow.remove();this.$nextArrow&&this.htmlExpr.test(this.options.nextArrow)&&this.$nextArrow.remove();this.$slides.removeClass("slick-slide slick-active slick-visible slick-current").attr("aria-hidden","true").css("width","")};g.prototype.unslick=function(a){this.$slider.trigger("unslick",[this,a]);this.destroy()};g.prototype.updateArrows=function(){!0===this.options.arrows&&this.slideCount>this.options.slidesToShow&&!this.options.infinite&&(this.$prevArrow.removeClass("slick-disabled").attr("aria-disabled",
"false"),this.$nextArrow.removeClass("slick-disabled").attr("aria-disabled","false"),0===this.currentSlide?(this.$prevArrow.addClass("slick-disabled").attr("aria-disabled","true"),this.$nextArrow.removeClass("slick-disabled").attr("aria-disabled","false")):this.currentSlide>=this.slideCount-this.options.slidesToShow&&!1===this.options.centerMode?(this.$nextArrow.addClass("slick-disabled").attr("aria-disabled","true"),this.$prevArrow.removeClass("slick-disabled").attr("aria-disabled","false")):this.currentSlide>=
this.slideCount-1&&!0===this.options.centerMode&&(this.$nextArrow.addClass("slick-disabled").attr("aria-disabled","true"),this.$prevArrow.removeClass("slick-disabled").attr("aria-disabled","false")))};g.prototype.updateDots=function(){null!==this.$dots&&(this.$dots.find("li").removeClass("slick-active").end(),this.$dots.find("li").eq(Math.floor(this.currentSlide/this.options.slidesToScroll)).addClass("slick-active"))};g.prototype.visibility=function(){this.options.autoplay&&(this.interrupted=document[this.hidden]?
!0:!1)};f.fn.slick=function(){var a=arguments[0],b=Array.prototype.slice.call(arguments,1),c=this.length,e,d;for(e=0;e<c;e++)if("object"==typeof a||"undefined"==typeof a?this[e].slick=new g(this[e],a):d=this[e].slick[a].apply(this[e].slick,b),"undefined"!=typeof d)return d;return this}});

"use strict";!function(t){var i,n;i=function(){function i(i,n){var o;this.options=n,this.$element=t(i),this.didInit=!1,o=this,this.$element.on("click.slickLightbox",this.options.itemSelector,(function(i){var n,e;if(i.preventDefault(),(n=t(this)).blur(),"function"!=typeof o.options.shouldOpen||o.options.shouldOpen(o,n,i))return e=o.$element.find(o.options.itemSelector),o.elementIsSlick()&&(e=o.filterOutSlickClones(e),n=o.handlePossibleCloneClick(n,e)),o.init(e.index(n))}))}return i.prototype.init=function(t){return this.didInit=!0,this.detectIE(),this.createModal(),this.bindEvents(),this.initSlick(t),this.open()},i.prototype.createModalItems=function(){var i,n,o,e,s,l,r;return e=this.options.lazyPlaceholder||"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7",o=function(t,i,n){return'<div class="slick-lightbox-slick-item">\n  <div class="slick-lightbox-slick-item-inner">\n    <img class="slick-lightbox-slick-img" '+(!0===n?' data-lazy="'+t+'" src="'+e+'" ':' src="'+t+'" ')+" />\n    "+i+"\n  </div>\n</div>"},this.options.images?l=t.map(this.options.images,(r=this,function(t){return o(t,r.options.lazy)})):(i=this.filterOutSlickClones(this.$element.find(this.options.itemSelector)),s=i.length,n=function(t){return function(i,n){var e,l,r;return l={index:n,length:s},e=t.getElementCaption(i,l),r=t.getElementSrc(i),o(r,e,t.options.lazy)}}(this),l=t.map(i,n)),l},i.prototype.createModal=function(){var i,n;return n=this.createModalItems(),i='<div class="slick-lightbox slick-lightbox-hide-init'+(this.isIE?" slick-lightbox-ie":"")+'" style="background: '+this.options.background+';">\n  <div class="slick-lightbox-inner">\n    <div class="slick-lightbox-slick slick-caption-'+this.options.captionPosition+'">'+n.join("")+"</div>\n  <div>\n<div>",this.$modalElement=t(i),this.$parts={},this.$parts.closeButton=t(this.options.layouts.closeButton),this.$modalElement.find(".slick-lightbox-inner").append(this.$parts.closeButton),t("body").append(this.$modalElement)},i.prototype.initSlick=function(i){var n;return n={initialSlide:i},this.options.lazy&&(n.lazyLoad="ondemand"),null!=this.options.slick?"function"==typeof this.options.slick?this.slick=this.options.slick(this.$modalElement):this.slick=this.$modalElement.find(".slick-lightbox-slick").slick(t.extend({},this.options.slick,n)):this.slick=this.$modalElement.find(".slick-lightbox-slick").slick(n),this.$modalElement.trigger("init.slickLightbox")},i.prototype.open=function(){var t;return this.options.useHistoryApi&&this.writeHistory(),this.$element.trigger("show.slickLightbox"),setTimeout((t=this,function(){return t.$element.trigger("shown.slickLightbox")}),this.getTransitionDuration()),this.$modalElement.removeClass("slick-lightbox-hide-init")},i.prototype.close=function(){var t;return this.$element.trigger("hide.slickLightbox"),setTimeout((t=this,function(){return t.$element.trigger("hidden.slickLightbox")}),this.getTransitionDuration()),this.$modalElement.addClass("slick-lightbox-hide"),this.destroy()},i.prototype.bindEvents=function(){var i,n;if(n=this,i=function(){var t;return t=n.$modalElement.find(".slick-lightbox-inner").height(),n.$modalElement.find(".slick-lightbox-slick-item").height(t),n.$modalElement.find(".slick-lightbox-slick-img, .slick-lightbox-slick-item-inner").css("max-height",Math.round(n.options.imageMaxHeight*t))},t(window).on("orientationchange.slickLightbox resize.slickLightbox",i),this.options.useHistoryApi&&t(window).on("popstate.slickLightbox",function(t){return function(){return t.close()}}(this)),this.$modalElement.on("init.slickLightbox",i),this.$modalElement.on("destroy.slickLightbox",function(t){return function(){return t.destroy()}}(this)),this.$element.on("destroy.slickLightbox",function(t){return function(){return t.destroy(!0)}}(this)),this.$parts.closeButton.on("click.slickLightbox touchstart.slickLightbox",function(t){return function(i){return i.preventDefault(),t.close()}}(this)),(this.options.closeOnEscape||this.options.navigateByKeyboard)&&t(document).on("keydown.slickLightbox",function(t){return function(i){var n;if(n=i.keyCode?i.keyCode:i.which,t.options.navigateByKeyboard&&(37===n?t.slideSlick("left"):39===n&&t.slideSlick("right")),t.options.closeOnEscape&&27===n)return t.close()}}(this)),this.options.closeOnBackdropClick)return this.$modalElement.on("click.slickLightbox touchstart.slickLightbox",".slick-lightbox-slick-img",(function(t){return t.stopPropagation()})),this.$modalElement.on("click.slickLightbox",".slick-lightbox-slick-item",function(t){return function(i){return i.preventDefault(),t.close()}}(this))},i.prototype.slideSlick=function(t){return"left"===t?this.slick.slick("slickPrev"):this.slick.slick("slickNext")},i.prototype.detectIE=function(){if(this.isIE=!1,/MSIE (\d+\.\d+);/.test(navigator.userAgent)&&new Number(RegExp.$1)<9)return this.isIE=!0},i.prototype.getElementCaption=function(i,n){return this.options.caption?'<span class="slick-lightbox-slick-caption">'+function(){switch(typeof this.options.caption){case"function":return this.options.caption(i,n);case"string":return t(i).data(this.options.caption)}}.call(this)+"</span>":""},i.prototype.getElementSrc=function(i){switch(typeof this.options.src){case"function":return this.options.src(i);case"string":return t(i).attr(this.options.src);default:return i.href}},i.prototype.unbindEvents=function(){return t(window).off(".slickLightbox"),t(document).off(".slickLightbox"),this.$modalElement.off(".slickLightbox")},i.prototype.destroy=function(t){var i;if(null==t&&(t=!1),this.didInit&&(this.unbindEvents(),setTimeout((i=this,function(){return i.$modalElement.remove()}),this.options.destroyTimeout)),t)return this.$element.off(".slickLightbox"),this.$element.off(".slickLightbox",this.options.itemSelector)},i.prototype.destroyPrevious=function(){return t("body").children(".slick-lightbox").trigger("destroy.slickLightbox")},i.prototype.getTransitionDuration=function(){var t;return this.transitionDuration?this.transitionDuration:(t=this.$modalElement.css("transition-duration"),this.transitionDuration=void 0===t?500:t.indexOf("ms")>-1?parseFloat(t):1e3*parseFloat(t))},i.prototype.writeHistory=function(){return"undefined"!=typeof history&&null!==history&&"function"==typeof history.pushState?history.pushState(null,null,""):void 0},i.prototype.filterOutSlickClones=function(i){return this.elementIsSlick()?i.filter((function(){var i;return!(i=t(this)).hasClass("slick-cloned")&&0===i.parents(".slick-cloned").length})):i},i.prototype.handlePossibleCloneClick=function(i,n){var o;return this.elementIsSlick()&&i.closest(".slick-slide").hasClass("slick-cloned")?(o=i.attr("href"),n.filter((function(){return t(this).attr("href")===o})).first()):i},i.prototype.elementIsSlick=function(){return this.$element.hasClass("slick-slider")},i}(),n={background:"rgba(0,0,0,.8)",closeOnEscape:!0,closeOnBackdropClick:!0,destroyTimeout:500,itemSelector:"a",navigateByKeyboard:!0,src:!1,caption:!1,captionPosition:"dynamic",images:!1,slick:{},useHistoryApi:!1,layouts:{closeButton:'<button type="button" class="slick-lightbox-close"></button>'},shouldOpen:null,imageMaxHeight:.9,lazy:!1},t.fn.slickLightbox=function(o){return o=t.extend({},n,o),t(this).each((function(){return this.slickLightbox=new i(this,o)})),this},t.fn.unslickLightbox=function(){return t(this).trigger("destroy.slickLightbox").each((function(){return this.slickLightbox=null}))}}(jQuery);
/* Posthemes JS */
var posthemes = {
	init: function(){
		this.headerSticky();
		this.mobileMenu();
		this.ajaxSearch();
		this.productImageSlider();
		this.initSlickLightBox();
		if($('#product').length > 0){
			this.productImageZoom();
			this.initSlider();
		}
		if($('#search_filters_wrapper').length > 0 && $('#search_filters_wrapper').hasClass('search_filters_top')){
			this.categoryFilterTop();
		}
		this.categoryFilterMobile();
	},
	setCookie: function(name , time) {
	    var value = '1';
	    var expire = new Date();
	    
	    expire.setDate(expire.getDate() + time);
	    
	    document.cookie = name + "=" + escape(value) + ";path=/;" + ((expire == null) ? "" : ("; expires=" + expire.toGMTString()))
	},
	readCookie: function(name) {
	    var nameEQ = name + "=";
	    var ca = document.cookie.split(';');
	    for (var i = 0; i < ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
	        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	    }
	    return null;
	},
	headerSticky: function(){
		if($('#header').length > 0){
			var headerSpaceH = $('#header .sticky-inner').outerHeight(true);
			$('#header .sticky-inner').before('<div class="headerSpace unvisible" style="height: '+headerSpaceH+'px;" ></div>'); 
			if($('.page-index').length > 0 && $('.sticky-inner.absolute-header').length > 0){
				$('.headerSpace').remove();
			} 
			if($('.page-index').length > 0 && $('.sticky-inner.absolute-header-full').length > 0){
				$('.headerSpace').remove();  
			} 	
		}	
		$(window).scroll(function() {
			var headerSpaceH = $('#header').outerHeight();
			var screenWidth = $(window).width();
			
			if ($(this).scrollTop() > headerSpaceH && screenWidth >= 1024 ){  
				  $(".use-sticky").find(".sticky-inner").addClass("scroll-menu"); 
				   $('.headerSpace').removeClass("unvisible");
			}
			else{
				 $(".use-sticky").find(".sticky-inner").removeClass("scroll-menu");
				 $(".headerSpace").addClass("unvisible");
			}
		});	
	},
	mobileMenu: function(){
		$('#menu-icon') .click(function(){ 
			var _this= $(this);
			_this.addClass('open-menu'); 
			$('.pos-overlay').addClass('open');
			$('body').addClass('overlay-opened');
			$('#mobile_menu_wrapper').addClass('box-menu');
			var hClass = _this.hasClass('open-menu');
			if(hClass){
				$(window).resize(function(){
					if($(window).width() > 1024)   
					{
						_this.removeClass('open-menu');
						$('.pos-overlay').removeClass('open');
						$('body').removeClass('overlay-opened');
						$('#mobile_menu_wrapper').removeClass('box-menu');
					}
				});
			}else{	
				$('.pos-overlay').removeClass('open');
				$('body').removeClass('overlay-opened');
				$('#mobile_menu_wrapper').removeClass('box-menu');
				
			}
		});	  
		$('.menu-close, .pos-overlay') .click(function(){
			$('body').removeClass('overlay-opened');
			$('.pos-overlay').removeClass('open');
			$('#mobile_menu_wrapper').removeClass('box-menu');
			$('#menu-icon').removeClass('open-menu');
		});	
	},
	ajaxSearch: function(){
		$('.search-cat-value').on('click', function(e){
			e.preventDefault();
			var id = $(this).data('id'),
				text = $(this).html();
			$('input[name="cat"]').val(id);
			$('.search-category-items > a > span').html(text)
		})
		$('.search-selected-cat').on('click', function(e){
			e.preventDefault();
		})
		var $search = $('.pos-search');
	    var $searchContainer = $('.pos-search__container');
	    
	    if($search.hasClass('search-topbar')){
	        $('body').on('click', '.pos-search__toggle', function(){
	            $searchContainer.removeClass('unvisible');
				$('body').addClass('search-opened');
	        })
			$('body').on('click', '.dropdown-menu', function(e){
				if($(e.target).attr('class') != 'icon-rt-close-outline'){
					e.stopPropagation();
				}
	            
	        })
	        $('body').on('click', '.dialog-close-button', function(e){
				e.preventDefault();
	            $searchContainer.addClass('unvisible');
				$('body').removeClass('search-opened');
	        })
			
	    }
	    var searchWrapper = $('.pos-search-wrapper'),
	        searchResults = $('.pos-search__result'),
	        searchForm    = $('.pos-search'),
	        searchInput   = $('.pos-search__input'),
	        searchURL     = searchForm.data('search-controller-url'),
	        controller    = searchForm.find('input[name="controller"]').val(),
	        order 	 	  = searchForm.find('input[name="order"]').val(),
	        searchClear   = $('.search-clear'),
	        flag = false;

	    if(searchWrapper.hasClass('search-icon-type')){
	        flag = true;
	    }
	    searchInput
	    	.on('click', function(e){
	    		e.stopPropagation();
		        var resultShow = searchResults.html();
		        if(resultShow.length > 0 && searchResults.hasClass('unvisible')){
		    		searchResults.removeClass('unvisible');
		    	}
		    })
		    .keyup(function() {
		    	const $this = $(this);
	            if($this.val().length >= 3){
	                searchResults.html('');
	                searchClear.removeClass('unvisible');
	                searchClear.addClass('loading_search');

	                clearTimeout(timer);
	                var timer = setTimeout(function() {
	                   	var limit = 10;
	                   	if(possearch_number){
	                   		var limit = possearch_number;
	                   	}
	                    var data = {
	                        's': $this.val(),
	                        'resultsPerPage': limit,
	                        'cat': $('#search-cat').val()
	                    };
	                    
	                    $.post(searchURL, data , null, 'json')
	                        .then(function (resp) {
	                            searchClear.removeClass('loading_search');
	                            var html = '';
	                            html += '<div class="search-container">';
	                                if(resp.products){
	                                    for(var i=0 ; i<resp.products.length ; i++){
	                                        html += '<div class="search-item">';
	                                            html += '<a href="' + resp.products[i].url + '">';
	                                            	html += '<img src="'+ resp.products[i].cover.small.url +'" alt="" />';
	                                                html += '<div class="product-infos">';
	                                                    html += '<p class="product_name">'+ resp.products[i].name +'</p>';
	                                                    if(resp.products[i].has_discount){
	                                                        html += '<p class="product_old_price">'+ resp.products[i].regular_price +'</p>';
	                                                    }
	                                                    html += '<p class="product_price">'+ resp.products[i].price +'</p>';
	                                                html += '</div>';
	                                            html += '</a>';
	                                        html += '</div>';   
	                                    }
	                                    html += '<a class="btn btn-secondary" href="'+ searchURL +'?order='+ order +'&s='+ $this.val() +'">'+ view_more +'</a>';
	                                }else{
	                                    html += search_not_found;
	                                }
	                                
	                            html += '</div>';
	                            searchResults.removeClass('unvisible');
	                            searchResults.html(html);
	                        })
	                });
	            }else{
	                searchResults.html('');
	                searchClear.addClass('unvisible');
	            }
	        });

	    searchClear.click(function(){
	        searchInput.val('');
	        searchResults.html('');
	        $(this).addClass('unvisible');
	    });

	    $('body').on('click', function() {
	        searchResults.addClass('unvisible'); 
			$('body').removeClass('search-opened');
	    });
	},
	categoryFilterTop: function(){
		var filters = $('#search_filters_wrapper');
	    $('#pos_search_filter_toggler').parents('body#category').addClass("page-filter-top"); 
		$('#pos_search_filter_toggler').on('click', function(){
			//filters.fadeIn();
			filters.addClass('opened');
			$('.search_filters_overlay').addClass('opened');
		})
		
		$('body').on('click', '.search_filters_overlay', function(){
			//filters.fadeOut();
			filters.removeClass('opened');
			$('.search_filters_overlay').removeClass('opened');
		});
		$('.close-filter').on('click', function(){
			//filters.fadeOut();
			filters.removeClass('opened');
			$('.search_filters_overlay').removeClass('opened');
			$(this).parents('body').css('overflow','visible');
		})
		prestashop.on('updateProductList', (data) => {
	    	posthemes.categoryFilterTop();
	    	//filters.fadeOut();
			filters.removeClass('opened');
			$('.search_filters_overlay').removeClass('opened');
	    });
	},
	categoryFilterMobile: function(){
		$('body').on('click', '.filters-top .facet .facet-title', function(e){
			e.preventDefault();
			$('.facet-title').not($(this)).removeClass('active');
			$('.facet-content').not($(this).next('.facet-content')).removeClass('facet-open');
			$(this).toggleClass('active');
			$(this).next('.facet-content').toggleClass('facet-open');
		  })
		  $("body").on('click', function(e) {
			  e = $(e.target);
			  if(!e.is(".facet-content") && !e.next(".facet-content").length > 0 && !e.parents(".facet-content").hasClass('facet-open')) {
				  $(".facet-title").removeClass("active");
				  $(".facet-content").removeClass("facet-open");
			  }     
		  })
		$('body').on('click', '.filter-button a', function(e){
			e.preventDefault();
			e.stopPropagation();
			$('.filters-canvas').addClass('filter-open');
			$('.pos-overlay').addClass('open');
			$('body').addClass('overlay-opened');
		})
	  
		$('body').on('click', '.pos-overlay, .filter-close-btn', function(e){
			$('.filters-canvas').removeClass('filter-open');
			$('.pos-overlay').removeClass('open');
			$('body').removeClass('overlay-opened');
		})
	},
	productImageZoom: function(){
		var $easyzoom = $('.easyzoom');
		$easyzoom.trigger( 'zoom.destroy' );
	 	if($(window).width() >= 992) 
		{
			$easyzoom.easyZoom();
		}
		$(window).resize(function(){
			$easyzoom.trigger( 'zoom.destroy' );
			if($(window).width() >= 992){
				$easyzoom.easyZoom();
			}
		});
	},
	productImageSlider: function(){
		var $imageContainer = $('.images-container'),
			$images = $('.product-cover.slick-block'),
			$thumbnails = $('.product-images.slick-block');

		if($imageContainer.hasClass('default')){
			var item = $thumbnails.data('item');
			$images.not('.slick-initialized').slick({
				infinite: false,
			});
			$thumbnails
				.on('init', function(event, slick) {$('.product-images.slick-block .slick-slide.slick-current').addClass('is-active');})
				.not('.slick-initialized').slick({
					slidesToShow: item,
					infinite: false,
				});
		};
		if($imageContainer.hasClass('left-vertical') || $imageContainer.hasClass('right-vertical')){
			var item = $thumbnails.data('item');
			$images.not('.slick-initialized').slick({
				infinite: false,
			});
			$thumbnails
			.on('init', function(event, slick) {$('.product-images.slick-block .slick-slide.slick-current').addClass('is-active');})
			.not('.slick-initialized').slick({
				slidesToShow: item,
				infinite: false,
				vertical: true,
				responsive: [
					{
						breakpoint: 992,
						settings: {	
						  slidesToShow: 4,
						  slidesToScroll: 1
						}
					},
					{
						breakpoint: 768,
						settings: {	
							vertical: false,
						  slidesToShow: 5,
						  slidesToScroll: 1
						}
					},
					{
					  breakpoint: 399,
					  settings: {
						vertical: false,
						slidesToShow: 5,
						slidesToScroll: 1, 
					  }
					}
				]
			});
			$('.product-images.slick-block img').load(function() {
				$thumbnails.slick("setPosition", 0);
			});
			
		};
		if($imageContainer.hasClass('grid')){
			if($(window).width() > 767){
				$('.product-cover.slick-initialized').slick('unslick');

				$('.images-container .product-cover').slickLightbox({
					src: 'src',
					itemSelector: '.cover-item .cover-img'
				});
				$('.is-fixed').css('width','auto');
				var fixed_height = $('.is-fixed').outerHeight(false),
					height = $('.images-container.grid').outerHeight(false);
				if($('.is-fixed').length > 0 && fixed_height < height) {
					var fixed_height = $('.is-fixed').outerHeight(false),
						fixed_width = $('.is-fixed').outerWidth();
					$(window).scroll(function(){
						var static_height = $('.product-cover').outerHeight(),
							fixed_top_offset = $('.is-fixed').parent().offset().top,
							absolute_height = static_height + fixed_top_offset - fixed_height;
							
						if($(this).scrollTop() > fixed_top_offset && $(this).scrollTop() < absolute_height) {
							$('.is-fixed').css({'position': 'fixed', 'top': 80, 'width': fixed_width});
						}else if($(this).scrollTop() > absolute_height) {
							$('.is-fixed').css({'position': 'absolute', 'top': 'auto' , 'bottom' : 0});
						}else {
							$('.is-fixed').css('position', 'static');
						}
					})

				}
			}else{
				$('.product-cover.image-grid').not('.slick-initialized').slick({
					infinite: false,
				});
			}
			return;
		};

		$images.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
		 	$thumbnails.slick('slickGoTo', nextSlide);
		 	var currrentNavSlideElem = $thumbnails.find('.slick-slide[data-slick-index="' + nextSlide + '"]');
		 	$thumbnails.find('.slick-slide').removeClass('is-active');
		 	currrentNavSlideElem.addClass('is-active');
		});

		$thumbnails.on('click', '.slick-slide', function(event) {
		 	event.preventDefault();
		 	var goToSingleSlide = $(this).data('slick-index');

		 	$images.slick('slickGoTo', goToSingleSlide);
		});
		
	 
	},
	initSlickLightBox: function(){
		var $imageContainer = $('.images-container');
		if($imageContainer.hasClass('grid')){

			$('.images-container .product-cover').slickLightbox({
				src: 'src',
				itemSelector: '.cover-item a > img'
			});
		}else{
			$('.images-container .slick-block').slickLightbox({
				src: 'src',
				itemSelector: '.cover-item a > img'
			});
		}
		
	},
	initSlider: function(){
		$('.product_accessoriesslide').on('init', function(event, slick, currentSlide){
			var slideToShow = $(this).find('.slick-active').length - 1;
			$(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
			$(this).find('.slick-active').eq(0).addClass('first-active');
			$(this).find('.slick-active').eq(slideToShow).addClass('last-active');
		});
		$('.product_accessoriesslide').not('.slick-initialized').slick({
		   slidesToShow: 4,
		   slidesToScroll: 1,
		   dots: false, 
		   arrows: true,  
		   responsive: [
		   	{breakpoint: 1199, settings: { slidesToShow: 4}},
			{breakpoint: 991, settings: { slidesToShow: 3}},
			{breakpoint: 767, settings: { slidesToShow: 2}},
			{breakpoint: 575, settings: { slidesToShow: 2}},
			{breakpoint: 359, settings: { slidesToShow: 1}}
			]
		});
		$('.product_accessoriesslide').on('afterChange', function(event, slick, currentSlide){
			var slideToShow = $(this).find('.slick-active').length - 1;
			$(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
			$(this).find('.slick-active').eq(0).addClass('first-active');
			$(this).find('.slick-active').eq(slideToShow).addClass('last-active');
		});
		$('.product_categoryslide').on('init', function(event, slick, currentSlide){
			var slideToShow = $(this).find('.slick-active').length - 1;
			$(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
			$(this).find('.slick-active').eq(0).addClass('first-active');
			$(this).find('.slick-active').eq(slideToShow).addClass('last-active');
		});
		$('.product_categoryslide').not('.slick-initialized').slick({ 
		   slidesToShow: 4,
		   slidesToScroll: 1,
		   dots: false, 
		   arrows: true,  
		   responsive: [
		   	{breakpoint: 1199, settings: { slidesToShow: 4}},
			{breakpoint: 991, settings: { slidesToShow: 3}},
			{breakpoint: 767, settings: { slidesToShow: 2}},
			{breakpoint: 575, settings: { slidesToShow: 2}},
			{breakpoint: 359, settings: { slidesToShow: 1}}
			]
		});
		$('.product_categoryslide').on('afterChange', function(event, slick, currentSlide){
			var slideToShow = $(this).find('.slick-active').length - 1;
			$(this).find('.slick-slide').removeClass('first-active').removeClass('last-active');
			$(this).find('.slick-active').eq(0).addClass('first-active');
			$(this).find('.slick-active').eq(slideToShow).addClass('last-active');
		});
	}
};
$(".back-top").hide();
$(function () {
	$(window).scroll(function () {
		if ($(this).scrollTop() > 150) {
			$('.back-top').fadeIn();
		} else {
			$('.back-top').fadeOut();
		}
	});
	$('.back-top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 1000);
		return false; 
	});
});
$(document).ready(function(){
	posthemes.init();
	//categories tree custom
	var praent= $('.block-categories li.active').parents('li');
	praent.each(function(){
		$(this).children('.collapse-icons').trigger('click');
	})
	$(".filter-mobile").mPageScroll2id({
		offset:50,
	});
	//Add loading to cart button
	$('body').on('click', 'button.add-to-cart', (event) => {
		const buttonCart = $(event.target);
		buttonCart.addClass('loading');
	})
	$('.showcase-inner') .each(function(){
		$(this).parents('body').addClass('showcase-body');
	});
	$('.pospromo a.pospromo-close').on('click', function(e){
    	e.preventDefault();
    	$('.pospromo').slideUp();
    	posthemes.setCookie('pospromo', 1);
    });
	prestashop.on('updatedProduct', (data) =>{
		const quickView = $('.modal.quickview');

		if (quickView.length) {
			quickView.find('.images-container').removeClass('left-vertical').removeClass('right-vertical').removeClass('grid').addClass('default');
		}
		posthemes.productImageSlider();
		posthemes.initSlickLightBox();
		posthemes.productImageZoom();
});
prestashop.on('updateProductList', function (event) {
	
});

});

$(window).resize(function() {
	posthemes.productImageSlider();
})

