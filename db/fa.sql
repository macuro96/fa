DROP TABLE IF EXISTS "peliculas" CASCADE;
DROP TABLE IF EXISTS "generos" CASCADE;

CREATE TABLE "generos"
(
     id     BIGSERIAL       PRIMARY KEY
   , nombre VARCHAR(255)    NOT NULL UNIQUE
);

CREATE TABLE "peliculas"
(
     id        BIGSERIAL       PRIMARY KEY
   , titulo    VARCHAR(255)    UNIQUE
   , sipnosis  TEXT
   , anyo      NUMERIC(4)
   , duracion  NUMERIC(3)      DEFAULT 0
                               CONSTRAINT ck_peliculas_duracion_positiva
                               CHECK (coalesce(duracion, 0) >= 0)
   , genero_id BIGINT          NOT NULL
                               REFERENCES generos (id)
                               ON DELETE NO ACTION
                               ON UPDATE CASCADE
);


CREATE TABLE "usuarios"
(
    id          BIGSERIAL       PRIMARY KEY
  , nombre      VARCHAR(255)    UNIQUE
  , password    VARCHAR(32)

);

-- Funciones


-- INSERT

DELETE FROM "generos";
INSERT INTO "generos" (nombre)
VALUES ('Comedia')
     , ('Terror')
     , ('Ciencia-Ficción')
     , ('Drama')
     , ('Aventuras');

DELETE FROM "peliculas";
INSERT INTO "peliculas" (titulo, sipnosis, anyo, duracion, genero_id)
VALUES ('Los Últimos Jedi', 'Va uno y se cae...', 2017, 204, 3)
     , ('Los Goonies', 'Unos niños encuentran un tesoro', 1984, 120, 5)
     , ('Aquí llega Condemor', 'Mejor no cuento nada...', 1996, 90, 1);

DELETE FROM "usuarios";
INSERT INTO "usuarios" (nombre, password)
VALUES ('manuel', 1234);
-- crypt('pepe', gen_salt('bf'))
