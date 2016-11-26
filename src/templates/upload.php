<div id ="uploadTab" class="tabBody hidden">
  <form id="uploadForm" action="" method="post" enctype="multipart/form-data">
    <div class="firstColumn">Files to Upload:</div> <input class="secondColumn" type="file" name="files[]" size=100 multiple>
    <div class="firstColumn">Set images as Visible:</div> <div clas="secondColumn"><input type="checkbox" name="isVisible" checked><span class="helpIcon" onclick="showHelpBox(arguments[0], this, 'visible')"><img src='ad_icons/help.png'></span></div><div class="spacer"> </div>
    <input type="submit" class="center" value="Upload" name="uploadFiles">
  </form>

</div>
