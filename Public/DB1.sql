CREATE TABLE comentarios (
    id BIGSERIAL PRIMARY KEY,
    id_user INT,
    nickname VARCHAR(255),
    titulo VARCHAR(255),
    comentario TEXT,
    FOREIGN KEY (id_user) REFERENCES usuarios(id)
);