<?php

// 사용하는 변수;  영상파일명,썸네일파일명,제목변수.설명변수,태그변수,사용자아이디 변수, 날짜변수



if (isset($_FILES["videoFile"]) && isset($_FILES["imageFile"]) )
{
  $video_file_name = time().".mp4";
  $image_file_name = time().".jpg";
  $id = $_POST['id'];
  $title = $_POST['title'];
  $tag = $_POST['tag'];
  $explain = $_POST['explain'];
  $theFirst = $_POST['theFirst'];
  $startTime = $_POST['startTime'];



  //""제거
  $id = str_replace('"','',$id);
  $title = str_replace('"','',$title);
  $tag = str_replace('"','',$tag);
  $explain = str_replace('"','',$explain);
  $startTime = str_replace('"','',$startTime);




  //시간변수에 업로드 된 현재 시간을 저장한다.
  $date = date("Y-m-d H:i:s");



  //$email = $_POST['email'];


 $file_path = "videos/";
  if(move_uploaded_file($_FILES['videoFile']['tmp_name'], $file_path.$video_file_name) &&
  move_uploaded_file($_FILES['imageFile']['tmp_name'], $file_path.$image_file_name)){
    //$update_sql = mysqli_query($db, "UPDATE users set thumbnail = '" . $file_name . "' where email ='" . $email . "'");

    //echo json_encode(array("response"=>"영상파일명 :".$video_file_name." 이미지파일명 :".$image_file_name." 아이디: "+$id));

      $db = mysqli_connect("localhost", "rayDalio","suMMit88$","youtube");
      $db->set_charset("utf8");
      $insert_sql = mysqli_query($db, "INSERT into video (videoName, thumbnailName, userId, title, subtitle, tag, dateTime, hit , theFirst, startTime) values ('$video_file_name','$image_file_name','$id','$title','$explain','$tag','$date',0,'$theFirst','$startTime')");
      //$insert_sql = mysqli_query($db, "INSERT into video (videoName) values ('$video_file_name')");
      if($insert_sql){
      echo json_encode(array("response"=>$video_file_name));
      //echo json_encode(array("response"=>$theFirst));

  }




}else{
    echo json_encode(array("response"=>"fail"));
  }





}



 ?>
