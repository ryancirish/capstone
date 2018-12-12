const express = require('express');
const parser = require('body-parser');
const cors = require('cors');
const fs = require('fs');
var mysql = require('mysql');

const app = express();
app.use(parser.json());
app.use(parser.urlencoded({ extended: true }));
app.use(cors());
app.options('*', cors());

app.get('/', (req, res) => {
  res.send('Hello World');
});

app.listen(3000, () => {
  console.log('listening on 3000');
});

var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'root',
  database : 'capstone',
  port: 8889
});

connection.connect()

function ret(req, res, next){
	connection.query('SELECT * FROM step', function (err, rows, fields) {
	  if (err) {
	  	throw err
	  } else {
	  	res.json(rows)
	  }
	})
}

function getrecs(req, res, next){
	const v = req.body
	const query = "SELECT * FROM recipies WHERE type = '" + v.type + "'"
	connection.query(query, function(err, rows, fields) {
		if(err){
			throw err
		} else {
			res.json(rows)
		}
	})
}



app.get('/bus', ret);
app.post('/recps', getrecs);