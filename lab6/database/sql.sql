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

INSERT INTO users (uuid, username, first_name, last_name) VALUES
    ('user-uuid-1', 'johndoe', 'John', 'Doe'),
    ('user-uuid-2', 'janedoe', 'Jane', 'Doe'),
    ('user-uuid-3', 'alice', 'Alice', 'Smith');

INSERT INTO posts (uuid, author_uuid, title, text) VALUES
    ('post-uuid-1', 'user-uuid-1', 'First Post', 'This is the content of the first post.'),
    ('post-uuid-2', 'user-uuid-2', 'Second Post', 'This is the content of the second post.'),
    ('post-uuid-3', 'user-uuid-3', 'Third Post', 'This is the content of the third post.');

INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES
    ('comment-uuid-1', 'post-uuid-1', 'user-uuid-2', 'Great post!'),
    ('comment-uuid-2', 'post-uuid-1', 'user-uuid-3', 'Very informative.'),
    ('comment-uuid-3', 'post-uuid-2', 'user-uuid-1', 'Nice work!'),
    ('comment-uuid-4', 'post-uuid-3', 'user-uuid-2', 'Interesting perspective.');