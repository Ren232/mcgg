#!/usr/bin/env bash
function jsonval {
    temp=`echo $json | sed 's/\\\\\//\//g' | sed 's/[{}]//g' | awk -v k="text" '{n=split($0,a,","); for (i=1; i<=n; i++) print a[i]}' | sed 's/\"\:\"/\|/g' | sed 's/[\,]/ /g' | sed 's/\"//g' | grep -w $prop`
    echo ${temp##*|}
}
dns=`curl -v https://api.dynu.com/v1/oauth2/token -H "Accept: application/json" -H "Accept-Language: en_US" -u "dcf1406d-3a91-4dd8-9b36-f9a5da0b7164:SWKW28Y9ffaN9pDJNUM8cW6cUSgMYg" -d "grant_type=client_credentials" | python3 -c "import sys, json; print(json.load(sys.stdin)['accessToken'])"`
json=`curl -v https://api.dynu.com/v1/dns/records/techimpact.giize.com -H "Content-Type: application/json" -H "Authorization: Bearer $dns"`
prop='id'
res=`jsonval`
res2=${res/\id:2410174/}
res3=${res2/\id:/}
id=${res3/\ /}
echo ""
echo "!----- REMOVING DNS RECORD ID:$id"
echo ""
sleep 2
curl -v https://api.dynu.com/v1/dns/record/delete/$id \
        -H "Content-Type: application/json" \
        -H "Authorization: Bearer $dns"
echo ""
echo "!----- ADDING NEW DNS RECORD"
echo ""
sleep 2
port=$(php -r "require_once 'panel/inc/lib.php';$full=ngrok_stat('GGJohny');$nospace=str_replace(' ','',$full);list($1,$2)=explode(':',$nospace);echo $2;")
curl -v -X POST https://api.dynu.com/v1/dns/record/add \
        -d "{\"port\":\"$port\", \"priority\":\"0\", \"weight\":\"5\", \"target\":\"0.tcp.ngrok.io\", \"service\":\"minecraft\", \"protocol\":\"tcp\", \"domain_name\":\"techimpact.giize.com\", \"node_name\":\"\", \"record_type\":\"SRV\", \"ttl\":\"300\", \"state\":\"true\" }" \
        -H "Content-Type: application/json" \
        -H "Authorization: Bearer $dns"
