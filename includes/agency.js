//functions.js
//Create a boolean variable to check for a valid IE instance.
var xmlhttp = false;

//Check if we are using IE.
try {
	//If the javascript version is greater than 5.
	xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
	//If not, then use the older active x object.
	try {
	//If we are using IE.
	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
	//Else we must be using a non-IE browser.
	xmlhttp = false;
	}
}
//If we are using a non-IE browser, create a JavaScript instance of the object.
if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
	xmlhttp = new XMLHttpRequest();
}

var xmlhttp2 = false;

//Check if we are using IE.
try {
	//If the javascript version is greater than 5.
	xmlhttp2 = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
	//If not, then use the older active x object.
	try {
	//If we are using IE.
	xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
	//Else we must be using a non-IE browser.
	xmlhttp2 = false;
	}
}
//If we are using a non-IE browser, create a JavaScript instance of the object.
if (!xmlhttp2 && typeof XMLHttpRequest != 'undefined') {
	xmlhttp2 = new XMLHttpRequest();
}





//Function to process an XMLHttpRequest.
function processajax (serverPage, obj, getOrPost, str){
	//Get an XMLHttpRequest object for use.  NOT ACTIVE
	// xmlhttp = getxmlhttp ();
	// Show "Loading...."
	// obj.style.visibility = 'visible';

	obj.innerHTML = '<br /><br /><div align="center"><img src="./images/ajax-loader3.gif" alt="" /><br /><br />processing...</div>';

	if (getOrPost == "get"){
		xmlhttp.open("GET", serverPage);
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				obj.innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.send(null);
	} else {
		xmlhttp.open("POST", serverPage, true);
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				obj.innerHTML = xmlhttp.responseText;
			}
		}
		xmlhttp.send(str);
	}
}


function loaddiv(divtofill, divwithcontent, url, ajax2) {  // IMPORTANT: MUST HAVE A ? OR & AFTER AN URL
	// the div has to either be filled with an url or content from another div
	if(divwithcontent) {
   		document.getElementById(divtofill).innerHTML = document.getElementById(divwithcontent).innerHTML;
	} else if(url) {
		var obj = document.getElementById(divtofill);
		// obj3.style.height = '100%';
		obj.innerHTML = '<div align="center" style="padding-top:20px"><img src="./images/ajax-loader3.gif" /></div>';

		var serverPage = url + "sid="+Math.random();
		
		if(ajax2) {
			xmlhttp2.open("GET", serverPage);
			xmlhttp2.onreadystatechange = function() {
	
				if (xmlhttp2.readyState == 4 && xmlhttp2.status == 200) {
					obj.innerHTML = xmlhttp2.responseText;
				}
			}
			xmlhttp2.send(null);
		} else {
			xmlhttp.open("GET", serverPage);
			xmlhttp.onreadystatechange = function() {
	
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					obj.innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.send(null);
		}
	}
}
function loading(loadingdiv) {
	document.getElementById(loadingdiv).innerHTML = '<img src="images/loadingAnimation.gif">';
}
/*
function popup(thediv, url=false) {
	document.getElementById(thediv).innerHTML = document.getElementById(thediv).innerHTML;
}


function addslashes( str ) {
    return (str+'').replace(/([\\"'])/g, "\\$1").replace(/\0/g, "\\0");
}


*/




//Function to validate the addtask form.
function validatetask (thevalue, thename){

	var nowcont = true;

	return nowcont;
}

var aok;

//Functions to submit a form.
function getformvalues (fobj, valfunc){

	var str = "";
	aok = true;
	var val;

	//Run through a list of all objects contained within the form.
	for(var i = 0; i < fobj.elements.length; i++){
		if(valfunc) {
			if (aok == true){
				         //Build Send String
				 if(fobj.elements[i].type == "text"){ //Handle Textbox's
						  str = str + fobj.elements[i].name + "=" + encodeURIComponent(fobj.elements[i].value) + "&";
				 }else if(fobj.elements[i].type == "textarea"){ //Handle textareas
						  str = str + fobj.elements[i].name + "=" + encodeURIComponent(fobj.elements[i].value) + "&";
				 }else if(fobj.elements[i].type == "checkbox"){ //Handle checkbox's
				 		 if(fobj.elements[i].checked == true) {
						 	str = str + fobj.elements[i].name + "=" + fobj.elements[i].value + "&";
						 }
				 }else if(fobj.elements[i].type == "radio"){ //Handle Radio buttons
						  if(fobj.elements[i].checked==true){
							 str = str + fobj.elements[i].name + "=" + fobj.elements[i].value + "&";
						  }
				 }else{
						  //finally, this should theoretically this is a select box.
						  str = str + fobj.elements[i].name + "=" + encodeURIComponent(fobj.elements[i].value) + "&";
				 }

				// val = valfunc (fobj.elements[i].value,fobj.elements[i].name);
				//if (val == false){
					//aok = false;
				//}
			}
		}
		// str += fobj.elements[i].name + "=" + escape(fobj.elements[i].value) + "&";
	}
	//Then return the string values.
	return str;
}


function submitform (theform, serverPage, objID, valfunc, proposalid){
	var file = serverPage;
	var str = getformvalues(theform,valfunc);
	//If the validation is ok.
	if (aok == true){
		obj = document.getElementById(objID);
		processajax (serverPage, obj, "post", str, proposalid);
	}
	if (objID == 'process') { // if this is just for processing
		var process = document.getElementById('process');
		process.innerHTML = '';
		process.style.visibility = 'hidden';
	}
}

/*
function switchlink(div) {
	var obj = document.getElementById(div);
	obj.innerHTML = '<b>VIEW</b>';
}
*/
function wopen(url)
{
// Fudge factors for window decoration space.
 // In my tests these work well on all platforms & browsers.
w = 550;
h = 300;
 var win = window.open(url,
  'Attach_File', 'width=' + w + ', height=' + h + ', ' +
  'location=no, menubar=no, ' +
  'status=no, toolbar=no, scrollbars=no, resizable=no');
 win.resizeTo(w, h);
 win.focus();
}
function wopenlarge(url)
{
// Fudge factors for window decoration space.
 // In my tests these work well on all platforms & browsers.
w = 1000;
h = 700;
 var win = window.open(url,
  'Attach_File', 'width=' + w + ', height=' + h + ', ' +
  'location=no, menubar=no, ' +
  'status=no, toolbar=no, scrollbars=yes, resizable=yes');
 win.resizeTo(w, h);
 win.focus();
}

function cardimageswap(image) {
	document.getElementById('primaryspot').innerHTML='<img src="'+image+'" width="200">';
	document.getElementById('primaryspot').style.display='block';
	document.getElementById('primarypic').style.display='none';
}
function cardimageswapout() {
	document.getElementById('primaryspot').style.display='none';
	document.getElementById('primarypic').style.display='block';
}

function changecss(theClass,element,value) {

	 var cssRules;

	 var added = false;
	 for (var S = 0; S < document.styleSheets.length; S++){

    if (document.styleSheets[S]['rules']) {
	  cssRules = 'rules';
	 } else if (document.styleSheets[S]['cssRules']) {
	  cssRules = 'cssRules';
	 } else {
	  //no rules found... browser unknown
	 }

	  for (var R = 0; R < document.styleSheets[S][cssRules].length; R++) {
	   if (document.styleSheets[S][cssRules][R].selectorText == theClass) {
	    if(document.styleSheets[S][cssRules][R].style[element]){
	    document.styleSheets[S][cssRules][R].style[element] = value;
	    added=true;
		break;
	    }
	   }
	  }
	  if(!added){
	  if(document.styleSheets[S].insertRule){
			  document.styleSheets[S].insertRule(theClass+' { '+element+': '+value+'; }',document.styleSheets[S][cssRules].length);
			} else if (document.styleSheets[S].addRule) {
				document.styleSheets[S].addRule(theClass,element+': '+value+';');
			}
	  }
	 }
}
function setgendercss(gender) {
	if(gender == 'O' || !gender) {
		changecss('.maleclass', 'display', '');
		changecss('.femaleclass', 'display', '');
	} else if(gender == 'M') {
		changecss('.maleclass', 'display', '');
		changecss('.femaleclass', 'display', 'none');
	} else if(gender == 'F') {
		changecss('.maleclass', 'display', 'none');
		changecss('.femaleclass', 'display', '');
	}



}
function removeItems(originalArray, itemToRemove) {  // removes an item from an array
	var j = 0;
	while (j < originalArray.length) {
		if (originalArray[j] == itemToRemove) {
			originalArray.splice(j, 1);
			// return originalArray;
		} else {
			j++;
		}
	}
	return originalArray;
}

function addProcessCheck(originalArray, friendid, theid) { // add ids to array
	// alert(theid);
	var obj = document.getElementById(theid);
	if(obj.checked) {
		originalArray.push(friendid);
	} else {
		removeItems(originalArray, friendid);
	}
	return originalArray;
}

function checkAll(doarray, submitpage) {
	var c = new Array();
	c = document.getElementsByTagName('input');
	for (var i = 0; i < c.length; i++) 	{
		if (c[i].type == 'checkbox') {
			c[i].checked = true;
			if(doarray) {
				var str = c[i].id;
				if(doarray == '_') {
					str = str.substring(str.indexOf('_'), str.length);
				}
				str = str.replace(doarray, "");
				addProcessCheck(window.ProcessArray, str, c[i].id);
				if(submitpage) {
					var theid = c[i].value;
				} else {
					var theid = c[i].name.replace("addme", "");
				}
				lightbox_check('lightbox', c[i], theid);
			}
		}
	}
}
function uncheckAll(submitpage) {
	var c = new Array();
	c = document.getElementsByTagName('input');
	for (var i = 0; i < c.length; i++) 	{
		if (c[i].type == 'checkbox') {
			c[i].checked = false;
			if(checkCookie('lightbox')) { // if we are unchecking boxes on the search results, remove from lightbox cookie
				if(submitpage) {
					var theid = c[i].value;
				} else {
					var theid = c[i].name.replace("addme", "");
				}
				lightbox_check('lightbox', c[i], theid);
			}
		}
	}
	window.ProcessArray.length = 0;
}

function checkAllRoles(role) {
	var c = new Array();
	c = document.getElementsByTagName('input');
	for (var i = 0; i < c.length; i++) 	{
		if (c[i].type == 'checkbox') {
			var str = c[i].id;
			if(role) {
				/* if(role == '_') {
					str = str.substring(str.indexOf('_'), str.length);
				} */
				var x = str.split("_");
				if(x[0]  == role) {
					c[i].checked = true;
					//str = str.replace(role, "");
					// addProcessCheck(window.ProcessArray, str, c[i].id); // this may not be doing anything....
					lightbox_check('lightbox', c[i], str);
				}
			} else {
				c[i].checked = true;
				lightbox_check('lightbox', c[i], str);
			}
		}
	}
}
function uncheckAllRoles(role) {
	var c = new Array();
	c = document.getElementsByTagName('input');
	for (var i = 0; i < c.length; i++) 	{
		if (c[i].type == 'checkbox') {
			var str = c[i].id;
			if(role) {
				var x = str.split("_");
				if(x[0]  == role) {
					c[i].checked = false;
					lightbox_check('lightbox', c[i], str);
				}
			} else {
				c[i].checked = false;
				lightbox_check('lightbox', c[i], str);
			}
		}
	}
}


function checkAllToggle(whichlink) {
	if(whichlink == 'switch_to_uncheck') {
		document.getElementById('check_uncheck_all').innerHTML = '<input type="button" value="unselect all" onclick="uncheckAll(\'talent_\'); checkAllToggle(\'switch_to_check\')">';
	} else {
		document.getElementById('check_uncheck_all').innerHTML = '<input type="button" value="select all" onclick="checkAll(\'talent_\'); checkAllToggle(\'switch_to_uncheck\')">';
	}
}
function checkAllToggle2(whichlink) {
	if(whichlink == 'switch_to_uncheck') {
		document.getElementById('check_uncheck_all').innerHTML = '<a href="javascript:void(0)" onclick="uncheckAllRoles(); checkAllToggle2(\'switch_to_check\')" class="AGENCY_graybutton">uncheck all</a>';
		document.getElementById('check_uncheck_all2').innerHTML = '<a href="javascript:void(0)" onclick="uncheckAllRoles(); checkAllToggle2(\'switch_to_check\')" class="AGENCY_graybutton">uncheck all</a>';
	} else {
		document.getElementById('check_uncheck_all').innerHTML = '<a href="javascript:void(0)" onclick="checkAllRoles(); checkAllToggle2(\'switch_to_uncheck\')" class="AGENCY_graybutton">check all</a>';
		document.getElementById('check_uncheck_all2').innerHTML = '<a href="javascript:void(0)" onclick="checkAllRoles(); checkAllToggle2(\'switch_to_uncheck\')" class="AGENCY_graybutton">check all</a>';
	}
}

function checkAllToggle3(whichlink, role) {
	if(whichlink == 'switch_to_uncheck') {
		document.getElementById('check_uncheck_'+role).innerHTML = '<a href="javascript:void(0)" onclick="uncheckAllRoles('+role+'); checkAllToggle3(\'switch_to_check\', '+role+')" class="AGENCY_graybutton">uncheck all in role</a>';
	} else {
		document.getElementById('check_uncheck_'+role).innerHTML = '<a href="javascript:void(0)" onclick="checkAllRoles('+role+'); checkAllToggle3(\'switch_to_uncheck\', '+role+')" class="AGENCY_graybutton">check all in role</a>';
	}
}

function checkGroupBtn(identifier, checkit, whichlink, theID, word) {
	
	// identifier is the prefix on the id tag for the checkboxes in the group
	// checkit is set to true or false; true will check all in group; false will uncheck group
	// whichlink indicates whether the toggle button is being switch to check or uncheck
	// theID is the id of the check/uncheck button so it can be toggled
	
	if(!word) {
		var word = 'group';
	}
		
	var c = new Array();
	c = document.getElementsByTagName('input');
	for (var i = 0; i < c.length; i++) 	{
		var str = c[i].id;
		if (c[i].type == 'checkbox' && (str.substr(0, identifier.length) == identifier) ) {
			c[i].checked = checkit;
		}
	}
	
	if(whichlink == 'switch_to_uncheck') {
		document.getElementById(theID).innerHTML = '<input type="button" value="unselect '+word+'" onclick="checkGroupBtn(\''+identifier+'\', false, \'switch_to_check\', \''+theID+'\', \''+word+'\')">';
	} else {
		document.getElementById(theID).innerHTML = '<input type="button" value="select '+word+'" onclick="checkGroupBtn(\''+identifier+'\', true, \'switch_to_uncheck\', \''+theID+'\', \''+word+'\')">';
	}	
}

function generate_email_list() {
	
	var c = new Array();
	var e_list = new Array();
	
	c = document.getElementsByTagName('input');
	for (var i = 0; i < c.length; i++) 	{
		if (c[i].type == 'checkbox') {
			if(c[i].checked) {
				e_list.push(document.getElementById('emails_'+c[i].value).value);
			}
		}
	}
	
	e_list = uniqueArr(e_list);
	return e_list.join(', ');
}

function checkGroup(identifier, checkit) {
	
	// identifier is the prefix on the id tag for the checkboxes in the group
	// checkit is set to true or false; true will check all in group; false will uncheck group
	
	var c = new Array();
	c = document.getElementsByTagName('input');
	for (var i = 0; i < c.length; i++) 	{
		var str = c[i].id;
		if (c[i].type == 'checkbox' && (str.substr(0, identifier.length) == identifier) ) {
			c[i].checked = checkit;
			lightbox_check('lightbox', c[i], str);
		}
	}	
}



// SYNCHRONOUS AJAX (SJAX)
function getFile(url) {
  if (window.XMLHttpRequest) {              
    AJAX=new XMLHttpRequest();              
  } else {                                  
    AJAX=new ActiveXObject("Microsoft.XMLHTTP");
  }
  if (AJAX) {
     AJAX.open("GET", url, false);                             
     AJAX.send(null);
     return AJAX.responseText;                                         
  } else {
     return false;
  }                                             
}

// var fileFromServer = getFile('http://somedomain.com/somefile.txt');

// COOKIES
function getCookie(c_name)
{
	if (document.cookie.length>0)
	  {
	  c_start=document.cookie.indexOf(c_name + "=");
	  if (c_start!=-1)
		{
		c_start=c_start + c_name.length+1;
		c_end=document.cookie.indexOf(";",c_start);
		if (c_end==-1) c_end=document.cookie.length;
		return unescape(document.cookie.substring(c_start,c_end));
		}
	  }
	return "";
}

function setCookie(c_name,value,expiredays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+
	((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}

function checkCookie(c_name)
{
	var thecookie=getCookie(c_name);
	if (thecookie!=null && thecookie!="")
	  {
	  return true;
	  }
	else
	  {
		return false;
	  }
}

function deleteCookie( c_name, path, domain ) {
	if ( checkCookie( c_name ) ) document.cookie = c_name + "=" +
	( ( path ) ? ";path=" + path : "") +
	( ( domain ) ? ";domain=" + domain : "" ) +
	";expires=Thu, 01-Jan-1970 00:00:01 GMT";
	if(document.getElementById('debug')) {
		document.getElementById('debug').innerHTML = getCookie(c_name);
	}
}

function lightbox_check(c_name, boxname, theitem) {
	if(boxname.checked == true) {
		if(checkCookie(c_name)) {
			if(getCookie(c_name).search(theitem) < 0) { // if id not already in cookie
				setCookie(c_name, getCookie(c_name)+','+theitem, 1);
			}
		} else {
			setCookie(c_name, theitem, 1);
		}
	} else {
		if(checkCookie(c_name)) {
			var thecookie=getCookie(c_name);
			var cookiearray = thecookie.split(',');
			cookiearray = removeItems(cookiearray, theitem);
			thecookie = cookiearray.toString();
			setCookie(c_name, thecookie);
		}
	}
	if(document.getElementById('debug')) {
		document.getElementById('debug').innerHTML = getCookie(c_name);
	}
}			

/*
function testimonial_shift(direction, current, max_t) {
	current += direction;
	if(current > max_t) {
		current = 1;
	} else if(current < 1) {
		current = max_t;
	}
	document.getElementById('testimonial_list').style.marginLeft = -((current - 1) * 570)+'px';
	return current;
	
} */

function testimonial_shift(shift_to, current) {
	document.getElementById('testimonial_list').style.marginLeft = -((shift_to-1) * 900)+'px';
	document.getElementById('testimonial_dot'+current).style.marginTop = '0px';
	document.getElementById('testimonial_dot'+current).style.marginBottom = '0px';
	document.getElementById('testimonial_dot'+shift_to).style.marginTop = '-12px';
	document.getElementById('testimonial_dot'+shift_to).style.marginBottom = '12px';
}


//Adds new uniqueArr values to temp array
function uniqueArr(a) {
 temp = new Array();
 for(i=0;i<a.length;i++){
  if(!contains(temp, a[i])){
   temp.length+=1;
   temp[temp.length-1]=a[i];
  }
 }
 return temp;
}
 
//Will check for the Uniqueness
function contains(a, e) {
 for(j=0;j<a.length;j++)if(a[j]==e)return true;
 return false;
}