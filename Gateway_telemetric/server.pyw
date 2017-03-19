#!/usr/bin/env python
#Script que cria servidor e "escuta"  na porta especificada, 80 no caso
#Habilita tambem o uso de scripts cgi
import BaseHTTPServer
import CGIHTTPServer
import cgitb; cgitb.enable()  ## This line enables CGI error reporting, retorno de mensagens de erro melhor especificadas
 
server = BaseHTTPServer.HTTPServer
handler = CGIHTTPServer.CGIHTTPRequestHandler
server_address = ("", 8000)
handler.cgi_directories = ["/"]
 
httpd = server(server_address, handler)
httpd.serve_forever()