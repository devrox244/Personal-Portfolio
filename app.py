from flask import Flask, request, jsonify, render_template, redirect, url_for, session
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
import os
from dotenv import load_dotenv

load_dotenv(".env")

app = Flask(__name__)
CORS(app=app)
app.secret_key = os.getenv("SECRET_KEY")

ADMIN_USERNAME = os.getenv("USERNAME")
ADMIN_PASSWORD = os.getenv("PASSWORD")

# SQLite database
app.config['SQLALCHEMY_DATABASE_URI'] = os.getenv("INTERNAL_DB_URL", 'sqlite:///database.db')
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

# Upload folder for achievements
app.config["UPLOAD_FOLDER"] = "static/uploads"
os.makedirs(app.config["UPLOAD_FOLDER"], exist_ok=True)

db = SQLAlchemy(app)

# Database Model
class Achievement(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), nullable=False)
    description = db.Column(db.String(200), nullable=False)
    date = db.Column(db.String(50), nullable=False)
    image = db.Column(db.String(500), nullable=False) # Increased length for long URLs

# Create the database
with app.app_context():
    db.create_all()

# home route
@app.route("/")
def home():
    achievements = Achievement.query.all()
    return render_template("index.html", achievements=achievements, admin=session.get("logged_in"))

# Login
@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        username = request.form["username"]
        password = request.form["password"]
        if username == ADMIN_USERNAME and password == ADMIN_PASSWORD:
            session["logged_in"] = True
            return redirect(url_for("home"))
        else:
            session["logged_in"] = False
            return redirect(url_for("home"))
    return render_template("login.html")

# **Logout Route**
@app.route("/logout")
def logout():
    session.pop("logged_in", None)
    return redirect(url_for("home"))

# get achievements
@app.route("/achievements", methods=["GET"])
def get_achievements():
    achievements = Achievement.query.all()
    return jsonify([{
        "id": a.id,
        "title": a.name,
        "description": a.description,
        "date": a.date,
        "image": a.image
    } for a in achievements])

# add achievements
@app.route("/add_achievement", methods=["POST"])
def add_achievement():
    if "logged_in" not in session or not session["logged_in"]:
        return redirect(url_for("login"))

    if request.method == "POST":
        # We now expect a URL string from the form, not a file
        image_url = request.form["image_url"] 

        new_achievement = Achievement(
            name=request.form["name"],
            description=request.form["description"],
            date=request.form["date"],
            image=image_url
        )

        db.session.add(new_achievement)
        db.session.commit()

    return redirect(url_for("home"))

# delete achievements
@app.route("/delete_achievement/<int:id>", methods=["DELETE"])
def delete_achievement(id):
    achievement = Achievement.query.get(id)
    
    if achievement:
        # Delete the image file
        image_path = os.path.join(app.config["UPLOAD_FOLDER"], achievement.image)
        if os.path.exists(image_path):
            os.remove(image_path)

        # Delete the achievement from the database
        db.session.delete(achievement)
        db.session.commit()
        
        return jsonify({"message": "Achievement deleted successfully"}), 200

    return jsonify({"error": "Achievement not found"}), 404

# Edit Achievement Route
@app.route("/edit_achievement", methods=["POST"])
def edit_achievement():
    if "logged_in" not in session or not session["logged_in"]:
        return redirect(url_for("login"))

    achievement = Achievement.query.get(request.form["id"])
    if achievement:
        achievement.name = request.form["name"]
        achievement.description = request.form["description"]
        achievement.date = request.form["date"]
        # Update the URL
        if request.form.get("image_url"):
            achievement.image = request.form["image_url"]

        db.session.commit()
    
    return redirect(url_for("home"))

if __name__ == "__main__":
    app.run(debug=True)