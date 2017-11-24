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

-- InsertarPelicula (SIN REFACTORIZAR, PONER FOR)
CREATE OR REPLACE FUNCTION "insertarPelicula"(titulo VARCHAR, sipnosis TEXT, anyo NUMERIC, duracion NUMERIC, genero_id BIGINT) RETURNS BOOLEAN
AS $$
DECLARE
	bInsertar BOOLEAN;
	sSipnosis TEXT;
	iAnyo     NUMERIC;
	iDuracion NUMERIC;

	sSipnosisDefault TEXT;
	iAnyoDefault     NUMERIC;
	iDuracionDefault NUMERIC;

BEGIN

	bInsertar := FALSE;

	SELECT d.adsrc AS default_value
	INTO sSipnosisDefault
	FROM   pg_catalog.pg_attribute a
	LEFT   JOIN pg_catalog.pg_attrdef d ON (a.attrelid, a.attnum)
					     = (d.adrelid,  d.adnum)
	WHERE  NOT a.attisdropped
	AND    a.attnum > 0
	AND    a.attrelid = 'public.peliculas'::regclass
	AND    a.attname = 'sipnosis';

	SELECT d.adsrc AS default_value
	INTO iAnyoDefault
	FROM   pg_catalog.pg_attribute a
	LEFT   JOIN pg_catalog.pg_attrdef d ON (a.attrelid, a.attnum)
					     = (d.adrelid,  d.adnum)
	WHERE  NOT a.attisdropped
	AND    a.attnum > 0
	AND    a.attrelid = 'public.peliculas'::regclass
	AND    a.attname = 'anyo';

	SELECT d.adsrc AS default_value
	INTO iDuracionDefault
	FROM   pg_catalog.pg_attribute a
	LEFT   JOIN pg_catalog.pg_attrdef d ON (a.attrelid, a.attnum)
					     = (d.adrelid,  d.adnum)
	WHERE  NOT a.attisdropped
	AND    a.attnum > 0
	AND    a.attrelid = 'public.peliculas'::regclass
	AND    a.attname = 'duracion';

	sSipnosis := COALESCE(sipnosis, sSipnosisDefault);
	iAnyo     := COALESCE(anyo, iAnyoDefault);
	iDuracion := COALESCE(duracion, iDuracionDefault);

	INSERT INTO "peliculas" (titulo, sipnosis, anyo, duracion, genero_id)
			VALUES  (titulo, sSipnosis, iAnyo, iDuracion, genero_id);

	IF FOUND THEN
		bInsertar := TRUE;
	END IF;

	RETURN bInsertar;

END;
$$
LANGUAGE plpgsql;

-- ModificarPelicula (SIN REFACTORIZAR Y CON REPETICION DE FUNCION)

CREATE OR REPLACE FUNCTION "modificarPelicula"(iId BIGINT, sTitulo VARCHAR, sipnosis TEXT, anyo NUMERIC, duracion NUMERIC, iGenero_id BIGINT) RETURNS BOOLEAN
AS $$
DECLARE
	bModificar BOOLEAN;
	sSipnosis  TEXT;
	iAnyo      NUMERIC;
	iDuracion  NUMERIC;

	sSipnosisDefault TEXT;
	iAnyoDefault     NUMERIC;
	iDuracionDefault NUMERIC;

BEGIN

	bModificar := FALSE;

	SELECT d.adsrc AS default_value
	INTO sSipnosisDefault
	FROM   pg_catalog.pg_attribute a
	LEFT   JOIN pg_catalog.pg_attrdef d ON (a.attrelid, a.attnum)
					     = (d.adrelid,  d.adnum)
	WHERE  NOT a.attisdropped
	AND    a.attnum > 0
	AND    a.attrelid = 'public.peliculas'::regclass
	AND    a.attname = 'sipnosis';

	SELECT d.adsrc AS default_value
	INTO iAnyoDefault
	FROM   pg_catalog.pg_attribute a
	LEFT   JOIN pg_catalog.pg_attrdef d ON (a.attrelid, a.attnum)
					     = (d.adrelid,  d.adnum)
	WHERE  NOT a.attisdropped
	AND    a.attnum > 0
	AND    a.attrelid = 'public.peliculas'::regclass
	AND    a.attname = 'anyo';

	SELECT d.adsrc AS default_value
	INTO iDuracionDefault
	FROM   pg_catalog.pg_attribute a
	LEFT   JOIN pg_catalog.pg_attrdef d ON (a.attrelid, a.attnum)
					     = (d.adrelid,  d.adnum)
	WHERE  NOT a.attisdropped
	AND    a.attnum > 0
	AND    a.attrelid = 'public.peliculas'::regclass
	AND    a.attname = 'duracion';

	sSipnosis := COALESCE(sipnosis, sSipnosisDefault);
	iAnyo     := COALESCE(anyo, iAnyoDefault);
	iDuracion := COALESCE(duracion, iDuracionDefault);

    UPDATE "peliculas" SET "titulo" = sTitulo, "sipnosis" = sSipnosis, "anyo" = iAnyo,
                            "duracion" = iDuracion, "genero_id" = iGenero_id
    WHERE ("id" = iId);

	IF FOUND THEN
		bModificar := TRUE;
	END IF;

	RETURN bModificar;

END;
$$
LANGUAGE plpgsql;

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
