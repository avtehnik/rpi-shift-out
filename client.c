/*+--------------------------------------------------------------+
  |            client.c  -  description                          |
  |                -------------------                           |
  | begin      : 08/01/2010 18.31                                |
  | copyright  : (C) 2010 xAppSoftware                           |
  | author     : Luigi D'Andrea                                  |
  | email      : gg1 ( at ) xappsoftware dot com                 |
  | compiling  : gcc -o client client.c                          |
  |                                                              |
  | Latest version on http://www.xappsoftware.com                |
  +--------------------------------------------------------------+
  | udp client-server may be redistributed and modified under    |
  | certain conditions. This software is distributed on an       |
  | "AS IS" basis WITHOUT WARRANTY OF ANY KIND, either express or|
  | implied.  See the file License.txt for details.              |
  +--------------------------------------------------------------+*/

/*+--------------------------------------------------------------+
  | SYSTEM INCLUDES                                              |
  +--------------------------------------------------------------+*/
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#include <arpa/inet.h>
#include <netinet/in.h>
#include <sys/types.h>
#include <sys/socket.h>

/*+--------------------------------------------------------------+
  | SPECIFIC INCLUDES                                            |
  +--------------------------------------------------------------+*/
#include "clientserver.h"

/*+--------------------------------------------------------------+
  | Function name :  main                                        |
  | Parameters    :  The destination IP address                  |
  |                  The port number                             |
  | Description   :  The simple udp client main                  |
  +--------------------------------------------------------------+*/
int main(int argc, char **argv)
{
	struct sockaddr_in  si_server;
	int                 port, s, i, slen=sizeof(si_server);
	char                buf[BUFLEN];
	char                srv_addr[16];

	memset(srv_addr, 0, 16);
	if(argc<3)
	{
		fprintf(stderr, "Usage: %s <destination IP address> <port number>\n", argv[0]);
		exit(EXIT_FAILURE);
	}

	port=atoi(argv[2]);
	printf("port = %d\n", port);
	if(port<1024)
	{
		fprintf(stderr, "Usage: %s <destination IP address> <port number>\n", argv[0]);
		fprintf(stderr, "\twhere <port number> shall be > 1023\n");
		exit(EXIT_FAILURE);
	}

	if ((s=socket(AF_INET, SOCK_DGRAM, IPPROTO_UDP))==-1)
	{
		perror("socket");
		exit(EXIT_FAILURE);
	}
    memcpy(srv_addr, argv[1], strlen(argv[1]));

	memset((char *) &si_server, 0, sizeof(si_server));
	si_server.sin_family   =  AF_INET;
	si_server.sin_port     =  htons(port);
	if (inet_aton(srv_addr, &si_server.sin_addr)==0)
	{
		fprintf(stderr, "inet_aton() failed\n");
		exit(EXIT_FAILURE);
	}
    /*+---------------------------------------------------------------------+
      | If the fourth parameters is present we need to send it instead      |
      | of a sequence o packets. We can use this case when we want to       |
      | terminate the server sending to it the string ".quit."              |
      +---------------------------------------------------------------------+*/
	if(argc==4)
	{
		sprintf(buf, "%s", argv[3]);
		if (sendto(s, buf, strlen(argv[3]), 0, (struct sockaddr *)&si_server, slen)==-1)
		{
			perror("sendto()");
			exit(EXIT_FAILURE);
		}
	}
	else
	{
		for (i=0; i<NPACK; i++)
		{
			printf("Sending packet %d\n", i);
			sprintf(buf, "This is packet %d\n", i);
			if (sendto(s, buf, BUFLEN, 0, (struct sockaddr *)&si_server, slen)==-1)
			{
				perror("sendto()");
				exit(EXIT_FAILURE);
			}
		}
	}
	close(s);
	return 0;
}
