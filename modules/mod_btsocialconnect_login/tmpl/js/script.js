function newPopup(pageURL, title,w,h){
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	var popupWindow = window.open(pageURL,'connectingPopup','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes');
	//popupWindow.document.title = title;
}