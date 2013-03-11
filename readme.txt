To start server

sudo ./udp_server  <port> <delay>


./udp_client  10.0.1.12 1099 7



// значение в val, выводятся начиная с младшего бита
for (size_t i=0; i<8; i++)
  printf("%c", '0' + ((val & (1<<i))?1:0));

// значение в val, выводятся начиная со старшего бита
for (size_t i=0; i<8; i++)
  printf("%c", '0' + ((val & (0x80>>i))?1:0));
