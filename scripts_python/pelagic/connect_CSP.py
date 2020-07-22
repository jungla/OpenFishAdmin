import cx_Oracle
connection = cx_Oracle.connect('sde/sde@orcl')
cursor = connection.cursor()
querystring = "select * from Parcels"
cursor.execute(querystring)
