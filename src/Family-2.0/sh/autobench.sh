#!/bin/bash

if [ $# -eq 0 ]; then
    echo "Usage: $0 <host> <uri>"
    echo ""
    exit
fi

DM=$1
URL=$2

#--signle_host 只测单机
#--host1 测试主机地址
#--uri1 host1 测试URI
#--quiet 安静模式
#--low_rate 测试时最低请求数(指 httperf)
#--hight_rate 测试时最高请求数
#--rate_step 每次测试请求数增加步长
#--num-call 每连接中发起联接数，一般是1
#--num_conn 测试联接数
#--file 测试结果输出的 tsv文件

autobench \
    --single_host \
    --host1=$DM \
    --port1=80 \
    --uri1=$URL \
    --low_rate=1 \
    --high_rate=50 \
    --rate_step=1 \
    --num_call=1 \
    --num_conn=50 \
    --timeout=5 \
    --file ./$DM.tsv
