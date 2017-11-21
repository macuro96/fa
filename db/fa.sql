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
   , titulo    VARCHAR(255)    NOT NULL UNIQUE
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
