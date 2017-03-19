import urllib2 
import xml.etree.ElementTree as ET
import time

def writeToFile(list):
	f = open("C:\Users\Rodrigo Morbach\Dropbox\Projeto\Programas e Arquivos\Python\RSSI_LQI\status.txt","a")	
	count = 0;
	for x in list:
		if(count == 0):
			f.write("\n"+x+";")			
		else:	
			f.write(x+";")
		count  = count+1
	f.close()
#Pega informacoes de forca de sinal
#@deprecated
'''def getRSSI(Url, mac):	
	req = urllib2.Request(url= Url, 
	                      data="""
	                           <?xml version="1.0" ?>
	                            <rci_request version="1.0">
	                                <do_command target="zigbee">
	                                    <query_state addr="%s"/>
	                                </do_command>
	                            </rci_request>
	                      """%(mac), 
	                      headers={'Content-Type': 'application/xml'})           
	f = urllib2.urlopen(req)
	dataText = f.read()
	root = ET.fromstring(dataText)	
	dicData.append(mac);
	#Busca a tag rssi
	dicData.append(root[0][0][0][12].text);
#root.attrib para atributos
#root.tag para a tag	
'''	
	
#Pega informacoes de qualidade de link
def getLqiRssi(Url):	
	#Contador para identificar o radio
	count = 0	
	dicData.append(coordinator)
	req = urllib2.Request(url= Url, 
	                      data="""
	                           <?xml version="1.0" ?>
	                            <rci_request version="1.0">
	                                <do_command target="zigbee">
	                                    <get_lqi addr="%s"/>
	                                    <query_state addr="%s" />
	                                    <query_state addr="%s" />
	                                </do_command>
	                            </rci_request>
	                      """%(coordinator, router1, router2),
	                      headers={'Content-Type': 'application/xml'})           	
	f = urllib2.urlopen(req)		
	dataText = f.read()	
	print dataText
	root = ET.fromstring(dataText);		
#root.attrib para atributos
#root.tag para a tag	
	if (root.tag == "rci_reply"):		
		for child in root:			
			for operacao in child:
				if(operacao.tag == "get_lqi"):
					for device in operacao:
						for neighbor in device:
							if(neighbor.tag == "neighbor"):
								dicData.append(neighbor[1].text)
								dicData.append(neighbor[8].text)
				elif(operacao.tag == "query_state"):
					for radio in operacao:
						if(count == 0):
							dicData.append(router1);
							dicData.append(radio[12].text);
						elif(count == 1):
							dicData.append(router2);
							dicData.append(radio[12].text);
						count = count+1;
	if(dicData):		
		return True
	

router1 = "00:13:a2:00:40:ac:6c:3b!"
router2 = "00:13:a2:00:40:ac:6a:af!"
coordinator = "00:13:a2:00:40:64:8c:42!" 
urlRCI = "http://192.168.15.149/UE/rci"
dtHora = time.strftime("%Y-%m-%d %H:%M:%S");

#Lista no formato [coordenador, radio, lqi, radio, lqi, radio, rssi, radio, rssi]
dicData = []
def main():    			
    if(getLqiRssi(urlRCI)):    
    	dicData.append(dtHora)
    writeToFile(dicData)
    print "Concluido"    
if __name__ == "__main__":	
    main()      

