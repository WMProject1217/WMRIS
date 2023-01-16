import psutil
import platform
import json
import time
import socket
import requests
import struct
import codecs
import wmi
import winreg
import os
from datetime import datetime

def WMJson_GetValue(filepath, itemname, fallback):
    if os.path.exists(filepath):
        loadjsontf = {}
        with open(filepath,'r',encoding='utf-8') as jsonopenex:
            loadjsontf = json.load(jsonopenex)
        if itemname in loadjsontf:
            return loadjsontf[itemname]
        else:
            return fallback
    else:
        return fallback

def WMJson_SetValue(filepath, itemname, itemvalue):
    if os.path.exists(filepath):
        loadjsontf = {}
        with open(filepath,'r',encoding='utf-8') as jsonopenex:
            loadjsontf = json.load(jsonopenex)
        loadjsontf[itemname] = str(itemvalue)
        with open(filepath, 'w', encoding='utf-8') as file_obj:
            json.dump(loadjsontf, file_obj)
        return 0
    else:
        loadjsontf = {}
        loadjsontf[itemname] = str(itemvalue)
        with open(filepath, 'w', encoding='utf-8') as file_obj:
            json.dump(loadjsontf, file_obj)
        return 0

WMRISC_NETNAME = platform.node()
WMRISC_COMPUTERARCH = platform.architecture()[0]
WMRISC_PLATFORMTYPE = winreg.QueryValueEx(winreg.OpenKey(winreg.HKEY_LOCAL_MACHINE,"SOFTWARE\\Microsoft\\Windows NT\\CurrentVersion"),"ProductName")[0]
WMRISC_PLATFORMTYPEL = winreg.QueryValueEx(winreg.OpenKey(winreg.HKEY_LOCAL_MACHINE,"SOFTWARE\\Microsoft\\Windows NT\\CurrentVersion"),"BuildLabEx")[0]
WMRISC_CPUNAME = wmi.WMI().Win32_Processor()[0].Name
WMRISC_CPUARCH = platform.machine()
WMRISC_CPUTYPE = platform.processor()
WMRISC_NICKNAME = WMJson_GetValue("./config.json","nickname",WMRISC_NETNAME)
WMRISC_LOCATION = WMJson_GetValue("./config.json","location","Local Network")
WMRISC_ID = WMJson_GetValue("./config.json","id","0")
WMRISC_SERVER = WMJson_GetValue("./config.json","server","http://127.0.0.1:12200/in")
WMJson_SetValue("./config.json","nickname",WMRISC_NICKNAME)
WMJson_SetValue("./config.json","location",WMRISC_LOCATION)
WMJson_SetValue("./config.json","id",WMRISC_ID)
WMJson_SetValue("./config.json","server",WMRISC_SERVER)

def status():
    cpu_count = psutil.cpu_count(logical=False)
    xc_count = psutil.cpu_count()
    cpu_slv = round((psutil.cpu_percent(1)), 2)
    memory = psutil.virtual_memory()
    total_nc = float(memory.total)
    used_nc = float(memory.used)
    free_nc = float(memory.free)
    syl_nc = memory.percent
    swapex = psutil.swap_memory()
    total_sp = float(swapex.total)
    used_sp = float(swapex.used)
    free_sp = float(swapex.free)
    syl_sp = swapex.percent
    disktotal = 0
    diskused = 0
    for disk in psutil.disk_partitions():
        if 'cdrom' in disk.opts or disk.fstype == '':
            continue
        disk_name_arr = disk.device.split(':')
        disk_name = disk_name_arr[0]
        disk_info = psutil.disk_usage(disk.device)
        disktotal = disktotal + disk_info.total
        diskused = diskused + disk_info.used
    boot_time = psutil.boot_time()
    boot_time_obj = datetime.fromtimestamp(boot_time)
    now_time = datetime.now()
    bootup_time = now_time - boot_time_obj
    loadjsontf = {}
    loadjsontf["status"] = "RUN"
    loadjsontf["nickname"] = WMRISC_NICKNAME
    loadjsontf["type"] = WMRISC_PLATFORMTYPE
    loadjsontf['typel'] = WMRISC_PLATFORMTYPEL
    loadjsontf["location"] = WMRISC_LOCATION
    loadjsontf["uptime"] = str(bootup_time).split('.')[0]
    loadjsontf["computerarch"] = WMRISC_COMPUTERARCH
    loadjsontf["netname"] = WMRISC_NETNAME
    loadjsontf["ipaddress"] = socket.gethostbyname(socket.gethostname())
    loadjsontf["cpuname"] = WMRISC_CPUNAME
    loadjsontf["cpuarch"] = WMRISC_CPUARCH
    loadjsontf["cputype"] = WMRISC_CPUTYPE
    loadjsontf["cpucoreval"] = str(cpu_count)
    loadjsontf["cputhreadval"] = str(xc_count)
    loadjsontf["cpuusage"] = str(cpu_slv)
    loadjsontf["memtotal"] = str(total_nc)
    loadjsontf["memused"] = str(used_nc)
    loadjsontf["memfree"] = str(free_nc)
    loadjsontf["memusage"] = str(syl_nc)
    loadjsontf["swaptotal"] = str(total_sp)
    loadjsontf["swapused"] = str(used_sp)
    loadjsontf["swapfree"] = str(free_sp)
    loadjsontf["swapusage"] = str(syl_sp)
    loadjsontf["disktotal"] = str(disktotal)
    loadjsontf["diskused"] = str(diskused)
    loadjsontf["diskusage"] = str(format(diskused/disktotal*100,'.2f'))
    loadjsontf["perx"] = str()
    loadjsontf["perf"] = str()
    #return '{"status":"RUN","name":"'+WMRISC_NAME+'","type":"'+platform.system()+' '+platform.version()+'","location":"'+WMRISC_LOCATION+'","uptime":"'+str(bootup_time).split('.')[0]+'",'+'"cpucoreval":"'+str(cpu_count)+'","cputhreadval":"'+str(xc_count)+'","cpuusage":"'+str(cpu_slv)+'%",'+'"memtotal":"'+str(total_nc)+'","memused":"'+str(used_nc)+'","memfree":"'+str(free_nc)+'","memusage":"'+str(syl_nc)+'%",'+'"disktotal":"'+str(ConvertByteNumber(disktotal))+'","diskused":"'+str(ConvertByteNumber(diskused))+'","diskusage":"'+ str(format(diskused/disktotal*100,'.2f'))+'%"}'
    return json.dumps(loadjsontf)

while 1:
    url = WMRISC_SERVER+"?id="+WMRISC_ID+"&json="+status()
    res = requests.get(url)
    print(res.text)
    time.sleep(2)

