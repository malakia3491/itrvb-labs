DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    uuid TEXT PRIMARY KEY,
    username TEXT NOT NULL UNIQUE,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL
);

CREATE TABLE posts (
    uuid TEXT PRIMARY KEY,
    author_uuid TEXT NOT NULL,
    title TEXT NOT NULL,
    text TEXT NOT NULL,
    FOREIGN KEY (author_uuid) REFERENCES users (uuid)
);

CREATE TABLE comments (
    uuid TEXT PRIMARY KEY,
    post_uuid TEXT NOT NULL,
    author_uuid TEXT NOT NULL,
    text TEXT NOT NULL,
    FOREIGN KEY (post_uuid) REFERENCES posts (uuid),
    FOREIGN KEY (author_uuid) REFERENCES users (uuid)
);

CREATE TABLE likes (
    uuid TEXT PRIMARY KEY,
    likeable_uuid TEXT NOT NULL,
    user_uuid TEXT NOT NULL,
    likeable_type TEXT NOT NULL CHECK (likeable_type IN ('post', 'comment'))
);

CREATE TABLE tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_uuid TEXT NOT NULL,
    token TEXT NOT NULL,
    expires_at DATETIME NOT NULL
);