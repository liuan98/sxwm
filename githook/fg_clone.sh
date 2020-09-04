#!/usr/bin/env bash
cd $1;
#pwd;
git clone $2 $4;
chmod 777 -R $3/;
chown www:www -R $3/;

