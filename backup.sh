#!/bin/bash
rm -r backup/*
set -e
docker exec db mariabackup --backup \
   --target-dir=/backup/ \
   --user=mariabackup \
   --password=fhdsgffds
cd backup/
tar -czvf ../tar/bkup.tar.gz *
rm -r *