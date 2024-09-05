#!/bin/bash
rm -r /backup/*
rm /backup/bkup.tar.gz
set -e
mariabackup --backup \
   --target-dir=/backup/ \
   --user=mariabackup \
   --password=fhdsgffds
# sudo tar -czvf bkup.tar.gz mariadb-backup/
 
