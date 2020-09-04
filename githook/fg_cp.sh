#!/usr/bin/env bash
cd $2;
rm -rf $7;
cd $1;
cp -rf $7 $2;
cd $4;
rm -rf $6.blade.php;
cd $3;
cp -rf index.html $4/$6.blade.php;
chmod 777 -R $5/;
chown www:www -R $5/;

