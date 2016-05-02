function pickTab(tab)
{
  var tabs = document.getElementsByClassName('tabBody');
  for (i = 0; i < tabs.length; i++)
  {
    if (tabs[i].id == tab)
      tabs[i].style.display = 'block';
    else
      tabs[i].style.display = 'none';
  }
}

function setLinkURL(linkElement, url)
{
  linkElement.innerText=url;
  linkElement.href=url;
}

var originalFileName="";
var visibleImgId = "";
function clearInfoBox()
{
  originalFileName = "";
  visibleImgId = "";
  document.getElementById("ImageName").value = "";
  document.getElementById("IsPublic").checked = false;
  document.getElementById("IsVisible").checked = false;
  document.getElementById("DirectLink").innerText = "";
  document.getElementById("ThumbnailLink").innerText = "";
  document.getElementById("imageInfoError").innerText = "";
}


function displayInfoBox(imgId)
{
  hideInfoBox();
  hideZoomBox();
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange= function()
  {
    if (xmlhttp.readyState == 4 && xmlhttp.status==200)
    {
      try
      {
        var response = JSON.parse(xmlhttp.responseText);
        document.getElementById("imageInfoBox").style.display="block";
        if (response["success"])
        {
          document.getElementById("ImageName").value = response["imageName"];
          originalFileName = response["imageName"];
          visibleImgId = imgId;
          document.getElementById("IsPublic").checked = response["isPublic"] == 1;
          document.getElementById("IsVisible").checked = response["isVisible"] ==1;
          document.getElementById("DirectLink").innerText = response["directLink"];
          document.getElementById("ThumbnailLink").innerText = response["thumbLink"];
        }
        else
        { 
          document.getElementById("imageInfoError").style.display="block";
          document.getElementById("imageInfoError").innerText = response["error"];
        }
      }
      catch (e)
      {
        
        document.getElementById("imageInfoBox").style.display="block";
        document.getElementById("imageInfoError").style.display="block";
        document.getElementById("imageInfoError").innerText = xmlhttp.responseText;
      }
    }
    else if (xmlhttp.readyState == 4 && xmlhttp.status==500)
    {
      document.getElementById("imageInfoBox").style.display="block";
      document.getElementById("imageInfoError").style.display="block";
      document.getElementById("imageInfoError").innerText = "oops";
    }
  };
  xmlhttp.open("GET", "?getInfo="+imgId);
  xmlhttp.send();
}

function updateImageInfo()
{
  var fileName = document.getElementById("ImageName").value;
  var extensionIndex = originalFileName.lastIndexOf(".");
  if (extensionIndex != -1)
  {
    var extension = originalFileName.substring(extensionIndex);
    var newExtensionIndex = fileName.lastIndexOf(".");
    if (newExtensionIndex == -1)
    {
      fileName = fileName + extension;
    }
    else
    {
      // Strip old extension, add new.
      fileName = fileName.substr(0,newExtensionIndex) + extension;
    }
  }
  var isPublic = document.getElementById("IsPublic").checked ? 1 : 0;
  var isVisible = document.getElementById("IsVisible").checked ? 1 : 0;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange= function()
  {
    if (xmlhttp.readyState == 4 && xmlhttp.status==200)
    {
      try
      {
        var response = JSON.parse(xmlhttp.responseText);
        document.getElementById("imageInfoBox").style.display="block";
        if (response["success"])
        {
          window.alert("Image Information Updated");
          location.reload();
        }
        else
        { 
          document.getElementById("imageInfoError").style.display="block";
          document.getElementById("imageInfoError").innerText = response["error"];
        }
      }
      catch (e)
      {
        document.getElementById("imageInfoBox").style.display="block";
        document.getElementById("imageInfoError").style.display="block";
        document.getElementById("imageInfoError").innerText = xmlhttp.responseText;
      }
    }
  };
  xmlhttp.open("GET", "?updateInfo="+visibleImgId +"&fileName="+ encodeURI(fileName) + "&isPublic=" + isPublic + "&isVisible=" + isVisible);
  xmlhttp.send();
}

function displayZoomBox(imgUrl)
{
  hideInfoBox();
  document.getElementById("zoomImage").src = imgUrl;
  document.getElementById("zoomBox").style.display= "block";
}

function hideInfoBox()
{
  document.getElementById("imageInfoBox").style.display="none";
  clearInfoBox();
}

function hideZoomBox()
{
  document.getElementById("zoomBox").style.display="none";
  document.getElementById("zoomImage").src = "";
}

function selectText(element)
{
  if (document.selection)
  {
    // IE?
    var range = document.body.createTextRange();
    range.moveToElementText(element);
    range.select();
  }
  else
  {
    var range = document.createRange();
    range.selectNode(element);
    window.getSelection().addRange(range);
  }
}

function openURL(element)
{
  window.open(element.innerText);
}

function zoom(url)
{
  displayZoomBox(url);
}
