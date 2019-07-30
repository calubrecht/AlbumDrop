function pickTab(tab)
{
  hideZoomBox();
  hideInfoBox();
  var tabBodies = document.getElementsByClassName('tabBody');
  var tabs = document.getElementsByClassName('tab');
  for (i = 0; i < tabs.length; i++)
  {
    var baseClass = tabBodies[i].className.includes("narrow") ?
      "tabBody narrow" :
      "tabBody";
    if (tabBodies[i].id == tab)
    {
      //tabBodies[i].style.display = 'block';
      tabBodies[i].className = baseClass;
      tabs[i].className = "tab active";
    }
    else
    {
      //tabBodies[i].style.display = 'none';
      tabBodies[i].className = baseClass + " hidden";
      tabs[i].className = "tab";
    }
  }
}

var publicTabLoaded = 0;

function pickAndLoadTab(tab)
{
  pickTab(tab);
  if (publicTabLoaded == 0)
  {
    publicTabLoaded = 1;
    updateGallery("public");
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
          var enableEdit = response["enableEdit"] == 1;
          document.getElementById("ImageName").value = response["imageName"];
          document.getElementById("ImageName").disabled = (enableEdit == 0);
          originalFileName = response["imageName"];
          visibleImgId = imgId;
          document.getElementById("IsPublic").checked = response["isPublic"] == 1;
          document.getElementById("IsPublic").disabled = (enableEdit == 0);
          document.getElementById("IsVisible").checked = response["isVisible"] ==1;
          document.getElementById("IsVisible").disabled = (enableEdit == 0);
          document.getElementById("UpdateButton").style.display = (enableEdit == 1) ? "block" : "none";
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
          hideInfoBox();
          updateGallery("gallery");
          updateGallery("public");
          return;
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

function register()
{
  var form = document.getElementById("registerForm");
  var formData = {};
  var inputs = form.getElementsByTagName("input");
  for (i = 0; i < inputs.length; i++)
  {
    formData[inputs[i].name] = inputs[i].value;
  }
  var errorBox = document.getElementById("registerError");
  if (formData["password"] != formData["confirmPassword"])
  {
    errorBox.innerText = "Password confirmation does not match."
  }
  else if (formData["username"] == "")
  {
    errorBox.innerText = "Please enter a username";
  }
  else if (formData["password"] == "")
  {
    errorBox.innerText = "Please enter a password";
  }
  else if (formData["displayName"] == "")
  {
    errorBox.innerText = "Please be friendly and enter a Dispay Name";
  }
  else
  {
    errorBox.innerText = "";
    formData["action"] = "register";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange= function()
    {
      if (xmlhttp.readyState == 4 && xmlhttp.status==200)
      {
        try
        {
          var response = JSON.parse(xmlhttp.responseText);
          if (response["success"])
          {
            location.reload();
          }
          else
          {
            errorBox.innerText = response["error"];
          }
        }
        catch (e)
        {
          errorBox.innerText = xmlhttp.responseText;
        }
      }
    };
    xmlhttp.open("POST", "", true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    xmlhttp.send(JSON.stringify(formData));
  }
}

function resetPassword()
{
  var form = document.getElementById("resetPasswordForm");
  var formData = {};
  var inputs = form.getElementsByTagName("input");
  for (i = 0; i < inputs.length; i++)
  {
    formData[inputs[i].name] = inputs[i].value;
  }
  var errorBox = document.getElementById("resetPasswordError");
  if (formData["password"] == "")
  {
    errorBox.innerText = "Please enter a password";
  }
  else if (formData["password"] != formData["confirmPassword"])
  {
    errorBox.innerText = "Password confirmation does not match."
  }
  else
  {
    errorBox.innerText = "";
    formData["action"] = "doResetPassword";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange= function()
    {
      if (xmlhttp.readyState == 4 && xmlhttp.status==200)
      {
        try
        {
          var response = JSON.parse(xmlhttp.responseText);
          if (response["success"])
          {
            window.location = response["redirect"];
          }
          else
          {
            errorBox.innerText = response["error"];
          }
        }
        catch (e)
        {
          errorBox.innerText = xmlhttp.responseText;
        }
      }
    };
    xmlhttp.open("POST", "/", true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    xmlhttp.send(JSON.stringify(formData));
  }
}

function sendPasswordLink()
{
  var form = document.getElementById("passwordForm");
  var formData = {};
  var inputs = form.getElementsByTagName("input");
  for (i = 0; i < inputs.length; i++)
  {
    formData[inputs[i].name] = inputs[i].value;
  }
  var errorBox = document.getElementById("passwordError");
  var infoBox = document.getElementById("passwordInfo");
  if (formData["username"] == "")
  {
    errorBox.innerText = "Please enter a username";
    infoBox.innerText = "";
  }
  else
  {
    errorBox.innerText = "";
    infoBox.innerText = "";
    formData["action"] = "resetPassword";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange= function()
    {
      if (xmlhttp.readyState == 4 && xmlhttp.status==200)
      {
        try
        {
          var response = JSON.parse(xmlhttp.responseText);
          if (response["success"])
          {
            infoBox.innerText = response["message"];
          }
          else
          {
            errorBox.innerText = response["error"];
          }
        }
        catch (e)
        {
          errorBox.innerText = xmlhttp.responseText;
        }
      }
    };
    xmlhttp.open("POST", "", true);
    xmlhttp.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    xmlhttp.send(JSON.stringify(formData));
  }
}

function deleteImage(imgId, gallery)
{
  document.getElementById(gallery + "Error").style.display="none";
  var yesno = window.confirm("Are you sure you want to delete this image?");
  if (yesno == false)
  {
    return;
  }
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange= function()
  {
    if (xmlhttp.readyState == 4 && xmlhttp.status==200)
    {
      try
      {
        var response = JSON.parse(xmlhttp.responseText);
        if (response["success"])
        {
          updateGallery("gallery");
          updateGallery("public");
          return;
        }
        else
        { 
          document.getElementById(gallery + "Error").style.display="block";
          document.getElementById(gallery + "Error").innerText = response["error"];
        }
      }
      catch (e)
      {
        
        document.getElementById(gallery + "Error").style.display="block";
        document.getElementById(gallery + "Error").innerText = xmlhttp.responseText;
      }
    }
  };
  var command =  {};
  command["action"] = "delete";
  command["imgId"] = imgId;
  xmlhttp.open("POST", "", true);
  xmlhttp.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
  xmlhttp.send(JSON.stringify(command));
}

function displayZoomBox(imgUrl, width, height)
{
  hideInfoBox();
  if (width > 0)
  {
    document.getElementById("zoomBox").style.marginLeft= -width/2 + "px";
    document.getElementById("zoomBox").style.left= "50%";
  }
  else
  {
    document.getElementById("zoomBox").style.marginLeft= "0";
    document.getElementById("zoomBox").style.left= "15px"
  }
  document.getElementById("zoomImage").src = imgUrl;
}

function showZoombox()
{
  document.getElementById("zoomBox").style.display= "block";
}

function hideInfoBox()
{
  if (!document.getElementById("zoomBox"))
  {
    return;
  }
  document.getElementById("imageInfoBox").style.display="none";
  clearInfoBox();
}

function hideZoomBox()
{
  if (!document.getElementById("zoomBox"))
  {
    return;
  }
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
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
  }
}

function openURL(element)
{
  window.open(element.innerText);
}

function zoom(url, width, height)
{
  displayZoomBox(url, width, height);
}

function loadAll()
{
  updateGallery("gallery");
  enableDrop();
}


dragFiles = new Object()
dragFiles.files = null;
dragFiles.fileNames = []
dragFiles.clear = function() {this.files = null; this.fileNames=[]};
dragFiles = {
  files : null,
  fileNames : [],
  clear : function () { this.files =null; this.fileNames=[]},
  setFiles : function(files) { this.files = files; this.fileNames = [...files].map(f => f.name); }
  };

function enableDrop()
{
  dropArea1 = document.getElementById("galleryTab");
  dropArea2 = document.getElementById("uploadTab");

  ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      document.addEventListener(eventName, preventDefaults, false)
  });

  ['dragenter', 'dragover'].forEach(eventName => {
      dropArea1.addEventListener(eventName, highlight, false)
      dropArea2.addEventListener(eventName, highlight, false)
  });


  ;['dragleave', 'drop'].forEach(eventName => {
      dropArea1.addEventListener(eventName, unhighlight, false)
      dropArea2.addEventListener(eventName, unhighlight, false)
  })

  dropArea1.addEventListener('drop', doUpload, false);
  dropArea2.addEventListener('drop', doUpload, false);

  cancelButton = document.getElementById('cancelDragUpload');
  cancelButton.addEventListener(
    "click",
    function() {
      cancelDragged();
    });
  uploadButton = document.getElementById('uploadFiles');
  uploadButton.addEventListener(
    "click",
    function(evt) {
      if (dragFiles.files)
      {
        files = dragFiles.files;
      }
      else
      {
        selected = document.getElementById('selectedFiles').files;
        files = selected;
      }
      evt.preventDefault();
      // Check for empty files
      xhr = new XMLHttpRequest();
      formData = new FormData();
      //formData.append('files', files);
      [...files].forEach(f => formData.append("files[]", f));
      total =  0;
      for (i = 0; i < files.length; i++)
      {
         total += files[i]["size"];
      }
      formData.append('isVisible', document.getElementById("isVisible").checked);
      formData.append('uploadFiles', "Upload");
      formData.append('async', "y");

      progress = document.getElementById("progress-bar");
      progress.style.display="block";

      xhr.addEventListener("readystatechange", function (e)
      {
         if (xhr.readyState ==4 && xhr.status == 200)
         {
           cancelDragged();
           progress.style.display="none";
           document.getElementById('selectedFiles').value = '';
           pickTab("galleryTab");
           updateGallery("gallery");
         }
         else if (xhr.readyState == 4 && xhr.status != 200)
         {
           // Todo, make a better error field
           alert(xhr.responseText);
         }
      });
      xhr.upload.addEventListener("progress", function (e)
       {
         p = (e.loaded * 100.0 / total)  || 100;
         progress.value = p;
       });

      xhr.open('POST', '/', true);
      xhr.send(formData);

    });
}

function cancelDragged()
{
  dragFiles.clear();
  dragSection = document.getElementById('dragUploads');
  dragSection.style.display="none";
  selectSection = document.getElementById('selectUploads');
  selectSection.style.display="block";
  dragNames = document.getElementById('dragFileNames');
  dragNames.innerHTML = '';
  progress = document.getElementById("progress-bar");
  progress.style.display="none";
  progress.value=0;
}

function doUpload(e)
{
  dt = e .dataTransfer;
  fileList = dt.files;

  fileName = fileList[0];
  dragFiles.setFiles(fileList);
  dragNames = document.getElementById('dragFileNames');
  populateFilenames(dragNames, dragFiles.fileNames);
 // populatePreviews(dragNames, fileList);
  pickTab("uploadTab");
  dragSection = document.getElementById('dragUploads');
  dragSection.style.display="block";
  selectSection = document.getElementById('selectUploads');
  selectSection.style.display="none";
}

function populateFilenames(el, fileNames)
{
  el.innerHTML='';
  fileNames.forEach(
    function(fileName)
    {
      fDiv = document.createElement("div");
      fDiv.innerHTML = fileName;
      el.appendChild(fDiv);
    }
  );
}

function populatePreview(el, file)
{
  reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onloadend = function()
  {
      img = document.createElement("img");
      img.src = this.result;
      d = document.createElement("div");
      d.className = "uploadPreview";
      d.appendChild(img);
      el.appendChild(d); 
  }
}

function populatePreviews(el, files)
{
  [...files].forEach(file => populatePreview(el, file));
}

function highlight(e) {
}

function unhighlight(e) {
}

function preventDefaults (e) {
    e.preventDefault()
    e.stopPropagation()
}

function updateGallery(gallery)
{
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange= function()
  {
    if (xmlhttp.readyState == 4 && xmlhttp.status==200)
    {
      try
      {
        var response = JSON.parse(xmlhttp.responseText);
        if (!response["success"])
        {
          // err
        }
        else
        { 
          doUpdateGallery(gallery+"Tab", response["data"]);
        }
      }
      catch (e)
      {
        //err  
        console.log(e);
        window.alert(e);
      }
    }
  };
  var command =  {};
  command["action"] = "updateGallery";
  command["gallery"] = gallery;
  xmlhttp.open("POST", "", true);
  xmlhttp.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
  xmlhttp.send(JSON.stringify(command));
}

function doUpdateGallery(galleryId, imgInfo)
{
  var gallery = document.getElementById(galleryId);
  var insertAfter = gallery.firstChild
  imgThumbIds = [];
  for (var i = 0 ; i < imgInfo.length; i++)
  {
    var divId = galleryId + "_" + imgInfo[i]["imgDivID"];
    imgThumbIds[i] = divId;
    var box = document.getElementById(divId);
    //var logoutButton = gallery.getElementsByClassName("LogoutButton")[0];
    if (box == null)
    {
      box = document.createElement("span");
      box.className="imgThumb";
      box.id=divId;
      box.draggable = "true";
      box.innerHTML = imgInfo[i]["html"];
      gallery.insertBefore(box, insertAfter.nextSibling);
      insertAfter = box;
    }
    else
    {
      var fileName = imgInfo[i]["fileName"];
      box.getElementsByClassName("fileName")[0].innerText = fileName;
      box.getElementsByClassName("fileName")[0].title = fileName;
      box.getElementsByClassName("mainImage")[0].childNodes[0].alt = fileName;
    }
 
  }
  imgs = gallery.getElementsByClassName("imgThumb");
  for (var j = 0; j < imgs.length; j++)
  {
    if (imgThumbIds.indexOf(imgs[j].id) == -1)
    {
      gallery.removeChild(imgs[j]);
    }
  }
}

function showHelpBox(event, element, category)
{
  event = event || window.event;
  hideHelp();
  if (category == "visible")
  {
    text = "Toggle whether to let the world see this image if they have the link.";
  }
  else
  {
    return;
  }
  helpBox = document.createElement("span");
  helpBox.innerHTML = text;
  helpBox.className = 'helpBox';
  helpBox.id = 'helpBox';
  helpBox.onclick = hideHelp;
  element.appendChild(helpBox);
  document.onclick = hideHelp;
  event.stopPropagation();
  return false;
}

function hideHelp(event)
{
  var oldBox = document.getElementById("helpBox");
  if (oldBox != null)
  {
    oldBox.parentNode.removeChild(oldBox);
  }
  document.onclick = null;
  if (event)
  {
    event.stopPropagation();
  }
}

function forgotPassword()
{
  var passwordTab = document.getElementById("passwordTabHead");
  passwordTab.style.display="inline";
  pickTab("passwordTab");
}
