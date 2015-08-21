$(function (){
	var chat = {
		newMessage: function (message){
			$(".cs-template")
				.clone(true)
				.removeClass("cs-template")
				.find(".content")
					.text(message)
				.end()
				.appendTo(".conversation");
		},

		newUserInput: function (){
			$(".user-template")
				.clone(true)
				.removeClass("user-template")
				.appendTo(".conversation");
		}
	};

	chat.newMessage("您好！請問有什麼問題哦？");
	chat.newUserInput();



	var resize = function (){
		$(".conversation, .remark").css({
			"height": (($(window).height() - $(".header").outerHeight()) / 2) + "px"
		});
	};

	$(window).resize(resize).load(resize);
	resize();
});