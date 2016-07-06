$(document).ready(function(){
	
	var clickData = new Firebase("https://vinyl-fce41.firebaseio.com/");
	var clickCounter = 0;
	
	$('#submit').on('click', function(){
		

		var serialNumber = $('input.serial').val().toLowerCase().trim();

		var artist = serialNumber;
		var placeholder = 'Enter Matrix or Serial Number';

		console.log(artist);

		if(artist === placeholder || ' ' || ''){

			$('#warnMe').openModal();
		}
		else{

			console.log('working on that.. patience please.');
		}

		clickCounter++;

    	clickData.set({
			clickCount: clickCounter
		});

		// var newEpochTime = moment().unix();
  		// var randomString = makeid();
		// var token = 'ptYFGAWGtssxNdaQNqNMoJoKGgAWOCsxkKqKGgPU';
		// var consumerKey = 'neklIMvpFercqywbfAvz';
		// var consumerSecret = 'ssgECWeOUPdtNFKGbHAheaufjbwcAXsE&';
		// Request Token URL https://api.discogs.com/oauth/request_token
		// Authorize URL https://www.discogs.com/oauth/authorize
		// Access Token URL https://api.discogs.com/oauth/access_token
		//User-Agent: some_user_agent

		// if(artist == "" || null){
		// 	$('.switcher').html('<form class="col s12 search"><div class="row"><div class="input-field col s12"><input id="icon_prefix" type="text" class="validate serial"><label for="icon_prefix">Please enter matrix number</label><a class="waves-effect waves-light btn pink accent-2" id="submit">Search</a></div></div></form>');
		// }else{
		// 	$('.switcher').html('<div class="progress"><div class="indeterminate"></div></div>');
		// 	$('form.search').after(artist);
		// }

		// var oauth = OAuth({
		//     consumer: {
		//         public: 'neklIMvpFercqywbfAvz',
		//         secret: 'ssgECWeOUPdtNFKGbHAheaufjbwcAXsE&'
		//     },
		//     signature_method: 'PLAINTEXT'
		// });

		// var request_data = {
		//     url: 'https://api.discogs.com/oauth/request_token',
		//     method: 'POST',
		//     data: {
		//         oauth_consumer_key: consumerKey,
		//         oauth_nonce: randomString,
		//         oauth_signature: consumerSecret,
		//         oauth_signature_method: 'PLAINTEXT',
		//         oauth_timestamp: newEpochTime,
		//         oauth_callback: apiReturn()
		//     }
		// };

  //   	$.ajax({
		//     url: 'https://api.discogs.com/database/search?type=release&q=-format:"album"+format:"Vinyl"&artist='+artist+'',
		//     type: 'GET',
		//     //data: artist,
		//     //headers: oauth.toHeader(oauth.authorize(request_data.data))
		// }).done(function(data) {
		    
		//     console.log("test");
		//     console.log(data);
		//     $('.switcher').html('Data complete:', data);

		// });
		//$('input.serial').empty();

		// apiReturn();
    }); 
    return false;

	clickData.on("value", function(snapshot) {

		console.log(snapshot.val());

		$(".vinylCount").html(snapshot.val().clickCount);

		clickCounter = snapshot.val().clickCount;

	}, function (errorObject) {

	  	console.log("The read failed: " + errorObject.code);
	
	});
});



// function apiReturn(){
// 	console.log("return");
// }

// function makeid(){
//   var text = "";
//   var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

//   for( var i=0; i < 5; i++ )
//     text += possible.charAt(Math.floor(Math.random() * possible.length));

//   return text;
// };