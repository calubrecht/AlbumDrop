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

function displayInfoBox(imgId)
{
  document.getElementById("imageInfoBox").style.display="block";
}

function hideInfoBox(imgId)
{
  document.getElementById("imageInfoBox").style.display="none";
}
