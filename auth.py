from flask import Flask, render_template, redirect, session, url_for
from flask_mysqldb import MySQL

app = Flask(__name__)

#SQL Login
app.config["MYSQL_HOST"] = "rahi's IP"
app.config["MYSQL_USER"] = "rahi"
app.config["MYSQL_PASSWORD"] = "database"
app.config["MYSQL_DB"] = "users"

database = MySQL(app)

@app.route('/', methods=['GET', 'POST'])
def index():
	if request.method == 'POST':
		if 'username' in request.form and 'password' in request.form:
			username = request.form['username']
			password = request.form['password']
			cursor = db.connection.cursor(MySQLdb.cursors.DictCursor)
			cursor.execute("SELECT * FROM users WHERE username=%s AND password=%s", (username, password)
			info = cursor.fetchall()
			if info is not None:
				if info['username'] == username and info['passsword'] == password:
					return "Login Successful" #tell frontend auth is good
				else:
					return "Unauthenticated"
	
