<?php

// 사용하는 변수;  영상파일명,썸네일파일명,제목변수.설명변수,태그변수,사용자아이디 변수, 날짜변수



if (isset($_FILES["imageFile"]) )
{
  $image_file_name = time().".jpg";
  $id = $_POST['id'];
  $title = $_POST['title'];
  //echo json_encode(array("response"=>$image_file_name));




  //""제거
  $id = str_replace('"','',$id);
  $title = str_replace('"','',$title);




  //시간변수에 업로드 된 현재 시간을 저장한다.
  $date = date("Y-m-d H:i:s");



  //$email = $_POST['email'];


 $file_path = "videos/";
  if(move_uploaded_file($_FILES['imageFile']['tmp_name'], $file_path.$image_file_name)){

/*
      $db = mysqli_connect("localhost", "rayDalio","suMMit88$","youtube");
      $db->set_charset("utf8");
      $insert_sql = mysqli_query($db, "INSERT into video (id,title, startTime,current,humbnailName) values ('$id','$title','$date',"true",'$image_file_name')");
      //$insert_sql = mysqli_query($db, "INSERT into video (videoName) values ('$video_file_name')");

      if($insert_sql){
      echo json_encode(array("response"=>$image_file_name));
      //echo json_encode(array("response"=>$theFirst));

  }




}else{
    echo json_encode(array("response"=>"fail"));
  }



*/
echo json_encode(array("response"=>$image_file_name));


}



 ?>
