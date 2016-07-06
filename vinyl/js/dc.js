var discogs = require('discogs-client');
 
var client = discogs({userAgent: 'Vinyl/1.0 +https://www.ericsilvay.com'});
 
client.artist('Lamb Of God', function(err, artist) {
    console.log(artist.name); // Lamb Of God 
});