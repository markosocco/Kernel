if(!_.theme_linear_gauge){_.theme_linear_gauge=1;(function($){$.sa($.fa.anychart.themes.defaultTheme,{linearGauge:{padding:10,markerPalette:{items:"circle diamond square triangle-down triangle-up triangle-left triangle-right diagonal-cross pentagon cross v-line star5 star4 trapezium star7 star6 star10".split(" ")},globalOffset:"0%",layout:"vertical",tooltip:{titleFormat:function(){return this.name},format:function(){return this.high?$.RI.call(this):"Value: "+$.CI(this.value)}},scales:[{type:"linear"}],defaultAxisSettings:{enabled:!0,width:"10%",offset:"0%"},
defaultScaleBarSettings:{enabled:!0,width:"10%",offset:"0%",from:"min",to:"max",colorScale:{type:"ordinal-color",inverted:!1,ticks:{maxCount:100}},points:[{height:0,left:0,right:0},{height:1,left:0,right:0}]},defaultPointerSettings:{base:{enabled:!0,selectionMode:"single",width:"10%",offset:"0%",legendItem:{enabled:!0},normal:{stroke:$.OI,fill:$.GI,hatchFill:null,labels:{zIndex:0,position:"center-top"}},hovered:{stroke:$.QI,fill:$.MI,labels:{enabled:null}},selected:{stroke:$.LI,fill:$.LI,labels:{enabled:null}}},
bar:{},rangeBar:{normal:{labels:{format:function(){return $.CI(this.high)}}}},marker:{width:"3%"},tank:{normal:{emptyFill:"#fff 0.3",emptyHatchFill:null},hovered:{emptyFill:$.GI},selected:{emptyFill:$.GI}},thermometer:{normal:{fill:function(){var a=this.sourceColor,b=$.sk(a);return{angle:this.isVertical?0:90,keys:[{color:b},{color:a},{color:b}]}}},width:"3%",bulbRadius:"120%",bulbPadding:"3%"},led:{dimmer:function(a){return $.sk(a)},gap:"1%",size:"2%",count:null,colorScale:{type:"ordinal-color",inverted:!1,
ticks:{maxCount:100}}}}},thermometer:{},tank:{},led:{}});}).call(this,$)}
