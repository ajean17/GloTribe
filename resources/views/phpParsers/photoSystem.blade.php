<?php
  use App\User;
  if (isset($_FILES["avatar"]["name"]) && $_FILES["avatar"]["tmp_name"] != "")
  {
    $log_username = Auth::user()->name;
  	$fileName = $_FILES["avatar"]["name"];
    $fileTmpLoc = $_FILES["avatar"]["tmp_name"];
  	$fileType = $_FILES["avatar"]["type"];
  	$fileSize = $_FILES["avatar"]["size"];
  	$fileErrorMsg = $_FILES["avatar"]["error"];
  	$kaboom = explode(".", $fileName);
  	$fileExt = end($kaboom);
  	list($width, $height) = getimagesize($fileTmpLoc);
    /*
    if(request->hasFile('file'))
    $file = request->file('file');
    $fileName = $file->getClientOriginalName();
    $destinationPath = config(app.fileDestinationPath).'/'.$user.'/'.$fileName;
    Storage::put($destinationPath, file_get_contents($file->getRealPath())); <--Returns true or false for success or failure
    */
    if($width < 10 || $height < 10)
    {
  		header("location: ../message.php?msg=ERROR: That image has no dimensions");
      exit();
  	}
  	$db_file_name = rand(100000,999999).".".$fileExt;
  	if($fileSize > 1048576)
    {
  		header("location: /message?msg=ERROR: Your image file was larger than 1mb");
  		exit();
  	}
    else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) )
    {
  		header("location: ./message?msg=ERROR: Your image file was not jpg, gif or png type");
  		exit();
  	}
    else if ($fileErrorMsg == 1)
    {
  		header("location: /message?msg=ERROR: An unknown error occurred");
  		exit();
  	}

  	$avatar = User::where('name','=',$log_username)->first();
    $avatar = $avatar->avatar;

    /*
    $path = resource_path().'/uploads'.'/user'.'/'.$user.'/'.$type.'/'.$filename;
    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
    */
    if($avatar != "")
    {
  		$picurl = resource_path().'/uploads'.'/user'.'/'.$log_username.'/images'.'/'.$avatar;
	    if (file_exists($picurl))
      {
        unlink($picurl);
      }
  	}

    $file = $request->file('file');
    $fileName = $file->getClientOriginalName();
    $destinationPath = resource_path().'/uploads'.'/user'.'/'.$log_username.'/images'.'/'.$db_file_name;
    $moveResult = Storage::put($destinationPath, file_get_contents($fileName->getRealPath()));// <--Returns true or false for success or failure

    if($moveResult != true)
    {
  		header("location: /message?msg=ERROR: File upload failed");
  		exit();
  	}

    include_once("/resizeImage");
  	$target_file = resource_path().'/uploads'.'/user'.'/'.$log_username.'/images'.'/'.$db_file_name;
  	$resized_file = resource_path().'/uploads'.'/user'.'/'.$log_username.'/images'.'/'.$db_file_name;
  	$wmax = 245;
  	$hmax = 245;
  	img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);

    User::where('name','=',$log_username)->update(Array('avatar' => $db_file_name));

    header("location: /profile/'.$log_username.'");
  	exit();
  }
?>
