if(!_.waterfall){_.waterfall=1;(function($){var D0=function(a,b,c,d,e){$.iy.call(this,a,b,c,d,e)},E0=function(){$.Ey.call(this);this.Id="waterfall";$.Oo(this.Ha,[["dataMode",0,1],["connectorStroke",0,1]])},F0=function(a,b){var c=a.bb().transform(b),d=a.fg;if(a.ua()){var e=d.left;d=d.width}else e=d.Ka(),d=-d.height;return e+c*d},G0=function(a,b){for(var c=a.length;c--;){var d=a[c].data;if(d.length&&!d[b].o.missing)return c}return window.NaN},H0=function(a){$.Uy.call(this,a);this.D=!1},I0=function(a){var b=new E0;b.ia(!0,$.kl("waterfall"));for(var c=
0,d=arguments.length;c<d;c++)b.waterfall(arguments[c]);return b},xga={wha:"absolute",Uia:"diff"};$.I(D0,$.iy);$.f=D0.prototype;$.f.kD={"%BubbleSize":"size","%RangeStart":"low","%RangeEnd":"high","%XValue":"x","%Diff":"diff","%Absolute":"absolute","%IsTotal":"isTotal"};$.f.mp=function(a,b){var c=D0.F.mp.call(this,a,b);c.diff={value:b.o("diff"),type:"number"};c.absolute={value:b.o("absolute"),type:"number"};c.isTotal={value:b.o("isTotal"),type:""};return c};
$.f.NZ=function(){return{prevValue:0,tZ:!1,Bda:"absolute"==this.wa.I("dataMode")}};$.f.j0=function(a,b,c){var d=-1<(0,$.Ca)(this.Xl||[],b.o.rawIndex);a=a.get("isTotal");d=!!b.o.missing||d;var e=!(c.tZ||d);a=e||($.n(a)?!!a:d);!d||!e&&a?(d=c.Bda?d?c.prevValue:+b.data.value:c.prevValue+(d?0:+b.data.value),b.o.absolute=d,b.o.diff=d-c.prevValue,b.o.isTotal=a,b.o.missing=0,c.tZ=!0,c.prevValue=d):b.o.missing=1};$.f.qP=function(a){return"value"==a?0<=(Number(this.da().o("diff"))||0):D0.F.qP.call(this,a)};$.I(E0,$.Ey);var J0={};J0.waterfall={xb:32,Ab:2,Fb:[$.ZC,$.aD,$.bD,$.dD,$.gD,$.UC],Db:null,yb:null,wb:$.jy|5767168,ub:"value",tb:"zero"};E0.prototype.Mi=J0;$.Hw(E0,E0.prototype.Mi);$.f=E0.prototype;$.f.Tr=function(){return"waterfall"};$.f.AF=function(){return"value"};$.f.zr=function(){};$.f.fZ=function(a){return+a.o[a.o.isTotal?"absolute":"diff"]};
$.f.k0=function(a,b,c,d){var e=0,g;if(b)for(g=0;g<a.length;g++){var h=a[g].data[b-1];e+=Number(h.o.diff)||0}this.Fa=[];for(g=b;g<=c;g++){for(var k=b=0;k<a.length;k++)h=a[k].data[g],h.o.isTotal||(h.o.stackedZero+=e,h.o.stackedValue+=e,h.o.stackedZeroPrev+=e,h.o.stackedValuePrev+=e,h.o.stackedZeroNext+=e,h.o.stackedValueNext+=e),d.ld(h.o.stackedValue),d.ld(h.o.stackedValuePrev),d.ld(h.o.stackedValueNext),b+=h.o.missing?0:Number(h.o.diff)||0;e+=b;this.Fa.push(e)}};
$.f.nO=function(){E0.F.nO.call(this);this.g?this.g.clear():this.g=$.uj();var a=this.Wa(),b=this.Zn[String($.oa(a))];if(b&&b.length){var c=this.I("connectorStroke");this.g.stroke(c);c=$.bc(c);this.g.parent(this.U());this.g.zIndex(1E3);this.g.clip(this.Pf());var d=this.ua();a=a.hw();var e=b[0].Ph,g=b[0].lastIndex,h;var k=G0(b,e);if((0,window.isNaN)(k))var l=h=window.NaN;else{var m=b[k];k=m.data[e].o;var p=a?m.ca.Os($.n(k.category)?k.category:e):m.ca.Zr;h=$.Dn(F0(this,this.Fa[0]),c);l=k.valueX+p/2}for(var q=
e+1;q<=g;q++){a:{for(k=0;k<b.length;k++)if(!b[k].data[q].o.missing)break a;k=window.NaN}(0,window.isNaN)(k)?k=m=window.NaN:(m=b[k],k=m.data[q].o,p=a?m.ca.Os($.n(k.category)?k.category:e):m.ca.Zr,m=$.Dn(F0(this,this.Fa[q-e-1]),c),k=k.valueX-p/2);if(!(0,window.isNaN)(l)&&!(0,window.isNaN)(h))if((0,window.isNaN)(k)||(0,window.isNaN)(m))continue;else $.Fx(this.g,d,l,h),$.Gx(this.g,d,k,m);k=G0(b,q);(0,window.isNaN)(k)?l=h=window.NaN:(m=b[k],k=m.data[q].o,p=a?m.ca.Os($.n(k.category)?k.category:e):m.ca.Zr,
h=$.Dn(F0(this,this.Fa[q-e]),c),l=k.valueX+p/2)}}};$.f.du=function(a,b){return new D0(this,this,a,b,!0)};var K0={};$.T(K0,0,"dataMode",function(a,b){return $.Cj(xga,a,b||"absolute")});$.T(K0,1,"connectorStroke",$.gp);$.$o(E0,K0);$.f=E0.prototype;$.f.Or=function(){return!0};
$.f.Vl=function(a,b){var c=[];if("categories"==a){this.K={};for(var d=this.mf(),e,g,h,k={},l=0,m=0;m<d.length;m++)e=d[m],g=$.bl("risingFill",1,!1),g=g(e,$.dl,!0,!0),h=$.dn(g),h in k?this.K[k[h]].ca.push(e):(k[h]=l,this.K[l]={ca:[e],type:"rising"},c.push({text:"Increase",iconEnabled:!0,iconFill:g,sourceUid:$.oa(this),sourceKey:l++})),g=$.bl("fallingFill",1,!1),g=g(e,$.dl,!0,!0),h=$.dn(g),h in k?this.K[k[h]].ca.push(e):(k[h]=l,this.K[l]={ca:[e],type:"falling"},c.push({text:"Decrease",iconEnabled:!0,
iconFill:g,sourceUid:$.oa(this),sourceKey:l++})),g=$.bl("fill",1,!1),g=g(e,$.dl,!0,!0),h=$.dn(g),h in k?this.K[k[h]].ca.push(e):(k[h]=l,this.K[l]={ca:[e],type:"total"},c.push({text:"Total",iconEnabled:!0,iconFill:g,sourceUid:$.oa(this),sourceKey:l++}))}else c=E0.F.Vl.call(this,a,b);return c};
$.f.vp=function(a,b){if("categories"==this.Te().xj()){var c=a.Mh(),d=this.K[c];c=d.ca;d=d.type;for(var e,g,h,k,l,m,p=0;p<c.length;p++){e=c[p];g=e.Xf();for(m=[];g.advance();){var q=g.na();g.o("missing")||(h=g.o("isTotal"),k=0<=g.o("diff")&&!h,l=0>g.o("diff")&&!h,(h=h&&"total"==d||k&&"rising"==d||l&&"falling"==d)&&m.push(q))}e.cj(m)}}else return E0.F.vp.call(this,a,b)};$.f.up=function(a,b){if("categories"==this.Te().xj())this.wd();else return E0.F.up.call(this,a,b)};
$.f.Iq=function(a,b){if("default"==this.Te().xj())return E0.F.Iq.call(this,a,b)};$.f.J=function(){var a=E0.F.J.call(this);$.kp(this,K0,a.chart);return a};$.f.$=function(a,b){E0.F.$.call(this,a,b);$.cp(this,K0,a)};$.f.W=function(){$.M(this.g);E0.F.W.call(this)};var L0=E0.prototype;L0.xScale=L0.Wa;L0.yScale=L0.bb;L0.crosshair=L0.dg;L0.xGrid=L0.Oo;L0.yGrid=L0.Qo;L0.xMinorGrid=L0.eq;L0.yMinorGrid=L0.gq;L0.xAxis=L0.gi;L0.getXAxesCount=L0.GB;L0.yAxis=L0.Gj;L0.getYAxesCount=L0.IB;L0.getSeries=L0.We;
L0.lineMarker=L0.vo;L0.rangeMarker=L0.Fo;L0.textMarker=L0.No;L0.palette=L0.ec;L0.markerPalette=L0.kf;L0.hatchFillPalette=L0.Ud;L0.getType=L0.Sa;L0.addSeries=L0.Kk;L0.getSeriesAt=L0.Vh;L0.getSeriesCount=L0.bm;L0.removeSeries=L0.Go;L0.removeSeriesAt=L0.An;L0.removeAllSeries=L0.Np;L0.getPlotBounds=L0.Pf;L0.xZoom=L0.ql;L0.xScroller=L0.Po;L0.getStat=L0.mg;L0.annotations=L0.Jj;L0.getXScales=L0.Ow;L0.getYScales=L0.Pw;L0.data=L0.data;$.I(H0,$.Uy);$.uC[32]=H0;$.f=H0.prototype;$.f.type=32;$.f.flags=263713;$.f.Gg={rising:"path",risingHatchFill:"path",falling:"path",fallingHatchFill:"path",path:"path",hatchFill:"path"};$.f.rs=function(a){var b=a.o("shapes");for(c in b)b[c].clear();var c=0<=a.o("diff");if(a.o("isTotal")){c="path";var d="hatchFill"}else c?(c="rising",d="risingHatchFill"):(c="falling",d="fallingHatchFill");this.NE(a,b[c],b[d])};
$.f.Sf=function(a,b){var c=0<=a.o("diff");if(a.o("isTotal")){c="path";var d="hatchFill"}else c?(c="rising",d="risingHatchFill"):(c="falling",d="fallingHatchFill");var e={};e[c]=e[d]=!0;e=this.Sc.Qc(b,e);this.NE(a,e[c],e[d])};$.qo.waterfall=I0;$.H("anychart.waterfall",I0);}).call(this,$)}
