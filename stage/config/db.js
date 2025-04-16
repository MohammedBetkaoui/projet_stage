const mysql = require('mysql2/promise');

// Create MySQL Connection Pool
const pool = mysql.createPool({
  host: "localhost",
  user: "root",
  password: "",
  database: "projet_stages",
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
}); 
if(pool){
    console.log("conected");
} else {
  console.log("erooooooooooo");
}

module.exports = pool;
