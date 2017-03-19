from threading import Timer
import sqlite3
import urllib2
import time
import base64
import xml.etree.ElementTree as ET
def createConnection():
	return sqlite3.connect("redesensora.sqlite")

def getXML():
    request = urllib2.Request("http://10.10.6.128/status.xml")
    base64string = base64.encodestring('aipo:admin').replace('\n', '')
    request.add_header("Authorization", "Basic %s" % base64string)   
    result = urllib2.urlopen(request)    
    stringXML = result.read()
    #transforma em XML
    root = ET.fromstring(stringXML)
    if (root.tag == "response"):     
        for child in root:     
            if(child.tag == "estado1"):                
                if(child.text == "ON"):
                    dicData['Tomada1'] = "1"
                else:
                    dicData['Tomada1'] = "0"
            elif(child.tag == "estado2"):                
                if(child.text == "ON"):
                    dicData['Tomada2'] = "1"
                else:
                    dicData['Tomada2'] = "0"
            elif(child.tag == "estado3"):                
                if(child.text == "ON"):
                    dicData['Tomada3'] = "1"
                else:
                    dicData['Tomada3'] = "0"
            elif(child.tag == "valor8"):
                dicData['Voltimetro AC'] = child.text
            elif(child.tag == "valor9"):
                dicData['Voltimetro DC'] = child.text
            elif(child.tag == "valor10"):
                dicData['Temperatura'] = child.text.encode('utf-8')

def getMac():
    conn = createConnection();
    cursor = conn.cursor();
    sql = "select mac from dispositivos where id = 1"    
    cursor.execute(sql)
    return cursor.fetchone()
def sendData(Url, mac, sensor, valor):
    try:        
        dataAtual = time.strftime("%Y-%m-%d %H:%M:%S")
        #cria um request http, com XML como conteudo 
        req = urllib2.Request(url=Url, 
                      data="<dadoColetado><dataHoraAtual>%s</dataHoraAtual><radio>%s</radio><sensor>%s</sensor><valor>%s</valor></dadoColetado>"%(dataAtual, mac, sensor, valor), 
                      headers={'Content-Type': 'application/xml'})
                      
        f = urllib2.urlopen(req)
        
        #imprime a resposta, caso exista
        print f.read()
        
		#retorna erro caso exista.
    except urllib2.URLError, e:
        print e
        print "Erro ao enviar dados para o servidor"
def prepareRequest(count):   
    print count    
    #value = random.randint(10, 20)  
    sendData(url, "[47-B6-11-7A-88-45!]", value)    
    time.sleep(60);
    print count
    prepareRequest(count+1);

def initProcess():
    #Dicionario com os elementos    
    getXML()     
    for key in dicData:        
        sendData(url, "[47-B6-11-7A-88-45!]", key, dicData[key])           
        #Espera Cinco segundos entre cada requisicao
        time.sleep(5)
    #Espera cinco minutos entre cada interacao
    time.sleep(300)
    initProcess()
dicData = {}
ipVPN = "10.10.254.6"
idRadio = "31"
url = "http://%s/logs/gravarlog/idradio/%s/idsensor/19/valor/"%(ipVPN, idRadio)    
initProcess();

