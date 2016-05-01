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

function clearInfoBox()
{
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


function hideInfoBox()
{
  document.getElementById("imageInfoBox").style.display="none";
  clearInfoBox();
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
