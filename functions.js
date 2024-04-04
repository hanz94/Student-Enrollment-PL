//MODAL window - functions modal btns behaviour
var modalContainer = document.getElementById("modal-con");
var modalAcceptBtn = document.getElementById("modal-accept");
var modalDeclineBtn = document.getElementById("modal-decline");
var modalCloseBtn = document.getElementById("modal-close");
var modalText = document.getElementById("modal-text");
var modalInputText = document.getElementById("modal-input-text");
var modalForm = document.getElementById("modal-form");


modalCloseBtn.onclick = function () {
	modalContainer.style.display = 'none';
}
modalAcceptBtn.onclick = function () {
	modalContainer.style.display = 'none';
}
modalDeclineBtn.onclick = function () {
	modalContainer.style.display = 'none';
}

function showModal(message) {
	modalContainer.style.display = 'flex';
	modalText.innerHTML = message;
}

function showModalPrompt() {
	modalContainer.style.display = 'flex';
	modalText.innerHTML = 'Wpisz kod:';
	modalInputText.style.display = 'inline-block';
	modalInputText.focus();
	
	modalAcceptBtn.onclick = function () {
		modalForm.submit();
	}
	
}

function showModalError(message, locate) {
	modalContainer.style.display = 'flex';
	modalText.innerHTML = message;
	modalCloseBtn.onclick = function () {
		window.location.replace(locate);
	}
	modalAcceptBtn.onclick = function () {
		window.location.replace(locate);
	}
	modalDeclineBtn.onclick = function () {
		window.location.replace(locate);
	}
}
//function enrollmentChangesRedirect(){if(null==getCookie("uid"))showModalPrompt();else{var e=getCookieValue("uid");location.href="?uID="+e}}

function enrollmentChangesRedirect() {
	var cookie = getCookie("uid");
					
	if (cookie == null) {
		showModalPrompt();
	}
	else {
		var cookieValue = getCookieValue('uid');
		location.href = "?uID=" + cookieValue;
	}
	
}

//function setCookie(e,t,i,n){let o=new Date;o.setTime(o.getTime()+864e5*i);let l="expires="+o.toUTCString();var u=window.location.pathname,a=u.substring(0,u.lastIndexOf("/"));if(document.cookie=e+"="+t+";"+l+";path="+a,"uid"==e){setCookie("nameSurname",nameSurname,90);var r=document.getElementById("cookie-button");r.setAttribute("onclick","deleteCookie('uid', '"+n+"');"),r.textContent="Usuń kod z pamięci przeglądarki"};showModal("Kod został poprawnie zapisany w pamięci przeglądarki.<br>Informacje będą przechowywane na Twoim urządzeniu przez maksymalnie 90 dni.")}

function setCookie(name, value, exdays, currentUID) {
	const d = new Date();
	d.setTime(d.getTime() + (exdays*864e5));
	let expires = "expires="+ d.toUTCString();
	var loc = window.location.pathname;
	var dir = loc.substring(0, loc.lastIndexOf('/'));
	document.cookie = name + "=" + value + ";" + expires + ";path=" + dir;
	
		if (name == 'uid') {
			setCookie('nameSurname', nameSurname, 90);
			var li = document.getElementById('cookie-button');
			li.setAttribute('onclick', "deleteCookie('uid', '" + currentUID + "');");
			li.textContent = 'Usuń kod z pamięci przeglądarki';
		}
		showModal("Kod został poprawnie zapisany w pamięci przeglądarki.<br>Informacje będą przechowywane na Twoim urządzeniu przez maksymalnie 90 dni.")
}

//function deleteCookie(e,t){var i=window.location.pathname,n=i.substring(0,i.lastIndexOf("/"));if(document.cookie=e+"=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path="+n,"uid"==e){deleteCookie("nameSurname");var o=document.getElementById("cookie-button");o.setAttribute("onclick","setCookie('uid', '"+t+"', 90, '"+t+"');"),o.textContent="Zapisz kod w pamięci przeglądarki"};showModal("Informacje zostały trwale usunięte z pamięci przeglądarki.")}

function deleteCookie(name, currentUID) {
	var loc = window.location.pathname;
	var dir = loc.substring(0, loc.lastIndexOf('/'));
	document.cookie = name+'=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=' + dir;
	
		if (name == 'uid') {
			deleteCookie('nameSurname');
			var li = document.getElementById('cookie-button');
			li.setAttribute('onclick', "setCookie('uid', '" + currentUID + "', 90, '" + currentUID + "');");
			li.textContent = 'Zapisz kod w pamięci przeglądarki';
		}
		showModal("Informacje zostały trwale usunięte z pamięci przeglądarki.")
}

//function getCookie(e){var t=document.cookie,i=e+"=",n=t.indexOf("; "+i);if(-1==n){if(0!=(n=t.indexOf(i)))return null}else{n+=2;var o=document.cookie.indexOf(";",n);-1==o&&(o=t.length)}return decodeURI(t.substring(n+i.length,o))}

function getCookie(name) {
	var dc = document.cookie;
	var prefix = name + "=";
	var begin = dc.indexOf("; " + prefix);
		if (begin == -1) {
			begin = dc.indexOf(prefix);
			if (begin != 0) return null;
		}
		else
		{
			begin += 2;
			var end = document.cookie.indexOf(";", begin);
				if (end == -1) {
				end = dc.length;
				}
		
		}

	return decodeURI(dc.substring(begin + prefix.length, end));
	
}

//function getCookieValue(e){var t=`; ${document.cookie}`,i=t.split(`; ${e}=`);if(2===i.length)return i.pop().split(";").shift()}

function getCookieValue(name) {
	const value = `; ${document.cookie}`;
	const parts = value.split(`; ${name}=`);
	if (parts.length === 2) return parts.pop().split(';').shift();
}

//function checkCookieMessage(e){var t=document.getElementById("cookie-button");null==getCookie("uid")?(t.textContent="Zapisz kod w pamięci przeglądarki",t.setAttribute("onclick","setCookie('uid', '"+e+"', 90, '"+e+"');")):(t.textContent="Usuń kod z pamięci przeglądarki",t.setAttribute("onclick","deleteCookie('uid', '"+e+"');"))}

function checkCookieMessage(currentUID) {
	var li = document.getElementById('cookie-button');
	var cookie = getCookie('uid');
	
		if (cookie == null) {
			li.textContent = 'Zapisz kod w pamięci przeglądarki';
			li.setAttribute('onclick', "setCookie('uid', '" + currentUID + "', 365, '" + currentUID + "');");
		}
		else {
			li.textContent = 'Usuń kod z pamięci przeglądarki';
			li.setAttribute('onclick', "deleteCookie('uid', '" + currentUID + "');");
		}
}

//function checkCookieEnrollmentBeginButton(){var e=document.getElementById("enrollment-begin-button-container");if(null!=getCookie("nameSurname")){var t=getCookieValue("nameSurname");e.innerHTML="Witamy ponownie, "+t+"!"}}

function checkCookieEnrollmentBeginButton() {
	var enrBeginButtonContainer = document.getElementById('enrollment-begin-button-container');
	var cookieNameSurname = getCookie('nameSurname');
	
		if (cookieNameSurname != null) {
			var cookieNameSurnameValue = getCookieValue('nameSurname');
			enrBeginButtonContainer.innerHTML = 'Witamy ponownie, ' + cookieNameSurnameValue + '!';
		}
}

//MAIN - nav btns behaviour
var btnNavBegin = document.getElementById("btn-nav-begin");
var btnNavChanges = document.getElementById("btn-nav-changes");
var btnNavResults = document.getElementById("btn-nav-results");

btnNavBegin.onclick = function () {
	location.href='?uID=new';
}
btnNavChanges.onclick = function () {
	enrollmentChangesRedirect();
}
btnNavResults.onclick = function () {
	location.href='wyniki';
}

