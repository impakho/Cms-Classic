$(document).ready(function(){
  $("#nav li").hover(
    function() { $(this).addClass("iehover"); },
    function() { $(this).removeClass("iehover"); }
  );
});

myFocus.set({
	id:'banner_box',
	pattern:'mF_YSlider',
	time:5,//切换时间间隔(秒)
	trigger:'click',
	width:996,
	height:219,
	txtHeight:'0'//文字层高度设置(像素),'default'为默认高度，0为隐藏
});

myFocus.set({
	id:'banner_box2',
	pattern:'mF_classicHC',
	time:4,//切换时间间隔(秒)
	trigger:'click',
	width:300,
	height:200,
	txtHeight:'26px'//文字层高度设置(像素),'default'为默认高度，0为隐藏
});

function currentTime(){
var d = new Date(),str = '';
 str += d.getFullYear()+'年';
 str  += d.getMonth() + 1+'月';
 str  += d.getDate()+'日';
 str += d.getHours()+'时'; 
 str  += d.getMinutes()+'分'; 
str+= d.getSeconds()+'秒'; 
return str;}
setInterval(function(){$('#menu21').html(currentTime)},1000);



$(function($){
	$("#switchBox").switchTab();
	
	$("#switchBox2").switchTab({effect: "fade"});
	
	$("#switchBox3").switchTab({titCell: "dt a", trigger: "mouseover", delayTime: 0});
	
	$("#switchBox4").switchTab({titCell: "dt a", effect: "fade", trigger: "mouseover", delayTime: 300});
	
	$("#switchBox5").switchTab({defaultIndex: "1", titOnClassName: "active", titCell: "dt em", mainCell: "dd ul", effect: "slide"});
	
	$(".slideBox").switchTab({trigger: "mouseover", delayTime: 0});
	
	$("#switchBox7").switchTab({defaultIndex: "1", titCell: "dt span", mainCell: "dd", interTime: 5000});
	
	$("#switchBox8").switchTab({titCell: "dt a", effect: "fade", trigger: "mouseover", delayTime: 300, omitLinks: true});
	
});

;(function($){
	$.fn.colorTable = function(option){
		if (!this.is("table")){
			return false;
		}
		var defaultSetting = {
			'even' : '#ccdbe2',
			'odd'  : '#e5eef3'
		};
		var s = $.extend(defaultSetting,option);
		return this.each(function(){
			var $table = $(this);
			$table.find("tbody tr:even").css("background-color",s.even);
			$table.find("tbody tr:odd").css("background-color",s.odd);
		});
	};
})(jQuery);

$(function(){
	$("#table1").colorTable();//给第一个table进行隔行变色处理
	$("#table2").colorTable();
	$("#table3").colorTable();
	$("#table4").colorTable();
	$("#table5").colorTable();
	$("#table6").colorTable();
});
