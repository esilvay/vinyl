var clickData = new Firebase("https://vinyl-fce41.firebaseio.com/");
var clickCounter = 1000;

$(document).ready(function(){

	$('#submit').on('click', function(){
		
		var serialNumber = $('input.serial').val().toLowerCase().trim();

		var artist = serialNumber;
		var placeholder = 'Enter Matrix or Serial Number';

		var consumerKey = 'aQGJBdrdzLeNvfeUfjqa';
		var consumerSecret = 'EEomfdgpbHRBxJisxdnwmnATikUzjUNV';
		var oauth = OAuth({
			consumer: {
				public: 'aQGJBdrdzLeNvfeUfjqa',
				secret: 'EEomfdgpbHRBxJisxdnwmnATikUzjUNV'
			},
			signature_method: 'PLAINTEXT'
		});

		var request_data = {
			url: 'https://api.discogs.com/oauth/request_token',
			method: 'GET',
			data:{
				oauth_version:"1.0a",
				"User-Agent": navigator.userAgent
			}
		};

		var head = oauth.toHeader(oauth.authorize(request_data));
		var hdrs ={
				"Content-Type": "application/x-www-form-urlencoded",
				"Authorization": head.Authorization
		};
		console.log(hdrs);
		$.ajax({
			url: request_data.url,
			type: request_data.method,
			dataType: "application/x-www-form-urlencoded",
			headers: hdrs,
			success: function(result){
				console.log(result)
			}
		}).done(function(data) {
			//process your data here
			console.log(data);
		});
		// console.log(artist);

		// if(artist === placeholder || ' ' || ''){

		// 	$('#warnMe').openModal();
		// }
		// else{

		// 	console.log('working on that.. patience please.');
		// }

		clickCounter++;

    	clickData.set({
			clickCount: clickCounter
		});
    }); 
    return false;
});

clickData.on("value", function(snapshot) {

	//console.log(snapshot.val());
	var number = snapshot.val().clickCount;
	var newNumber = number.toLocaleString('en');

	$(".vinylCount").html(newNumber);

	clickCounter = snapshot.val().clickCount;

}, function (errorObject) {

  	console.log("The read failed: " + errorObject.code);

});

function apiReturn(){
	console.log("return");
}

var handleToken = function (tokenObject){
	console.log(tokenObject)
};