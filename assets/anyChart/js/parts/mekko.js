if(!_.mekko){_.mekko=1;(function($){var SK=function(a,b){a.UN&&$.Kr(a,b)},TK=function(a,b,c,d,e,f){$.Gy.call(this,a,b,c,d,e);this.It=f},UK=function(a,b){$.Rx.call(this,!0);this.hb("mekko");this.Lu=this.lu=null;this.Vea=!!a;this.It=!!b;this.ca.defaultSeriesType="mekko";$.T(this.za,[["pointsPadding",4294967295,1]])},VK=function(a,b,c,d,e,f){a.data.length&&(a.data[0].j.missing||(c.push(a.U.name()),d.push(a.data[0].data.value)),a.data[b].j.missing||(e.push(a.U.name()),f.push(a.data[b].data.value)))},zea=function(a){if(a.rm.length){var b=
[],c=[],d=[],e=[],f=a.rm[0].data.length-1,h;if("direct"==a.Xa().vv())for(h=a.rm.length;h--;)VK(a.rm[h],f,b,d,c,e);else for(h=0;h<a.rm.length;h++)VK(a.rm[h],f,b,d,c,e);f=a.kP();f.Mg();f.Vc.apply(f,b);f.Ug();SK(f,d);f=a.eR();f.Mg();f.Vc.apply(f,c);f.Ug();SK(f,e)}},WK=function(a){$.$x.call(this,a)},XK=function(a,b,c,d){if(0!=b.get("value")&&c){var e=b.j("x"),f=b.j("zero"),h=b.j("value"),k=d?b.j("pointWidth"):a.o;b.j("pointWidth",k);d=Math.abs($.Bl($.M(a.U.ya.i("pointsPadding"),k)));k-=2*d;e-=k/2;k=e+
k;b=b.j("stroke")?b.j("stroke"):c.path.stroke();b=$.Sb(b);var l=b/2;e=$.xn(e+l,b);k=$.xn(k-l,b);h+=a.na?-l:l;f-=a.na?-l:l;h=$.xn(h,b);f=$.xn(f,b);d&&(b=Math.abs(f-h),d=b>2*d?d:b/2-1,f-=a.na?-d:d,h+=a.na?-d:d);d=c.path;$.Yx(d,a.na,e,h);$.Zx(d,a.na,k,h,k,f,e,f);d.close();d=c.hatchFill;$.Yx(d,a.na,e,h);$.Zx(d,a.na,k,h,k,f,e,f);d.close()}},YK=function(a){var b=new UK(!1);b.Id="mekko";b.mi();b.tf();for(var c=0,d=arguments.length;c<d;c++)b.mekko(arguments[c]);return b},ZK=function(a){var b=new UK(!0);b.hb("mosaic");
b.Id="mosaic";b.mi();b.tf();for(var c=0,d=arguments.length;c<d;c++)b.mekko(arguments[c]);return b},$K=function(a){var b=new UK(!1,!0);b.hb("barmekko");b.Id="barmekko";b.mi();b.tf();for(var c=0,d=arguments.length;c<d;c++)b.mekko(arguments[c]);return b};$.H(TK,$.Gy);TK.prototype.gZ=function(a){return!this.It&&0>a?0:a};TK.prototype.Mo=function(a,b,c){(this.It||0<a.get("value"))&&TK.B.Mo.call(this,a,b,c)};TK.prototype.Pf=function(a,b,c){(this.It||0<a.get("value"))&&TK.B.Pf.call(this,a,b,c)};$.H(UK,$.Rx);var aL={};aL.mekko={xb:29,Cb:2,Ib:[$.jE,$.JD],Gb:null,zb:null,sb:$.Hy|5767168,vb:"value",ub:"zero"};UK.prototype.Ei=aL;$.Zw(UK,UK.prototype.Ei);$.g=UK.prototype;$.g.oa=$.Rx.prototype.oa|268435456;$.g.nL=function(a){UK.B.nL.call(this,a);$.W(a,64)&&this.u(268435456,1)};
$.g.kP=function(){if($.n(void 0)){var a=$.yr(this.lu,void 0,"ordinal",$.qr,null,this.FH,this);if(a){var b=this.lu==a;this.lu=a;this.lu.ba(b);if(!b){a=458752;if(this.zt()&&"categories"==this.mf().i("itemsSourceMode")||this.It)a|=512;this.u(a,268435457)}}return this}this.lu||(a=$.qx(this),b=this.lh("firstCategoriesScale"),this.lu=$.yr(this.lu,a[b],null,void 0,null,this.FH,this),this.lu.ba(!1));return this.lu};
$.g.eR=function(){if($.n(void 0)){var a=$.yr(this.Lu,void 0,"ordinal",$.qr,null,this.FH,this);if(a){var b=this.Lu==a;this.Lu=a;this.Lu.ba(b);b||this.u(458752,268435457)}return this}this.Lu||(a=$.qx(this),b=this.lh("lastCategoriesScale"),this.Lu=$.yr(this.Lu,a[b],null,void 0,null,this.FH,this),this.Lu.ba(!1));return this.Lu};$.g.FH=function(a){$.V(this);if($.W(a,4)){a=458752;if(this.zt()&&"categories"==this.mf().i("itemsSourceMode")||this.It)a|=512;this.u(a,268435456)}this.ba(!0)};$.g.Ey=function(){return $.qr};
$.g.fB=function(){return["Mekko chart X scale","ordinal"]};$.g.kJ=function(){return"linear"};$.g.IE=function(){return 15};$.g.lJ=function(){return["Chart scale","ordinal, linear, log, date-time"]};$.g.zt=function(){return!0};
$.g.Jl=function(a,b){if(this.It&&1==this.Tl()&&$.K(this.Qa(),$.Jr)){this.kb();var c=[],d,e=this.Qa().names(),f=this.Qa().values(),h=this.Sh(0);for(d=0;d<f.length;d++){var k=null;$.E(b)&&(k={value:f[d],name:e[d]},k=b.call(k,k));$.z(k)||(k=String(e[d]));var l=this.Yb().fc(d);c.push({text:k,iconType:"square",iconStroke:$.yk(l,1),iconFill:l,iconHatchFill:h.hatchFill()})}return c}return UK.B.Jl.call(this,a,b)};
$.g.D_=function(a){if(this.Vea){var b=!this.Qa().wf(),c="left"==a.i("orientation")||"bottom"==a.i("orientation");a.scale(c==b?this.kP():this.eR())}else a.scale(this.Xa())};
$.g.kb=function(){var a=this.J(196608);this.J(458752)&&this.u(268435456);UK.B.kb.call(this);if(a){var b,c=[],d=[],e=this.rm.length;for(a=0;a<this.rm.length;a++){var f=this.rm[a].data;for(b=0;b<f.length;b++){$.n(d[b])||(d[b]=0);if(f[b].j.missing){d[b]++;var h=0}else h=$.O(f[b].data.value),h=this.It?Math.abs(h):0>h?0:h;void 0==c[b]?c.push(h):c[b]+=h}}for(a=0;a<this.rm.length;a++)for(f=this.rm[a].data,b=h=0;b<f.length;b++)f[b].j.category=d[b]<e?h++:h;c=(0,$.se)(c,function(a,b){return d[b]<e});SK(this.Qa(),
c)}this.J(268435456)&&(zea(this),this.I(268435456))};var bL={};$.R(bL,0,"pointsPadding",$.Vo);$.Zo(UK,bL);$.g=UK.prototype;$.g.St=function(a,b){return new TK(this,this,a,b,!1,this.It)};$.g.qr=function(){return"mekko"};$.g.hI=function(){return 3};$.g.F=function(){var a=UK.B.F.call(this);a.type=this.La();$.lp(this,bL,a);return{chart:a}};$.g.Cr=function(a,b,c){UK.B.Cr.call(this,a,b,c)};
$.g.GS=function(a,b,c,d){var e=a.F();(a.scale()==this.kP()&&"left"!=a.i("orientation")||a.scale()==this.eR()&&"right"!=a.i("orientation"))&&$.tx(e,"scale",a.scale(),b,c);d.push($.pa(a));return e};$.g.Y=function(a,b){UK.B.Y.call(this,a,b);$.bp(this,bL,a)};var cL=UK.prototype;cL.xScale=cL.Qa;cL.yScale=cL.Xa;cL.crosshair=cL.ig;cL.xAxis=cL.Hh;cL.getXAxesCount=cL.eB;cL.yAxis=cL.Ki;cL.getYAxesCount=cL.gB;cL.getSeries=cL.Te;cL.palette=cL.Yb;cL.markerPalette=cL.ef;cL.hatchFillPalette=cL.Qd;cL.getType=cL.La;
cL.addSeries=cL.Gk;cL.getSeriesAt=cL.Sh;cL.getSeriesCount=cL.Tl;cL.removeSeries=cL.no;cL.removeSeriesAt=cL.sn;cL.removeAllSeries=cL.pp;cL.getPlotBounds=cL.Ff;cL.annotations=cL.Ej;cL.lineMarker=cL.Hm;cL.rangeMarker=cL.Om;cL.textMarker=cL.Um;$.H(WK,$.$x);$.jD[29]=WK;$.g=WK.prototype;$.g.type=29;$.g.flags=263905;$.g.Ig={path:"path",hatchFill:"path"};$.g.Mf=function(a,b){if(!a.j("missing")){var c=this.Lc.Pc(b);XK(this,a,c)}};$.g.Sr=function(a){var b=a.j("shapes"),c;for(c in b)b[c].clear();XK(this,a,b,!0)};$.g.Qz=function(a){if(!a.j("missing")){var b=a.j("shapes"),c;for(c in b)b[c].clear();XK(this,a,b,!0)}};$.no.mekko=YK;$.no.mosaic=ZK;$.no.barmekko=$K;$.G("anychart.mekko",YK);$.G("anychart.mosaic",ZK);$.G("anychart.barmekko",$K);}).call(this,$)}
