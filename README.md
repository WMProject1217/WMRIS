# WMRIS
Server Status - Servers' Probes Set up with WMRIS

只需要配置php和python然后执行启动脚本即可使用。
php已测试版本: 7.3.4nts
python已测试版本: 3.8.10
python需求:
pip install psutil
pip install requests

WMRISC有一个配置文件config.json将会在启动后创建，其含义为:
{"location": "Local Network", "id": "0", "server": "http://127.0.0.1:12200/in", "nickname": "localhost"}
location: 显示的位置，默认值是Local Network
id: 设备id，最好不要重复，默认值是0
server: 服务地址，默认值是http://127.0.0.1:12200/in
nickname: 显示的名称，默认值是计算机的网络名称

WMRISS默认在12200端口上使用HTTP协议。
常用页面
http://127.0.0.1:12200/ui.php 图形用户界面
http://127.0.0.1:12200/index.php 数据的转储
http://127.0.0.1:12200/out?id=0 转储指定id的数据
http://127.0.0.1:12200/wmscapi.php?id=0 转储指定id的数据

ui.php的主题来自 https://github.com/cokemine/hotaru_theme
