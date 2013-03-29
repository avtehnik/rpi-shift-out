#! /bin/sh
sudo cp lighter /usr/local/bin/lighter
sudo cp service /etc/init.d/lighter
sudo chmod 755 /etc/init.d/lighter
sudo update-rc.d lighter defaults
sudo /etc/init.d/lighter start
