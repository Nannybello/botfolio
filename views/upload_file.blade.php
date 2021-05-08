<!DOCTYPE html>
<html>
<body>

<form action="uploadfile?token={{ $token }}" method="post" enctype="multipart/form-data">
  Select File to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  
  <input type="submit" value="Upload File" name="submit">
</form>

</body>
</html>