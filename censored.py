#coding=utf-8

from flask import Blueprint, request, render_template, flash, redirect, url_for
from flask import Flask
from flask import jsonify
import pymysql
import json
import locale
import cv2
from tensorflow.keras.preprocessing import image as im
from tensorflow.keras import applications
from tensorflow.keras.models import model_from_json
from tensorflow.compat.v2.keras.models import model_from_json
from tensorflow.keras.preprocessing import image
import numpy as np
from datetime import datetime
import time

#검열결과를 저장할 전역
global result
##파일병을 저장할 글로벌 변수
global fileName

app=Flask(__name__)
@app.route('/',methods = ['GET', 'POST'])
def predict():


    locale.setlocale(locale.LC_ALL, '')

    fileName =  request.form['request']
    #return jsonify(response=fileName)
# 영상의 의미지를 연속적으로 캡쳐할 수 있게 하는 class

#모델불러오는 부분
    json_file = open("/var/www/html/model/model3.json", "r")
    loaded_model_json = json_file.read()
    json_file.close()

    loaded_model = model_from_json(loaded_model_json)

    loaded_model.load_weights("/var/www/html/model/model3_weights.h5")
    print("Loaded model from disk")

    #영상불러오는 부분
    face_classifier = cv2.CascadeClassifier('/var/www/html/model/haarcascade_frontalface_default.xml')
    #vidcap = cv2.VideoCapture('drive/My Drive/CNN/mp4/test.mp4')
    #vidcap = cv2.VideoCapture('drive/My Drive/yes.MOV')
    vidcap = cv2.VideoCapture('/var/www/html/videos/'+fileName)
    #vidcap = cv2.VideoCapture('drive/My Drive/nosomking.mp4')


    frame_count = int(vidcap.get(cv2.CAP_PROP_FRAME_COUNT))

    count = 0

    boolean = 'true'
    firCheck = 0
    secCheck = 0
    check = 0
    response = 'true'
    ciga = '1'
    smoke = '2'

    while(vidcap.isOpened()):
    # read()는 grab()와 retrieve() 두 함수를 한 함수로 불러옴
    # 두 함수를 동시에 불러오는 이유는 프레임이 존재하지 않을 때
    # grab() 함수를 이용하여 return false 혹은 NULL 값을 넘겨 주기 때문
        ret, frame = vidcap.read()

        if frame is not None:


          height, width, channel = frame.shape

          matrix = cv2.getRotationMatrix2D((width/2, height/2), 90, 1)
          dst = cv2.warpAffine(frame, matrix, (width, height))
          gray = cv2.cvtColor(dst,cv2.COLOR_BGR2GRAY)
          faces = face_classifier.detectMultiScale(gray,1.3,5)

          for(x,y,w,h) in faces:
              print("얼굴감지")
              cropped_face = dst[y:y+h, x:x+w]
              img = cv2.resize(cropped_face,(48,48))
              gray_fr = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
              pred = loaded_model.predict(gray_fr[np.newaxis, :, :, np.newaxis])
              pred = int(np.argmax(pred))
              print(pred)


              if pred == 2:
                firCheck = count
                print("2번요소감지시점")
                print(firCheck)
                ciga = str(time.mktime(datetime.today().timetuple()))+"c.jpg"
                cv2.imwrite("/var/www/html/images/"+ciga, cropped_face)

              if pred == 0:
                smoke = str(time.mktime(datetime.today().timetuple()))+"s.jpg"
                cv2.imwrite("/var/www/html/images/"+smoke, cropped_face)
                secCheck = count
                print("0번요소감지시점")
                print(secCheck)
                check = secCheck - firCheck
                print("둘의 시점차이")
                print(check)



              if check >0 and check < 20:
                print("부적합한 영상")
                response = 'false'
                boolean = 'false'
                conn = pymysql.connect(host='localhost', user='rayDalio', password='suMMit88$',
                                        db='youtube', charset='utf8')

                curs = conn.cursor(pymysql.cursors.DictCursor)
                sql = "UPDATE `video` SET `censorship`=%s,`censorPhoto`=%s  WHERE `videoName`=%s"
                   #cursor.execute(sql, ('webmaster@python.org',))
                   #sql = "UPDATE video SET censorship = 'false' WHERE videoName = fileName"
                curs.execute(sql,('false',"http://54.180.8.33/images/"+ciga+","+"http://54.180.8.33/images/"+smoke,fileName))
                conn.commit()
                conn.close()
                break

        if boolean == 'false':
          break
        if frame_count == count:
          break
        count += 1
    vidcap.release()
    return jsonify(response)











if __name__=="__main__":
    app.run(host='0.0.0.0')
