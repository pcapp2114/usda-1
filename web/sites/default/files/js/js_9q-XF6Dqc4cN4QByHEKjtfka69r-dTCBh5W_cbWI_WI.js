/*! jQuery v3.6.0 | (c) OpenJS Foundation and other contributors | jquery.org/license */
!function(e,t){"use strict";"object"==typeof module&&"object"==typeof module.exports?module.exports=e.document?t(e,!0):function(e){if(!e.document)throw new Error("jQuery requires a window with a document");return t(e)}:t(e)}("undefined"!=typeof window?window:this,function(C,e){"use strict";var t=[],r=Object.getPrototypeOf,s=t.slice,g=t.flat?function(e){return t.flat.call(e)}:function(e){return t.concat.apply([],e)},u=t.push,i=t.indexOf,n={},o=n.toString,v=n.hasOwnProperty,a=v.toString,l=a.call(Object),y={},m=function(e){return"function"==typeof e&&"number"!=typeof e.nodeType&&"function"!=typeof e.item},x=function(e){return null!=e&&e===e.window},E=C.document,c={type:!0,src:!0,nonce:!0,noModule:!0};function b(e,t,n){var r,i,o=(n=n||E).createElement("script");if(o.text=e,t)for(r in c)(i=t[r]||t.getAttribute&&t.getAttribute(r))&&o.setAttribute(r,i);n.head.appendChild(o).parentNode.removeChild(o)}function w(e){return null==e?e+"":"object"==typeof e||"function"==typeof e?n[o.call(e)]||"object":typeof e}var f="3.6.0",S=function(e,t){return new S.fn.init(e,t)};function p(e){var t=!!e&&"length"in e&&e.length,n=w(e);return!m(e)&&!x(e)&&("array"===n||0===t||"number"==typeof t&&0<t&&t-1 in e)}S.fn=S.prototype={jquery:f,constructor:S,length:0,toArray:function(){return s.call(this)},get:function(e){return null==e?s.call(this):e<0?this[e+this.length]:this[e]},pushStack:function(e){var t=S.merge(this.constructor(),e);return t.prevObject=this,t},each:function(e){return S.each(this,e)},map:function(n){return this.pushStack(S.map(this,function(e,t){return n.call(e,t,e)}))},slice:function(){return this.pushStack(s.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},even:function(){return this.pushStack(S.grep(this,function(e,t){return(t+1)%2}))},odd:function(){return this.pushStack(S.grep(this,function(e,t){return t%2}))},eq:function(e){var t=this.length,n=+e+(e<0?t:0);return this.pushStack(0<=n&&n<t?[this[n]]:[])},end:function(){return this.prevObject||this.constructor()},push:u,sort:t.sort,splice:t.splice},S.extend=S.fn.extend=function(){var e,t,n,r,i,o,a=arguments[0]||{},s=1,u=arguments.length,l=!1;for("boolean"==typeof a&&(l=a,a=arguments[s]||{},s++),"object"==typeof a||m(a)||(a={}),s===u&&(a=this,s--);s<u;s++)if(null!=(e=arguments[s]))for(t in e)r=e[t],"__proto__"!==t&&a!==r&&(l&&r&&(S.isPlainObject(r)||(i=Array.isArray(r)))?(n=a[t],o=i&&!Array.isArray(n)?[]:i||S.isPlainObject(n)?n:{},i=!1,a[t]=S.extend(l,o,r)):void 0!==r&&(a[t]=r));return a},S.extend({expando:"jQuery"+(f+Math.random()).replace(/\D/g,""),isReady:!0,error:function(e){throw new Error(e)},noop:function(){},isPlainObject:function(e){var t,n;return!(!e||"[object Object]"!==o.call(e))&&(!(t=r(e))||"function"==typeof(n=v.call(t,"constructor")&&t.constructor)&&a.call(n)===l)},isEmptyObject:function(e){var t;for(t in e)return!1;return!0},globalEval:function(e,t,n){b(e,{nonce:t&&t.nonce},n)},each:function(e,t){var n,r=0;if(p(e)){for(n=e.length;r<n;r++)if(!1===t.call(e[r],r,e[r]))break}else for(r in e)if(!1===t.call(e[r],r,e[r]))break;return e},makeArray:function(e,t){var n=t||[];return null!=e&&(p(Object(e))?S.merge(n,"string"==typeof e?[e]:e):u.call(n,e)),n},inArray:function(e,t,n){return null==t?-1:i.call(t,e,n)},merge:function(e,t){for(var n=+t.length,r=0,i=e.length;r<n;r++)e[i++]=t[r];return e.length=i,e},grep:function(e,t,n){for(var r=[],i=0,o=e.length,a=!n;i<o;i++)!t(e[i],i)!==a&&r.push(e[i]);return r},map:function(e,t,n){var r,i,o=0,a=[];if(p(e))for(r=e.length;o<r;o++)null!=(i=t(e[o],o,n))&&a.push(i);else for(o in e)null!=(i=t(e[o],o,n))&&a.push(i);return g(a)},guid:1,support:y}),"function"==typeof Symbol&&(S.fn[Symbol.iterator]=t[Symbol.iterator]),S.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "),function(e,t){n["[object "+t+"]"]=t.toLowerCase()});var d=function(n){var e,d,b,o,i,h,f,g,w,u,l,T,C,a,E,v,s,c,y,S="sizzle"+1*new Date,p=n.document,k=0,r=0,m=ue(),x=ue(),A=ue(),N=ue(),j=function(e,t){return e===t&&(l=!0),0},D={}.hasOwnProperty,t=[],q=t.pop,L=t.push,H=t.push,O=t.slice,P=function(e,t){for(var n=0,r=e.length;n<r;n++)if(e[n]===t)return n;return-1},R="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",M="[\\x20\\t\\r\\n\\f]",I="(?:\\\\[\\da-fA-F]{1,6}"+M+"?|\\\\[^\\r\\n\\f]|[\\w-]|[^\0-\\x7f])+",W="\\["+M+"*("+I+")(?:"+M+"*([*^$|!~]?=)"+M+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+I+"))|)"+M+"*\\]",F=":("+I+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+W+")*)|.*)\\)|)",B=new RegExp(M+"+","g"),$=new RegExp("^"+M+"+|((?:^|[^\\\\])(?:\\\\.)*)"+M+"+$","g"),_=new RegExp("^"+M+"*,"+M+"*"),z=new RegExp("^"+M+"*([>+~]|"+M+")"+M+"*"),U=new RegExp(M+"|>"),X=new RegExp(F),V=new RegExp("^"+I+"$"),G={ID:new RegExp("^#("+I+")"),CLASS:new RegExp("^\\.("+I+")"),TAG:new RegExp("^("+I+"|[*])"),ATTR:new RegExp("^"+W),PSEUDO:new RegExp("^"+F),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+M+"*(even|odd|(([+-]|)(\\d*)n|)"+M+"*(?:([+-]|)"+M+"*(\\d+)|))"+M+"*\\)|)","i"),bool:new RegExp("^(?:"+R+")$","i"),needsContext:new RegExp("^"+M+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+M+"*((?:-\\d)?\\d*)"+M+"*\\)|)(?=[^-]|$)","i")},Y=/HTML$/i,Q=/^(?:input|select|textarea|button)$/i,J=/^h\d$/i,K=/^[^{]+\{\s*\[native \w/,Z=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,ee=/[+~]/,te=new RegExp("\\\\[\\da-fA-F]{1,6}"+M+"?|\\\\([^\\r\\n\\f])","g"),ne=function(e,t){var n="0x"+e.slice(1)-65536;return t||(n<0?String.fromCharCode(n+65536):String.fromCharCode(n>>10|55296,1023&n|56320))},re=/([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,ie=function(e,t){return t?"\0"===e?"\ufffd":e.slice(0,-1)+"\\"+e.charCodeAt(e.length-1).toString(16)+" ":"\\"+e},oe=function(){T()},ae=be(function(e){return!0===e.disabled&&"fieldset"===e.nodeName.toLowerCase()},{dir:"parentNode",next:"legend"});try{H.apply(t=O.call(p.childNodes),p.childNodes),t[p.childNodes.length].nodeType}catch(e){H={apply:t.length?function(e,t){L.apply(e,O.call(t))}:function(e,t){var n=e.length,r=0;while(e[n++]=t[r++]);e.length=n-1}}}function se(t,e,n,r){var i,o,a,s,u,l,c,f=e&&e.ownerDocument,p=e?e.nodeType:9;if(n=n||[],"string"!=typeof t||!t||1!==p&&9!==p&&11!==p)return n;if(!r&&(T(e),e=e||C,E)){if(11!==p&&(u=Z.exec(t)))if(i=u[1]){if(9===p){if(!(a=e.getElementById(i)))return n;if(a.id===i)return n.push(a),n}else if(f&&(a=f.getElementById(i))&&y(e,a)&&a.id===i)return n.push(a),n}else{if(u[2])return H.apply(n,e.getElementsByTagName(t)),n;if((i=u[3])&&d.getElementsByClassName&&e.getElementsByClassName)return H.apply(n,e.getElementsByClassName(i)),n}if(d.qsa&&!N[t+" "]&&(!v||!v.test(t))&&(1!==p||"object"!==e.nodeName.toLowerCase())){if(c=t,f=e,1===p&&(U.test(t)||z.test(t))){(f=ee.test(t)&&ye(e.parentNode)||e)===e&&d.scope||((s=e.getAttribute("id"))?s=s.replace(re,ie):e.setAttribute("id",s=S)),o=(l=h(t)).length;while(o--)l[o]=(s?"#"+s:":scope")+" "+xe(l[o]);c=l.join(",")}try{return H.apply(n,f.querySelectorAll(c)),n}catch(e){N(t,!0)}finally{s===S&&e.removeAttribute("id")}}}return g(t.replace($,"$1"),e,n,r)}function ue(){var r=[];return function e(t,n){return r.push(t+" ")>b.cacheLength&&delete e[r.shift()],e[t+" "]=n}}function le(e){return e[S]=!0,e}function ce(e){var t=C.createElement("fieldset");try{return!!e(t)}catch(e){return!1}finally{t.parentNode&&t.parentNode.removeChild(t),t=null}}function fe(e,t){var n=e.split("|"),r=n.length;while(r--)b.attrHandle[n[r]]=t}function pe(e,t){var n=t&&e,r=n&&1===e.nodeType&&1===t.nodeType&&e.sourceIndex-t.sourceIndex;if(r)return r;if(n)while(n=n.nextSibling)if(n===t)return-1;return e?1:-1}function de(t){return function(e){return"input"===e.nodeName.toLowerCase()&&e.type===t}}function he(n){return function(e){var t=e.nodeName.toLowerCase();return("input"===t||"button"===t)&&e.type===n}}function ge(t){return function(e){return"form"in e?e.parentNode&&!1===e.disabled?"label"in e?"label"in e.parentNode?e.parentNode.disabled===t:e.disabled===t:e.isDisabled===t||e.isDisabled!==!t&&ae(e)===t:e.disabled===t:"label"in e&&e.disabled===t}}function ve(a){return le(function(o){return o=+o,le(function(e,t){var n,r=a([],e.length,o),i=r.length;while(i--)e[n=r[i]]&&(e[n]=!(t[n]=e[n]))})})}function ye(e){return e&&"undefined"!=typeof e.getElementsByTagName&&e}for(e in d=se.support={},i=se.isXML=function(e){var t=e&&e.namespaceURI,n=e&&(e.ownerDocument||e).documentElement;return!Y.test(t||n&&n.nodeName||"HTML")},T=se.setDocument=function(e){var t,n,r=e?e.ownerDocument||e:p;return r!=C&&9===r.nodeType&&r.documentElement&&(a=(C=r).documentElement,E=!i(C),p!=C&&(n=C.defaultView)&&n.top!==n&&(n.addEventListener?n.addEventListener("unload",oe,!1):n.attachEvent&&n.attachEvent("onunload",oe)),d.scope=ce(function(e){return a.appendChild(e).appendChild(C.createElement("div")),"undefined"!=typeof e.querySelectorAll&&!e.querySelectorAll(":scope fieldset div").length}),d.attributes=ce(function(e){return e.className="i",!e.getAttribute("className")}),d.getElementsByTagName=ce(function(e){return e.appendChild(C.createComment("")),!e.getElementsByTagName("*").length}),d.getElementsByClassName=K.test(C.getElementsByClassName),d.getById=ce(function(e){return a.appendChild(e).id=S,!C.getElementsByName||!C.getElementsByName(S).length}),d.getById?(b.filter.ID=function(e){var t=e.replace(te,ne);return function(e){return e.getAttribute("id")===t}},b.find.ID=function(e,t){if("undefined"!=typeof t.getElementById&&E){var n=t.getElementById(e);return n?[n]:[]}}):(b.filter.ID=function(e){var n=e.replace(te,ne);return function(e){var t="undefined"!=typeof e.getAttributeNode&&e.getAttributeNode("id");return t&&t.value===n}},b.find.ID=function(e,t){if("undefined"!=typeof t.getElementById&&E){var n,r,i,o=t.getElementById(e);if(o){if((n=o.getAttributeNode("id"))&&n.value===e)return[o];i=t.getElementsByName(e),r=0;while(o=i[r++])if((n=o.getAttributeNode("id"))&&n.value===e)return[o]}return[]}}),b.find.TAG=d.getElementsByTagName?function(e,t){return"undefined"!=typeof t.getElementsByTagName?t.getElementsByTagName(e):d.qsa?t.querySelectorAll(e):void 0}:function(e,t){var n,r=[],i=0,o=t.getElementsByTagName(e);if("*"===e){while(n=o[i++])1===n.nodeType&&r.push(n);return r}return o},b.find.CLASS=d.getElementsByClassName&&function(e,t){if("undefined"!=typeof t.getElementsByClassName&&E)return t.getElementsByClassName(e)},s=[],v=[],(d.qsa=K.test(C.querySelectorAll))&&(ce(function(e){var t;a.appendChild(e).innerHTML="<a id='"+S+"'></a><select id='"+S+"-\r\\' msallowcapture=''><option selected=''></option></select>",e.querySelectorAll("[msallowcapture^='']").length&&v.push("[*^$]="+M+"*(?:''|\"\")"),e.querySelectorAll("[selected]").length||v.push("\\["+M+"*(?:value|"+R+")"),e.querySelectorAll("[id~="+S+"-]").length||v.push("~="),(t=C.createElement("input")).setAttribute("name",""),e.appendChild(t),e.querySelectorAll("[name='']").length||v.push("\\["+M+"*name"+M+"*="+M+"*(?:''|\"\")"),e.querySelectorAll(":checked").length||v.push(":checked"),e.querySelectorAll("a#"+S+"+*").length||v.push(".#.+[+~]"),e.querySelectorAll("\\\f"),v.push("[\\r\\n\\f]")}),ce(function(e){e.innerHTML="<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";var t=C.createElement("input");t.setAttribute("type","hidden"),e.appendChild(t).setAttribute("name","D"),e.querySelectorAll("[name=d]").length&&v.push("name"+M+"*[*^$|!~]?="),2!==e.querySelectorAll(":enabled").length&&v.push(":enabled",":disabled"),a.appendChild(e).disabled=!0,2!==e.querySelectorAll(":disabled").length&&v.push(":enabled",":disabled"),e.querySelectorAll("*,:x"),v.push(",.*:")})),(d.matchesSelector=K.test(c=a.matches||a.webkitMatchesSelector||a.mozMatchesSelector||a.oMatchesSelector||a.msMatchesSelector))&&ce(function(e){d.disconnectedMatch=c.call(e,"*"),c.call(e,"[s!='']:x"),s.push("!=",F)}),v=v.length&&new RegExp(v.join("|")),s=s.length&&new RegExp(s.join("|")),t=K.test(a.compareDocumentPosition),y=t||K.test(a.contains)?function(e,t){var n=9===e.nodeType?e.documentElement:e,r=t&&t.parentNode;return e===r||!(!r||1!==r.nodeType||!(n.contains?n.contains(r):e.compareDocumentPosition&&16&e.compareDocumentPosition(r)))}:function(e,t){if(t)while(t=t.parentNode)if(t===e)return!0;return!1},j=t?function(e,t){if(e===t)return l=!0,0;var n=!e.compareDocumentPosition-!t.compareDocumentPosition;return n||(1&(n=(e.ownerDocument||e)==(t.ownerDocument||t)?e.compareDocumentPosition(t):1)||!d.sortDetached&&t.compareDocumentPosition(e)===n?e==C||e.ownerDocument==p&&y(p,e)?-1:t==C||t.ownerDocument==p&&y(p,t)?1:u?P(u,e)-P(u,t):0:4&n?-1:1)}:function(e,t){if(e===t)return l=!0,0;var n,r=0,i=e.parentNode,o=t.parentNode,a=[e],s=[t];if(!i||!o)return e==C?-1:t==C?1:i?-1:o?1:u?P(u,e)-P(u,t):0;if(i===o)return pe(e,t);n=e;while(n=n.parentNode)a.unshift(n);n=t;while(n=n.parentNode)s.unshift(n);while(a[r]===s[r])r++;return r?pe(a[r],s[r]):a[r]==p?-1:s[r]==p?1:0}),C},se.matches=function(e,t){return se(e,null,null,t)},se.matchesSelector=function(e,t){if(T(e),d.matchesSelector&&E&&!N[t+" "]&&(!s||!s.test(t))&&(!v||!v.test(t)))try{var n=c.call(e,t);if(n||d.disconnectedMatch||e.document&&11!==e.document.nodeType)return n}catch(e){N(t,!0)}return 0<se(t,C,null,[e]).length},se.contains=function(e,t){return(e.ownerDocument||e)!=C&&T(e),y(e,t)},se.attr=function(e,t){(e.ownerDocument||e)!=C&&T(e);var n=b.attrHandle[t.toLowerCase()],r=n&&D.call(b.attrHandle,t.toLowerCase())?n(e,t,!E):void 0;return void 0!==r?r:d.attributes||!E?e.getAttribute(t):(r=e.getAttributeNode(t))&&r.specified?r.value:null},se.escape=function(e){return(e+"").replace(re,ie)},se.error=function(e){throw new Error("Syntax error, unrecognized expression: "+e)},se.uniqueSort=function(e){var t,n=[],r=0,i=0;if(l=!d.detectDuplicates,u=!d.sortStable&&e.slice(0),e.sort(j),l){while(t=e[i++])t===e[i]&&(r=n.push(i));while(r--)e.splice(n[r],1)}return u=null,e},o=se.getText=function(e){var t,n="",r=0,i=e.nodeType;if(i){if(1===i||9===i||11===i){if("string"==typeof e.textContent)return e.textContent;for(e=e.firstChild;e;e=e.nextSibling)n+=o(e)}else if(3===i||4===i)return e.nodeValue}else while(t=e[r++])n+=o(t);return n},(b=se.selectors={cacheLength:50,createPseudo:le,match:G,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(e){return e[1]=e[1].replace(te,ne),e[3]=(e[3]||e[4]||e[5]||"").replace(te,ne),"~="===e[2]&&(e[3]=" "+e[3]+" "),e.slice(0,4)},CHILD:function(e){return e[1]=e[1].toLowerCase(),"nth"===e[1].slice(0,3)?(e[3]||se.error(e[0]),e[4]=+(e[4]?e[5]+(e[6]||1):2*("even"===e[3]||"odd"===e[3])),e[5]=+(e[7]+e[8]||"odd"===e[3])):e[3]&&se.error(e[0]),e},PSEUDO:function(e){var t,n=!e[6]&&e[2];return G.CHILD.test(e[0])?null:(e[3]?e[2]=e[4]||e[5]||"":n&&X.test(n)&&(t=h(n,!0))&&(t=n.indexOf(")",n.length-t)-n.length)&&(e[0]=e[0].slice(0,t),e[2]=n.slice(0,t)),e.slice(0,3))}},filter:{TAG:function(e){var t=e.replace(te,ne).toLowerCase();return"*"===e?function(){return!0}:function(e){return e.nodeName&&e.nodeName.toLowerCase()===t}},CLASS:function(e){var t=m[e+" "];return t||(t=new RegExp("(^|"+M+")"+e+"("+M+"|$)"))&&m(e,function(e){return t.test("string"==typeof e.className&&e.className||"undefined"!=typeof e.getAttribute&&e.getAttribute("class")||"")})},ATTR:function(n,r,i){return function(e){var t=se.attr(e,n);return null==t?"!="===r:!r||(t+="","="===r?t===i:"!="===r?t!==i:"^="===r?i&&0===t.indexOf(i):"*="===r?i&&-1<t.indexOf(i):"$="===r?i&&t.slice(-i.length)===i:"~="===r?-1<(" "+t.replace(B," ")+" ").indexOf(i):"|="===r&&(t===i||t.slice(0,i.length+1)===i+"-"))}},CHILD:function(h,e,t,g,v){var y="nth"!==h.slice(0,3),m="last"!==h.slice(-4),x="of-type"===e;return 1===g&&0===v?function(e){return!!e.parentNode}:function(e,t,n){var r,i,o,a,s,u,l=y!==m?"nextSibling":"previousSibling",c=e.parentNode,f=x&&e.nodeName.toLowerCase(),p=!n&&!x,d=!1;if(c){if(y){while(l){a=e;while(a=a[l])if(x?a.nodeName.toLowerCase()===f:1===a.nodeType)return!1;u=l="only"===h&&!u&&"nextSibling"}return!0}if(u=[m?c.firstChild:c.lastChild],m&&p){d=(s=(r=(i=(o=(a=c)[S]||(a[S]={}))[a.uniqueID]||(o[a.uniqueID]={}))[h]||[])[0]===k&&r[1])&&r[2],a=s&&c.childNodes[s];while(a=++s&&a&&a[l]||(d=s=0)||u.pop())if(1===a.nodeType&&++d&&a===e){i[h]=[k,s,d];break}}else if(p&&(d=s=(r=(i=(o=(a=e)[S]||(a[S]={}))[a.uniqueID]||(o[a.uniqueID]={}))[h]||[])[0]===k&&r[1]),!1===d)while(a=++s&&a&&a[l]||(d=s=0)||u.pop())if((x?a.nodeName.toLowerCase()===f:1===a.nodeType)&&++d&&(p&&((i=(o=a[S]||(a[S]={}))[a.uniqueID]||(o[a.uniqueID]={}))[h]=[k,d]),a===e))break;return(d-=v)===g||d%g==0&&0<=d/g}}},PSEUDO:function(e,o){var t,a=b.pseudos[e]||b.setFilters[e.toLowerCase()]||se.error("unsupported pseudo: "+e);return a[S]?a(o):1<a.length?(t=[e,e,"",o],b.setFilters.hasOwnProperty(e.toLowerCase())?le(function(e,t){var n,r=a(e,o),i=r.length;while(i--)e[n=P(e,r[i])]=!(t[n]=r[i])}):function(e){return a(e,0,t)}):a}},pseudos:{not:le(function(e){var r=[],i=[],s=f(e.replace($,"$1"));return s[S]?le(function(e,t,n,r){var i,o=s(e,null,r,[]),a=e.length;while(a--)(i=o[a])&&(e[a]=!(t[a]=i))}):function(e,t,n){return r[0]=e,s(r,null,n,i),r[0]=null,!i.pop()}}),has:le(function(t){return function(e){return 0<se(t,e).length}}),contains:le(function(t){return t=t.replace(te,ne),function(e){return-1<(e.textContent||o(e)).indexOf(t)}}),lang:le(function(n){return V.test(n||"")||se.error("unsupported lang: "+n),n=n.replace(te,ne).toLowerCase(),function(e){var t;do{if(t=E?e.lang:e.getAttribute("xml:lang")||e.getAttribute("lang"))return(t=t.toLowerCase())===n||0===t.indexOf(n+"-")}while((e=e.parentNode)&&1===e.nodeType);return!1}}),target:function(e){var t=n.location&&n.location.hash;return t&&t.slice(1)===e.id},root:function(e){return e===a},focus:function(e){return e===C.activeElement&&(!C.hasFocus||C.hasFocus())&&!!(e.type||e.href||~e.tabIndex)},enabled:ge(!1),disabled:ge(!0),checked:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&!!e.checked||"option"===t&&!!e.selected},selected:function(e){return e.parentNode&&e.parentNode.selectedIndex,!0===e.selected},empty:function(e){for(e=e.firstChild;e;e=e.nextSibling)if(e.nodeType<6)return!1;return!0},parent:function(e){return!b.pseudos.empty(e)},header:function(e){return J.test(e.nodeName)},input:function(e){return Q.test(e.nodeName)},button:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&"button"===e.type||"button"===t},text:function(e){var t;return"input"===e.nodeName.toLowerCase()&&"text"===e.type&&(null==(t=e.getAttribute("type"))||"text"===t.toLowerCase())},first:ve(function(){return[0]}),last:ve(function(e,t){return[t-1]}),eq:ve(function(e,t,n){return[n<0?n+t:n]}),even:ve(function(e,t){for(var n=0;n<t;n+=2)e.push(n);return e}),odd:ve(function(e,t){for(var n=1;n<t;n+=2)e.push(n);return e}),lt:ve(function(e,t,n){for(var r=n<0?n+t:t<n?t:n;0<=--r;)e.push(r);return e}),gt:ve(function(e,t,n){for(var r=n<0?n+t:n;++r<t;)e.push(r);return e})}}).pseudos.nth=b.pseudos.eq,{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})b.pseudos[e]=de(e);for(e in{submit:!0,reset:!0})b.pseudos[e]=he(e);function me(){}function xe(e){for(var t=0,n=e.length,r="";t<n;t++)r+=e[t].value;return r}function be(s,e,t){var u=e.dir,l=e.next,c=l||u,f=t&&"parentNode"===c,p=r++;return e.first?function(e,t,n){while(e=e[u])if(1===e.nodeType||f)return s(e,t,n);return!1}:function(e,t,n){var r,i,o,a=[k,p];if(n){while(e=e[u])if((1===e.nodeType||f)&&s(e,t,n))return!0}else while(e=e[u])if(1===e.nodeType||f)if(i=(o=e[S]||(e[S]={}))[e.uniqueID]||(o[e.uniqueID]={}),l&&l===e.nodeName.toLowerCase())e=e[u]||e;else{if((r=i[c])&&r[0]===k&&r[1]===p)return a[2]=r[2];if((i[c]=a)[2]=s(e,t,n))return!0}return!1}}function we(i){return 1<i.length?function(e,t,n){var r=i.length;while(r--)if(!i[r](e,t,n))return!1;return!0}:i[0]}function Te(e,t,n,r,i){for(var o,a=[],s=0,u=e.length,l=null!=t;s<u;s++)(o=e[s])&&(n&&!n(o,r,i)||(a.push(o),l&&t.push(s)));return a}function Ce(d,h,g,v,y,e){return v&&!v[S]&&(v=Ce(v)),y&&!y[S]&&(y=Ce(y,e)),le(function(e,t,n,r){var i,o,a,s=[],u=[],l=t.length,c=e||function(e,t,n){for(var r=0,i=t.length;r<i;r++)se(e,t[r],n);return n}(h||"*",n.nodeType?[n]:n,[]),f=!d||!e&&h?c:Te(c,s,d,n,r),p=g?y||(e?d:l||v)?[]:t:f;if(g&&g(f,p,n,r),v){i=Te(p,u),v(i,[],n,r),o=i.length;while(o--)(a=i[o])&&(p[u[o]]=!(f[u[o]]=a))}if(e){if(y||d){if(y){i=[],o=p.length;while(o--)(a=p[o])&&i.push(f[o]=a);y(null,p=[],i,r)}o=p.length;while(o--)(a=p[o])&&-1<(i=y?P(e,a):s[o])&&(e[i]=!(t[i]=a))}}else p=Te(p===t?p.splice(l,p.length):p),y?y(null,t,p,r):H.apply(t,p)})}function Ee(e){for(var i,t,n,r=e.length,o=b.relative[e[0].type],a=o||b.relative[" "],s=o?1:0,u=be(function(e){return e===i},a,!0),l=be(function(e){return-1<P(i,e)},a,!0),c=[function(e,t,n){var r=!o&&(n||t!==w)||((i=t).nodeType?u(e,t,n):l(e,t,n));return i=null,r}];s<r;s++)if(t=b.relative[e[s].type])c=[be(we(c),t)];else{if((t=b.filter[e[s].type].apply(null,e[s].matches))[S]){for(n=++s;n<r;n++)if(b.relative[e[n].type])break;return Ce(1<s&&we(c),1<s&&xe(e.slice(0,s-1).concat({value:" "===e[s-2].type?"*":""})).replace($,"$1"),t,s<n&&Ee(e.slice(s,n)),n<r&&Ee(e=e.slice(n)),n<r&&xe(e))}c.push(t)}return we(c)}return me.prototype=b.filters=b.pseudos,b.setFilters=new me,h=se.tokenize=function(e,t){var n,r,i,o,a,s,u,l=x[e+" "];if(l)return t?0:l.slice(0);a=e,s=[],u=b.preFilter;while(a){for(o in n&&!(r=_.exec(a))||(r&&(a=a.slice(r[0].length)||a),s.push(i=[])),n=!1,(r=z.exec(a))&&(n=r.shift(),i.push({value:n,type:r[0].replace($," ")}),a=a.slice(n.length)),b.filter)!(r=G[o].exec(a))||u[o]&&!(r=u[o](r))||(n=r.shift(),i.push({value:n,type:o,matches:r}),a=a.slice(n.length));if(!n)break}return t?a.length:a?se.error(e):x(e,s).slice(0)},f=se.compile=function(e,t){var n,v,y,m,x,r,i=[],o=[],a=A[e+" "];if(!a){t||(t=h(e)),n=t.length;while(n--)(a=Ee(t[n]))[S]?i.push(a):o.push(a);(a=A(e,(v=o,m=0<(y=i).length,x=0<v.length,r=function(e,t,n,r,i){var o,a,s,u=0,l="0",c=e&&[],f=[],p=w,d=e||x&&b.find.TAG("*",i),h=k+=null==p?1:Math.random()||.1,g=d.length;for(i&&(w=t==C||t||i);l!==g&&null!=(o=d[l]);l++){if(x&&o){a=0,t||o.ownerDocument==C||(T(o),n=!E);while(s=v[a++])if(s(o,t||C,n)){r.push(o);break}i&&(k=h)}m&&((o=!s&&o)&&u--,e&&c.push(o))}if(u+=l,m&&l!==u){a=0;while(s=y[a++])s(c,f,t,n);if(e){if(0<u)while(l--)c[l]||f[l]||(f[l]=q.call(r));f=Te(f)}H.apply(r,f),i&&!e&&0<f.length&&1<u+y.length&&se.uniqueSort(r)}return i&&(k=h,w=p),c},m?le(r):r))).selector=e}return a},g=se.select=function(e,t,n,r){var i,o,a,s,u,l="function"==typeof e&&e,c=!r&&h(e=l.selector||e);if(n=n||[],1===c.length){if(2<(o=c[0]=c[0].slice(0)).length&&"ID"===(a=o[0]).type&&9===t.nodeType&&E&&b.relative[o[1].type]){if(!(t=(b.find.ID(a.matches[0].replace(te,ne),t)||[])[0]))return n;l&&(t=t.parentNode),e=e.slice(o.shift().value.length)}i=G.needsContext.test(e)?0:o.length;while(i--){if(a=o[i],b.relative[s=a.type])break;if((u=b.find[s])&&(r=u(a.matches[0].replace(te,ne),ee.test(o[0].type)&&ye(t.parentNode)||t))){if(o.splice(i,1),!(e=r.length&&xe(o)))return H.apply(n,r),n;break}}}return(l||f(e,c))(r,t,!E,n,!t||ee.test(e)&&ye(t.parentNode)||t),n},d.sortStable=S.split("").sort(j).join("")===S,d.detectDuplicates=!!l,T(),d.sortDetached=ce(function(e){return 1&e.compareDocumentPosition(C.createElement("fieldset"))}),ce(function(e){return e.innerHTML="<a href='#'></a>","#"===e.firstChild.getAttribute("href")})||fe("type|href|height|width",function(e,t,n){if(!n)return e.getAttribute(t,"type"===t.toLowerCase()?1:2)}),d.attributes&&ce(function(e){return e.innerHTML="<input/>",e.firstChild.setAttribute("value",""),""===e.firstChild.getAttribute("value")})||fe("value",function(e,t,n){if(!n&&"input"===e.nodeName.toLowerCase())return e.defaultValue}),ce(function(e){return null==e.getAttribute("disabled")})||fe(R,function(e,t,n){var r;if(!n)return!0===e[t]?t.toLowerCase():(r=e.getAttributeNode(t))&&r.specified?r.value:null}),se}(C);S.find=d,S.expr=d.selectors,S.expr[":"]=S.expr.pseudos,S.uniqueSort=S.unique=d.uniqueSort,S.text=d.getText,S.isXMLDoc=d.isXML,S.contains=d.contains,S.escapeSelector=d.escape;var h=function(e,t,n){var r=[],i=void 0!==n;while((e=e[t])&&9!==e.nodeType)if(1===e.nodeType){if(i&&S(e).is(n))break;r.push(e)}return r},T=function(e,t){for(var n=[];e;e=e.nextSibling)1===e.nodeType&&e!==t&&n.push(e);return n},k=S.expr.match.needsContext;function A(e,t){return e.nodeName&&e.nodeName.toLowerCase()===t.toLowerCase()}var N=/^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;function j(e,n,r){return m(n)?S.grep(e,function(e,t){return!!n.call(e,t,e)!==r}):n.nodeType?S.grep(e,function(e){return e===n!==r}):"string"!=typeof n?S.grep(e,function(e){return-1<i.call(n,e)!==r}):S.filter(n,e,r)}S.filter=function(e,t,n){var r=t[0];return n&&(e=":not("+e+")"),1===t.length&&1===r.nodeType?S.find.matchesSelector(r,e)?[r]:[]:S.find.matches(e,S.grep(t,function(e){return 1===e.nodeType}))},S.fn.extend({find:function(e){var t,n,r=this.length,i=this;if("string"!=typeof e)return this.pushStack(S(e).filter(function(){for(t=0;t<r;t++)if(S.contains(i[t],this))return!0}));for(n=this.pushStack([]),t=0;t<r;t++)S.find(e,i[t],n);return 1<r?S.uniqueSort(n):n},filter:function(e){return this.pushStack(j(this,e||[],!1))},not:function(e){return this.pushStack(j(this,e||[],!0))},is:function(e){return!!j(this,"string"==typeof e&&k.test(e)?S(e):e||[],!1).length}});var D,q=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;(S.fn.init=function(e,t,n){var r,i;if(!e)return this;if(n=n||D,"string"==typeof e){if(!(r="<"===e[0]&&">"===e[e.length-1]&&3<=e.length?[null,e,null]:q.exec(e))||!r[1]&&t)return!t||t.jquery?(t||n).find(e):this.constructor(t).find(e);if(r[1]){if(t=t instanceof S?t[0]:t,S.merge(this,S.parseHTML(r[1],t&&t.nodeType?t.ownerDocument||t:E,!0)),N.test(r[1])&&S.isPlainObject(t))for(r in t)m(this[r])?this[r](t[r]):this.attr(r,t[r]);return this}return(i=E.getElementById(r[2]))&&(this[0]=i,this.length=1),this}return e.nodeType?(this[0]=e,this.length=1,this):m(e)?void 0!==n.ready?n.ready(e):e(S):S.makeArray(e,this)}).prototype=S.fn,D=S(E);var L=/^(?:parents|prev(?:Until|All))/,H={children:!0,contents:!0,next:!0,prev:!0};function O(e,t){while((e=e[t])&&1!==e.nodeType);return e}S.fn.extend({has:function(e){var t=S(e,this),n=t.length;return this.filter(function(){for(var e=0;e<n;e++)if(S.contains(this,t[e]))return!0})},closest:function(e,t){var n,r=0,i=this.length,o=[],a="string"!=typeof e&&S(e);if(!k.test(e))for(;r<i;r++)for(n=this[r];n&&n!==t;n=n.parentNode)if(n.nodeType<11&&(a?-1<a.index(n):1===n.nodeType&&S.find.matchesSelector(n,e))){o.push(n);break}return this.pushStack(1<o.length?S.uniqueSort(o):o)},index:function(e){return e?"string"==typeof e?i.call(S(e),this[0]):i.call(this,e.jquery?e[0]:e):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(e,t){return this.pushStack(S.uniqueSort(S.merge(this.get(),S(e,t))))},addBack:function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}}),S.each({parent:function(e){var t=e.parentNode;return t&&11!==t.nodeType?t:null},parents:function(e){return h(e,"parentNode")},parentsUntil:function(e,t,n){return h(e,"parentNode",n)},next:function(e){return O(e,"nextSibling")},prev:function(e){return O(e,"previousSibling")},nextAll:function(e){return h(e,"nextSibling")},prevAll:function(e){return h(e,"previousSibling")},nextUntil:function(e,t,n){return h(e,"nextSibling",n)},prevUntil:function(e,t,n){return h(e,"previousSibling",n)},siblings:function(e){return T((e.parentNode||{}).firstChild,e)},children:function(e){return T(e.firstChild)},contents:function(e){return null!=e.contentDocument&&r(e.contentDocument)?e.contentDocument:(A(e,"template")&&(e=e.content||e),S.merge([],e.childNodes))}},function(r,i){S.fn[r]=function(e,t){var n=S.map(this,i,e);return"Until"!==r.slice(-5)&&(t=e),t&&"string"==typeof t&&(n=S.filter(t,n)),1<this.length&&(H[r]||S.uniqueSort(n),L.test(r)&&n.reverse()),this.pushStack(n)}});var P=/[^\x20\t\r\n\f]+/g;function R(e){return e}function M(e){throw e}function I(e,t,n,r){var i;try{e&&m(i=e.promise)?i.call(e).done(t).fail(n):e&&m(i=e.then)?i.call(e,t,n):t.apply(void 0,[e].slice(r))}catch(e){n.apply(void 0,[e])}}S.Callbacks=function(r){var e,n;r="string"==typeof r?(e=r,n={},S.each(e.match(P)||[],function(e,t){n[t]=!0}),n):S.extend({},r);var i,t,o,a,s=[],u=[],l=-1,c=function(){for(a=a||r.once,o=i=!0;u.length;l=-1){t=u.shift();while(++l<s.length)!1===s[l].apply(t[0],t[1])&&r.stopOnFalse&&(l=s.length,t=!1)}r.memory||(t=!1),i=!1,a&&(s=t?[]:"")},f={add:function(){return s&&(t&&!i&&(l=s.length-1,u.push(t)),function n(e){S.each(e,function(e,t){m(t)?r.unique&&f.has(t)||s.push(t):t&&t.length&&"string"!==w(t)&&n(t)})}(arguments),t&&!i&&c()),this},remove:function(){return S.each(arguments,function(e,t){var n;while(-1<(n=S.inArray(t,s,n)))s.splice(n,1),n<=l&&l--}),this},has:function(e){return e?-1<S.inArray(e,s):0<s.length},empty:function(){return s&&(s=[]),this},disable:function(){return a=u=[],s=t="",this},disabled:function(){return!s},lock:function(){return a=u=[],t||i||(s=t=""),this},locked:function(){return!!a},fireWith:function(e,t){return a||(t=[e,(t=t||[]).slice?t.slice():t],u.push(t),i||c()),this},fire:function(){return f.fireWith(this,arguments),this},fired:function(){return!!o}};return f},S.extend({Deferred:function(e){var o=[["notify","progress",S.Callbacks("memory"),S.Callbacks("memory"),2],["resolve","done",S.Callbacks("once memory"),S.Callbacks("once memory"),0,"resolved"],["reject","fail",S.Callbacks("once memory"),S.Callbacks("once memory"),1,"rejected"]],i="pending",a={state:function(){return i},always:function(){return s.done(arguments).fail(arguments),this},"catch":function(e){return a.then(null,e)},pipe:function(){var i=arguments;return S.Deferred(function(r){S.each(o,function(e,t){var n=m(i[t[4]])&&i[t[4]];s[t[1]](function(){var e=n&&n.apply(this,arguments);e&&m(e.promise)?e.promise().progress(r.notify).done(r.resolve).fail(r.reject):r[t[0]+"With"](this,n?[e]:arguments)})}),i=null}).promise()},then:function(t,n,r){var u=0;function l(i,o,a,s){return function(){var n=this,r=arguments,e=function(){var e,t;if(!(i<u)){if((e=a.apply(n,r))===o.promise())throw new TypeError("Thenable self-resolution");t=e&&("object"==typeof e||"function"==typeof e)&&e.then,m(t)?s?t.call(e,l(u,o,R,s),l(u,o,M,s)):(u++,t.call(e,l(u,o,R,s),l(u,o,M,s),l(u,o,R,o.notifyWith))):(a!==R&&(n=void 0,r=[e]),(s||o.resolveWith)(n,r))}},t=s?e:function(){try{e()}catch(e){S.Deferred.exceptionHook&&S.Deferred.exceptionHook(e,t.stackTrace),u<=i+1&&(a!==M&&(n=void 0,r=[e]),o.rejectWith(n,r))}};i?t():(S.Deferred.getStackHook&&(t.stackTrace=S.Deferred.getStackHook()),C.setTimeout(t))}}return S.Deferred(function(e){o[0][3].add(l(0,e,m(r)?r:R,e.notifyWith)),o[1][3].add(l(0,e,m(t)?t:R)),o[2][3].add(l(0,e,m(n)?n:M))}).promise()},promise:function(e){return null!=e?S.extend(e,a):a}},s={};return S.each(o,function(e,t){var n=t[2],r=t[5];a[t[1]]=n.add,r&&n.add(function(){i=r},o[3-e][2].disable,o[3-e][3].disable,o[0][2].lock,o[0][3].lock),n.add(t[3].fire),s[t[0]]=function(){return s[t[0]+"With"](this===s?void 0:this,arguments),this},s[t[0]+"With"]=n.fireWith}),a.promise(s),e&&e.call(s,s),s},when:function(e){var n=arguments.length,t=n,r=Array(t),i=s.call(arguments),o=S.Deferred(),a=function(t){return function(e){r[t]=this,i[t]=1<arguments.length?s.call(arguments):e,--n||o.resolveWith(r,i)}};if(n<=1&&(I(e,o.done(a(t)).resolve,o.reject,!n),"pending"===o.state()||m(i[t]&&i[t].then)))return o.then();while(t--)I(i[t],a(t),o.reject);return o.promise()}});var W=/^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;S.Deferred.exceptionHook=function(e,t){C.console&&C.console.warn&&e&&W.test(e.name)&&C.console.warn("jQuery.Deferred exception: "+e.message,e.stack,t)},S.readyException=function(e){C.setTimeout(function(){throw e})};var F=S.Deferred();function B(){E.removeEventListener("DOMContentLoaded",B),C.removeEventListener("load",B),S.ready()}S.fn.ready=function(e){return F.then(e)["catch"](function(e){S.readyException(e)}),this},S.extend({isReady:!1,readyWait:1,ready:function(e){(!0===e?--S.readyWait:S.isReady)||(S.isReady=!0)!==e&&0<--S.readyWait||F.resolveWith(E,[S])}}),S.ready.then=F.then,"complete"===E.readyState||"loading"!==E.readyState&&!E.documentElement.doScroll?C.setTimeout(S.ready):(E.addEventListener("DOMContentLoaded",B),C.addEventListener("load",B));var $=function(e,t,n,r,i,o,a){var s=0,u=e.length,l=null==n;if("object"===w(n))for(s in i=!0,n)$(e,t,s,n[s],!0,o,a);else if(void 0!==r&&(i=!0,m(r)||(a=!0),l&&(a?(t.call(e,r),t=null):(l=t,t=function(e,t,n){return l.call(S(e),n)})),t))for(;s<u;s++)t(e[s],n,a?r:r.call(e[s],s,t(e[s],n)));return i?e:l?t.call(e):u?t(e[0],n):o},_=/^-ms-/,z=/-([a-z])/g;function U(e,t){return t.toUpperCase()}function X(e){return e.replace(_,"ms-").replace(z,U)}var V=function(e){return 1===e.nodeType||9===e.nodeType||!+e.nodeType};function G(){this.expando=S.expando+G.uid++}G.uid=1,G.prototype={cache:function(e){var t=e[this.expando];return t||(t={},V(e)&&(e.nodeType?e[this.expando]=t:Object.defineProperty(e,this.expando,{value:t,configurable:!0}))),t},set:function(e,t,n){var r,i=this.cache(e);if("string"==typeof t)i[X(t)]=n;else for(r in t)i[X(r)]=t[r];return i},get:function(e,t){return void 0===t?this.cache(e):e[this.expando]&&e[this.expando][X(t)]},access:function(e,t,n){return void 0===t||t&&"string"==typeof t&&void 0===n?this.get(e,t):(this.set(e,t,n),void 0!==n?n:t)},remove:function(e,t){var n,r=e[this.expando];if(void 0!==r){if(void 0!==t){n=(t=Array.isArray(t)?t.map(X):(t=X(t))in r?[t]:t.match(P)||[]).length;while(n--)delete r[t[n]]}(void 0===t||S.isEmptyObject(r))&&(e.nodeType?e[this.expando]=void 0:delete e[this.expando])}},hasData:function(e){var t=e[this.expando];return void 0!==t&&!S.isEmptyObject(t)}};var Y=new G,Q=new G,J=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,K=/[A-Z]/g;function Z(e,t,n){var r,i;if(void 0===n&&1===e.nodeType)if(r="data-"+t.replace(K,"-$&").toLowerCase(),"string"==typeof(n=e.getAttribute(r))){try{n="true"===(i=n)||"false"!==i&&("null"===i?null:i===+i+""?+i:J.test(i)?JSON.parse(i):i)}catch(e){}Q.set(e,t,n)}else n=void 0;return n}S.extend({hasData:function(e){return Q.hasData(e)||Y.hasData(e)},data:function(e,t,n){return Q.access(e,t,n)},removeData:function(e,t){Q.remove(e,t)},_data:function(e,t,n){return Y.access(e,t,n)},_removeData:function(e,t){Y.remove(e,t)}}),S.fn.extend({data:function(n,e){var t,r,i,o=this[0],a=o&&o.attributes;if(void 0===n){if(this.length&&(i=Q.get(o),1===o.nodeType&&!Y.get(o,"hasDataAttrs"))){t=a.length;while(t--)a[t]&&0===(r=a[t].name).indexOf("data-")&&(r=X(r.slice(5)),Z(o,r,i[r]));Y.set(o,"hasDataAttrs",!0)}return i}return"object"==typeof n?this.each(function(){Q.set(this,n)}):$(this,function(e){var t;if(o&&void 0===e)return void 0!==(t=Q.get(o,n))?t:void 0!==(t=Z(o,n))?t:void 0;this.each(function(){Q.set(this,n,e)})},null,e,1<arguments.length,null,!0)},removeData:function(e){return this.each(function(){Q.remove(this,e)})}}),S.extend({queue:function(e,t,n){var r;if(e)return t=(t||"fx")+"queue",r=Y.get(e,t),n&&(!r||Array.isArray(n)?r=Y.access(e,t,S.makeArray(n)):r.push(n)),r||[]},dequeue:function(e,t){t=t||"fx";var n=S.queue(e,t),r=n.length,i=n.shift(),o=S._queueHooks(e,t);"inprogress"===i&&(i=n.shift(),r--),i&&("fx"===t&&n.unshift("inprogress"),delete o.stop,i.call(e,function(){S.dequeue(e,t)},o)),!r&&o&&o.empty.fire()},_queueHooks:function(e,t){var n=t+"queueHooks";return Y.get(e,n)||Y.access(e,n,{empty:S.Callbacks("once memory").add(function(){Y.remove(e,[t+"queue",n])})})}}),S.fn.extend({queue:function(t,n){var e=2;return"string"!=typeof t&&(n=t,t="fx",e--),arguments.length<e?S.queue(this[0],t):void 0===n?this:this.each(function(){var e=S.queue(this,t,n);S._queueHooks(this,t),"fx"===t&&"inprogress"!==e[0]&&S.dequeue(this,t)})},dequeue:function(e){return this.each(function(){S.dequeue(this,e)})},clearQueue:function(e){return this.queue(e||"fx",[])},promise:function(e,t){var n,r=1,i=S.Deferred(),o=this,a=this.length,s=function(){--r||i.resolveWith(o,[o])};"string"!=typeof e&&(t=e,e=void 0),e=e||"fx";while(a--)(n=Y.get(o[a],e+"queueHooks"))&&n.empty&&(r++,n.empty.add(s));return s(),i.promise(t)}});var ee=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,te=new RegExp("^(?:([+-])=|)("+ee+")([a-z%]*)$","i"),ne=["Top","Right","Bottom","Left"],re=E.documentElement,ie=function(e){return S.contains(e.ownerDocument,e)},oe={composed:!0};re.getRootNode&&(ie=function(e){return S.contains(e.ownerDocument,e)||e.getRootNode(oe)===e.ownerDocument});var ae=function(e,t){return"none"===(e=t||e).style.display||""===e.style.display&&ie(e)&&"none"===S.css(e,"display")};function se(e,t,n,r){var i,o,a=20,s=r?function(){return r.cur()}:function(){return S.css(e,t,"")},u=s(),l=n&&n[3]||(S.cssNumber[t]?"":"px"),c=e.nodeType&&(S.cssNumber[t]||"px"!==l&&+u)&&te.exec(S.css(e,t));if(c&&c[3]!==l){u/=2,l=l||c[3],c=+u||1;while(a--)S.style(e,t,c+l),(1-o)*(1-(o=s()/u||.5))<=0&&(a=0),c/=o;c*=2,S.style(e,t,c+l),n=n||[]}return n&&(c=+c||+u||0,i=n[1]?c+(n[1]+1)*n[2]:+n[2],r&&(r.unit=l,r.start=c,r.end=i)),i}var ue={};function le(e,t){for(var n,r,i,o,a,s,u,l=[],c=0,f=e.length;c<f;c++)(r=e[c]).style&&(n=r.style.display,t?("none"===n&&(l[c]=Y.get(r,"display")||null,l[c]||(r.style.display="")),""===r.style.display&&ae(r)&&(l[c]=(u=a=o=void 0,a=(i=r).ownerDocument,s=i.nodeName,(u=ue[s])||(o=a.body.appendChild(a.createElement(s)),u=S.css(o,"display"),o.parentNode.removeChild(o),"none"===u&&(u="block"),ue[s]=u)))):"none"!==n&&(l[c]="none",Y.set(r,"display",n)));for(c=0;c<f;c++)null!=l[c]&&(e[c].style.display=l[c]);return e}S.fn.extend({show:function(){return le(this,!0)},hide:function(){return le(this)},toggle:function(e){return"boolean"==typeof e?e?this.show():this.hide():this.each(function(){ae(this)?S(this).show():S(this).hide()})}});var ce,fe,pe=/^(?:checkbox|radio)$/i,de=/<([a-z][^\/\0>\x20\t\r\n\f]*)/i,he=/^$|^module$|\/(?:java|ecma)script/i;ce=E.createDocumentFragment().appendChild(E.createElement("div")),(fe=E.createElement("input")).setAttribute("type","radio"),fe.setAttribute("checked","checked"),fe.setAttribute("name","t"),ce.appendChild(fe),y.checkClone=ce.cloneNode(!0).cloneNode(!0).lastChild.checked,ce.innerHTML="<textarea>x</textarea>",y.noCloneChecked=!!ce.cloneNode(!0).lastChild.defaultValue,ce.innerHTML="<option></option>",y.option=!!ce.lastChild;var ge={thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};function ve(e,t){var n;return n="undefined"!=typeof e.getElementsByTagName?e.getElementsByTagName(t||"*"):"undefined"!=typeof e.querySelectorAll?e.querySelectorAll(t||"*"):[],void 0===t||t&&A(e,t)?S.merge([e],n):n}function ye(e,t){for(var n=0,r=e.length;n<r;n++)Y.set(e[n],"globalEval",!t||Y.get(t[n],"globalEval"))}ge.tbody=ge.tfoot=ge.colgroup=ge.caption=ge.thead,ge.th=ge.td,y.option||(ge.optgroup=ge.option=[1,"<select multiple='multiple'>","</select>"]);var me=/<|&#?\w+;/;function xe(e,t,n,r,i){for(var o,a,s,u,l,c,f=t.createDocumentFragment(),p=[],d=0,h=e.length;d<h;d++)if((o=e[d])||0===o)if("object"===w(o))S.merge(p,o.nodeType?[o]:o);else if(me.test(o)){a=a||f.appendChild(t.createElement("div")),s=(de.exec(o)||["",""])[1].toLowerCase(),u=ge[s]||ge._default,a.innerHTML=u[1]+S.htmlPrefilter(o)+u[2],c=u[0];while(c--)a=a.lastChild;S.merge(p,a.childNodes),(a=f.firstChild).textContent=""}else p.push(t.createTextNode(o));f.textContent="",d=0;while(o=p[d++])if(r&&-1<S.inArray(o,r))i&&i.push(o);else if(l=ie(o),a=ve(f.appendChild(o),"script"),l&&ye(a),n){c=0;while(o=a[c++])he.test(o.type||"")&&n.push(o)}return f}var be=/^([^.]*)(?:\.(.+)|)/;function we(){return!0}function Te(){return!1}function Ce(e,t){return e===function(){try{return E.activeElement}catch(e){}}()==("focus"===t)}function Ee(e,t,n,r,i,o){var a,s;if("object"==typeof t){for(s in"string"!=typeof n&&(r=r||n,n=void 0),t)Ee(e,s,n,r,t[s],o);return e}if(null==r&&null==i?(i=n,r=n=void 0):null==i&&("string"==typeof n?(i=r,r=void 0):(i=r,r=n,n=void 0)),!1===i)i=Te;else if(!i)return e;return 1===o&&(a=i,(i=function(e){return S().off(e),a.apply(this,arguments)}).guid=a.guid||(a.guid=S.guid++)),e.each(function(){S.event.add(this,t,i,r,n)})}function Se(e,i,o){o?(Y.set(e,i,!1),S.event.add(e,i,{namespace:!1,handler:function(e){var t,n,r=Y.get(this,i);if(1&e.isTrigger&&this[i]){if(r.length)(S.event.special[i]||{}).delegateType&&e.stopPropagation();else if(r=s.call(arguments),Y.set(this,i,r),t=o(this,i),this[i](),r!==(n=Y.get(this,i))||t?Y.set(this,i,!1):n={},r!==n)return e.stopImmediatePropagation(),e.preventDefault(),n&&n.value}else r.length&&(Y.set(this,i,{value:S.event.trigger(S.extend(r[0],S.Event.prototype),r.slice(1),this)}),e.stopImmediatePropagation())}})):void 0===Y.get(e,i)&&S.event.add(e,i,we)}S.event={global:{},add:function(t,e,n,r,i){var o,a,s,u,l,c,f,p,d,h,g,v=Y.get(t);if(V(t)){n.handler&&(n=(o=n).handler,i=o.selector),i&&S.find.matchesSelector(re,i),n.guid||(n.guid=S.guid++),(u=v.events)||(u=v.events=Object.create(null)),(a=v.handle)||(a=v.handle=function(e){return"undefined"!=typeof S&&S.event.triggered!==e.type?S.event.dispatch.apply(t,arguments):void 0}),l=(e=(e||"").match(P)||[""]).length;while(l--)d=g=(s=be.exec(e[l])||[])[1],h=(s[2]||"").split(".").sort(),d&&(f=S.event.special[d]||{},d=(i?f.delegateType:f.bindType)||d,f=S.event.special[d]||{},c=S.extend({type:d,origType:g,data:r,handler:n,guid:n.guid,selector:i,needsContext:i&&S.expr.match.needsContext.test(i),namespace:h.join(".")},o),(p=u[d])||((p=u[d]=[]).delegateCount=0,f.setup&&!1!==f.setup.call(t,r,h,a)||t.addEventListener&&t.addEventListener(d,a)),f.add&&(f.add.call(t,c),c.handler.guid||(c.handler.guid=n.guid)),i?p.splice(p.delegateCount++,0,c):p.push(c),S.event.global[d]=!0)}},remove:function(e,t,n,r,i){var o,a,s,u,l,c,f,p,d,h,g,v=Y.hasData(e)&&Y.get(e);if(v&&(u=v.events)){l=(t=(t||"").match(P)||[""]).length;while(l--)if(d=g=(s=be.exec(t[l])||[])[1],h=(s[2]||"").split(".").sort(),d){f=S.event.special[d]||{},p=u[d=(r?f.delegateType:f.bindType)||d]||[],s=s[2]&&new RegExp("(^|\\.)"+h.join("\\.(?:.*\\.|)")+"(\\.|$)"),a=o=p.length;while(o--)c=p[o],!i&&g!==c.origType||n&&n.guid!==c.guid||s&&!s.test(c.namespace)||r&&r!==c.selector&&("**"!==r||!c.selector)||(p.splice(o,1),c.selector&&p.delegateCount--,f.remove&&f.remove.call(e,c));a&&!p.length&&(f.teardown&&!1!==f.teardown.call(e,h,v.handle)||S.removeEvent(e,d,v.handle),delete u[d])}else for(d in u)S.event.remove(e,d+t[l],n,r,!0);S.isEmptyObject(u)&&Y.remove(e,"handle events")}},dispatch:function(e){var t,n,r,i,o,a,s=new Array(arguments.length),u=S.event.fix(e),l=(Y.get(this,"events")||Object.create(null))[u.type]||[],c=S.event.special[u.type]||{};for(s[0]=u,t=1;t<arguments.length;t++)s[t]=arguments[t];if(u.delegateTarget=this,!c.preDispatch||!1!==c.preDispatch.call(this,u)){a=S.event.handlers.call(this,u,l),t=0;while((i=a[t++])&&!u.isPropagationStopped()){u.currentTarget=i.elem,n=0;while((o=i.handlers[n++])&&!u.isImmediatePropagationStopped())u.rnamespace&&!1!==o.namespace&&!u.rnamespace.test(o.namespace)||(u.handleObj=o,u.data=o.data,void 0!==(r=((S.event.special[o.origType]||{}).handle||o.handler).apply(i.elem,s))&&!1===(u.result=r)&&(u.preventDefault(),u.stopPropagation()))}return c.postDispatch&&c.postDispatch.call(this,u),u.result}},handlers:function(e,t){var n,r,i,o,a,s=[],u=t.delegateCount,l=e.target;if(u&&l.nodeType&&!("click"===e.type&&1<=e.button))for(;l!==this;l=l.parentNode||this)if(1===l.nodeType&&("click"!==e.type||!0!==l.disabled)){for(o=[],a={},n=0;n<u;n++)void 0===a[i=(r=t[n]).selector+" "]&&(a[i]=r.needsContext?-1<S(i,this).index(l):S.find(i,this,null,[l]).length),a[i]&&o.push(r);o.length&&s.push({elem:l,handlers:o})}return l=this,u<t.length&&s.push({elem:l,handlers:t.slice(u)}),s},addProp:function(t,e){Object.defineProperty(S.Event.prototype,t,{enumerable:!0,configurable:!0,get:m(e)?function(){if(this.originalEvent)return e(this.originalEvent)}:function(){if(this.originalEvent)return this.originalEvent[t]},set:function(e){Object.defineProperty(this,t,{enumerable:!0,configurable:!0,writable:!0,value:e})}})},fix:function(e){return e[S.expando]?e:new S.Event(e)},special:{load:{noBubble:!0},click:{setup:function(e){var t=this||e;return pe.test(t.type)&&t.click&&A(t,"input")&&Se(t,"click",we),!1},trigger:function(e){var t=this||e;return pe.test(t.type)&&t.click&&A(t,"input")&&Se(t,"click"),!0},_default:function(e){var t=e.target;return pe.test(t.type)&&t.click&&A(t,"input")&&Y.get(t,"click")||A(t,"a")}},beforeunload:{postDispatch:function(e){void 0!==e.result&&e.originalEvent&&(e.originalEvent.returnValue=e.result)}}}},S.removeEvent=function(e,t,n){e.removeEventListener&&e.removeEventListener(t,n)},S.Event=function(e,t){if(!(this instanceof S.Event))return new S.Event(e,t);e&&e.type?(this.originalEvent=e,this.type=e.type,this.isDefaultPrevented=e.defaultPrevented||void 0===e.defaultPrevented&&!1===e.returnValue?we:Te,this.target=e.target&&3===e.target.nodeType?e.target.parentNode:e.target,this.currentTarget=e.currentTarget,this.relatedTarget=e.relatedTarget):this.type=e,t&&S.extend(this,t),this.timeStamp=e&&e.timeStamp||Date.now(),this[S.expando]=!0},S.Event.prototype={constructor:S.Event,isDefaultPrevented:Te,isPropagationStopped:Te,isImmediatePropagationStopped:Te,isSimulated:!1,preventDefault:function(){var e=this.originalEvent;this.isDefaultPrevented=we,e&&!this.isSimulated&&e.preventDefault()},stopPropagation:function(){var e=this.originalEvent;this.isPropagationStopped=we,e&&!this.isSimulated&&e.stopPropagation()},stopImmediatePropagation:function(){var e=this.originalEvent;this.isImmediatePropagationStopped=we,e&&!this.isSimulated&&e.stopImmediatePropagation(),this.stopPropagation()}},S.each({altKey:!0,bubbles:!0,cancelable:!0,changedTouches:!0,ctrlKey:!0,detail:!0,eventPhase:!0,metaKey:!0,pageX:!0,pageY:!0,shiftKey:!0,view:!0,"char":!0,code:!0,charCode:!0,key:!0,keyCode:!0,button:!0,buttons:!0,clientX:!0,clientY:!0,offsetX:!0,offsetY:!0,pointerId:!0,pointerType:!0,screenX:!0,screenY:!0,targetTouches:!0,toElement:!0,touches:!0,which:!0},S.event.addProp),S.each({focus:"focusin",blur:"focusout"},function(e,t){S.event.special[e]={setup:function(){return Se(this,e,Ce),!1},trigger:function(){return Se(this,e),!0},_default:function(){return!0},delegateType:t}}),S.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(e,i){S.event.special[e]={delegateType:i,bindType:i,handle:function(e){var t,n=e.relatedTarget,r=e.handleObj;return n&&(n===this||S.contains(this,n))||(e.type=r.origType,t=r.handler.apply(this,arguments),e.type=i),t}}}),S.fn.extend({on:function(e,t,n,r){return Ee(this,e,t,n,r)},one:function(e,t,n,r){return Ee(this,e,t,n,r,1)},off:function(e,t,n){var r,i;if(e&&e.preventDefault&&e.handleObj)return r=e.handleObj,S(e.delegateTarget).off(r.namespace?r.origType+"."+r.namespace:r.origType,r.selector,r.handler),this;if("object"==typeof e){for(i in e)this.off(i,t,e[i]);return this}return!1!==t&&"function"!=typeof t||(n=t,t=void 0),!1===n&&(n=Te),this.each(function(){S.event.remove(this,e,n,t)})}});var ke=/<script|<style|<link/i,Ae=/checked\s*(?:[^=]|=\s*.checked.)/i,Ne=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;function je(e,t){return A(e,"table")&&A(11!==t.nodeType?t:t.firstChild,"tr")&&S(e).children("tbody")[0]||e}function De(e){return e.type=(null!==e.getAttribute("type"))+"/"+e.type,e}function qe(e){return"true/"===(e.type||"").slice(0,5)?e.type=e.type.slice(5):e.removeAttribute("type"),e}function Le(e,t){var n,r,i,o,a,s;if(1===t.nodeType){if(Y.hasData(e)&&(s=Y.get(e).events))for(i in Y.remove(t,"handle events"),s)for(n=0,r=s[i].length;n<r;n++)S.event.add(t,i,s[i][n]);Q.hasData(e)&&(o=Q.access(e),a=S.extend({},o),Q.set(t,a))}}function He(n,r,i,o){r=g(r);var e,t,a,s,u,l,c=0,f=n.length,p=f-1,d=r[0],h=m(d);if(h||1<f&&"string"==typeof d&&!y.checkClone&&Ae.test(d))return n.each(function(e){var t=n.eq(e);h&&(r[0]=d.call(this,e,t.html())),He(t,r,i,o)});if(f&&(t=(e=xe(r,n[0].ownerDocument,!1,n,o)).firstChild,1===e.childNodes.length&&(e=t),t||o)){for(s=(a=S.map(ve(e,"script"),De)).length;c<f;c++)u=e,c!==p&&(u=S.clone(u,!0,!0),s&&S.merge(a,ve(u,"script"))),i.call(n[c],u,c);if(s)for(l=a[a.length-1].ownerDocument,S.map(a,qe),c=0;c<s;c++)u=a[c],he.test(u.type||"")&&!Y.access(u,"globalEval")&&S.contains(l,u)&&(u.src&&"module"!==(u.type||"").toLowerCase()?S._evalUrl&&!u.noModule&&S._evalUrl(u.src,{nonce:u.nonce||u.getAttribute("nonce")},l):b(u.textContent.replace(Ne,""),u,l))}return n}function Oe(e,t,n){for(var r,i=t?S.filter(t,e):e,o=0;null!=(r=i[o]);o++)n||1!==r.nodeType||S.cleanData(ve(r)),r.parentNode&&(n&&ie(r)&&ye(ve(r,"script")),r.parentNode.removeChild(r));return e}S.extend({htmlPrefilter:function(e){return e},clone:function(e,t,n){var r,i,o,a,s,u,l,c=e.cloneNode(!0),f=ie(e);if(!(y.noCloneChecked||1!==e.nodeType&&11!==e.nodeType||S.isXMLDoc(e)))for(a=ve(c),r=0,i=(o=ve(e)).length;r<i;r++)s=o[r],u=a[r],void 0,"input"===(l=u.nodeName.toLowerCase())&&pe.test(s.type)?u.checked=s.checked:"input"!==l&&"textarea"!==l||(u.defaultValue=s.defaultValue);if(t)if(n)for(o=o||ve(e),a=a||ve(c),r=0,i=o.length;r<i;r++)Le(o[r],a[r]);else Le(e,c);return 0<(a=ve(c,"script")).length&&ye(a,!f&&ve(e,"script")),c},cleanData:function(e){for(var t,n,r,i=S.event.special,o=0;void 0!==(n=e[o]);o++)if(V(n)){if(t=n[Y.expando]){if(t.events)for(r in t.events)i[r]?S.event.remove(n,r):S.removeEvent(n,r,t.handle);n[Y.expando]=void 0}n[Q.expando]&&(n[Q.expando]=void 0)}}}),S.fn.extend({detach:function(e){return Oe(this,e,!0)},remove:function(e){return Oe(this,e)},text:function(e){return $(this,function(e){return void 0===e?S.text(this):this.empty().each(function(){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||(this.textContent=e)})},null,e,arguments.length)},append:function(){return He(this,arguments,function(e){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||je(this,e).appendChild(e)})},prepend:function(){return He(this,arguments,function(e){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var t=je(this,e);t.insertBefore(e,t.firstChild)}})},before:function(){return He(this,arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this)})},after:function(){return He(this,arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this.nextSibling)})},empty:function(){for(var e,t=0;null!=(e=this[t]);t++)1===e.nodeType&&(S.cleanData(ve(e,!1)),e.textContent="");return this},clone:function(e,t){return e=null!=e&&e,t=null==t?e:t,this.map(function(){return S.clone(this,e,t)})},html:function(e){return $(this,function(e){var t=this[0]||{},n=0,r=this.length;if(void 0===e&&1===t.nodeType)return t.innerHTML;if("string"==typeof e&&!ke.test(e)&&!ge[(de.exec(e)||["",""])[1].toLowerCase()]){e=S.htmlPrefilter(e);try{for(;n<r;n++)1===(t=this[n]||{}).nodeType&&(S.cleanData(ve(t,!1)),t.innerHTML=e);t=0}catch(e){}}t&&this.empty().append(e)},null,e,arguments.length)},replaceWith:function(){var n=[];return He(this,arguments,function(e){var t=this.parentNode;S.inArray(this,n)<0&&(S.cleanData(ve(this)),t&&t.replaceChild(e,this))},n)}}),S.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(e,a){S.fn[e]=function(e){for(var t,n=[],r=S(e),i=r.length-1,o=0;o<=i;o++)t=o===i?this:this.clone(!0),S(r[o])[a](t),u.apply(n,t.get());return this.pushStack(n)}});var Pe=new RegExp("^("+ee+")(?!px)[a-z%]+$","i"),Re=function(e){var t=e.ownerDocument.defaultView;return t&&t.opener||(t=C),t.getComputedStyle(e)},Me=function(e,t,n){var r,i,o={};for(i in t)o[i]=e.style[i],e.style[i]=t[i];for(i in r=n.call(e),t)e.style[i]=o[i];return r},Ie=new RegExp(ne.join("|"),"i");function We(e,t,n){var r,i,o,a,s=e.style;return(n=n||Re(e))&&(""!==(a=n.getPropertyValue(t)||n[t])||ie(e)||(a=S.style(e,t)),!y.pixelBoxStyles()&&Pe.test(a)&&Ie.test(t)&&(r=s.width,i=s.minWidth,o=s.maxWidth,s.minWidth=s.maxWidth=s.width=a,a=n.width,s.width=r,s.minWidth=i,s.maxWidth=o)),void 0!==a?a+"":a}function Fe(e,t){return{get:function(){if(!e())return(this.get=t).apply(this,arguments);delete this.get}}}!function(){function e(){if(l){u.style.cssText="position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0",l.style.cssText="position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%",re.appendChild(u).appendChild(l);var e=C.getComputedStyle(l);n="1%"!==e.top,s=12===t(e.marginLeft),l.style.right="60%",o=36===t(e.right),r=36===t(e.width),l.style.position="absolute",i=12===t(l.offsetWidth/3),re.removeChild(u),l=null}}function t(e){return Math.round(parseFloat(e))}var n,r,i,o,a,s,u=E.createElement("div"),l=E.createElement("div");l.style&&(l.style.backgroundClip="content-box",l.cloneNode(!0).style.backgroundClip="",y.clearCloneStyle="content-box"===l.style.backgroundClip,S.extend(y,{boxSizingReliable:function(){return e(),r},pixelBoxStyles:function(){return e(),o},pixelPosition:function(){return e(),n},reliableMarginLeft:function(){return e(),s},scrollboxSize:function(){return e(),i},reliableTrDimensions:function(){var e,t,n,r;return null==a&&(e=E.createElement("table"),t=E.createElement("tr"),n=E.createElement("div"),e.style.cssText="position:absolute;left:-11111px;border-collapse:separate",t.style.cssText="border:1px solid",t.style.height="1px",n.style.height="9px",n.style.display="block",re.appendChild(e).appendChild(t).appendChild(n),r=C.getComputedStyle(t),a=parseInt(r.height,10)+parseInt(r.borderTopWidth,10)+parseInt(r.borderBottomWidth,10)===t.offsetHeight,re.removeChild(e)),a}}))}();var Be=["Webkit","Moz","ms"],$e=E.createElement("div").style,_e={};function ze(e){var t=S.cssProps[e]||_e[e];return t||(e in $e?e:_e[e]=function(e){var t=e[0].toUpperCase()+e.slice(1),n=Be.length;while(n--)if((e=Be[n]+t)in $e)return e}(e)||e)}var Ue=/^(none|table(?!-c[ea]).+)/,Xe=/^--/,Ve={position:"absolute",visibility:"hidden",display:"block"},Ge={letterSpacing:"0",fontWeight:"400"};function Ye(e,t,n){var r=te.exec(t);return r?Math.max(0,r[2]-(n||0))+(r[3]||"px"):t}function Qe(e,t,n,r,i,o){var a="width"===t?1:0,s=0,u=0;if(n===(r?"border":"content"))return 0;for(;a<4;a+=2)"margin"===n&&(u+=S.css(e,n+ne[a],!0,i)),r?("content"===n&&(u-=S.css(e,"padding"+ne[a],!0,i)),"margin"!==n&&(u-=S.css(e,"border"+ne[a]+"Width",!0,i))):(u+=S.css(e,"padding"+ne[a],!0,i),"padding"!==n?u+=S.css(e,"border"+ne[a]+"Width",!0,i):s+=S.css(e,"border"+ne[a]+"Width",!0,i));return!r&&0<=o&&(u+=Math.max(0,Math.ceil(e["offset"+t[0].toUpperCase()+t.slice(1)]-o-u-s-.5))||0),u}function Je(e,t,n){var r=Re(e),i=(!y.boxSizingReliable()||n)&&"border-box"===S.css(e,"boxSizing",!1,r),o=i,a=We(e,t,r),s="offset"+t[0].toUpperCase()+t.slice(1);if(Pe.test(a)){if(!n)return a;a="auto"}return(!y.boxSizingReliable()&&i||!y.reliableTrDimensions()&&A(e,"tr")||"auto"===a||!parseFloat(a)&&"inline"===S.css(e,"display",!1,r))&&e.getClientRects().length&&(i="border-box"===S.css(e,"boxSizing",!1,r),(o=s in e)&&(a=e[s])),(a=parseFloat(a)||0)+Qe(e,t,n||(i?"border":"content"),o,r,a)+"px"}function Ke(e,t,n,r,i){return new Ke.prototype.init(e,t,n,r,i)}S.extend({cssHooks:{opacity:{get:function(e,t){if(t){var n=We(e,"opacity");return""===n?"1":n}}}},cssNumber:{animationIterationCount:!0,columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,gridArea:!0,gridColumn:!0,gridColumnEnd:!0,gridColumnStart:!0,gridRow:!0,gridRowEnd:!0,gridRowStart:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{},style:function(e,t,n,r){if(e&&3!==e.nodeType&&8!==e.nodeType&&e.style){var i,o,a,s=X(t),u=Xe.test(t),l=e.style;if(u||(t=ze(s)),a=S.cssHooks[t]||S.cssHooks[s],void 0===n)return a&&"get"in a&&void 0!==(i=a.get(e,!1,r))?i:l[t];"string"===(o=typeof n)&&(i=te.exec(n))&&i[1]&&(n=se(e,t,i),o="number"),null!=n&&n==n&&("number"!==o||u||(n+=i&&i[3]||(S.cssNumber[s]?"":"px")),y.clearCloneStyle||""!==n||0!==t.indexOf("background")||(l[t]="inherit"),a&&"set"in a&&void 0===(n=a.set(e,n,r))||(u?l.setProperty(t,n):l[t]=n))}},css:function(e,t,n,r){var i,o,a,s=X(t);return Xe.test(t)||(t=ze(s)),(a=S.cssHooks[t]||S.cssHooks[s])&&"get"in a&&(i=a.get(e,!0,n)),void 0===i&&(i=We(e,t,r)),"normal"===i&&t in Ge&&(i=Ge[t]),""===n||n?(o=parseFloat(i),!0===n||isFinite(o)?o||0:i):i}}),S.each(["height","width"],function(e,u){S.cssHooks[u]={get:function(e,t,n){if(t)return!Ue.test(S.css(e,"display"))||e.getClientRects().length&&e.getBoundingClientRect().width?Je(e,u,n):Me(e,Ve,function(){return Je(e,u,n)})},set:function(e,t,n){var r,i=Re(e),o=!y.scrollboxSize()&&"absolute"===i.position,a=(o||n)&&"border-box"===S.css(e,"boxSizing",!1,i),s=n?Qe(e,u,n,a,i):0;return a&&o&&(s-=Math.ceil(e["offset"+u[0].toUpperCase()+u.slice(1)]-parseFloat(i[u])-Qe(e,u,"border",!1,i)-.5)),s&&(r=te.exec(t))&&"px"!==(r[3]||"px")&&(e.style[u]=t,t=S.css(e,u)),Ye(0,t,s)}}}),S.cssHooks.marginLeft=Fe(y.reliableMarginLeft,function(e,t){if(t)return(parseFloat(We(e,"marginLeft"))||e.getBoundingClientRect().left-Me(e,{marginLeft:0},function(){return e.getBoundingClientRect().left}))+"px"}),S.each({margin:"",padding:"",border:"Width"},function(i,o){S.cssHooks[i+o]={expand:function(e){for(var t=0,n={},r="string"==typeof e?e.split(" "):[e];t<4;t++)n[i+ne[t]+o]=r[t]||r[t-2]||r[0];return n}},"margin"!==i&&(S.cssHooks[i+o].set=Ye)}),S.fn.extend({css:function(e,t){return $(this,function(e,t,n){var r,i,o={},a=0;if(Array.isArray(t)){for(r=Re(e),i=t.length;a<i;a++)o[t[a]]=S.css(e,t[a],!1,r);return o}return void 0!==n?S.style(e,t,n):S.css(e,t)},e,t,1<arguments.length)}}),((S.Tween=Ke).prototype={constructor:Ke,init:function(e,t,n,r,i,o){this.elem=e,this.prop=n,this.easing=i||S.easing._default,this.options=t,this.start=this.now=this.cur(),this.end=r,this.unit=o||(S.cssNumber[n]?"":"px")},cur:function(){var e=Ke.propHooks[this.prop];return e&&e.get?e.get(this):Ke.propHooks._default.get(this)},run:function(e){var t,n=Ke.propHooks[this.prop];return this.options.duration?this.pos=t=S.easing[this.easing](e,this.options.duration*e,0,1,this.options.duration):this.pos=t=e,this.now=(this.end-this.start)*t+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),n&&n.set?n.set(this):Ke.propHooks._default.set(this),this}}).init.prototype=Ke.prototype,(Ke.propHooks={_default:{get:function(e){var t;return 1!==e.elem.nodeType||null!=e.elem[e.prop]&&null==e.elem.style[e.prop]?e.elem[e.prop]:(t=S.css(e.elem,e.prop,""))&&"auto"!==t?t:0},set:function(e){S.fx.step[e.prop]?S.fx.step[e.prop](e):1!==e.elem.nodeType||!S.cssHooks[e.prop]&&null==e.elem.style[ze(e.prop)]?e.elem[e.prop]=e.now:S.style(e.elem,e.prop,e.now+e.unit)}}}).scrollTop=Ke.propHooks.scrollLeft={set:function(e){e.elem.nodeType&&e.elem.parentNode&&(e.elem[e.prop]=e.now)}},S.easing={linear:function(e){return e},swing:function(e){return.5-Math.cos(e*Math.PI)/2},_default:"swing"},S.fx=Ke.prototype.init,S.fx.step={};var Ze,et,tt,nt,rt=/^(?:toggle|show|hide)$/,it=/queueHooks$/;function ot(){et&&(!1===E.hidden&&C.requestAnimationFrame?C.requestAnimationFrame(ot):C.setTimeout(ot,S.fx.interval),S.fx.tick())}function at(){return C.setTimeout(function(){Ze=void 0}),Ze=Date.now()}function st(e,t){var n,r=0,i={height:e};for(t=t?1:0;r<4;r+=2-t)i["margin"+(n=ne[r])]=i["padding"+n]=e;return t&&(i.opacity=i.width=e),i}function ut(e,t,n){for(var r,i=(lt.tweeners[t]||[]).concat(lt.tweeners["*"]),o=0,a=i.length;o<a;o++)if(r=i[o].call(n,t,e))return r}function lt(o,e,t){var n,a,r=0,i=lt.prefilters.length,s=S.Deferred().always(function(){delete u.elem}),u=function(){if(a)return!1;for(var e=Ze||at(),t=Math.max(0,l.startTime+l.duration-e),n=1-(t/l.duration||0),r=0,i=l.tweens.length;r<i;r++)l.tweens[r].run(n);return s.notifyWith(o,[l,n,t]),n<1&&i?t:(i||s.notifyWith(o,[l,1,0]),s.resolveWith(o,[l]),!1)},l=s.promise({elem:o,props:S.extend({},e),opts:S.extend(!0,{specialEasing:{},easing:S.easing._default},t),originalProperties:e,originalOptions:t,startTime:Ze||at(),duration:t.duration,tweens:[],createTween:function(e,t){var n=S.Tween(o,l.opts,e,t,l.opts.specialEasing[e]||l.opts.easing);return l.tweens.push(n),n},stop:function(e){var t=0,n=e?l.tweens.length:0;if(a)return this;for(a=!0;t<n;t++)l.tweens[t].run(1);return e?(s.notifyWith(o,[l,1,0]),s.resolveWith(o,[l,e])):s.rejectWith(o,[l,e]),this}}),c=l.props;for(!function(e,t){var n,r,i,o,a;for(n in e)if(i=t[r=X(n)],o=e[n],Array.isArray(o)&&(i=o[1],o=e[n]=o[0]),n!==r&&(e[r]=o,delete e[n]),(a=S.cssHooks[r])&&"expand"in a)for(n in o=a.expand(o),delete e[r],o)n in e||(e[n]=o[n],t[n]=i);else t[r]=i}(c,l.opts.specialEasing);r<i;r++)if(n=lt.prefilters[r].call(l,o,c,l.opts))return m(n.stop)&&(S._queueHooks(l.elem,l.opts.queue).stop=n.stop.bind(n)),n;return S.map(c,ut,l),m(l.opts.start)&&l.opts.start.call(o,l),l.progress(l.opts.progress).done(l.opts.done,l.opts.complete).fail(l.opts.fail).always(l.opts.always),S.fx.timer(S.extend(u,{elem:o,anim:l,queue:l.opts.queue})),l}S.Animation=S.extend(lt,{tweeners:{"*":[function(e,t){var n=this.createTween(e,t);return se(n.elem,e,te.exec(t),n),n}]},tweener:function(e,t){m(e)?(t=e,e=["*"]):e=e.match(P);for(var n,r=0,i=e.length;r<i;r++)n=e[r],lt.tweeners[n]=lt.tweeners[n]||[],lt.tweeners[n].unshift(t)},prefilters:[function(e,t,n){var r,i,o,a,s,u,l,c,f="width"in t||"height"in t,p=this,d={},h=e.style,g=e.nodeType&&ae(e),v=Y.get(e,"fxshow");for(r in n.queue||(null==(a=S._queueHooks(e,"fx")).unqueued&&(a.unqueued=0,s=a.empty.fire,a.empty.fire=function(){a.unqueued||s()}),a.unqueued++,p.always(function(){p.always(function(){a.unqueued--,S.queue(e,"fx").length||a.empty.fire()})})),t)if(i=t[r],rt.test(i)){if(delete t[r],o=o||"toggle"===i,i===(g?"hide":"show")){if("show"!==i||!v||void 0===v[r])continue;g=!0}d[r]=v&&v[r]||S.style(e,r)}if((u=!S.isEmptyObject(t))||!S.isEmptyObject(d))for(r in f&&1===e.nodeType&&(n.overflow=[h.overflow,h.overflowX,h.overflowY],null==(l=v&&v.display)&&(l=Y.get(e,"display")),"none"===(c=S.css(e,"display"))&&(l?c=l:(le([e],!0),l=e.style.display||l,c=S.css(e,"display"),le([e]))),("inline"===c||"inline-block"===c&&null!=l)&&"none"===S.css(e,"float")&&(u||(p.done(function(){h.display=l}),null==l&&(c=h.display,l="none"===c?"":c)),h.display="inline-block")),n.overflow&&(h.overflow="hidden",p.always(function(){h.overflow=n.overflow[0],h.overflowX=n.overflow[1],h.overflowY=n.overflow[2]})),u=!1,d)u||(v?"hidden"in v&&(g=v.hidden):v=Y.access(e,"fxshow",{display:l}),o&&(v.hidden=!g),g&&le([e],!0),p.done(function(){for(r in g||le([e]),Y.remove(e,"fxshow"),d)S.style(e,r,d[r])})),u=ut(g?v[r]:0,r,p),r in v||(v[r]=u.start,g&&(u.end=u.start,u.start=0))}],prefilter:function(e,t){t?lt.prefilters.unshift(e):lt.prefilters.push(e)}}),S.speed=function(e,t,n){var r=e&&"object"==typeof e?S.extend({},e):{complete:n||!n&&t||m(e)&&e,duration:e,easing:n&&t||t&&!m(t)&&t};return S.fx.off?r.duration=0:"number"!=typeof r.duration&&(r.duration in S.fx.speeds?r.duration=S.fx.speeds[r.duration]:r.duration=S.fx.speeds._default),null!=r.queue&&!0!==r.queue||(r.queue="fx"),r.old=r.complete,r.complete=function(){m(r.old)&&r.old.call(this),r.queue&&S.dequeue(this,r.queue)},r},S.fn.extend({fadeTo:function(e,t,n,r){return this.filter(ae).css("opacity",0).show().end().animate({opacity:t},e,n,r)},animate:function(t,e,n,r){var i=S.isEmptyObject(t),o=S.speed(e,n,r),a=function(){var e=lt(this,S.extend({},t),o);(i||Y.get(this,"finish"))&&e.stop(!0)};return a.finish=a,i||!1===o.queue?this.each(a):this.queue(o.queue,a)},stop:function(i,e,o){var a=function(e){var t=e.stop;delete e.stop,t(o)};return"string"!=typeof i&&(o=e,e=i,i=void 0),e&&this.queue(i||"fx",[]),this.each(function(){var e=!0,t=null!=i&&i+"queueHooks",n=S.timers,r=Y.get(this);if(t)r[t]&&r[t].stop&&a(r[t]);else for(t in r)r[t]&&r[t].stop&&it.test(t)&&a(r[t]);for(t=n.length;t--;)n[t].elem!==this||null!=i&&n[t].queue!==i||(n[t].anim.stop(o),e=!1,n.splice(t,1));!e&&o||S.dequeue(this,i)})},finish:function(a){return!1!==a&&(a=a||"fx"),this.each(function(){var e,t=Y.get(this),n=t[a+"queue"],r=t[a+"queueHooks"],i=S.timers,o=n?n.length:0;for(t.finish=!0,S.queue(this,a,[]),r&&r.stop&&r.stop.call(this,!0),e=i.length;e--;)i[e].elem===this&&i[e].queue===a&&(i[e].anim.stop(!0),i.splice(e,1));for(e=0;e<o;e++)n[e]&&n[e].finish&&n[e].finish.call(this);delete t.finish})}}),S.each(["toggle","show","hide"],function(e,r){var i=S.fn[r];S.fn[r]=function(e,t,n){return null==e||"boolean"==typeof e?i.apply(this,arguments):this.animate(st(r,!0),e,t,n)}}),S.each({slideDown:st("show"),slideUp:st("hide"),slideToggle:st("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(e,r){S.fn[e]=function(e,t,n){return this.animate(r,e,t,n)}}),S.timers=[],S.fx.tick=function(){var e,t=0,n=S.timers;for(Ze=Date.now();t<n.length;t++)(e=n[t])()||n[t]!==e||n.splice(t--,1);n.length||S.fx.stop(),Ze=void 0},S.fx.timer=function(e){S.timers.push(e),S.fx.start()},S.fx.interval=13,S.fx.start=function(){et||(et=!0,ot())},S.fx.stop=function(){et=null},S.fx.speeds={slow:600,fast:200,_default:400},S.fn.delay=function(r,e){return r=S.fx&&S.fx.speeds[r]||r,e=e||"fx",this.queue(e,function(e,t){var n=C.setTimeout(e,r);t.stop=function(){C.clearTimeout(n)}})},tt=E.createElement("input"),nt=E.createElement("select").appendChild(E.createElement("option")),tt.type="checkbox",y.checkOn=""!==tt.value,y.optSelected=nt.selected,(tt=E.createElement("input")).value="t",tt.type="radio",y.radioValue="t"===tt.value;var ct,ft=S.expr.attrHandle;S.fn.extend({attr:function(e,t){return $(this,S.attr,e,t,1<arguments.length)},removeAttr:function(e){return this.each(function(){S.removeAttr(this,e)})}}),S.extend({attr:function(e,t,n){var r,i,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return"undefined"==typeof e.getAttribute?S.prop(e,t,n):(1===o&&S.isXMLDoc(e)||(i=S.attrHooks[t.toLowerCase()]||(S.expr.match.bool.test(t)?ct:void 0)),void 0!==n?null===n?void S.removeAttr(e,t):i&&"set"in i&&void 0!==(r=i.set(e,n,t))?r:(e.setAttribute(t,n+""),n):i&&"get"in i&&null!==(r=i.get(e,t))?r:null==(r=S.find.attr(e,t))?void 0:r)},attrHooks:{type:{set:function(e,t){if(!y.radioValue&&"radio"===t&&A(e,"input")){var n=e.value;return e.setAttribute("type",t),n&&(e.value=n),t}}}},removeAttr:function(e,t){var n,r=0,i=t&&t.match(P);if(i&&1===e.nodeType)while(n=i[r++])e.removeAttribute(n)}}),ct={set:function(e,t,n){return!1===t?S.removeAttr(e,n):e.setAttribute(n,n),n}},S.each(S.expr.match.bool.source.match(/\w+/g),function(e,t){var a=ft[t]||S.find.attr;ft[t]=function(e,t,n){var r,i,o=t.toLowerCase();return n||(i=ft[o],ft[o]=r,r=null!=a(e,t,n)?o:null,ft[o]=i),r}});var pt=/^(?:input|select|textarea|button)$/i,dt=/^(?:a|area)$/i;function ht(e){return(e.match(P)||[]).join(" ")}function gt(e){return e.getAttribute&&e.getAttribute("class")||""}function vt(e){return Array.isArray(e)?e:"string"==typeof e&&e.match(P)||[]}S.fn.extend({prop:function(e,t){return $(this,S.prop,e,t,1<arguments.length)},removeProp:function(e){return this.each(function(){delete this[S.propFix[e]||e]})}}),S.extend({prop:function(e,t,n){var r,i,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return 1===o&&S.isXMLDoc(e)||(t=S.propFix[t]||t,i=S.propHooks[t]),void 0!==n?i&&"set"in i&&void 0!==(r=i.set(e,n,t))?r:e[t]=n:i&&"get"in i&&null!==(r=i.get(e,t))?r:e[t]},propHooks:{tabIndex:{get:function(e){var t=S.find.attr(e,"tabindex");return t?parseInt(t,10):pt.test(e.nodeName)||dt.test(e.nodeName)&&e.href?0:-1}}},propFix:{"for":"htmlFor","class":"className"}}),y.optSelected||(S.propHooks.selected={get:function(e){var t=e.parentNode;return t&&t.parentNode&&t.parentNode.selectedIndex,null},set:function(e){var t=e.parentNode;t&&(t.selectedIndex,t.parentNode&&t.parentNode.selectedIndex)}}),S.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){S.propFix[this.toLowerCase()]=this}),S.fn.extend({addClass:function(t){var e,n,r,i,o,a,s,u=0;if(m(t))return this.each(function(e){S(this).addClass(t.call(this,e,gt(this)))});if((e=vt(t)).length)while(n=this[u++])if(i=gt(n),r=1===n.nodeType&&" "+ht(i)+" "){a=0;while(o=e[a++])r.indexOf(" "+o+" ")<0&&(r+=o+" ");i!==(s=ht(r))&&n.setAttribute("class",s)}return this},removeClass:function(t){var e,n,r,i,o,a,s,u=0;if(m(t))return this.each(function(e){S(this).removeClass(t.call(this,e,gt(this)))});if(!arguments.length)return this.attr("class","");if((e=vt(t)).length)while(n=this[u++])if(i=gt(n),r=1===n.nodeType&&" "+ht(i)+" "){a=0;while(o=e[a++])while(-1<r.indexOf(" "+o+" "))r=r.replace(" "+o+" "," ");i!==(s=ht(r))&&n.setAttribute("class",s)}return this},toggleClass:function(i,t){var o=typeof i,a="string"===o||Array.isArray(i);return"boolean"==typeof t&&a?t?this.addClass(i):this.removeClass(i):m(i)?this.each(function(e){S(this).toggleClass(i.call(this,e,gt(this),t),t)}):this.each(function(){var e,t,n,r;if(a){t=0,n=S(this),r=vt(i);while(e=r[t++])n.hasClass(e)?n.removeClass(e):n.addClass(e)}else void 0!==i&&"boolean"!==o||((e=gt(this))&&Y.set(this,"__className__",e),this.setAttribute&&this.setAttribute("class",e||!1===i?"":Y.get(this,"__className__")||""))})},hasClass:function(e){var t,n,r=0;t=" "+e+" ";while(n=this[r++])if(1===n.nodeType&&-1<(" "+ht(gt(n))+" ").indexOf(t))return!0;return!1}});var yt=/\r/g;S.fn.extend({val:function(n){var r,e,i,t=this[0];return arguments.length?(i=m(n),this.each(function(e){var t;1===this.nodeType&&(null==(t=i?n.call(this,e,S(this).val()):n)?t="":"number"==typeof t?t+="":Array.isArray(t)&&(t=S.map(t,function(e){return null==e?"":e+""})),(r=S.valHooks[this.type]||S.valHooks[this.nodeName.toLowerCase()])&&"set"in r&&void 0!==r.set(this,t,"value")||(this.value=t))})):t?(r=S.valHooks[t.type]||S.valHooks[t.nodeName.toLowerCase()])&&"get"in r&&void 0!==(e=r.get(t,"value"))?e:"string"==typeof(e=t.value)?e.replace(yt,""):null==e?"":e:void 0}}),S.extend({valHooks:{option:{get:function(e){var t=S.find.attr(e,"value");return null!=t?t:ht(S.text(e))}},select:{get:function(e){var t,n,r,i=e.options,o=e.selectedIndex,a="select-one"===e.type,s=a?null:[],u=a?o+1:i.length;for(r=o<0?u:a?o:0;r<u;r++)if(((n=i[r]).selected||r===o)&&!n.disabled&&(!n.parentNode.disabled||!A(n.parentNode,"optgroup"))){if(t=S(n).val(),a)return t;s.push(t)}return s},set:function(e,t){var n,r,i=e.options,o=S.makeArray(t),a=i.length;while(a--)((r=i[a]).selected=-1<S.inArray(S.valHooks.option.get(r),o))&&(n=!0);return n||(e.selectedIndex=-1),o}}}}),S.each(["radio","checkbox"],function(){S.valHooks[this]={set:function(e,t){if(Array.isArray(t))return e.checked=-1<S.inArray(S(e).val(),t)}},y.checkOn||(S.valHooks[this].get=function(e){return null===e.getAttribute("value")?"on":e.value})}),y.focusin="onfocusin"in C;var mt=/^(?:focusinfocus|focusoutblur)$/,xt=function(e){e.stopPropagation()};S.extend(S.event,{trigger:function(e,t,n,r){var i,o,a,s,u,l,c,f,p=[n||E],d=v.call(e,"type")?e.type:e,h=v.call(e,"namespace")?e.namespace.split("."):[];if(o=f=a=n=n||E,3!==n.nodeType&&8!==n.nodeType&&!mt.test(d+S.event.triggered)&&(-1<d.indexOf(".")&&(d=(h=d.split(".")).shift(),h.sort()),u=d.indexOf(":")<0&&"on"+d,(e=e[S.expando]?e:new S.Event(d,"object"==typeof e&&e)).isTrigger=r?2:3,e.namespace=h.join("."),e.rnamespace=e.namespace?new RegExp("(^|\\.)"+h.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,e.result=void 0,e.target||(e.target=n),t=null==t?[e]:S.makeArray(t,[e]),c=S.event.special[d]||{},r||!c.trigger||!1!==c.trigger.apply(n,t))){if(!r&&!c.noBubble&&!x(n)){for(s=c.delegateType||d,mt.test(s+d)||(o=o.parentNode);o;o=o.parentNode)p.push(o),a=o;a===(n.ownerDocument||E)&&p.push(a.defaultView||a.parentWindow||C)}i=0;while((o=p[i++])&&!e.isPropagationStopped())f=o,e.type=1<i?s:c.bindType||d,(l=(Y.get(o,"events")||Object.create(null))[e.type]&&Y.get(o,"handle"))&&l.apply(o,t),(l=u&&o[u])&&l.apply&&V(o)&&(e.result=l.apply(o,t),!1===e.result&&e.preventDefault());return e.type=d,r||e.isDefaultPrevented()||c._default&&!1!==c._default.apply(p.pop(),t)||!V(n)||u&&m(n[d])&&!x(n)&&((a=n[u])&&(n[u]=null),S.event.triggered=d,e.isPropagationStopped()&&f.addEventListener(d,xt),n[d](),e.isPropagationStopped()&&f.removeEventListener(d,xt),S.event.triggered=void 0,a&&(n[u]=a)),e.result}},simulate:function(e,t,n){var r=S.extend(new S.Event,n,{type:e,isSimulated:!0});S.event.trigger(r,null,t)}}),S.fn.extend({trigger:function(e,t){return this.each(function(){S.event.trigger(e,t,this)})},triggerHandler:function(e,t){var n=this[0];if(n)return S.event.trigger(e,t,n,!0)}}),y.focusin||S.each({focus:"focusin",blur:"focusout"},function(n,r){var i=function(e){S.event.simulate(r,e.target,S.event.fix(e))};S.event.special[r]={setup:function(){var e=this.ownerDocument||this.document||this,t=Y.access(e,r);t||e.addEventListener(n,i,!0),Y.access(e,r,(t||0)+1)},teardown:function(){var e=this.ownerDocument||this.document||this,t=Y.access(e,r)-1;t?Y.access(e,r,t):(e.removeEventListener(n,i,!0),Y.remove(e,r))}}});var bt=C.location,wt={guid:Date.now()},Tt=/\?/;S.parseXML=function(e){var t,n;if(!e||"string"!=typeof e)return null;try{t=(new C.DOMParser).parseFromString(e,"text/xml")}catch(e){}return n=t&&t.getElementsByTagName("parsererror")[0],t&&!n||S.error("Invalid XML: "+(n?S.map(n.childNodes,function(e){return e.textContent}).join("\n"):e)),t};var Ct=/\[\]$/,Et=/\r?\n/g,St=/^(?:submit|button|image|reset|file)$/i,kt=/^(?:input|select|textarea|keygen)/i;function At(n,e,r,i){var t;if(Array.isArray(e))S.each(e,function(e,t){r||Ct.test(n)?i(n,t):At(n+"["+("object"==typeof t&&null!=t?e:"")+"]",t,r,i)});else if(r||"object"!==w(e))i(n,e);else for(t in e)At(n+"["+t+"]",e[t],r,i)}S.param=function(e,t){var n,r=[],i=function(e,t){var n=m(t)?t():t;r[r.length]=encodeURIComponent(e)+"="+encodeURIComponent(null==n?"":n)};if(null==e)return"";if(Array.isArray(e)||e.jquery&&!S.isPlainObject(e))S.each(e,function(){i(this.name,this.value)});else for(n in e)At(n,e[n],t,i);return r.join("&")},S.fn.extend({serialize:function(){return S.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var e=S.prop(this,"elements");return e?S.makeArray(e):this}).filter(function(){var e=this.type;return this.name&&!S(this).is(":disabled")&&kt.test(this.nodeName)&&!St.test(e)&&(this.checked||!pe.test(e))}).map(function(e,t){var n=S(this).val();return null==n?null:Array.isArray(n)?S.map(n,function(e){return{name:t.name,value:e.replace(Et,"\r\n")}}):{name:t.name,value:n.replace(Et,"\r\n")}}).get()}});var Nt=/%20/g,jt=/#.*$/,Dt=/([?&])_=[^&]*/,qt=/^(.*?):[ \t]*([^\r\n]*)$/gm,Lt=/^(?:GET|HEAD)$/,Ht=/^\/\//,Ot={},Pt={},Rt="*/".concat("*"),Mt=E.createElement("a");function It(o){return function(e,t){"string"!=typeof e&&(t=e,e="*");var n,r=0,i=e.toLowerCase().match(P)||[];if(m(t))while(n=i[r++])"+"===n[0]?(n=n.slice(1)||"*",(o[n]=o[n]||[]).unshift(t)):(o[n]=o[n]||[]).push(t)}}function Wt(t,i,o,a){var s={},u=t===Pt;function l(e){var r;return s[e]=!0,S.each(t[e]||[],function(e,t){var n=t(i,o,a);return"string"!=typeof n||u||s[n]?u?!(r=n):void 0:(i.dataTypes.unshift(n),l(n),!1)}),r}return l(i.dataTypes[0])||!s["*"]&&l("*")}function Ft(e,t){var n,r,i=S.ajaxSettings.flatOptions||{};for(n in t)void 0!==t[n]&&((i[n]?e:r||(r={}))[n]=t[n]);return r&&S.extend(!0,e,r),e}Mt.href=bt.href,S.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:bt.href,type:"GET",isLocal:/^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(bt.protocol),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":Rt,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/\bxml\b/,html:/\bhtml/,json:/\bjson\b/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":JSON.parse,"text xml":S.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(e,t){return t?Ft(Ft(e,S.ajaxSettings),t):Ft(S.ajaxSettings,e)},ajaxPrefilter:It(Ot),ajaxTransport:It(Pt),ajax:function(e,t){"object"==typeof e&&(t=e,e=void 0),t=t||{};var c,f,p,n,d,r,h,g,i,o,v=S.ajaxSetup({},t),y=v.context||v,m=v.context&&(y.nodeType||y.jquery)?S(y):S.event,x=S.Deferred(),b=S.Callbacks("once memory"),w=v.statusCode||{},a={},s={},u="canceled",T={readyState:0,getResponseHeader:function(e){var t;if(h){if(!n){n={};while(t=qt.exec(p))n[t[1].toLowerCase()+" "]=(n[t[1].toLowerCase()+" "]||[]).concat(t[2])}t=n[e.toLowerCase()+" "]}return null==t?null:t.join(", ")},getAllResponseHeaders:function(){return h?p:null},setRequestHeader:function(e,t){return null==h&&(e=s[e.toLowerCase()]=s[e.toLowerCase()]||e,a[e]=t),this},overrideMimeType:function(e){return null==h&&(v.mimeType=e),this},statusCode:function(e){var t;if(e)if(h)T.always(e[T.status]);else for(t in e)w[t]=[w[t],e[t]];return this},abort:function(e){var t=e||u;return c&&c.abort(t),l(0,t),this}};if(x.promise(T),v.url=((e||v.url||bt.href)+"").replace(Ht,bt.protocol+"//"),v.type=t.method||t.type||v.method||v.type,v.dataTypes=(v.dataType||"*").toLowerCase().match(P)||[""],null==v.crossDomain){r=E.createElement("a");try{r.href=v.url,r.href=r.href,v.crossDomain=Mt.protocol+"//"+Mt.host!=r.protocol+"//"+r.host}catch(e){v.crossDomain=!0}}if(v.data&&v.processData&&"string"!=typeof v.data&&(v.data=S.param(v.data,v.traditional)),Wt(Ot,v,t,T),h)return T;for(i in(g=S.event&&v.global)&&0==S.active++&&S.event.trigger("ajaxStart"),v.type=v.type.toUpperCase(),v.hasContent=!Lt.test(v.type),f=v.url.replace(jt,""),v.hasContent?v.data&&v.processData&&0===(v.contentType||"").indexOf("application/x-www-form-urlencoded")&&(v.data=v.data.replace(Nt,"+")):(o=v.url.slice(f.length),v.data&&(v.processData||"string"==typeof v.data)&&(f+=(Tt.test(f)?"&":"?")+v.data,delete v.data),!1===v.cache&&(f=f.replace(Dt,"$1"),o=(Tt.test(f)?"&":"?")+"_="+wt.guid+++o),v.url=f+o),v.ifModified&&(S.lastModified[f]&&T.setRequestHeader("If-Modified-Since",S.lastModified[f]),S.etag[f]&&T.setRequestHeader("If-None-Match",S.etag[f])),(v.data&&v.hasContent&&!1!==v.contentType||t.contentType)&&T.setRequestHeader("Content-Type",v.contentType),T.setRequestHeader("Accept",v.dataTypes[0]&&v.accepts[v.dataTypes[0]]?v.accepts[v.dataTypes[0]]+("*"!==v.dataTypes[0]?", "+Rt+"; q=0.01":""):v.accepts["*"]),v.headers)T.setRequestHeader(i,v.headers[i]);if(v.beforeSend&&(!1===v.beforeSend.call(y,T,v)||h))return T.abort();if(u="abort",b.add(v.complete),T.done(v.success),T.fail(v.error),c=Wt(Pt,v,t,T)){if(T.readyState=1,g&&m.trigger("ajaxSend",[T,v]),h)return T;v.async&&0<v.timeout&&(d=C.setTimeout(function(){T.abort("timeout")},v.timeout));try{h=!1,c.send(a,l)}catch(e){if(h)throw e;l(-1,e)}}else l(-1,"No Transport");function l(e,t,n,r){var i,o,a,s,u,l=t;h||(h=!0,d&&C.clearTimeout(d),c=void 0,p=r||"",T.readyState=0<e?4:0,i=200<=e&&e<300||304===e,n&&(s=function(e,t,n){var r,i,o,a,s=e.contents,u=e.dataTypes;while("*"===u[0])u.shift(),void 0===r&&(r=e.mimeType||t.getResponseHeader("Content-Type"));if(r)for(i in s)if(s[i]&&s[i].test(r)){u.unshift(i);break}if(u[0]in n)o=u[0];else{for(i in n){if(!u[0]||e.converters[i+" "+u[0]]){o=i;break}a||(a=i)}o=o||a}if(o)return o!==u[0]&&u.unshift(o),n[o]}(v,T,n)),!i&&-1<S.inArray("script",v.dataTypes)&&S.inArray("json",v.dataTypes)<0&&(v.converters["text script"]=function(){}),s=function(e,t,n,r){var i,o,a,s,u,l={},c=e.dataTypes.slice();if(c[1])for(a in e.converters)l[a.toLowerCase()]=e.converters[a];o=c.shift();while(o)if(e.responseFields[o]&&(n[e.responseFields[o]]=t),!u&&r&&e.dataFilter&&(t=e.dataFilter(t,e.dataType)),u=o,o=c.shift())if("*"===o)o=u;else if("*"!==u&&u!==o){if(!(a=l[u+" "+o]||l["* "+o]))for(i in l)if((s=i.split(" "))[1]===o&&(a=l[u+" "+s[0]]||l["* "+s[0]])){!0===a?a=l[i]:!0!==l[i]&&(o=s[0],c.unshift(s[1]));break}if(!0!==a)if(a&&e["throws"])t=a(t);else try{t=a(t)}catch(e){return{state:"parsererror",error:a?e:"No conversion from "+u+" to "+o}}}return{state:"success",data:t}}(v,s,T,i),i?(v.ifModified&&((u=T.getResponseHeader("Last-Modified"))&&(S.lastModified[f]=u),(u=T.getResponseHeader("etag"))&&(S.etag[f]=u)),204===e||"HEAD"===v.type?l="nocontent":304===e?l="notmodified":(l=s.state,o=s.data,i=!(a=s.error))):(a=l,!e&&l||(l="error",e<0&&(e=0))),T.status=e,T.statusText=(t||l)+"",i?x.resolveWith(y,[o,l,T]):x.rejectWith(y,[T,l,a]),T.statusCode(w),w=void 0,g&&m.trigger(i?"ajaxSuccess":"ajaxError",[T,v,i?o:a]),b.fireWith(y,[T,l]),g&&(m.trigger("ajaxComplete",[T,v]),--S.active||S.event.trigger("ajaxStop")))}return T},getJSON:function(e,t,n){return S.get(e,t,n,"json")},getScript:function(e,t){return S.get(e,void 0,t,"script")}}),S.each(["get","post"],function(e,i){S[i]=function(e,t,n,r){return m(t)&&(r=r||n,n=t,t=void 0),S.ajax(S.extend({url:e,type:i,dataType:r,data:t,success:n},S.isPlainObject(e)&&e))}}),S.ajaxPrefilter(function(e){var t;for(t in e.headers)"content-type"===t.toLowerCase()&&(e.contentType=e.headers[t]||"")}),S._evalUrl=function(e,t,n){return S.ajax({url:e,type:"GET",dataType:"script",cache:!0,async:!1,global:!1,converters:{"text script":function(){}},dataFilter:function(e){S.globalEval(e,t,n)}})},S.fn.extend({wrapAll:function(e){var t;return this[0]&&(m(e)&&(e=e.call(this[0])),t=S(e,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&t.insertBefore(this[0]),t.map(function(){var e=this;while(e.firstElementChild)e=e.firstElementChild;return e}).append(this)),this},wrapInner:function(n){return m(n)?this.each(function(e){S(this).wrapInner(n.call(this,e))}):this.each(function(){var e=S(this),t=e.contents();t.length?t.wrapAll(n):e.append(n)})},wrap:function(t){var n=m(t);return this.each(function(e){S(this).wrapAll(n?t.call(this,e):t)})},unwrap:function(e){return this.parent(e).not("body").each(function(){S(this).replaceWith(this.childNodes)}),this}}),S.expr.pseudos.hidden=function(e){return!S.expr.pseudos.visible(e)},S.expr.pseudos.visible=function(e){return!!(e.offsetWidth||e.offsetHeight||e.getClientRects().length)},S.ajaxSettings.xhr=function(){try{return new C.XMLHttpRequest}catch(e){}};var Bt={0:200,1223:204},$t=S.ajaxSettings.xhr();y.cors=!!$t&&"withCredentials"in $t,y.ajax=$t=!!$t,S.ajaxTransport(function(i){var o,a;if(y.cors||$t&&!i.crossDomain)return{send:function(e,t){var n,r=i.xhr();if(r.open(i.type,i.url,i.async,i.username,i.password),i.xhrFields)for(n in i.xhrFields)r[n]=i.xhrFields[n];for(n in i.mimeType&&r.overrideMimeType&&r.overrideMimeType(i.mimeType),i.crossDomain||e["X-Requested-With"]||(e["X-Requested-With"]="XMLHttpRequest"),e)r.setRequestHeader(n,e[n]);o=function(e){return function(){o&&(o=a=r.onload=r.onerror=r.onabort=r.ontimeout=r.onreadystatechange=null,"abort"===e?r.abort():"error"===e?"number"!=typeof r.status?t(0,"error"):t(r.status,r.statusText):t(Bt[r.status]||r.status,r.statusText,"text"!==(r.responseType||"text")||"string"!=typeof r.responseText?{binary:r.response}:{text:r.responseText},r.getAllResponseHeaders()))}},r.onload=o(),a=r.onerror=r.ontimeout=o("error"),void 0!==r.onabort?r.onabort=a:r.onreadystatechange=function(){4===r.readyState&&C.setTimeout(function(){o&&a()})},o=o("abort");try{r.send(i.hasContent&&i.data||null)}catch(e){if(o)throw e}},abort:function(){o&&o()}}}),S.ajaxPrefilter(function(e){e.crossDomain&&(e.contents.script=!1)}),S.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/\b(?:java|ecma)script\b/},converters:{"text script":function(e){return S.globalEval(e),e}}}),S.ajaxPrefilter("script",function(e){void 0===e.cache&&(e.cache=!1),e.crossDomain&&(e.type="GET")}),S.ajaxTransport("script",function(n){var r,i;if(n.crossDomain||n.scriptAttrs)return{send:function(e,t){r=S("<script>").attr(n.scriptAttrs||{}).prop({charset:n.scriptCharset,src:n.url}).on("load error",i=function(e){r.remove(),i=null,e&&t("error"===e.type?404:200,e.type)}),E.head.appendChild(r[0])},abort:function(){i&&i()}}});var _t,zt=[],Ut=/(=)\?(?=&|$)|\?\?/;S.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var e=zt.pop()||S.expando+"_"+wt.guid++;return this[e]=!0,e}}),S.ajaxPrefilter("json jsonp",function(e,t,n){var r,i,o,a=!1!==e.jsonp&&(Ut.test(e.url)?"url":"string"==typeof e.data&&0===(e.contentType||"").indexOf("application/x-www-form-urlencoded")&&Ut.test(e.data)&&"data");if(a||"jsonp"===e.dataTypes[0])return r=e.jsonpCallback=m(e.jsonpCallback)?e.jsonpCallback():e.jsonpCallback,a?e[a]=e[a].replace(Ut,"$1"+r):!1!==e.jsonp&&(e.url+=(Tt.test(e.url)?"&":"?")+e.jsonp+"="+r),e.converters["script json"]=function(){return o||S.error(r+" was not called"),o[0]},e.dataTypes[0]="json",i=C[r],C[r]=function(){o=arguments},n.always(function(){void 0===i?S(C).removeProp(r):C[r]=i,e[r]&&(e.jsonpCallback=t.jsonpCallback,zt.push(r)),o&&m(i)&&i(o[0]),o=i=void 0}),"script"}),y.createHTMLDocument=((_t=E.implementation.createHTMLDocument("").body).innerHTML="<form></form><form></form>",2===_t.childNodes.length),S.parseHTML=function(e,t,n){return"string"!=typeof e?[]:("boolean"==typeof t&&(n=t,t=!1),t||(y.createHTMLDocument?((r=(t=E.implementation.createHTMLDocument("")).createElement("base")).href=E.location.href,t.head.appendChild(r)):t=E),o=!n&&[],(i=N.exec(e))?[t.createElement(i[1])]:(i=xe([e],t,o),o&&o.length&&S(o).remove(),S.merge([],i.childNodes)));var r,i,o},S.fn.load=function(e,t,n){var r,i,o,a=this,s=e.indexOf(" ");return-1<s&&(r=ht(e.slice(s)),e=e.slice(0,s)),m(t)?(n=t,t=void 0):t&&"object"==typeof t&&(i="POST"),0<a.length&&S.ajax({url:e,type:i||"GET",dataType:"html",data:t}).done(function(e){o=arguments,a.html(r?S("<div>").append(S.parseHTML(e)).find(r):e)}).always(n&&function(e,t){a.each(function(){n.apply(this,o||[e.responseText,t,e])})}),this},S.expr.pseudos.animated=function(t){return S.grep(S.timers,function(e){return t===e.elem}).length},S.offset={setOffset:function(e,t,n){var r,i,o,a,s,u,l=S.css(e,"position"),c=S(e),f={};"static"===l&&(e.style.position="relative"),s=c.offset(),o=S.css(e,"top"),u=S.css(e,"left"),("absolute"===l||"fixed"===l)&&-1<(o+u).indexOf("auto")?(a=(r=c.position()).top,i=r.left):(a=parseFloat(o)||0,i=parseFloat(u)||0),m(t)&&(t=t.call(e,n,S.extend({},s))),null!=t.top&&(f.top=t.top-s.top+a),null!=t.left&&(f.left=t.left-s.left+i),"using"in t?t.using.call(e,f):c.css(f)}},S.fn.extend({offset:function(t){if(arguments.length)return void 0===t?this:this.each(function(e){S.offset.setOffset(this,t,e)});var e,n,r=this[0];return r?r.getClientRects().length?(e=r.getBoundingClientRect(),n=r.ownerDocument.defaultView,{top:e.top+n.pageYOffset,left:e.left+n.pageXOffset}):{top:0,left:0}:void 0},position:function(){if(this[0]){var e,t,n,r=this[0],i={top:0,left:0};if("fixed"===S.css(r,"position"))t=r.getBoundingClientRect();else{t=this.offset(),n=r.ownerDocument,e=r.offsetParent||n.documentElement;while(e&&(e===n.body||e===n.documentElement)&&"static"===S.css(e,"position"))e=e.parentNode;e&&e!==r&&1===e.nodeType&&((i=S(e).offset()).top+=S.css(e,"borderTopWidth",!0),i.left+=S.css(e,"borderLeftWidth",!0))}return{top:t.top-i.top-S.css(r,"marginTop",!0),left:t.left-i.left-S.css(r,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var e=this.offsetParent;while(e&&"static"===S.css(e,"position"))e=e.offsetParent;return e||re})}}),S.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(t,i){var o="pageYOffset"===i;S.fn[t]=function(e){return $(this,function(e,t,n){var r;if(x(e)?r=e:9===e.nodeType&&(r=e.defaultView),void 0===n)return r?r[i]:e[t];r?r.scrollTo(o?r.pageXOffset:n,o?n:r.pageYOffset):e[t]=n},t,e,arguments.length)}}),S.each(["top","left"],function(e,n){S.cssHooks[n]=Fe(y.pixelPosition,function(e,t){if(t)return t=We(e,n),Pe.test(t)?S(e).position()[n]+"px":t})}),S.each({Height:"height",Width:"width"},function(a,s){S.each({padding:"inner"+a,content:s,"":"outer"+a},function(r,o){S.fn[o]=function(e,t){var n=arguments.length&&(r||"boolean"!=typeof e),i=r||(!0===e||!0===t?"margin":"border");return $(this,function(e,t,n){var r;return x(e)?0===o.indexOf("outer")?e["inner"+a]:e.document.documentElement["client"+a]:9===e.nodeType?(r=e.documentElement,Math.max(e.body["scroll"+a],r["scroll"+a],e.body["offset"+a],r["offset"+a],r["client"+a])):void 0===n?S.css(e,t,i):S.style(e,t,n,i)},s,n?e:void 0,n)}})}),S.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(e,t){S.fn[t]=function(e){return this.on(t,e)}}),S.fn.extend({bind:function(e,t,n){return this.on(e,null,t,n)},unbind:function(e,t){return this.off(e,null,t)},delegate:function(e,t,n,r){return this.on(t,e,n,r)},undelegate:function(e,t,n){return 1===arguments.length?this.off(e,"**"):this.off(t,e||"**",n)},hover:function(e,t){return this.mouseenter(e).mouseleave(t||e)}}),S.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "),function(e,n){S.fn[n]=function(e,t){return 0<arguments.length?this.on(n,null,e,t):this.trigger(n)}});var Xt=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;S.proxy=function(e,t){var n,r,i;if("string"==typeof t&&(n=e[t],t=e,e=n),m(e))return r=s.call(arguments,2),(i=function(){return e.apply(t||this,r.concat(s.call(arguments)))}).guid=e.guid=e.guid||S.guid++,i},S.holdReady=function(e){e?S.readyWait++:S.ready(!0)},S.isArray=Array.isArray,S.parseJSON=JSON.parse,S.nodeName=A,S.isFunction=m,S.isWindow=x,S.camelCase=X,S.type=w,S.now=Date.now,S.isNumeric=function(e){var t=S.type(e);return("number"===t||"string"===t)&&!isNaN(e-parseFloat(e))},S.trim=function(e){return null==e?"":(e+"").replace(Xt,"")},"function"==typeof define&&define.amd&&define("jquery",[],function(){return S});var Vt=C.jQuery,Gt=C.$;return S.noConflict=function(e){return C.$===S&&(C.$=Gt),e&&C.jQuery===S&&(C.jQuery=Vt),S},"undefined"==typeof e&&(C.jQuery=C.$=S),S});
;
/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

if (!Element.prototype.matches) {
  Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
};
/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

if (typeof Object.assign !== 'function') {
  Object.defineProperty(Object, 'assign', {
    value: function assign(target, varArgs) {
      'use strict';

      if (target === null || target === undefined) {
        throw new TypeError('Cannot convert undefined or null to object');
      }

      var to = Object(target);

      for (var index = 1; index < arguments.length; index++) {
        var nextSource = arguments[index];

        if (nextSource !== null && nextSource !== undefined) {
          for (var nextKey in nextSource) {
            if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
              to[nextKey] = nextSource[nextKey];
            }
          }
        }
      }

      return to;
    },
    writable: true,
    configurable: true
  });
};
!function(n,r){"object"==typeof exports&&"undefined"!=typeof module?module.exports=r():"function"==typeof define&&define.amd?define("underscore",r):(n="undefined"!=typeof globalThis?globalThis:n||self,function(){var t=n._,e=n._=r();e.noConflict=function(){return n._=t,e}}())}(this,(function(){
//     Underscore.js 1.13.2
//     https://underscorejs.org
//     (c) 2009-2021 Jeremy Ashkenas, Julian Gonggrijp, and DocumentCloud and Investigative Reporters & Editors
//     Underscore may be freely distributed under the MIT license.
var n="1.13.2",r="object"==typeof self&&self.self===self&&self||"object"==typeof global&&global.global===global&&global||Function("return this")()||{},t=Array.prototype,e=Object.prototype,u="undefined"!=typeof Symbol?Symbol.prototype:null,o=t.push,i=t.slice,a=e.toString,f=e.hasOwnProperty,c="undefined"!=typeof ArrayBuffer,l="undefined"!=typeof DataView,s=Array.isArray,p=Object.keys,v=Object.create,h=c&&ArrayBuffer.isView,y=isNaN,d=isFinite,g=!{toString:null}.propertyIsEnumerable("toString"),b=["valueOf","isPrototypeOf","toString","propertyIsEnumerable","hasOwnProperty","toLocaleString"],m=Math.pow(2,53)-1;function j(n,r){return r=null==r?n.length-1:+r,function(){for(var t=Math.max(arguments.length-r,0),e=Array(t),u=0;u<t;u++)e[u]=arguments[u+r];switch(r){case 0:return n.call(this,e);case 1:return n.call(this,arguments[0],e);case 2:return n.call(this,arguments[0],arguments[1],e)}var o=Array(r+1);for(u=0;u<r;u++)o[u]=arguments[u];return o[r]=e,n.apply(this,o)}}function _(n){var r=typeof n;return"function"===r||"object"===r&&!!n}function w(n){return void 0===n}function A(n){return!0===n||!1===n||"[object Boolean]"===a.call(n)}function x(n){var r="[object "+n+"]";return function(n){return a.call(n)===r}}var S=x("String"),O=x("Number"),M=x("Date"),E=x("RegExp"),B=x("Error"),N=x("Symbol"),I=x("ArrayBuffer"),T=x("Function"),k=r.document&&r.document.childNodes;"function"!=typeof/./&&"object"!=typeof Int8Array&&"function"!=typeof k&&(T=function(n){return"function"==typeof n||!1});var D=T,R=x("Object"),F=l&&R(new DataView(new ArrayBuffer(8))),V="undefined"!=typeof Map&&R(new Map),P=x("DataView");var q=F?function(n){return null!=n&&D(n.getInt8)&&I(n.buffer)}:P,U=s||x("Array");function W(n,r){return null!=n&&f.call(n,r)}var z=x("Arguments");!function(){z(arguments)||(z=function(n){return W(n,"callee")})}();var L=z;function $(n){return O(n)&&y(n)}function C(n){return function(){return n}}function K(n){return function(r){var t=n(r);return"number"==typeof t&&t>=0&&t<=m}}function J(n){return function(r){return null==r?void 0:r[n]}}var G=J("byteLength"),H=K(G),Q=/\[object ((I|Ui)nt(8|16|32)|Float(32|64)|Uint8Clamped|Big(I|Ui)nt64)Array\]/;var X=c?function(n){return h?h(n)&&!q(n):H(n)&&Q.test(a.call(n))}:C(!1),Y=J("length");function Z(n,r){r=function(n){for(var r={},t=n.length,e=0;e<t;++e)r[n[e]]=!0;return{contains:function(n){return!0===r[n]},push:function(t){return r[t]=!0,n.push(t)}}}(r);var t=b.length,u=n.constructor,o=D(u)&&u.prototype||e,i="constructor";for(W(n,i)&&!r.contains(i)&&r.push(i);t--;)(i=b[t])in n&&n[i]!==o[i]&&!r.contains(i)&&r.push(i)}function nn(n){if(!_(n))return[];if(p)return p(n);var r=[];for(var t in n)W(n,t)&&r.push(t);return g&&Z(n,r),r}function rn(n,r){var t=nn(r),e=t.length;if(null==n)return!e;for(var u=Object(n),o=0;o<e;o++){var i=t[o];if(r[i]!==u[i]||!(i in u))return!1}return!0}function tn(n){return n instanceof tn?n:this instanceof tn?void(this._wrapped=n):new tn(n)}function en(n){return new Uint8Array(n.buffer||n,n.byteOffset||0,G(n))}tn.VERSION=n,tn.prototype.value=function(){return this._wrapped},tn.prototype.valueOf=tn.prototype.toJSON=tn.prototype.value,tn.prototype.toString=function(){return String(this._wrapped)};var un="[object DataView]";function on(n,r,t,e){if(n===r)return 0!==n||1/n==1/r;if(null==n||null==r)return!1;if(n!=n)return r!=r;var o=typeof n;return("function"===o||"object"===o||"object"==typeof r)&&function n(r,t,e,o){r instanceof tn&&(r=r._wrapped);t instanceof tn&&(t=t._wrapped);var i=a.call(r);if(i!==a.call(t))return!1;if(F&&"[object Object]"==i&&q(r)){if(!q(t))return!1;i=un}switch(i){case"[object RegExp]":case"[object String]":return""+r==""+t;case"[object Number]":return+r!=+r?+t!=+t:0==+r?1/+r==1/t:+r==+t;case"[object Date]":case"[object Boolean]":return+r==+t;case"[object Symbol]":return u.valueOf.call(r)===u.valueOf.call(t);case"[object ArrayBuffer]":case un:return n(en(r),en(t),e,o)}var f="[object Array]"===i;if(!f&&X(r)){if(G(r)!==G(t))return!1;if(r.buffer===t.buffer&&r.byteOffset===t.byteOffset)return!0;f=!0}if(!f){if("object"!=typeof r||"object"!=typeof t)return!1;var c=r.constructor,l=t.constructor;if(c!==l&&!(D(c)&&c instanceof c&&D(l)&&l instanceof l)&&"constructor"in r&&"constructor"in t)return!1}o=o||[];var s=(e=e||[]).length;for(;s--;)if(e[s]===r)return o[s]===t;if(e.push(r),o.push(t),f){if((s=r.length)!==t.length)return!1;for(;s--;)if(!on(r[s],t[s],e,o))return!1}else{var p,v=nn(r);if(s=v.length,nn(t).length!==s)return!1;for(;s--;)if(p=v[s],!W(t,p)||!on(r[p],t[p],e,o))return!1}return e.pop(),o.pop(),!0}(n,r,t,e)}function an(n){if(!_(n))return[];var r=[];for(var t in n)r.push(t);return g&&Z(n,r),r}function fn(n){var r=Y(n);return function(t){if(null==t)return!1;var e=an(t);if(Y(e))return!1;for(var u=0;u<r;u++)if(!D(t[n[u]]))return!1;return n!==hn||!D(t[cn])}}var cn="forEach",ln="has",sn=["clear","delete"],pn=["get",ln,"set"],vn=sn.concat(cn,pn),hn=sn.concat(pn),yn=["add"].concat(sn,cn,ln),dn=V?fn(vn):x("Map"),gn=V?fn(hn):x("WeakMap"),bn=V?fn(yn):x("Set"),mn=x("WeakSet");function jn(n){for(var r=nn(n),t=r.length,e=Array(t),u=0;u<t;u++)e[u]=n[r[u]];return e}function _n(n){for(var r={},t=nn(n),e=0,u=t.length;e<u;e++)r[n[t[e]]]=t[e];return r}function wn(n){var r=[];for(var t in n)D(n[t])&&r.push(t);return r.sort()}function An(n,r){return function(t){var e=arguments.length;if(r&&(t=Object(t)),e<2||null==t)return t;for(var u=1;u<e;u++)for(var o=arguments[u],i=n(o),a=i.length,f=0;f<a;f++){var c=i[f];r&&void 0!==t[c]||(t[c]=o[c])}return t}}var xn=An(an),Sn=An(nn),On=An(an,!0);function Mn(n){if(!_(n))return{};if(v)return v(n);var r=function(){};r.prototype=n;var t=new r;return r.prototype=null,t}function En(n){return U(n)?n:[n]}function Bn(n){return tn.toPath(n)}function Nn(n,r){for(var t=r.length,e=0;e<t;e++){if(null==n)return;n=n[r[e]]}return t?n:void 0}function In(n,r,t){var e=Nn(n,Bn(r));return w(e)?t:e}function Tn(n){return n}function kn(n){return n=Sn({},n),function(r){return rn(r,n)}}function Dn(n){return n=Bn(n),function(r){return Nn(r,n)}}function Rn(n,r,t){if(void 0===r)return n;switch(null==t?3:t){case 1:return function(t){return n.call(r,t)};case 3:return function(t,e,u){return n.call(r,t,e,u)};case 4:return function(t,e,u,o){return n.call(r,t,e,u,o)}}return function(){return n.apply(r,arguments)}}function Fn(n,r,t){return null==n?Tn:D(n)?Rn(n,r,t):_(n)&&!U(n)?kn(n):Dn(n)}function Vn(n,r){return Fn(n,r,1/0)}function Pn(n,r,t){return tn.iteratee!==Vn?tn.iteratee(n,r):Fn(n,r,t)}function qn(){}function Un(n,r){return null==r&&(r=n,n=0),n+Math.floor(Math.random()*(r-n+1))}tn.toPath=En,tn.iteratee=Vn;var Wn=Date.now||function(){return(new Date).getTime()};function zn(n){var r=function(r){return n[r]},t="(?:"+nn(n).join("|")+")",e=RegExp(t),u=RegExp(t,"g");return function(n){return n=null==n?"":""+n,e.test(n)?n.replace(u,r):n}}var Ln={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"},$n=zn(Ln),Cn=zn(_n(Ln)),Kn=tn.templateSettings={evaluate:/<%([\s\S]+?)%>/g,interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g},Jn=/(.)^/,Gn={"'":"'","\\":"\\","\r":"r","\n":"n","\u2028":"u2028","\u2029":"u2029"},Hn=/\\|'|\r|\n|\u2028|\u2029/g;function Qn(n){return"\\"+Gn[n]}var Xn=/^\s*(\w|\$)+\s*$/;var Yn=0;function Zn(n,r,t,e,u){if(!(e instanceof r))return n.apply(t,u);var o=Mn(n.prototype),i=n.apply(o,u);return _(i)?i:o}var nr=j((function(n,r){var t=nr.placeholder,e=function(){for(var u=0,o=r.length,i=Array(o),a=0;a<o;a++)i[a]=r[a]===t?arguments[u++]:r[a];for(;u<arguments.length;)i.push(arguments[u++]);return Zn(n,e,this,this,i)};return e}));nr.placeholder=tn;var rr=j((function(n,r,t){if(!D(n))throw new TypeError("Bind must be called on a function");var e=j((function(u){return Zn(n,e,r,this,t.concat(u))}));return e})),tr=K(Y);function er(n,r,t,e){if(e=e||[],r||0===r){if(r<=0)return e.concat(n)}else r=1/0;for(var u=e.length,o=0,i=Y(n);o<i;o++){var a=n[o];if(tr(a)&&(U(a)||L(a)))if(r>1)er(a,r-1,t,e),u=e.length;else for(var f=0,c=a.length;f<c;)e[u++]=a[f++];else t||(e[u++]=a)}return e}var ur=j((function(n,r){var t=(r=er(r,!1,!1)).length;if(t<1)throw new Error("bindAll must be passed function names");for(;t--;){var e=r[t];n[e]=rr(n[e],n)}return n}));var or=j((function(n,r,t){return setTimeout((function(){return n.apply(null,t)}),r)})),ir=nr(or,tn,1);function ar(n){return function(){return!n.apply(this,arguments)}}function fr(n,r){var t;return function(){return--n>0&&(t=r.apply(this,arguments)),n<=1&&(r=null),t}}var cr=nr(fr,2);function lr(n,r,t){r=Pn(r,t);for(var e,u=nn(n),o=0,i=u.length;o<i;o++)if(r(n[e=u[o]],e,n))return e}function sr(n){return function(r,t,e){t=Pn(t,e);for(var u=Y(r),o=n>0?0:u-1;o>=0&&o<u;o+=n)if(t(r[o],o,r))return o;return-1}}var pr=sr(1),vr=sr(-1);function hr(n,r,t,e){for(var u=(t=Pn(t,e,1))(r),o=0,i=Y(n);o<i;){var a=Math.floor((o+i)/2);t(n[a])<u?o=a+1:i=a}return o}function yr(n,r,t){return function(e,u,o){var a=0,f=Y(e);if("number"==typeof o)n>0?a=o>=0?o:Math.max(o+f,a):f=o>=0?Math.min(o+1,f):o+f+1;else if(t&&o&&f)return e[o=t(e,u)]===u?o:-1;if(u!=u)return(o=r(i.call(e,a,f),$))>=0?o+a:-1;for(o=n>0?a:f-1;o>=0&&o<f;o+=n)if(e[o]===u)return o;return-1}}var dr=yr(1,pr,hr),gr=yr(-1,vr);function br(n,r,t){var e=(tr(n)?pr:lr)(n,r,t);if(void 0!==e&&-1!==e)return n[e]}function mr(n,r,t){var e,u;if(r=Rn(r,t),tr(n))for(e=0,u=n.length;e<u;e++)r(n[e],e,n);else{var o=nn(n);for(e=0,u=o.length;e<u;e++)r(n[o[e]],o[e],n)}return n}function jr(n,r,t){r=Pn(r,t);for(var e=!tr(n)&&nn(n),u=(e||n).length,o=Array(u),i=0;i<u;i++){var a=e?e[i]:i;o[i]=r(n[a],a,n)}return o}function _r(n){var r=function(r,t,e,u){var o=!tr(r)&&nn(r),i=(o||r).length,a=n>0?0:i-1;for(u||(e=r[o?o[a]:a],a+=n);a>=0&&a<i;a+=n){var f=o?o[a]:a;e=t(e,r[f],f,r)}return e};return function(n,t,e,u){var o=arguments.length>=3;return r(n,Rn(t,u,4),e,o)}}var wr=_r(1),Ar=_r(-1);function xr(n,r,t){var e=[];return r=Pn(r,t),mr(n,(function(n,t,u){r(n,t,u)&&e.push(n)})),e}function Sr(n,r,t){r=Pn(r,t);for(var e=!tr(n)&&nn(n),u=(e||n).length,o=0;o<u;o++){var i=e?e[o]:o;if(!r(n[i],i,n))return!1}return!0}function Or(n,r,t){r=Pn(r,t);for(var e=!tr(n)&&nn(n),u=(e||n).length,o=0;o<u;o++){var i=e?e[o]:o;if(r(n[i],i,n))return!0}return!1}function Mr(n,r,t,e){return tr(n)||(n=jn(n)),("number"!=typeof t||e)&&(t=0),dr(n,r,t)>=0}var Er=j((function(n,r,t){var e,u;return D(r)?u=r:(r=Bn(r),e=r.slice(0,-1),r=r[r.length-1]),jr(n,(function(n){var o=u;if(!o){if(e&&e.length&&(n=Nn(n,e)),null==n)return;o=n[r]}return null==o?o:o.apply(n,t)}))}));function Br(n,r){return jr(n,Dn(r))}function Nr(n,r,t){var e,u,o=-1/0,i=-1/0;if(null==r||"number"==typeof r&&"object"!=typeof n[0]&&null!=n)for(var a=0,f=(n=tr(n)?n:jn(n)).length;a<f;a++)null!=(e=n[a])&&e>o&&(o=e);else r=Pn(r,t),mr(n,(function(n,t,e){((u=r(n,t,e))>i||u===-1/0&&o===-1/0)&&(o=n,i=u)}));return o}var Ir=/[^\ud800-\udfff]|[\ud800-\udbff][\udc00-\udfff]|[\ud800-\udfff]/g;function Tr(n){return n?U(n)?i.call(n):S(n)?n.match(Ir):tr(n)?jr(n,Tn):jn(n):[]}function kr(n,r,t){if(null==r||t)return tr(n)||(n=jn(n)),n[Un(n.length-1)];var e=Tr(n),u=Y(e);r=Math.max(Math.min(r,u),0);for(var o=u-1,i=0;i<r;i++){var a=Un(i,o),f=e[i];e[i]=e[a],e[a]=f}return e.slice(0,r)}function Dr(n,r){return function(t,e,u){var o=r?[[],[]]:{};return e=Pn(e,u),mr(t,(function(r,u){var i=e(r,u,t);n(o,r,i)})),o}}var Rr=Dr((function(n,r,t){W(n,t)?n[t].push(r):n[t]=[r]})),Fr=Dr((function(n,r,t){n[t]=r})),Vr=Dr((function(n,r,t){W(n,t)?n[t]++:n[t]=1})),Pr=Dr((function(n,r,t){n[t?0:1].push(r)}),!0);function qr(n,r,t){return r in t}var Ur=j((function(n,r){var t={},e=r[0];if(null==n)return t;D(e)?(r.length>1&&(e=Rn(e,r[1])),r=an(n)):(e=qr,r=er(r,!1,!1),n=Object(n));for(var u=0,o=r.length;u<o;u++){var i=r[u],a=n[i];e(a,i,n)&&(t[i]=a)}return t})),Wr=j((function(n,r){var t,e=r[0];return D(e)?(e=ar(e),r.length>1&&(t=r[1])):(r=jr(er(r,!1,!1),String),e=function(n,t){return!Mr(r,t)}),Ur(n,e,t)}));function zr(n,r,t){return i.call(n,0,Math.max(0,n.length-(null==r||t?1:r)))}function Lr(n,r,t){return null==n||n.length<1?null==r||t?void 0:[]:null==r||t?n[0]:zr(n,n.length-r)}function $r(n,r,t){return i.call(n,null==r||t?1:r)}var Cr=j((function(n,r){return r=er(r,!0,!0),xr(n,(function(n){return!Mr(r,n)}))})),Kr=j((function(n,r){return Cr(n,r)}));function Jr(n,r,t,e){A(r)||(e=t,t=r,r=!1),null!=t&&(t=Pn(t,e));for(var u=[],o=[],i=0,a=Y(n);i<a;i++){var f=n[i],c=t?t(f,i,n):f;r&&!t?(i&&o===c||u.push(f),o=c):t?Mr(o,c)||(o.push(c),u.push(f)):Mr(u,f)||u.push(f)}return u}var Gr=j((function(n){return Jr(er(n,!0,!0))}));function Hr(n){for(var r=n&&Nr(n,Y).length||0,t=Array(r),e=0;e<r;e++)t[e]=Br(n,e);return t}var Qr=j(Hr);function Xr(n,r){return n._chain?tn(r).chain():r}function Yr(n){return mr(wn(n),(function(r){var t=tn[r]=n[r];tn.prototype[r]=function(){var n=[this._wrapped];return o.apply(n,arguments),Xr(this,t.apply(tn,n))}})),tn}mr(["pop","push","reverse","shift","sort","splice","unshift"],(function(n){var r=t[n];tn.prototype[n]=function(){var t=this._wrapped;return null!=t&&(r.apply(t,arguments),"shift"!==n&&"splice"!==n||0!==t.length||delete t[0]),Xr(this,t)}})),mr(["concat","join","slice"],(function(n){var r=t[n];tn.prototype[n]=function(){var n=this._wrapped;return null!=n&&(n=r.apply(n,arguments)),Xr(this,n)}}));var Zr=Yr({__proto__:null,VERSION:n,restArguments:j,isObject:_,isNull:function(n){return null===n},isUndefined:w,isBoolean:A,isElement:function(n){return!(!n||1!==n.nodeType)},isString:S,isNumber:O,isDate:M,isRegExp:E,isError:B,isSymbol:N,isArrayBuffer:I,isDataView:q,isArray:U,isFunction:D,isArguments:L,isFinite:function(n){return!N(n)&&d(n)&&!isNaN(parseFloat(n))},isNaN:$,isTypedArray:X,isEmpty:function(n){if(null==n)return!0;var r=Y(n);return"number"==typeof r&&(U(n)||S(n)||L(n))?0===r:0===Y(nn(n))},isMatch:rn,isEqual:function(n,r){return on(n,r)},isMap:dn,isWeakMap:gn,isSet:bn,isWeakSet:mn,keys:nn,allKeys:an,values:jn,pairs:function(n){for(var r=nn(n),t=r.length,e=Array(t),u=0;u<t;u++)e[u]=[r[u],n[r[u]]];return e},invert:_n,functions:wn,methods:wn,extend:xn,extendOwn:Sn,assign:Sn,defaults:On,create:function(n,r){var t=Mn(n);return r&&Sn(t,r),t},clone:function(n){return _(n)?U(n)?n.slice():xn({},n):n},tap:function(n,r){return r(n),n},get:In,has:function(n,r){for(var t=(r=Bn(r)).length,e=0;e<t;e++){var u=r[e];if(!W(n,u))return!1;n=n[u]}return!!t},mapObject:function(n,r,t){r=Pn(r,t);for(var e=nn(n),u=e.length,o={},i=0;i<u;i++){var a=e[i];o[a]=r(n[a],a,n)}return o},identity:Tn,constant:C,noop:qn,toPath:En,property:Dn,propertyOf:function(n){return null==n?qn:function(r){return In(n,r)}},matcher:kn,matches:kn,times:function(n,r,t){var e=Array(Math.max(0,n));r=Rn(r,t,1);for(var u=0;u<n;u++)e[u]=r(u);return e},random:Un,now:Wn,escape:$n,unescape:Cn,templateSettings:Kn,template:function(n,r,t){!r&&t&&(r=t),r=On({},r,tn.templateSettings);var e=RegExp([(r.escape||Jn).source,(r.interpolate||Jn).source,(r.evaluate||Jn).source].join("|")+"|$","g"),u=0,o="__p+='";n.replace(e,(function(r,t,e,i,a){return o+=n.slice(u,a).replace(Hn,Qn),u=a+r.length,t?o+="'+\n((__t=("+t+"))==null?'':_.escape(__t))+\n'":e?o+="'+\n((__t=("+e+"))==null?'':__t)+\n'":i&&(o+="';\n"+i+"\n__p+='"),r})),o+="';\n";var i,a=r.variable;if(a){if(!Xn.test(a))throw new Error("variable is not a bare identifier: "+a)}else o="with(obj||{}){\n"+o+"}\n",a="obj";o="var __t,__p='',__j=Array.prototype.join,"+"print=function(){__p+=__j.call(arguments,'');};\n"+o+"return __p;\n";try{i=new Function(a,"_",o)}catch(n){throw n.source=o,n}var f=function(n){return i.call(this,n,tn)};return f.source="function("+a+"){\n"+o+"}",f},result:function(n,r,t){var e=(r=Bn(r)).length;if(!e)return D(t)?t.call(n):t;for(var u=0;u<e;u++){var o=null==n?void 0:n[r[u]];void 0===o&&(o=t,u=e),n=D(o)?o.call(n):o}return n},uniqueId:function(n){var r=++Yn+"";return n?n+r:r},chain:function(n){var r=tn(n);return r._chain=!0,r},iteratee:Vn,partial:nr,bind:rr,bindAll:ur,memoize:function(n,r){var t=function(e){var u=t.cache,o=""+(r?r.apply(this,arguments):e);return W(u,o)||(u[o]=n.apply(this,arguments)),u[o]};return t.cache={},t},delay:or,defer:ir,throttle:function(n,r,t){var e,u,o,i,a=0;t||(t={});var f=function(){a=!1===t.leading?0:Wn(),e=null,i=n.apply(u,o),e||(u=o=null)},c=function(){var c=Wn();a||!1!==t.leading||(a=c);var l=r-(c-a);return u=this,o=arguments,l<=0||l>r?(e&&(clearTimeout(e),e=null),a=c,i=n.apply(u,o),e||(u=o=null)):e||!1===t.trailing||(e=setTimeout(f,l)),i};return c.cancel=function(){clearTimeout(e),a=0,e=u=o=null},c},debounce:function(n,r,t){var e,u,o,i,a,f=function(){var c=Wn()-u;r>c?e=setTimeout(f,r-c):(e=null,t||(i=n.apply(a,o)),e||(o=a=null))},c=j((function(c){return a=this,o=c,u=Wn(),e||(e=setTimeout(f,r),t&&(i=n.apply(a,o))),i}));return c.cancel=function(){clearTimeout(e),e=o=a=null},c},wrap:function(n,r){return nr(r,n)},negate:ar,compose:function(){var n=arguments,r=n.length-1;return function(){for(var t=r,e=n[r].apply(this,arguments);t--;)e=n[t].call(this,e);return e}},after:function(n,r){return function(){if(--n<1)return r.apply(this,arguments)}},before:fr,once:cr,findKey:lr,findIndex:pr,findLastIndex:vr,sortedIndex:hr,indexOf:dr,lastIndexOf:gr,find:br,detect:br,findWhere:function(n,r){return br(n,kn(r))},each:mr,forEach:mr,map:jr,collect:jr,reduce:wr,foldl:wr,inject:wr,reduceRight:Ar,foldr:Ar,filter:xr,select:xr,reject:function(n,r,t){return xr(n,ar(Pn(r)),t)},every:Sr,all:Sr,some:Or,any:Or,contains:Mr,includes:Mr,include:Mr,invoke:Er,pluck:Br,where:function(n,r){return xr(n,kn(r))},max:Nr,min:function(n,r,t){var e,u,o=1/0,i=1/0;if(null==r||"number"==typeof r&&"object"!=typeof n[0]&&null!=n)for(var a=0,f=(n=tr(n)?n:jn(n)).length;a<f;a++)null!=(e=n[a])&&e<o&&(o=e);else r=Pn(r,t),mr(n,(function(n,t,e){((u=r(n,t,e))<i||u===1/0&&o===1/0)&&(o=n,i=u)}));return o},shuffle:function(n){return kr(n,1/0)},sample:kr,sortBy:function(n,r,t){var e=0;return r=Pn(r,t),Br(jr(n,(function(n,t,u){return{value:n,index:e++,criteria:r(n,t,u)}})).sort((function(n,r){var t=n.criteria,e=r.criteria;if(t!==e){if(t>e||void 0===t)return 1;if(t<e||void 0===e)return-1}return n.index-r.index})),"value")},groupBy:Rr,indexBy:Fr,countBy:Vr,partition:Pr,toArray:Tr,size:function(n){return null==n?0:tr(n)?n.length:nn(n).length},pick:Ur,omit:Wr,first:Lr,head:Lr,take:Lr,initial:zr,last:function(n,r,t){return null==n||n.length<1?null==r||t?void 0:[]:null==r||t?n[n.length-1]:$r(n,Math.max(0,n.length-r))},rest:$r,tail:$r,drop:$r,compact:function(n){return xr(n,Boolean)},flatten:function(n,r){return er(n,r,!1)},without:Kr,uniq:Jr,unique:Jr,union:Gr,intersection:function(n){for(var r=[],t=arguments.length,e=0,u=Y(n);e<u;e++){var o=n[e];if(!Mr(r,o)){var i;for(i=1;i<t&&Mr(arguments[i],o);i++);i===t&&r.push(o)}}return r},difference:Cr,unzip:Hr,transpose:Hr,zip:Qr,object:function(n,r){for(var t={},e=0,u=Y(n);e<u;e++)r?t[n[e]]=r[e]:t[n[e][0]]=n[e][1];return t},range:function(n,r,t){null==r&&(r=n||0,n=0),t||(t=r<n?-1:1);for(var e=Math.max(Math.ceil((r-n)/t),0),u=Array(e),o=0;o<e;o++,n+=t)u[o]=n;return u},chunk:function(n,r){if(null==r||r<1)return[];for(var t=[],e=0,u=n.length;e<u;)t.push(i.call(n,e,e+=r));return t},mixin:Yr,default:tn});return Zr._=Zr,Zr}));;
/*! @drupal/once - v1.0.1 - 2021-06-12 */
var once=function(){"use strict";var n=/[\11\12\14\15\40]+/,e="data-once",t=document;function r(n,t,r){return n[t+"Attribute"](e,r)}function o(e){if("string"!=typeof e)throw new TypeError("once ID must be a string");if(""===e||n.test(e))throw new RangeError("once ID must not be empty or contain spaces");return'[data-once~="'+e+'"]'}function u(n){if(!(n instanceof Element))throw new TypeError("The element must be an instance of Element");return!0}function i(n,e){void 0===e&&(e=t);var r=n;if(null===n)r=[];else{if(!n)throw new TypeError("Selector must not be empty");"string"!=typeof n||e!==t&&!u(e)?n instanceof Element&&(r=[n]):r=e.querySelectorAll(n)}return Array.prototype.slice.call(r)}function c(n,e,t){return e.filter((function(e){var r=u(e)&&e.matches(n);return r&&t&&t(e),r}))}function f(e,t){var o=t.add,u=t.remove,i=[];r(e,"has")&&r(e,"get").trim().split(n).forEach((function(n){i.indexOf(n)<0&&n!==u&&i.push(n)})),o&&i.push(o);var c=i.join(" ");r(e,""===c?"remove":"set",c)}function a(n,e,t){return c(":not("+o(n)+")",i(e,t),(function(e){return f(e,{add:n})}))}return a.remove=function(n,e,t){return c(o(n),i(e,t),(function(e){return f(e,{remove:n})}))},a.filter=function(n,e,t){return c(o(n),i(e,t))},a.find=function(n,e){return i(n?o(n):"[data-once]",e)},a}();

;
/*!
 * jQuery Once v2.2.3 - http://github.com/robloach/jquery-once
 * @license MIT, GPL-2.0
 *   http://opensource.org/licenses/MIT
 *   http://opensource.org/licenses/GPL-2.0
 */
(function(e){"use strict";if(typeof exports==="object"&&typeof exports.nodeName!=="string"){e(require("jquery"))}else if(typeof define==="function"&&define.amd){define(["jquery"],e)}else{e(jQuery)}})(function(t){"use strict";var r=function(e){e=e||"once";if(typeof e!=="string"){throw new TypeError("The jQuery Once id parameter must be a string")}return e};t.fn.once=function(e){var n="jquery-once-"+r(e);return this.filter(function(){return t(this).data(n)!==true}).data(n,true)};t.fn.removeOnce=function(e){return this.findOnce(e).removeData("jquery-once-"+r(e))};t.fn.findOnce=function(e){var n="jquery-once-"+r(e);return this.filter(function(){return t(this).data(n)===true})}});

/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function () {
  var settingsElement = document.querySelector('head > script[type="application/json"][data-drupal-selector="drupal-settings-json"], body > script[type="application/json"][data-drupal-selector="drupal-settings-json"]');
  window.drupalSettings = {};

  if (settingsElement !== null) {
    window.drupalSettings = JSON.parse(settingsElement.textContent);
  }
})();;
/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

window.Drupal = {
  behaviors: {},
  locale: {}
};

(function (Drupal, drupalSettings, drupalTranslations, console, Proxy, Reflect) {
  Drupal.throwError = function (error) {
    setTimeout(function () {
      throw error;
    }, 0);
  };

  Drupal.attachBehaviors = function (context, settings) {
    context = context || document;
    settings = settings || drupalSettings;
    var behaviors = Drupal.behaviors;
    Object.keys(behaviors || {}).forEach(function (i) {
      if (typeof behaviors[i].attach === 'function') {
        try {
          behaviors[i].attach(context, settings);
        } catch (e) {
          Drupal.throwError(e);
        }
      }
    });
  };

  Drupal.detachBehaviors = function (context, settings, trigger) {
    context = context || document;
    settings = settings || drupalSettings;
    trigger = trigger || 'unload';
    var behaviors = Drupal.behaviors;
    Object.keys(behaviors || {}).forEach(function (i) {
      if (typeof behaviors[i].detach === 'function') {
        try {
          behaviors[i].detach(context, settings, trigger);
        } catch (e) {
          Drupal.throwError(e);
        }
      }
    });
  };

  Drupal.checkPlain = function (str) {
    str = str.toString().replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    return str;
  };

  Drupal.formatString = function (str, args) {
    var processedArgs = {};
    Object.keys(args || {}).forEach(function (key) {
      switch (key.charAt(0)) {
        case '@':
          processedArgs[key] = Drupal.checkPlain(args[key]);
          break;

        case '!':
          processedArgs[key] = args[key];
          break;

        default:
          processedArgs[key] = Drupal.theme('placeholder', args[key]);
          break;
      }
    });
    return Drupal.stringReplace(str, processedArgs, null);
  };

  Drupal.stringReplace = function (str, args, keys) {
    if (str.length === 0) {
      return str;
    }

    if (!Array.isArray(keys)) {
      keys = Object.keys(args || {});
      keys.sort(function (a, b) {
        return a.length - b.length;
      });
    }

    if (keys.length === 0) {
      return str;
    }

    var key = keys.pop();
    var fragments = str.split(key);

    if (keys.length) {
      for (var i = 0; i < fragments.length; i++) {
        fragments[i] = Drupal.stringReplace(fragments[i], args, keys.slice(0));
      }
    }

    return fragments.join(args[key]);
  };

  Drupal.t = function (str, args, options) {
    options = options || {};
    options.context = options.context || '';

    if (typeof drupalTranslations !== 'undefined' && drupalTranslations.strings && drupalTranslations.strings[options.context] && drupalTranslations.strings[options.context][str]) {
      str = drupalTranslations.strings[options.context][str];
    }

    if (args) {
      str = Drupal.formatString(str, args);
    }

    return str;
  };

  Drupal.url = function (path) {
    return drupalSettings.path.baseUrl + drupalSettings.path.pathPrefix + path;
  };

  Drupal.url.toAbsolute = function (url) {
    var urlParsingNode = document.createElement('a');

    try {
      url = decodeURIComponent(url);
    } catch (e) {}

    urlParsingNode.setAttribute('href', url);
    return urlParsingNode.cloneNode(false).href;
  };

  Drupal.url.isLocal = function (url) {
    var absoluteUrl = Drupal.url.toAbsolute(url);
    var protocol = window.location.protocol;

    if (protocol === 'http:' && absoluteUrl.indexOf('https:') === 0) {
      protocol = 'https:';
    }

    var baseUrl = "".concat(protocol, "//").concat(window.location.host).concat(drupalSettings.path.baseUrl.slice(0, -1));

    try {
      absoluteUrl = decodeURIComponent(absoluteUrl);
    } catch (e) {}

    try {
      baseUrl = decodeURIComponent(baseUrl);
    } catch (e) {}

    return absoluteUrl === baseUrl || absoluteUrl.indexOf("".concat(baseUrl, "/")) === 0;
  };

  Drupal.formatPlural = function (count, singular, plural, args, options) {
    args = args || {};
    args['@count'] = count;
    var pluralDelimiter = drupalSettings.pluralDelimiter;
    var translations = Drupal.t(singular + pluralDelimiter + plural, args, options).split(pluralDelimiter);
    var index = 0;

    if (typeof drupalTranslations !== 'undefined' && drupalTranslations.pluralFormula) {
      index = count in drupalTranslations.pluralFormula ? drupalTranslations.pluralFormula[count] : drupalTranslations.pluralFormula.default;
    } else if (args['@count'] !== 1) {
      index = 1;
    }

    return translations[index];
  };

  Drupal.encodePath = function (item) {
    return window.encodeURIComponent(item).replace(/%2F/g, '/');
  };

  Drupal.deprecationError = function (_ref) {
    var message = _ref.message;

    if (drupalSettings.suppressDeprecationErrors === false && typeof console !== 'undefined' && console.warn) {
      console.warn("[Deprecation] ".concat(message));
    }
  };

  Drupal.deprecatedProperty = function (_ref2) {
    var target = _ref2.target,
        deprecatedProperty = _ref2.deprecatedProperty,
        message = _ref2.message;

    if (!Proxy || !Reflect) {
      return target;
    }

    return new Proxy(target, {
      get: function get(target, key) {
        if (key === deprecatedProperty) {
          Drupal.deprecationError({
            message: message
          });
        }

        for (var _len = arguments.length, rest = new Array(_len > 2 ? _len - 2 : 0), _key = 2; _key < _len; _key++) {
          rest[_key - 2] = arguments[_key];
        }

        return Reflect.get.apply(Reflect, [target, key].concat(rest));
      }
    });
  };

  Drupal.theme = function (func) {
    if (func in Drupal.theme) {
      var _Drupal$theme;

      for (var _len2 = arguments.length, args = new Array(_len2 > 1 ? _len2 - 1 : 0), _key2 = 1; _key2 < _len2; _key2++) {
        args[_key2 - 1] = arguments[_key2];
      }

      return (_Drupal$theme = Drupal.theme)[func].apply(_Drupal$theme, args);
    }
  };

  Drupal.theme.placeholder = function (str) {
    return "<em class=\"placeholder\">".concat(Drupal.checkPlain(str), "</em>");
  };
})(Drupal, window.drupalSettings, window.drupalTranslations, window.console, window.Proxy, window.Reflect);;
/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

if (window.jQuery) {
  jQuery.noConflict();
}

document.documentElement.className += ' js';

(function (Drupal, drupalSettings) {
  var domReady = function domReady(callback) {
    var listener = function listener() {
      callback();
      document.removeEventListener('DOMContentLoaded', listener);
    };

    if (document.readyState !== 'loading') {
      setTimeout(callback, 0);
    } else {
      document.addEventListener('DOMContentLoaded', listener);
    }
  };

  domReady(function () {
    Drupal.attachBehaviors(document, drupalSettings);
  });
})(Drupal, window.drupalSettings);;
!function r(a,o,i){function c(e,t){if(!o[e]){if(!a[e]){var n="function"==typeof require&&require;if(!t&&n)return n(e,!0);if(s)return s(e,!0);throw(t=new Error("Cannot find module '"+e+"'")).code="MODULE_NOT_FOUND",t}n=o[e]={exports:{}},a[e][0].call(n.exports,function(t){return c(a[e][1][t]||t)},n,n.exports,r,a,o,i)}return o[e].exports}for(var s="function"==typeof require&&require,t=0;t<i.length;t++)c(i[t]);return c}({1:[function(t,e,n){"use strict";var r;if("document"in window.self)if("classList"in document.createElement("_")&&(!document.createElementNS||"classList"in document.createElementNS("http://www.w3.org/2000/svg","g")))(a=document.createElement("_")).classList.add("c1","c2"),a.classList.contains("c2")||((i=function(t){var r=DOMTokenList.prototype[t];DOMTokenList.prototype[t]=function(t){for(var e=arguments.length,n=0;n<e;n++)r.call(this,arguments[n])}})("add"),i("remove")),a.classList.toggle("c3",!1),a.classList.contains("c3")&&(r=DOMTokenList.prototype.toggle,DOMTokenList.prototype.toggle=function(t,e){return 1 in arguments&&!this.contains(t)==!e?e:r.call(this,t)});else if("Element"in(i=window.self)){var a="classList",o="prototype",i=i.Element[o],c=Object,s=String[o].trim||function(){return this.replace(/^\s+|\s+$/g,"")},u=Array[o].indexOf||function(t){for(var e=0,n=this.length;e<n;e++)if(e in this&&this[e]===t)return e;return-1},l=function(t,e){this.name=t,this.code=DOMException[t],this.message=e},d=function(t,e){if(""===e)throw new l("SYNTAX_ERR","An invalid or illegal string was specified");if(/\s/.test(e))throw new l("INVALID_CHARACTER_ERR","String contains an invalid character");return u.call(t,e)},f=function(t){for(var e=s.call(t.getAttribute("class")||""),n=e?e.split(/\s+/):[],r=0,a=n.length;r<a;r++)this.push(n[r]);this._updateClassName=function(){t.setAttribute("class",this.toString())}},p=f[o]=[],b=function(){return new f(this)};if(l[o]=Error[o],p.item=function(t){return this[t]||null},p.contains=function(t){return-1!==d(this,t+="")},p.add=function(){for(var t,e=arguments,n=0,r=e.length,a=!1;-1===d(this,t=e[n]+"")&&(this.push(t),a=!0),++n<r;);a&&this._updateClassName()},p.remove=function(){var t,e,n=arguments,r=0,a=n.length,o=!1;do{for(e=d(this,t=n[r]+"");-1!==e;)this.splice(e,1),o=!0,e=d(this,t)}while(++r<a);o&&this._updateClassName()},p.toggle=function(t,e){var n=this.contains(t+=""),r=n?!0!==e&&"remove":!1!==e&&"add";return r&&this[r](t),!0===e||!1===e?e:!n},p.toString=function(){return this.join(" ")},c.defineProperty){p={get:b,enumerable:!0,configurable:!0};try{c.defineProperty(i,a,p)}catch(t){-2146823252===t.number&&(p.enumerable=!1,c.defineProperty(i,a,p))}}else c[o].__defineGetter__&&i.__defineGetter__(a,b)}},{}],2:[function(t,e,n){"use strict";function r(t){return(r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}!function(t){void 0!==e?e.exports=t():"function"==typeof define&&"object"==r(define.amd)?define(t):this.domready=t()}(function(){var t,e=[],n=document,r=n.documentElement.doScroll,a="DOMContentLoaded",o=(r?/^loaded|^c/:/^loaded|^i|^c/).test(n.readyState);return o||n.addEventListener(a,t=function(){for(n.removeEventListener(a,t),o=1;t=e.shift();)t()}),function(t){o?setTimeout(t,0):e.push(t)}})},{}],3:[function(t,e,n){"use strict";var r;"function"!=typeof(r=window.Element.prototype).matches&&(r.matches=r.msMatchesSelector||r.mozMatchesSelector||r.webkitMatchesSelector||function(t){for(var e=(this.document||this.ownerDocument).querySelectorAll(t),n=0;e[n]&&e[n]!==this;)++n;return Boolean(e[n])}),"function"!=typeof r.closest&&(r.closest=function(t){for(var e=this;e&&1===e.nodeType;){if(e.matches(t))return e;e=e.parentNode}return null})},{}],4:[function(t,e,n){"use strict";for(var r={polyfill:function(){if(!("KeyboardEvent"in window)||"key"in KeyboardEvent.prototype)return!1;var t={get:function(t){var e=r.keys[this.which||this.keyCode];return e=Array.isArray(e)?e[+this.shiftKey]:e}};return Object.defineProperty(KeyboardEvent.prototype,"key",t),t},keys:{3:"Cancel",6:"Help",8:"Backspace",9:"Tab",12:"Clear",13:"Enter",16:"Shift",17:"Control",18:"Alt",19:"Pause",20:"CapsLock",27:"Escape",28:"Convert",29:"NonConvert",30:"Accept",31:"ModeChange",32:" ",33:"PageUp",34:"PageDown",35:"End",36:"Home",37:"ArrowLeft",38:"ArrowUp",39:"ArrowRight",40:"ArrowDown",41:"Select",42:"Print",43:"Execute",44:"PrintScreen",45:"Insert",46:"Delete",48:["0",")"],49:["1","!"],50:["2","@"],51:["3","#"],52:["4","$"],53:["5","%"],54:["6","^"],55:["7","&"],56:["8","*"],57:["9","("],91:"OS",93:"ContextMenu",144:"NumLock",145:"ScrollLock",181:"VolumeMute",182:"VolumeDown",183:"VolumeUp",186:[";",":"],187:["=","+"],188:[",","<"],189:["-","_"],190:[".",">"],191:["/","?"],192:["`","~"],219:["[","{"],220:["\\","|"],221:["]","}"],222:["'",'"'],224:"Meta",225:"AltGraph",246:"Attn",247:"CrSel",248:"ExSel",249:"EraseEof",250:"Play",251:"ZoomOut"}},a=1;a<25;a++)r.keys[111+a]="F"+a;var o="";for(a=65;a<91;a++)o=String.fromCharCode(a),r.keys[a]=[o.toLowerCase(),o.toUpperCase()];"function"==typeof define&&define.amd?define("keyboardevent-key-polyfill",r):void 0!==n&&void 0!==e?e.exports=r:window&&(window.keyboardeventKeyPolyfill=r)},{}],5:[function(t,e,n){"use strict";var s=Object.getOwnPropertySymbols,u=Object.prototype.hasOwnProperty,l=Object.prototype.propertyIsEnumerable;e.exports=function(){try{if(!Object.assign)return;var t=new String("abc");if(t[5]="de","5"===Object.getOwnPropertyNames(t)[0])return;for(var e={},n=0;n<10;n++)e["_"+String.fromCharCode(n)]=n;if("0123456789"!==Object.getOwnPropertyNames(e).map(function(t){return e[t]}).join(""))return;var r={};return"abcdefghijklmnopqrst".split("").forEach(function(t){r[t]=t}),"abcdefghijklmnopqrst"!==Object.keys(Object.assign({},r)).join("")?void 0:1}catch(t){return}}()?Object.assign:function(t,e){for(var n,r=function(t){if(null==t)throw new TypeError("Object.assign cannot be called with null or undefined");return Object(t)}(t),a=1;a<arguments.length;a++){for(var o in n=Object(arguments[a]))u.call(n,o)&&(r[o]=n[o]);if(s)for(var i=s(n),c=0;c<i.length;c++)l.call(n,i[c])&&(r[i[c]]=n[i[c]])}return r}},{}],6:[function(t,e,n){"use strict";function s(t){return(s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function u(t,e){var n=t[e];return delete t[e],n}var l=t("object-assign"),d=t("../delegate"),f=t("../delegateAll"),p=/^(.+):delegate\((.+)\)$/;e.exports=function(c,t){var n=Object.keys(c).reduce(function(t,e){n=c[e=e],(i=e.match(p))&&(e=i[1],r=i[2]),"object"===s(n)&&(a={capture:u(n,"capture"),passive:u(n,"passive")}),o={selector:r,delegate:"object"===s(n)?f(n):r?d(r,n):n,options:a};var n,r,a,o,i=-1<e.indexOf(" ")?e.split(" ").map(function(t){return l({type:t},o)}):(o.type=e,[o]);return t.concat(i)},[]);return l({add:function(e){n.forEach(function(t){e.addEventListener(t.type,t.delegate,t.options)})},remove:function(e){n.forEach(function(t){e.removeEventListener(t.type,t.delegate,t.options)})}},t)}},{"../delegate":8,"../delegateAll":9,"object-assign":5}],7:[function(t,e,n){"use strict";e.exports=function(t){return function(e){return t.some(function(t){return!1===t.call(this,e)},this)}}},{}],8:[function(t,e,n){"use strict";t("element-closest"),e.exports=function(n,r){return function(t){var e=t.target.closest(n);if(e)return r.call(e,t)}}},{"element-closest":3}],9:[function(t,e,n){"use strict";var r=t("../delegate"),a=t("../compose");e.exports=function(n){var t=Object.keys(n);if(1===t.length&&"*"===t[0])return n["*"];t=t.reduce(function(t,e){return t.push(r(e,n[e])),t},[]);return a(t)}},{"../compose":7,"../delegate":8}],10:[function(t,e,n){"use strict";e.exports=function(e,n){return function(t){if(e!==t.target&&!e.contains(t.target))return n.call(this,t)}}},{}],11:[function(t,e,n){"use strict";e.exports={behavior:t("./behavior"),delegate:t("./delegate"),delegateAll:t("./delegateAll"),ignore:t("./ignore"),keymap:t("./keymap")}},{"./behavior":6,"./delegate":8,"./delegateAll":9,"./ignore":10,"./keymap":12}],12:[function(t,e,n){"use strict";t("keyboardevent-key-polyfill");var o={Alt:"altKey",Control:"ctrlKey",Ctrl:"ctrlKey",Shift:"shiftKey"};e.exports=function(a){var t=Object.keys(a).some(function(t){return-1<t.indexOf("+")});return function(n){var r=function(t,e){var n=t.key;if(e)for(var r in o)!0===t[o[r]]&&(n=[r,n].join("+"));return n}(n,t);return[r,r.toLowerCase()].reduce(function(t,e){return t=e in a?a[r].call(this,n):t},void 0)}},e.exports.MODIFIERS=o},{"keyboardevent-key-polyfill":4}],13:[function(t,e,n){"use strict";e.exports=function(e,n){function r(t){return t.currentTarget.removeEventListener(t.type,r,n),e.call(this,t)}return r}},{}],14:[function(t,e,n){"use strict";function r(t){return(r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}var a=/(^\s+)|(\s+$)/g,o=/\s+/,i=String.prototype.trim?function(t){return t.trim()}:function(t){return t.replace(a,"")};e.exports=function(t,e){if("string"!=typeof t)throw new Error("Expected a string but got "+r(t));var n=((e=e||window.document).getElementById||function(t){return this.querySelector('[id="'+t.replace(/"/g,'\\"')+'"]')}).bind(e);return 1===(t=i(t).split(o)).length&&""===t[0]?[]:t.map(function(t){var e=n(t);if(e)return e;throw new Error('no element with id: "'+t+'"')})}},{}],15:[function(t,e,n){"use strict";function r(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function a(e){return i(f,e).filter(function(t){return t.closest(d)===e})}function o(e,t){var n=e.closest(d);if(!n)throw new Error("".concat(f," is missing outer ").concat(d));var t=s(e,t),r="true"===n.getAttribute("aria-multiselectable");t&&!r&&a(n).forEach(function(t){t!==e&&s(t,!1)})}var i=t("../utils/select"),c=t("../utils/behavior"),s=t("../utils/toggle"),u=t("../utils/is-in-viewport"),l=t("../events").CLICK,t=t("../config").prefix,d=".".concat(t,"-accordion, .").concat(t,"-accordion--bordered"),f=".".concat(t,"-accordion__button[aria-controls]"),p="aria-expanded",t=c(r({},l,r({},f,function(t){t.preventDefault(),o(this),"true"!==this.getAttribute(p)||u(this)||this.scrollIntoView()})),{init:function(t){i(f,t).forEach(function(t){var e="true"===t.getAttribute(p);o(t,e)})},ACCORDION:d,BUTTON:f,show:function(t){return o(t,!0)},hide:function(t){return o(t,!1)},toggle:o,getButtons:a});e.exports=t},{"../config":34,"../events":35,"../utils/behavior":43,"../utils/is-in-viewport":45,"../utils/select":49,"../utils/toggle":52}],16:[function(t,e,n){"use strict";function r(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var a=t("../utils/behavior"),o=t("../events").CLICK,t=t("../config").prefix,i=".".concat(t,"-banner__header"),c="".concat(t,"-banner__header--expanded");e.exports=a(r({},o,r({},"".concat(i," [aria-controls]"),function(t){t.preventDefault(),this.closest(i).classList.toggle(c)})))},{"../config":34,"../events":35,"../utils/behavior":43}],17:[function(t,e,n){"use strict";function c(t){if(!(t=t.closest(s)))throw new Error("".concat(u," is missing outer ").concat(s));var e=t.querySelector(l);if(e)return{characterCountEl:t,messageEl:e};throw new Error("".concat(s," is missing inner ").concat(l))}function a(t){var e,n,r,a,o=(i=c(t)).characterCountEl,i=i.messageEl;(o=parseInt(o.getAttribute("data-maxlength"),10))&&(a="",e=(n=t.value.length)&&o<n,a=0===n?"".concat(o," characters allowed"):(o=Math.abs(o-n),n="character".concat(1===o?"":"s"),r=e?"over limit":"left","".concat(o," ").concat(n," ").concat(r)),i.classList.toggle(f,e),i.textContent=a,e&&!t.validationMessage&&t.setCustomValidity(d),e||t.validationMessage!==d||t.setCustomValidity(""))}var r,o=t("../utils/select"),i=t("../utils/behavior"),t=t("../config").prefix,s=".".concat(t,"-character-count"),u=".".concat(t,"-character-count__field"),l=".".concat(t,"-character-count__message"),d="The content is too long.",f="".concat(t,"-character-count__message--invalid"),i=i({input:(t=function(){a(this)},(i=u)in(r={})?Object.defineProperty(r,i,{value:t,enumerable:!0,configurable:!0,writable:!0}):r[i]=t,r)},{init:function(t){o(u,t).forEach(function(t){var e,n,r;n=c(e=t).characterCountEl,(r=e.getAttribute("maxlength"))&&(e.removeAttribute("maxlength"),n.setAttribute("data-maxlength",r)),a(t)})},MESSAGE_INVALID_CLASS:f,VALIDATION_MESSAGE:d});e.exports=i},{"../config":34,"../utils/behavior":43,"../utils/select":49}],18:[function(t,N,B){"use strict";var v,h;function m(t,e){return e=e||t.slice(0),Object.freeze(Object.defineProperties(t,{raw:{value:Object.freeze(e)}}))}function g(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function y(t){var e=(t=O(t)).inputEl,n=t.toggleListBtnEl;(t=t.clearInputBtnEl).hidden=!0,t.disabled=!0,n.disabled=!0,e.disabled=!0}function e(t){if(!(t=t.closest(M)).dataset.enhanced){var n=t.querySelector("select");if(!n)throw new Error("".concat(M," is missing inner select"));var e,r=n.id,a=document.querySelector('label[for="'.concat(r,'"]')),o="".concat(r,"--list"),i="".concat(r,"-label"),c="".concat(r,"--assistiveHint"),s=[],u=t.dataset.defaultValue,l=t.dataset.placeholder;if(l&&s.push({placeholder:l}),u)for(var d=0,f=n.options.length;d<f;d+=1){var p=n.options[d];if(p.value===u){e=p;break}}if(!a||!a.matches('label[for="'.concat(r,'"]')))throw new Error("".concat(M," for ").concat(r,' is either missing a label or a "for" attribute'));a.setAttribute("id",i),a.setAttribute("id",i),n.setAttribute("aria-hidden","true"),n.setAttribute("tabindex","-1"),n.classList.add("usa-sr-only",w),n.id="",n.value="",["required","aria-label","aria-labelledby"].forEach(function(t){var e;n.hasAttribute(t)&&(e=n.getAttribute(t),s.push(g({},t,e)),n.removeAttribute(t))});var b=document.createElement("input");b.setAttribute("id",r),b.setAttribute("aria-owns",o),b.setAttribute("aria-controls",o),b.setAttribute("aria-autocomplete","list"),b.setAttribute("aria-describedby",c),b.setAttribute("aria-expanded","false"),b.setAttribute("autocapitalize","off"),b.setAttribute("autocomplete","off"),b.setAttribute("class",x),b.setAttribute("type","text"),b.setAttribute("role","combobox"),s.forEach(function(n){return Object.keys(n).forEach(function(t){var e=A.escapeHTML(v=v||m(["",""]),n[t]);b.setAttribute(t,e)})}),t.insertAdjacentElement("beforeend",b),t.insertAdjacentHTML("beforeend",A.escapeHTML(h=h||m(['\n    <span class="','" tabindex="-1">\n        <button type="button" class="','" aria-label="Clear the select contents">&nbsp;</button>\n      </span>\n      <span class="','">&nbsp;</span>\n      <span class="','" tabindex="-1">\n        <button type="button" tabindex="-1" class="','" aria-label="Toggle the dropdown list">&nbsp;</button>\n      </span>\n      <ul\n        tabindex="-1"\n        id="','"\n        class="','"\n        role="listbox"\n        aria-labelledby="','"\n        hidden>\n      </ul>\n      <div class="',' usa-sr-only" role="status"></div>\n      <span id="','" class="usa-sr-only">\n        When autocomplete results are available use up and down arrows to review and enter to select.\n        Touch device users, explore by touch or with swipe gestures.\n      </span>']),H,S,F,R,L,o,D,i,k,c)),e&&(l=O(t).inputEl,j(n,e.value),j(l,e.text),t.classList.add(E)),n.disabled&&(y(t),n.disabled=!1),t.dataset.enhanced="true"}}function a(t){for(var c,e,n=(t=O(t)).comboBoxEl,r=t.selectEl,a=t.inputEl,o=t.listEl,i=t.statusEl,s=t.isPristine,u=t.disableFiltering,l="".concat(o.id,"--option-"),d=(a.value||"").toLowerCase(),t=n.dataset.filter||W,f=$(t,d,n.dataset),p=[],b=0,v=r.options.length;b<v;b+=1){var h=r.options[b],m="".concat(l).concat(p.length);h.value&&(u||s||!d||f.test(h.text))&&(r.value&&h.value===r.value&&(c=m),u&&!e&&f.test(h.text)&&(e=m),p.push(h))}var g,t=p.length,n=p.map(function(t,e){var n="".concat(l).concat(e),r=[_],a="-1",o="false",i=(n===c&&(r.push(T,C),a="0",o="true"),c||0!==e||(r.push(C),a="0"),document.createElement("li"));return i.setAttribute("aria-setsize",p.length),i.setAttribute("aria-posinset",e+1),i.setAttribute("aria-selected",o),i.setAttribute("id",n),i.setAttribute("class",r.join(" ")),i.setAttribute("tabindex",a),i.setAttribute("role","option"),i.setAttribute("data-value",t.value),i.textContent=t.text,i}),y=document.createElement("li");y.setAttribute("class","".concat(_,"--no-results")),y.textContent="No results found",o.hidden=!1,t?(o.innerHTML="",n.forEach(function(t){return o.insertAdjacentElement("beforeend",t)})):(o.innerHTML="",o.insertAdjacentElement("beforeend",y)),a.setAttribute("aria-expanded","true"),i.textContent=t?"".concat(t," result").concat(1<t?"s":""," available."):"No results.",s&&c?g=o.querySelector("#".concat(c)):u&&e&&(g=o.querySelector("#".concat(e))),g&&q(o,g,{skipFocus:!0})}function o(t){var e=(t=O(t)).inputEl,n=t.listEl,r=t.statusEl,t=t.focusedOptionEl;r.innerHTML="",e.setAttribute("aria-expanded","false"),e.setAttribute("aria-activedescendant",""),t&&t.classList.remove(C),n.scrollTop=0,n.hidden=!0}function n(t){var e=(r=O(t)).comboBoxEl,n=r.selectEl,r=r.inputEl;j(n,t.dataset.value),j(r,t.textContent),e.classList.add(E),o(e),r.focus()}function r(t){var e=O(t.target),n=e.comboBoxEl;(e=e.listEl).hidden&&a(n),(e=e.querySelector(z)||e.querySelector(b))&&q(n,e),t.preventDefault()}function i(t){var e=t.target,n=e.nextSibling;n&&q(e,n),t.preventDefault()}function c(t){var e=(r=O(t.target)).comboBoxEl,n=r.listEl,r=(r=r.focusedOptionEl)&&r.previousSibling,n=!n.hidden;q(e,r),n&&t.preventDefault(),r||o(e)}var s=t("receptor/keymap"),P=t("../utils/select"),u=t("../utils/behavior"),A=t("../utils/sanitizer"),l=t("../config").prefix,t=t("../events").CLICK,l="".concat(l,"-combo-box"),E="".concat(l,"--pristine"),w="".concat(l,"__select"),x="".concat(l,"__input"),S="".concat(l,"__clear-input"),H="".concat(S,"__wrapper"),F="".concat(l,"__input-button-separator"),L="".concat(l,"__toggle-list"),R="".concat(L,"__wrapper"),D="".concat(l,"__list"),_="".concat(l,"__list-option"),C="".concat(_,"--focused"),T="".concat(_,"--selected"),k="".concat(l,"__status"),M=".".concat(l),Y=".".concat(w),d=".".concat(x),f=".".concat(S),p=".".concat(L),U=".".concat(D),b=".".concat(_),z=".".concat(C),K=".".concat(T),V=".".concat(k),W=".*{{query}}.*",j=function(t){var e=1<arguments.length&&void 0!==arguments[1]?arguments[1]:"",e=(t.value=e,new CustomEvent("change",{bubbles:!0,cancelable:!0,detail:{value:e}}));t.dispatchEvent(e)},O=function(t){t=t.closest(M);if(!t)throw new Error("Element is missing outer ".concat(M));var e=t.querySelector(Y),n=t.querySelector(d),r=t.querySelector(U),a=t.querySelector(V),o=t.querySelector(z),i=t.querySelector(K),c=t.querySelector(p),s=t.querySelector(f),u=t.classList.contains(E);return{comboBoxEl:t,selectEl:e,inputEl:n,listEl:r,statusEl:a,focusedOptionEl:o,selectedOptionEl:i,toggleListBtnEl:c,clearInputBtnEl:s,isPristine:u,disableFiltering:"true"===t.dataset.disableFiltering}},q=function(t,e){var n=2<arguments.length&&void 0!==arguments[2]?arguments[2]:{},r=n.skipFocus,n=n.preventScroll,t=O(t),a=t.inputEl,o=t.listEl,t=t.focusedOptionEl;t&&(t.classList.remove(C),t.setAttribute("tabIndex","-1")),e?(a.setAttribute("aria-activedescendant",e.id),e.setAttribute("tabIndex","0"),e.classList.add(C),n||(t=e.offsetTop+e.offsetHeight,o.scrollTop+o.offsetHeight<t&&(o.scrollTop=t-o.offsetHeight),e.offsetTop<o.scrollTop&&(o.scrollTop=e.offsetTop)),r||e.focus({preventScroll:n})):(a.setAttribute("aria-activedescendant",""),a.focus())},$=function(t){function r(t){return t.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&")}var a=1<arguments.length&&void 0!==arguments[1]?arguments[1]:"",o=2<arguments.length&&void 0!==arguments[2]?arguments[2]:{},t=t.replace(/{{(.*?)}}/g,function(t,e){var e=e.trim(),n=o[e];return"query"!==e&&n?(e=new RegExp(n,"i"),(n=a.match(e))?r(n[1]):""):r(a)}),t="^(?:".concat(t,")$");return new RegExp(t,"i")},I=function(t){var t=O(t),e=t.comboBoxEl,n=t.selectEl,r=t.inputEl,a=n.value,o=(r.value||"").toLowerCase();if(a)for(var i=0,c=n.options.length;i<c;i+=1){var s=n.options[i];if(s.value===a)return o!==s.text&&j(r,s.text),void e.classList.add(E)}o&&j(r)},s=u((g(u={},t,(g(t={},d,function(){var t,e;this.disabled||(t=O(t=this),e=t.comboBoxEl,t.listEl.hidden&&a(e))}),g(t,p,function(){var t,e,n;this.disabled||(t=O(t=this),e=t.comboBoxEl,n=t.listEl,t=t.inputEl,(n.hidden?a:o)(e),t.focus())}),g(t,b,function(){this.disabled||n(this)}),g(t,f,function(){var t,e,n,r;this.disabled||(t=O(t=this),e=t.comboBoxEl,n=t.listEl,r=t.selectEl,t=t.inputEl,n=!n.hidden,r.value&&j(r),t.value&&j(t),e.classList.remove(E),n&&a(e),t.focus())}),t)),g(u,"focusout",g({},M,function(t){this.contains(t.relatedTarget)||(I(this),o(this))})),g(u,"keydown",(g(t={},M,s({Escape:function(t){var t=O(t.target),e=t.comboBoxEl,t=t.inputEl;o(e),I(e),t.focus()}})),g(t,d,s({Enter:function(t){var e=O(t.target),n=e.comboBoxEl,e=!e.listEl.hidden;!function(t){var t=O(t),e=t.comboBoxEl,n=t.selectEl,r=t.inputEl,a=(t.statusEl.textContent="",(r.value||"").toLowerCase());if(a)for(var o=0,i=n.options.length;o<i;o+=1){var c=n.options[o];if(c.text.toLowerCase()===a)return j(n,c.value),j(r,c.text),e.classList.add(E)}I(e)}(n),e&&o(n),t.preventDefault()},ArrowDown:r,Down:r})),g(t,b,s({ArrowUp:c,Up:c,ArrowDown:i,Down:i,Enter:function(t){n(t.target),t.preventDefault()},Tab:function(t){n(t.target),t.preventDefault()},"Shift+Tab":function(){}})),t)),g(u,"input",g({},d,function(){this.closest(M).classList.remove(E),a(this)})),g(u,"mouseover",g({},b,function(){var t;(t=this).classList.contains(C)||q(t,t,{preventScroll:!0})})),u),{init:function(t){P(M,t).forEach(function(t){e(t)})},getComboBoxContext:O,enhanceComboBox:e,generateDynamicRegExp:$,disable:y,enable:function(t){var t=O(t),e=t.inputEl,n=t.toggleListBtnEl,t=t.clearInputBtnEl;t.hidden=!1,t.disabled=!1,n.disabled=!1,e.disabled=!1},displayList:a,hideList:o,COMBO_BOX_CLASS:l});N.exports=s},{"../config":34,"../events":35,"../utils/behavior":43,"../utils/sanitizer":47,"../utils/select":49,"receptor/keymap":12}],19:[function(t,w,x){"use strict";var _,G,Z,C,T,k,e;function n(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function H(t,e){return e=e||t.slice(0),Object.freeze(Object.defineProperties(t,{raw:{value:Object.freeze(e)}}))}function M(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var n=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=n){var r,a,o=[],i=!0,c=!1;try{for(n=n.call(t);!(i=(r=n.next()).done)&&(o.push(r.value),!e||o.length!==e);i=!0);}catch(t){c=!0,a=t}finally{try{i||null==n.return||n.return()}finally{if(c)throw a}}return o}}(t,e)||function(t,e){if(t){if("string"==typeof t)return j(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Map"===(n="Object"===n&&t.constructor?t.constructor.name:n)||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?j(t,e):void 0}}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function j(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}function O(){for(var t=arguments.length,e=new Array(t),n=0;n<t;n++)e[n]=arguments[n];return e.map(function(t){return t+":not([disabled])"}).join(", ")}function q(t){var e=new Date(0);return e.setFullYear(t.getFullYear(),t.getMonth()+1,0),e}function I(t,e){return z(t,7*e)}function N(t,e){return K(t,12*e)}function B(t,e){return N(t,-e)}function s(t,e,n){var r=t;return t<e?r=e:n&&n<t&&(r=n),new Date(r.getTime())}function P(t){var e=(t=Q(t)).externalInputEl;t.toggleBtnEl.disabled=!0,e.disabled=!0}function X(t){var e,n,r,a,o=(t=Q(t)).externalInputEl,i=t.minDate,t=t.maxDate,o=o.value,c=!1;return o&&(c=!0,e=(r=M((o=o.split("/")).map(function(t){var e,t=parseInt(t,10);return e=Number.isNaN(t)?e:t}),3))[0],n=r[1],r=r[2],e&&n&&null!=r&&((a=he(r,e-1,n)).getMonth()===e-1&&a.getDate()===n&&a.getFullYear()===r&&4===o[2].length&&Le(a,i,t)&&(c=!1))),c}function J(t,e){var n,r,a=E(e);a&&(a=$(a,le),n=(t=Q(t)).datePickerEl,r=t.internalInputEl,t=t.externalInputEl,ke(r,e),ke(t,a),Me(n))}function c(t,e){for(var n,r,a,o,i,c,s,u,l,d,f,p=(t=Q(t)).datePickerEl,b=t.calendarEl,v=t.statusEl,h=t.selectedDate,m=t.maxDate,g=t.minDate,y=t.rangeDate,A=me(),E=e||A,t=b.hidden,w=z(E,0),x=E.getMonth(),e=E.getFullYear(),S=Ee(E,1),N=K(E,1),L=$(E),D=ge(E),_=V(E,g),C=V(E,m),T=h||E,k=y&&we(T,y),M=y&&xe(T,y),B=y&&z(k,1),P=y&&ye(M,1),T=se[x],E=Ae(D),j=[];j.length<28||E.getMonth()===x||j.length%7!=0;)j.push((f=c=d=l=u=s=c=i=o=a=r=void 0,r=[R],a=(n=E).getDate(),o=n.getMonth(),i=n.getFullYear(),c=n.getDay(),s=$(n),u="-1",l=!Le(n,g,m),d=W(n,h),V(n,S)&&r.push(mt),V(n,w)&&r.push(gt),V(n,N)&&r.push(yt),d&&r.push(ht),W(n,A)&&r.push(Et),y&&(W(n,y)&&r.push(At),W(n,k)&&r.push(wt),W(n,M)&&r.push(xt),Le(n,B,P)&&r.push(St)),W(n,w)&&(u="0",r.push(vt)),n=se[o],c=ue[c],(f=document.createElement("button")).setAttribute("type","button"),f.setAttribute("tabindex",u),f.setAttribute("class",r.join(" ")),f.setAttribute("data-day",a),f.setAttribute("data-month",o+1),f.setAttribute("data-year",i),f.setAttribute("data-value",s),f.setAttribute("aria-label",F.escapeHTML(G=G||H([""," "," "," ",""]),a,n,i,c)),f.setAttribute("aria-selected",d?"true":"false"),!0==l&&(f.disabled=!0),f.textContent=a,f)),E=z(E,1);var D=Ce(j,7),O=b.cloneNode();O.dataset.value=L,O.style.top="".concat(p.offsetHeight,"px"),O.hidden=!1,O.innerHTML=F.escapeHTML(Z=Z||H(['\n    <div tabindex="-1" class="','">\n      <div class="','">\n        <div class="'," ",'">\n          <button\n            type="button"\n            class="','"\n            aria-label="Navigate back one year"\n            ','\n          ></button>\n        </div>\n        <div class="'," ",'">\n          <button\n            type="button"\n            class="','"\n            aria-label="Navigate back one month"\n            ','\n          ></button>\n        </div>\n        <div class="'," ",'">\n          <button\n            type="button"\n            class="','" aria-label="','. Click to select month"\n          >','</button>\n          <button\n            type="button"\n            class="','" aria-label="','. Click to select year"\n          >','</button>\n        </div>\n        <div class="'," ",'">\n          <button\n            type="button"\n            class="','"\n            aria-label="Navigate forward one month"\n            ','\n          ></button>\n        </div>\n        <div class="'," ",'">\n          <button\n            type="button"\n            class="','"\n            aria-label="Navigate forward one year"\n            ',"\n          ></button>\n        </div>\n      </div>\n    </div>\n    "]),Bt,Rt,Y,Yt,Lt,_?'disabled="disabled"':"",Y,Yt,Dt,_?'disabled="disabled"':"",Y,Ut,Tt,T,T,kt,e,e,Y,Yt,Ct,C?'disabled="disabled"':"",Y,Yt,_t,C?'disabled="disabled"':"");(L=document.createElement("table")).setAttribute("class",Ft),L.setAttribute("role","presentation");var _=document.createElement("thead"),q=(L.insertAdjacentElement("beforeend",_),document.createElement("tr")),I=(_.insertAdjacentElement("beforeend",q),{Sunday:"S",Monday:"M",Tuesday:"T",Wednesday:"W",Thursday:"Th",Friday:"Fr",Saturday:"S"}),C=(Object.keys(I).forEach(function(t){var e=document.createElement("th");e.setAttribute("class",zt),e.setAttribute("scope","presentation"),e.setAttribute("aria-label",t),e.textContent=I[t],q.insertAdjacentElement("beforeend",e)}),Te(D));return L.insertAdjacentElement("beforeend",C),O.querySelector(U).insertAdjacentElement("beforeend",L),b.parentNode.replaceChild(O,b),p.classList.add(lt),_=[],W(h,w)&&_.push("Selected date"),t?(_.push("You can navigate by day using left and right arrows","Weeks by using up and down arrows","Months by using page up and page down keys","Years by using shift plus page up and shift plus page down","Home and end keys navigate to the beginning and end of a week"),v.textContent=""):_.push("".concat(T," ").concat(e)),v.textContent=_.join(". "),O}function i(t){var e=(t=Q(t)).datePickerEl,n=t.calendarEl,t=t.statusEl;e.classList.remove(lt),n.hidden=!0,t.textContent=""}function tt(t){var e=(t=Q(t)).calendarEl,n=t.inputDate,r=t.minDate,t=t.maxDate;!e.hidden&&n&&(n=s(n,r,t),c(e,n))}function et(t,e){var n=(t=Q(t)).calendarEl,r=t.statusEl,c=t.calendarDate,s=t.minDate,u=t.maxDate,l=c.getMonth(),d=null==e?l:e,t=se.map(function(t,e){var n=A(c,e),n=De(n,s,u),r="-1",a=[b],o=e===l,i=(e===d&&(r="0",a.push(Mt)),o&&a.push(jt),document.createElement("button"));return i.setAttribute("type","button"),i.setAttribute("tabindex",r),i.setAttribute("class",a.join(" ")),i.setAttribute("data-value",e),i.setAttribute("data-label",t),i.setAttribute("aria-selected",o?"true":"false"),!0===n&&(i.disabled=!0),i.textContent=t,i}),a=((e=document.createElement("div")).setAttribute("tabindex","-1"),e.setAttribute("class",Pt),document.createElement("table")),t=(a.setAttribute("class",Ft),a.setAttribute("role","presentation"),Ce(t,3)),t=Te(t);return a.insertAdjacentElement("beforeend",t),e.insertAdjacentElement("beforeend",a),(t=n.cloneNode()).insertAdjacentElement("beforeend",e),n.parentNode.replaceChild(t,n),r.textContent="Select a month.",t}function u(t,e){for(var n=(t=Q(t)).calendarEl,r=t.statusEl,a=t.calendarDate,o=t.minDate,i=t.maxDate,c=a.getFullYear(),s=null==e?c:e,t=s,e=(t-=t%L,t=Math.max(0,t),_e(D(a,t-1),o,i)),u=_e(D(a,t+L),o,i),l=[],d=t;l.length<L;){var f=_e(D(a,d),o,i),p="-1",b=[S],v=d===c,h=(d===s&&(p="0",b.push(Ot)),v&&b.push(qt),document.createElement("button"));h.setAttribute("type","button"),h.setAttribute("tabindex",p),h.setAttribute("class",b.join(" ")),h.setAttribute("data-value",d),h.setAttribute("aria-selected",v?"true":"false"),!0===f&&(h.disabled=!0),h.textContent=d,l.push(h),d+=1}var m=n.cloneNode(),g=document.createElement("div"),y=(g.setAttribute("tabindex","-1"),g.setAttribute("class",Ht),document.createElement("table")),A=(y.setAttribute("role","presentation"),y.setAttribute("class",Ft),document.createElement("tbody")),E=document.createElement("tr"),w=document.createElement("button");w.setAttribute("type","button"),w.setAttribute("class",It),w.setAttribute("aria-label","Navigate back ".concat(L," years")),!0===e&&(w.disabled=!0),w.innerHTML=F.escapeHTML(C=C||H(["&nbsp"])),(e=document.createElement("button")).setAttribute("type","button"),e.setAttribute("class",Nt),e.setAttribute("aria-label","Navigate forward ".concat(L," years")),!0===u&&(e.disabled=!0),e.innerHTML=F.escapeHTML(T=T||H(["&nbsp"]));(u=document.createElement("table")).setAttribute("class",Ft),u.setAttribute("role","presentation");var x=Ce(l,3),x=Te(x);return u.insertAdjacentElement("beforeend",x),(x=document.createElement("td")).insertAdjacentElement("beforeend",w),(w=document.createElement("td")).setAttribute("colspan","3"),w.insertAdjacentElement("beforeend",u),(u=document.createElement("td")).insertAdjacentElement("beforeend",e),E.insertAdjacentElement("beforeend",x),E.insertAdjacentElement("beforeend",w),E.insertAdjacentElement("beforeend",u),A.insertAdjacentElement("beforeend",E),y.insertAdjacentElement("beforeend",A),g.insertAdjacentElement("beforeend",y),m.insertAdjacentElement("beforeend",g),n.parentNode.replaceChild(m,n),r.textContent=F.escapeHTML(k=k||H(["Showing years "," to ",". Select a year."]),t,t+L-1),m}function nt(t){var e=(n=Q(t.target)).datePickerEl,n=n.externalInputEl;i(e),n.focus(),t.preventDefault()}function r(i){return function(t){var e=Q(t.target),n=e.calendarEl,r=e.calendarDate,a=e.minDate,e=e.maxDate,o=i(r),o=s(o,a,e);W(r,o)||c(n,o).querySelector(h).focus(),t.preventDefault()}}function a(c){return function(t){var e=t.target,n=parseInt(e.dataset.value,10),e=Q(e),r=e.calendarEl,a=e.calendarDate,o=e.minDate,e=e.maxDate,i=A(a,n),n=c(n),n=Math.max(0,Math.min(11,n)),a=A(a,n),n=s(a,o,e);V(i,n)||et(r,n.getMonth()).querySelector(ie).focus(),t.preventDefault()}}function o(c){return function(t){var e=t.target,n=parseInt(e.dataset.value,10),e=Q(e),r=e.calendarEl,a=e.calendarDate,o=e.minDate,e=e.maxDate,i=D(a,n),n=c(n),n=Math.max(0,n),a=D(a,n),n=s(a,o,e);Se(i,n)||u(r,n.getFullYear()).querySelector(y).focus(),t.preventDefault()}}function rt(o){function a(t){var t=Q(t).calendarEl,e=(t=ot(o,t)).length-1,n=t[0],r=t[e],a=t.indexOf(it());return{focusableElements:t,isNotFound:-1===a,firstTabStop:n,isFirstTab:0===a,lastTabStop:r,isLastTab:a===e}}return{tabAhead:function(t){var e=a(t.target),n=e.firstTabStop,r=e.isLastTab,e=e.isNotFound;(r||e)&&(t.preventDefault(),n.focus())},tabBack:function(t){var e=a(t.target),n=e.lastTabStop,r=e.isFirstTab,e=e.isNotFound;(r||e)&&(t.preventDefault(),n.focus())}}}var l=t("receptor/keymap"),at=t("../utils/behavior"),ot=t("../utils/select"),d=t("../config").prefix,f=t("../events").CLICK,it=t("../utils/active-element"),ct=t("../utils/is-ios-device"),F=t("../utils/sanitizer"),t="".concat(d,"-date-picker"),st="".concat(t,"__wrapper"),ut="".concat(t,"--initialized"),lt="".concat(t,"--active"),dt="".concat(t,"__internal-input"),ft="".concat(t,"__external-input"),pt="".concat(t,"__button"),p="".concat(t,"__calendar"),bt="".concat(t,"__status"),R="".concat(p,"__date"),vt="".concat(R,"--focused"),ht="".concat(R,"--selected"),mt="".concat(R,"--previous-month"),gt="".concat(R,"--current-month"),yt="".concat(R,"--next-month"),At="".concat(R,"--range-date"),Et="".concat(R,"--today"),wt="".concat(R,"--range-date-start"),xt="".concat(R,"--range-date-end"),St="".concat(R,"--within-range"),Lt="".concat(p,"__previous-year"),Dt="".concat(p,"__previous-month"),_t="".concat(p,"__next-year"),Ct="".concat(p,"__next-month"),Tt="".concat(p,"__month-selection"),kt="".concat(p,"__year-selection"),b="".concat(p,"__month"),Mt="".concat(b,"--focused"),jt="".concat(b,"--selected"),S="".concat(p,"__year"),Ot="".concat(S,"--focused"),qt="".concat(S,"--selected"),It="".concat(p,"__previous-year-chunk"),Nt="".concat(p,"__next-year-chunk"),Bt="".concat(p,"__date-picker"),Pt="".concat(p,"__month-picker"),Ht="".concat(p,"__year-picker"),Ft="".concat(p,"__table"),Rt="".concat(p,"__row"),Y="".concat(p,"__cell"),Yt="".concat(Y,"--center-items"),Ut="".concat(p,"__month-label"),zt="".concat(p,"__day-of-week"),v=".".concat(t),Kt=".".concat(pt),Vt=".".concat(dt),Wt=".".concat(ft),$t=".".concat(p),Qt=".".concat(bt),d=".".concat(R),h=".".concat(vt),t=".".concat(gt),Gt=".".concat(Lt),Zt=".".concat(Dt),Xt=".".concat(_t),Jt=".".concat(Ct),m=".".concat(kt),g=".".concat(Tt),te=".".concat(b),ee=".".concat(S),ne=".".concat(It),re=".".concat(Nt),U=".".concat(Bt),ae=".".concat(Pt),oe=".".concat(Ht),ie=".".concat(Mt),y=".".concat(Ot),ce="Please enter a valid date",se=["January","February","March","April","May","June","July","August","September","October","November","December"],ue=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],L=12,le="MM/DD/YYYY",de="YYYY-MM-DD",fe=O(Gt,Zt,m,g,Xt,Jt,h),pe=O(ie),be=O(ne,re,y),ve=function(t,e){return e!==t.getMonth()&&t.setDate(0),t},he=function(t,e,n){var r=new Date(0);return r.setFullYear(t,e,n),r},me=function(){var t=new Date,e=t.getDate(),n=t.getMonth(),t=t.getFullYear();return he(t,n,e)},ge=function(t){var e=new Date(0);return e.setFullYear(t.getFullYear(),t.getMonth(),1),e},z=function(t,e){t=new Date(t.getTime());return t.setDate(t.getDate()+e),t},ye=function(t,e){return z(t,-e)},Ae=function(t){var e=t.getDay();return ye(t,e)},K=function(t,e){var t=new Date(t.getTime()),n=(t.getMonth()+12+e)%12;return t.setMonth(t.getMonth()+e),ve(t,n),t},Ee=function(t,e){return K(t,-e)},A=function(t,e){t=new Date(t.getTime());return t.setMonth(e),ve(t,e),t},D=function(t,e){var t=new Date(t.getTime()),n=t.getMonth();return t.setFullYear(e),ve(t,n),t},we=function(t,e){return new Date((e<t?e:t).getTime())},xe=function(t,e){return new Date((t<e?e:t).getTime())},Se=function(t,e){return t&&e&&t.getFullYear()===e.getFullYear()},V=function(t,e){return Se(t,e)&&t.getMonth()===e.getMonth()},W=function(t,e){return V(t,e)&&t.getDate()===e.getDate()},Le=function(t,e,n){return e<=t&&(!n||t<=n)},De=function(t,e,n){return q(t)<e||n&&ge(t)>n},_e=function(t,e,n){return q(A(t,11))<e||n&&ge(A(t,0))>n},E=function(t){var e,n,r,a,o,i,c,s,u=1<arguments.length&&void 0!==arguments[1]?arguments[1]:de,l=2<arguments.length&&void 0!==arguments[2]&&arguments[2];return t&&(u===le?(r=(u=M(t.split("/"),3))[0],a=u[1],o=u[2]):(o=(u=M(t.split("-"),3))[0],r=u[1],a=u[2]),o&&(c=parseInt(o,10),Number.isNaN(c)||(i=c,l&&(i=Math.max(0,i),o.length<3&&(i=(t=me().getFullYear())-t%Math.pow(10,o.length)+c)))),r&&(c=parseInt(r,10),Number.isNaN(c)||(n=c,l&&(n=Math.max(1,n),n=Math.min(12,n)))),n&&a&&null!=i&&(c=parseInt(a,10),Number.isNaN(c)||(s=c,l&&(u=he(i,n,0).getDate(),s=Math.max(1,s),s=Math.min(u,s)))),n&&s&&null!=i&&(e=he(i,n-1,s))),e},$=function(t){function e(t,e){return"0000".concat(t).slice(-e)}var n=1<arguments.length&&void 0!==arguments[1]?arguments[1]:de,r=t.getMonth()+1,a=t.getDate(),t=t.getFullYear();return n===le?[e(r,2),e(a,2),e(t,4)].join("/"):[e(t,4),e(r,2),e(a,2)].join("-")},Ce=function(n,r){for(var a=[],o=[],i=0;i<n.length;)!function(){o=[];for(var e=document.createElement("tr");i<n.length&&o.length<r;){var t=document.createElement("td");t.insertAdjacentElement("beforeend",n[i]),o.push(t),i+=1}o.forEach(function(t){e.insertAdjacentElement("beforeend",t)}),a.push(e)}();return a},Te=function(t){var e=document.createElement("tbody");return t.forEach(function(t){e.insertAdjacentElement("beforeend",t)}),e},ke=function(t){var e=1<arguments.length&&void 0!==arguments[1]?arguments[1]:"",e=(t.value=e,new CustomEvent("change",{bubbles:!0,cancelable:!0,detail:{value:e}}));t.dispatchEvent(e)},Q=function(t){t=t.closest(v);if(!t)throw new Error("Element is missing outer ".concat(v));var e=t.querySelector(Vt),n=t.querySelector(Wt),r=t.querySelector($t),a=t.querySelector(Kt),o=t.querySelector(Qt),i=t.querySelector(ee),c=E(n.value,le,!0),s=E(e.value),u=E(r.dataset.value),l=E(t.dataset.minDate),d=E(t.dataset.maxDate),f=E(t.dataset.rangeDate),p=E(t.dataset.defaultDate);if(l&&d&&d<l)throw new Error("Minimum date cannot be after maximum date");return{calendarDate:u,minDate:l,toggleBtnEl:a,selectedDate:s,maxDate:d,firstYearChunkEl:i,datePickerEl:t,inputDate:c,internalInputEl:e,externalInputEl:n,calendarEl:r,rangeDate:f,defaultDate:p,statusEl:o}},Me=function(t){var t=Q(t).externalInputEl,e=X(t);e&&!t.validationMessage&&t.setCustomValidity(ce),e||t.validationMessage!==ce||t.setCustomValidity("")},je=r(function(t){return I(t,-1)}),Oe=r(function(t){return I(t,1)}),qe=r(function(t){return ye(t,1)}),Ie=r(function(t){return z(t,1)}),Ne=r(function(t){return Ae(t)}),Be=r(function(t){return e=(t=t).getDay(),z(t,6-e);var e}),Pe=r(function(t){return K(t,1)}),He=r(function(t){return Ee(t,1)}),Fe=r(function(t){return N(t,1)}),Re=r(function(t){return B(t,1)}),Ye=a(function(t){return t-3}),Ue=a(function(t){return t+3}),ze=a(function(t){return t-1}),Ke=a(function(t){return t+1}),Ve=a(function(t){return t-t%3}),We=a(function(t){return t+2-t%3}),$e=a(function(){return 11}),Qe=a(function(){return 0}),Ge=o(function(t){return t-3}),Ze=o(function(t){return t+3}),Xe=o(function(t){return t-1}),Je=o(function(t){return t+1}),tn=o(function(t){return t-t%3}),en=o(function(t){return t+2-t%3}),nn=o(function(t){return t-L}),rn=o(function(t){return t+L}),fe=rt(fe),pe=rt(pe),be=rt(be),f=(n(e={},f,(n(f={},Kt,function(){var t,e,n,r,a,o;(t=this).disabled||(e=(a=Q(t)).calendarEl,o=a.inputDate,n=a.minDate,r=a.maxDate,a=a.defaultDate,e.hidden?(o=s(o||a||me(),n,r),c(e,o).querySelector(h).focus()):i(t))}),n(f,d,function(){var t,e,n;(t=this).disabled||(e=(n=Q(t)).datePickerEl,n=n.externalInputEl,J(t,t.dataset.value),i(e),n.focus())}),n(f,te,function(){var t,e,n,r,a;(t=this).disabled||(e=(r=Q(t)).calendarEl,a=r.calendarDate,n=r.minDate,r=r.maxDate,t=parseInt(t.dataset.value,10),a=A(a,t),a=s(a,n,r),c(e,a).querySelector(h).focus())}),n(f,ee,function(){var t,e,n,r,a;(t=this).disabled||(e=(r=Q(t)).calendarEl,a=r.calendarDate,n=r.minDate,r=r.maxDate,t=parseInt(t.innerHTML,10),a=D(a,t),a=s(a,n,r),c(e,a).querySelector(h).focus())}),n(f,Zt,function(){var t,e,n,r;(t=this).disabled||(e=(t=Q(t)).calendarEl,n=t.calendarDate,r=t.minDate,t=t.maxDate,n=Ee(n,1),n=s(n,r,t),(t=(t=(r=c(e,n)).querySelector(Zt)).disabled?r.querySelector(U):t).focus())}),n(f,Jt,function(){var t,e,n,r;(t=this).disabled||(e=(t=Q(t)).calendarEl,n=t.calendarDate,r=t.minDate,t=t.maxDate,n=K(n,1),n=s(n,r,t),(t=(t=(r=c(e,n)).querySelector(Jt)).disabled?r.querySelector(U):t).focus())}),n(f,Gt,function(){var t,e,n,r;(t=this).disabled||(e=(t=Q(t)).calendarEl,n=t.calendarDate,r=t.minDate,t=t.maxDate,n=B(n,1),n=s(n,r,t),(t=(t=(r=c(e,n)).querySelector(Gt)).disabled?r.querySelector(U):t).focus())}),n(f,Xt,function(){var t,e,n,r;(t=this).disabled||(e=(t=Q(t)).calendarEl,n=t.calendarDate,r=t.minDate,t=t.maxDate,n=N(n,1),n=s(n,r,t),(t=(t=(r=c(e,n)).querySelector(Xt)).disabled?r.querySelector(U):t).focus())}),n(f,ne,function(){var t,e,n,r,a;(t=this).disabled||(e=(t=Q(t)).calendarEl,r=t.calendarDate,a=t.minDate,t=t.maxDate,n=e.querySelector(y),n=parseInt(n.textContent,10)-L,n=Math.max(0,n),r=D(r,n),n=s(r,a,t),(a=(a=(r=u(e,n.getFullYear())).querySelector(ne)).disabled?r.querySelector(oe):a).focus())}),n(f,re,function(){var t,e,n,r,a;(t=this).disabled||(e=(t=Q(t)).calendarEl,r=t.calendarDate,a=t.minDate,t=t.maxDate,n=e.querySelector(y),n=parseInt(n.textContent,10)+L,n=Math.max(0,n),r=D(r,n),n=s(r,a,t),(a=(a=(r=u(e,n.getFullYear())).querySelector(re)).disabled?r.querySelector(oe):a).focus())}),n(f,g,function(){et(this).querySelector(ie).focus()}),n(f,m,function(){u(this).querySelector(y).focus()}),f)),n(e,"keyup",n({},$t,function(t){var e=this.dataset.keydownKeyCode;"".concat(t.keyCode)!==e&&t.preventDefault()})),n(e,"keydown",(n(g={},Wt,function(t){13===t.keyCode&&Me(this)}),n(g,d,l({Up:je,ArrowUp:je,Down:Oe,ArrowDown:Oe,Left:qe,ArrowLeft:qe,Right:Ie,ArrowRight:Ie,Home:Ne,End:Be,PageDown:Pe,PageUp:He,"Shift+PageDown":Fe,"Shift+PageUp":Re,Tab:fe.tabAhead})),n(g,U,l({Tab:fe.tabAhead,"Shift+Tab":fe.tabBack})),n(g,te,l({Up:Ye,ArrowUp:Ye,Down:Ue,ArrowDown:Ue,Left:ze,ArrowLeft:ze,Right:Ke,ArrowRight:Ke,Home:Ve,End:We,PageDown:$e,PageUp:Qe})),n(g,ae,l({Tab:pe.tabAhead,"Shift+Tab":pe.tabBack})),n(g,ee,l({Up:Ge,ArrowUp:Ge,Down:Ze,ArrowDown:Ze,Left:Xe,ArrowLeft:Xe,Right:Je,ArrowRight:Je,Home:tn,End:en,PageDown:rn,PageUp:nn})),n(g,oe,l({Tab:be.tabAhead,"Shift+Tab":be.tabBack})),n(g,$t,function(t){this.dataset.keydownKeyCode=t.keyCode}),n(g,v,function(t){l({Escape:nt})(t)}),g)),n(e,"focusout",(n(m={},Wt,function(){Me(this)}),n(m,v,function(t){this.contains(t.relatedTarget)||i(this)}),m)),n(e,"input",n({},Wt,function(){var t,e,n,r;e=Q(t=this),n=e.internalInputEl,e=e.inputDate,r="",e&&!X(t)&&(r=$(e)),n.value!==r&&ke(n,r),tt(this)})),e),je=(ct()||(f.mouseover=(n(d={},t,function(){var t,e,n;(t=this).disabled||(n=(e=t.closest($t)).dataset.value,(t=t.dataset.value)!==n&&(n=E(t),c(e,n).querySelector(h).focus()))}),n(d,te,function(){var t,e;(t=this).disabled||t.classList.contains(Mt)||(e=parseInt(t.dataset.value,10),et(t,e).querySelector(ie).focus())}),n(d,ee,function(){var t,e;(t=this).disabled||t.classList.contains(Ot)||(e=parseInt(t.dataset.value,10),u(t,e).querySelector(y).focus())}),d)),at(f,{init:function(t){ot(v,t).forEach(function(t){var e=(t=t.closest(v)).dataset.defaultValue,n=t.querySelector("input");if(!n)throw new Error("".concat(v," is missing inner input"));n.value&&(n.value="");var r=E(t.dataset.minDate||n.getAttribute("min"));t.dataset.minDate=r?$(r):"0000-01-01",(r=E(t.dataset.maxDate||n.getAttribute("max")))&&(t.dataset.maxDate=$(r));(r=document.createElement("div")).classList.add(st);var a=n.cloneNode();a.classList.add(ft),a.type="text",r.appendChild(a),r.insertAdjacentHTML("beforeend",F.escapeHTML(_=_||H(['\n    <button type="button" class="','" aria-haspopup="true" aria-label="Toggle calendar"></button>\n    <div class="','" role="dialog" aria-modal="true" hidden></div>\n    <div class="usa-sr-only ','" role="status" aria-live="polite"></div>']),pt,p,bt)),n.setAttribute("aria-hidden","true"),n.setAttribute("tabindex","-1"),n.style.display="none",n.classList.add(dt),n.removeAttribute("id"),n.removeAttribute("name"),n.required=!1,t.appendChild(r),t.classList.add(ut),e&&J(t,e),n.disabled&&(P(t),n.disabled=!1)})},getDatePickerContext:Q,disable:P,enable:function(t){var t=Q(t),e=t.externalInputEl;t.toggleBtnEl.disabled=!1,e.disabled=!1},isDateInputInvalid:X,setCalendarValue:J,validateDateInput:Me,renderCalendar:c,updateCalendarIfVisible:tt}));w.exports=je},{"../config":34,"../events":35,"../utils/active-element":42,"../utils/behavior":43,"../utils/is-ios-device":46,"../utils/sanitizer":47,"../utils/select":49,"receptor/keymap":12}],20:[function(t,e,n){"use strict";function r(t,e,n){e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n}function a(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var n=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=n){var r,a,o=[],i=!0,c=!1;try{for(n=n.call(t);!(i=(r=n.next()).done)&&(o.push(r.value),!e||o.length!==e);i=!0);}catch(t){c=!0,a=t}finally{try{i||null==n.return||n.return()}finally{if(c)throw a}}return o}}(t,e)||function(t,e){if(t){if("string"==typeof t)return o(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Map"===(n="Object"===n&&t.constructor?t.constructor.name:n)||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?o(t,e):void 0}}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function o(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}function i(t){if(!(t=t.closest(g)))throw new Error("Element is missing outer ".concat(g));var e=t.querySelector(y),n=t.querySelector(A);return{dateRangePickerEl:t,rangeStartEl:e,rangeEndEl:n}}function c(t){var e=(t=i(t)).dateRangePickerEl,n=t.rangeStartEl,t=t.rangeEndEl,r=(n=f(n).internalInputEl).value;r&&!p(n)?(t.dataset.minDate=r,t.dataset.rangeDate=r,t.dataset.defaultDate=r):(t.dataset.minDate=e.dataset.minDate||"",t.dataset.rangeDate="",t.dataset.defaultDate=""),b(t)}function s(t){var e=(t=i(t)).dateRangePickerEl,n=t.rangeStartEl,t=t.rangeEndEl,r=(t=f(t).internalInputEl).value;r&&!p(t)?(n.dataset.maxDate=r,n.dataset.rangeDate=r,n.dataset.defaultDate=r):(n.dataset.maxDate=e.dataset.maxDate||"",n.dataset.rangeDate="",n.dataset.defaultDate=""),b(n)}var u=t("../utils/behavior"),l=t("../utils/select"),d=t("../config").prefix,t=t("./date-picker"),f=t.getDatePickerContext,p=t.isDateInputInvalid,b=t.updateCalendarIfVisible,t="".concat(d,"-date-picker"),d="".concat(d,"-date-range-picker"),v="".concat(d,"__range-start"),h="".concat(d,"__range-end"),m=".".concat(t),g=".".concat(d),y=".".concat(v),A=".".concat(h),d=u({"input change":(r(t={},y,function(){c(this)}),r(t,A,function(){s(this)}),t)},{init:function(t){l(g,t).forEach(function(t){var t=(t=t).closest(g),e=(n=a(l(m,t),2))[0],n=n[1];if(!e)throw new Error("".concat(g," is missing inner two '").concat(m,"' elements"));if(!n)throw new Error("".concat(g," is missing second '").concat(m,"' element"));e.classList.add(v),n.classList.add(h),t.dataset.minDate||(t.dataset.minDate="0000-01-01");var r=t.dataset.minDate;e.dataset.minDate=r,n.dataset.minDate=r,(r=t.dataset.maxDate)&&(e.dataset.maxDate=r,n.dataset.maxDate=r),c(t),s(t)})}});e.exports=d},{"../config":34,"../utils/behavior":43,"../utils/select":49,"./date-picker":19}],21:[function(t,e,N){"use strict";var d,f,u,l;function p(t,e){return e=e||t.slice(0),Object.freeze(Object.defineProperties(t,{raw:{value:Object.freeze(e)}}))}function b(t){var e=(t=j(t)).dropZoneEl;t.inputEl.disabled=!0,e.classList.add(s),e.setAttribute("aria-disabled","true")}function n(t){var e=t.charCodeAt(0);return 32===e?"-":65<=e&&e<=90?"img_".concat(t.toLowerCase()):"__".concat(e.toString(16).slice(-4))}function g(t,r,a,o){for(var i=t.target.files,c=document.createElement("div"),t=r.dataset.defaultAriaLabel,s=[],e=(I(o,a,t),0);e<i.length;e+=1)!function(t){var e=new FileReader,n=i[t].name;s.push(n),0===t?r.setAttribute("aria-label","You have selected the file: ".concat(n)):1<=t&&r.setAttribute("aria-label","You have selected ".concat(i.length," files: ").concat(s.join(", "))),e.onloadstart=function(){var t=q(O(n));a.insertAdjacentHTML("afterend",h.escapeHTML(u=u||p(['<div class="','" aria-hidden="true">\n          <img id="','" src="','" alt="" class="'," ",'"/>',"\n        <div>"]),A,t,k,T,D,n))},e.onloadend=function(){var t=q(O(n)),t=document.getElementById(t);0<n.indexOf(".pdf")?t.setAttribute("onerror",'this.onerror=null;this.src="'.concat(k,'"; this.classList.add("').concat(R,'")')):0<n.indexOf(".doc")||0<n.indexOf(".pages")?t.setAttribute("onerror",'this.onerror=null;this.src="'.concat(k,'"; this.classList.add("').concat(Y,'")')):0<n.indexOf(".xls")||0<n.indexOf(".numbers")?t.setAttribute("onerror",'this.onerror=null;this.src="'.concat(k,'"; this.classList.add("').concat(z,'")')):0<n.indexOf(".mov")||0<n.indexOf(".mp4")?t.setAttribute("onerror",'this.onerror=null;this.src="'.concat(k,'"; this.classList.add("').concat(U,'")')):t.setAttribute("onerror",'this.onerror=null;this.src="'.concat(k,'"; this.classList.add("').concat(F,'")')),t.classList.remove(D),t.src=e.result},i[t]&&e.readAsDataURL(i[t]),0===t?(o.insertBefore(c,a),c.innerHTML='Selected file <span class="usa-file-input__choose">Change file</span>'):1<=t&&(o.insertBefore(c,a),c.innerHTML=h.escapeHTML(l=l||p(["",' files selected <span class="usa-file-input__choose">Change files</span>']),t+1)),c&&(a.classList.add(_),c.classList.add(E))}(e)}function v(t,e,n,r){var a,o,i=t,c=e,s=n,u=r,l=c.getAttribute("accept");if(u.classList.remove(C),l){for(var d=l.split(","),l=document.createElement("div"),f=!0,p=i.target.files||i.dataTransfer.files,b=0;b<p.length;b+=1){var v=p[b];if(!f)break;for(var h=0;h<d.length;h+=1){var m=d[h];if(f=0<v.name.indexOf(m)||(a=v.type,m=m.replace(/\*/g,""),void 0,o=!1,o=0<=a.indexOf(m)||o)){M=!0;break}}}f||(I(u,s),c.value="",u.insertBefore(l,c),l.textContent=c.dataset.errormessage||"This is not a valid file type.",l.classList.add(x),u.classList.add(C),M=!1,i.preventDefault(),i.stopPropagation())}!0===M&&g(t,e,n,r)}var r=t("../utils/select"),a=t("../utils/behavior"),o=t("../config").prefix,h=t("../utils/sanitizer"),m="".concat(o,"-file-input"),i=".".concat(m),y="".concat(o,"-file-input__input"),B="".concat(o,"-file-input__target"),c=".".concat(y),P="".concat(o,"-file-input__box"),H="".concat(o,"-file-input__instructions"),A="".concat(o,"-file-input__preview"),E="".concat(o,"-file-input__preview-heading"),s="".concat(o,"-file-input--disabled"),w="".concat(o,"-file-input__choose"),x="".concat(o,"-file-input__accepted-files-message"),S="".concat(o,"-file-input__drag-text"),L="".concat(o,"-file-input--drag"),D="is-loading",_="display-none",C="has-invalid-file",T="".concat(o,"-file-input__preview-image"),F="".concat(T,"--generic"),R="".concat(T,"--pdf"),Y="".concat(T,"--word"),U="".concat(T,"--video"),z="".concat(T,"--excel"),k="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7",M=Boolean(!0),j=function(t){t=t.closest(i);if(!t)throw new Error("Element is missing outer ".concat(i));var e=t.querySelector(c);return{dropZoneEl:t,inputEl:e}},O=function(t){return t.replace(/[^a-z0-9]/g,n)},q=function(t){return"".concat(t,"-").concat(Math.floor(Date.now().toString()/1e3))},I=function(t,e,n){var r=t.querySelectorAll(".".concat(A)),a=t.querySelector(c),o=t.querySelector(".".concat(E)),i=t.querySelector(".".concat(x));o&&(o.outerHTML=""),i&&(i.outerHTML="",t.classList.remove(C)),null!==r&&(e&&e.classList.remove(_),a.setAttribute("aria-label",n),Array.prototype.forEach.call(r,function(t){t.parentNode.removeChild(t)}))},t=a({},{init:function(t){r(i,t).forEach(function(e){r=(t=e).hasAttribute("multiple"),a=document.createElement("div"),o=document.createElement("div"),s=document.createElement("div"),i=document.createElement("div"),c=t.hasAttribute("disabled"),t.classList.remove(m),t.classList.add(y),a.classList.add(m),s.classList.add(P),i.classList.add(H),i.setAttribute("aria-hidden","true"),o.classList.add(B),t.setAttribute("aria-live","polite"),t.parentNode.insertBefore(o,t),t.parentNode.insertBefore(a,o),o.appendChild(t),a.appendChild(o),t.parentNode.insertBefore(i,t),t.parentNode.insertBefore(s,t),c&&b(t),r?(n="No files selected",i.innerHTML=h.escapeHTML(d=d||p(['<span class="','">Drag files here or </span><span class="','">choose from folder</span>']),S,w)):(n="No file selected",i.innerHTML=h.escapeHTML(f=f||p(['<span class="','">Drag file here or </span><span class="','">choose from folder</span>']),S,w)),t.setAttribute("aria-label",n),t.setAttribute("data-default-aria-label",n),(/rv:11.0/i.test(navigator.userAgent)||/Edge\/\d./i.test(navigator.userAgent))&&(a.querySelector(".".concat(S)).outerHTML="");var t,n,r,a,o,i,c,s={instructions:i,dropTarget:o},u=s.instructions,l=s.dropTarget;l.addEventListener("dragover",function(){this.classList.add(L)},!1),l.addEventListener("dragleave",function(){this.classList.remove(L)},!1),l.addEventListener("drop",function(){this.classList.remove(L)},!1),e.addEventListener("change",function(t){return v(t,e,u,l)},!1)})},getFileInputContext:j,disable:b,enable:function(t){var t=j(t),e=t.dropZoneEl;t.inputEl.disabled=!1,e.classList.remove(s),e.removeAttribute("aria-disabled")}});e.exports=t},{"../config":34,"../utils/behavior":43,"../utils/sanitizer":47,"../utils/select":49}],22:[function(t,e,n){"use strict";function r(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var a=t("../utils/behavior"),o=t("../events").CLICK,i=t("../config").prefix,c=".".concat(i,"-footer--big"),t="".concat(c," nav"),s="".concat(t," .").concat(i,"-footer__primary-link");function u(r){var a,t=document.querySelector(c);t&&(t=t.querySelectorAll(s),a=r?"button":"h4",t.forEach(function(t){var e=t.getAttribute("class"),n=document.createElement(a);n.setAttribute("class",e),n.classList.toggle("".concat(i,"-footer__primary-link--button"),r),n.textContent=t.textContent,r&&(e="".concat(i,"-footer-menu-list-").concat(Math.floor(1e5*Math.random())),n.setAttribute("aria-controls",e),n.setAttribute("aria-expanded","false"),t.nextElementSibling.setAttribute("id",e),n.setAttribute("type","button")),t.after(n),t.remove()}))}function l(t){u(t.matches)}e.exports=a(r({},o,r({},s,function(){var t;window.innerWidth<480&&(t="true"===this.getAttribute("aria-expanded"),this.closest(c).querySelectorAll(s).forEach(function(t){t.setAttribute("aria-expanded",!1)}),this.setAttribute("aria-expanded",!t))})),{HIDE_MAX_WIDTH:480,init:function(){u(window.innerWidth<480),this.mediaQueryList=window.matchMedia("(max-width: ".concat(479.9,"px)")),this.mediaQueryList.addListener(l)},teardown:function(){this.mediaQueryList.removeListener(l)}})},{"../config":34,"../events":35,"../utils/behavior":43}],23:[function(t,e,n){"use strict";var r=t("./accordion"),a=t("./banner"),o=t("./character-count"),i=t("./combo-box"),c=t("./file-input"),s=t("./footer"),u=t("./input-prefix-suffix"),l=t("./modal"),d=t("./navigation"),f=t("./password"),p=t("./search"),b=t("./skipnav"),v=t("./tooltip"),h=t("./validator"),m=t("./date-picker"),g=t("./date-range-picker"),y=t("./time-picker"),t=t("./table");e.exports={accordion:r,banner:a,characterCount:o,comboBox:i,datePicker:m,dateRangePicker:g,fileInput:c,footer:s,inputPrefixSuffix:u,modal:l,navigation:d,password:f,search:p,skipnav:b,table:t,timePicker:y,tooltip:v,validator:h}},{"./accordion":15,"./banner":16,"./character-count":17,"./combo-box":18,"./date-picker":19,"./date-range-picker":20,"./file-input":21,"./footer":22,"./input-prefix-suffix":24,"./modal":25,"./navigation":26,"./password":27,"./search":28,"./skipnav":29,"./table":30,"./time-picker":31,"./tooltip":32,"./validator":33}],24:[function(t,e,n){"use strict";function r(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var a=t("../utils/behavior"),o=t("../utils/select"),i=t("../config").prefix,t=t("../events").CLICK,c=".".concat(i,"-input-group"),s="".concat(c," .").concat(i,"-input"),u="".concat(c," .").concat(i,"-input-prefix, ").concat(c," .").concat(i,"-input-suffix"),l="is-focused";function d(){this.closest(c).classList.add(l)}function f(){this.closest(c).classList.remove(l)}a=a(r({},t,r({},u,function(){this.closest(c).querySelector(".".concat(i,"-input")).focus()})),{init:function(t){o(s,t).forEach(function(t){t.addEventListener("focus",d,!1),t.addEventListener("blur",f,!1)})}});e.exports=a},{"../config":34,"../events":35,"../utils/behavior":43,"../utils/select":49}],25:[function(t,e,n){"use strict";function r(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function l(){T.toggleModal.call(T,!1)}var a=t("../utils/behavior"),s=t("../utils/select"),d=t("../utils/focus-trap"),o=t("../utils/scrollbar-width"),i=t("../events").CLICK,t=t("../config").prefix,f="".concat(t,"-modal"),u="".concat(f,"-overlay"),p="".concat(f,"-wrapper"),b="data-open-modal",v="data-close-modal",h="data-force-action",m="data-modal-hidden",c=".".concat(f),g=".".concat(p," *[data-focus]"),y="".concat(p," *[").concat(v,"]"),A="*[".concat(b,"][aria-controls]"),E="".concat(y,", .").concat(u,":not([").concat(h,"])"),w="body > *:not(.".concat(p,"):not([aria-hidden])"),x="[".concat(m,"]"),S="usa-js-modal--active",L="is-hidden",t=o(),D=window.getComputedStyle(document.body).getPropertyValue("padding-right"),_="".concat(parseInt(D.replace(/px/,""),10)+parseInt(t.replace(/px/,""),10),"px");function C(t){var e,n=t.target,r=document.body,a=!document.body.classList.contains(S),o=n?n.getAttribute("aria-controls"):document.querySelector(".usa-modal-wrapper.is-visible"),o=a?document.getElementById(o):document.querySelector(".usa-modal-wrapper.is-visible");if(!o)return!1;var i=o.querySelector(g)?o.querySelector(g):o.querySelector(".usa-modal"),c=document.getElementById(o.getAttribute("data-opener")),s=r.querySelector(A),u=o.getAttribute(h);return(n="keydown"===t.type&&null!==o?o.querySelector(y):n)&&(n.hasAttribute(b)&&(null===this.getAttribute("id")?(e="modal-".concat(Math.floor(9e5*Math.random())+1e5),this.setAttribute("id",e)):e=this.getAttribute("id"),o.setAttribute("data-opener",e)),n.closest(".".concat(f))&&!n.hasAttribute(v)&&!n.closest("[".concat(v,"]")))?(t.stopPropagation(),!1):(r.classList.toggle(S,a),o.classList.toggle("is-visible",a),o.classList.toggle(L,!a),u&&r.classList.toggle("usa-js-no-click",a),r.style.paddingRight=r.style.paddingRight===_?D:_,a&&i?(T.focusTrap=u?d(o):d(o,{Escape:l}),T.focusTrap.update(a),i.focus(),document.querySelectorAll(w).forEach(function(t){t.setAttribute("aria-hidden","true"),t.setAttribute(m,"")})):!a&&s&&c&&(document.querySelectorAll(x).forEach(function(t){t.removeAttribute("aria-hidden"),t.removeAttribute(m)}),c.focus(),T.focusTrap.update(a)),a)}var T=a(r({},i,(r(o={},A,C),r(o,E,C),o)),{init:function(t){s(c,t).forEach(function(t){var e,n,r,a,o,i,c;e=t=t,n=document.createElement("div"),r=document.createElement("div"),a=t.getAttribute("id"),o=t.getAttribute("aria-labelledby"),i=t.getAttribute("aria-describedby"),c=!!t.hasAttribute(h)&&t.hasAttribute(h),e.parentNode.insertBefore(n,e),n.appendChild(e),e.parentNode.insertBefore(r,e),r.appendChild(e),n.classList.add(L),n.classList.add(p),r.classList.add(u),n.setAttribute("role","dialog"),n.setAttribute("id",a),o&&n.setAttribute("aria-labelledby",o),i&&n.setAttribute("aria-describedby",i),c&&n.setAttribute(h,"true"),t.removeAttribute("id"),t.removeAttribute("aria-labelledby"),t.removeAttribute("aria-describedby"),t.setAttribute("tabindex","-1"),e=n.querySelectorAll(E),s(e).forEach(function(t){t.setAttribute("aria-controls",a)}),document.body.appendChild(n)}),s(A,t).forEach(function(t){"A"===t.nodeName&&(t.setAttribute("role","button"),t.addEventListener("click",function(t){t.preventDefault()}))})},focusTrap:null,toggleModal:C});e.exports=T},{"../config":34,"../events":35,"../utils/behavior":43,"../utils/focus-trap":44,"../utils/scrollbar-width":48,"../utils/select":49}],26:[function(t,e,N){"use strict";function n(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function a(){return document.body.classList.contains(M)}function r(t){var e=document.body,n="boolean"==typeof t?t:!a(),t=(e.classList.toggle(M,n),f(C).forEach(function(t){return t.classList.toggle("is-visible",n)}),I.focusTrap.update(n),e.querySelector(D)),r=document.querySelector(L);return e.style.paddingRight=e.style.paddingRight===O?j:O,(n?q:B)(),n&&t?t.focus():!n&&document.activeElement===t&&r&&r.focus(),n}function o(){var t=document.body.querySelector(D);a()&&t&&0===t.getBoundingClientRect().width&&I.toggleNav.call(t,!1)}function i(){return I.toggleNav.call(I,!1)}function c(){s&&(p(s,!1),s=null)}var s,u,l=t("receptor/keymap"),d=t("../utils/behavior"),f=t("../utils/select"),p=t("../utils/toggle"),b=t("../utils/focus-trap"),v=t("./accordion"),h=t("../utils/scrollbar-width"),m=t("../events").CLICK,t=t("../config").prefix,g=".".concat(t,"-header"),y=".".concat(t,"-nav"),A=".".concat(t,"-nav__primary"),E=".".concat(t,"-nav__primary-item"),w="button.".concat(t,"-nav__link"),x="".concat(y," a"),S="data-nav-hidden",L=".".concat(t,"-menu-btn"),D=".".concat(t,"-nav__close"),_=".".concat(t,"-overlay"),t="".concat(D,", .").concat(t,"-overlay"),C=[y,_].join(", "),T="body > *:not(".concat(g,"):not([aria-hidden])"),k="[".concat(S,"]"),M="usa-js-mobile-nav--active",_=h(),j=window.getComputedStyle(document.body).getPropertyValue("padding-right"),O="".concat(parseInt(j.replace(/px/,""),10)+parseInt(_.replace(/px/,""),10),"px"),q=function(){(u=document.querySelectorAll(T)).forEach(function(t){t.setAttribute("aria-hidden",!0),t.setAttribute(S,"")})},B=function(){(u=document.querySelectorAll(k))&&u.forEach(function(t){t.removeAttribute("aria-hidden"),t.removeAttribute(S)})},I=d((n(g={},m,(n(h={},w,function(){return s!==this&&c(),s||p(s=this,!0),!1}),n(h,"body",c),n(h,L,r),n(h,t,r),n(h,x,function(){var t=this.closest(v.ACCORDION);t&&v.getButtons(t).forEach(function(t){return v.hide(t)}),a()&&I.toggleNav.call(I,!1)}),h)),n(g,"keydown",n({},A,l({Escape:function(t){var e;c(),e=(t=t).target.closest(E),t.target.matches(w)||e.querySelector(w).focus()}}))),n(g,"focusout",n({},A,function(t){t.target.closest(A).contains(t.relatedTarget)||c()})),g),{init:function(t){t=t.querySelector(y);t&&(I.focusTrap=b(t,{Escape:i})),o(),window.addEventListener("resize",o,!1)},teardown:function(){window.removeEventListener("resize",o,!1),s=!1},focusTrap:null,toggleNav:r});e.exports=I},{"../config":34,"../events":35,"../utils/behavior":43,"../utils/focus-trap":44,"../utils/scrollbar-width":48,"../utils/select":49,"../utils/toggle":52,"./accordion":15,"receptor/keymap":12}],27:[function(t,e,n){"use strict";function r(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var a=t("../utils/behavior"),o=t("../utils/toggle-form-input"),i=t("../events").CLICK,t=t("../config").prefix,t=".".concat(t,"-show-password, .").concat(t,"-show-multipassword");e.exports=a(r({},i,r({},t,function(t){t.preventDefault(),o(this)})))},{"../config":34,"../events":35,"../utils/behavior":43,"../utils/toggle-form-input":51}],28:[function(t,e,n){"use strict";function r(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function a(t,e){var n,r=b(t);if(!r)throw new Error("No ".concat(d," found for search toggle in ").concat(p,"!"));t.hidden=e,r.hidden=!e,e&&((t=r.querySelector(f))&&t.focus(),n=i(r,function(){o&&!function(){a(this,!1),o=void 0}.call(o),document.body.removeEventListener(u,n)}),setTimeout(function(){document.body.addEventListener(u,n)},0))}var o,i=t("receptor/ignore"),c=t("../utils/behavior"),s=t("../utils/select"),u=t("../events").CLICK,l=".js-search-button",d=".js-search-form",f="[type=search]",p="header",b=function(t){t=t.closest(p);return(t||document).querySelector(d)};t=c(r({},u,r({},l,function(){a(this,!0),o=this})),{init:function(t){s(l,t).forEach(function(t){a(t,!1)})},teardown:function(){o=void 0}});e.exports=t},{"../events":35,"../utils/behavior":43,"../utils/select":49,"receptor/ignore":10}],29:[function(t,e,n){"use strict";function r(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var a=t("receptor/once"),o=t("../utils/behavior"),i=t("../events").CLICK,t=t("../config").prefix,t=".".concat(t,'-skipnav[href^="#"], .').concat(t,'-footer__return-to-top [href^="#"]');e.exports=o(r({},i,r({},t,function(){var t=encodeURI(this.getAttribute("href")),e=document.getElementById("#"===t?"main-content":t.slice(1));e&&(e.style.outline="0",e.setAttribute("tabindex",0),e.focus(),e.addEventListener("blur",a(function(){e.setAttribute("tabindex",-1)})))})))},{"../config":34,"../events":35,"../utils/behavior":43,"receptor/once":13}],30:[function(t,e,n){"use strict";var a;function r(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function o(t,e){return t.children[e].getAttribute("data-sort-value")||t.children[e].innerText||t.children[e].textContent}function i(e,t){var n,r,a,o=e.closest(p);if("boolean"!=typeof t&&e.getAttribute(b),!o)throw new Error("".concat(y," is missing outer ").concat(p));if(t=t,(i=e).setAttribute(b,!0===t?h:v),w(i),n=i.closest(p).querySelector("tbody"),c=[].slice.call(n.querySelectorAll("tr")),r=[].slice.call(i.parentNode.children).indexOf(i),c.sort(E(r,!t)).forEach(function(t){[].slice.call(t.children).forEach(function(t){return t.removeAttribute("data-sort-active")}),t.children[r].setAttribute("data-sort-active",!0),n.appendChild(t)}),!0){s(y,a=o).filter(function(t){return t.closest(p)===a}).forEach(function(t){t!==e&&((t=t).removeAttribute(b),w(t))});var i=o,c=e,t=i.querySelector("caption").innerText,o=c.getAttribute(b)===v,c=c.innerText;if(!(i=i.nextElementSibling)||!i.matches(A))throw new Error("Table containing a sortable column header is not followed by an aria-live region.");t='The table named "'.concat(t,'" is now sorted by ').concat(c," in ").concat(o?v:h," order."),i.innerText=t}}function c(t){var e,n,r=document.createElement("button");r.setAttribute("tabindex","0"),r.classList.add(m),r.innerHTML=f.escapeHTML(a||(e=['\n  <svg class="','-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">\n    <g class="descending" fill="transparent">\n      <path d="M17 17L15.59 15.59L12.9999 18.17V2H10.9999V18.17L8.41 15.58L7 17L11.9999 22L17 17Z" />\n    </g>\n    <g class="ascending" fill="transparent">\n      <path transform="rotate(180, 12, 12)" d="M17 17L15.59 15.59L12.9999 18.17V2H10.9999V18.17L8.41 15.58L7 17L11.9999 22L17 17Z" />\n    </g>\n    <g class="unsorted" fill="transparent">\n      <polygon points="15.17 15 13 17.17 13 6.83 15.17 9 16.58 7.59 12 3 7.41 7.59 8.83 9 11 6.83 11 17.17 8.83 15 7.42 16.41 12 21 16.59 16.41 15.17 15"/>\n    </g>\n  </svg>\n  '],n=n||e.slice(0),a=Object.freeze(Object.defineProperties(e,{raw:{value:Object.freeze(n)}}))),d),t.appendChild(r),w(t)}var s=t("../utils/select"),u=t("../utils/behavior"),l=t("../events").CLICK,d=t("../config").prefix,f=t("../utils/sanitizer"),p=".".concat(d,"-table"),b="aria-sort",v="ascending",h="descending",m="".concat(d,"-table__header__button"),g=".".concat(m),y="th[data-sortable]",A=".".concat(d,'-table__announcement-region[aria-live="polite"]'),E=function(r,a){return function(t,e){var n=o(a?t:e,r),e=o(a?e:t,r);return n&&e&&!Number.isNaN(Number(n))&&!Number.isNaN(Number(e))?n-e:n.toString().localeCompare(e,navigator.language,{numeric:!0,ignorePunctuation:!0})}},w=function(t){var e=t.innerText,n=t.getAttribute(b)===v,r=t.getAttribute(b)===v||t.getAttribute(b)===h||!1,r="".concat(e,"', sortable column, currently ").concat(r?"".concat("sorted ".concat(n?v:h)):"unsorted"),e="Click to sort by ".concat(e," in ").concat(n?h:v," order.");t.setAttribute("aria-label",r),t.querySelector(g).setAttribute("title",e)},t=u(r({},l,r({},g,function(t){t.preventDefault(),i(t.target.closest(y),t.target.closest(y).getAttribute(b)===v)})),{init:function(t){var e,t=s(y,t),t=(t.forEach(c),t.filter(function(t){return t.getAttribute(b)===v||t.getAttribute(b)===h})[0]);void 0!==t&&((e=t.getAttribute(b))===v?i(t,!0):e===h&&i(t,!1))},TABLE:p,SORTABLE_HEADER:y,SORT_BUTTON:g});e.exports=t},{"../config":34,"../events":35,"../utils/behavior":43,"../utils/sanitizer":47,"../utils/select":49}],31:[function(t,e,n){"use strict";function r(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var n=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=n){var r,a,o=[],i=!0,c=!1;try{for(n=n.call(t);!(i=(r=n.next()).done)&&(o.push(r.value),!e||o.length!==e);i=!0);}catch(t){c=!0,a=t}finally{try{i||null==n.return||n.return()}finally{if(c)throw a}}return o}}(t,e)||function(t,e){if(t){if("string"==typeof t)return a(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Map"===(n="Object"===n&&t.constructor?t.constructor.name:n)||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?a(t,e):void 0}}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function a(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}function b(t){var e,n;return t&&(n=(t=r(t.split(":").map(function(t){var e,t=parseInt(t,10);return e=Number.isNaN(t)?e:t}),2))[0],t=t[1],null!=n&&null!=t&&(e=60*n+t)),e}function o(t){var e=t.closest(h),n=e.querySelector("input");if(!n)throw new Error("".concat(h," is missing inner input"));for(var r,a=document.createElement("select"),o=(["id","name","required","aria-label","aria-labelledby"].forEach(function(t){var e;n.hasAttribute(t)&&(e=n.getAttribute(t),a.setAttribute(t,e),n.removeAttribute(t))}),function(t,e){return"0000".concat(t).slice(-e)}),t=Math.max(0,b(e.dataset.minTime)||0),i=Math.min(1439,b(e.dataset.maxTime)||1439),c=Math.floor(Math.max(1,e.dataset.step||30)),s=t;s<=i;s+=c){u=void 0,u=(l=s)%60,l=Math.floor(l/60);var u={minute:u,hour24:l,hour12:l%12||12,ampm:l<12?"am":"pm"},l=u.minute,d=u.hour24,f=u.hour12,u=u.ampm,p=document.createElement("option");p.value="".concat(o(d,2),":").concat(o(l,2)),p.text="".concat(f,":").concat(o(l,2)).concat(u),p.text===n.value&&(r=p.value),a.appendChild(p)}e.classList.add(v),Object.keys(m).forEach(function(t){e.dataset[t]=m[t]}),e.dataset.disableFiltering="true",e.dataset.defaultValue=r,e.appendChild(a),n.style.display="none"}var i=t("../utils/behavior"),c=t("../utils/select"),s=t("../config").prefix,t=t("./combo-box"),v=t.COMBO_BOX_CLASS,u=t.enhanceComboBox,t="".concat(s,"-time-picker"),h=".".concat(t),m={filter:"0?{{ hourQueryFilter }}:{{minuteQueryFilter}}.*{{ apQueryFilter }}m?",apQueryFilter:"([ap])",hourQueryFilter:"([1-9][0-2]?)",minuteQueryFilter:"[\\d]+:([0-9]{0,2})"},s=i({},{init:function(t){c(h,t).forEach(function(t){o(t),u(t)})},FILTER_DATASET:m});e.exports=s},{"../config":34,"../utils/behavior":43,"../utils/select":49,"./combo-box":18}],32:[function(t,e,n){"use strict";function l(t,e,n){for(var r=e.split(" "),a=0,o=r.length;a<o;a+=1)t.addEventListener(r[a],n,!1)}function d(e,r,t){function o(t){u(t);var e=d("top",t.offsetHeight,r),n=d("left",t.offsetWidth,r);a("top"),t.style.left="50%",t.style.top="-".concat(5,"px"),t.style.margin="-".concat(e,"px 0 0 -").concat(n/2,"px")}function i(t){u(t);var e=d("left",t.offsetWidth,r);a("bottom"),t.style.left="50%",t.style.margin="".concat(5,"px 0 0 -").concat(e/2,"px")}function c(t){u(t);var e=d("top",t.offsetHeight,r);a("right"),t.style.top="50%",t.style.left="".concat(r.offsetLeft+r.offsetWidth+5,"px"),t.style.margin="-".concat(e/2,"px 0 0 0")}function s(t){u(t);var e=d("top",t.offsetHeight,r),n=d("left",r.offsetLeft>t.offsetWidth?r.offsetLeft-t.offsetWidth:t.offsetWidth,r);a("left"),t.style.top="50%",t.style.left="-".concat(5,"px"),t.style.margin="-".concat(e/2,"px 0 0 ").concat(r.offsetLeft>t.offsetWidth?n:-n,"px")}e.setAttribute("aria-hidden","false"),e.classList.add("is-set");var a=function(t){e.classList.remove("".concat(v,"--top")),e.classList.remove("".concat(v,"--bottom")),e.classList.remove("".concat(v,"--right")),e.classList.remove("".concat(v,"--left")),e.classList.add("".concat(v,"--").concat(t))},u=function(t){t.style.top=null,t.style.bottom=null,t.style.right=null,t.style.left=null,t.style.margin=null},l=function(t,e){return parseInt(window.getComputedStyle(t).getPropertyValue(e),10)},d=function(t,e,n){return 0<l(n,"margin-".concat(t))?e-l(n,"margin-".concat(t)):e};function f(n,t){var t=1<arguments.length&&void 0!==t?t:1,r=[o,i,c,s],a=!1;!function t(e){e<r.length&&((0,r[e])(n),p(n)?a=!0:t(e+=1))}(0),a||(n.classList.add(m),t<=2&&f(n,t+=1))}switch(t){case"top":o(e),p(e)||f(e);break;case"bottom":i(e),p(e)||f(e);break;case"right":c(e),p(e)||f(e);break;case"left":s(e),p(e)||f(e)}setTimeout(function(){e.classList.add(h)},20)}var r=t("../utils/select"),a=t("../utils/behavior"),o=t("../config").prefix,p=t("../utils/is-in-viewport"),i=".".concat(o,"-tooltip"),f="".concat(o,"-tooltip__trigger"),b="".concat(o,"-tooltip"),v="".concat(o,"-tooltip__body"),h="is-visible",m="".concat(o,"-tooltip__body--wrap"),t=a({},{init:function(t){r(i,t).forEach(function(t){c=t,e="tooltip-".concat(Math.floor(9e5*Math.random())+1e5),n=c.getAttribute("title"),r=document.createElement("span"),a=document.createElement("span"),o=c.getAttribute("data-position")?c.getAttribute("data-position"):"top",i=c.getAttribute("data-classes"),c.setAttribute("aria-describedby",e),c.setAttribute("tabindex","0"),c.setAttribute("title",""),c.classList.remove(b),c.classList.add(f),c.parentNode.insertBefore(r,c),r.appendChild(c),r.classList.add(b),r.appendChild(a),i&&i.split(" ").forEach(function(t){return r.classList.add(t)}),a.classList.add(v),a.setAttribute("id",e),a.setAttribute("role","tooltip"),a.setAttribute("aria-hidden","true"),a.textContent=n;var e,n,r,a,o,i,c={tooltipBody:a,position:o,tooltipContent:n,wrapper:r},s=c.tooltipBody,u=c.position;c.tooltipContent&&(l(t,"mouseenter focus",function(){return d(s,t,u),!1}),l(t,"mouseleave blur keydown",function(){var t;return(t=s).classList.remove(h),t.classList.remove("is-set"),t.classList.remove(m),t.setAttribute("aria-hidden","true"),!1}))})}});e.exports=t},{"../config":34,"../utils/behavior":43,"../utils/is-in-viewport":45,"../utils/select":49}],33:[function(t,e,n){"use strict";var r=t("../utils/behavior"),a=t("../utils/validate-input");t=r({"keyup change":{"input[data-validation-element]":function(){a(this)}}});e.exports=t},{"../utils/behavior":43,"../utils/validate-input":53}],34:[function(t,e,n){"use strict";e.exports={prefix:"usa"}},{}],35:[function(t,e,n){"use strict";e.exports={CLICK:"click"}},{}],36:[function(t,e,n){"use strict";"function"!=typeof window.CustomEvent&&(window.CustomEvent=function(t,e){var e=e||{bubbles:!1,cancelable:!1,detail:null},n=document.createEvent("CustomEvent");return n.initCustomEvent(t,e.bubbles,e.cancelable,e.detail),n})},{}],37:[function(t,e,n){"use strict";var r=window.HTMLElement.prototype,a="hidden";a in r||Object.defineProperty(r,a,{get:function(){return this.hasAttribute(a)},set:function(t){t?this.setAttribute(a,""):this.removeAttribute(a)}})},{}],38:[function(t,e,n){"use strict";t("classlist-polyfill"),t("./element-hidden"),t("./number-is-nan"),t("./custom-event"),t("./svg4everybody")},{"./custom-event":36,"./element-hidden":37,"./number-is-nan":39,"./svg4everybody":40,"classlist-polyfill":1}],39:[function(t,e,n){"use strict";Number.isNaN=Number.isNaN||function(t){return"number"==typeof t&&t!=t}},{}],40:[function(t,e,n){"use strict";function b(t,e,n,r){if(n){var a=document.createDocumentFragment(),o=!e.hasAttribute("viewBox")&&n.getAttribute("viewBox");o&&e.setAttribute("viewBox",o);for(var i=document.importNode?document.importNode(n,!0):n.cloneNode(!0),c=document.createElementNS(e.namespaceURI||"http://www.w3.org/2000/svg","g");i.childNodes.length;)c.appendChild(i.firstChild);if(r)for(var s=0;r.attributes.length>s;s++){var u=r.attributes[s];"xlink:href"!==u.name&&"href"!==u.name&&c.setAttribute(u.name,u.value)}a.appendChild(c),t.appendChild(a)}}e.exports=function(t){var s,u=Object(t),t=window.top!==window.self,l=(s="polyfill"in u?u.polyfill:/\bTrident\/[567]\b|\bMSIE (?:9|10)\.0\b/.test(navigator.userAgent)||(navigator.userAgent.match(/\bEdge\/12\.(\d+)\b/)||[])[1]<10547||(navigator.userAgent.match(/\bAppleWebKit\/(\d+)\b/)||[])[1]<537||/\bEdge\/.(\d+)\b/.test(navigator.userAgent)&&t,{}),d=window.requestAnimationFrame||setTimeout,f=document.getElementsByTagName("use"),p=0;s&&function t(){if(p&&f.length-p<=0)d(t,67);else{for(var e=p=0;e<f.length;){var n,r,a=f[e],o=a.parentNode,i=function(t){for(var e=t;"svg"!==e.nodeName.toLowerCase()&&(e=e.parentNode););return e}(o),c=a.getAttribute("xlink:href")||a.getAttribute("href");!c&&u.attributeName&&(c=a.getAttribute(u.attributeName)),i&&c?s&&(!u.validate||u.validate(c,i,a)?(o.removeChild(a),n=(c=c.split("#")).shift(),c=c.join("#"),n.length?((r=l[n])||((r=l[n]=new XMLHttpRequest).open("GET",n),r.send(),r._embeds=[]),r._embeds.push({parent:o,svg:i,id:c}),function(r,a){r.onreadystatechange=function(){var n;4===r.readyState&&((n=r._cachedDocument)||((n=r._cachedDocument=document.implementation.createHTMLDocument("")).body.innerHTML=r.responseText,n.domain!==document.domain&&(n.domain=document.domain),r._cachedTarget={}),r._embeds.splice(0).map(function(t){var e=(e=r._cachedTarget[t.id])||(r._cachedTarget[t.id]=n.getElementById(t.id));b(t.parent,t.svg,e,a)}))},r.onreadystatechange()}(r,a)):b(o,i,document.getElementById(c),a)):(++e,++p)):++e}d(t,67)}}()}},{}],41:[function(t,e,n){"use strict";var r=t("domready"),a=(window.uswdsPresent=!0,t("./polyfills"),t("./config")),o=t("./components"),i=t("./polyfills/svg4everybody");a.components=o,r(function(){var e=document.body;Object.keys(o).forEach(function(t){o[t].on(e)}),i()}),e.exports=a},{"./components":23,"./config":34,"./polyfills":38,"./polyfills/svg4everybody":40,domready:2}],42:[function(t,e,n){"use strict";e.exports=function(){return(0<arguments.length&&void 0!==arguments[0]?arguments[0]:document).activeElement}},{}],43:[function(t,e,n){"use strict";function r(){for(var t=arguments.length,r=new Array(t),e=0;e<t;e++)r[e]=arguments[e];return function(){var e=this,n=0<arguments.length&&void 0!==arguments[0]?arguments[0]:document.body;r.forEach(function(t){"function"==typeof e[t]&&e[t].call(e,n)})}}var a=t("object-assign"),o=t("receptor/behavior");e.exports=function(t,e){return o(t,a({on:r("init","add"),off:r("teardown","remove")},e))}},{"object-assign":5,"receptor/behavior":6}],44:[function(t,e,n){"use strict";function o(t){var e=a('a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex="0"], [contenteditable]',t),n=e[0],r=e[e.length-1];return{firstTabStop:n,lastTabStop:r,tabAhead:function(t){u()===r&&(t.preventDefault(),n.focus())},tabBack:function(t){u()===n?(t.preventDefault(),r.focus()):e.includes(u())||(t.preventDefault(),n.focus())}}}var i=t("object-assign"),c=t("receptor").keymap,s=t("./behavior"),a=t("./select"),u=t("./active-element");e.exports=function(t){var e=1<arguments.length&&void 0!==arguments[1]?arguments[1]:{},n=o(t),t=e,r=t.Esc,a=t.Escape,r=(a&&!r&&(t.Esc=a),c(i({Tab:n.tabAhead,"Shift+Tab":n.tabBack},e)));return s({keydown:r},{init:function(){n.firstTabStop&&n.firstTabStop.focus()},update:function(t){t?this.on():this.off()}})}},{"./active-element":42,"./behavior":43,"./select":49,"object-assign":5,receptor:11}],45:[function(t,e,n){"use strict";e.exports=function(t){var e=1<arguments.length&&void 0!==arguments[1]?arguments[1]:window,n=2<arguments.length&&void 0!==arguments[2]?arguments[2]:document.documentElement,t=t.getBoundingClientRect();return 0<=t.top&&0<=t.left&&t.bottom<=(e.innerHeight||n.clientHeight)&&t.right<=(e.innerWidth||n.clientWidth)}},{}],46:[function(t,e,n){"use strict";e.exports=function(){return"undefined"!=typeof navigator&&(navigator.userAgent.match(/(iPod|iPhone|iPad)/g)||"MacIntel"===navigator.platform&&1<navigator.maxTouchPoints)&&!window.MSStream}},{}],47:[function(t,e,n){"use strict";e.exports=function(){"use strict";var i={_entity:/[&<>"'/]/g,_entities:{"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&apos;","/":"&#x2F;"},getEntity:function t(e){return i._entities[e]},escapeHTML:function t(e){var n="";for(var r=0;r<e.length;r++){n+=e[r];if(r+1<arguments.length){var a=arguments[r+1]||"";n+=String(a).replace(i._entity,i.getEntity)}}return n},createSafeHTML:function t(e){var n=arguments.length;var r=new Array(n>1?n-1:0);for(var a=1;a<n;a++)r[a-1]=arguments[a];var o=i.escapeHTML.apply(i,[e].concat(r));return{__html:o,toString:function t(){return"[object WrappedHTMLObject]"},info:"This is a wrapped HTML object. See https://developer.mozilla.or"+"g/en-US/Firefox_OS/Security/Security_Automation for more."}},unwrapSafeHTML:function t(){var e=arguments.length;var n=new Array(e);for(var r=0;r<e;r++)n[r]=arguments[r];var a=n.map(function(t){return t.__html});return a.join("")}};return i}()},{}],48:[function(t,e,n){"use strict";e.exports=function(){var t=document.createElement("div"),e=(t.style.visibility="hidden",t.style.overflow="scroll",t.style.msOverflowStyle="scrollbar",document.body.appendChild(t),document.createElement("div")),e=(t.appendChild(e),"".concat(t.offsetWidth-e.offsetWidth,"px"));return t.parentNode.removeChild(t),e}},{}],49:[function(t,e,n){"use strict";function r(t){return(r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}e.exports=function(t,e){if("string"!=typeof t)return[];var n=(e=e&&((n=e)&&"object"===r(n)&&1===n.nodeType)?e:window.document).querySelectorAll(t);return Array.prototype.slice.call(n)}},{}],50:[function(t,e,n){"use strict";e.exports=function(t,e){t.setAttribute("autocapitalize","off"),t.setAttribute("autocorrect","off"),t.setAttribute("type",e?"password":"text")}},{}],51:[function(t,e,n){"use strict";var a=t("resolve-id-refs"),o=t("./toggle-field-mask"),i="aria-pressed",c="data-show-text";e.exports=function(t){var e=t.hasAttribute(i)&&"true"!==t.getAttribute(i),n=(a(t.getAttribute("aria-controls")).forEach(function(t){return o(t,e)}),t.hasAttribute(c)||t.setAttribute(c,t.textContent),t.getAttribute(c)),r=t.getAttribute("data-hide-text")||n.replace(/\bShow\b/i,function(t){return"".concat("S"===t[0]?"H":"h","ide")});return t.textContent=e?n:r,t.setAttribute(i,e),e}},{"./toggle-field-mask":50,"resolve-id-refs":14}],52:[function(t,e,n){"use strict";var r="aria-expanded";e.exports=function(t,e){"boolean"!=typeof e&&(e="false"===t.getAttribute(r)),t.setAttribute(r,e);var t=t.getAttribute("aria-controls"),n=document.getElementById(t);if(n)return e?n.removeAttribute("hidden"):n.setAttribute("hidden",""),e;throw new Error('No toggle target found with id: "'.concat(t,'"'))}},{}],53:[function(t,e,n){"use strict";function o(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var n=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=n){var r,a,o=[],i=!0,c=!1;try{for(n=n.call(t);!(i=(r=n.next()).done)&&(o.push(r.value),!e||o.length!==e);i=!0);}catch(t){c=!0,a=t}finally{try{i||null==n.return||n.return()}finally{if(c)throw a}}return o}}(t,e)||function(t,e){if(t){if("string"==typeof t)return r(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Map"===(n="Object"===n&&t.constructor?t.constructor.name:n)||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?r(t,e):void 0}}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function r(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,r=new Array(e);n<e;n++)r[n]=t[n];return r}var t=t("../config").prefix,i="".concat(t,"-checklist__item--checked");e.exports=function(r){var t=r.dataset.validationElement,a="#"===t.charAt(0)?document.querySelector(t):document.getElementById(t);if(!a)throw new Error('No validation element found with id: "'.concat(t,'"'));Object.entries(r.dataset).forEach(function(t){var t=o(t,2),e=t[0],t=t[1];if(e.startsWith("validate")){var e=e.substr("validate".length).toLowerCase(),t=new RegExp(t),n='[data-validator="'.concat(e,'"]'),n=a.querySelector(n);if(!n)throw new Error('No validator checkbox found for: "'.concat(e,'"'));e=t.test(r.value);n.classList.toggle(i,e),n.setAttribute("aria-checked",e)}})}},{"../config":34}]},{},[41]);

;
/*
 * Superfish v1.4.8 - jQuery menu widget
 * Copyright (c) 2008 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 *
 * CHANGELOG: http://users.tpg.com.au/j_birch/plugins/superfish/changelog.txt
 */
/*
 * This is not the original jQuery Superfish plugin.
 * Please refer to the README for more information.
 */

(function($){
  $.fn.superfish = function(op){
    var sf = $.fn.superfish,
      c = sf.c,
      $arrow = $(['<span class="',c.arrowClass,'"> &#187;</span>'].join('')),
      over = function(){
        var $$ = $(this), menu = getMenu($$);
        clearTimeout(menu.sfTimer);
        $$.showSuperfishUl().siblings().hideSuperfishUl();
      },
      out = function(){
        var $$ = $(this), menu = getMenu($$), o = sf.op;
        clearTimeout(menu.sfTimer);
        menu.sfTimer=setTimeout(function(){
          if ($$.children('.sf-clicked').length == 0){
            o.retainPath=($.inArray($$[0],o.$path)>-1);
            $$.hideSuperfishUl();
            if (o.$path.length && $$.parents(['li.',o.hoverClass].join('')).length<1){over.call(o.$path);}
          }
        },o.delay);
      },
      getMenu = function($menu){
        var menu = $menu.parents(['ul.',c.menuClass,':first'].join(''))[0];
        sf.op = sf.o[menu.serial];
        return menu;
      },
      addArrow = function($a){ $a.addClass(c.anchorClass).append($arrow.clone()); };

    return this.each(function() {
      var s = this.serial = sf.o.length;
      var o = $.extend({},sf.defaults,op);
      o.$path = $('li.'+o.pathClass,this).slice(0,o.pathLevels),
      p = o.$path;
      for (var l = 0; l < p.length; l++){
        p.eq(l).addClass([o.hoverClass,c.bcClass].join(' ')).filter('li:has(ul)').removeClass(o.pathClass);
      }
      sf.o[s] = sf.op = o;

      $('li:has(ul)',this)[($.fn.hoverIntent && !o.disableHI) ? 'hoverIntent' : 'hover'](over,out).each(function() {
        if (o.autoArrows) addArrow( $(this).children('a:first-child, span.nolink:first-child') );
      })
      .not('.'+c.bcClass)
        .hideSuperfishUl();

      var $a = $('a, span.nolink',this);
      $a.each(function(i){
        var $li = $a.eq(i).parents('li');
        $a.eq(i).focus(function(){over.call($li);}).blur(function(){out.call($li);});
      });
      o.onInit.call(this);

    }).each(function() {
      var menuClasses = [c.menuClass];
      if (sf.op.dropShadows){
        menuClasses.push(c.shadowClass);
      }
      $(this).addClass(menuClasses.join(' '));
    });
  };

  var sf = $.fn.superfish;
  sf.o = [];
  sf.op = {};

  sf.c = {
    bcClass: 'sf-breadcrumb',
    menuClass: 'sf-js-enabled',
    anchorClass: 'sf-with-ul',
    arrowClass: 'sf-sub-indicator',
    shadowClass: 'sf-shadow'
  };
  sf.defaults = {
    hoverClass: 'sfHover',
    pathClass: 'overideThisToUse',
    pathLevels: 1,
    delay: 800,
    animation: {opacity:'show'},
    speed: 'fast',
    autoArrows: true,
    dropShadows: true,
    disableHI: false, // true disables hoverIntent detection
    onInit: function(){}, // callback functions
    onBeforeShow: function(){},
    onShow: function(){},
    onHide: function(){}
  };
  $.fn.extend({
    hideSuperfishUl : function(){
      var o = sf.op,
        not = (o.retainPath===true) ? o.$path : '';
      o.retainPath = false;
      var $ul = $(['li.',o.hoverClass].join(''),this).add(this).not(not).removeClass(o.hoverClass)
          .children('ul').addClass('sf-hidden');
      o.onHide.call($ul);
      return this;
    },
    showSuperfishUl : function(){
      var o = sf.op,
        sh = sf.c.shadowClass+'-off',
        $ul = this.addClass(o.hoverClass)
          .children('ul.sf-hidden').hide().removeClass('sf-hidden');
      o.onBeforeShow.call($ul);
      $ul.animate(o.animation,o.speed,function(){ o.onShow.call($ul); });
      return this;
    }
  });
})(jQuery);;
/*!
 * hoverIntent v1.8.0 // 2014.06.29 // jQuery v1.9.1+
 * http://cherne.net/brian/resources/jquery.hoverIntent.html
 *
 * You may use hoverIntent under the terms of the MIT license. Basically that
 * means you are free to use hoverIntent as long as this header is left intact.
 * Copyright 2007, 2014 Brian Cherne
 */
(function($){$.fn.hoverIntent=function(handlerIn,handlerOut,selector){var cfg={interval:100,sensitivity:6,timeout:0};if(typeof handlerIn==="object"){cfg=$.extend(cfg,handlerIn)}else{if($.isFunction(handlerOut)){cfg=$.extend(cfg,{over:handlerIn,out:handlerOut,selector:selector})}else{cfg=$.extend(cfg,{over:handlerIn,out:handlerIn,selector:handlerOut})}}var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if(Math.sqrt((pX-cX)*(pX-cX)+(pY-cY)*(pY-cY))<cfg.sensitivity){$(ob).off("mousemove.hoverIntent",track);ob.hoverIntent_s=true;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=false;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=$.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type==="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).on("mousemove.hoverIntent",track);if(!ob.hoverIntent_s){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).off("mousemove.hoverIntent",track);if(ob.hoverIntent_s){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.on({"mouseenter.hoverIntent":handleHover,"mouseleave.hoverIntent":handleHover},cfg.selector)}})(jQuery);;
/*
 * sf-Smallscreen v1.3b - Provides small-screen compatibility for the jQuery Superfish plugin.
 *
 * Developer's note:
 * Built as a part of the Superfish project for Drupal (http://drupal.org/project/superfish)
 * Found any bug? have any cool ideas? contact me right away! http://drupal.org/user/619294/contact
 *
 * jQuery version: 1.3.x or higher.
 *
 * Dual licensed under the MIT and GPL licenses:
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
  */

(function($){
  $.fn.sfsmallscreen = function(options){
    options = $.extend({
      mode: 'inactive',
      type: 'accordion',
      breakpoint: 768,
      breakpointUnit: 'px',
      useragent: '',
      title: '',
      addSelected: false,
      menuClasses: false,
      hyperlinkClasses: false,
      excludeClass_menu: '',
      excludeClass_hyperlink: '',
      includeClass_menu: '',
      includeClass_hyperlink: '',
      accordionButton: 1,
      expandText: 'Expand',
      collapseText: 'Collapse'
    }, options);

    // We need to clean up the menu from anything unnecessary.
    function refine(menu){
      var
      refined = menu.clone(),
      // Things that should not be in the small-screen menus.
      rm = refined.find('span.sf-sub-indicator'),
      // This is a helper class for those who need to add extra markup that shouldn't exist
      // in the small-screen versions.
      rh = refined.find('.sf-smallscreen-remove'),
      // Mega-menus has to be removed too.
      mm = refined.find('ul.sf-multicolumn');
      for (var a = 0; a < rh.length; a++){
        rh.eq(a).replaceWith(rh.eq(a).html());
      }
      if (options.accordionButton == 2 || options.type == 'select'){
        for (var b = 0; b < rm.length; b++){
          rm.eq(b).remove();
        }
      }
      if (mm.length > 0){
        mm.removeClass('sf-multicolumn');
        var ol = refined.find('div.sf-multicolumn-column > ol');
        for (var o = 0; o < ol.length; o++){
          ol.eq(o).replaceWith('<ul>' + ol.eq(o).html() + '</ul>');
        }
        var elements = ['div.sf-multicolumn-column','.sf-multicolumn-wrapper > ol','li.sf-multicolumn-wrapper'];
        for (var i = 0; i < elements.length; i++){
          obj = refined.find(elements[i]);
          for (var t = 0; t < obj.length; t++){
            obj.eq(t).replaceWith(obj.eq(t).html());
          }
        }
        refined.find('.sf-multicolumn-column').removeClass('sf-multicolumn-column');
      }
      refined.add(refined.find('*')).css({width:''});
      return refined;
    }

    // Creating <option> elements out of the menu.
    function toSelect(menu, level){
      var
      items = '',
      childLI = $(menu).children('li');
      for (var a = 0; a < childLI.length; a++){
        var list = childLI.eq(a), parent = list.children('a, span');
        for (var b = 0; b < parent.length; b++){
          var
          item = parent.eq(b),
          path = (item.is('a') && !!item.attr('href')) ? item.attr('href') : '',
          // Class names modification.
          itemClone = item.clone(),
          classes = (options.hyperlinkClasses) ? ((options.excludeClass_hyperlink && itemClone.hasClass(options.excludeClass_hyperlink)) ? itemClone.removeClass(options.excludeClass_hyperlink).attr('class') : itemClone.attr('class')) : '',
          classes = (options.includeClass_hyperlink && !itemClone.hasClass(options.includeClass_hyperlink)) ? ((options.hyperlinkClasses) ? itemClone.addClass(options.includeClass_hyperlink).attr('class') : options.includeClass_hyperlink) : classes;
          // Retaining the active class if requested.
          if (options.addSelected && item.hasClass('active')){
            classes += ' active';
          }
          classes = (classes) ? ' class="' + classes + '"' : '';
          // <option> has to be disabled if the item is not a link.
          disable = (path == '') || (path == '#') ? ' disabled="disabled"' : '',
          // Crystal clear.
          subIndicator = 1 < level ? Array(level).join('-') + ' ' : '';
          // Preparing the <option> element.
          items += '<option value="' + path + '"' + classes + disable + '>' + subIndicator + $.trim(item.text()) +'</option>',
          childUL = list.find('> ul');
          // Using the function for the sub-menu of this item.
          for (var u = 0; u < childUL.length; u++){
            items += toSelect(childUL.eq(u), level + 1);
          }
        }
      }
      return items;
    }

    // Create the new version, hide the original.
    function convert(menu){
      var menuID = menu.attr('id'),
      // Creating a refined version of the menu.
      refinedMenu = refine(menu);
      // Currently the plugin provides two reactions to small screens.
      // Converting the menu to a <select> element, and converting to an accordion version of the menu.
      if (options.type == 'accordion'){
        var
        toggleID = menuID + '-toggle',
        accordionID = menuID + '-accordion';
        // Making sure the accordion does not exist.
        if ($('#' + accordionID).length == 0){
          var
          // Getting the style class.
          styleClass = menu.attr('class').split(' ').filter(function(item){
            return item.indexOf('sf-style-') > -1 ? item : '';
          }),
          // Creating the accordion.
          accordion = $(refinedMenu).attr('id', accordionID);
          // Removing unnecessary classes.
          accordion.removeClass('sf-horizontal sf-vertical sf-navbar sf-shadow sf-js-enabled');
          // Adding necessary classes.
          accordion.addClass('sf-accordion sf-hidden');
          // Removing style attributes and any unnecessary class.
          accordion.find('li').each(function(){
            $(this).removeAttr('style').removeClass('sfHover').attr('id', $(this).attr('id') + '-accordion');
          });
          // Doing the same and making sure all the sub-menus are off-screen (hidden).
          accordion.children('ul').removeAttr('style').not('.sf-hidden').addClass('sf-hidden');
          // Creating the accordion toggle switch.
          var toggle = '<div class="sf-accordion-toggle ' + styleClass + '"><a href="#" id="' + toggleID + '"><span>' + options.title + '</span></a></div>';

          // Adding Expand\Collapse buttons if requested.
          if (options.accordionButton == 2){
            accordion.addClass('sf-accordion-with-buttons');
            var parent = accordion.find('li.menuparent');
            for (var i = 0; i < parent.length; i++){
              parent.eq(i).prepend('<a href="#" class="sf-accordion-button">' + options.expandText + '</a>');
            }
          }
          // Inserting the according and hiding the original menu.
          menu.before(toggle).before(accordion).hide();

          var
          accordionElement = $('#' + accordionID),
          // Deciding what should be used as accordion buttons.
          buttonElement = (options.accordionButton < 2) ? 'a.menuparent,span.nolink.menuparent' : 'a.sf-accordion-button',
          button = accordionElement.find(buttonElement);

          // Attaching a click event to the toggle switch.
          $('#' + toggleID).on('click', function(e){
            // Preventing the click.
            e.preventDefault();
            // Adding the sf-expanded class.
            $(this).toggleClass('sf-expanded');

            if (accordionElement.hasClass('sf-expanded')){
              // If the accordion is already expanded:
              // Hiding its expanded sub-menus and then the accordion itself as well.
              accordionElement.add(accordionElement.find('li.sf-expanded')).removeClass('sf-expanded')
              .end().children('ul').hide()
              // This is a bit tricky, it's the same trick that has been in use in the main plugin for some time.
              // Basically we'll add a class that keeps the sub-menu off-screen and still visible,
              // and make it invisible and removing the class one moment before showing or hiding it.
              // This helps screen reader software access all the menu items.
              .end().hide().addClass('sf-hidden').show();
              // Changing the caption of any existing accordion buttons to 'Expand'.
              if (options.accordionButton == 2){
                accordionElement.find('a.sf-accordion-button').text(options.expandText);
              }
            }
            else {
              // But if it's collapsed,
              accordionElement.addClass('sf-expanded').hide().removeClass('sf-hidden').show();
            }
          });

          // Attaching a click event to the buttons.
          button.on('click', function(e){
            // Making sure the buttons does not exist already.
            if ($(this).closest('li').children('ul').length > 0){
              e.preventDefault();
              // Selecting the parent menu items.
              var parent = $(this).closest('li');
              // Creating and inserting Expand\Collapse buttons to the parent menu items,
              // of course only if not already happened.
              if (options.accordionButton == 1 && parent.children('a.menuparent,span.nolink.menuparent').length > 0 && parent.children('ul').children('li.sf-clone-parent').length == 0){
                var
                // Cloning the hyperlink of the parent menu item.
                cloneLink = parent.children('a.menuparent,span.nolink.menuparent').clone();
                // Removing unnecessary classes and element(s).
                cloneLink.removeClass('menuparent sf-with-ul').children('.sf-sub-indicator').remove();
                // Wrapping the hyerplinks in <li>.
                cloneLink = $('<li class="sf-clone-parent" />').html(cloneLink);
                // Adding a helper class and attaching them to the sub-menus.
                parent.children('ul').addClass('sf-has-clone-parent').prepend(cloneLink);
              }
              // Once the button is clicked, collapse the sub-menu if it's expanded.
              if (parent.hasClass('sf-expanded')){
                parent.children('ul').slideUp('fast', function(){
                  // Doing the accessibility trick after hiding the sub-menu.
                  $(this).closest('li').removeClass('sf-expanded').end().addClass('sf-hidden').show();
                });
                // Changing the caption of the inserted Collapse link to 'Expand', if any is inserted.
                if (options.accordionButton == 2 && parent.children('.sf-accordion-button').length > 0){
                  parent.children('.sf-accordion-button').text(options.expandText);
                }
              }
              // Otherwise, expand the sub-menu.
              else {
                // Doing the accessibility trick and then showing the sub-menu.
                parent.children('ul').hide().removeClass('sf-hidden').slideDown('fast')
                // Changing the caption of the inserted Expand link to 'Collape', if any is inserted.
                .end().addClass('sf-expanded').children('a.sf-accordion-button').text(options.collapseText)
                // Hiding any expanded sub-menu of the same level.
                .end().siblings('li.sf-expanded').children('ul')
                .slideUp('fast', function(){
                  // Doing the accessibility trick after hiding it.
                  $(this).closest('li').removeClass('sf-expanded').end().addClass('sf-hidden').show();
                })
                // Assuming Expand\Collapse buttons do exist, resetting captions, in those hidden sub-menus.
                .parent().children('a.sf-accordion-button').text(options.expandText);
              }
            }
          });
        }
      }
      else {
        var
        // Class names modification.
        menuClone = menu.clone(), classes = (options.menuClasses) ? ((options.excludeClass_menu && menuClone.hasClass(options.excludeClass_menu)) ? menuClone.removeClass(options.excludeClass_menu).attr('class') : menuClone.attr('class')) : '',
        classes = (options.includeClass_menu && !menuClone.hasClass(options.includeClass_menu)) ? ((options.menuClasses) ? menuClone.addClass(options.includeClass_menu).attr('class') : options.includeClass_menu) : classes,
        classes = (classes) ? ' class="' + classes + '"' : '';

        // Making sure the <select> element does not exist already.
        if ($('#' + menuID + '-select').length == 0){
          // Creating the <option> elements.
          var newMenu = toSelect(refinedMenu, 1),
          // Creating the <select> element and assigning an ID and class name.
          selectList = $('<select' + classes + ' id="' + menuID + '-select"/>')
          // Attaching the title and the items to the <select> element.
          .html('<option>' + options.title + '</option>' + newMenu)
          // Attaching an event then.
          .change(function(){
            // Except for the first option that is the menu title and not a real menu item.
            if ($('option:selected', this).index()){
              window.location = selectList.val();
            }
          });
          // Applying the addSelected option to it.
          if (options.addSelected){
            selectList.find('.active').attr('selected', !0);
          }
          // Finally inserting the <select> element into the document then hiding the original menu.
          menu.before(selectList).hide();
        }
      }
    }

    // Turn everything back to normal.
    function turnBack(menu){
      var
      id = '#' + menu.attr('id');
      // Removing the small screen version.
      $(id + '-' + options.type).remove();
      // Removing the accordion toggle switch as well.
      if (options.type == 'accordion'){
        $(id + '-toggle').parent('div').remove();
      }
      // Remove inline CSS display property; less clear than simply using .show(), but respects stylesheet
      $(id).css('display', '');
    }

    // Return original object to support chaining.
    // Although this is unnecessary because of the way the module uses these plugins.
    for (var s = 0; s < this.length; s++){
      var
      menu = $(this).eq(s),
      mode = options.mode;
      // The rest is crystal clear, isn't it? :)
      if (mode == 'always_active'){
        convert(menu);
      }
      else if (mode == 'window_width'){
        var breakpoint = (options.breakpointUnit == 'em') ? (options.breakpoint * parseFloat($('body').css('font-size'))) : options.breakpoint,
        windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
        timer;
        if ((typeof Modernizr === 'undefined' || typeof Modernizr.mq !== 'function') && windowWidth < breakpoint){
          convert(menu);
        }
        else if (typeof Modernizr !== 'undefined' && typeof Modernizr.mq === 'function' && Modernizr.mq('(max-width:' + (breakpoint - 1) + 'px)')) {
          convert(menu);
        }
        $(window).resize(function(){
          clearTimeout(timer);
          timer = setTimeout(function(){
            var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            if ((typeof Modernizr === 'undefined' || typeof Modernizr.mq !== 'function') && windowWidth < breakpoint){
              convert(menu);
            }
            else if (typeof Modernizr !== 'undefined' && typeof Modernizr.mq === 'function' && Modernizr.mq('(max-width:' + (breakpoint - 1) + 'px)')) {
              convert(menu);
            }
            else {
              turnBack(menu);
            }
          }, 50);
        });
      }
      else if (mode == 'useragent_custom'){
        if (options.useragent != ''){
          var ua = RegExp(options.useragent, 'i');
          if (navigator.userAgent.match(ua)){
            convert(menu);
          }
        }
      }
      else if (mode == 'useragent_predefined' && navigator.userAgent.match(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i)){
        convert(menu);
      }
    }
    return this;
  }
})(jQuery);
;
/*
 * Supposition v0.2 - an optional enhancer for Superfish jQuery menu widget.
 *
 * Copyright (c) 2008 Joel Birch - based mostly on work by Jesse Klaasse and credit goes largely to him.
 * Special thanks to Karl Swedberg for valuable input.
 *
 * Dual licensed under the MIT and GPL licenses:
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 */
/*
 * This is not the original jQuery Supposition plugin.
 * Please refer to the README for more information.
 */

(function($){
  $.fn.supposition = function(){
    var $w = $(window), /*do this once instead of every onBeforeShow call*/
    _offset = function(dir) {
      return window[dir == 'y' ? 'pageYOffset' : 'pageXOffset']
      || document.documentElement && document.documentElement[dir=='y' ? 'scrollTop' : 'scrollLeft']
      || document.body[dir=='y' ? 'scrollTop' : 'scrollLeft'];
    },
    onHide = function(){
      this.css({bottom:''});
    },
    onBeforeShow = function(){
      this.each(function(){
        var $u = $(this);
        $u.css('display','block');
        var $mul = $u.closest('.sf-menu'),
        level = $u.parents('ul').length,
        menuWidth = $u.width(),
        menuParentWidth = $u.closest('li').outerWidth(true),
        menuParentLeft = $u.closest('li').offset().left,
        totalRight = $w.width() + _offset('x'),
        menuRight = $u.offset().left + menuWidth,
        exactMenuWidth = (menuRight > (menuParentWidth + menuParentLeft)) ? menuWidth - (menuRight - (menuParentWidth + menuParentLeft)) : menuWidth;
        if ($u.parents('.sf-js-enabled').hasClass('rtl')) {
          if (menuParentLeft < exactMenuWidth) {
            if (($mul.hasClass('sf-horizontal') && level == 1) || ($mul.hasClass('sf-navbar') && level == 2)){
              $u.css({left:0,right:'auto'});
            }
            else {
              $u.css({left:menuParentWidth + 'px',right:'auto'});
            }
          }
        }
        else {
          if (menuRight > totalRight && menuParentLeft > menuWidth) {
            if (($mul.hasClass('sf-horizontal') && level == 1) || ($mul.hasClass('sf-navbar') && level == 2)){
              $u.css({right:0,left:'auto'});
            }
            else {
              $u.css({right:menuParentWidth + 'px',left:'auto'});
            }
          }
        }
        var windowHeight = $w.height(),
        offsetTop = $u.offset().top,
        menuParentShadow = ($mul.hasClass('sf-shadow') && $u.css('padding-bottom').length > 0) ? parseInt($u.css('padding-bottom').slice(0,-2)) : 0,
        menuParentHeight = ($mul.hasClass('sf-vertical')) ? '-' + menuParentShadow : $u.parent().outerHeight(true) - menuParentShadow,
        menuHeight = $u.height(),
        baseline = windowHeight + _offset('y');
        var expandUp = ((offsetTop + menuHeight > baseline) && (offsetTop > menuHeight));
        if (expandUp) {
          $u.css({bottom:menuParentHeight + 'px',top:'auto'});
        }
        $u.css('display','none');
      });
    };

    return this.each(function() {
      var o = $.fn.superfish.o[this.serial]; /* get this menu's options */

      /* if callbacks already set, store them */
      var _onBeforeShow = o.onBeforeShow,
      _onHide = o.onHide;

      $.extend($.fn.superfish.o[this.serial],{
        onBeforeShow: function() {
          onBeforeShow.call(this); /* fire our Supposition callback */
          _onBeforeShow.call(this); /* fire stored callbacks */
        },
        onHide: function() {
          onHide.call(this); /* fire our Supposition callback */
          _onHide.call(this); /* fire stored callbacks */
        }
      });
    });
  };
})(jQuery);;
/*
 * Supersubs v0.4b - jQuery plugin
 * Copyright (c) 2013 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 *
 * This plugin automatically adjusts submenu widths of suckerfish-style menus to that of
 * their longest list item children. If you use this, please expect bugs and report them
 * to the jQuery Google Group with the word 'Superfish' in the subject line.
 *
 */
/*
 * This is not the original jQuery Supersubs plugin.
 * Please refer to the README for more information.
 */

(function($){ // $ will refer to jQuery within this closure
  $.fn.supersubs = function(options){
    var opts = $.extend({}, $.fn.supersubs.defaults, options);
    // return original object to support chaining
    // Although this is unnecessary due to the way the module uses these plugins.
    for (var a = 0; a < this.length; a++) {
      // cache selections
      var $$ = $(this).eq(a),
      // support metadata
      o = $.meta ? $.extend({}, opts, $$.data()) : opts;
      // Jump one level if it's a "NavBar"
      if ($$.hasClass('sf-navbar')) {
        $$ = $$.children('li').children('ul');
      }
      // cache all ul elements
      var $ULs = $$.find('ul'),
      // get the font size of menu.
      // .css('fontSize') returns various results cross-browser, so measure an em dash instead
      fontsize = $('<li id="menu-fontsize">&#8212;</li>'),
      size = fontsize.attr('style','padding:0;position:absolute;top:-99999em;width:auto;')
      .appendTo($$)[0].clientWidth; //clientWidth is faster than width()
      // remove em dash
      fontsize.remove();

      // loop through each ul in menu
      for (var b = 0; b < $ULs.length; b++) {
        var
        // cache this ul
        $ul = $ULs.eq(b);
        // If a multi-column sub-menu, and only if correctly configured.
        if (o.multicolumn && $ul.hasClass('sf-multicolumn') && $ul.find('.sf-multicolumn-column').length > 0){
          // Look through each column.
          var $column = $ul.find('div.sf-multicolumn-column > ol'),
          // Overall width.
          mwWidth = 0;
          for (var d = 0; d < $column.length; d++){
            resize($column.eq(d));
            // New column width, in pixels.
            var colWidth = $column.width();
            // Just a trick to convert em unit to px.
            $column.css({width:colWidth})
            // Making column parents the same size.
            .parents('.sf-multicolumn-column').css({width:colWidth});
            // Overall width.
            mwWidth += parseInt(colWidth);
          }
          // Resizing the columns container too.
          $ul.add($ul.find('li.sf-multicolumn-wrapper, li.sf-multicolumn-wrapper > ol')).css({width:mwWidth});
        }
        else {
          resize($ul);
        }
      }
    }
    function resize($ul){
      var
      // get all (li) children of this ul
      $LIs = $ul.children(),
      // get all anchor grand-children
      $As = $LIs.children('a');
      // force content to one line and save current float property
      $LIs.css('white-space','nowrap');
      // remove width restrictions and floats so elements remain vertically stacked
      $ul.add($LIs).add($As).css({float:'none',width:'auto'});
      // this ul will now be shrink-wrapped to longest li due to position:absolute
      // so save its width as ems.
      var emWidth = $ul.get(0).clientWidth / size;
      // add more width to ensure lines don't turn over at certain sizes in various browsers
      emWidth += o.extraWidth;
      // restrict to at least minWidth and at most maxWidth
      if (emWidth > o.maxWidth) {emWidth = o.maxWidth;}
      else if (emWidth < o.minWidth) {emWidth = o.minWidth;}
      emWidth += 'em';
      // set ul to width in ems
      $ul.css({width:emWidth});
      // restore li floats to avoid IE bugs
      // set li width to full width of this ul
      // revert white-space to normal
      $LIs.add($As).css({float:'',width:'',whiteSpace:''});
      // update offset position of descendant ul to reflect new width of parent.
      // set it to 100% in case it isn't already set to this in the CSS
      for (var c = 0; c < $LIs.length; c++) {
        var $childUl = $LIs.eq(c).children('ul');
        var offsetDirection = $childUl.css('left') !== undefined ? 'left' : 'right';
        $childUl.css(offsetDirection,'100%');
      }
    }
    return this;
  };
  // expose defaults
  $.fn.supersubs.defaults = {
    multicolumn: true, // define width for multi-column sub-menus and their columns.
    minWidth: 12, // requires em unit.
    maxWidth: 27, // requires em unit.
    extraWidth: 1 // extra width can ensure lines don't sometimes turn over due to slight browser differences in how they round-off values
  };
})(jQuery); // plugin code ends
;
/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function ($, once) {
  var deprecatedMessageSuffix = "is deprecated in Drupal 9.3.0 and will be removed in Drupal 10.0.0. Use the core/once library instead. See https://www.drupal.org/node/3158256";
  var originalJQOnce = $.fn.once;
  var originalJQRemoveOnce = $.fn.removeOnce;

  $.fn.once = function jQueryOnce(id) {
    Drupal.deprecationError({
      message: "jQuery.once() ".concat(deprecatedMessageSuffix)
    });
    return originalJQOnce.apply(this, [id]);
  };

  $.fn.removeOnce = function jQueryRemoveOnce(id) {
    Drupal.deprecationError({
      message: "jQuery.removeOnce() ".concat(deprecatedMessageSuffix)
    });
    return originalJQRemoveOnce.apply(this, [id]);
  };

  var drupalOnce = once;

  function augmentedOnce(id, selector, context) {
    originalJQOnce.apply($(selector, context), [id]);
    return drupalOnce(id, selector, context);
  }

  function remove(id, selector, context) {
    originalJQRemoveOnce.apply($(selector, context), [id]);
    return drupalOnce.remove(id, selector, context);
  }

  window.once = Object.assign(augmentedOnce, drupalOnce, {
    remove: remove
  });
})(jQuery, once);;
/**
 * @file
 * The Superfish Drupal Behavior to apply the Superfish jQuery plugin to lists.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * jQuery Superfish plugin.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the behavior to an applicable <ul> element.
   */
  Drupal.behaviors.superfish = {
    attach: function (context, drupalSettings) {
      // Take a look at each menu to apply Superfish to.
      $.each(drupalSettings.superfish || {}, function (index, options) {
        var $menu = $('ul#' + options.id, context);

        // Check if we are to apply the Supersubs plug-in to it.
        if (options.plugins || false) {
          if (options.plugins.supersubs || false) {
            $menu.supersubs(options.plugins.supersubs);
          }
        }

        // Apply Superfish to the menu.
        $menu.superfish(options.sf);

        // Check if we are to apply any other plug-in to it.
        if (options.plugins || false) {
          if (options.plugins.touchscreen || false) {
            $menu.sftouchscreen(options.plugins.touchscreen);
          }
          if (options.plugins.smallscreen || false) {
            $menu.sfsmallscreen(options.plugins.smallscreen);
          }
          if (options.plugins.supposition || false) {
            $menu.supposition();
          }
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
;
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/frontend.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/frontend.js":
/*!************************!*\
  !*** ./js/frontend.js ***!
  \************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _plugin_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./plugin.js */ "./js/plugin.js");
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }



(function (Drupal) {
  'use strict';

  Drupal.TBMegaMenu = Drupal.TBMegaMenu || {};
  var focusableSelector = 'a:not([disabled]):not([tabindex="-1"]), button:not([disabled]):not([tabindex="-1"]), input:not([disabled]):not([tabindex="-1"]), select:not([disabled]):not([tabindex="-1"]), textarea:not([disabled]):not([tabindex="-1"]), details:not([disabled]):not([tabindex="-1"]), [tabindex]:not([disabled]):not([tabindex="-1"])';

  var updateTBMenus = function updateTBMenus() {
    document.querySelectorAll('.tbm').forEach(function (thisMenu) {
      var menuId = thisMenu.getAttribute('id');
      Drupal.TBMegaMenu[menuId] = {};
      var breakpoint = parseInt(thisMenu.getAttribute('data-breakpoint'));

      if (window.matchMedia("(max-width: ".concat(breakpoint, "px)")).matches) {
        thisMenu.classList.add('tbm--mobile');
      } else {
        thisMenu.classList.remove('tbm--mobile');
      }

      var focusable = document.querySelectorAll(focusableSelector);
      focusable = _toConsumableArray(focusable);
      var topLevel = thisMenu.querySelectorAll('.tbm-link.level-1, .tbm-link.level-1 + .tbm-submenu-toggle');
      topLevel = _toConsumableArray(topLevel);
      topLevel = topLevel.filter(function (element) {
        return element.offsetWidth > 0 && element.offsetHeight > 0;
      });
      Drupal.TBMegaMenu['focusable'] = focusable;
      Drupal.TBMegaMenu[menuId]['topLevel'] = topLevel;
    });
  };

  var throttled = _.throttle(updateTBMenus, 100);

  ['load', 'resize'].forEach(function (event) {
    window.addEventListener(event, throttled);
  });

  Drupal.TBMegaMenu.getNextPrevElement = function (direction) {
    var excludeSubnav = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
    var current = document.activeElement;
    var nextElement = null;

    if (current) {
      var focusable = document.querySelectorAll(focusableSelector);
      focusable = _toConsumableArray(focusable);
      focusable = Drupal.TBMegaMenu['focusable'].filter(function (element) {
        if (excludeSubnav) {
          return !element.closest('.tbm-subnav') && element.offsetWidth > 0 && element.offsetHeight > 0;
        }

        return element.offsetWidth > 0 && element.offsetHeight > 0;
      });
      var index = focusable.indexOf(current);

      if (index > -1) {
        if (direction === 'next') {
          nextElement = focusable[index + 1] || focusable[0];
        } else {
          nextElement = focusable[index - 1] || focusable[0];
        }
      }
    }

    return nextElement;
  };

  Drupal.behaviors.tbMegaMenuInit = {
    attach: function attach(context) {
      context.querySelectorAll('.tbm').forEach(function (menu) {
        if (!menu.getAttribute('data-initialized')) {
          menu.setAttribute('data-initialized', 'true');
          var tbMega = new _plugin_js__WEBPACK_IMPORTED_MODULE_0__["TBMegaMenu"](menu.getAttribute('id'));
          tbMega.init();
        }
      });
    }
  };
  Drupal.behaviors.tbMegaMenuRespond = {
    attach: function attach(context) {
      updateTBMenus();
    }
  };
})(Drupal);

/***/ }),

/***/ "./js/plugin.js":
/*!**********************!*\
  !*** ./js/plugin.js ***!
  \**********************/
/*! exports provided: TBMegaMenu */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "TBMegaMenu", function() { return TBMegaMenu; });
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var TBMegaMenu = function () {
  function TBMegaMenu(id) {
    _classCallCheck(this, TBMegaMenu);

    _defineProperty(this, 'use strict', void 0);

    this.id = id;
    this.navParent = document.getElementById(this.id);
    this.isTouch = window.matchMedia('(pointer: coarse)').matches;
    var menuSettings = drupalSettings['TBMegaMenu'][this.id];
    this.hasArrows = menuSettings['arrows'] === '1';
    var mm_duration = this.navParent.getAttribute('data-duration') ? parseInt(this.navParent.getAttribute('data-duration')) : 0;
    this.mm_timeout = mm_duration ? 100 + mm_duration : 500;
  }

  _createClass(TBMegaMenu, [{
    key: "isMobile",
    get: function get() {
      return this.navParent.classList.contains('tbm--mobile');
    }
  }, {
    key: "keyDownHandler",
    value: function keyDownHandler(k) {
      var _this = this;

      var menuId = this.id;

      switch (k.keyCode) {
        case 9:
          if (!_this.isMobile) {
            nav_tab(k);
          }

          break;

        case 13:
          nav_enter();
          break;

        case 27:
          nav_esc();
          break;

        case 37:
          k.preventDefault();
          nav_left(k);
          break;

        case 38:
          k.preventDefault();
          nav_up(k);
          break;

        case 39:
          k.preventDefault();
          nav_right(k);
          break;

        case 40:
          k.preventDefault();
          nav_down(k);
          break;

        default:
      }

      function nav_tab(k) {
        k.preventDefault();

        if (nav_is_toplink()) {
          if (k.shiftKey || k.keyCode === 38 || k.keyCode === 37) {
            nav_prev_toplink();
          } else {
            nav_next_toplink();
          }
        } else {
          if (k.shiftKey || k.keyCode === 38 || k.keyCode === 37) {
            Drupal.TBMegaMenu.getNextPrevElement('prev').focus();
          } else {
            Drupal.TBMegaMenu.getNextPrevElement('next').focus();
          }
        }
      }

      function nav_esc() {
        _this.closeMenu();
      }

      function nav_enter() {
        if (document.activeElement.classList.contains('no-link')) {
          document.activeElement.click();
        }
      }

      function nav_left(k) {
        if (nav_is_toplink()) {
          nav_prev_toplink();
        } else {
          nav_up(k);
        }
      }

      function nav_right(k) {
        if (nav_is_toplink()) {
          nav_next_toplink();
        } else {
          nav_down(k);
        }
      }

      function nav_up(k) {
        if (nav_is_toplink()) {} else {
          nav_tab(k);
        }
      }

      function nav_down(k) {
        if (nav_is_toplink()) {
          Drupal.TBMegaMenu.getNextPrevElement('next').focus();
        } else if (Drupal.TBMegaMenu.getNextPrevElement('next').closest('.tbm-item.level-1') !== document.activeElement.closest('.tbm-item.level-1')) {} else {
          nav_tab(k);
        }
      }

      function nav_is_toplink() {
        var topLevel = Drupal.TBMegaMenu[menuId]['topLevel'];
        return topLevel.indexOf(document.activeElement) > -1;
      }

      function nav_is_last_toplink() {
        var topLevel = Drupal.TBMegaMenu[menuId]['topLevel'];
        return topLevel.indexOf(document.activeElement) === topLevel.length - 1;
      }

      function nav_is_first_toplink() {
        var topLevel = Drupal.TBMegaMenu[menuId]['topLevel'];
        return topLevel.indexOf(document.activeElement) === 0;
      }

      function nav_next_toplink() {
        if (!nav_is_last_toplink()) {
          var topLevel = Drupal.TBMegaMenu[menuId]['topLevel'];
          var index = topLevel.indexOf(document.activeElement);

          if (index > -1) {
            topLevel[index + 1].focus();
          }
        } else {
          Drupal.TBMegaMenu.getNextPrevElement('next', true).focus();
        }
      }

      function nav_prev_toplink() {
        if (!nav_is_first_toplink()) {
          var topLevel = Drupal.TBMegaMenu[menuId]['topLevel'];
          var index = topLevel.indexOf(document.activeElement);

          if (index > -1) {
            topLevel[index - 1].focus();
          }
        } else {
          Drupal.TBMegaMenu.getNextPrevElement('prev', true).focus();
        }
      }
    }
  }, {
    key: "handleTouch",
    value: function handleTouch(item) {
      var _this = this;

      var link = item.querySelector(':scope > .tbm-link-container').querySelector(':scope > .tbm-link');
      var tbitem = link.closest('.tbm-item');
      link.addEventListener('click', function (event) {
        if (!_this.isMobile && _this.isTouch && !_this.hasArrows) {
          if (link.classList.contains('tbm-clicked')) {
            var uri = link.getAttribute('href');

            if (uri) {
              window.location.href = uri;
            } else {
              link.classList.remove('tbm-clicked');

              _this.hideMenu(tbitem, _this.mm_timeout);
            }
          } else {
            event.preventDefault();

            var allOpen = _this.navParent.querySelectorAll('.open');

            allOpen.forEach(function (element) {
              if (element.contains(link)) {} else {
                element.classList.remove('open');
              }
            });

            _this.ariaCheck();

            _this.navParent.querySelectorAll('.tbm-clicked').forEach(function (element) {
              element.classList.remove('tbm-clicked');
            });

            link.classList.add('tbm-clicked');

            _this.showMenu(tbitem, _this.mm_timeout);
          }
        }
      });
      document.addEventListener('click', function (event) {
        if (!event.target.closest('.tbm-nav')) {
          if (_this.navParent.querySelectorAll('.open').length > 0) {
            _this.closeMenu();
          }
        }
      });
      document.addEventListener('focusin', function (event) {
        if (!event.target.closest('.tbm')) {
          _this.closeMenu();
        }
      });
    }
  }, {
    key: "closeMenu",
    value: function closeMenu() {
      this.navParent.classList.remove('tbm--mobile-show');
      this.navParent.querySelector('.tbm-button').setAttribute('aria-expanded', 'false');
      this.navParent.querySelectorAll('.open').forEach(function (element) {
        element.classList.remove('open');
      });
      this.navParent.querySelectorAll('.tbm-clicked').forEach(function (element) {
        element.classList.remove('tbm-clicked');
      });
      this.ariaCheck();
    }
  }, {
    key: "ariaCheck",
    value: function ariaCheck() {
      var toggleElement = function toggleElement(element, value) {
        element.querySelectorAll('.tbm-toggle, .tbm-submenu-toggle').forEach(function (toggle) {
          toggle.setAttribute('aria-expanded', value);
        });
      };

      this.navParent.querySelectorAll('.tbm-item').forEach(function (element) {
        if (element.classList.contains('tbm-group')) {
          if (!element.closest('.open')) {
            toggleElement(element, 'false');
          } else if (element.closest('.open')) {
            toggleElement(element, 'true');
          }
        } else if (element.classList.contains('tbm-item--has-dropdown') || element.classList.contains('tbm-item--has-flyout')) {
          if (!element.classList.contains('open')) {
            toggleElement(element, 'false');
          } else if (element.classList.contains('open')) {
            toggleElement(element, 'true');
          }
        } else {
          element.querySelectorAll('.tbm-toggle, .tbm-submenu-toggle').forEach(function (toggle) {
            toggle.removeAttribute('aria-expanded');
          });
        }
      });
    }
  }, {
    key: "showMenu",
    value: function showMenu(listItem, mm_timeout) {
      var _this = this;

      if (listItem.classList.contains('level-1')) {
        listItem.classList.add('animating');
        clearTimeout(listItem.animatingTimeout);
        listItem.animatingTimeout = setTimeout(function () {
          listItem.classList.remove('animating');
        }, mm_timeout);
        clearTimeout(listItem.hoverTimeout);
        listItem.hoverTimeout = setTimeout(function () {
          listItem.classList.add('open');

          _this.ariaCheck();
        }, 100);
      } else {
        clearTimeout(listItem.hoverTimeout);
        listItem.hoverTimeout = setTimeout(function () {
          listItem.classList.add('open');

          _this.ariaCheck();
        }, 100);
      }
    }
  }, {
    key: "hideMenu",
    value: function hideMenu(listItem, mm_timeout) {
      var _this = this;

      listItem.querySelectorAll('.tbm-toggle, .tbm-submenu-toggle').forEach(function (element) {
        element.setAttribute('aria-expanded', false);
      });

      if (listItem.classList.contains('level-1')) {
        listItem.classList.add('animating');
        clearTimeout(listItem.animatingTimeout);
        listItem.animatingTimeout = setTimeout(function () {
          listItem.classList.remove('animating');
        }, mm_timeout);
        clearTimeout(listItem.hoverTimeout);
        listItem.hoverTimeout = setTimeout(function () {
          listItem.classList.remove('open');

          _this.ariaCheck();
        }, 100);
      } else {
        clearTimeout(listItem.hoverTimeout);
        listItem.hoverTimeout = setTimeout(function () {
          listItem.classList.remove('open');

          _this.ariaCheck();
        }, 100);
      }
    }
  }, {
    key: "init",
    value: function init() {
      var _this = this;

      document.querySelectorAll('.tbm-button').forEach(function (element) {
        element.addEventListener('click', function (event) {
          if (_this.navParent.classList.contains('tbm--mobile-show')) {
            _this.closeMenu();
          } else {
            _this.navParent.classList.add('tbm--mobile-show');

            event.currentTarget.setAttribute('aria-expanded', 'true');
          }
        });
      });

      if (!this.isTouch) {
        this.navParent.querySelectorAll('.tbm-item').forEach(function (element) {
          element.addEventListener('mouseenter', function (event) {
            if (!_this.isMobile && !_this.hasArrows) {
              _this.showMenu(element, _this.mm_timeout);
            }
          });
          element.addEventListener('mouseleave', function (event) {
            if (!_this.isMobile && !_this.hasArrows) {
              _this.hideMenu(element, _this.mm_timeout);
            }
          });
        });
        this.navParent.querySelectorAll('.tbm-toggle').forEach(function (element) {
          element.addEventListener('focus', function (event) {
            if (!_this.isMobile && !_this.hasArrows) {
              var listItem = event.currentTarget.closest('li');

              _this.showMenu(listItem, _this.mm_timeout);

              document.addEventListener('focusin', function (event) {
                if (!_this.isMobile && !_this.hasArrows) {
                  if (event.target !== listItem && !listItem.contains(event.target)) {
                    document.removeEventListener('focusin', event);

                    _this.hideMenu(listItem, _this.mm_timeout);
                  }
                }
              });
            }
          });
        });
      }

      this.navParent.querySelectorAll('.tbm-item').forEach(function (item) {
        if (item.querySelector(':scope > .tbm-submenu')) {
          _this.handleTouch(item);
        }
      });
      this.navParent.querySelectorAll('.tbm-submenu-toggle, .tbm-link.no-link').forEach(function (toggleElement) {
        toggleElement.addEventListener('click', function (event) {
          if (_this.isMobile) {
            var parentItem = event.currentTarget.closest('.tbm-item');

            if (parentItem.classList.contains('open')) {
              _this.hideMenu(parentItem, _this.mm_timeout);
            } else {
              _this.showMenu(parentItem, _this.mm_timeout);
            }
          }

          if (!_this.isMobile && !(_this.isTouch && !_this.hasArrows && event.currentTarget.classList.contains('no-link'))) {
            var _parentItem = event.currentTarget.closest('.tbm-item');

            if (_parentItem.classList.contains('open')) {
              _this.hideMenu(_parentItem, _this.mm_timeout);

              _parentItem.querySelectorAll('.open').forEach(function (element) {
                _this.hideMenu(element, _this.mm_timeout);
              });
            } else {
              _this.showMenu(_parentItem, _this.mm_timeout);

              var prevSibling = _parentItem.previousElementSibling;

              while (prevSibling) {
                _this.hideMenu(prevSibling, _this.mm_timeout);

                prevSibling.querySelectorAll('.open').forEach(function (item) {
                  _this.hideMenu(item, _this.mm_timeout);
                });
                prevSibling = prevSibling.previousElementSibling;
              }

              var nextSibling = _parentItem.nextElementSibling;

              while (nextSibling) {
                _this.hideMenu(nextSibling, _this.mm_timeout);

                nextSibling.querySelectorAll('.open').forEach(function (item) {
                  _this.hideMenu(item, _this.mm_timeout);
                });
                nextSibling = nextSibling.nextElementSibling;
              }
            }
          }
        });
      });
      this.navParent.addEventListener('keydown', this.keyDownHandler.bind(this));
    }
  }]);

  return TBMegaMenu;
}();

/***/ })

/******/ });
