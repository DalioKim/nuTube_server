# 너튜브 서버


## 기능 소개
---
유튜브와 같이 동영상 업로드,시청,댓글을 등록하고,라이브 방송 및 실시간 채팅,최초공개,타임라인,미리보기 등 유튜브의 흥미로운 기능들을 구현해봤다.
뿐만 아니라, 정규표현식을 이용해 한국어,영어등 한가지 언어의 댓글로만 필터링이 되게하는 기능과 영상 업로드시 부적합한 장면(흡연)을 감지하는 AI 기능도 구현했다.

업로드와 같은 기본적인 기능외에도, 최초공개(FCM),댓글 타임라인과 언어별 필터링(정규표현식 응용),채팅(소켓통신 java서버),영상 감지(flask,tensorflow,opencv)등등의 
기능과 기능을 구현하기 위한 기술을 사용했다.

## 기능 별 파일(링크이동)
---

  <a href="https://github.com/DalioKim/nuTube_server/tree/master">전체 보기</a></br></br>
  <a href="https://github.com/DalioKim/nuTube_server/blob/master/LiveChat.java">채팅서버</a></br></br>
  <a href="https://github.com/DalioKim/nuTube_server/blob/master/censored.py">영상감지 Ai Flask 서버</a></br></br>
  <a href="https://github.com/DalioKim/nuTube_server/blob/master/upload_comment.php">Vod 댓글 작 서버</a></br></br>
  <a href="https://github.com/DalioKim/nuTube_server/blob/master/push_notification.php">최초공개(FCM) 서버</a></br></br>
 </br></br>
