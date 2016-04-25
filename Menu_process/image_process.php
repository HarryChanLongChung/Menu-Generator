<?php
	// link to the font file no the server
	$fontname = 'font/Capriola-Regular.ttf';
	// controls the spacing between text
	$i=30;
	//JPG image quality 0-100
	$quality = 90;
	$imglocation = "";
	$txtlocation = "";

	$imgLocat = "upload/menu.jpg";
	$txtLocat = "upload/content.txt";

	if (isset($_POST['fontSelection'])) {
		$fontOption = $_POST['fontSelection'];
		switch ($fontOption) {
			case "1":
				$fontname = 'font/Lobster-Regular.ttf';
				break;
			case "2":
				$fontname = 'font/PassionOne-Regular.ttf';
				break;
			case "3":
				$fontname = 'font/PoiretOne-Regular.ttf';
				break;
			case "4":
				$fontname = 'font/RobotoCondensed-Regular.ttf';
				break;
		}
	}

	$text_content = array();   
	$unit = array(
		array(
			'Dishname'=>'', 
			'font-size'=>'',),
		array(
			'Detail'=>'', 
			'font-size'=>'',),
		array(
			'Prize'=>'', 
			'font-size'=>'',),
	);

	function create_image($text_content, $start, $imgLoca){

		global $fontname;	
		global $quality;

		$file = "covers/".md5($text_content[0][0]['Dishname'].$text_content[1][0]['Dishname']).".jpg";	
	
			// define the base image that we lay our text on
			$im = imagecreatefromjpeg($imgLoca);
			
			// setup the text colours to be black
			$black = imagecolorallocate($im, 12, 12, 12);

			// this defines the starting height for the text block
			// 400 is just a testing value
			$y = imagesy($im)-(imagesy($im)-$start);
			 
			 
		// loop through each set	
		foreach ($text_content as $set){
				// place the Dish name
				$x = imagesx($im)*0.1;
				imagettftext($im, $set[0]['font-size'], 0, $x, $y+$i, $black, $fontname,$set[0]['Dishname']);	
				$x = imagesx($im)-imagesx($im)*0.2;
				imagettftext($im, $set[2]['font-size'], 0, $x, $y+$i, $black, $fontname,$set[2]['Prize']);
				$i = $i+$set[0]['font-size']*1.2;	
				$x = imagesx($im)*0.1;
				imagettftext($im, $set[1]['font-size'], 0, $x, $y+$i, $black, $fontname,$set[1]['Detail']);
				$i = $i+100;
		}

		// create the image
		imagejpeg($im, $file, $quality);

		return $file;	
	}

	function center_text($string, $font_size, $im){
			global $fontname;
			$image_width = imagesx($im);
			$dimensions = imagettfbbox($font_size, 0, $fontname, $string);

			return ceil(($image_width - $dimensions[4]) / 2);				
	}


	function set_text_content($filelocation){

		global $unit;

		$myfile = fopen($filelocation, "r");
		$finish = "=end of define=";
		$text = fgets($myfile);
		$st = 0;
		$sd = 0;
		$sp = 0;
		$result = array();

		while(!feof($myfile) && strcmp($text,$finish)!=1){

			if(substr($text,0,1)=="t"){
				$st = (int)substr($text,2);
			}
			if(substr($text,0,1)=="d"){
				$sd = (int)substr($text,2);
			}
			if(substr($text,0,1)=="p"){
				$sp = (int)substr($text,2);
			}
			$text = fgets($myfile);
		}

		$set=0;
		$text = fgets($myfile);
		while(!feof($myfile)){

			$unit[0]['Dishname']  = substr($text,3);
			$unit[0]['font-size'] = $st;
			$text = fgets($myfile);
			$unit[1]['Detail']  = substr($text,3);
			$unit[1]['font-size'] = $sd;
			$text = fgets($myfile);
			$unit[2]['Prize']  = substr($text,3);
			$unit[2]['font-size'] = $sp;

			$result[$set] = $unit;

			$text = fgets($myfile);
			$set++;
		}
		fclose($myfile);

		return $result;
	}

	//the upload button is invoked
	if(isset($_POST["submit"])) {
		//directory for all files
		$target_dir = "upload/";

		//check with image file is exist
		if(is_uploaded_file($_FILES["ImgFile"]["tmp_name"])){

			//set up image required component
			$target_img = $target_dir . basename($_FILES["ImgFile"]["name"]);
			$def_img_name   = "menu";
			$imageFileType = pathinfo($target_img,PATHINFO_EXTENSION);
			$upPass = 1;
			$tmp_name   = $_FILES["ImgFile"]["tmp_name"];

    		//mime is the correspondant MIME type of the image.
    		$check = getimagesize($_FILES["ImgFile"]["tmp_name"]);

    		//if cannot get image size, not a image
    		if($check !== false){}else{$upPass = 0;}

   			//actually upload the file
    		if($upPass == 1){
    		    if(move_uploaded_file($tmp_name, "$target_dir/$def_img_name.$imageFileType")){
    		        echo "<script>alert('Success! The image file is uploaded.');</script>";
    		    }else{
    		        echo "<script>alert('Fail!');</script>";
    		    }
    		}
		}

		//check with image file is exist
		if(is_uploaded_file($_FILES["TxtFile"]["tmp_name"])){
			//set up txt required component
			$target_txt = $target_dir . basename($_FILES["TxtFile"]["name"]);
			$def_txt_name   = "content";
			$txtFileType = pathinfo($target_txt,PATHINFO_EXTENSION);
			$upPass = 1;
			$tmp_name   = $_FILES["TxtFile"]["tmp_name"];

			//confirm that it is a .txt file
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			if(finfo_file($finfo, $tmp_name)=="text/plain"){
				if(move_uploaded_file($tmp_name, "$target_dir/$def_txt_name.$txtFileType")){
    		        echo "<script>alert('Success! The text file is uploaded.');</script>";
    		    }else{
    		        echo "<script>alert('Fail!');</script>";
    		    }
			}
		}

	}
	
	if(file_exists($imgLocat)){
		$imglocation = $imgLocat;
	}else{
		$imglocation = "default.jpg";
	  }

	if(file_exists($txtLocat)){
		$txtlocation = $txtLocat;
	}else{
		$txtlocation = "default.txt";
	}

	// run the script to create the image
	$text_content=set_text_content($txtlocation);
	$filename = create_image($text_content,400,$imglocation);
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Menu Generator</title>
		<link href="../style.css" rel="stylesheet" type="text/css" />
		<style>
			input{
				border:1px solid #ccc;
				padding:8px;
				font-size:14px;
				width:300px;
			}
	
			.submit{
				width:110px;
				background-color:#FF6;
				padding:3px;
				border:1px solid #FC0;
				margin-top:20px;}
		</style>
	</head>

	<body>
		<img src="<?=$filename;?>?id=<?=rand(0,1292938);?>"/><br/><br/>

		<ul>
		<?php
			if(isset($error)){
				foreach($error as $errors){
					echo '<li>'.$errors.'</li>';
				}	
			}
		?>
		</ul>
		
		<p>Upload the image you want to be the background image.</p>
		<div class="dynamic-form">
			<form action="" method="post" enctype="multipart/form-data">
    			<label>Select image to upload: </label>
    				<input type="file" name="ImgFile" id="imgToUpload"><br/>
    			<label>Select text file to upload:</label>
    				<input type="file" name="TxtFile" id="txtToUpload"><br/>
    			<label>Select the wanted font:</label>
    			<select name="fontSelection">
  					<option value="1">Font 1</option>
  					<option value="2">Font 2</option>
  					<option value="3">Font 3</option>
  					<option value="4">Font 4</option>
				</select><br/>
    				<input type="submit" value="Renew Image" name="submit">
			</form>
		</div>

	</body>
</html>
