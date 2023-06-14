#!/usr/bin/bash

/bin/systemctl -q is-enabled nm_gadget.service 

status=$?

if [[ "$status" == 0 ]]; then 
echo "Le RPI 0 est en mode RDNIS (en mode Keypass), passage en mode classique..."
echo " "

else
echo "Le RPI 0 est en mode classique, passage en mode RDNIS (en mode Keypass)..."
echo " "

fi

#Desactivation du serveur .local
if [[ "$status" == 1 ]]; then
sudo systemctl enable nm_gadget.service
sudo systemctl disable dhcpcd.service

else 
#Activation du mode .local
sudo systemctl disable nm_gadget.service
sudo systemctl enable dhcpcd.service

fi


sleep 60
sudo reboot
