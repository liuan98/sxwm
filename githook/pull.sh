#!/usr/bin/env bash
cd $1;
git fetch --all;
git reset --hard origin/master;
chmod 777 -R $2/;
chown www:www -R $2/;

