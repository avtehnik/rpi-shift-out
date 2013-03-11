#############################################################
# File:              Makefile                               #
#                                                           #
# Rel.:              1.0.0                                  #
#                                                           #
# Product:           simple udp client server               #
#                                                           #
# Author:            Luigi D'Andrea                         #
#                    Copyleft 2010 www.xappsoftware.com     #
#                                                           #
# Creation date:     04 Jan 2010                            #
#                                                           #
# Description:       Makefile for the simple udp client     #
#                    server apps.                           #
# Changes:                                                  #
#       <Rel.> - <Author> - <Date>                          #
#       <Description>                                       #
#                                                           #
#############################################################

CLI_OBJS   =  client.o
SER_OBJS   =  server.o
DEM_OBJS   =  demonize.o

CLI_APP    =  udp_client
SER_APP    =  udp_server

CFLAGS=-pedantic -Wall -g -O2

all: $(CLI_APP) $(SER_APP)

$(CLI_APP): $(CLI_OBJS)
	$(CC) -o $(CLI_APP) $(CLI_OBJS)

$(SER_APP): $(SER_OBJS)
	$(CC) -o $(SER_APP) $(SER_OBJS) $(DEM_OBJS)

client.o: clientserver.h

server.o: clientserver.h demonize.o

demonize.o: demonize.h

splint:
	splint -I. client.c server.c demonize.c

light: all
	strip $(CLI_APP) $(SER_APP)

clean:
	$(RM) *.o
	$(RM) $(CLI_APP)
	$(RM) $(SER_APP)

clobber: clean
	$(RM) core*

