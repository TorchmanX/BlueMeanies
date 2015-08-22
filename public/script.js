$(function (){
	function speak(textToSpeak, cb) {
		// Create a new instance of SpeechSynthesisUtterance
		var newUtterance = new SpeechSynthesisUtterance();

		// Set the text
		newUtterance.text = textToSpeak;

		newUtterance.lang = "zh-TW";

		// Add this text to the utterance queue
		window.speechSynthesis.speak(newUtterance);

		if (cb) {
			setTimeout(cb, textToSpeak.length * 400);
		}
	}

	var chat = {
		scrollToBottom: function (){
			setTimeout(function (){
				$(".conversation").animate({
					"scrollTop": "+=600"
				});
			}, 200);
		},

		newMessage: function (message){
			$(".cs-template")
				.clone(true)
				.removeClass("cs-template")
				.find(".content")
				.text(message)
				.end()
				.appendTo(".conversation");

			speak(message);

			chat.scrollToBottom();
		},

		newVoiceMessage: function (message){
			$(".voice-template")
				.clone(true)
				.removeClass("voice-template")
				.find(".content")
					.text(message)
				.end()
				.appendTo(".conversation");

			speak(message);

			chat.scrollToBottom();
		},

		newLoadingMessage: function (saidWord){
			var cs = $(".cs-template")
				.clone(true)
				.removeClass("cs-template")
				.appendTo(".conversation");

			chat.scrollToBottom();

			$.post("/answer", {
				"id": $(".conversation").attr("data-session-id"),
				"saidWord": saidWord
			}).success(function (res){
				cs.find(".content").text(res.data.response);
				chat.scrollToBottom();
				speak(res.data.response, function (){
					chat.newUserInput();
				});

				if (res.data.hasOwnProperty("category")){
					var category = res.data.category;
					if (category != null) {
						for (var i = category.length - 1; i >= 0; i--) {
							var cat = category[i];
							$(".categories li[data-id=" + cat + "]")
								.detach()
								.addClass("active")
								.prependTo(".categories ul");
						}
					}
				}

				if (res.data.hasOwnProperty("department")){
					var department = res.data.department;
					if (department != null) {
						for (var i = department.length - 1; i >= 0; i--) {
							var dep = department[i];

							$(".suggestions li[data-id=" + dep + "]")
								.detach()
								.addClass("active")
								.prependTo(".suggestions ul");
						}
					}
				}
			});
		},

		newUserInput: function (cb){
			$(".user-template")
				.clone(true)
				.removeClass("user-template")
				.find(".mic")
					.click(function (){
						var ctrl = this;
						var newRecognition = new webkitSpeechRecognition();

						newRecognition.lang = "cmn-Hant-TW";

						var inputted = function (saidWord){
							$(ctrl).parent()
								.find(".content")
									.text(saidWord)
								.end()
								.removeClass("active")
								.find(".mic")
								.remove();

							if (cb){
								cb();
							}

							chat.newLoadingMessage(saidWord);
						};

						newRecognition.onresult = function (event){
							var resultsLength = event.results.length -1 ;
							// get length of latest results
							var ArrayLength = event.results[resultsLength].length -1;
							// get last word detected
							var saidWord = event.results[resultsLength][ArrayLength].transcript;

							inputted(saidWord);
						};

						window.inputted = inputted;

						newRecognition.start();
					})
				.end()
				.appendTo(".conversation");
			chat.scrollToBottom();
		}
	};

	chat.newVoiceMessage("您好！請問有什麼問題哦？");
	chat.newUserInput(function (){
		chat.newVoiceMessage("好的，我幫你轉接到客服去");
	});



	$(".suggestions li").click(function (){
		$(".suggestions li").removeClass("selected");
		$(this).addClass("selected");

		$(".actions h4").text($(this).text());
		//$(this).popover({
		//	"html": true,
		//	"content": $(".popover-template").html(),
		//	"trigger": "focus",
		//	"placement": "auto top"
		//}).popover("toggle");
	});


	var resize = function (){
		var half = (($(window).height() - $(".header").outerHeight()) / 2);
		$(".conversation, .remark, .categories, .suggestions").css({
			"height": half + "px"
		});

	};

	$(window).resize(resize).load(resize);
	resize();
});