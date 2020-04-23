<div id ="uploadTab" class="tabBody hidden">
  <form id="uploadForm" action="" method="post" enctype="multipart/form-data">
    <div id="selectUploads"><div class="firstColumn">Files to Upload:</div> <input class="secondColumn" type="file" name="files[]" size=100 id="selectedFiles" multiple></div>
    <div id="dragUploads">
      <div class="firstColumn">Files to Upload:</div>
      <div class="spacer"></div>
    <div id="previews"></div>
      <input type="button" class="Center" value="Cancel" name="Cancel" id="cancelDragUpload">
    </div>
    <div class="firstColumn">Set images as Visible:</div> <div clas="secondColumn"><input type="checkbox" name="isVisible" id="isVisible" checked><span class="helpIcon" onclick="showHelpBox(arguments[0], this, 'visible')"><img src='album_icons/help.png'></span></div><div class="spacer"> </div>
    <input type="submit" class="center" value="Upload" name="uploadFiles" id="uploadFiles">
    <div><progress id="progress-bar" max=100 value=0 style="display:none"></progress></div>
  </form>

</div>
