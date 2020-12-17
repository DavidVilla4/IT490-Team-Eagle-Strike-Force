#!/bin/sh

SERVER="23.3.198.99"
CLIENT="25.2.225.100"
BROKER="25.1.246.116"

iptables --flush
iptables --policy INPUT DROP
iptables --policy OUTPUT ACCEPT
iptables --policy FORWARD DROP

iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -o lo -j ACCEPT

iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -A OUTPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

iptables -A INPUT -i eth0 -p tcp -s $CLIENT -d $SERVER --dport 5672 -j ACCEPT

iptables -A INPUT -i eth0 -p tcp -s $BROKER -d $SERVER --dport 5672 -j ACCEPT

iptables -A INPUT -i eth0 -p tcp -s $CLIENT -d $SERVER --dport 80 -j ACCEPT

iptables -A INPUT -i eth0 -p tcp -s $CLIENT -d $SERVER --dport 443 -j ACCEPT

iptables -A INPUT -i eth0 -p tcp -s $CLIENT -d $SERVER --dport 22 -j ACCEPT
