

<?php
//로컬로부터 영상목록에 대한 요청이 들어오면
//테이블에 접근해서 가장 최신 영상 10개를 들고온다.
//받아온 영상아이템의 정보들을 json형태로 만들어준다.
//클라이언트로 다시 보내준다.

$db = mysqli_connect("localhost", "rayDalio","suMMit88$","youtube");
$db->set_charset("utf8");

$id = $_POST['id'];
$content = $_POST['content'];
$videoIdx = $_POST['videoIdx'];
$date = date("Y-m-d H:i:s");
$language = "normal";

//$match = "Korean"
//댓글에 입력된 언어가 어느 나라 언어인지 구분
//정규표현식을 이용한다.


//사용자가 입력한 댓글에서 각 글자가 어떤 나라의 언어인지 셀 정수형 변수들
$ko = 0;
$en = 0;
$cn = 0;
$jp = 0;


//입력받은 댓글의 길이

$size = (int)mb_strlen($content, 'utf-8');

//입력받은 댓글의 길이만큼 반복하면서 각 문자가 어느 나라언어인제 정규표현식으로 검사하고
//합산


for($i = 0; $i<$size; $i++){

  $element = mb_substr($content, $i, 1, 'utf-8');


  //정규표현식
  if(preg_match_all('!['
      .'\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}'
      .']+!u', $element, $match)){

        $ko++;
  }else if(
  //  한자
  preg_match_all('!['
      .'\x{2E80}-\x{2EFF}'// 한,중,일 부수 보충
      .'\x{31C0}-\x{31EF}\x{3200}-\x{32FF}'
      .'\x{3400}-\x{4DBF}\x{4E00}-\x{9FBF}\x{F900}-\x{FAFF}'
      .'\x{20000}-\x{2A6DF}\x{2F800}-\x{2FA1F}'// 한,중,일 호환한자
      .']+!u', $element, $match)){
  $cn++;
  }else if(
  //  일어
  preg_match_all('!['
      .'\x{3040}-\x{309F}'// 히라가나
      .'\x{30A0}-\x{30FF}'// 가타카나
      .'\x{31F0}-\x{31FF}'// 가타카나 음성 확장
      .']+!u', $element, $match)){
  $jp++;

  }


  else if (preg_match_all('!['
          .'\x{0061}-\x{007a}|\x{0041}-\x{005a}'
          .']+!u', $element, $match)){
  $en++;

  }

}

//가장 많이 나온 문자의 언어를 전체 댓글의 언어로 지정한다.
$arr = array($ko, $en, $cn,$jp);
//echo  'max($arr) : '.max($arr).'<br>';
$big = max($arr);
$key = array_search($big,$arr);

//$key 순서 한국어, 영어, 중국어, 일본어
if($key ==0){
$language ="Korean";
}else if($key ==1){
  $language ="English";

}else if($key ==2){
  $language ="Chinese";


}else{
  $language ="Japanese";

}



//$insert_sql = mysqli_query($db, "INSERT into video (videoName, thumbnailName, userId, title, subtitle, tag, dateTime, hit) values ('$video_file_name','$image_file_name','$id','$title',''$explain','$tag','$date',0)");

//$insert = mysqli_query($db, "INSERT into comment (video_idx, id, content, dateTime) values ('$videoIdx','$id','$content','$date')");
$insert = mysqli_query($db, "INSERT into comment (video_idx, id, content, dateTime,language) values ('$videoIdx','$id','$content','$date','$language')");



//요청횟수에 을 곱한 수부터 데이터 X개를 들고온다.
$sql = mysqli_query($db,"SELECT * from comment where video_idx = '" . $videoIdx . "' order by idx desc");

//$row_num = mysqli_num_rows($sql);
//echo $row_num;


//영상정보목록을 담을 변수를 선언한다.
// 영상아이템에 들어갈정보
//String videoName,videoThumbnail,date,hit,title,id,userThumbnail=

$array = array();

while($row = mysqli_fetch_assoc($sql)){



//현재시간과 얼마나 차이가 나는지 구한다
$date = ($row['dateTime']);
$result = (strtotime(date('Y-m-d H:i:s')) - strtotime($date)) / 60;

// 결과 값은 소숫점으로 출력되는 경우가 있으므로 정수형(int)으로 캐스팅(형변환)
// 소숫점 이하 자리 제거는 floor나 number_format($result, false)를 이용하는 것도 가능
$date = (int) $result;
//분을 구한후 수치에 따라 분,시간,일자로 구분
if($date < 60){
$date = $date."분 전";
}else if($date < 1440){
$date = $date/60;
$date = round($date);
$date = $date."시간 전";

}else{
$date = $date/1400;
$date = round($date);
$date = $date."일 전";

}

$hit = ($row['hit']);

$arrayMiddle = array(
//pushing fetched data in an array
'id'=>urlencode($row['id']),
'content'=>urlencode($row['content']),
'date'=>urlencode($date)
//'language'=>urlencode($row['language'])




);
array_push($array, $arrayMiddle);



}
//echo json_encode(array("response"=>"ok"));

echo urldecode(json_encode(array("commentItem"=>$array)));
//echo urldecode(json_encode(array("themaItems"=>$array)));






 ?>
