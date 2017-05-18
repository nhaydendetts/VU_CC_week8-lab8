<?php
#documentation - http://googlecloudplatform.github.io/google-cloud-php/#/docs/v0.22.0/storage/storageobject?method=delete
require 'vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;
define ("PROJECTID",'');
define ("BUCKETNAME",'');
putenv('GOOGLE_APPLICATION_CREDENTIALS=cred.json');
$storage = new StorageClient([ 'projectId' => PROJECTID ]);
$bucket = $storage->bucket(BUCKETNAME);
function post($request, $bucket) {
  if (isset($request[0]) && isset($request[1])) {
    $filename=$request[1]."txt";
    $object = $bucket->object($filename);
    if ($object->exists()) {
      echo "Object already exists!";
      http_response_code(404);
    }
    else { 
      $file=fopen($filename,'w');
      fwrite($file,"username: $request[0], firstname: $request[1], lastname: $request[2]");
      fclose($file);
      $result=$bucket->upload(fopen($filename,'r'));
      echo json_encode($result->info());
    }
  }
  else {
    echo "You need to set both username and firstname";
    http_response_code(404);
  } 
}
function get($request, $bucket) {
  $filename=$request[0];
  $object = $bucket->object($filename);
  if ($object->exists()) {
    $content=$object->downloadAsString();
    $replace=array("username: ", " firstname: ");
  //$object->downloadToFile('file_backup.txt');
    list($arr['username'], $arr['firstname'])=explode(",", str_replace($replace,"",$content));
    echo json_encode($arr); 
  }
  else {
    echo "Object doesn't exist!";
    http_response_code(404);
  }
}
function put($request, $bucket) {
  $filename=$request[0];
  $object = $bucket->object($filename);
  if ($object->exists()) {
    post($request,$bucket);
  }  
  else { 
    echo "Object doesn't exist!";
    http_response_code(404);
  }
}
function deleter($request, $bucket){
  $filename=$request[0];
  $object = $bucket->object($filename);
  if ($object->exists()) {
    $object->delete();
    echo json_encode(array("file"=>$filename, "action"=>"deleted"));
  }
  else {
    echo "Object doesn't exist!";
    http_response_code(404);
  }
}
$method = $_SERVER['REQUEST_METHOD'];
if (isset($_SERVER['PATH_INFO'])) {
  $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
  switch ($method) {
    case 'GET':
      get($request, $bucket); break;
    case 'PUT':
      put($request, $bucket); break;
    case 'POST':
      post($request, $bucket); break;
    case 'DELETE':
      deleter($request, $bucket); break;
  }
}
else {
  echo "Nothing here, use http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'/username/firstname to use the API';
}
?>