#!/usr/bin/env python
# coding: utf-8

# In[ ]:


#!/usr/bin/env python

from requests import Session
from requests.auth import HTTPBasicAuth
from zeep import Client
from zeep.transports import Transport
from urllib.error import HTTPError
from datetime import datetime
import mysql.connector

mydb = mysql.connector.connect(host='localhost',user='bitch',password='42069xd',database='Airplanes')
cursor = mydb.cursor()
data = []

username = 'bobjoe400'
apiKey = open('/var/www/data/apikey').readlines()[0]
wsdlFile = 'http://flightxml.flightaware.com/soap/FlightXML2/wsdl'

session = Session()
session.auth = HTTPBasicAuth(username,apiKey)
client = Client(wsdl=wsdlFile,transport=Transport(session=session))

def identifierupdate():
    grabdata('tail',"tail <> 'NaN'")
    global data
    for i,j in enumerate(data):
        try:
            ident = client.service.FlightInfo(j,1)['flights'][0]['ident']
            if j!=ident:
                q = "UPDATE Airplanes SET ident= %s WHERE tail= %s"
                val = (ident,j)
                cursor.execute(q, val)
        #goes here if the plane hasn't flown in 3 months
        except Exception as e:
            print(e)
            print('except'+j)
    mydb.commit()
    mydb.disconnect()
    #print(cursor.rowcount,"record inserted.")
        

def databaseupdate():
    grabdata('ident','ident IS NOT NULL')
    for i in data:
        vals=[]
        plane = client.service.InFlightInfo(i)
        q="UPDATE Airplanes set currapt=%s,origin=%s,destination=%s,speed=%s,altitude=%s,latitude=%s,longitude=%s WHERE ident=%s"
        #check if plane has flown recently
        if plane['timeout']:
            #check if plane is in the air
            if (plane['timeout'] != 'timed_out'):
                vals.append('In Flight')
                vals.append(airport(plane,'origin')[0])
                vals.append(airport(plane,'destination')[0])
                vals.append(int(plane['groundspeed']))
                vals.append(int(plane['altitude'])*100)
                vals.append(round(plane['latitude'],2))
                vals.append(round(plane['longitude'],2))
            else:
                currapt = (airport(plane,'origin'),airport(plane,'destination'))[client.service.FlightInfo(i,1)['flights'][0]['actualarrivaltime'] >0]
                vals.append(currapt[0])
                vals.append('On the ground')
                vals.append('On the ground')
                vals.append(0)
                vals.append(0)
                vals.append(round(currapt[1],2))
                vals.append(round(currapt[2],2))
        else:
            plane = client.service.FlightInfo(i,1)['flights'][0]
            currapt = airport(plane,'destination')
            vals.append(currapt[0])
            vals.append('On the ground')
            vals.append('On the ground')
            vals.append(0)
            vals.append(0)
            vals.append(round(currapt[1],2))
            vals.append(round(currapt[2],2))
        vals.append(i)
        cursor.execute(q,vals)
    mydb.commit()
    print("done")
    mydb.disconnect()

def airport(plane,val):
    if len(str(plane[val]))!=4 or str(plane[val]) == 'None':
        return('No data',0,0)
    else:
        apt = client.service.AirportInfo(plane[val]) 
        return(apt['name'],apt['latitude'],apt['longitude'])
    
def grabdata(column,condition):
    global data
    #selecting all the flights from the sql database that have a tail number and then cleaning up the output
#     q = "UPDATE Airplanes SET currapt=NULL, origin=NULL, destination=NULL, speed=NULL, altitude=NULL, latitude=NULL, longitude=NULL"
#     cursor.execute(q)
    q = "SELECT "+column+" FROM Airplanes WHERE "+condition
    cursor.execute(q)
    newdata = list(cursor.fetchall())
    for i,j in enumerate(newdata):
        data.append(newdata[i][0])
databaseupdate()

#todo add new rows to database for departure times and checking if depart is greater than system clock, if it is skip
#the update of that plane (as to only update planes in flight) 


# In[47]:


print(datetime.now().timestamp())


# In[172]:


plane = client.service.InFlightInfo('ETD411')
print(plane)
val = 'origin'
currapt = (airport(plane,'origin'),airport(plane,'destination'))[client.service.FlightInfo(plane['ident'],1)['flights'][0]['actualarrivaltime'] >0]
print(currapt)
# print((client.service.AirportInfo(currapt)['latitude'],0)[currapt == 'No data'])
# print((client.service.AirportInfo(plane[val])['name'],'No data')[len(str(plane[val]))!=4 or str(plane[val]) == 'None'])
val = 'destination'
if currapt == 'No data':
    print(0)
else:
    print(client.service.AirportInfo(currapt)['latitude'])
# print(plane)
# print(len(str(plane['origin'])))
# print(str(plane['destination'])=='None')
# print(len(str(plane['origin']))!=4)
# coords = (data['waypoints'].split(' ')[-2],data['waypoints'].split(' ')[-1])
# print(coords)


# In[170]:





# In[158]:


print(client.service.InFlightInfo('NKS922'))
print(datetime.now().timestamp())


# In[69]:


print(client.service.InFlightInfo('AAY1161'))


# In[71]:


print(client.service.FlightInfo('NKS922',1))


# In[72]:


print(client.service.FlightInfo('AAY1161',1))


# In[ ]:




