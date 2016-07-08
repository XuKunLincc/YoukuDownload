import httplib2
import json
import re
import os
from urllib import request


flag = 1
i = 1

def down_file(url, fileName):
    global flag
    flag = 1
    request.urlretrieve(url, fileName, Schedule)
    print("\n下载成功")
    fileUrl = os.path.abspath(fileName)
    print("视频保存在：%s" %fileUrl)

def Schedule(a,b,c):
    '''''
    a:已经下载的数据块
    b:数据块的大小
    c:远程文件的大小
   '''
    per = 100.0 * a * b / c
    if per > 100 :
        per = 100
    print ('\r正在下载：%.2f%%' % per,end='')

def down():
    global flag
    global i
    
    while True:
        fileName = input("请输入视频保存文件名")
        if fileName != "" or fileName != None or fileName!= '\n':
            break
    
    fileName = fileName + ".mp4"
    while True:
        try:
            down_file(url, fileName)
        except BaseException as e:
            print("\r由于优酷限制,下载失败，第%d 次重试下载中...\n"% i,end='')
            flag = 0
            i = i + 1
        if flag == 1:
            break

print("该工具仅供学习使用，不建议使用该工具进行下载优酷视频")
print("作者：Ckl")
print("博客网址： www.atqiao.cn\n")

reStr = r"(?<=id_).+(?=.html)"
urlToPrase = input("输入要下载的优酷视频链接：\n")
sid = re.findall(reStr,urlToPrase)
apiUrl = "http://www.atqiao.cn/youku.php?id=" + "".join(sid)
html = request.urlopen(apiUrl).read()
html = html.decode('utf-8')
jsonObj = json.loads(html)
url = jsonObj['URL']

while True:
    ask = input("是否使用第三方下载器进行下载：Y/N\n")
    if ask == "Y" or ask == "yes" or ask == "Yes" or ask == 'y':
        print("复制以下链接地址到其他下载器中进行下载\n")
        print(url + '\n')
        break
    elif ask == 'N' or ask == 'No' or ask == 'no' or ask == 'n':
        down()
        break

print("bye bye")



