#LIBRERÍAS
from flask import Flask, redirect, render_template, request, url_for
from flask_mysqldb import MySQL

#CREAR APLICACIÓN
app = Flask(__name__)

app.config["TEMPLATES_AUTO_RELOAD"] = True

#CONEXIÓN A BASE DE DATOS
app.config["MYSQL_HOST"] = "localhost"
app.config["MYSQL_USER"] = "root"
app.config["MYSQL_PASSWORD"] = "1234"
app.config["MYSQL_DB"] = "crudb_almbus"

app.secret_key = "mysecretkey"

#Inicializar la conexión a la base de datos
mysql = MySQL(app)

#RUTA PRINCIPAL
@app.route("/")
def index():
    con = mysql.connection.cursor()
    con.execute("SELECT * FROM tb_almbums")
    albums = con.fetchall()
    print(conAlbums)
    return render_template("index.html", albums=albums)

#EJECUTAR APLICACIÓN
if __name__ == "__main__":
    app.run(debug=True)

#RUTA/guardar
@app.route("/guardar", methods=["POST"])
def guardar():
    if request.method == "POST":
        titulo = request.form["titulo"]
        Autor = request.form["autor"]
        Anio = request.form["anio"]
        Calificación = request.form["calificacion"]
        Reseña = request.form["reseña"]
        print(titulo, Autor, Anio, Calificación, Reseña)

        """ Conexión a la base de datos """
        con = mysql.connection.cursor()
        con.execute(
            "INSERT INTO tb_almbums (titulo, autor, anio, calificacion, reseña) VALUES (%s, %s, %s, %s, %s)",
            (titulo, Autor, Anio, Calificación, Reseña))
        mysql.connection.commit()
    flash ("Datos guardados correctamente")
    return redirect(url_for("index"))