(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["profile"],{"595c":function(e,t,n){"use strict";n.r(t);var r=function(){var e=this,t=e._self._c;return t("div",[t("b-spinner",{staticStyle:{color:"lightgray"}})],1)},a=[],c=n("c7eb"),s=n("1da1"),i=n("5530"),o=(n("ac1f"),n("5319"),n("9fee")),u=n("b279"),l=n("2f62"),p={data:function(){return{exists:""}},computed:Object(i["a"])({},Object(l["b"])({shareLink:"app/shared",user:"auth/user"})),mounted:function(){var e=this;return Object(s["a"])(Object(c["a"])().mark((function t(){var n,r;return Object(c["a"])().wrap((function(t){while(1)switch(t.prev=t.next){case 0:if("undefined"!==typeof e.shareLink.id){t.next=3;break}return t.next=3,e.$router.replace("/");case 3:return n=new o["a"]({id:e.shareLink.id}),t.prev=4,Object(u["a"])(),t.next=8,e.$store.dispatch("shareLink/shareProjectByLinkId",n);case 8:return r=t.sent,r.data.shareProjectByLinkId?console.log("Exist link"):console.log("Invalid link"),e.$store.dispatch("app/shared",""),t.next=13,e.$router.replace("/");case 13:Object(u["i"])(),t.next=19;break;case 16:t.prev=16,t.t0=t["catch"](4),console.log(t.t0);case 19:case"end":return t.stop()}}),t,null,[[4,16]])})))()}},d=p,h=n("2877"),b=Object(h["a"])(d,r,a,!1,null,null,null);t["default"]=b.exports}}]);