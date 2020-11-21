//Br
var siteWidth;
var siteHeight;

if( typeof( window.innerWidth ) == 'number' ) { 

//Non-IE 

siteWidth = window.innerWidth;
siteHeight = window.innerHeight; 

} else if( document.documentElement && 

( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) { 

//IE 6+ in 'standards compliant mode' 

siteWidth = document.documentElement.clientWidth; 
siteHeight = document.documentElement.clientHeight; 

} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) { 

//IE 4 compatible 

siteWidth = document.body.clientWidth; 
siteHeight = document.body.clientHeight; 
}