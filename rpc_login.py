from flask import render_template, request, flash, redirect, url_for
from flask_login import login_user, login_required, logout_user, current_user
from flask_mysqldb import MySQL

import pika

app = Flask(__name__)

#SQL Login
app.config["MYSQL_HOST"] = "192.168.194.201"
app.config["MYSQL_USER"] = "rahi"
app.config["MYSQL_PASSWORD"] = "database"
app.config["MYSQL_DB"] = "users"

database = MySQL(app)

creds=pika.PlainCredentials(username='nick', password='password123')
connection=pika.BlockingConnection(
        pika.ConnectionParameters(host ='192.168.194.33', credentials=creds))

channel = connection.channel()

channel.queue_declare(queue='rpc_login')

#PUT CODE FOR AUTH ON BACK END
@auth.route('/login', methods=['GET', 'POST'])
def login():
	if request.method == 'POST':
		if 'username' in request.form and 'password' in request.form:
			username = request.form.get('username')
			password = request.form.get('password')
			cursor = db.connection.cursor(MySQLdb.cursors.DictCursor)
			cursor.execute("SELECT * FROM users WHERE username=%s AND password=%s", (username, password)
			info = cursor.fetchall()
			
			if info['username'] == username and info['passsword'] == password:
				return redirect(url_for('home.html'))
			else:
				flash('Unauthenticated')
				return render_template("login.html")

def on_request(ch, method, props, body):

    print(" [*] Processing... ")
    response = login()

    ch.basic_publish(exchange='',
                     routing_key=props.reply_to,
                     properties=pika.BasicProperties(correlation_id = \props.correlation_id), body=response)
    ch.basic_ack(delivery_tag=method.delivery_tag)

channel.basic_qos(prefetch_count=1)
channel.basic_consume(queue='rpc_login', on_message_callback=on_request)

print(" [x] Awaiting RPC requests")
channel.start_consuming()
