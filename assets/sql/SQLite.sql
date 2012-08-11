-- Dump of table collection
---------------------------------------------------------------

DROP TABLE IF EXISTS collection;

CREATE TABLE collection (
  id TEXT PRIMARY KEY,
  title TEXT,
  search TEXT,
  tagline TEXT,
  overview TEXT,
  poster_path TEXT,
  imdb_id TEXT,
  release_date TEXT
)
