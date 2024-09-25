#!/bin/bash
rm -r backup/*
rm tar/prev.bkup.tar.gz
mv tar/bkup.tar.gz tar/prev.bkup.tar.gz
set -e
docker exec db mariabackup --backup \
   --target-dir=/backup/ \
   --user=mariabackup \
   --password=fhdsgffds
cd backup/
tar -czvf ../tar/bkup.tar.gz *
rm -r *