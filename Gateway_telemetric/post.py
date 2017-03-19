import cgi
import xml.etree.ElementTree as ET
#para conexao com o banco
import sqlite3
import cgitb
cgitb.enable()

#Cria uma coenxao com o banco
def createConecction():
	return sqlite3.connect("redesensora.sqlite")	
#Consulta o estado do dispositivo
def queryState(mac):
	conn = createConecction();
	cursor = conn.cursor()	
	sql = "select * from dispositivos where mac=?"
	cursor.execute(sql, [(mac)])
	data = cursor.fetchone()  # ou use fetchone()	
	print "Content-type:text/xml"	
	print	
	print "<?xml version='1.0' ?>"
	print """<rci_reply version="1.0">
				<do_command target="zigbee">
				<query_state addr="%s">
				<radio>
				<pan_id>%s</pan_id>				
				<net_addr>%s</net_addr>				
				<ext_pan_id>%s</ext_pan_id>		
				<rssi>%s</rssi>		
				</radio>
				</query_state>
				</do_command>
			</rci_reply>"""%(mac, data[3], data[3], data[3], data[9])

#Consulta as configuracoes do dispositivo
def querySetting(mac):
	conn = createConecction();
	cursor = conn.cursor()	
	sql = "select * from dispositivos where mac=?"
	cursor.execute(sql, [(mac)])
	data = cursor.fetchone()  # ou use fetchone()		
	print data	
	print "Content-type:text/xml"	
	print	
	print "<?xml version='1.0' ?>"
	print """
		<rci_reply version="1.0">
				<do_command target="zigbee">
				<query_setting addr="%s">
				<radio>				
				<ext_pan_id>%s</ext_pan_id>								
				<node_id>%s</node_id>
				<dest_addr>%s</dest_addr>
				</radio>
				</query_setting>
				</do_command>
		</rci_reply>"""%(mac, data[2], data[1], data[4])	
#Pesquisa por dispositivos na rede	
def descobertaDispositivos():
	conn = createConecction();
	cursor = conn.cursor()	
	sql = "select * from dispositivos"
	cursor.execute(sql)
	data = cursor.fetchall()  # ou use fetchone()		
	print "Content-type:text/xml"	
	print	
	print "<?xml version='1.0' ?>"
	print """<rci_reply version="1.1">
			   <do_command target="zigbee">
			      <discover>
			         <device index="1">
			            <type>1</type>
			            <ext_addr>%s</ext_addr>
			            <net_addr>%s</net_addr>
			            <parent_addr>%s</parent_addr>
			            <profile_id>0xc105</profile_id>
			            <mfg_id>0x101e</mfg_id>
			            <device_type>0x11</device_type>
			            <node_id>%s</node_id>
			            <contact_time>6410</contact_time>
			            <fw_status>
			               <hw_version>0x1a44</hw_version>
			               <fw_version>0x21a7</fw_version>
			               <status>Up to date</status>
			               <file />
			               <updater />
			               <error_code>0</error_code>
			               <error_text />
			               <tries>0</tries>
			            </fw_status>
			         </device>         
			      </discover>
			   </do_command>
			</rci_reply>""" % (data[0][2], data[0][3], data[0][3],data[0][1])
#Altera configuracoes do dispositivo
def setSetting(mac, name, enderecoDestino):	
	sql = "Update dispositivos set nome = '%s', dest_id = '%s' where mac = '%s'" %(name, enderecoDestino, mac)
	conn = createConecction();
	cursor = conn.cursor()
	cursor.execute(sql)
	conn.commit() 
	print "Content-type:text/xml"	
	print
	print """
		<?xml version="1.0" encoding="UTF-8"?>
			<rci_reply version="1.1">
			   <do_command target="zigbee">
			      <set_setting addr="%s">
			         <radio />
			      </set_setting>
			   </do_command>
			</rci_reply>"""%(mac)

#recebe os dados de entrada via POST
input = cgi.FieldStorage()
#verifica se eh um arquivo
raw_data = ""
if getattr(input, 'file'):
    raw_data = input.file.read();
#captura a string de entrada pelo parametro 'data'
string = raw_data

if string == "":	
	print "Content-type:text/xml"
	print
	print "<?xml version='1.0' ?>"	
	print "<response><message>Nada encontrado</message></response>"
else:			
	root = ET.fromstring(string);
#root.attrib para atributos
#root.tag para a tag
	if (root.tag == "rci_request"):		
		for child in root:			
			for operacao in child:
				if (operacao.tag == "discover"):
					descobertaDispositivos()
				elif(operacao.tag == "query_setting"):				 
					mac = operacao.attrib.get('addr')					
					querySetting(mac)
				elif(operacao.tag == "query_state"):				 
					mac = operacao.attrib.get('addr')				
					queryState(mac)
				elif(operacao.tag == "set_setting"):
					mac = operacao.attrib.get('addr')
					nome = operacao[0][1].text									
					enderecoDestino = operacao[0][2].text					
					setSetting(mac, nome, enderecoDestino)
								 				
						

