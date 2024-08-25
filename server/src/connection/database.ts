import mysql from "mysql2";
import env from "dotenv";
env.config();
const connection = mysql.createConnection({
  user: process.env.DB_USER,
  database: process.env.DB_NAME,
  host: process.env.DB_HOST,
  password: process.env.DB_PASS,
});

connection.connect(function (err) {
  if (err) console.log("Connection failure to Database ! " + err.code);
  else console.log("Connected to the database!");
});

export default connection;
