if(!window.__DBW){var __DBW=(function(){var M={api:"http://widgets.digg.com/buttons/count?url=",analytics:"http://a.digg.com/",css:"http://widgets.digg.com/css/buttonb.css",popup:"http://digg.com/tools/diggthis/",submit:"http://digg.com/submit?"},W={impression:5,click:100},O={source:"source",submitted:"submitted",event:"event",diggs:"diggs",storyId:"storyId",buttons:"buttons"},G=true,d,f,b={},c=function(i,j){var k,h=parseInt(j.diggs),g=i.firstChild.firstChild.firstChild.firstChild;
if(h>9999){h=Math.floor(data.diggs/1000)+"K"}else{if(h>999){k=(h+="").charAt(0);h=h.replace(k,k+",")}}g.appendChild(document.createTextNode(h));
g.nextSibling.appendChild(document.createTextNode(h==1?"digg":"diggs"));if(!j.id){i.firstChild.firstChild.className="db-container db-submit"
}},F=function(k){var g=d.cloneNode(true),j=D(k),l={},m,i;g.className+=" db-"+j;g.getElementsByTagName("SPAN")[2].className+=" db-"+j;
m=L(k);if(m.style===false){g.className="db-clean"}function h(){for(var n in l){return n}}g.firstChild.onclick=function(){var o,n=this.firstChild;
n.className="db-container db-dugg";if(!i){n=n.firstChild.firstChild;if(f.ie){e(n)}else{Y(n,0,function(){e(n);
setTimeout(function(){Y(n,100)},175)})}i=1}if(h()){U(l,"click")}else{setTimeout(function(){U(l,"click")
},1000)}Q(m,l)};k.parentNode.replaceChild(g,k);b[m.url]=b[m.url]||[];b[m.url].push(function(n){if(!h()){l=n;
U(n,"impression");c(g,n)}});a(m.url)},H=function(){var h=document.createElement("link"),g=document.getElementsByTagName("head")[0]||document.documentElement,i=g.firstChild;
h.setAttribute("rel","stylesheet");h.setAttribute("type","text/css");h.setAttribute("href",M.css);i?g.insertBefore(h,i):g.appendChild(h);
H=0},Y=function(k,i,n){var j,l,n,m=function(){j=i?5:-5;l=i?0:100;h(l);setTimeout(g,15)},h=function(o){l=o;
k.style.opacity=(o/100);k.style.filter="alpha(opacity="+o+")"},g=function(){if(l==i){n&&n()}else{h(l+j);
setTimeout(g,15)}};m()},V=function(j){var g=document.createElement("script"),h=document.getElementsByTagName("head")[0]||document.documentElement,i=h.firstChild;
g.setAttribute("type","text/javascript");g.setAttribute("src",j);i?h.insertBefore(g,i):h.appendChild(g)
},a=function(g){V(M.api+g)},D=function(h){var g="large",i=" "+h.className+" ",k,j="";if(i.match(/( DiggThisButtonMedium | DiggMedium )/)){g="medium"
}else{if(i.match(/ DiggLarge /)){g="large"}else{if(i.match(/ DiggCompact /)){g="compact"}else{if(i.match(/ DiggIcon /)){g="digger"
}}}}k=h.getElementsByTagName("IMG");if(k[0]){j=k[0].src}else{return g}if(j.match(/diggThis\.(gif|png)/i)){return"large"
}else{if(j.match(/diggThisMedium\.(gif|png)/i)){return"medium"}else{if(j.match(/diggThisCompact\.(gif|png)/i)){return"compact"
}else{if(j.match(/digg-guy-icon.(gif|png)/i)){return"digger"}else{if(j.match(/diggThisIcon.(gif|png)/i)){return"digger"
}}}}}return g},e=function(i){var h=i.firstChild,g=h.nodeValue;if(g.indexOf(",")>=0){h.nodeValue=+g.replace(",","","g")+1
}else{if(!g.match(/k/i)){g=+g+1;h.nodeValue=g;if(g==1){i.nextSibling.firstChild.nodeValue="digg"}else{i.nextSibling.firstChild.nodeValue="diggs"
}}}},B,Q=function(j,h){var g=480,i;if(h.id){j.storyId=h.id}if(j.related===false){g=335}i=M.popup+"confirm"+I(j);
B=window.open(i,"diggAction","status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=0,scrollsbars=0,height="+g+",width=590")
},R=function(k){var m,j,h,n={},g=k.indexOf("?");if(g>-1){k=k.substr(g+1).replace("&amp;","&","g").split("&");
for(m=0,j=k.length;m<j;m++){h=k[m].split("=");n[h[0]]=h[1]}}return n},L=function(h){var j={},g;if(h.href){j=R(h.href)
}j.title=escape(unescape(j.url?j.title||"":document.title));j.url=escape(unescape(j.url||location.href)).replace("+","%2b","g");
j.related=j.related||true;j.style=j.style!="no";if(h.rev){g=h.rev.split(",");if(g.length>1){j.rev=g[0].replace("(","").replace(")","").replace("'","","g");
j.topic=Z(g[1])}}j.bodytext=null;var i=h.getElementsByTagName("span")[0];if(i){j.bodytext=i.innerHTML
}return j},E=function(){var k,h,g,j,m,l,i;f=f||{ie:!!document.all,ie6:this.ie&&document.documentElement.style&&"maxHeight" in document.documentElement.style};
d=document.createElement("SPAN");d.className="db-wrapper db-clear";k=document.createElement("SPAN");if(f.ie){k.className="db-ie";
if(f.ie6){k.className="db-ie db-ie6"}}h=document.createElement("SPAN");h.className="db-container";g=document.createElement("SPAN");
g.className="db-body";j=document.createElement("SPAN");j.className="db-count";m=document.createElement("SPAN");
m.className="db-copy";i=document.createElement("A");i.className="db-anchor";d.appendChild(k);k.appendChild(h);
h.appendChild(g);g.appendChild(j);g.appendChild(m);g.appendChild(i);i.appendChild(document.createTextNode("digg"))
},I=function(h){var g=[];for(prop in h){if(h[prop]!==null){g.push(prop+"="+h[prop])}}return"?"+g.join("&")
},T=function(h){var g;for(prop in h){if(O[prop]){g=h[prop];delete h[prop];h[O[prop]]=g}}return h},U=function(i,h){if(G){var k={source:"button",submitted:!!i.id,event:h,diggs:i.diggs,storyId:i.id||0,buttons:S},l=T(k),j=M.analytics+I(l),g=Math.floor(Math.random()*100)+1;
if(g<=W[h]){new Image().src=j}}},Z=function(g){return g.replace(/^\s+|\s+$/g,"")},N,K,X,S=0,P,C=function(){H&&H();
d||E();P();if(!X){N=setTimeout(C,750)}},A=function(){var g=['<a class="DiggThisButton'];if(window.digg_skin=="compact"){g.push(' DiggCompact"')
}else{if(window.digg_skin=="icon"){g.push(' DiggIcon"')}else{g.push('"')}}if(window.digg_title){g.push(' href="',M.submit,"url=",escape(unescape(digg_url||DIGG_URL||location.href)).replace("+","%2b","g"),"&title=",escape(digg_title),'"')
}else{if(window.digg_url||window.DIGG_URL){g.push(' href="',M.submit,"url=",escape(unescape(digg_url||DIGG_URL)).replace("+","%2b","g"),'"')
}}if(window.digg_media&&window.digg_topic){g.push(' rev="',digg_media,", ",digg_topic,'"')}g.push(">");
if(window.digg_bodytext){g.push("<span>",digg_bodytext,"</span>")}g.push("</a>");document.write(g.join(""))
},J=function(){X=1};if(function(g){return g&&(g+="").substr(g.indexOf("{")).replace(/\s/g,"")=="{[nativecode]}"
}(document.getElementsByClassName)){P=function(){var g=document.getElementsByClassName("DiggThisButton");
while(g.length){F(g[0]);S++}}}else{P=function(){var h=document.getElementsByTagName("A"),g=h.length,j=0,k;
for(;j<g;j++){k=" "+h[j].className+" ";if(k.indexOf(" DiggThisButton ")>=0){F(h[j]);S++}}}}C();if(document.body&&!S){A()
}if(window.addEventListener){window.addEventListener("load",J,true)}else{if(window.attachEvent){window.attachEvent("onload",J)
}else{K=window.onload;window.onload=K?function(){K();J()}:J}}return{writeLink:A,collectDiggs:function(k){var h=b[escape(k.url).replace("+","%2b","g")],g=h.length,j=0;
for(;j<g;j++){h[j](k)}}}})()}else{__DBW.writeLink()};