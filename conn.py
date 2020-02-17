import mysql.connector
conn = mysql.connector.connect(
    host='localhost',
    user='ww2web',
    passwd='wod23sep'
)
cursor = conn.cursor()
cursor.execute('SELECT * FROM footages LIMIT 5')

for row in cursor: print(row)
conn.close()
