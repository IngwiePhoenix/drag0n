// Bar-control

var bar = {
	clear:function(){
		$(".home").hide();
		$(".DIuser").hide();
		$(".characters").hide();
		$('.manage').hide();
	},
	home:function(){
		this.clear();
		$(".home").show();
	},
	chars:function(){
		this.clear();
		$(".characters").show();
	},
	user:function(){
		this.clear();
		$(".DIuser").show();
	},
	shoutbox:function(){
		$('#shoutbox').show();
	},
	shoutboxClose:function(){
		$('#shoutbox').hide();		
	},
	manage:function(){
		this.clear();
		$('.manage').show();
	}
}
	