<!DOCTYPE html>
<html>
<head>
	<title>Form</title>
</head>
<body>
	<form action="form/upload" method="post" enctype="multipart/form-data">
		<label for="imagename">Image name</label>
		<input type="text" name="imagename" id="imagename" placeholder="Enter the image name" required>
		<br>
		<label for="image">Choose Image</label>
		<input type="file" name="image" id="image" accept="image/*" required>
		<br>
		<button type="submit">Upload</button>
	</form>
</body>
</html>