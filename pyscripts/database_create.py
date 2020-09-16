#!/usr/bin/env python
# coding: utf-8

# In[2]:


import mysql.connector
import numpy as np
mydb = mysql.connector.connect(host="localhost",user="bitch",password="42069xd",database="Airplanes")
mycursor = mydb.cursor()

sql = "INSERT INTO Airplanes (line,tail,type) VALUES (%s,%s,%s)"
planes = np.load('/var/www/data/data.npy')
val=[]
for i in planes:
    currval = (int(i[0]),str(i[3]),str(i[1]))
    val.append(currval)

mycursor.executemany(sql, val)

mydb.commit()

print(mycursor.rowcount, "was inserted.")


# In[ ]:





# In[ ]:




