CREATE TABLE plan
(id INTEGER PRIMARY KEY AUTOINCREMENT,
title VARCHAR(50),
start TIMESTAMP NOT NULL,
end TIMESTAMP NOT NULL,
goal INTEGER NOT NULL,
startamount INTEGER NOT NULL DEFAULT 0,
endamount INTEGER NOT NULL DEFAULT 0,
globalshow INTEGER NOT NULL DEFAULT 0);
