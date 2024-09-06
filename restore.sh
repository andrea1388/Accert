#!/bin/bash
echo "delete bkup dir"
rm -r backup/*

set -e
echo "untar"
tar -xzvf tar/bkup.tar.gz -C backup/


echo "stop db"
docker container stop db

docker run --rm -v ./datadir:/var/lib/mysql -v ./backup:/backup mariadb:10.8.2 mariabackup --prepare --target-dir=/backup 


echo "delete datadir"
rm -r datadir/*

echo "restore"

docker run --rm -v ./datadir:/var/lib/mysql -v ./backup:/backup mariadb:10.8.2 mariabackup --copy-back --target-dir=/backup

echo "start db"
docker container start db
#sudo rm bkup.tar.gz