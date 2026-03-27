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
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///database.db'
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
    image = db.Column(db.String(100), nullable=False)

# Create the database
with app.app_context():
    db.create_all()

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

@app.route("/add_achievement", methods=["POST"])
def add_achievement():
    if request.method == "POST":
        name = request.form["name"]
        description = request.form["description"]
        date = request.form["date"]
        image = request.files["image"]

        if image:
            image_path = os.path.join(app.config["UPLOAD_FOLDER"], image.filename)
            image.save(image_path)

            new_achievement = Achievement(
                name=name,
                description=description,
                date=date,
                image=image.filename
            )

            db.session.add(new_achievement)
            db.session.commit()

    return redirect(url_for("home"))

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
    if "logged_in" not in session:
        return redirect(url_for("login"))

    achievement_id = request.form["id"]
    achievement = Achievement.query.get(achievement_id)

    if achievement:
        achievement.name = request.form["name"]
        achievement.description = request.form["description"]
        achievement.date = request.form["date"]

        # If new image is uploaded, update it
        if "image" in request.files and request.files["image"].filename:
            image = request.files["image"]
            image_path = os.path.join(app.config["UPLOAD_FOLDER"], image.filename)
            image.save(image_path)
            achievement.image = image.filename  # Update database entry

        db.session.commit()
    
    return redirect(url_for("home"))

if __name__ == "__main__":
    app.run(debug=True)