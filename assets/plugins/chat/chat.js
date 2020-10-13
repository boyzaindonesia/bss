/*

Copyright (c) 2009 Anant Garg (anantgarg.com | inscripts.com)

This script may be used for non-commercial purposes only. For any
commercial purposes, please contact the author at 
anant.garg@inscripts.com

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

*/
var windowFocus = true;
var username;
var chatHeartbeatCount = 0;
var minChatHeartbeat = 2000;
var maxChatHeartbeat = 33000;
var chatHeartbeatTime = minChatHeartbeat;
var originalTitle;
var blinkOrder = 0;

var chatboxFocus = new Array();
var newMessages = new Array();
var newMessagesWin = new Array();
var chatBoxes = new Array();

$(document).ready(function(){
	originalTitle = document.title;
	startChatSession();

	$([window, document]).blur(function(){
		windowFocus = false;
	}).focus(function(){
		windowFocus = true;
		document.title = originalTitle;
	});
});

function restructureChatBoxes() {
	align = 0;
	for (x in chatBoxes) {
		chatboxtitle = chatBoxes[x];

		if ($("#chatbox_"+chatboxtitle).css('display') != 'none') {
			if (align == 0) {
				$("#chatbox_"+chatboxtitle).css('right', '20px');
			} else {
				width = (align)*(300+7)+20;
				$("#chatbox_"+chatboxtitle).css('right', width+'px');
			}
			align++;
		}
	}
}

function chatWith(chatuser) {
	createChatBox(chatuser);
	$("#chatbox_"+chatuser+" .chatboxtextarea").focus();
}

function createChatBox(chatboxtitle,minimizeChatBox) {
	if ($("#chatbox_"+chatboxtitle).length > 0) {
		if ($("#chatbox_"+chatboxtitle).css('display') == 'none') {
			$("#chatbox_"+chatboxtitle).css('display','block');
			restructureChatBoxes();
		}
		$("#chatbox_"+chatboxtitle+" .chatboxtextarea").focus();
		return;
	}

	$(" <div />" ).attr("id","chatbox_"+chatboxtitle)
	.addClass("panel chatbox")
	.html('<div class="panel-heading chatboxhead"><div class="right-content"><div class="btn btn-sm" onclick="javascript:toggleChatBoxGrowth(\''+chatboxtitle+'\')"><i class="fa fa-minus"></i></div><div class="btn btn-sm" onclick="javascript:closeChatBox(\''+chatboxtitle+'\')"><i class="fa fa-times"></i></div></div><h3 class="panel-title">'+chatboxtitle+'</h3></div><div class="chatboxcontainer"><div class="panel-body chat-wrap scroll-chatbox"><ul class="media-list media-xs media-dotted media-chat chatboxcontent"></ul></div><div class="panel-footer"><textarea class="form-control no-resize chatboxtextarea" onkeydown="javascript:return checkChatBoxInputKey(event,this,\''+chatboxtitle+'\');" rows="2" placeholder="Type and press enter"></textarea></div></div>')
	.appendTo($( "body" ));
			   
	$("#chatbox_"+chatboxtitle).css('bottom', '0px');
	
	chatBoxeslength = 0;

	for (x in chatBoxes) {
		if ($("#chatbox_"+chatBoxes[x]).css('display') != 'none') {
			chatBoxeslength++;
		}
	}

	if (chatBoxeslength == 0) {
		$("#chatbox_"+chatboxtitle).css('right', '20px');
	} else {
		width = (chatBoxeslength)*(300+7)+20;
		$("#chatbox_"+chatboxtitle).css('right', width+'px');
	}
	
	chatBoxes.push(chatboxtitle);

	if (minimizeChatBox == 1) {
		minimizedChatBoxes = new Array();

		if ($.cookie('chatbox_minimized')) {
			minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
		}
		minimize = 0;
		for (j=0;j<minimizedChatBoxes.length;j++) {
			if (minimizedChatBoxes[j] == chatboxtitle) {
				minimize = 1;
			}
		}

		if (minimize == 1) {
			$('#chatbox_'+chatboxtitle+' .chatboxcontainer').css('display','none');
		}
	}

	chatboxFocus[chatboxtitle] = false;

	$("#chatbox_"+chatboxtitle+" .chatboxtextarea").blur(function(){
		chatboxFocus[chatboxtitle] = false;
		$("#chatbox_"+chatboxtitle+" .chatboxtextarea").removeClass('chatboxtextareaselected');
	}).focus(function(){
		chatboxFocus[chatboxtitle] = true;
		newMessages[chatboxtitle] = false;
		$('#chatbox_'+chatboxtitle+' .chatboxhead').removeClass('chatboxblink');
		$("#chatbox_"+chatboxtitle+" .chatboxtextarea").addClass('chatboxtextareaselected');
	});

	// $("#chatbox_"+chatboxtitle).click(function() {
	// 	if ($('#chatbox_'+chatboxtitle+' .chatboxcontainer').css('display') != 'none') {
	// 		$("#chatbox_"+chatboxtitle+" .chatboxtextarea").focus();
	// 	}
	// });

	$("#chatbox_"+chatboxtitle).show();
}


function chatHeartbeat(){

	var itemsfound = 0;
	
	if (windowFocus == false) {
 
		var blinkNumber = 0;
		var titleChanged = 0;
		for (x in newMessagesWin) {
			if (newMessagesWin[x] == true) {
				++blinkNumber;
				if (blinkNumber >= blinkOrder) {
					document.title = x+' says...';
					titleChanged = 1;
					break;	
				}
			}
		}
		
		if (titleChanged == 0) {
			document.title = originalTitle;
			blinkOrder = 0;
		} else {
			++blinkOrder;
		}

	} else {
		for (x in newMessagesWin) {
			newMessagesWin[x] = false;
		}
	}

	for (x in newMessages) {
		if (newMessages[x] == true) {
			if (chatboxFocus[x] == false) {
				//FIXME: add toggle all or none policy, otherwise it looks funny
				$('#chatbox_'+x+' .chatboxhead').toggleClass('chatboxblink');
			}
		}
	}
	
	$.ajax({
	  url: MOD_URL+"chat/me/?action=chatheartbeat",
	  cache: false,
	  dataType: "json",
	  success: function(data) {

		$.each(data.items, function(i,item){
			if (item)	{ // fix strange ie bug

				chatboxtitle = item.f;

				if ($("#chatbox_"+chatboxtitle).length <= 0) {
					createChatBox(chatboxtitle);
				}
				if ($("#chatbox_"+chatboxtitle).css('display') == 'none') {
					$("#chatbox_"+chatboxtitle).css('display','block');
					restructureChatBoxes();
				}
				
				if (item.s == 1) {
					item.f = username;
				}

				if (item.s == 2) {
					// $("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<li class="media"><p class="small no-margin"><em>'+item.m+'<em></p></li>');
				} else {
					newMessages[chatboxtitle] = true;
					newMessagesWin[chatboxtitle] = true;
					if(username == item.f){
						$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<li class="media"><div class="pull-right"><img class="media-object img-circle" src="'+item.i+'" alt=""></div><div class="media-body me"><p class="name"><small>Me</small></p><p class="small">'+item.m+'</p><p><small class="timeago" title="'+item.t+'">'+item.t+'</small></p></div></li>');
					} else {
						$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<li class="media"><div class="pull-left"><img class="media-object img-circle" src="'+item.i+'" alt=""></div><div class="media-body"><p class="name"><small>'+item.f+'</small></p><p class="small">'+item.m+'</p><p class="text-danger"><small class="timeago" title="'+item.t+'">'+item.t+'</small></p></div></li>');
					}
				}

				$(".timeago").timeago();

				$('#chatbox_'+chatboxtitle+' .scroll-chatbox').slimscroll({ scrollBy: $('#chatbox_'+chatboxtitle+' .chatboxcontent').height()+' px' });
				itemsfound += 1;
			}
		});

		chatHeartbeatCount++;

		if (itemsfound > 0) {
			chatHeartbeatTime = minChatHeartbeat;
			chatHeartbeatCount = 1;
		} else if (chatHeartbeatCount >= 10) {
			chatHeartbeatTime *= 2;
			chatHeartbeatCount = 1;
			if (chatHeartbeatTime > maxChatHeartbeat) {
				chatHeartbeatTime = maxChatHeartbeat;
			}
		}
		
		setTimeout('chatHeartbeat();',chatHeartbeatTime);
	}});
}

function closeChatBox(chatboxtitle) {
	$('#chatbox_'+chatboxtitle).css('display','none');
	restructureChatBoxes();

	$.post(MOD_URL+"chat/me/?action=closechat", { chatbox: chatboxtitle} , function(data){	
	});

}

function toggleChatBoxGrowth(chatboxtitle) {
	if ($('#chatbox_'+chatboxtitle+' .chatboxcontainer').css('display') == 'none') {  
		
		var minimizedChatBoxes = new Array();
		
		if ($.cookie('chatbox_minimized')) {
			minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
		}

		var newCookie = '';

		for (i=0;i<minimizedChatBoxes.length;i++) {
			if (minimizedChatBoxes[i] != chatboxtitle) {
				newCookie += chatboxtitle+'|';
			}
		}

		newCookie = newCookie.slice(0, -1)


		$.cookie('chatbox_minimized', newCookie);
		$('#chatbox_'+chatboxtitle+' .chatboxcontainer').css('display','block');
		$('#chatbox_'+chatboxtitle+' .scroll-chatbox').slimscroll({ scrollBy: $('#chatbox_'+chatboxtitle+' .chatboxcontent').height()+' px' });
	} else {
		
		var newCookie = chatboxtitle;

		if ($.cookie('chatbox_minimized')) {
			newCookie += '|'+$.cookie('chatbox_minimized');
		}


		$.cookie('chatbox_minimized',newCookie);
		$('#chatbox_'+chatboxtitle+' .chatboxcontainer').css('display','none');
	}
	
}

function checkChatBoxInputKey(event,chatboxtextarea,chatboxtitle) {
	 
	if(event.keyCode == 13 && event.shiftKey == 0)  {
		message = $(chatboxtextarea).val();
		message = message.replace(/^\s+|\s+$/g,"");

		$(chatboxtextarea).val('');
		$(chatboxtextarea).focus();
		if (message != '') {
			// '+username+'

			$.post(MOD_URL+"chat/me/?action=sendchat", {to: chatboxtitle, message: message} , function(data){
				photo = data.photo;
				timestamp = data.timestamp;
				message = message.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;");
				$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<li class="media"><div class="pull-right"><img class="media-object img-circle" src="'+photo+'" alt=""></div><div class="media-body me"><p class="name"><small>Me</small></p><p class="small">'+message+'</p><p><small class="timeago" title="'+timestamp+'">'+timestamp+'</small></p></div></li>');
				$('#chatbox_'+chatboxtitle+' .scroll-chatbox').slimscroll({ scrollBy: $('#chatbox_'+chatboxtitle+' .chatboxcontent').height()+' px' });
				$(".timeago").timeago();
			});

		}
		chatHeartbeatTime = minChatHeartbeat;
		chatHeartbeatCount = 1;

		return false;
	}
}

function startChatSession(){  
	$.ajax({
	  url: MOD_URL+"chat/me/?action=startchatsession",
	  cache: false,
	  dataType: "json",
	  success: function(data) {
 
		username = data.username;

		$.each(data.items, function(i,item){
			if (item)	{ // fix strange ie bug

				chatboxtitle = item.f;

				if ($("#chatbox_"+chatboxtitle).length <= 0) {
					createChatBox(chatboxtitle,1);
				}
				
				if (item.s == 1) {
					item.f = username;
				}

				if (item.s == 2) {
					// $("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<li class="media"><p class="small no-margin"><em>'+item.m+'<em></p></li>');
				} else {
					if(username == item.f){
						$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<li class="media"><div class="pull-right"><img class="media-object img-circle" src="'+item.i+'" alt=""></div><div class="media-body me"><p class="name"><small>Me</small></p><p class="small">'+item.m+'</p><p><small class="timeago" title="'+item.t+'">'+item.t+'</small></p></div></li>');
					} else {
						$("#chatbox_"+chatboxtitle+" .chatboxcontent").append('<li class="media"><div class="pull-left"><img class="media-object img-circle" src="'+item.i+'" alt=""></div><div class="media-body"><p class="name"><small>'+item.f+'</small></p><p class="small">'+item.m+'</p><p class="text-danger"><small class="timeago" title="'+item.t+'">'+item.t+'</small></p></div></li>');
					}
				}
			}
		});
		
		$(".timeago").timeago();

		for (i=0;i<chatBoxes.length;i++) {
			chatboxtitle = chatBoxes[i];
			$('#chatbox_'+chatboxtitle+' .scroll-chatbox').slimscroll({ scrollBy: $("#chatbox_"+chatboxtitle+" .chatboxcontent").height()+' px' });
			setTimeout("$('#chatbox_'+chatboxtitle+' .scroll-chatbox').slimscroll({ scrollBy: $('#chatbox_'+chatboxtitle+' .chatboxcontent').height()+' px' });", 100); // yet another strange ie bug
		}
	
		setTimeout('chatHeartbeat();',chatHeartbeatTime);
		
	}});
}