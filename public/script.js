$(function (){
	function speak(textToSpeak, cb) {
		// Create a new instance of SpeechSynthesisUtterance
		var newUtterance = new SpeechSynthesisUtterance();

		// Set the text
		newUtterance.text = textToSpeak;

		newUtterance.lang = "zh-TW";

		newUtterance.addEventListener('end', cb);

		// Add this text to the utterance queue
		window.speechSynthesis.speak(newUtterance);
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
				//console.log(res);
				cs.find(".content").text(res.data.response);
				chat.scrollToBottom();
				speak(res.data.response, function (){
					chat.newUserInput();
				});
			});
		},

		newUserInput: function (){
			$(".user-template")
				.clone(true)
				.removeClass("user-template")
				.find(".mic")
					.click(function (){
						var ctrl = this;
						var newRecognition = new webkitSpeechRecognition();

						newRecognition.lang = "cmn-Hant-TW";

						newRecognition.onresult = function (event){
							var resultsLength = event.results.length -1 ;
							// get length of latest results
							var ArrayLength = event.results[resultsLength].length -1;
							// get last word detected
							var saidWord = event.results[resultsLength][ArrayLength].transcript;

							$(ctrl).parent()
								.find(".content")
									.text(saidWord)
								.end()
								.removeClass("active")
								.find(".mic")
									.remove();

							chat.newLoadingMessage(saidWord);
						};

						newRecognition.start();
					})
				.end()
				.appendTo(".conversation");
			chat.scrollToBottom();
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