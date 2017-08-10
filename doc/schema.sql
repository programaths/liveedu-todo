DROP table users;
DROP table todos;

CREATE TABLE users(
  id SERIAL PRIMARY KEY,
  nickname VARCHAR(60),
  pass VARCHAR(255)
);

CREATE TABLE todos(
  id SERIAL PRIMARY KEY,
  user_id INTEGER,
  done BOOLEAN,
  title VARCHAR(60),
  description TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE UNIQUE INDEX ON users(nickname);