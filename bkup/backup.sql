--
-- PostgreSQL database dump
--

-- Dumped from database version 14.9
-- Dumped by pg_dump version 14.9

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE geospatialdb;
--
-- Name: geospatialdb; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE geospatialdb WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE = 'en_US.UTF-8';


ALTER DATABASE geospatialdb OWNER TO postgres;

\connect geospatialdb

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: geospatialdb; Type: DATABASE PROPERTIES; Schema: -; Owner: postgres
--

ALTER DATABASE geospatialdb SET search_path TO '$user', 'public', 'topology';


\connect geospatialdb

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: artisanal; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA artisanal;


ALTER SCHEMA artisanal OWNER TO postgres;

--
-- Name: artisanal_catches; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA artisanal_catches;


ALTER SCHEMA artisanal_catches OWNER TO postgres;

--
-- Name: crevette; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA crevette;


ALTER SCHEMA crevette OWNER TO postgres;

--
-- Name: fishery; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA fishery;


ALTER SCHEMA fishery OWNER TO postgres;

--
-- Name: infraction; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA infraction;


ALTER SCHEMA infraction OWNER TO postgres;

--
-- Name: poisson; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA poisson;


ALTER SCHEMA poisson OWNER TO postgres;

--
-- Name: seiners; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA seiners;


ALTER SCHEMA seiners OWNER TO postgres;

--
-- Name: shapefiles; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA shapefiles;


ALTER SCHEMA shapefiles OWNER TO postgres;

--
-- Name: thon; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA thon;


ALTER SCHEMA thon OWNER TO postgres;

--
-- Name: tiger; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA tiger;


ALTER SCHEMA tiger OWNER TO postgres;

--
-- Name: tiger_data; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA tiger_data;


ALTER SCHEMA tiger_data OWNER TO postgres;

--
-- Name: topology; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA topology;


ALTER SCHEMA topology OWNER TO postgres;

--
-- Name: SCHEMA topology; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA topology IS 'PostGIS Topology schema';


--
-- Name: trawlers; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA trawlers;


ALTER SCHEMA trawlers OWNER TO postgres;

--
-- Name: trawlers_server; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA trawlers_server;


ALTER SCHEMA trawlers_server OWNER TO postgres;

--
-- Name: users; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA users;


ALTER SCHEMA users OWNER TO postgres;

--
-- Name: vms; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA vms;


ALTER SCHEMA vms OWNER TO postgres;

--
-- Name: fuzzystrmatch; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS fuzzystrmatch WITH SCHEMA public;


--
-- Name: EXTENSION fuzzystrmatch; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION fuzzystrmatch IS 'determine similarities and distance between strings';


--
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- Name: EXTENSION pg_trgm; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pg_trgm IS 'text similarity measurement and index searching based on trigrams';


--
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


--
-- Name: postgis_topology; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS postgis_topology WITH SCHEMA topology;


--
-- Name: EXTENSION postgis_topology; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis_topology IS 'PostGIS topology spatial types and functions';


--
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: captures; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.captures (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_species uuid,
    wgt_tot double precision,
    wgt_spc double precision,
    n_ind double precision
);


ALTER TABLE artisanal.captures OWNER TO postgres;

--
-- Name: carte; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.carte (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    carte integer NOT NULL,
    id_fisherman uuid,
    date_v date,
    id_license uuid,
    active boolean DEFAULT false,
    paid boolean DEFAULT false
);


ALTER TABLE artisanal.carte OWNER TO postgres;

--
-- Name: carte_carte_seq; Type: SEQUENCE; Schema: artisanal; Owner: postgres
--

CREATE SEQUENCE artisanal.carte_carte_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE artisanal.carte_carte_seq OWNER TO postgres;

--
-- Name: carte_carte_seq; Type: SEQUENCE OWNED BY; Schema: artisanal; Owner: postgres
--

ALTER SEQUENCE artisanal.carte_carte_seq OWNED BY artisanal.carte.carte;


--
-- Name: effort; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.effort (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    date_e date,
    obs_name character varying(200),
    t_site integer,
    db1 integer,
    dh1 integer,
    db3 integer,
    dh3 integer,
    ps1 integer,
    pc1 integer,
    ps3 integer,
    pc3 integer
);


ALTER TABLE artisanal.effort OWNER TO postgres;

--
-- Name: fisherman; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.fisherman (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    first_name character varying(100),
    last_name character varying(100),
    bday date,
    wives integer,
    children integer,
    t_card integer,
    idcard character varying(200),
    ycard date,
    address text,
    t_nationality integer,
    telephone character varying(100),
    photo_data bytea,
    comments text,
    id_temp integer
);


ALTER TABLE artisanal.fisherman OWNER TO postgres;

--
-- Name: fleet; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.fleet (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    date_f date,
    obs_name character varying(200),
    t_site integer,
    source text,
    ppb integer,
    gpf integer,
    ppf integer,
    tot integer
);


ALTER TABLE artisanal.fleet OWNER TO postgres;

--
-- Name: infraction; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.infraction (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    date_i date,
    id_license uuid,
    id_pirogue uuid,
    pir_name character varying(100),
    immatriculation character varying(100),
    id_carte uuid,
    id_fisherman uuid,
    fish_first character varying(100),
    fish_last character varying(100),
    fish_idcard character varying(100),
    t_org integer,
    name character varying(200),
    obj_confiscated character varying(200),
    amount_infract integer,
    payment integer,
    receipt integer,
    comments text,
    location public.geometry(Point,4326),
    settled boolean
);


ALTER TABLE artisanal.infraction OWNER TO postgres;

--
-- Name: infractions; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.infractions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    t_infraction integer,
    id_infraction uuid
);


ALTER TABLE artisanal.infractions OWNER TO postgres;

--
-- Name: license; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.license (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    license integer NOT NULL,
    date_v date,
    t_license integer,
    t_license_2 integer,
    t_gear integer,
    t_gear_2 integer,
    t_site integer,
    t_site_obb integer,
    mesh_min double precision,
    mesh_max double precision,
    length double precision,
    mesh_min_2 double precision,
    mesh_max_2 double precision,
    length_2 double precision,
    engine_brand character varying(100),
    engine_cv integer,
    payment integer,
    receipt character varying,
    agasa character varying(100),
    t_coop integer,
    id_pirogue uuid,
    active boolean DEFAULT false,
    id_temp integer,
    comments text
);


ALTER TABLE artisanal.license OWNER TO postgres;

--
-- Name: license_license_seq; Type: SEQUENCE; Schema: artisanal; Owner: postgres
--

CREATE SEQUENCE artisanal.license_license_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE artisanal.license_license_seq OWNER TO postgres;

--
-- Name: license_license_seq; Type: SEQUENCE OWNED BY; Schema: artisanal; Owner: postgres
--

ALTER SEQUENCE artisanal.license_license_seq OWNED BY artisanal.license.license;


--
-- Name: maree; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.maree (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    datetime_d timestamp without time zone,
    datetime_r timestamp without time zone,
    obs_name character varying(200),
    t_site integer,
    t_study integer,
    id_pirogue uuid,
    immatriculation character varying(200),
    t_gear integer,
    mesh_min character varying(9),
    mesh_max character varying(9),
    length character varying(9),
    wgt_tot character varying(9),
    gps_file character varying(200),
    gps_track public.geometry(LineStringZ,4326)
);


ALTER TABLE artisanal.maree OWNER TO postgres;

--
-- Name: market; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.market (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    date_m date,
    obs_name character varying(100),
    t_site integer,
    id_species uuid,
    p_s integer,
    p_p integer,
    p_c integer,
    p_m integer,
    p_f integer
);


ALTER TABLE artisanal.market OWNER TO postgres;

--
-- Name: owner; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.owner (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    first_name character varying(100),
    last_name character varying(100),
    bday date,
    wives integer,
    children integer,
    t_card integer,
    idcard character varying(200),
    ycard date,
    address text,
    t_nationality integer,
    telephone character varying(100),
    photo_data bytea,
    comments text,
    id_temp integer
);


ALTER TABLE artisanal.owner OWNER TO postgres;

--
-- Name: pelagic_lkp; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.pelagic_lkp (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    date_t timestamp without time zone,
    name character varying(100),
    location public.geometry(Point,4326)
);


ALTER TABLE artisanal.pelagic_lkp OWNER TO postgres;

--
-- Name: pelagic_points; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.pelagic_points (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    date_t timestamp without time zone,
    name character varying(100),
    speed double precision,
    range double precision,
    heading double precision,
    location public.geometry(Point,4326)
);


ALTER TABLE artisanal.pelagic_points OWNER TO postgres;

--
-- Name: pelagic_tracks; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.pelagic_tracks (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    date_t timestamp without time zone,
    name character varying(100),
    speed double precision,
    range double precision,
    heading double precision,
    location public.geometry(Point,4326)
);


ALTER TABLE artisanal.pelagic_tracks OWNER TO postgres;

--
-- Name: pirogue; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.pirogue (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    name character varying(100),
    immatriculation character varying(200),
    t_pirogue integer,
    length character varying,
    id_owner uuid,
    comments text,
    id_temp integer,
    photo_data_1 bytea,
    photo_data_2 bytea,
    photo_data_3 bytea,
    plate character varying
);


ALTER TABLE artisanal.pirogue OWNER TO postgres;

--
-- Name: t_card; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_card (
    id integer NOT NULL,
    card character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_card OWNER TO postgres;

--
-- Name: t_coop; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_coop (
    id integer NOT NULL,
    coop character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_coop OWNER TO postgres;

--
-- Name: t_gear; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_gear (
    id integer NOT NULL,
    gear character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_gear OWNER TO postgres;

--
-- Name: t_immatriculation; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_immatriculation (
    id integer NOT NULL,
    immatriculation character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_immatriculation OWNER TO postgres;

--
-- Name: t_infraction; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_infraction (
    id integer NOT NULL,
    infraction character varying(200)
);


ALTER TABLE artisanal.t_infraction OWNER TO postgres;

--
-- Name: t_license; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_license (
    id integer NOT NULL,
    license character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_license OWNER TO postgres;

--
-- Name: t_license_id_seq; Type: SEQUENCE; Schema: artisanal; Owner: postgres
--

CREATE SEQUENCE artisanal.t_license_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE artisanal.t_license_id_seq OWNER TO postgres;

--
-- Name: t_license_id_seq; Type: SEQUENCE OWNED BY; Schema: artisanal; Owner: postgres
--

ALTER SEQUENCE artisanal.t_license_id_seq OWNED BY artisanal.t_license.id;


--
-- Name: t_nationality; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_nationality (
    id integer NOT NULL,
    nationality character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_nationality OWNER TO postgres;

--
-- Name: t_navire; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_navire (
    id integer NOT NULL,
    navire character varying(100)
);


ALTER TABLE artisanal.t_navire OWNER TO postgres;

--
-- Name: t_pirogue; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_pirogue (
    id integer NOT NULL,
    pirogue character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_pirogue OWNER TO postgres;

--
-- Name: t_registration; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_registration (
    id integer NOT NULL,
    registration character varying(100)
);


ALTER TABLE artisanal.t_registration OWNER TO postgres;

--
-- Name: t_site; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_site (
    id integer NOT NULL,
    site character varying(100),
    strata character varying(100),
    region character varying(100),
    code character varying(100),
    active boolean,
    location public.geometry(Point,4326)
);


ALTER TABLE artisanal.t_site OWNER TO postgres;

--
-- Name: t_site_id_seq; Type: SEQUENCE; Schema: artisanal; Owner: postgres
--

CREATE SEQUENCE artisanal.t_site_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE artisanal.t_site_id_seq OWNER TO postgres;

--
-- Name: t_site_id_seq; Type: SEQUENCE OWNED BY; Schema: artisanal; Owner: postgres
--

ALTER SEQUENCE artisanal.t_site_id_seq OWNED BY artisanal.t_site.id;


--
-- Name: t_site_obb; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_site_obb (
    id integer NOT NULL,
    site character varying(100),
    strata character varying(100),
    region character varying(100),
    code character varying(100),
    active boolean,
    location public.geometry(Point,4326)
);


ALTER TABLE artisanal.t_site_obb OWNER TO postgres;

--
-- Name: t_site_obb_id_seq; Type: SEQUENCE; Schema: artisanal; Owner: postgres
--

CREATE SEQUENCE artisanal.t_site_obb_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE artisanal.t_site_obb_id_seq OWNER TO postgres;

--
-- Name: t_site_obb_id_seq; Type: SEQUENCE OWNED BY; Schema: artisanal; Owner: postgres
--

ALTER SEQUENCE artisanal.t_site_obb_id_seq OWNED BY artisanal.t_site_obb.id;


--
-- Name: t_status; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_status (
    id integer NOT NULL,
    status character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_status OWNER TO postgres;

--
-- Name: t_status_id_seq; Type: SEQUENCE; Schema: artisanal; Owner: postgres
--

CREATE SEQUENCE artisanal.t_status_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE artisanal.t_status_id_seq OWNER TO postgres;

--
-- Name: t_status_id_seq; Type: SEQUENCE OWNED BY; Schema: artisanal; Owner: postgres
--

ALTER SEQUENCE artisanal.t_status_id_seq OWNED BY artisanal.t_status.id;


--
-- Name: t_strata; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_strata (
    id integer NOT NULL,
    strata character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_strata OWNER TO postgres;

--
-- Name: t_strata_id_seq; Type: SEQUENCE; Schema: artisanal; Owner: postgres
--

CREATE SEQUENCE artisanal.t_strata_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE artisanal.t_strata_id_seq OWNER TO postgres;

--
-- Name: t_strata_id_seq; Type: SEQUENCE OWNED BY; Schema: artisanal; Owner: postgres
--

ALTER SEQUENCE artisanal.t_strata_id_seq OWNED BY artisanal.t_strata.id;


--
-- Name: t_study; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_study (
    id integer NOT NULL,
    study character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_study OWNER TO postgres;

--
-- Name: t_study_id_seq; Type: SEQUENCE; Schema: artisanal; Owner: postgres
--

CREATE SEQUENCE artisanal.t_study_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE artisanal.t_study_id_seq OWNER TO postgres;

--
-- Name: t_study_id_seq; Type: SEQUENCE OWNED BY; Schema: artisanal; Owner: postgres
--

ALTER SEQUENCE artisanal.t_study_id_seq OWNED BY artisanal.t_study.id;


--
-- Name: t_zone; Type: TABLE; Schema: artisanal; Owner: postgres
--

CREATE TABLE artisanal.t_zone (
    id integer NOT NULL,
    zone character varying(100),
    active boolean
);


ALTER TABLE artisanal.t_zone OWNER TO postgres;

--
-- Name: enq_catch; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.enq_catch (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_species uuid,
    wgt double precision
);


ALTER TABLE artisanal_catches.enq_catch OWNER TO postgres;

--
-- Name: enq_maree; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.enq_maree (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    datetime_d timestamp without time zone,
    datetime_r timestamp without time zone,
    obs_name character varying(200),
    t_site integer,
    t_gear integer,
    id_pirogue uuid,
    immatriculation character varying(200)
);


ALTER TABLE artisanal_catches.enq_maree OWNER TO postgres;

--
-- Name: log_catch; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.log_catch (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_species uuid,
    wgt double precision
);


ALTER TABLE artisanal_catches.log_catch OWNER TO postgres;

--
-- Name: log_maree; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.log_maree (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    datetime_d timestamp without time zone,
    datetime_r timestamp without time zone,
    t_site integer,
    id_pirogue uuid,
    immatriculation character varying(200)
);


ALTER TABLE artisanal_catches.log_maree OWNER TO postgres;

--
-- Name: obs_action; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.obs_action (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    date_a date,
    time_a time without time zone,
    id_maree uuid,
    wpt character varying(100),
    t_gear integer,
    boarded boolean,
    comments text,
    location public.geometry(Point,4326)
);


ALTER TABLE artisanal_catches.obs_action OWNER TO postgres;

--
-- Name: obs_catch; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.obs_catch (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_species uuid,
    wgt_tot double precision,
    wgt_spc double precision,
    n_ind double precision
);


ALTER TABLE artisanal_catches.obs_catch OWNER TO postgres;

--
-- Name: obs_fish; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.obs_fish (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_species uuid,
    n_lot double precision,
    per double precision,
    wgt double precision
);


ALTER TABLE artisanal_catches.obs_fish OWNER TO postgres;

--
-- Name: obs_mammals; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.obs_mammals (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_action uuid,
    id_species uuid,
    t_sex integer,
    t_maturity integer,
    lt double precision,
    wgt double precision,
    t_status integer,
    t_action integer,
    photo_data bytea,
    comments text
);


ALTER TABLE artisanal_catches.obs_mammals OWNER TO postgres;

--
-- Name: obs_maree; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.obs_maree (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    obs_name character varying(200),
    t_mission integer,
    date_d date,
    time_d time without time zone,
    t_site_d integer,
    date_r date,
    time_r time without time zone,
    t_site_r integer,
    n_deb integer,
    zone character varying(9),
    id_pirogue uuid,
    immatriculation character varying(200),
    engine character varying(200),
    t_gear_1 integer,
    length_1 character varying(9),
    height_1 character varying(9),
    mesh_min_1 character varying(9),
    mesh_max_1 character varying(9),
    t_gear_2 integer,
    length_2 character varying(9),
    height_2 character varying(9),
    mesh_min_2 character varying(9),
    mesh_max_2 character varying(9),
    t_gear_3 integer,
    length_3 character varying(9),
    height_3 character varying(9),
    mesh_min_3 character varying(9),
    mesh_max_3 character varying(9),
    gps_file text,
    comments text,
    gps_track public.geometry(LineStringZ,4326)
);


ALTER TABLE artisanal_catches.obs_maree OWNER TO postgres;

--
-- Name: obs_poids_taille; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.obs_poids_taille (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_species uuid,
    t_maturity integer,
    t_measure integer,
    length double precision,
    wgt double precision
);


ALTER TABLE artisanal_catches.obs_poids_taille OWNER TO postgres;

--
-- Name: obs_sharks; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.obs_sharks (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_action uuid,
    id_species uuid,
    t_sex integer,
    t_maturity integer,
    lt double precision,
    la double precision,
    wgt double precision,
    t_status integer,
    t_action integer,
    photo_data bytea,
    comments text
);


ALTER TABLE artisanal_catches.obs_sharks OWNER TO postgres;

--
-- Name: obs_turtles; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.obs_turtles (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_action uuid,
    id_species uuid,
    t_sex integer,
    t_maturity integer,
    bague character varying(100),
    integrity text,
    fibrop text,
    epibionte text,
    lt double precision,
    wgt double precision,
    t_status integer,
    t_action integer,
    photo_data bytea,
    comments text
);


ALTER TABLE artisanal_catches.obs_turtles OWNER TO postgres;

--
-- Name: t_action; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.t_action (
    id integer NOT NULL,
    action character varying(100)
);


ALTER TABLE artisanal_catches.t_action OWNER TO postgres;

--
-- Name: t_gear; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.t_gear (
    id integer NOT NULL,
    gear character varying(100)
);


ALTER TABLE artisanal_catches.t_gear OWNER TO postgres;

--
-- Name: t_integrity; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.t_integrity (
    id integer NOT NULL,
    integrity character varying(100)
);


ALTER TABLE artisanal_catches.t_integrity OWNER TO postgres;

--
-- Name: t_maturity; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.t_maturity (
    id integer NOT NULL,
    maturity character varying(100)
);


ALTER TABLE artisanal_catches.t_maturity OWNER TO postgres;

--
-- Name: t_mission; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.t_mission (
    id integer NOT NULL,
    mission character varying(100)
);


ALTER TABLE artisanal_catches.t_mission OWNER TO postgres;

--
-- Name: t_sex; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.t_sex (
    id integer NOT NULL,
    sex character varying(100)
);


ALTER TABLE artisanal_catches.t_sex OWNER TO postgres;

--
-- Name: t_status; Type: TABLE; Schema: artisanal_catches; Owner: postgres
--

CREATE TABLE artisanal_catches.t_status (
    id integer NOT NULL,
    status character varying(100)
);


ALTER TABLE artisanal_catches.t_status OWNER TO postgres;

--
-- Name: capture; Type: TABLE; Schema: crevette; Owner: postgres
--

CREATE TABLE crevette.capture (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_lance uuid,
    id_species uuid,
    t_taille integer,
    poids double precision
);


ALTER TABLE crevette.capture OWNER TO postgres;

--
-- Name: lance; Type: TABLE; Schema: crevette; Owner: postgres
--

CREATE TABLE crevette.lance (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_navire uuid,
    date_l date,
    t_zone integer,
    lance integer,
    h_d time without time zone,
    h_f time without time zone,
    d_d double precision,
    d_f double precision,
    t_d double precision,
    rejets double precision,
    c0_cre double precision,
    c1_cre double precision,
    c2_cre double precision,
    c3_cre double precision,
    c4_cre double precision,
    c5_cre double precision,
    c6_cre double precision,
    c7_cre double precision,
    c8_cre double precision,
    c_cre double precision,
    cc_cre double precision,
    o_cre double precision,
    v6_cre double precision,
    location_d public.geometry(Point,4326),
    location_f public.geometry(Point,4326)
);


ALTER TABLE crevette.lance OWNER TO postgres;

--
-- Name: t_taille; Type: TABLE; Schema: crevette; Owner: postgres
--

CREATE TABLE crevette.t_taille (
    id integer NOT NULL,
    taille character varying(100)
);


ALTER TABLE crevette.t_taille OWNER TO postgres;

--
-- Name: t_zone; Type: TABLE; Schema: crevette; Owner: postgres
--

CREATE TABLE crevette.t_zone (
    id integer NOT NULL,
    zone character varying(100)
);


ALTER TABLE crevette.t_zone OWNER TO postgres;

--
-- Name: species; Type: TABLE; Schema: fishery; Owner: postgres
--

CREATE TABLE fishery.species (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    francaise character varying(100),
    family character varying(100),
    genus character varying(100),
    species character varying(100),
    fao character varying(100),
    obs character varying(100),
    category character varying(100),
    iucn character varying(100)
);


ALTER TABLE fishery.species OWNER TO postgres;

--
-- Name: infraction; Type: TABLE; Schema: infraction; Owner: postgres
--

CREATE TABLE infraction.infraction (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_pv character varying,
    date_i date,
    t_org integer,
    name_org character varying(200),
    id_pirogue uuid,
    pir_name character varying(100),
    immatriculation character varying(100),
    id_owner uuid,
    owner_first character varying(100),
    owner_last character varying(100),
    owner_idcard character varying(100),
    owner_t_card integer,
    owner_t_nationality integer,
    owner_telephone character varying(100),
    id_fisherman_1 uuid,
    fish_first_1 character varying(100),
    fish_last_1 character varying(100),
    fish_idcard_1 character varying(100),
    fish_t_card_1 integer,
    fish_t_nationality_1 integer,
    fish_telephone_1 character varying(100),
    id_fisherman_2 uuid,
    fish_first_2 character varying(100),
    fish_last_2 character varying(100),
    fish_idcard_2 character varying(100),
    fish_t_card_2 integer,
    fish_t_nationality_2 integer,
    fish_telephone_2 character varying(100),
    id_fisherman_3 uuid,
    fish_first_3 character varying(100),
    fish_last_3 character varying(100),
    fish_idcard_3 character varying(100),
    fish_t_card_3 integer,
    fish_t_nationality_3 integer,
    fish_telephone_3 character varying(100),
    id_fisherman_4 uuid,
    fish_first_4 character varying(100),
    fish_last_4 character varying(100),
    fish_idcard_4 character varying(100),
    fish_t_card_4 integer,
    fish_t_nationality_4 integer,
    fish_telephone_4 character varying(100),
    pir_conf character varying(200),
    eng_conf character varying(200),
    net_conf character varying(200),
    doc_conf character varying(200),
    other_conf character varying(200),
    amount character varying,
    payment character varying,
    n_dep character varying,
    n_cdc character varying,
    n_lib character varying,
    comments text,
    location public.geometry(Point,4326),
    settled boolean,
    owner_ycard date,
    fish_ycard_1 date,
    fish_ycard_2 date,
    fish_ycard_3 date,
    fish_ycard_4 date
);


ALTER TABLE infraction.infraction OWNER TO postgres;

--
-- Name: infractions; Type: TABLE; Schema: infraction; Owner: postgres
--

CREATE TABLE infraction.infractions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    t_infraction integer,
    id_infraction uuid
);


ALTER TABLE infraction.infractions OWNER TO postgres;

--
-- Name: t_infraction; Type: TABLE; Schema: infraction; Owner: postgres
--

CREATE TABLE infraction.t_infraction (
    id integer NOT NULL,
    infraction character varying(200)
);


ALTER TABLE infraction.t_infraction OWNER TO postgres;

--
-- Name: t_org; Type: TABLE; Schema: infraction; Owner: postgres
--

CREATE TABLE infraction.t_org (
    id integer NOT NULL,
    org character varying(100),
    active boolean
);


ALTER TABLE infraction.t_org OWNER TO postgres;

--
-- Name: capture; Type: TABLE; Schema: poisson; Owner: postgres
--

CREATE TABLE poisson.capture (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_maree uuid,
    id_species uuid,
    poids double precision,
    t_taille integer
);


ALTER TABLE poisson.capture OWNER TO postgres;

--
-- Name: maree; Type: TABLE; Schema: poisson; Owner: postgres
--

CREATE TABLE poisson.maree (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_navire uuid,
    date_d date,
    date_r date,
    captain character varying(100),
    nlance integer,
    port_d character varying(100),
    port_r character varying(100),
    t_zone integer,
    rejets double precision
);


ALTER TABLE poisson.maree OWNER TO postgres;

--
-- Name: t_taille; Type: TABLE; Schema: poisson; Owner: postgres
--

CREATE TABLE poisson.t_taille (
    id integer NOT NULL,
    taille character varying(100)
);


ALTER TABLE poisson.t_taille OWNER TO postgres;

--
-- Name: t_zone; Type: TABLE; Schema: poisson; Owner: postgres
--

CREATE TABLE poisson.t_zone (
    id integer NOT NULL,
    zone character varying(100)
);


ALTER TABLE poisson.t_zone OWNER TO postgres;

--
-- Name: -a; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."-a" (
    gid integer NOT NULL,
    name character varying(80),
    descriptio character varying(80),
    geom public.geometry(MultiLineStringZM)
);


ALTER TABLE public."-a" OWNER TO postgres;

--
-- Name: -a_gid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."-a_gid_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."-a_gid_seq" OWNER TO postgres;

--
-- Name: -a_gid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."-a_gid_seq" OWNED BY public."-a".gid;


--
-- Name: objet; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.objet (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    t_zee integer,
    n_objet integer,
    id_route uuid,
    n_route integer,
    l_route integer,
    t_objet integer,
    type_balise character varying(100),
    code_balise character varying(100),
    t_operation integer,
    t_appartenance integer,
    t_devenir integer,
    remarque text
);


ALTER TABLE seiners.objet OWNER TO postgres;

--
-- Name: prise_access; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.prise_access (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    n_calee integer,
    t_type integer,
    t_zee integer,
    id_route uuid,
    n_route integer,
    l_route integer,
    h_d time without time zone,
    h_c time without time zone,
    h_f time without time zone,
    vitesse real,
    direction integer,
    d_max real,
    t_prise integer,
    id_species uuid,
    t_action integer,
    t_raison integer,
    poids real,
    n_ind real,
    taille real,
    photo character varying(100),
    remarque text
);


ALTER TABLE seiners.prise_access OWNER TO postgres;

--
-- Name: prise_access_taille; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.prise_access_taille (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    n_cale integer,
    id_route uuid,
    n_route integer,
    l_route integer,
    id_species uuid,
    t_measure integer,
    taille double precision,
    poids double precision,
    t_sexe integer,
    t_capture integer,
    t_relache integer,
    photo character varying(100),
    remarque text
);


ALTER TABLE seiners.prise_access_taille OWNER TO postgres;

--
-- Name: route; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.route (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_navire uuid,
    maree text,
    date date,
    n_route integer,
    l_route integer,
    "time" time without time zone,
    speed real,
    t_activite integer,
    t_neighbours integer,
    temperature real,
    windspeed real,
    t_detection integer,
    t_systeme integer,
    comment text,
    location public.geometry(Point,4326)
);


ALTER TABLE seiners.route OWNER TO postgres;

--
-- Name: t_action; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_action (
    id integer NOT NULL,
    action character varying(100)
);


ALTER TABLE seiners.t_action OWNER TO postgres;

--
-- Name: t_activite; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_activite (
    id integer NOT NULL,
    activite character varying(100)
);


ALTER TABLE seiners.t_activite OWNER TO postgres;

--
-- Name: t_appartenance; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_appartenance (
    id integer NOT NULL,
    appartenance character varying(100)
);


ALTER TABLE seiners.t_appartenance OWNER TO postgres;

--
-- Name: t_capture; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_capture (
    id integer NOT NULL,
    capture character varying(100)
);


ALTER TABLE seiners.t_capture OWNER TO postgres;

--
-- Name: t_categorie; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_categorie (
    id integer NOT NULL,
    categorie character varying(100)
);


ALTER TABLE seiners.t_categorie OWNER TO postgres;

--
-- Name: t_detection; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_detection (
    id integer NOT NULL,
    detection character varying(100)
);


ALTER TABLE seiners.t_detection OWNER TO postgres;

--
-- Name: t_devenir; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_devenir (
    id integer NOT NULL,
    devenir character varying(100)
);


ALTER TABLE seiners.t_devenir OWNER TO postgres;

--
-- Name: t_espece; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_espece (
    id integer NOT NULL,
    espece character varying(100)
);


ALTER TABLE seiners.t_espece OWNER TO postgres;

--
-- Name: t_measure; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_measure (
    id integer NOT NULL,
    measure character varying(100)
);


ALTER TABLE seiners.t_measure OWNER TO postgres;

--
-- Name: t_neighbours; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_neighbours (
    id integer NOT NULL,
    neighbours character varying(100)
);


ALTER TABLE seiners.t_neighbours OWNER TO postgres;

--
-- Name: t_objet; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_objet (
    id integer NOT NULL,
    objet character varying(100)
);


ALTER TABLE seiners.t_objet OWNER TO postgres;

--
-- Name: t_operation; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_operation (
    id integer NOT NULL,
    operation character varying(100)
);


ALTER TABLE seiners.t_operation OWNER TO postgres;

--
-- Name: t_peche; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_peche (
    id integer NOT NULL,
    peche character varying(100)
);


ALTER TABLE seiners.t_peche OWNER TO postgres;

--
-- Name: t_prise; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_prise (
    id integer NOT NULL,
    prise character varying(100)
);


ALTER TABLE seiners.t_prise OWNER TO postgres;

--
-- Name: t_raison; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_raison (
    id integer NOT NULL,
    raison character varying(100)
);


ALTER TABLE seiners.t_raison OWNER TO postgres;

--
-- Name: t_relache; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_relache (
    id integer NOT NULL,
    relache character varying(100)
);


ALTER TABLE seiners.t_relache OWNER TO postgres;

--
-- Name: t_sexe; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_sexe (
    id integer NOT NULL,
    sexe character varying(100)
);


ALTER TABLE seiners.t_sexe OWNER TO postgres;

--
-- Name: t_systeme; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_systeme (
    id integer NOT NULL,
    systeme character varying(100)
);


ALTER TABLE seiners.t_systeme OWNER TO postgres;

--
-- Name: t_type; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_type (
    id integer NOT NULL,
    type character varying(100)
);


ALTER TABLE seiners.t_type OWNER TO postgres;

--
-- Name: t_zee; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.t_zee (
    id integer NOT NULL,
    zee character varying(100)
);


ALTER TABLE seiners.t_zee OWNER TO postgres;

--
-- Name: thon_rejete; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.thon_rejete (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    t_zee integer,
    n_calee integer,
    t_type integer,
    id_route uuid,
    n_route integer,
    l_route integer,
    h_d time without time zone,
    h_c time without time zone,
    h_f time without time zone,
    vitesse real,
    direction integer,
    d_max real,
    id_species uuid,
    t_categorie integer,
    t_raison integer,
    poids real,
    monte boolean,
    photo character varying(100),
    remarque text
);


ALTER TABLE seiners.thon_rejete OWNER TO postgres;

--
-- Name: thon_rejete_taille; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.thon_rejete_taille (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    id_species uuid,
    n_calee integer,
    n_route integer,
    l_route integer,
    id_route uuid,
    c009 integer,
    c010 integer,
    c011 integer,
    c012 integer,
    c013 integer,
    c014 integer,
    c015 integer,
    c016 integer,
    c017 integer,
    c018 integer,
    c019 integer,
    c020 integer,
    c021 integer,
    c022 integer,
    c023 integer,
    c024 integer,
    c025 integer,
    c026 integer,
    c027 integer,
    c028 integer,
    c029 integer,
    c030 integer,
    c031 integer,
    c032 integer,
    c033 integer,
    c034 integer,
    c035 integer,
    c036 integer,
    c037 integer,
    c038 integer,
    c039 integer,
    c040 integer,
    c041 integer,
    c042 integer,
    c043 integer,
    c044 integer,
    c045 integer,
    c046 integer,
    c047 integer,
    c048 integer,
    c049 integer,
    c050 integer,
    c051 integer,
    c052 integer,
    c053 integer,
    c054 integer,
    c055 integer,
    c056 integer,
    c057 integer,
    c058 integer,
    c059 integer,
    c060 integer,
    c061 integer,
    c062 integer,
    c063 integer,
    c064 integer,
    c065 integer,
    c066 integer,
    c067 integer,
    c068 integer,
    c069 integer,
    c070 integer,
    c071 integer,
    c072 integer,
    c073 integer,
    c074 integer,
    c075 integer,
    c076 integer,
    c077 integer,
    c078 integer,
    c079 integer,
    c080 integer,
    c081 integer,
    c082 integer,
    c083 integer,
    c084 integer,
    c085 integer,
    c086 integer,
    c087 integer,
    c088 integer,
    c089 integer,
    c090 integer,
    c091 integer,
    c092 integer,
    c093 integer,
    c094 integer,
    c095 integer,
    c096 integer,
    c097 integer,
    c098 integer,
    c099 integer,
    c100 integer,
    c110 integer,
    c111 integer,
    c112 integer,
    c135 integer,
    c138 integer,
    c139 integer,
    c140 integer,
    c144 integer,
    c145 integer,
    c146 integer,
    c147 integer,
    c148 integer,
    c149 integer,
    c150 integer,
    c151 integer,
    c154 integer,
    c155 integer,
    c156 integer,
    c157 integer,
    c158 integer,
    c159 integer,
    c160 integer,
    c170 integer
);


ALTER TABLE seiners.thon_rejete_taille OWNER TO postgres;

--
-- Name: thon_retenue; Type: TABLE; Schema: seiners; Owner: postgres
--

CREATE TABLE seiners.thon_retenue (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    t_zee integer,
    n_calee integer,
    t_type integer,
    id_route uuid,
    n_route integer,
    l_route integer,
    h_d time without time zone,
    h_c time without time zone,
    h_f time without time zone,
    vitesse real,
    direction integer,
    d_max real,
    sonar boolean,
    raison character varying(100),
    id_species uuid,
    t_categorie integer,
    poids real,
    cuve character varying(100),
    remarque text
);


ALTER TABLE seiners.thon_retenue OWNER TO postgres;

--
-- Name: mpa; Type: TABLE; Schema: shapefiles; Owner: postgres
--

CREATE TABLE shapefiles.mpa (
    gid integer NOT NULL,
    type character varying(50),
    code character varying(50),
    nom_apa character varying(50),
    superf_km2 numeric,
    pechecoutu character varying(50),
    pecheartis character varying(50),
    pecheindus character varying(50),
    pechesport character varying(50),
    geom public.geometry(MultiPolygonZM,4326)
);


ALTER TABLE shapefiles.mpa OWNER TO postgres;

--
-- Name: mpa_buffer; Type: TABLE; Schema: shapefiles; Owner: postgres
--

CREATE TABLE shapefiles.mpa_buffer (
    gid integer NOT NULL,
    name character varying(254),
    nomcomplet character varying(50),
    superf_km2 numeric,
    geom public.geometry(MultiPolygon,4326)
);


ALTER TABLE shapefiles.mpa_buffer OWNER TO postgres;

--
-- Name: mpa_buffer_gid_seq; Type: SEQUENCE; Schema: shapefiles; Owner: postgres
--

CREATE SEQUENCE shapefiles.mpa_buffer_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE shapefiles.mpa_buffer_gid_seq OWNER TO postgres;

--
-- Name: mpa_buffer_gid_seq; Type: SEQUENCE OWNED BY; Schema: shapefiles; Owner: postgres
--

ALTER SEQUENCE shapefiles.mpa_buffer_gid_seq OWNED BY shapefiles.mpa_buffer.gid;


--
-- Name: mpa_gid_seq; Type: SEQUENCE; Schema: shapefiles; Owner: postgres
--

CREATE SEQUENCE shapefiles.mpa_gid_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE shapefiles.mpa_gid_seq OWNER TO postgres;

--
-- Name: mpa_gid_seq; Type: SEQUENCE OWNED BY; Schema: shapefiles; Owner: postgres
--

ALTER SEQUENCE shapefiles.mpa_gid_seq OWNED BY shapefiles.mpa.gid;


--
-- Name: captures; Type: TABLE; Schema: thon; Owner: postgres
--

CREATE TABLE thon.captures (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_lance uuid,
    rejete boolean,
    id_species uuid,
    taille character varying,
    poids double precision
);


ALTER TABLE thon.captures OWNER TO postgres;

--
-- Name: entreesortie; Type: TABLE; Schema: thon; Owner: postgres
--

CREATE TABLE thon.entreesortie (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_navire uuid,
    eez character varying(100),
    date_e date,
    heure_e time without time zone,
    entree boolean,
    yft character varying(100),
    bet character varying(100),
    skj character varying(100),
    fri character varying(100),
    remarques text,
    location public.geometry(Point,4326)
);


ALTER TABLE thon.entreesortie OWNER TO postgres;

--
-- Name: lance; Type: TABLE; Schema: thon; Owner: postgres
--

CREATE TABLE thon.lance (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_navire uuid,
    date_c date,
    heure_c time without time zone,
    eez character varying(100),
    success boolean,
    banclibre boolean,
    balise_id character varying(100),
    water_temp double precision,
    wind_speed double precision,
    wind_dir double precision,
    cur_speed double precision,
    comment text,
    location public.geometry(Point,4326)
);


ALTER TABLE thon.lance OWNER TO postgres;

--
-- Name: captures; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.captures (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    id_species uuid,
    poids double precision,
    comment text,
    n_ind double precision
);


ALTER TABLE trawlers.captures OWNER TO postgres;

--
-- Name: captures_mammal; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.captures_mammal (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    date date,
    "time" time without time zone,
    lance integer,
    id_species uuid,
    n_ind double precision,
    t_sex integer,
    taille character varying(100),
    t_capture integer,
    t_relache integer,
    preleve character varying(100),
    camera text,
    photo text,
    remarque text,
    poids double precision
);


ALTER TABLE trawlers.captures_mammal OWNER TO postgres;

--
-- Name: captures_requin; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.captures_requin (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    date date,
    "time" time without time zone,
    lance integer,
    id_species uuid,
    n_ind double precision,
    t_sex integer,
    taille character varying(100),
    poids double precision,
    t_capture integer,
    t_relache integer,
    preleve character varying(100),
    camera text,
    photo text,
    remarque text
);


ALTER TABLE trawlers.captures_requin OWNER TO postgres;

--
-- Name: captures_tortue; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.captures_tortue (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    date date,
    "time" time without time zone,
    id_species uuid,
    n_ind double precision,
    t_sex integer,
    length double precision,
    width double precision,
    ring boolean,
    position_1 integer,
    code_1 character varying(100),
    position_2 integer,
    code_2 character varying(100),
    t_capture integer,
    t_relache integer,
    resumation boolean,
    resumation_res boolean,
    preleve character varying(100),
    camera text,
    photo text,
    remarque text
);


ALTER TABLE trawlers.captures_tortue OWNER TO postgres;

--
-- Name: cm_cre; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.cm_cre (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    id_species uuid,
    t_taille_poi integer,
    t_taille_cre integer,
    poids double precision,
    cm4_cre integer,
    cm5_cre integer,
    cm6_cre integer,
    cm7_cre integer,
    cm8_cre integer,
    cm9_cre integer,
    cm10_cre integer,
    cm11_cre integer,
    cm12_cre integer,
    cm13_cre integer,
    cm14_cre integer,
    cm15_cre integer,
    cm16_cre integer,
    cm17_cre integer,
    cm18_cre integer,
    cm19_cre integer,
    cm20_cre integer,
    cm21_cre integer,
    cm22_cre integer,
    cm23_cre integer,
    cm24_cre integer,
    cm25_cre integer,
    cm26_cre integer,
    cm27_cre integer,
    cm28_cre integer,
    cm29_cre integer,
    cm30_cre integer,
    cm31_cre integer,
    cm32_cre integer,
    cm33_cre integer,
    cm34_cre integer,
    cm35_cre integer,
    cm36_cre integer,
    cm37_cre integer,
    cm38_cre integer,
    cm39_cre integer,
    cm40_cre integer,
    cm41_cre integer,
    cm42_cre integer,
    cm43_cre integer,
    cm44_cre integer,
    cm45_cre integer,
    cm46_cre integer,
    cm47_cre integer,
    cm48_cre integer,
    cm49_cre integer,
    cm50_cre integer,
    cm51_cre integer,
    cm52_cre integer,
    cm53_cre integer,
    cm54_cre integer,
    cm55_cre integer,
    cm56_cre integer,
    cm57_cre integer,
    cm58_cre integer,
    cm59_cre integer,
    cm60_cre integer,
    cm61_cre integer,
    cm62_cre integer,
    cm63_cre integer,
    cm64_cre integer,
    cm65_cre integer,
    cm66_cre integer,
    cm67_cre integer,
    cm68_cre integer,
    cm69_cre integer,
    cm70_cre integer,
    cm71_cre integer,
    cm72_cre integer,
    cm73_cre integer,
    cm74_cre integer,
    cm75_cre integer,
    cm76_cre integer,
    cm77_cre integer,
    cm78_cre integer,
    cm79_cre integer,
    cm80_cre integer,
    cm81_cre integer,
    cm82_cre integer,
    cm83_cre integer,
    cm84_cre integer,
    cm85_cre integer
);


ALTER TABLE trawlers.cm_cre OWNER TO postgres;

--
-- Name: cm_poi; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.cm_poi (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    id_species uuid,
    t_taille_poi integer,
    t_taille_cre integer,
    poids double precision,
    cm2_poi integer,
    cm3_poi integer,
    cm4_poi integer,
    cm5_poi integer,
    cm6_poi integer,
    cm7_poi integer,
    cm8_poi integer,
    cm9_poi integer,
    cm10_poi integer,
    cm11_poi integer,
    cm12_poi integer,
    cm13_poi integer,
    cm14_poi integer,
    cm15_poi integer,
    cm16_poi integer,
    cm17_poi integer,
    cm18_poi integer,
    cm19_poi integer,
    cm20_poi integer,
    cm21_poi integer,
    cm22_poi integer,
    cm23_poi integer,
    cm24_poi integer,
    cm25_poi integer,
    cm26_poi integer,
    cm27_poi integer,
    cm28_poi integer,
    cm29_poi integer,
    cm30_poi integer,
    cm31_poi integer,
    cm32_poi integer,
    cm33_poi integer,
    cm34_poi integer,
    cm35_poi integer,
    cm36_poi integer,
    cm37_poi integer,
    cm38_poi integer,
    cm39_poi integer,
    cm40_poi integer,
    cm41_poi integer,
    cm42_poi integer,
    cm43_poi integer,
    cm44_poi integer,
    cm45_poi integer,
    cm46_poi integer,
    cm47_poi integer,
    cm48_poi integer,
    cm49_poi integer,
    cm50_poi integer,
    cm51_poi integer,
    cm52_poi integer,
    cm53_poi integer,
    cm54_poi integer,
    cm55_poi integer,
    cm56_poi integer,
    cm57_poi integer,
    cm58_poi integer,
    cm59_poi integer,
    cm60_poi integer,
    cm61_poi integer,
    cm62_poi integer,
    cm63_poi integer,
    cm64_poi integer,
    cm65_poi integer,
    cm66_poi integer,
    cm67_poi integer,
    cm68_poi integer,
    cm69_poi integer,
    cm70_poi integer,
    cm71_poi integer,
    cm72_poi integer,
    cm73_poi integer,
    cm74_poi integer,
    cm75_poi integer,
    cm76_poi integer,
    cm77_poi integer,
    cm78_poi integer,
    cm79_poi integer,
    cm80_poi integer,
    cm81_poi integer,
    cm82_poi integer,
    cm83_poi integer,
    cm84_poi integer,
    cm85_poi integer,
    cm86_poi integer,
    cm87_poi integer,
    cm88_poi integer,
    cm89_poi integer,
    cm90_poi integer,
    cm91_poi integer,
    cm92_poi integer,
    cm93_poi integer,
    cm94_poi integer,
    cm95_poi integer,
    cm96_poi integer,
    cm97_poi integer,
    cm98_poi integer,
    cm99_poi integer,
    cm100_poi integer,
    cm101_poi integer,
    cm102_poi integer,
    cm103_poi integer,
    cm104_poi integer,
    cm105_poi integer,
    cm106_poi integer,
    cm107_poi integer,
    cm108_poi integer,
    cm109_poi integer,
    cm110_poi integer
);


ALTER TABLE trawlers.cm_poi OWNER TO postgres;

--
-- Name: ft_cre; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.ft_cre (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    id_species uuid,
    t_sex integer,
    t_maturity integer,
    poids double precision,
    ft1_cre integer,
    ft2_cre integer,
    ft3_cre integer,
    ft4_cre integer,
    ft5_cre integer,
    ft6_cre integer,
    ft7_cre integer,
    ft8_cre integer,
    ft9_cre integer,
    ft10_cre integer,
    ft11_cre integer,
    ft12_cre integer,
    ft13_cre integer,
    ft14_cre integer,
    ft15_cre integer,
    ft16_cre integer,
    ft17_cre integer,
    ft18_cre integer,
    ft19_cre integer,
    ft20_cre integer,
    ft21_cre integer,
    ft22_cre integer,
    ft23_cre integer,
    ft24_cre integer,
    ft25_cre integer,
    ft26_cre integer,
    ft27_cre integer,
    ft28_cre integer,
    ft29_cre integer,
    ft30_cre integer,
    ft31_cre integer,
    ft32_cre integer,
    ft33_cre integer,
    ft34_cre integer,
    ft35_cre integer,
    ft36_cre integer,
    ft37_cre integer,
    ft38_cre integer,
    ft39_cre integer,
    ft40_cre integer,
    ft41_cre integer,
    ft42_cre integer,
    ft43_cre integer,
    ft44_cre integer,
    ft45_cre integer,
    ft46_cre integer,
    ft47_cre integer,
    ft48_cre integer,
    ft49_cre integer,
    ft50_cre integer,
    ft51_cre integer,
    ft52_cre integer,
    ft53_cre integer,
    ft54_cre integer,
    ft55_cre integer,
    ft56_cre integer,
    ft57_cre integer,
    ft58_cre integer,
    ft59_cre integer,
    ft60_cre integer,
    ft61_cre integer,
    ft62_cre integer,
    ft63_cre integer,
    ft64_cre integer,
    ft65_cre integer,
    ft66_cre integer,
    ft67_cre integer,
    ft68_cre integer,
    ft69_cre integer,
    ft70_cre integer,
    ft71_cre integer,
    ft72_cre integer,
    ft73_cre integer,
    ft74_cre integer,
    ft75_cre integer,
    ft76_cre integer,
    ft77_cre integer,
    ft78_cre integer,
    ft79_cre integer,
    ft80_cre integer,
    ft81_cre integer,
    ft82_cre integer,
    ft83_cre integer,
    ft84_cre integer,
    ft85_cre integer,
    ft86_cre integer,
    ft87_cre integer,
    ft88_cre integer,
    ft89_cre integer,
    ft90_cre integer,
    ft91_cre integer,
    ft92_cre integer,
    ft93_cre integer,
    ft94_cre integer,
    ft95_cre integer,
    ft96_cre integer,
    ft97_cre integer,
    ft98_cre integer,
    ft99_cre integer,
    ft100_cre integer
);


ALTER TABLE trawlers.ft_cre OWNER TO postgres;

--
-- Name: ft_poi; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.ft_poi (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    t_rejete integer,
    id_species uuid,
    t_measure integer,
    poids double precision,
    ft1_poi integer,
    ft2_poi integer,
    ft3_poi integer,
    ft4_poi integer,
    ft5_poi integer,
    ft6_poi integer,
    ft7_poi integer,
    ft8_poi integer,
    ft9_poi integer,
    ft10_poi integer,
    ft11_poi integer,
    ft12_poi integer,
    ft13_poi integer,
    ft14_poi integer,
    ft15_poi integer,
    ft16_poi integer,
    ft17_poi integer,
    ft18_poi integer,
    ft19_poi integer,
    ft20_poi integer,
    ft21_poi integer,
    ft22_poi integer,
    ft23_poi integer,
    ft24_poi integer,
    ft25_poi integer,
    ft26_poi integer,
    ft27_poi integer,
    ft28_poi integer,
    ft29_poi integer,
    ft30_poi integer,
    ft31_poi integer,
    ft32_poi integer,
    ft33_poi integer,
    ft34_poi integer,
    ft35_poi integer,
    ft36_poi integer,
    ft37_poi integer,
    ft38_poi integer,
    ft39_poi integer,
    ft40_poi integer,
    ft41_poi integer,
    ft42_poi integer,
    ft43_poi integer,
    ft44_poi integer,
    ft45_poi integer,
    ft46_poi integer,
    ft47_poi integer,
    ft48_poi integer,
    ft49_poi integer,
    ft50_poi integer,
    ft51_poi integer,
    ft52_poi integer,
    ft53_poi integer,
    ft54_poi integer,
    ft55_poi integer,
    ft56_poi integer,
    ft57_poi integer,
    ft58_poi integer,
    ft59_poi integer,
    ft60_poi integer,
    ft61_poi integer,
    ft62_poi integer,
    ft63_poi integer,
    ft64_poi integer,
    ft65_poi integer,
    ft66_poi integer,
    ft67_poi integer,
    ft68_poi integer,
    ft69_poi integer,
    ft70_poi integer,
    ft71_poi integer,
    ft72_poi integer,
    ft73_poi integer,
    ft74_poi integer,
    ft75_poi integer,
    ft76_poi integer,
    ft77_poi integer,
    ft78_poi integer,
    ft79_poi integer,
    ft80_poi integer,
    ft81_poi integer,
    ft82_poi integer,
    ft83_poi integer,
    ft84_poi integer,
    ft85_poi integer,
    ft86_poi integer,
    ft87_poi integer,
    ft88_poi integer,
    ft89_poi integer,
    ft90_poi integer,
    ft91_poi integer,
    ft92_poi integer,
    ft93_poi integer,
    ft94_poi integer,
    ft95_poi integer,
    ft96_poi integer,
    ft97_poi integer,
    ft98_poi integer,
    ft99_poi integer,
    ft100_poi integer,
    ft101_poi integer,
    ft102_poi integer,
    ft103_poi integer,
    ft104_poi integer,
    ft105_poi integer,
    ft106_poi integer,
    ft107_poi integer,
    ft108_poi integer,
    ft109_poi integer,
    ft110_poi integer,
    ft111_poi integer,
    ft112_poi integer
);


ALTER TABLE trawlers.ft_poi OWNER TO postgres;

--
-- Name: p_day; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.p_day (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    id_navire uuid,
    date_d date,
    lance_d integer,
    lance_f integer,
    id_species uuid,
    c0_cre double precision,
    c1_cre double precision,
    c2_cre double precision,
    c3_cre double precision,
    c4_cre double precision,
    c5_cre double precision,
    c6_cre double precision,
    c7_cre double precision,
    c8_cre double precision,
    c9_cre double precision,
    c0_poi double precision,
    c1_poi double precision,
    c2_poi double precision,
    c3_poi double precision,
    c4_poi double precision,
    c5_poi double precision,
    c6_poi double precision,
    comment text
);


ALTER TABLE trawlers.p_day OWNER TO postgres;

--
-- Name: p_lance; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.p_lance (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    id_species uuid,
    maree character varying(100),
    lance integer,
    c0_cre double precision,
    c1_cre double precision,
    c2_cre double precision,
    c3_cre double precision,
    c4_cre double precision,
    c5_cre double precision,
    c6_cre double precision,
    c7_cre double precision,
    c8_cre double precision,
    c9_cre double precision,
    c0_poi double precision,
    c1_poi double precision,
    c2_poi double precision,
    c3_poi double precision,
    c4_poi double precision,
    c5_poi double precision,
    c6_poi double precision,
    comment text
);


ALTER TABLE trawlers.p_lance OWNER TO postgres;

--
-- Name: poids_taille; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.poids_taille (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    id_species uuid,
    t_measure integer,
    taille double precision,
    p1 double precision,
    p2 double precision,
    p3 double precision,
    p4 double precision,
    p5 double precision
);


ALTER TABLE trawlers.poids_taille OWNER TO postgres;

--
-- Name: route; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.route (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_navire uuid,
    maree text,
    t_fleet integer,
    date date,
    lance integer,
    h_d time without time zone,
    h_f time without time zone,
    depth_d real,
    depth_f real,
    speed real,
    reject real,
    sample real,
    comment text,
    location_d public.geometry(Point,4326),
    location_f public.geometry(Point,4326)
);


ALTER TABLE trawlers.route OWNER TO postgres;

--
-- Name: route_accidentelle; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.route_accidentelle (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    t_fleet integer,
    id_navire uuid,
    maree character varying(100),
    date date,
    "time" time without time zone,
    t_co integer,
    lance integer,
    location public.geometry(Point,4326)
);


ALTER TABLE trawlers.route_accidentelle OWNER TO postgres;

--
-- Name: t_co; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_co (
    id integer NOT NULL,
    co character varying(100)
);


ALTER TABLE trawlers.t_co OWNER TO postgres;

--
-- Name: t_condition; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_condition (
    id integer NOT NULL,
    condition character varying(100)
);


ALTER TABLE trawlers.t_condition OWNER TO postgres;

--
-- Name: t_fleet; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_fleet (
    id integer NOT NULL,
    fleet character varying(100)
);


ALTER TABLE trawlers.t_fleet OWNER TO postgres;

--
-- Name: t_maturity; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_maturity (
    id integer NOT NULL,
    maturity character varying(100)
);


ALTER TABLE trawlers.t_maturity OWNER TO postgres;

--
-- Name: t_measure; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_measure (
    id integer NOT NULL,
    measure character varying(100)
);


ALTER TABLE trawlers.t_measure OWNER TO postgres;

--
-- Name: t_project; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_project (
    id integer NOT NULL,
    project character varying(100)
);


ALTER TABLE trawlers.t_project OWNER TO postgres;

--
-- Name: t_rejete; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_rejete (
    id integer NOT NULL,
    rejete character varying(100)
);


ALTER TABLE trawlers.t_rejete OWNER TO postgres;

--
-- Name: t_ring; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_ring (
    id integer NOT NULL,
    ring character varying(100)
);


ALTER TABLE trawlers.t_ring OWNER TO postgres;

--
-- Name: t_role; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_role (
    id integer NOT NULL,
    role character varying(100)
);


ALTER TABLE trawlers.t_role OWNER TO postgres;

--
-- Name: t_sex; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_sex (
    id integer NOT NULL,
    sex character varying(100)
);


ALTER TABLE trawlers.t_sex OWNER TO postgres;

--
-- Name: t_taille_cre; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_taille_cre (
    id integer NOT NULL,
    taille_cre character varying(100)
);


ALTER TABLE trawlers.t_taille_cre OWNER TO postgres;

--
-- Name: t_taille_poi; Type: TABLE; Schema: trawlers; Owner: postgres
--

CREATE TABLE trawlers.t_taille_poi (
    id integer NOT NULL,
    taille_poi character varying(100)
);


ALTER TABLE trawlers.t_taille_poi OWNER TO postgres;

--
-- Name: captures; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.captures (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    id_species uuid,
    poids double precision,
    comment text,
    n_ind real
);


ALTER TABLE trawlers_server.captures OWNER TO postgres;

--
-- Name: captures_mammal; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.captures_mammal (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    date date,
    "time" time without time zone,
    lance integer,
    id_species uuid,
    n_ind double precision,
    t_sex integer,
    taille character varying(100),
    t_capture integer,
    t_relache integer,
    preleve character varying(100),
    camera text,
    photo text,
    remarque text,
    poids double precision
);


ALTER TABLE trawlers_server.captures_mammal OWNER TO postgres;

--
-- Name: captures_requin; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.captures_requin (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    date date,
    "time" time without time zone,
    lance integer,
    id_species uuid,
    n_ind double precision,
    t_sex integer,
    taille character varying(100),
    poids double precision,
    t_capture integer,
    t_relache integer,
    preleve character varying(100),
    camera text,
    photo text,
    remarque text
);


ALTER TABLE trawlers_server.captures_requin OWNER TO postgres;

--
-- Name: captures_tortue; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.captures_tortue (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    date date,
    "time" time without time zone,
    id_species uuid,
    n_ind double precision,
    t_sex integer,
    length double precision,
    width double precision,
    ring boolean,
    position_1 integer,
    code_1 character varying(100),
    position_2 integer,
    code_2 character varying(100),
    t_capture integer,
    t_relache integer,
    resumation boolean,
    resumation_res boolean,
    preleve character varying(100),
    camera text,
    photo text,
    remarque text
);


ALTER TABLE trawlers_server.captures_tortue OWNER TO postgres;

--
-- Name: cm_cre; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.cm_cre (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    id_species uuid,
    t_taille_poi integer,
    t_taille_cre integer,
    poids double precision,
    cm4_cre integer,
    cm5_cre integer,
    cm6_cre integer,
    cm7_cre integer,
    cm8_cre integer,
    cm9_cre integer,
    cm10_cre integer,
    cm11_cre integer,
    cm12_cre integer,
    cm13_cre integer,
    cm14_cre integer,
    cm15_cre integer,
    cm16_cre integer,
    cm17_cre integer,
    cm18_cre integer,
    cm19_cre integer,
    cm20_cre integer,
    cm21_cre integer,
    cm22_cre integer,
    cm23_cre integer,
    cm24_cre integer,
    cm25_cre integer,
    cm26_cre integer,
    cm27_cre integer,
    cm28_cre integer,
    cm29_cre integer,
    cm30_cre integer,
    cm31_cre integer,
    cm32_cre integer,
    cm33_cre integer,
    cm34_cre integer,
    cm35_cre integer,
    cm36_cre integer,
    cm37_cre integer,
    cm38_cre integer,
    cm39_cre integer,
    cm40_cre integer,
    cm41_cre integer,
    cm42_cre integer,
    cm43_cre integer,
    cm44_cre integer,
    cm45_cre integer,
    cm46_cre integer,
    cm47_cre integer,
    cm48_cre integer,
    cm49_cre integer,
    cm50_cre integer,
    cm51_cre integer,
    cm52_cre integer,
    cm53_cre integer,
    cm54_cre integer,
    cm55_cre integer,
    cm56_cre integer,
    cm57_cre integer,
    cm58_cre integer,
    cm59_cre integer,
    cm60_cre integer,
    cm61_cre integer,
    cm62_cre integer,
    cm63_cre integer,
    cm64_cre integer,
    cm65_cre integer,
    cm66_cre integer,
    cm67_cre integer,
    cm68_cre integer,
    cm69_cre integer,
    cm70_cre integer,
    cm71_cre integer,
    cm72_cre integer,
    cm73_cre integer,
    cm74_cre integer,
    cm75_cre integer,
    cm76_cre integer,
    cm77_cre integer,
    cm78_cre integer,
    cm79_cre integer,
    cm80_cre integer,
    cm81_cre integer,
    cm82_cre integer,
    cm83_cre integer,
    cm84_cre integer,
    cm85_cre integer
);


ALTER TABLE trawlers_server.cm_cre OWNER TO postgres;

--
-- Name: cm_poi; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.cm_poi (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    id_species uuid,
    t_taille_poi integer,
    t_taille_cre integer,
    poids double precision,
    cm2_poi integer,
    cm3_poi integer,
    cm4_poi integer,
    cm5_poi integer,
    cm6_poi integer,
    cm7_poi integer,
    cm8_poi integer,
    cm9_poi integer,
    cm10_poi integer,
    cm11_poi integer,
    cm12_poi integer,
    cm13_poi integer,
    cm14_poi integer,
    cm15_poi integer,
    cm16_poi integer,
    cm17_poi integer,
    cm18_poi integer,
    cm19_poi integer,
    cm20_poi integer,
    cm21_poi integer,
    cm22_poi integer,
    cm23_poi integer,
    cm24_poi integer,
    cm25_poi integer,
    cm26_poi integer,
    cm27_poi integer,
    cm28_poi integer,
    cm29_poi integer,
    cm30_poi integer,
    cm31_poi integer,
    cm32_poi integer,
    cm33_poi integer,
    cm34_poi integer,
    cm35_poi integer,
    cm36_poi integer,
    cm37_poi integer,
    cm38_poi integer,
    cm39_poi integer,
    cm40_poi integer,
    cm41_poi integer,
    cm42_poi integer,
    cm43_poi integer,
    cm44_poi integer,
    cm45_poi integer,
    cm46_poi integer,
    cm47_poi integer,
    cm48_poi integer,
    cm49_poi integer,
    cm50_poi integer,
    cm51_poi integer,
    cm52_poi integer,
    cm53_poi integer,
    cm54_poi integer,
    cm55_poi integer,
    cm56_poi integer,
    cm57_poi integer,
    cm58_poi integer,
    cm59_poi integer,
    cm60_poi integer,
    cm61_poi integer,
    cm62_poi integer,
    cm63_poi integer,
    cm64_poi integer,
    cm65_poi integer,
    cm66_poi integer,
    cm67_poi integer,
    cm68_poi integer,
    cm69_poi integer,
    cm70_poi integer,
    cm71_poi integer,
    cm72_poi integer,
    cm73_poi integer,
    cm74_poi integer,
    cm75_poi integer,
    cm76_poi integer,
    cm77_poi integer,
    cm78_poi integer,
    cm79_poi integer,
    cm80_poi integer,
    cm81_poi integer,
    cm82_poi integer,
    cm83_poi integer,
    cm84_poi integer,
    cm85_poi integer,
    cm86_poi integer,
    cm87_poi integer,
    cm88_poi integer,
    cm89_poi integer,
    cm90_poi integer,
    cm91_poi integer,
    cm92_poi integer,
    cm93_poi integer,
    cm94_poi integer,
    cm95_poi integer,
    cm96_poi integer,
    cm97_poi integer,
    cm98_poi integer,
    cm99_poi integer,
    cm100_poi integer,
    cm101_poi integer,
    cm102_poi integer,
    cm103_poi integer,
    cm104_poi integer,
    cm105_poi integer,
    cm106_poi integer,
    cm107_poi integer,
    cm108_poi integer,
    cm109_poi integer,
    cm110_poi integer
);


ALTER TABLE trawlers_server.cm_poi OWNER TO postgres;

--
-- Name: ft_cre; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.ft_cre (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    id_species uuid,
    t_sex integer,
    t_maturity integer,
    poids double precision,
    ft1_cre integer,
    ft2_cre integer,
    ft3_cre integer,
    ft4_cre integer,
    ft5_cre integer,
    ft6_cre integer,
    ft7_cre integer,
    ft8_cre integer,
    ft9_cre integer,
    ft10_cre integer,
    ft11_cre integer,
    ft12_cre integer,
    ft13_cre integer,
    ft14_cre integer,
    ft15_cre integer,
    ft16_cre integer,
    ft17_cre integer,
    ft18_cre integer,
    ft19_cre integer,
    ft20_cre integer,
    ft21_cre integer,
    ft22_cre integer,
    ft23_cre integer,
    ft24_cre integer,
    ft25_cre integer,
    ft26_cre integer,
    ft27_cre integer,
    ft28_cre integer,
    ft29_cre integer,
    ft30_cre integer,
    ft31_cre integer,
    ft32_cre integer,
    ft33_cre integer,
    ft34_cre integer,
    ft35_cre integer,
    ft36_cre integer,
    ft37_cre integer,
    ft38_cre integer,
    ft39_cre integer,
    ft40_cre integer,
    ft41_cre integer,
    ft42_cre integer,
    ft43_cre integer,
    ft44_cre integer,
    ft45_cre integer,
    ft46_cre integer,
    ft47_cre integer,
    ft48_cre integer,
    ft49_cre integer,
    ft50_cre integer,
    ft51_cre integer,
    ft52_cre integer,
    ft53_cre integer,
    ft54_cre integer,
    ft55_cre integer,
    ft56_cre integer,
    ft57_cre integer,
    ft58_cre integer,
    ft59_cre integer,
    ft60_cre integer,
    ft61_cre integer,
    ft62_cre integer,
    ft63_cre integer,
    ft64_cre integer,
    ft65_cre integer,
    ft66_cre integer,
    ft67_cre integer,
    ft68_cre integer,
    ft69_cre integer,
    ft70_cre integer,
    ft71_cre integer,
    ft72_cre integer,
    ft73_cre integer,
    ft74_cre integer,
    ft75_cre integer,
    ft76_cre integer,
    ft77_cre integer,
    ft78_cre integer,
    ft79_cre integer,
    ft80_cre integer,
    ft81_cre integer,
    ft82_cre integer,
    ft83_cre integer,
    ft84_cre integer,
    ft85_cre integer,
    ft86_cre integer,
    ft87_cre integer,
    ft88_cre integer,
    ft89_cre integer,
    ft90_cre integer,
    ft91_cre integer,
    ft92_cre integer,
    ft93_cre integer,
    ft94_cre integer,
    ft95_cre integer,
    ft96_cre integer,
    ft97_cre integer,
    ft98_cre integer,
    ft99_cre integer,
    ft100_cre integer
);


ALTER TABLE trawlers_server.ft_cre OWNER TO postgres;

--
-- Name: ft_poi; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.ft_poi (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    maree character varying(100),
    lance integer,
    t_rejete integer,
    id_species uuid,
    t_measure integer,
    poids double precision,
    ft1_poi integer,
    ft2_poi integer,
    ft3_poi integer,
    ft4_poi integer,
    ft5_poi integer,
    ft6_poi integer,
    ft7_poi integer,
    ft8_poi integer,
    ft9_poi integer,
    ft10_poi integer,
    ft11_poi integer,
    ft12_poi integer,
    ft13_poi integer,
    ft14_poi integer,
    ft15_poi integer,
    ft16_poi integer,
    ft17_poi integer,
    ft18_poi integer,
    ft19_poi integer,
    ft20_poi integer,
    ft21_poi integer,
    ft22_poi integer,
    ft23_poi integer,
    ft24_poi integer,
    ft25_poi integer,
    ft26_poi integer,
    ft27_poi integer,
    ft28_poi integer,
    ft29_poi integer,
    ft30_poi integer,
    ft31_poi integer,
    ft32_poi integer,
    ft33_poi integer,
    ft34_poi integer,
    ft35_poi integer,
    ft36_poi integer,
    ft37_poi integer,
    ft38_poi integer,
    ft39_poi integer,
    ft40_poi integer,
    ft41_poi integer,
    ft42_poi integer,
    ft43_poi integer,
    ft44_poi integer,
    ft45_poi integer,
    ft46_poi integer,
    ft47_poi integer,
    ft48_poi integer,
    ft49_poi integer,
    ft50_poi integer,
    ft51_poi integer,
    ft52_poi integer,
    ft53_poi integer,
    ft54_poi integer,
    ft55_poi integer,
    ft56_poi integer,
    ft57_poi integer,
    ft58_poi integer,
    ft59_poi integer,
    ft60_poi integer,
    ft61_poi integer,
    ft62_poi integer,
    ft63_poi integer,
    ft64_poi integer,
    ft65_poi integer,
    ft66_poi integer,
    ft67_poi integer,
    ft68_poi integer,
    ft69_poi integer,
    ft70_poi integer,
    ft71_poi integer,
    ft72_poi integer,
    ft73_poi integer,
    ft74_poi integer,
    ft75_poi integer,
    ft76_poi integer,
    ft77_poi integer,
    ft78_poi integer,
    ft79_poi integer,
    ft80_poi integer,
    ft81_poi integer,
    ft82_poi integer,
    ft83_poi integer,
    ft84_poi integer,
    ft85_poi integer,
    ft86_poi integer,
    ft87_poi integer,
    ft88_poi integer,
    ft89_poi integer,
    ft90_poi integer,
    ft91_poi integer,
    ft92_poi integer,
    ft93_poi integer,
    ft94_poi integer,
    ft95_poi integer,
    ft96_poi integer,
    ft97_poi integer,
    ft98_poi integer,
    ft99_poi integer,
    ft100_poi integer,
    ft101_poi integer,
    ft102_poi integer,
    ft103_poi integer,
    ft104_poi integer,
    ft105_poi integer,
    ft106_poi integer,
    ft107_poi integer,
    ft108_poi integer,
    ft109_poi integer,
    ft110_poi integer,
    ft111_poi integer,
    ft112_poi integer
);


ALTER TABLE trawlers_server.ft_poi OWNER TO postgres;

--
-- Name: p_day; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.p_day (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    id_navire uuid,
    date_d date,
    lance_d integer,
    lance_f integer,
    id_species uuid,
    c0_cre double precision,
    c1_cre double precision,
    c2_cre double precision,
    c3_cre double precision,
    c4_cre double precision,
    c5_cre double precision,
    c6_cre double precision,
    c7_cre double precision,
    c8_cre double precision,
    c9_cre double precision,
    c0_poi double precision,
    c1_poi double precision,
    c2_poi double precision,
    c3_poi double precision,
    c4_poi double precision,
    c5_poi double precision,
    c6_poi double precision,
    comment text
);


ALTER TABLE trawlers_server.p_day OWNER TO postgres;

--
-- Name: p_lance; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.p_lance (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_route uuid,
    id_species uuid,
    maree character varying(100),
    lance integer,
    c0_cre double precision,
    c1_cre double precision,
    c2_cre double precision,
    c3_cre double precision,
    c4_cre double precision,
    c5_cre double precision,
    c6_cre double precision,
    c7_cre double precision,
    c8_cre double precision,
    c9_cre double precision,
    c0_poi double precision,
    c1_poi double precision,
    c2_poi double precision,
    c3_poi double precision,
    c4_poi double precision,
    c5_poi double precision,
    c6_poi double precision,
    comment text
);


ALTER TABLE trawlers_server.p_lance OWNER TO postgres;

--
-- Name: poids_taille; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.poids_taille (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    maree character varying(100),
    id_species uuid,
    t_measure integer,
    taille double precision,
    p1 double precision,
    p2 double precision,
    p3 double precision,
    p4 double precision,
    p5 double precision
);


ALTER TABLE trawlers_server.poids_taille OWNER TO postgres;

--
-- Name: route; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.route (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_navire uuid,
    maree text,
    t_fleet integer,
    date date,
    lance integer,
    h_d time without time zone,
    h_f time without time zone,
    depth_d real,
    depth_f real,
    speed real,
    reject real,
    sample real,
    comment text,
    location_d public.geometry(Point,4326),
    location_f public.geometry(Point,4326)
);


ALTER TABLE trawlers_server.route OWNER TO postgres;

--
-- Name: route_accidentelle; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.route_accidentelle (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    t_fleet integer,
    id_navire uuid,
    maree character varying(100),
    date date,
    "time" time without time zone,
    t_co integer,
    lance integer,
    location public.geometry(Point,4326)
);


ALTER TABLE trawlers_server.route_accidentelle OWNER TO postgres;

--
-- Name: t_co; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_co (
    id integer NOT NULL,
    co character varying(100)
);


ALTER TABLE trawlers_server.t_co OWNER TO postgres;

--
-- Name: t_condition; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_condition (
    id integer NOT NULL,
    condition character varying(100)
);


ALTER TABLE trawlers_server.t_condition OWNER TO postgres;

--
-- Name: t_fleet; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_fleet (
    id integer NOT NULL,
    fleet character varying(100)
);


ALTER TABLE trawlers_server.t_fleet OWNER TO postgres;

--
-- Name: t_maturity; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_maturity (
    id integer NOT NULL,
    maturity character varying(100)
);


ALTER TABLE trawlers_server.t_maturity OWNER TO postgres;

--
-- Name: t_measure; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_measure (
    id integer NOT NULL,
    measure character varying(100)
);


ALTER TABLE trawlers_server.t_measure OWNER TO postgres;

--
-- Name: t_project; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_project (
    id integer NOT NULL,
    project character varying(100)
);


ALTER TABLE trawlers_server.t_project OWNER TO postgres;

--
-- Name: t_rejete; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_rejete (
    id integer NOT NULL,
    rejete character varying(100)
);


ALTER TABLE trawlers_server.t_rejete OWNER TO postgres;

--
-- Name: t_ring; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_ring (
    id integer NOT NULL,
    ring character varying(100)
);


ALTER TABLE trawlers_server.t_ring OWNER TO postgres;

--
-- Name: t_role; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_role (
    id integer NOT NULL,
    role character varying(100)
);


ALTER TABLE trawlers_server.t_role OWNER TO postgres;

--
-- Name: t_sex; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_sex (
    id integer NOT NULL,
    sex character varying(100)
);


ALTER TABLE trawlers_server.t_sex OWNER TO postgres;

--
-- Name: t_taille_cre; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_taille_cre (
    id integer NOT NULL,
    taille_cre character varying(100)
);


ALTER TABLE trawlers_server.t_taille_cre OWNER TO postgres;

--
-- Name: t_taille_poi; Type: TABLE; Schema: trawlers_server; Owner: postgres
--

CREATE TABLE trawlers_server.t_taille_poi (
    id integer NOT NULL,
    taille_poi character varying(100)
);


ALTER TABLE trawlers_server.t_taille_poi OWNER TO postgres;

--
-- Name: project; Type: TABLE; Schema: users; Owner: postgres
--

CREATE TABLE users.project (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_user uuid,
    t_project integer,
    t_role integer,
    active boolean
);


ALTER TABLE users.project OWNER TO postgres;

--
-- Name: t_project; Type: TABLE; Schema: users; Owner: postgres
--

CREATE TABLE users.t_project (
    id integer NOT NULL,
    project character varying(100),
    active boolean
);


ALTER TABLE users.t_project OWNER TO postgres;

--
-- Name: t_role; Type: TABLE; Schema: users; Owner: postgres
--

CREATE TABLE users.t_role (
    id integer NOT NULL,
    role character varying(100),
    active boolean
);


ALTER TABLE users.t_role OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: users; Owner: postgres
--

CREATE TABLE users.users (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    first_name character varying(100),
    last_name character varying(100),
    nickname character varying(100),
    email character varying(200),
    password text
);


ALTER TABLE users.users OWNER TO postgres;

--
-- Name: navire; Type: TABLE; Schema: vms; Owner: postgres
--

CREATE TABLE vms.navire (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    navire character varying(100),
    flag character varying(100),
    owners character varying(100),
    fullname character varying(100),
    radio character varying(100),
    registration character varying(100),
    registration_ext character varying(100),
    registration_int character varying(100),
    registration_qrt character varying(100),
    mobile character varying(100),
    mmsi character varying(100),
    imo character varying(100),
    port character varying(100),
    active boolean,
    beacon character varying(100),
    satellite character varying(100),
    unknown character varying(100),
    t_navire integer,
    other_names character varying
);


ALTER TABLE vms.navire OWNER TO postgres;

--
-- Name: positions; Type: TABLE; Schema: vms; Owner: postgres
--

CREATE TABLE vms.positions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    datetime timestamp without time zone DEFAULT now(),
    username character varying(100),
    id_navire uuid,
    date_p timestamp without time zone,
    speed double precision,
    location public.geometry(Point,4326)
);


ALTER TABLE vms.positions OWNER TO postgres;

--
-- Name: t_navire; Type: TABLE; Schema: vms; Owner: postgres
--

CREATE TABLE vms.t_navire (
    id integer NOT NULL,
    navire character varying(100)
);


ALTER TABLE vms.t_navire OWNER TO postgres;

--
-- Name: carte carte; Type: DEFAULT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.carte ALTER COLUMN carte SET DEFAULT nextval('artisanal.carte_carte_seq'::regclass);


--
-- Name: license license; Type: DEFAULT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.license ALTER COLUMN license SET DEFAULT nextval('artisanal.license_license_seq'::regclass);


--
-- Name: t_license id; Type: DEFAULT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_license ALTER COLUMN id SET DEFAULT nextval('artisanal.t_license_id_seq'::regclass);


--
-- Name: t_site id; Type: DEFAULT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_site ALTER COLUMN id SET DEFAULT nextval('artisanal.t_site_id_seq'::regclass);


--
-- Name: t_site_obb id; Type: DEFAULT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_site_obb ALTER COLUMN id SET DEFAULT nextval('artisanal.t_site_obb_id_seq'::regclass);


--
-- Name: t_status id; Type: DEFAULT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_status ALTER COLUMN id SET DEFAULT nextval('artisanal.t_status_id_seq'::regclass);


--
-- Name: t_strata id; Type: DEFAULT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_strata ALTER COLUMN id SET DEFAULT nextval('artisanal.t_strata_id_seq'::regclass);


--
-- Name: t_study id; Type: DEFAULT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_study ALTER COLUMN id SET DEFAULT nextval('artisanal.t_study_id_seq'::regclass);


--
-- Name: -a gid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."-a" ALTER COLUMN gid SET DEFAULT nextval('public."-a_gid_seq"'::regclass);


--
-- Name: mpa gid; Type: DEFAULT; Schema: shapefiles; Owner: postgres
--

ALTER TABLE ONLY shapefiles.mpa ALTER COLUMN gid SET DEFAULT nextval('shapefiles.mpa_gid_seq'::regclass);


--
-- Name: mpa_buffer gid; Type: DEFAULT; Schema: shapefiles; Owner: postgres
--

ALTER TABLE ONLY shapefiles.mpa_buffer ALTER COLUMN gid SET DEFAULT nextval('shapefiles.mpa_buffer_gid_seq'::regclass);


--
-- Data for Name: captures; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.captures (id, datetime, username, id_maree, id_species, wgt_tot, wgt_spc, n_ind) FROM stdin;
\.


--
-- Data for Name: carte; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.carte (id, datetime, username, carte, id_fisherman, date_v, id_license, active, paid) FROM stdin;
\.


--
-- Data for Name: effort; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.effort (id, datetime, username, date_e, obs_name, t_site, db1, dh1, db3, dh3, ps1, pc1, ps3, pc3) FROM stdin;
\.


--
-- Data for Name: fisherman; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.fisherman (id, datetime, username, first_name, last_name, bday, wives, children, t_card, idcard, ycard, address, t_nationality, telephone, photo_data, comments, id_temp) FROM stdin;
\.


--
-- Data for Name: fleet; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.fleet (id, datetime, username, date_f, obs_name, t_site, source, ppb, gpf, ppf, tot) FROM stdin;
\.


--
-- Data for Name: infraction; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.infraction (id, datetime, username, date_i, id_license, id_pirogue, pir_name, immatriculation, id_carte, id_fisherman, fish_first, fish_last, fish_idcard, t_org, name, obj_confiscated, amount_infract, payment, receipt, comments, location, settled) FROM stdin;
\.


--
-- Data for Name: infractions; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.infractions (id, datetime, username, t_infraction, id_infraction) FROM stdin;
\.


--
-- Data for Name: license; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.license (id, datetime, username, license, date_v, t_license, t_license_2, t_gear, t_gear_2, t_site, t_site_obb, mesh_min, mesh_max, length, mesh_min_2, mesh_max_2, length_2, engine_brand, engine_cv, payment, receipt, agasa, t_coop, id_pirogue, active, id_temp, comments) FROM stdin;
\.


--
-- Data for Name: maree; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.maree (id, datetime, username, datetime_d, datetime_r, obs_name, t_site, t_study, id_pirogue, immatriculation, t_gear, mesh_min, mesh_max, length, wgt_tot, gps_file, gps_track) FROM stdin;
\.


--
-- Data for Name: market; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.market (id, datetime, username, date_m, obs_name, t_site, id_species, p_s, p_p, p_c, p_m, p_f) FROM stdin;
\.


--
-- Data for Name: owner; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.owner (id, datetime, username, first_name, last_name, bday, wives, children, t_card, idcard, ycard, address, t_nationality, telephone, photo_data, comments, id_temp) FROM stdin;
\.


--
-- Data for Name: pelagic_lkp; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.pelagic_lkp (id, datetime, date_t, name, location) FROM stdin;
\.


--
-- Data for Name: pelagic_points; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.pelagic_points (id, datetime, date_t, name, speed, range, heading, location) FROM stdin;
\.


--
-- Data for Name: pelagic_tracks; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.pelagic_tracks (id, datetime, date_t, name, speed, range, heading, location) FROM stdin;
\.


--
-- Data for Name: pirogue; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.pirogue (id, datetime, username, name, immatriculation, t_pirogue, length, id_owner, comments, id_temp, photo_data_1, photo_data_2, photo_data_3, plate) FROM stdin;
\.


--
-- Data for Name: t_card; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_card (id, card, active) FROM stdin;
0	Carte de Sejour	t
1	Carte Nationale de Identite	t
2	Passeport	t
3	Carte Professionnelle	t
4	Permis de Conduire	t
5	Recepisse	t
6	Acte de Naissance	t
\.


--
-- Data for Name: t_coop; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_coop (id, coop, active) FROM stdin;
0	Razel fishermen	t
1	Ami des pecheurs du gabon	t
2	Ehuzu 1	t
3	Ehuzu 2	t
4	BTI 1	t
5	Parc à Bois	t
6	Petit Village	t
7	Bac Aviation 1	t
8	Bac Aviation 2	t
9	Alenakiri	t
10	Akiliba	t
11	Grande Poubelle	t
12	COGAPAC	t
13	Ambowé 1	t
14	Ambowé 2	t
15	Soduco	t
16	Arche des pêcheurs	t
17	Michel Marine	t
18	Jeanne Ebory	t
19	Bambouchine	t
20	Vision d'Avenir	t
21	COPEGA	t
22	Emergence	t
23	Renaissance	t
24	CPAR 2	t
25	CAPAL	t
26	COGAPAM	t
27	CPACS (Sablière)	t
28	Belive in God	t
29	Requin Blanc	t
30	Bisso bi Name	t
31	POG	t
32	Mayumba	t
33	INDEPENDANT	t
34	MONO	t
35	Eledji	t
36	Gbenodou	t
37	Avenir	t
38	Elevagnon	t
39	Matanda Paletuvier	t
40	Mouvement des pêcheurs d'Iguiri	t
41	ETHRO	t
42	MURI MWER	t
43	SCONAPAC	t
44	CEPG	t
45	Chemin de or	t
46	Dieu merci	t
47	Gabon de abord	t
\.


--
-- Data for Name: t_gear; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_gear (id, gear, active) FROM stdin;
0	Piege a crabe	t
1	Filet maillant de fond	t
2	Filet maillant de surface	t
3	Filet maillant encerclant	t
4	Senne tournante (Tire-tire) [interdit]	t
5	Filet sp.	t
6	Ligne a main	t
7	Palangre	t
8	Senne de plage [interdit]	t
\.


--
-- Data for Name: t_immatriculation; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_immatriculation (id, immatriculation, active) FROM stdin;
0	L	t
1	MAY	t
2	PG	t
3	OW	t
4	CC	t
5	OMB	t
6	AK	t
7	KG	t
8	LA	t
9	GBA	t
\.


--
-- Data for Name: t_infraction; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_infraction (id, infraction) FROM stdin;
\.


--
-- Data for Name: t_license; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_license (id, license, active) FROM stdin;
0	Poissons	t
1	Mulets	t
2	Sardines	t
3	Crustaces	t
\.


--
-- Data for Name: t_nationality; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_nationality (id, nationality, active) FROM stdin;
0	Americaine	t
1	Beninoise	t
2	Burkinabe	t
3	Camerounaise	t
4	Cap Verdienne	t
5	Equato-Guineenne	t
6	Francaise	t
7	Gabonaise	t
8	Ghaneenne	t
9	Nigeriane	t
10	Sao-Tomeenne	t
11	Togolaise	t
12	Senegalaise	t
13	Congolaise	t
14	Nigerienne	t
15	Peruvienne	t
16	Malienne	t
17	Ivorienne	t
\.


--
-- Data for Name: t_navire; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_navire (id, navire) FROM stdin;
\.


--
-- Data for Name: t_pirogue; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_pirogue (id, pirogue, active) FROM stdin;
\.


--
-- Data for Name: t_registration; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_registration (id, registration) FROM stdin;
\.


--
-- Data for Name: t_site; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_site (id, site, strata, region, code, active, location) FROM stdin;
\.


--
-- Data for Name: t_site_obb; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_site_obb (id, site, strata, region, code, active, location) FROM stdin;
\.


--
-- Data for Name: t_status; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_status (id, status, active) FROM stdin;
0	Marie	t
1	Celibataire	t
2	Veuf	t
3	Concubinage	t
\.


--
-- Data for Name: t_strata; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_strata (id, strata, active) FROM stdin;
0	Cocobeach	t
1	Libreville	t
2	Port Gentil	t
3	Mayumba	t
\.


--
-- Data for Name: t_study; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_study (id, study, active) FROM stdin;
\.


--
-- Data for Name: t_zone; Type: TABLE DATA; Schema: artisanal; Owner: postgres
--

COPY artisanal.t_zone (id, zone, active) FROM stdin;
0	Cocobeach	t
1	Estuaire-Komo	t
2	Haute mer	t
\.


--
-- Data for Name: enq_catch; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.enq_catch (id, datetime, username, id_maree, id_species, wgt) FROM stdin;
\.


--
-- Data for Name: enq_maree; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.enq_maree (id, datetime, username, datetime_d, datetime_r, obs_name, t_site, t_gear, id_pirogue, immatriculation) FROM stdin;
\.


--
-- Data for Name: log_catch; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.log_catch (id, datetime, username, id_maree, id_species, wgt) FROM stdin;
\.


--
-- Data for Name: log_maree; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.log_maree (id, datetime, username, datetime_d, datetime_r, t_site, id_pirogue, immatriculation) FROM stdin;
\.


--
-- Data for Name: obs_action; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.obs_action (id, datetime, username, date_a, time_a, id_maree, wpt, t_gear, boarded, comments, location) FROM stdin;
\.


--
-- Data for Name: obs_catch; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.obs_catch (id, datetime, username, id_maree, id_species, wgt_tot, wgt_spc, n_ind) FROM stdin;
\.


--
-- Data for Name: obs_fish; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.obs_fish (id, datetime, username, id_maree, id_species, n_lot, per, wgt) FROM stdin;
\.


--
-- Data for Name: obs_mammals; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.obs_mammals (id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, lt, wgt, t_status, t_action, photo_data, comments) FROM stdin;
\.


--
-- Data for Name: obs_maree; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.obs_maree (id, datetime, username, obs_name, t_mission, date_d, time_d, t_site_d, date_r, time_r, t_site_r, n_deb, zone, id_pirogue, immatriculation, engine, t_gear_1, length_1, height_1, mesh_min_1, mesh_max_1, t_gear_2, length_2, height_2, mesh_min_2, mesh_max_2, t_gear_3, length_3, height_3, mesh_min_3, mesh_max_3, gps_file, comments, gps_track) FROM stdin;
\.


--
-- Data for Name: obs_poids_taille; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.obs_poids_taille (id, datetime, username, id_maree, id_species, t_maturity, t_measure, length, wgt) FROM stdin;
\.


--
-- Data for Name: obs_sharks; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.obs_sharks (id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, lt, la, wgt, t_status, t_action, photo_data, comments) FROM stdin;
\.


--
-- Data for Name: obs_turtles; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.obs_turtles (id, datetime, username, id_maree, id_action, id_species, t_sex, t_maturity, bague, integrity, fibrop, epibionte, lt, wgt, t_status, t_action, photo_data, comments) FROM stdin;
\.


--
-- Data for Name: t_action; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.t_action (id, action) FROM stdin;
\.


--
-- Data for Name: t_gear; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.t_gear (id, gear) FROM stdin;
\.


--
-- Data for Name: t_integrity; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.t_integrity (id, integrity) FROM stdin;
\.


--
-- Data for Name: t_maturity; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.t_maturity (id, maturity) FROM stdin;
\.


--
-- Data for Name: t_mission; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.t_mission (id, mission) FROM stdin;
\.


--
-- Data for Name: t_sex; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.t_sex (id, sex) FROM stdin;
\.


--
-- Data for Name: t_status; Type: TABLE DATA; Schema: artisanal_catches; Owner: postgres
--

COPY artisanal_catches.t_status (id, status) FROM stdin;
\.


--
-- Data for Name: capture; Type: TABLE DATA; Schema: crevette; Owner: postgres
--

COPY crevette.capture (id, datetime, username, id_lance, id_species, t_taille, poids) FROM stdin;
\.


--
-- Data for Name: lance; Type: TABLE DATA; Schema: crevette; Owner: postgres
--

COPY crevette.lance (id, datetime, username, id_navire, date_l, t_zone, lance, h_d, h_f, d_d, d_f, t_d, rejets, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c_cre, cc_cre, o_cre, v6_cre, location_d, location_f) FROM stdin;
\.


--
-- Data for Name: t_taille; Type: TABLE DATA; Schema: crevette; Owner: postgres
--

COPY crevette.t_taille (id, taille) FROM stdin;
\.


--
-- Data for Name: t_zone; Type: TABLE DATA; Schema: crevette; Owner: postgres
--

COPY crevette.t_zone (id, zone) FROM stdin;
\.


--
-- Data for Name: species; Type: TABLE DATA; Schema: fishery; Owner: postgres
--

COPY fishery.species (id, francaise, family, genus, species, fao, obs, category, iucn) FROM stdin;
9a47391b-fcc8-46a4-a01f-d4963d0b0530	Chirurgien chas-chas	ACANTHURIDAE	Acanthurus	monroviae	MDO	ACA/Aca.mon	\N	\N
3eb91892-2ce0-45ec-a292-a1127b74c9ee	\N	ACANTHURIDAE	spp	\N	\N	ACA	\N	\N
410c43e6-a287-4843-b7eb-cce8ded76a41	Banane de mer	ALBULIDAE	Albula	vulpes	BOF	ALB/Alb.vul	\N	\N
1eda4f2e-54e7-4aeb-a6cc-4c9ffb52ad52	\N	ALBULIDAE	spp	\N	\N	ALB	\N	\N
87dce644-8f8e-4405-9d82-445c50d27592	Requin renard indéterminé	ALOPIIDAE	Alopias	spp	THR	REQ/Alo	\N	\N
ef45a929-ab1b-46b1-b01f-2bc753fdef9b	Renard	ALOPIIDAE	Alopias	vulpinus	ALV	REQ/Alo.vul	\N	\N
14465f9e-e4fe-44ef-8334-7a189bf597eb	\N	ALOPIIDAE	spp	\N	\N	REQ/Alo	\N	\N
bd8ae9e6-5d8d-4e37-9f1d-821a64c48b1c	Apogonidés	APOGONIDAE	spp	\N	\N	APO	\N	\N
b4cab705-911a-4335-8e42-084295f6df4b	Mâchoiron géant	ARIIDAE	Arius	gigas	AUG	ARI/Ari.gig	\N	\N
23580849-9a31-46c9-9099-0842e8e6d105	Mâchoiron banderille	ARIIDAE	Arius	heudeloti	SMC	ARI/Ari.heu	\N	\N
e2005ea9-83b4-47c6-91b4-2d557f16086e	Mâchoiron de Gambie	ARIIDAE	Arius	latiscutatus	AUR	ARI/Ari.lat	\N	\N
c58cbe86-4469-4be6-af1f-56445a28298c	Mâchoiron jaune	ARIIDAE	Arius	parkeri	AWP	ARI/Ari.par	\N	\N
31b1f801-bf95-4d6c-bce0-8ac0a34fc2be	Mâchoiron indéterminé	ARIIDAE	spp	\N	AWX	ARI	crevette	\N
6aa8f5ef-82e1-46b4-8532-4bd2104c66a9	Ariomme indéterminé	ARIOMMATIDAE	spp	\N	DRK	ARIO	\N	\N
17958f4a-ce06-44ba-8ec2-65da2563dff9	Etoile de mer indéterminée	ASTEROIDEA	spp	\N	STF	AST	\N	\N
d820c95f-3fed-48a4-8b28-59871916a12b	Trompette	AULOSTOMIDAE	Aulostomus	strigosus	AGQ	AUL	\N	\N
1b773caf-a5e4-4944-98af-805c00f753b9	Baliste étoilé	BALISTIDAE	Abalistes	stellatus	AJS	BAL/Aba.ste	\N	\N
9aa748fb-8057-48a6-b0e4-87e46faa302e	Baliste royale	BALISTIDAE	Balistes	vetula	BLV	BAL/Bal.vet	\N	\N
3370ad8e-628b-4f9e-8cc3-69dd9497a29b	Baliste rude	BALISTIDAE	Canthidermis	maculatus	CNT	BAL/Can.mac	\N	\N
e1fe8469-ce82-48fa-8f5f-4a9fcc765385	Baliste indéterminé	BALISTIDAE	spp	\N	TRI	BAL	\N	\N
d67b8986-c495-46a4-85ea-bc163a1d753d	Baliste à tâches bleues	BALISTIDAE	Xenobalistes	punctatus	XEP	BAL/Xen.pun	\N	\N
6d0f59a4-cf4b-4692-ac29-2e2a769e1fcf	Crapaud poilu	BATRACHOIDAE	Batrachoides	liberiensis	BBL	BAT/Bat.lib	\N	\N
8a6d47ac-13e1-44f2-a941-d06b59c77801	Crapaud indéterminé	BATRACHOIDAE	spp	\N	TDF	BAT	\N	\N
6ac8a65b-15eb-4b93-8591-f94d56aef89f	Orphie plate	BELONIDAE	Ablennes	hians	BAF	BEL/Abl.hia	\N	\N
a2a17ee5-82a5-4bbb-ad15-5aa3408c88f1	Aiguille, Orphie indéterminé	BELONIDAE	spp	\N	BES	BEL	\N	\N
df037fc4-bacc-4def-9aee-ee103f99a43f	\N	BELONIDAE	Strongylura	senegalensis	SZW	BEL/Str.sen	\N	\N
512eea50-827f-4302-8493-9907d39d7f08	Aiguille voyeuse	BELONIDAE	Tylosurus	acus	AND	BEL/Tyl.acu	\N	\N
26050926-dca9-475f-94de-7e624c624a44	Aiguille crocodile	BELONIDAE	Tylosurus	crocodilus	BTS	BEL/Tyl.cro	\N	\N
db8b066b-d059-419b-91f7-0d346d76449a	Bivalve indéterminé	BIVALVIA	spp	\N	CLX	BIVALVIA	\N	\N
b03d0873-8f23-4cd1-b2ef-7dd32f555fff	Blennie indéterminée	BLENNIDAE	spp	\N	BLE	BLE	\N	\N
aac22f98-4878-4baa-bb94-3005f29127be	Arnoglosse du Cap	BOTHIDAE	Arnoglossus	capensis	RGK	BOT/Amo.cap	\N	\N
6c6e11ff-e638-486b-a4fc-38fe5b841a5d	Fosse imande pate	BOTHIDAE	Syacium	micrurum	YAM	BOT/Sya.mic	\N	\N
ae2c242f-7895-496b-831c-2fee6143946a	Rombou podas	BOTHIDAE	Bothus	podas	OUB	BOT/Bot.pod	\N	\N
1c8f3164-9d69-4aee-8df9-405b50df51d6	Arnoglosse indéterminé	BOTHIDAE	spp	\N	LEF	BOT	\N	\N
89dbc5fa-34bf-4e19-84e9-2ee18e2a72f5	Brame indéterminé	BRAMIDAE	spp	\N	BRA	BRM	\N	\N
3baf52da-d822-4d4f-ba4f-da3e45a7e0a7	Tile indéterminé	BRANCHIOSTEGIDAE	spp	\N	TIS	BRN	\N	\N
6a80abd4-970b-4eb0-9c7b-55996d31dfaf	Migraine rugueuse	CALAPPIDAE	Calappa	gallus	KAG	CRAB/Cal.gal	\N	\N
15dfe8db-f42b-406d-9fbc-a8a0e2928a17	Migraine épineuse	CALAPPIDAE	Calappa	pelii	KAP	CRAB/Cal.pel	\N	\N
ee64a536-0424-4945-9fc2-36140d2b7809	Migraine maculée	CALAPPIDAE	Calappa	rubroguttata	KAR	CRAB/Cal.rub	\N	\N
703c3854-7659-40c1-8682-ad9a4ff1232c	\N	CALAPPIDAE	Calappa	spp	\N	CRAB/Cal	\N	\N
a96a7a08-1071-4bbb-b8a4-1d3adae6aac4	\N	CALAPPIDAE	spp	\N	\N	CRAB	\N	\N
59dda822-aec4-4875-8b7b-5ede6c11a2fe	Cordonnier bossu (Yamal)	CARANGIDAE	Alectis	alexandrinus	ALA	CAR/Ale.ale	crevette	\N
c672e614-1a5a-4547-aabd-e715897cafe2	Cordonnier fil	CARANGIDAE	Alectis	ciliaris	LIJ	CAR/Ale.cil	\N	\N
c5dfe493-7430-4b35-b085-ef92605688cf	Liche lirio	CARANGIDAE	Campogramma	glaycos	VAD	CAR/Cam.gla	\N	\N
0d0e2b0e-7890-408d-8c5e-21afea80e2cf	Carangue mayole	CARANGIDAE	Caranx	latus	NXL	CAR/Car.lat	\N	\N
84599912-dd93-481f-a768-4d83d7ef19ee	Carangue coubali	CARANGIDAE	Caranx	crysos	RUB	CAR/Car.cry	\N	\N
f9cf40aa-833c-42de-991f-01f6ace780d3	Carangue de Fischer	CARANGIDAE	Caranx	fischeri	WFF	CAR/Car.fis	\N	\N
2afe31e4-38e6-41b7-8246-f95f018da23d	Carangue crevalle	CARANGIDAE	Caranx	hippos	CVJ	CAR/Car.hip	\N	\N
27835f6c-4242-45d7-a640-588dde20b702	\N	CARANGIDAE	Caranx	lugubris	NXU	CAR/Car.lug	\N	\N
77e00450-9b66-458c-81ee-0df158826aba	Carangue du Sénégal	CARANGIDAE	Caranx	senegallus	NXS	CAR/Car.sen	\N	\N
2e55f821-1607-4a46-8b77-ea0bb6e790e4	Sapater	CARANGIDAE	Chloroscombrus	chrysurus	BUA	CAR/Chl.chr	\N	\N
180a28e7-45a0-466c-be80-2c9a4c043c45	Chinchards	CARANGIDAE	Decapterus	spp	SDX	CAR/Dec	\N	\N
3aa9e520-c425-483b-a2cc-76fcecb8b4ae	Comète maquereau	CARANGIDAE	Decapterus	macarellus	MSD	CAR/Dec.mac	\N	\N
ec022c1f-ec4c-41ae-82f8-7cb45ca3f2f8	Comète quiaquia	CARANGIDAE	Decapterus	punctatus	WEC	CAR/Dec.pun	\N	\N
9a543397-c770-4d5b-8dc5-15c542a518f3	Comète coussut	CARANGIDAE	Decapterus	rhonchus	HMY	CAR/Dec.rho	\N	\N
f0300d81-a244-42a3-bcf5-9a10584aa33d	Comète saumon	CARANGIDAE	Elagatis	bipinnulata	RRU	CAR/Ela.bip	\N	\N
c1b37a4a-27ea-4e26-8fb5-d4aa8959528a	Carangue bicolore	CARANGIDAE	Hemicaranx	bicolor	HXB	CAR/Hem.bic	\N	\N
6d359672-a43e-497a-a954-cc4e5e201c7d	Liche	CARANGIDAE	Lichia	amia	LEE	CAR/Lic.ami	\N	\N
74735488-cc9c-49eb-9246-287d29d7ce9d	Poisson pilote	CARANGIDAE	Naucrates	ductor	NAU	CAR/Nau.duc	\N	\N
1dcef1a8-c764-4291-b07f-5422e0d84f92	Musso africain	CARANGIDAE	Selene	dorsalis	LUK	CAR/Sel.dor	\N	\N
4a3093e7-17cc-4b9e-b46c-94f24e436ffb	Sélar coulisou	CARANGIDAE	Selar	Crumenophthalmus	BIS	CAR/Sel.cru	\N	\N
4c00dc04-9f50-410d-9320-dfa2941494c9	Sériole guinéenne	CARANGIDAE	Seriola	carpenteri	RLR	CAR/Ser.car	\N	\N
17c49415-24d3-4e67-86a1-e354697cd9a7	Sériole couronnée	CARANGIDAE	Seriola	dumerili	AMB	CAR/Ser.dum	\N	\N
b6172974-8f0e-4278-ab45-46603ac090a4	Seriole indéterminée	CARANGIDAE	Seriola	spp	AMX	CAR/Ser	\N	\N
1ce2fc52-c79b-40f8-b01e-557ad61863e6	Carangue	CARANGIDAE	spp	\N	CGX	CAR	crevette	\N
02b681c2-89ba-47d0-b02d-3caa38238eaf	Pompaneau	CARANGIDAE	Trachinotus	spp	POX	CAR/Tra	\N	\N
6e857c4c-b70c-4bb8-83ce-0e2bdc701264	Pompaneau tacheté	CARANGIDAE	Trachinotus	goreensis	TOG	CAR/Tra.gor	\N	\N
3202ad82-cec1-4e8d-b0bf-18f8c5ce3bce	Pompaneau chévron	CARANGIDAE	Trachinotus	maxillosus	TOO	CAR/Tra.max	\N	\N
94669860-cb56-4607-9b99-02f40e8ed55f	Palomine	CARANGIDAE	Trachinotus	ovatus	POP	CAR/Tra.ova	\N	\N
75192535-5277-4151-8bd3-0195ae60bbb2	Pompaneau né-bé	CARANGIDAE	Trachinotus	teraia	TIE	CAR/Tra.ter	\N	\N
0c5d93b7-de6b-475d-a9e0-4537a5ecfca5	Chinchard d'Europe	CARANGIDAE	Trachurus	trachurus	HOM	CAR/Tra.tra	\N	\N
613ec6a6-8612-413d-92f7-cd9428a5bfa2	Chinchard du Cunène	CARANGIDAE	Trachurus	trecae	HMZ	CAR/Tra.tre	\N	\N
c2aa566d-ca81-4601-8562-40c713bbc5da	Carangue coton	CARANGIDAE	Urapsis	helvola	USE	CAR/Ura.hel	\N	\N
14c972ba-b00c-4764-baa5-e3a78bacecbc	Requin babosse	CARCHARHINIDAE	Carcharhinus	altimus	CCA	CAC/Car.alt	\N	\N
0564cfb6-ac51-4774-9dde-89ef91bf511e	Requin balestrine	CARCHARHINIDAE	Carcharhinus	amboinensis	CCF	CAC/Car.amb	\N	\N
9d21f4db-2a25-4b70-8e50-67e7a28b6b03	Requin tisserand	CARCHARHINIDAE	Carcharhinus	brevipinna	CCB	CAC/Car.bre	\N	\N
80b9225b-f33e-49fa-abd8-980782f3bb28	Requin soyeux	CARCHARHINIDAE	Carcharhinus	falciformis	FAL	CAC/Car.fal	\N	\N
8ee00d54-1256-4682-a031-cf7bc54e6878	Requin bouledogue	CARCHARHINIDAE	Carcharhinus	leucas	CCE	CAC/Car.leu	\N	\N
156d6ea0-dd1a-4e30-b88b-d85da738b263	Requin bordé	CARCHARHINIDAE	Carcharhinus	limbatus	CCL	CAC/Car.lim	\N	\N
f0d45f0e-f036-4a32-9c53-da3662d03e42	Requin océanique	CARCHARHINIDAE	Carcharhinus	longimanus	OCS	CAC/Car.lon	\N	\N
2e961f8f-6917-4f33-a5bf-caacdcbb4eda	Requin sombre	CARCHARHINIDAE	Carcharhinus	obscurus	DUS	CAC/Car.obs	\N	\N
43529918-4ead-452c-9e03-9b1db9806d7f	Requin gris	CARCHARHINIDAE	Carcharhinus	plumbeus	CCP	CAC/Car.plu	\N	\N
6c0d51a5-06b6-4282-918c-e62e9a57e54b	Carcharhinus indéterminé	CARCHARHINIDAE	Carcharhinus	spp	CWZ	CAC/Car	\N	\N
839218cb-03ea-4727-9f69-0f01d3ebcfaf	Requin tigre commun	CARCHARHINIDAE	Galeocerdo	cuvier	TIG	CAC/Gal.cuv	\N	\N
389514c0-4f5c-4df8-b1e8-fbb10cba993e	Requin citron	CARCHARHINIDAE	Negaprion	brevirostris	NGB	CAC/Neg.bre	\N	\N
cc3d713a-b5e7-4089-9c22-cbb4220ee1f1	Peau bleue	CARCHARHINIDAE	Prionace	glauca	BSH	CAC/Pri.gla	\N	\N
67a87cab-3dd1-478e-ad60-b2d1794f1a92	Requin à museau pointu	CARCHARHINIDAE	Rhizoprionodon	acutus	RHA	CAC/Rhi.acu	\N	\N
c7ac46e1-7a7a-439e-bfcf-f74bb946ccd0	Requin indetermine	CARCHARHINIDAE	spp	\N	\N	CAC	crevette	\N
968e79c4-9016-478e-a555-eded1397d256	Picarel indéterminé	CENTRACANTHIDAE	spp	\N	CEZ	CEN	\N	\N
a5aa355b-0b54-449a-9994-936b05d98d7a	Picarel à Gros Yeux	CENTRACANTHIDAE	Spicara	alta	QZU	CEN/Spi.alt	\N	\N
0369209d-6072-41a2-a4e7-8f31a6581922	Centrolophe indéterminé	CENTROLOPHIDAE	spp	\N	CEN	\N	\N	\N
775dd2a9-8680-4c74-8d09-645391b46b8c	Papillon indéterminé	CHAETODONTIDAE	spp	\N	BUS	CHA	\N	\N
a127d535-7cb3-42f5-b3ea-f9fa6699f6c5	Tortue caouanne	CHELONIIDAE	Caretta	caretta	TTL	TOR/Car.car	\N	\N
a4a14e04-5415-4a35-a1fb-444e82eaeb85	Tortue verte	CHELONIIDAE	Chelonia	mydas	TUG	TOR/Che.myd	\N	\N
f15591f5-82a1-4589-a62a-35a3d25326ef	Tortue imbriquée	CHELONIIDAE	Eretmochelys	imbricata	TTH	TOR/Ere.imb	\N	\N
c0f9ce8d-c5e4-403e-ab2e-47c52a399e41	Tortue olivâtre	CHELONIIDAE	Lepidochelys	olivacea	LKV	TOR/Lep.oli	\N	\N
00ba8377-05cd-473f-a411-645c0c822a50	Tortue indetermine	CHELONIIDAE	spp	\N	\N	TOR	crevette	\N
daca4c5b-b922-41c6-a240-13f43fc9c818	Guinean tilapia (EN)	CICHLIDAE	Coptodon	guineensis	TLG	\N	\N	\N
5bf5e59f-6df3-41df-bea2-1d053ba63301	\N	CICHLIDAE	Hemichromis	elongatus	\N	\N	\N	\N
de8dd727-1bc4-4ff1-9c7d-5db62cb58ee0	\N	CICHLIDAE	Pelmatolapia	cabrae	TIR	\N	\N	\N
2e2941a2-a95e-4361-9dcf-b6979cc6cfd6	\N	CICHLIDAE	Sarotherodon	nigripinnis	\N	\N	\N	\N
6ed97c01-f2a1-4ea2-a4e0-f54ecaa7dd68	\N	CICHLIDAE	spp	\N	\N	\N	\N	\N
dbb7d030-8b95-4787-b7fe-081aa8e03746	Feuille	CITHARIDAE	Citharus	linguatula	CIL	CIT/Cit.lin	\N	\N
9ecb8fca-fcc6-4cb9-b722-7d63e1cbeeaa	\N	CLAROTEIDAE	Chrysichthys	auratus	BCY	\N	\N	\N
b62d5e5a-a744-411c-ae35-e61d4702220f	Bagrid catfish (EN)	CLAROTEIDAE	Chrysichthys	nigrodigitatus	CSR	\N	\N	\N
fd356b74-0d72-401b-b5fc-56b4227fd33a	\N	CLAROTEIDAE	spp	\N	\N	\N	\N	\N
8d141573-bff5-446d-b98c-00fc29595fae	Ethmalose d'Afrique	CLUPEIDAE	Ethmalosa	fimbriata	BOA	CLU/Eth.fim	\N	\N
5fd9f79d-4e21-4583-9159-39bd600e4618	Alose rasoir	CLUPEIDAE	Ilisha	africana	ILI	CLU/Ili.afr	\N	\N
7ee52ddb-b1df-4f05-af8a-c406a8392848	\N	CLUPEIDAE	Odaxothrissa	ansorgii	\N	\N	\N	\N
b868539d-ae50-4c67-a1fa-2bff48e791ac	\N	CLUPEIDAE	Pellonula	vorax	\N	\N	\N	\N
8982f586-4a97-4edb-ad11-43771e7c5962	Allache	CLUPEIDAE	Sardinella	aurita	SAA	CLU/Sar.aur	\N	\N
4585f106-d5bf-4e0f-96f8-e6954243da63	Grande allache	CLUPEIDAE	Sardinella	maderensis	SAE	CLU/Sar.mad	\N	\N
7f64ef1a-e8ee-40c9-922e-78bc139332b2	Sardine indéterminée	CLUPEIDAE	spp	\N	CLP	CLU	\N	\N
892f8024-16b4-4eb4-8249-5d99b5f44e30	Méduse indéterminée	CNIDARIA	spp	\N	CNI	CNI	\N	\N
c9aa0414-447d-4553-bc9b-24839845d727	\N	CONGRIDAE	Bathyuroconger	vicinus	CBV	CON/Bat.vic	\N	\N
865879e8-97a3-470a-a404-f2b089a1d505	Congre indéterminé	CONGRIDAE	spp	\N	COX	CON	\N	\N
711b9611-c42d-4634-a59e-4f5faaf060ab	Coryphène indéterminé	CORYPHAENIDAE	spp	\N	DOX	COR	\N	\N
c34b9a5c-037e-4ad1-93d9-c4f5a83a12ed	\N	CYNOGLOSSIDAE	Cynoglossus	browni	YOW	CYN/Cyn.bro	\N	\N
9d45ed04-3a1a-456d-8b87-b7315df4a502	Sole-langue canarienne	CYNOGLOSSIDAE	Cynoglossus	canariensis	YOI	CYN/Cyn.can	\N	\N
0b9aa345-40fc-4ccd-a47a-5e6d672b0027	\N	CYNOGLOSSIDAE	Cynoglossus	monodi	YQG	CYN/Cyn.mon	\N	\N
0ac66a40-2dd3-40ab-a951-9181af0bc93a	Sole-langue sénégalaise	CYNOGLOSSIDAE	Cynoglossus	senegalensis	YOE	CYN/Cyn.sen	\N	\N
c10e9b67-f6a0-4599-998d-519476e8e49b	Sole-langue du Ghana	CYNOGLOSSIDAE	Cynoglossus	cadenati	\N	CYN/Cyn.cad	\N	\N
a3399cfb-ca5b-4f60-aa54-2f5cbf4f0fa4	Sole-langue indéterminée	CYNOGLOSSIDAE	Cynoglossus	spp	YOX	\N	\N	\N
bc1456e7-7b29-4263-b2ce-e7bdb1dc3f18	Sole-langue indéterminée	CYNOGLOSSIDAE	spp	\N	TOX	CYN	\N	\N
b88f4d44-076a-4fa6-9527-33a3bbb9b03a	Grondin volant	DACTYLOPTERIDAE	Dactylopterus	volitans	DYL	DAC/Dac.vol	\N	\N
a642f6d8-a14c-4b87-a5ab-d06119065f3a	\N	DACTYLOPTERIDAE	spp	\N	\N	DAC	\N	\N
59330f89-e9fe-47f4-9e05-8dcc458e76e0	Pastenague épineuse	DASYATIDAE	Dasyatis	centroura	RDC	RAI/Das.cen	\N	\N
b2b5ed57-0125-4076-b32f-1ad6473db2e0	Pastenague marguerite	DASYATIDAE	Dasyatis	margarita	RDS	RAI/Das.mag	\N	\N
21622028-fdb7-45b0-bd8f-790d82c061f2	Pastenague margueritella	DASYATIDAE	Dasyatis	margaritella	RDE	RAI/Das.mal	\N	\N
da0f2468-00a5-4361-9f56-57b261a383ca	Pastenague commune	DASYATIDAE	Dasyatis	pastinaca	JDP	RAI/Das.pas	\N	\N
b79d3827-7b23-452b-a113-509914b0232c	\N	DASYATIDAE	Dasyatis	marmorata	RDQ	RAI/Das.mam	\N	\N
ef305dfa-b434-40c9-b72f-896a3217d5b8	\N	DASYATIDAE	Torpedo	marmorata	TTR	RAI/Tor.mar	\N	\N
d5a37aee-939d-4774-b4b9-0b37fecbb274	\N	DASYATIDAE	Dasyatis	ukpam	RDW	\N	\N	\N
f80323c1-614c-40d6-a15c-3e5adad456c4	\N	DASYATIDAE	Dasyatis	spp	STI	RAI/Das	\N	\N
7bc3edfd-7d15-49db-958d-a78946011b00	Pastenague africaine	DASYATIDAE	Taeniura	grabata	RTB	RAI/Tae.gra	\N	\N
471ae66f-9859-452f-aa0f-a27233d9537e	Tortue luth	DERMOCHELYIDAE	Dermochelys	coriacea	DKK	TOR/Der.cor	\N	\N
f19d6fac-849e-44f0-bda6-4b543a429f51	\N	DERMOCHELYIDAE	spp	\N	\N	\N	\N	\N
5599d339-e91e-4e14-9c90-1cd3d0780a44	\N	DINOPERCIDAE	Centrarchops	atlanticus	\N	\N	\N	\N
117ed2be-cf0d-4173-a21c-f0dcda1f0927	\N	DINOPERCIDAE	spp	\N	\N	\N	\N	\N
bc01995b-6087-4cb7-89dd-2032aa8967f7	Porsc-épic indéterminé	DIODONTIDAE	spp	\N	DIO	DIO	\N	\N
69839abd-8647-4428-91ea-dabf1160a4a5	Forgeron ailé (Disque)	DREPANIDAE	Drepane	africana	SIC	DRE/Dre.afr	crevette	\N
8d34e412-934b-4698-9912-1daeb3763ac8	\N	DREPANIDAE	spp	\N	\N	DRE	\N	\N
52bbb386-beea-4c62-a602-bd76939745ec	Remora indéterminé	ECHENEIDAE	spp	\N	REM	ECH	\N	\N
dd3c8deb-174c-4471-a337-284096f33418	Oursin indéterminé	ECHINOIDEA	spp	\N	URX	XOur	\N	\N
5d93bf66-2f0e-4bc5-863b-a614eae3434a	\N	ELEOTRIDAE	Bostrychus	africanus	OUY	\N	\N	\N
efcdd4e8-4b29-4f7b-88a5-4167af023972	\N	ELEOTRIDAE	Dormitator	lebretonis	DOH	\N	\N	\N
0fdbf8c7-22ca-42a4-a49f-e8ac4abd9142	\N	ELEOTRIDAE	Eleotris	daganensis	\N	\N	\N	\N
1c33d14e-99b3-4d6f-8e1b-7e029834bb0a	\N	ELEOTRIDAE	Eleotris	senegalensis	DZZ	\N	\N	\N
0bd605a2-8a03-4696-adbf-f4e71175ccc1	\N	ELEOTRIDAE	Eleotris	vittata	EOV	\N	\N	\N
9066f519-6e75-4b33-ac48-832b12d82bb0	\N	ELEOTRIDAE	Kribia	kribensis	KRK	\N	\N	\N
069e47eb-44cd-4081-9459-c5ed49f98be6	\N	ELEOTRIDAE	spp	\N	\N	\N	\N	\N
3f4a780b-6e51-47b0-b11a-97a40cc480e6	Banane de mer	ELOPIDAE	Elops	lacerta	CEC	ELO/Elo.lac	\N	\N
e86f7c5e-1b40-4842-83d1-8802d4b5fece	Guinée du Sénégal	ELOPIDAE	Elops	senegalensis	CEG	ELO/Elo.sen	\N	\N
3173f381-53ab-4930-bb0c-8c106030ec1f	\N	ELOPIDAE	spp	\N	\N	ELO	\N	\N
98ead580-1a15-4201-821e-d37423d3e646	Poisson rubis	EMMELICHTHYIDAE	Erythrocles	monodi	EYO	EMM/Ery.mon	\N	\N
c8925e28-312b-4d0f-bfa6-54a3873d7912	\N	EMMELICHTHYIDAE	spp	\N	\N	EMM	\N	\N
87470b41-b951-44ee-92b4-5ed37270a7dd	Anchois	ENGRAULIDAE	Engraulis	encrasicolus	ANE	ENG/Eng.enc	\N	\N
e652cc35-ee5e-4256-b24b-8123a990b6c3	\N	ENGRAULIDAE	spp	\N	\N	ENG	\N	\N
b8b9c348-16b2-48da-a108-19e4609b755a	chèvre de mer	EPHIPPIDAE	Chaetodipterus	goreensis	\N	\N	\N	\N
bdae6186-e0ce-4a24-bc37-1172ab51658d	West African spadefish (EN)	EPHIPPIDAE	Chaetodipterus	lippei	HRL	\N	\N	\N
f0e09052-dbc5-4ea8-abee-27b8b3bf6859	\N	EPHIPPIDAE	Ephippus	goreensis	\N	\N	\N	\N
679c3c23-970a-4106-a8db-004f48c27ad4	Ephippidé indéterminé	EPHIPPIDAE	spp	\N	SP	EPH	\N	\N
a02f51ae-e47c-4a47-a0f0-c951b758834e	Poisson volant indéterminé	EXOCOETIDAE	spp	\N	FLY	EXO	\N	\N
c26850c2-ec9f-4dff-9720-f07b06ca7b31	Cornette rouge	FISTULARIIDAE	Fistularia	petimba	FIP	FIS/Fis.pet	\N	\N
b0a3ef37-2931-484c-8a1f-c86bd3d325dc	Cornette tachetée	FISTULARIIDAE	Fistularia	tabacaria	FUT	FIS/Fis.tab	\N	\N
b5964d2f-d13f-46da-a194-92f9b9e7dc08	\N	FISTULARIIDAE	spp	\N	\N	FIS	\N	\N
e1bbb577-f81f-47d4-8fd5-76665f1b9177	Gastéropode indéterminé	GASTROPODA	spp	\N	GAS	GAS	\N	\N
57b17551-dfb5-4191-8f46-b7fbfa84f4c5	Blanche drapeau	GERREIDAE	Eucinostomus	melanopterus	MFF	GER/Euc.mel	\N	\N
8120a95c-a757-4b13-8409-e04d80713e6f	Friture rayée	GERREIDAE	Gerres	nigri	GEZ	GER/Ger.nig	\N	\N
3539e3ba-be5b-4bcb-9ef5-b8cc01f00626	Gerreidé indéterminé	GERREIDAE	spp	\N	GDJ	GER	\N	\N
468dc0aa-f333-4417-bb4a-ce93110c99dd	Requin-nourrice	GINGLYMOSTOMATIDAE	Ginglymostoma	cirratum	GNC	REG/Gin.cir	\N	\N
e894d9ef-9b57-4445-991e-d225d24d63c5	\N	GINGLYMOSTOMATIDAE	spp	\N	\N	REQ/Gin	\N	\N
679865b3-945d-49e0-aef9-dfa5703c443d	Blackchin guitarfish	GLAUCOSTEGIDAE	Glaucostegus	cemiculus	\N	RHI/Rhi.cem	\N	\N
9ae28081-8b3a-4832-a241-cc9adc0472d8	\N	GLAUCOSTEGIDAE	spp	\N	\N	RHI	\N	\N
00128a82-af9f-45b0-ba53-525d142525b6	\N	GOBIIDAE	Awaous	lateristriga	AWQ	\N	\N	\N
97828154-940c-4be5-a847-05a406a7cb0f	Frillfin goby (EN)	GOBIIDAE	Bathygobius	soporator	BJO	\N	\N	\N
a8aae502-8867-44a1-b3b4-4e1604d2243f	\N	GOBIIDAE	Gobioides	sagitta	GSW	\N	\N	\N
12118afd-cb03-44f9-a3e6-f1ff35676791	\N	GOBIIDAE	Periophthalmus	barbarus	FTI	\N	\N	\N
ef2a4409-9f05-4690-a984-fed3b953716d	\N	GOBIIDAE	spp	\N	\N	\N	\N	\N
0d18f3a4-b834-4825-b4fd-a4eafe73b667	Gorgone indéterminée	GORGONIIDAE	spp	\N	GGW	GOR	\N	\N
8e7e9258-326c-4c98-b464-04876d3a17a9	\N	GYMNURIDAE	Gymnura	micrura	RGI	RAI/Gym.mic	\N	\N
3720f19d-83a4-400c-b408-4e7f60892442	Raie-papillon indéterminée	GYMNURIDAE	Gymnura	spp	RBY	RAI/Gym	\N	\N
d642d5fd-ec56-4113-ad11-3b486a961998	Lippu pelon	HAEMULIDAE	Brachydeuterus	auritus	GRB	HAE/Bra.aur	\N	\N
ce024613-676f-4314-83c9-5d8421aa6688	Diagramme à grosses lèvres	HAEMULIDAE	Plectorhinchus	macrolepsis	GBL	HAE/Ple.mac	\N	\N
42b0786b-0a41-43a8-be8e-c34e2501619b	Diagramme gris	HAEMULIDAE	Plectorhinchus	mediterraneus	GBR	HAE/Ple.med	\N	\N
d49bbc79-e581-462b-8177-9b8cf69d11c4	Grondeur métis	HAEMULIDAE	Pomadasys	incisus	BGR	HAE/Pom.inc	\N	\N
99c7413b-ab0c-43ff-bd31-4e00327d9964	Grondeur sompat	HAEMULIDAE	Pomadasys	jubelini	BUR	HAE/Pom.jub	\N	\N
76def156-204e-4367-973e-3f65f523bd98	Grondeur perroquet	HAEMULIDAE	Pomadasys	peroteti	PKE	HAE/Pom.per	\N	\N
7b2a270f-f286-4623-8abf-beb56540ea8b	Dorade grise	HAEMULIDAE	Pomadasys	spp	GBX	HAE/Pom	\N	\N
c147ccee-0f54-4a1d-b74b-2fe6514f77ae	Grondeur nez de cochon	HAEMULIDAE	Pomadasys	rogeri	PKK	HAE/Pom.rog	\N	\N
0f0fbc02-112f-4abd-bfda-876b62008607	Haemulidé indéterminé	HAEMULIDAE	spp	\N	GRX	HAE	\N	\N
9508c462-ecfa-4d91-8a4a-7dfc06d39fd5	Milandre jaune	HEMIGALEIDAE	Parageleus	pectoralis	HEI	HEM/Par.pec	\N	\N
fff510dd-3a0d-4c4f-9073-268951ea47b0	Marignan rouge	HOLOCENTRIDAE	Sargocentron	hastatus	AXH	HOL/Sar.has	\N	\N
921969ef-2af3-4a45-86a2-53dbe1a5b95e	Marignan indéterminé	HOLOCENTRIDAE	ssp	\N	HCZ	HOL	\N	\N
53a00b89-40ff-41db-b910-31317c9d140e	Holothurie indéterminé	HOLOTHURIOIDEA	spp	\N	CUX	HOLOTHURIOIDEA	\N	\N
5b51c075-ac89-4505-9e28-fd6af7d8096d	Voilier de l'Atlantique	ISTIOPHORIDAE	lstiophorus	albicans	SAI	IST/Ist.alb	\N	\N
c6b4068f-b6ca-4990-bb4d-6ca42d669366	Makaire bleu	ISTIOPHORIDAE	Makaira	nigricans	BUM	IST/Mak.nig	\N	\N
a827364b-96ca-4a49-841e-bb69abd72e35	Porte-épée indéterminé	ISTIOPHORIDAE	spp	\N	BIL	IST	\N	\N
3e22d99c-d4a7-461c-9500-cb48ff6e44dc	Calicagère jaune	KYPHOSIDAE	Kyphosus	incisor	KYI	KYP/Kyp.inc	\N	\N
3bf65488-7a68-4c6a-a300-88976e92c14d	Calicagère indéterminé	KYPHOSIDAE	spp	\N	KYP	KYP	\N	\N
81b177a2-921d-42a1-9bf3-603829756075	Pourceau dos noir	LABRIDAE	Bodianus	speciosus	BZD	LAB/Bod.spe	\N	\N
c7361d6c-f00b-4ac6-869b-67b669a09fce	Labre indéterminé	LABRIDAE	spp	\N	WRA	LAB	\N	\N
6c66d378-1919-4a23-93f3-d24b6bd53cec	Donzelle lame	LABRIDAE	Xyrichthys	novacula	XYN	LAB/Xyr.nov	\N	\N
dfd512ff-79d1-428c-9331-f6aa13fd1756	Taupe bleue	LAMNIDAE	Isurus	oxyrinchus	SMA	LAM/Isu.oxy	\N	\N
6e848935-8085-42cb-b13f-b8a9171627b9	Petite taupe	LAMNIDAE	Isurus	paucus	LMA	LAM/Isu.pau	\N	\N
2e1eda99-69df-47b8-ad23-42b7d88daf2d	\N	LAMNIDAE	spp	\N	\N	LAM	\N	\N
32f91b0b-cf37-4217-ba1a-4b70b4f15e90	Emissole à grandes lèvres	LEPTOCHARIIDAE	Leptocharias	smithii	CLL	LEP/Lep.smi	\N	\N
7267b372-a281-42aa-ae19-730cdce951ef	\N	LEPTOCHARIIDAE	spp	\N	\N	LEP	\N	\N
9c1fdeb2-6079-4d4a-8703-671e673025e3	Carpe lethrine	LETHRINIDAE	Lethrinus	atlanticus	LTN	LET/Let.atl	\N	\N
265d5ae8-dbd1-4328-b53a-efb70d39fa19	Lethrinidé indéterminé	LETHRINIDAE	spp	\N	EMP	LET	\N	\N
9e257cf6-5aa3-4087-b842-576713d9f985	Croupia roche	LOBOTIDAE	Lobotes	surinamensis	LOB	LOB/Lob.sur	\N	\N
3a6d4228-de31-4ad2-b838-0f903dfb4c41	\N	LOBOTIDAE	spp	\N	\N	LOB	\N	\N
86500ee2-c096-488b-92f7-cf6eb69f787e	Calmar côtier indéterminé	LOLIGINIDAE	spp	\N	SQZ	TEU	\N	\N
0a7b7a18-d10c-40d7-8ee5-989719ca82a3	Lophe indéterminé	LOPHIIDAE	spp	\N	ANF	LOP	\N	\N
2df2c81c-1ac7-498a-99c3-1eef774f9763	Vivaneau fourche d'Afrique	LUTJANIDAE	Apsilus	fuscus	AFK	LUT/Aps.fus	\N	\N
efaf3a72-643e-422e-be40-e39c34ec18a0	Vivaneau africain rouge	LUTJANIDAE	Lutjanus	agennes	LJA	LUT/Lut.age	\N	\N
204fef8a-7fe8-4dca-a326-18e55f44a000	Vivaneau brun d'afrique (Rouge)	LUTJANIDAE	Lutjanus	dentatus	ASX	LUT/Lut.den	crevette	\N
7d4cd37c-9fc1-44df-bfd7-0e2de9165c80	Vivaneau de Guinée	LUTJANIDAE	Lutjanus	endecathus	QFM	LUT/Lut.end	\N	\N
d1e4cd2e-71f0-4371-9e41-dfa865e8a5ba	Vivaneau doré	LUTJANIDAE	Lutjanus	fulgens	LVN	LUT/Lut.ful	\N	\N
52e365dc-1b39-4413-9b55-75594f5ea55f	Vivaneau de Gorée	LUTJANIDAE	Lutjanus	goreensis	LJO	LUT/Lut.gor	\N	\N
74254069-7f80-424d-839d-4374e459bf83	Rouges	LUTJANIDAE	spp	\N	SNX	LUT	\N	\N
d0431430-229e-4a37-9f43-828e778e807b	Araignée européenne	MAJIDAE	Maja	squinado	SCR	\N	\N	\N
013de660-d648-46a0-81de-e3c5d8e5f684	Tarpon argenté	MEGALOPIDAE	Megalops	atlanticus	TAR	\N	\N	\N
eb335ae3-f70b-4254-b918-dcf81890b807	Mélongène noire	MELONGENIDAE	Pugilina	morio	UGO	Mel	\N	\N
2618fea5-55e9-4101-b340-4e071e333868	Raie manta	MOBULIDAE	Manta	birostris	RMB	RAI/Man.bir	\N	\N
c8eed43a-b363-48bb-8bcb-09bf25119671	Diable de mer	MOBULIDAE	Mobula	spp	\N	RAI/Mob	\N	\N
58af93b1-4d5f-4549-a978-794f12820e69	Diable géant de Guinée	MOBULIDAE	Mobula	coilloti	RMC	RAI/Mob.coi	\N	\N
640f2366-e2f0-450b-9f12-ed978e6c6108	Petit diable de Guinée	MOBULIDAE	Mobula	rochebrunei	RMN	RAI/Mob.roc	\N	\N
1a87c772-daf1-4ffa-9065-8dd784f10046	Mobulidé indéterminé	MOBULIDAE	spp	\N	MAN	RAI	\N	\N
1b45097e-0069-452b-9940-e8582ffdff59	Poisson-lune à quaue pointue	MOLIDAE	Masturus	lanceolatus	MRW	MOL/Mas.lan	\N	\N
7bec673f-0064-461d-85ff-e3cd6e085147	Poisson-lune	MOLIDAE	Mola	mola	MOX	MOL/Mol.mol	\N	\N
7bf99cc5-a049-4b4a-8ac2-23033c3d6a7c	\N	MOLIDAE	spp	\N	\N	MOL	\N	\N
af3e6ce8-8105-4d38-91cf-e5af8868d92a	Poisson-bourse indéterminé	MONACANTHIDAE	spp	\N	FFX	MON	\N	\N
29365a1a-ddc9-414c-b81e-30409aa0cacf	\N	MONODACTYLIDAE	Monodactylus	sebae	QBS	\N	\N	\N
59c9cda4-22aa-487d-b61a-6f4feb5e81d8	\N	MUGILIDAE	Liza	falcipinnis	KZY	\N	\N	\N
88058f59-cf11-4a0b-8d38-dfa80bacfaac	Mulet à grosse tête	MUGILIDAE	Mugil	cephalus	MUF	MUG/Mug.cep	\N	\N
48a54540-70a4-4c10-9700-038c7ac5b360	\N	MUGILIDAE	Mugil	bananensis	KZS	\N	\N	\N
2518ea50-cdb8-4843-8b04-371ade1f0b24	Mulet blanc	MUGILIDAE	Mugil	curema	MGA	\N	\N	\N
395c5cc5-77a3-4da2-8e59-3d872edf2612	\N	MUGILIDAE	Parachelon	grandisquamis	\N	\N	\N	\N
55c0f091-0a46-4f8d-9c94-a848aee295f1	Mulet indéterminé	MUGILIDAE	spp	\N	MUL	MUG	\N	\N
9c75b640-ac3a-4655-993b-417fd29fe721	Rouget barbet	MULLIDAE	Pseudupeneus	prayensis	GOA	MUL/Pse.pra	\N	\N
4fe8c4b7-0719-4214-9c43-0b0df3e6526c	Murène anneau	MURAENIDAE	Channomuraena	vittata	AMH	MUR/Cha.vit	\N	\N
5e2edbf4-50dc-49f1-8e86-c257c0811ba5	\N	MURAENIDAE	Gymnothorax	vicinus	AMT	MUR/Gym.vic	\N	\N
2f9b27d0-9ef8-4811-99ef-941a7d5e2ab1	Murene obscure	MURAENIDAE	Lycodontis	afer	AWG	MUR/Lyc.afe	\N	\N
4e83909d-1dce-4d03-8ef7-b6938ea66d6d	Murène indéterminée	MURAENIDAE	spp	\N	MUI	MUR	\N	\N
cc519486-7736-441c-9115-21fc93640011	Aigle de mer léopard	MYLIOBATIDAE	Aetobatus	narinari	MAE	RAI/Aet.nar	\N	\N
caee4a64-36eb-4e32-8124-f6411ea9f983	Aigle commun	MYLIOBATIDAE	Myliobatis	aquila	MYL	RAI/Myl.aqu	\N	\N
690c8719-4e6d-43c8-91fd-62b7a2f0a939	Aigle-vachette	MYLIOBATIDAE	Pteromylaeus	bovinus	MPO	RAI/Pte.bov	\N	\N
ca2f62ed-80cb-464c-a71f-c5f6d8d492d6	\N	MYLIOBATIDAE	Myliobatis	spp	MWX	MYL/Myl	\N	\N
4a3c4703-62e4-4bc2-a034-99022fdcb1fa	\N	MYLIOBATIDAE	spp	\N	\N	\N	\N	\N
1b448688-e229-4010-b4a5-3ad112743ee8	Langoustine écarlate	NEPHROPIDAE	Nephropsis	atlantica	NFT	\N	\N	\N
3fd82131-1119-4962-83a1-b5af8c91df54	Nomeidé indéterminé	NOMEIDAE	spp	\N	NMW	\N	\N	\N
a9761b45-a414-4079-9177-b55751e3207b	Poulpe à longs bras	OCTOPODIDAE	Octopus	defilippi	OQD	OCT/Oct.def	\N	\N
92e38efa-83b1-47fe-83af-f92e13a74b90	Pieuvre	OCTOPODIDAE	Octopus	Vulgaris	OCC	OCT/Oct.vul	\N	\N
2a265dbb-0cc1-4402-a2b2-5cc5b4dfaa42	Poulpe indéterminé	OCTOPODIDAE	Octopus	spp	OCZ	OCT	\N	\N
167fccf0-bf22-4bde-b3f7-aa09e0451b13	Encornet indéterminé	OMMASTREPHIDAE	Illex	spp	ILL	\N	\N	\N
75ee2488-bb47-4bbf-835c-92c2bc6a0424	Serpenton léopard	OPHICHTHIDAE	Myrichthys	pardalis	MXU	OPHIC/Myr.par	\N	\N
a428ad95-3639-4787-a6ba-aabd8e5e816c	Ophichthidé indéterminé	OPHICHTHIDAE	spp	\N	OWX	OPH	\N	\N
df9dc035-aabb-4d95-970f-df718546dbe3	Brotule barbée	OPHIDIIDAE	Brotula	barbata	BRD	OPH/Bro.bar	\N	\N
721c2db8-ce07-4b53-99ba-6df7fb16af1b	Ophiure indéterminé	OPHIUROIDEA	spp	\N	OWP	OPI	\N	\N
f59f7477-1dbc-4c99-9897-5a8cdb7a1309	Coffre indéterminé	OSTRACIIDAE	spp	\N	BXF	OST	\N	\N
610bb6cb-bb0c-4ce4-be8c-4268b13827fc	Centrine commune	OXYNOTIDAE	Oxynotus	spp	\N	REQ/Oxy	\N	\N
6d52eb1c-c70e-4f51-8e4f-5d42b6f7a7d3	Centrine commune	OXYNOTIDAE	Oxynotus	centrina	OXY	REQ/Oxy.cen	\N	\N
7efc9891-3523-4629-8688-609324b77180	Langouste blanche	PALINURIDAE	Panulirus	argus	SLC	PAL/Pan.arg	\N	\N
acdbaf40-a8e8-4bcd-9ee9-b5c1454f34a4	Langouste royale	PALINURIDAE	Panulirus	regius	LOY	PAL/Pan.reg	\N	\N
ef35c329-4c6e-444f-b22d-9bd081053681	\N	PALINURIDAE	spp	\N	VLO	PAL	\N	\N
c17062b7-6ef5-4d2c-ba01-dec77c6ea30e	Crevette nylon armée	PANDALIDAE	Heterocarpus	ensifer	HKF	PAN/Her.ens	\N	\N
cb3e5abe-49d9-414b-ab98-19c0e664979d	Crevette dorée	PANDALIDAE	Plesionika	martia	LKT	PAN/Ple.mar	\N	\N
eb54e363-a578-416e-8f88-b216228e6173	Crevette pandalide indéterminée	PANDALIDAE	spp	\N	PAN	PAN	\N	\N
4fdb2c39-f021-4ed1-b0c2-ce7ecabd919b	Smooth flounder (EN)	PARALICHTHYIDAE	Citharichthys	stampflii	IYT	\N	\N	\N
24f5d4a9-0480-441d-81a9-8696ec514453	Crevette rose du large	PENAEIDAE	Parapenaeus	longirostris	DPS	PEN/Par.lon	\N	\N
afecf403-0fde-4e58-ba55-b5d71cf004e3	Crevette tigrée	PENAEIDAE	Penaeus	kerathurus	TGS	PEN/Pen.ker	\N	\N
58b8bdbc-3d59-4258-8154-da592c2059fb	Crevette géante tigrée	PENAEIDAE	Penaeus	monodon	GIT	PEN/Pen.mon	\N	\N
68fb58ba-8785-435f-b95d-4f8008415d8c	Crevette rose du Sud	PENAEIDAE	Penaeus	notialis	SOP	PEN/Pen.not	\N	\N
879bd8be-e98b-4a66-ba6c-4f742b63113e	Crevette pénéidé indéterminée	PENAEIDAE	spp	\N	PEN	PEN	\N	\N
6d291d6c-2157-41d5-8743-8894da980e41	Plathycéphale de Guinée	PLATYCEPHALIDAE	Grammoplites	gruveli	GMU	PLA/Gra.gru	\N	\N
3853b8cb-9543-430a-b5c7-b22d5fe04bba	Guitare bouclée	PLATYRHINIDAE	Zanobatus	schoenleinii	RZS	RAI/Zan.sch	\N	\N
2e510631-92a6-401d-bcbf-e83e7f831232	\N	POECILIIDAE	Aplocheilichthys	spilauchen	\N	\N	\N	\N
efc32697-f64e-4493-9d08-d7a27791b978	Petit capitaine	POLYNEMIDAE	Galeoides	decadactylus	GAL	POL/Gal.dec	crevette	\N
1318e3a8-9898-4bc6-9d59-80b3be03efb5	Capitaine royal	POLYNEMIDAE	Pentanemus	quinquarius	PET	POL/Pen.qui	\N	\N
6f7445e1-1ab8-4e79-862f-2d97989b5463	Gros capitaine	POLYNEMIDAE	Polydactylus	quadrifilis	TGA	POL/Pol.qua	\N	\N
20009f9e-9652-4738-8bc1-20ca3ef43995	Capitaine	POLYNEMIDAE	spp	\N	\N	POL	crevette	\N
7ed2bf0b-64b7-4e18-af4a-0b5d7ba22c15	Crabe marbré	PORTUNIDAE	Callinectes	marginatus	KLG	POR/Cal.mar	\N	\N
94f5226d-19b7-48ad-88d7-3c74f4024454	Crabe gladiateur	PORTUNIDAE	Callinectes	pallidus	KLP	POR/Cal.pal	\N	\N
d142d418-eadf-4175-9d02-695ee2195fb9	Étrille lisse du Sénégal	PORTUNIDAE	Portunus	validus	PVQ	POR/Por.val	\N	\N
1ea46e70-8609-4fbe-a804-420730aa28b4	Crabe indetermine	PORTUNIDAE	spp	\N	SWN	POR	crevette	\N
cb9d89d1-9ecd-47ae-a6b2-334a2d3a3006	Beauclaire de roche	PRIACANTHIDAE	Heteropriacanthus	cruentatus	HTU	\N	\N	\N
c6a3b11d-2c2c-422f-b70b-87247838c5b3	Seaudaire soleil	PRIACANTHIDAE	Priacanthus	arenatus	PQR	PRI/Pri.are	\N	\N
6c94f1ed-d883-4793-9059-c867b95c2d48	\N	PRIACANTHIDAE	spp	\N	\N	PRI	\N	\N
57c1f4e8-4fa2-40f5-b890-4f9cee65f68a	Turbot épineux tacheté	PSETTODIDAE	Psettodes	belcheri	SOT	PSE/Pse.bel	crevette	\N
9effce98-c771-4356-9fce-fcd734828ad4	Cobia	RACHYCENTRIDAE	Rachycentron	canadum	CBA	RAC/Rac.can	\N	\N
d95a147d-12df-4e66-9dd2-772e9322b2de	Raie blanche	RAJIDAE	Raja	alba	RJA	RAI/Raj.alb	\N	\N
eaab48b5-6952-478d-8a58-8bffca681d1d	Raie bouclée	RAJIDAE	Raja	clavata	RJC	RAI/Raj.cla	\N	\N
7e97139b-12c5-4011-9971-660f7b9ca930	Raie violette	RAJIDAE	Raja	doutrei	JFD	RAI/Raj.dou	\N	\N
eaa03507-5140-445d-8af4-e6480a4215b2	Raie léopard	RAJIDAE	Raja	leopardus	JFV	RAI/Raj.leo	\N	\N
f240c008-9682-4a29-9398-b0b071d42a2f	Raie miroir	RAJIDAE	Raja	miraletus	JAI	RAI/Raj.mir	\N	\N
97f4ad09-7747-41c6-ab8d-bc5cfbe1468d	Raie tachetée	RAJIDAE	Raja	straeleni	RFL	RAI/Raj.str	\N	\N
d080cd8c-2f46-4617-9bba-93a85efecbf5	Rajidé indéterminé	RAJIDAE	Rajidae	spp	RAJ	RAI/Raj	\N	\N
7800335d-68e4-4591-b71f-a61b54b5a3b8	Requin baleine	RHINIODONTIDAE	Rhincodon	typus	RHN	REQ/Rhi.typ	\N	\N
3fbd8a11-fc59-4d69-a2e0-ed4ea8a1c730	Guitares nca	RHINOBATIDAE	Rhinobatos	spp	GUZ	RAI/Rhi	\N	\N
efd2fa43-f799-44aa-82b7-6710a545bfa9	Poisson-guitare à lunaires	RHINOBATIDAE	Rhinobatos	albomaculatus	GUB	RAI/Rhi.alb	\N	\N
da409dc3-b3cf-4c14-9a9f-6281be9230db	Raie guitare fouisseuse	RHINOBATIDAE	Rhinobatos	cemiculus	RBC	RAI/Rhi.cem	\N	\N
b9e418c3-5971-498d-94a0-630e7c03eb68	Raie-guitare à dos épineux	RHINOBATIDAE	Rhinobatos	irvinei	RBI	RAI/Rhi.irv	\N	\N
bbd5f2ef-a3d3-403b-9f14-228a0928e237	Poisson-guitaire commun	RHINOBATIDAE	Rhinobatos	rhinobatos	RBX	RAI/Rhi.rhi	\N	\N
fee281a2-7b37-447f-9fac-dcd8b3a28466	Mourine lusitanienne	RHINOPTERIDAE	Rhinoptera	marginata	MRM	RAI/Rhi.mar	\N	\N
f2bfdb95-5308-4745-a20e-406b19e70c6e	Poisson-paille	RHYNCHOBATIDAE	Rhynchobatus	luebberti	RCL	RAI/Rhy.lue	\N	\N
a1394d3e-aa53-40d7-bce5-20b2961049e8	\N	SCARIDAE	Scarus	hoefleri	UVB	\N	\N	\N
1d20e3a9-0671-4d81-955f-66d94f6fbe53	\N	SCARIDAE	Sparisoma	choati	QZV	\N	\N	\N
3b971c4c-9fb4-44aa-93ea-7607d7131cfa	Perroquet indéterminé	SCARIDAE	spp	\N	PWT	SCA	\N	\N
a9d8ad46-7c9a-49da-9e33-9156ee4dd236	Maigre commun	SCIAENIDAE	Argyrosomus	regius	MGR	SCI/Arg.reg	\N	\N
61d1c4d4-bf43-4b6a-bf45-1122a11214b0	Otolithe gabo	SCIAENIDAE	Pseudotolithus	brachygnathus	CKL	SCI/Pse.bra	\N	\N
195ea3bc-1e58-4b80-821b-5da4c3c9e70d	Otolithe bobo	SCIAENIDAE	Pseudotolithus	elongatus	PSE	SCI/Pse.elo	\N	\N
8677b8b9-38ff-4312-b45b-0c66d082a2c8	\N	SCIAENIDAE	Pseudotolithus	epipercus	QCK	SCI/Pse.epi	\N	\N
8027adf5-6b02-4e9f-8d6c-653454d52de9	Otolithe senegalaise	SCIAENIDAE	Pseudotolithus	senegalensis	PSS	SCI/Pse.sen	\N	\N
5b735a5f-2881-4d25-8bd8-361e2ed7212a	\N	SCIAENIDAE	Pseudotolithus	senegallus	CKL	?	\N	\N
9eeb8a0f-2899-42ec-9f55-c6734b431949	Otolithe nanka	SCIAENIDAE	Pseudotolithus	typus	PTY	SCI/Pse.typ	\N	\N
c0828615-cfc9-4bf2-ac19-e3a870cec04b	Bar	SCIAENIDAE	Pseudotolithus	spp	CKW	SCI/Pse	crevette	\N
938692fb-8dc2-4576-9ade-ffdbb0dc292e	Courbine pélin	SCIAENIDAE	Pteroscion	peli	DRS	SCI/Pte.pel	\N	\N
04516609-d5a7-4726-ba46-9de9bd4796d1	Sciaenidé indéterminé	SCIAENIDAE	spp	\N	CDX	SCI	\N	\N
0d753141-f7b1-44b8-a8cd-ae3fd15a80e9	Ombrine bronze	SCIAENIDAE	Umbrina	canariensis	UCA	SCI/Umb.can	\N	\N
6b277914-866f-45f4-b144-927b377f7b8e	Ombrine côtière	SCIAENIDAE	Umbrina	cirrosa	COB	SCI/Umb.cir	\N	\N
8e953e38-6497-4d66-9ce5-50429621877a	Ombrine fusca	SCIAENIDAE	Umbrina	ronchus	UMO	SCI/Umb.rho	\N	\N
cc1ff6d4-75ae-44df-9b86-dd8243843e3a	Ombrine de Steindachner	SCIAENIDAE	Umbrina	steindachneri	UMS	SCI/Umb.ste	\N	\N
47e82cce-e50b-4313-a220-6a8da8f4d437	Wahoo	SCOMBRIDAE	Acanthocybium	solandri	WAH	SCOM/Aca.sol	\N	\N
48c401bb-cbc3-498a-a82c-13f1a28a5a67	Auxide et bonitou	SCOMBRIDAE	Auxis	spp	FRZ	SCOM/Aux	\N	\N
15382118-0c99-4207-9c1c-462591d4b31e	Bonitou	SCOMBRIDAE	Auxis	rochei	BLT	SCOM/Aux.roc	\N	\N
ccd5abd9-cf12-4852-84e0-82a87302a892	Auxide	SCOMBRIDAE	Auxis	thazard	FRI	SCOM/Aux.tha	\N	\N
ae9d8b0a-0d80-423f-bc24-0875dc045d9d	Thonine commune	SCOMBRIDAE	Euthynnus	alletteratus	LTA	SCOM/Eut.all	\N	\N
0960627e-cf1a-4e0c-b4a2-6f977933e770	Bonite à ventre rayé	SCOMBRIDAE	Katsuwonus	pelamis	SKJ	SCOM/Kat.pel	\N	\N
a5218d4a-6a28-4f56-bd46-9154695c2594	Palomette	SCOMBRIDAE	Orcynopsis	unicolor	BOP	SCOM/Orc.uni	\N	\N
e93fc143-72b2-4af6-94f9-5d6658ab8d38	Bonite à dos rayé	SCOMBRIDAE	Sarda	sarda	BON	SCOM/Sar.sar	\N	\N
65edf37c-c7a4-4980-bc05-d1d0bc746b1b	Maquereau esgnole	SCOMBRIDAE	Scomber	colias	\N	\N	\N	\N
9b3d2c43-d326-4228-8db6-43e907936f64	Maquereau espagnol	SCOMBRIDAE	Scomber	japonicus	MAS	SCOM/Scom.jap	\N	\N
97699718-6459-4dc6-a617-84795620eca8	Thazard blanc	SCOMBRIDAE	Scomberomorus	tritor	MAW	SCOM/Scom.tri	\N	\N
8d7a0882-80db-4335-9b1f-c9279e5415ea	Scombridé indéterminé	SCOMBRIDAE	spp	\N	TUN	SCOM	\N	\N
8b30066b-5ad2-4bbc-b90d-135de9de5261	Germon	SCOMBRIDAE	Thunnus	alalunga	ALB	SCOM/Thu.ala	\N	\N
2b141d82-835d-4825-8a2b-96241b2fd452	Albacore	SCOMBRIDAE	Thunnus	albacares	YFT	SCOM/Thu.alb	\N	\N
bce379ac-3ca5-4902-81b4-6f19ae5d829b	Thon obèse	SCOMBRIDAE	Thunnus	obesus	BET	SCOM/Thu.obe	\N	\N
f5b63481-51bf-4c4e-aa70-937fc9a50dad	Rascasse du large	SCORPAENIDAE	Pontinus	kuhlii	POI	SCOR/Pon.kuh	\N	\N
58d5f1a5-5d68-4ff5-9752-b2ea6492a2c7	Rascasse du Sénégal	SCORPAENIDAE	Scorpaena	laevis	SLQ	SCOR/Scor.lae	\N	\N
fba33ae5-6a4a-4e80-974e-ed677c9a3d9f	Rascasse indéterminée	SCORPAENIDAE	spp	\N	SCO	SCOR	\N	\N
f3bad13c-1bf0-4ff4-9909-62bf55fd1a84	Chien râpe	SCYLIORHINIDAE	Galeus	polli	GAQ	REQ/Gal.pol	\N	\N
31fc949e-25d1-40ca-9296-c67d25172162	Grande roussette	SCYLIORHINIDAE	Scyliorhinus	stellaris	SYT	REQ/Scy.ste	\N	\N
5b3c9ca0-da4a-40db-8864-9f5f18415504	\N	SCYLIORHINIDAE	spp	\N	\N	\N	\N	\N
6ca67ce7-7856-4a94-9608-bc048a4439cb	Cigale rouge	SCYLLARIDAE	Scyllarides	herklotsii	YLK	SCY/Scy.her	\N	\N
e0418c6a-6c33-4b5a-bc67-75322b463a25	Roussette thalassa	SCYLORHINIDAE	Scyliorhinus	cervigoni	SYE	REQ/Scy.cer	\N	\N
66f30995-97ea-41c7-8c16-45ec334fb05c	Seiche indéterminée	SEPIIDAE	Sepia	spp	IAX	SEP	\N	\N
cab1ecdf-31fe-401c-9600-c7c5f8d73552	Barbier-hirondelle	SERRANIDAE	Anthias	anthias	AHN	SER/Ant.ant	\N	\N
ffeebe0e-5f95-4e81-84ea-71afe23846cf	Mérou barré	SERRANIDAE	Centrarchops	chapini	ENH	SER/Cen.cha	\N	\N
96d9d951-a859-427e-b989-a5ce6e40d6b9	Mérou du Niger	SERRANIDAE	Cephalopholis	nigri	CFQ	SER/Cep.nig	\N	\N
460b5cc8-8630-4fbc-8776-581177b6844f	Mérou à points bleus	SERRANIDAE	Cephalopholis	taeniops	EFA	SER/Cep.tae	\N	\N
d3ea8fb2-4219-4b99-99c8-42de96fd477a	Mérou blanc	SERRANIDAE	Epinephelus	aeneus	GPW	SER/Epi.aen	\N	\N
e6f8f6e7-476b-48d5-a70d-20406a40d677	Mérou dungat	SERRANIDAE	Epinephelus	goreensis	EEG	SER/Epi.gor	\N	\N
ef01265b-f895-451a-9bf4-605b701546d1	Mérou géant	SERRANIDAE	Epinephelus	itajara	EET	SER/Epi.ita	\N	\N
ee2280e4-4498-49f1-8b81-dff3bb046295	Mérou royal	SERRANIDAE	Mycteroperca	rubra	MKU	SER/Myc.rub	\N	\N
41bc868a-861c-43d1-95a1-2bce1589faf7	Greater soapfish (EN)	SERRANIDAE	Rypticus	saponaceus	RYC	\N	\N	\N
22976e73-6a58-4e1f-a4e3-3c84393245ca	Serran ghanéen	SERRANIDAE	Serranus	accraensis	\N	SER/Ser.acc	\N	\N
ce5f385d-3fa0-4306-b97a-6582f7a8d4ac	Serran chèvre	SERRANIDAE	Serranus	cabrilla	CBR	SER/Ser.cab	\N	\N
f1bdb6fd-1378-4039-80eb-44e8058e628e	Mérou indéterminé	SERRANIDAE	spp	\N	BSX	SER	\N	\N
f6a4555f-9f18-44a4-8649-93bef9d990d8	Boucot méditerranéen	SICYONIDAE	Sicyonia	carinata	YIA	SIC/Sic.car	\N	\N
fed54f17-90c1-40ae-a69d-aa34bb7f9a5e	Sicyonie huppée	SICYONIDAE	Sicyonia	galeata	YIG	SIC/Sic.gal	\N	\N
6ac7154f-7d5b-4b05-8bcb-dc725f80991b	\N	SICYONIDAE	spp	\N	\N	SIC	\N	\N
d530c004-901c-44f2-92ff-2cea570ee2e7	Sole-pole	SOLEIDAE	Pegusa	lascaris	SOS	SOL/Peg.las	\N	\N
d5182f23-ada6-48b0-a526-c04a5d29cfc2	Sole	SOLEIDAE	Pegusa	cadenati	\N	SOL/Peg.cad	crevette	\N
47368d9c-7e57-402f-9e7a-841dfa37ba6b	Sole laiteuse	SOLEIDAE	Bathysolea	lactea	YOL	SOL/Bat.lac	\N	\N
61172c3b-6f6d-4437-8575-d564afa6021d	\N	SOLEIDAE	Dagetichthys	lusitanicus	\N	SOL/Dag.lus	\N	\N
6afcd524-03fd-47a2-8883-050c2d4fc9d7	Céteau	SOLEIDAE	Dicologlossa	cuneata	CET	SOL/Dic.cun	\N	\N
19b4f017-17ab-48b0-a294-2864f68b62cf	Céteau ocellé	SOLEIDAE	Dicologlossa	hexophthalmus	DHZ	SOL/Dic.hex	\N	\N
836c7322-1214-4dcb-853b-747f662e757d	Sole ocellée	SOLEIDAE	Microchirus	ocellatus	MRK	SOL/Mic.oce	\N	\N
689fdac5-09ac-440b-9ca2-ed6062eed5c3	Sole indéterminée	SOLEIDAE	spp	\N	SOX	SOL	\N	\N
ce8c6460-31cd-4d10-9c09-0d37a87681f7	Bogue	SPARIDAE	Boops	boops	BOG	SPA/Boo.boo	\N	\N
baf75b6b-629d-41b4-9b03-e3eb38916a5c	Denté	SPARIDAE	Dentex	spp	DEX	SPA/Den	crevette	\N
e29ab7de-484b-4441-adf1-0e63c9ea4f53	Denté angolais	SPARIDAE	Dentex	angolensis	DEA	SPA/Den.ang	\N	\N
cb93ed7f-8103-4578-b110-6e00630e3637	Denté austral	SPARIDAE	Dentex	barnardi	\N	SPA/Den.bar	\N	\N
e3c340ee-2334-4895-80ce-8a7934d67fd7	Denté à tache rouge	SPARIDAE	Dentex	canariensis	DEN	SPA/Den.can	\N	\N
4eae4fc2-91f6-428c-9aff-f3584d3c67e6	Denté congolais	SPARIDAE	Dentex	congoensis	DNC	SPA/Den.con	\N	\N
2f06dd87-0a32-4855-8f66-3d136d3acdc2	Gros denté rose	SPARIDAE	Dentex	gibbosus	DEP	SPA/Den.gib	\N	\N
307b8f34-1772-47a7-b08a-71dee81f2e33	Denté du Maroc	SPARIDAE	Dentex	macroccanus	DEM	SPA/Den.macroc	\N	\N
cc40ac98-626d-4392-904e-0030e29f5594	Denté à gros yeux	SPARIDAE	Dentex	macrophthalmus	DEL	SPA/Den.macrop	\N	\N
0be80cd4-8b10-4711-9807-181add22472d	Sar a grosses lievres	SPARIDAE	Diplodus	cervinus	SBZ	SPA/Dip.cer	\N	\N
2bdecec8-857c-4426-8fb5-0db60cbb60f7	Marbré	SPARIDAE	Lithognathus	mormyrus	SSB	SPA/Lit.mor	\N	\N
4b52523e-87c2-4037-8ec2-577ac8a2f4b7	Oblade	SPARIDAE	Oblada	melanura	SBS	SPA/Obl.mel	\N	\N
909ce8eb-f4c1-4003-9bab-2935ea22c514	Pageot	SPARIDAE	Pagellus	spp	PAX	SPA/Pag	crevette	\N
59771d05-103b-4de3-9f41-c1164848f1b7	Pageot acarne	SPARIDAE	Pagellus	acarne	SBA	SPA/Pag.aca	\N	\N
087ee2ef-f4b3-42fc-a840-05cf7794ab3a	Pageot à tache rouge	SPARIDAE	Pagellus	bellottii	PAR	SPA/Pag.bel	\N	\N
86a68b78-30fe-4b50-ae11-b1cdedfc24a6	Dorade rose	SPARIDAE	Pagellus	bogaraveo	SBR	SPA/Pag.bog	crevette	\N
7ee8d7c0-b814-4d8a-96cb-22ae369fc953	Pagre des tropiques	SPARIDAE	Pagrus	africanus	RPG	SPA/Pag.afr	\N	\N
4af93fb1-b9db-4f9f-bd2f-111f0bb9ccab	Pagre rayé	SPARIDAE	Pagrus	auriga	REA	SPA/Pag.aur	\N	\N
58c5962e-a964-46c2-beba-236c61939642	Pagre à points bleus	SPARIDAE	Pagrus	caeruleostictus	BSC	SPA/Pag.cae	\N	\N
993db69f-b5c1-4370-8e67-70cba50cc60b	Dorade royale	SPARIDAE	Sparus	aurata	SBG	SPA/Spa.aur	\N	\N
81184739-ee47-4a23-874f-f5e1942b5a32	Saupe	SPARIDAE	Sarpa	salpa	SLM	SPA/Sar.sal	\N	\N
81afa80b-e0f5-4d84-92db-4be342dca0b0	Dorade grise	SPARIDAE	Spondyliosoma	cantharus	BRB	SPA/Spo.can	\N	\N
d88fb534-7e4c-4ac3-b440-bb113ab8579f	Sparidé indéterminé	SPARIDAE	spp	\N	SBX	SPA	\N	\N
f5804953-e8a6-4dfd-a0e4-aae1641de7c0	Bécune guinéenne	SPHYRAENIDAE	Sphyraena	afra	BAG	SPH/Sph.afr	\N	\N
2353c045-9af2-4398-a185-45859ce4bae2	Barracuda	SPHYRAENIDAE	Sphyraena	barracuda	GBA	SPH/Sph.bar	\N	\N
90cbe93a-d273-4cd3-a6a6-3b988165f73c	Guachanche barracuda (EN)	SPHYRAENIDAE	Sphyraena	guachancho	YRU	SPH/Sph.gua	crevette	\N
df7f8997-ca6d-48f8-82c6-02bb44ce1630	Becune europeenne	SPHYRAENIDAE	Sphyraena	sphyraena	YRS	SPH/Sph.sph	\N	\N
13ec0715-fd4d-4474-9058-5e8378bf7eea	Bécune indéterminée	SPHYRAENIDAE	spp	\N	BAZ	SPH	\N	\N
0d6827a4-f294-4046-84c5-21c18a19c490	Requin-marteau halicorne	SPHYRNIDAE	Sphyrna	lewini	SPL	SPN/Sph.lew	\N	\N
38836180-32de-492f-8608-8b0247ea8a9b	Grand requin marteau	SPHYRNIDAE	Sphyrna	mokarran	SPK	SPN/Sph.mok	\N	\N
cb7ee321-6645-49df-a9a1-87f3d4d98dba	Requin-marteau commun	SPHYRNIDAE	Sphyrna	zygaena	SPZ	SPN/Sph.zyg	\N	\N
67c0a69d-edda-42c5-9d59-36c8b6fff519	Requin-marteau commun	SPHYRNIDAE	spp	\N	SPN	SPN	\N	\N
5004024c-7db7-4854-8160-cf768147d714	Requin-marteau commun	SPHYRNIDAE	Sphyrna	spp	SPN	SPN/Sph	\N	\N
0bbe8992-bf1d-42cd-a089-7f0d86f6ce09	\N	SQUALIDAE	spp	\N	\N	SQL	\N	\N
7d50b5fe-f629-4359-957e-2bd170b58fdc	Aiguillat	SQUALIDAE	Squalus	spp	DGZ	SQL/Squ	\N	\N
334cc389-f278-4474-a757-5facebbd9473	Aiguillat coq	SQUALIDAE	Squalus	blainvillei	QUB	SQL/Squ.bla	\N	\N
6e60f48c-e692-4f3b-b128-b7f7c6fb71f7	Aiguillat à gros yeux	SQUALIDAE	Squalus	megalops	DOP	SQL/Squ.meg	\N	\N
d13dfde9-c170-4f56-803c-e7d6cecbca2b	\N	SQUATINIDAE	spp	\N	\N	SQT	\N	\N
7cf614dd-1a19-4746-b0ec-2d00ac9fe70d	Ange de mer épineux	SQUATINIDAE	Squatina	aculeata	SUA	SQT/Squ.acu	\N	\N
f8bab2f5-149d-4a3d-80c6-4443003fc393	Ange de mer ocellé	SQUATINIDAE	Squatina	oculata	SUT	SQT/Squ.ocu	\N	\N
e791072e-d5d0-45c4-a6d6-22adb7caf605	Ange de mer indéterminé	SQUATINIDAE	Squatina	spp	ASK	SQT/Squ	\N	\N
3d8ee9c0-7f50-4177-b644-abd87174add7	\N	SQUILLIDAE	Squilla	spp	\N	SQU	\N	\N
480c5308-4c1b-42ed-8610-83a04453a2be	Squille ocellée	SQUILLIDAE	Squilla	mantis	MTS	SQU/Squ.man	\N	\N
f38274c7-4129-47c9-ab3e-dbc522bbcce3	Fiatole	STROMATEIDAE	Stromateus	fiatola	BLB	STR/Str.fia	\N	\N
005db325-cabc-4a06-9d98-455217b69d43	Synodontidé indéterminé	SYNODONTIDAE	spp	\N	LIX	SYO	\N	\N
d4d331b0-f79e-437a-8d09-6c892d4c63c6	\N	SYNODONTIDAE	Saurida	spp	SZX	SYO/Sau	\N	\N
32c74d00-245b-4686-b036-7aa5d9f89bcf	Lagarto ñato	SYNODONTIDAE	Trachinocephalus	myops	TCY	SYO/Tra.myo	\N	\N
b8727205-ecd1-47c3-b204-977d2f5ba2de	Hippocampe indéterminé	SYNGNATHIDAE	spp	\N	SWY	SYG	\N	\N
5168827c-4a38-4de9-811a-872fc25edad5	Compère à points blancs	TETRAODONTIDAE	Ephippion	guttifer	EFG	TET/Eph.gut	\N	\N
17d3eb19-2e08-4a4c-bd2e-9ec2601b0fb5	Compère lisse	TETRAODONTIDAE	Lagocephalus	laevigatus	LFL	TET/Lag.lae	\N	\N
9c2fbb00-2a00-4870-997c-9e76831b58d6	Compère lièvre	TETRAODONTIDAE	Lagocephalus	lagocephalus	LGH	TET/Lag.lag	\N	\N
f7e2b083-1d0f-44df-a611-1a91ecb3e52f	Compère émoussé	TETRAODONTIDAE	Sphoeroides	pachygaster	TSP	TET/Sph.pac	\N	\N
ba44fe2d-db2e-40eb-9581-55372af44797	Compère indéterminé	TETRAODONTIDAE	spp	\N	PUX	TET	\N	\N
e40eb18e-72d7-4e07-9e85-1de6e1a29492	Raie éléctrique indéterminée	TORPEDINIDAE	ssp	\N	TOD	RAI/Tor	\N	\N
61b74307-f0bc-4584-b293-b7eb509a3770	Vive indéterminée	TRACHINIDAE	spp	\N	TRA	TRA	\N	\N
7211c8d3-aea6-439f-8bcd-a4e9455140c2	Vive guinéenne	TRACHINIDAE	Trachinus	armatus	WVW	TRA/Tra.arm	\N	\N
fb0ecf57-516e-498f-8141-ab5219cd9d83	Vive du Cap Vert	TRACHINIDAE	Trachinus	pellegrini	WWB	TRA/Tra.pel	\N	\N
30aac938-5524-4903-8d45-79a993a53f1b	Vive rayée	TRACHINIDAE	Trachinus	lineolatus	WWA	TRA/Tra.lin	\N	\N
d4637911-4d04-42c4-8c85-0c42198e2f48	Emissole lisse	TRIAKIDAE	Mustelus	mustelus	SMD	TRI/Mus.mus	\N	\N
a1ee659c-3072-4136-9208-1a5b5c71f414	Sabre indéterminé	TRICHIURIDAE	spp	\N	CUT	TRC	\N	\N
338939ac-9a8a-45ea-8512-78dcb9452812	Ceinture	TRICHIURIDAE	Trichiurus	lepturus	LHT	TRC/Trc.lep	\N	\N
4c0a487d-ebe7-4918-b687-3e5277a34394	Sabre fleuret	TRICHIURIDAE	Benthodesmus	tenuis	\N	TRC/Ben.ten	\N	\N
efacf1b8-f5c4-4351-ae1d-1e16d8e4296d	Grondin du Cap	TRIGLIDAE	Chelidonichthys	capensis	GUC	TRG/Che.cap	\N	\N
664e52f6-6e70-449d-b659-ffe3cab02f4d	Grondin du Gabon	TRIGLIDAE	Chelidonichthys	gabonensis	CGY	TRG/Che.gab	\N	\N
b172ff8a-2543-4158-a46c-1d1eb414bb08	Grondin camard	TRIGLIDAE	Chelidonichthys	lastoviza	CTZ	TRG/Che.las	\N	\N
4c483c36-d111-49e3-965c-bfc075b82cbc	Grondin indéterminé	TRIGLIDAE	spp	\N	GUX	TRG	\N	\N
ba444632-6e4a-4b22-9728-9732e9330454	Grondin lyre	TRIGLIDAE	Trigla	lyra	GUN	TRG/Trg.lyr	\N	\N
67fc862c-48eb-42e1-ae66-423f2099154d	Uranoscope a points blancs	URANOSCOPIDAE	Uranoscopus	polli	\N	URA/Ura.pol	\N	\N
3781db54-582d-4f49-a973-33c05c4634a1	Uranoscope indéterminé	URANOSCOPIDAE	spp	\N	URA	URA	\N	\N
3929dc6f-8e34-4981-b6ab-7d6880942210	Volute trompe de cochon	VOLUTIDAE	Cymbium	cymbium	YBC	\N	\N	\N
7358579c-29fb-4e83-97c8-085e7f5a6c35	Espadon	XIPHIIDAE	Xiphias	gladius	SWO	XIP/Xip.gla	\N	\N
e46f792b-6ee0-4a03-8138-009d7419350e	Saint Pierre indéterminé	ZEIDAE	spp	\N	ZEX	ZEI	\N	\N
d2958bf9-da28-4ce7-a899-81ec31087ed1	Saint Pierre argenté	ZEIDAE	Zenopsis	conchifer	JOS	ZEI/Zen.con	\N	\N
647102ea-e04a-4f3c-b837-2e41a982bb15	Saint Pierre	ZEIDAE	Zeus	faber	JOD	ZEI/Zen.fab	\N	\N
39af9815-93f2-48e7-9ba5-7244f990e5b8	Baleine	BALAENOPTERIDAE	spp	\N	\N	Baleine	\N	\N
78479964-e146-42ad-958b-389a756e9e7a	Baleine a bosse	BALAENOPTERIDAE	Megaptera	novaeangliae	HUW	Baleine/Meg.nov	\N	\N
c59f5974-e6c9-40aa-9244-bcd99c89e402	Pastenague violette	DASYATIDAE	Pteroplatytrygon	violacea	\N	RAI/Pte.vio	\N	\N
8a811dbc-e23e-4021-a91a-57cf58cd5f6a	Natice indéterminé	GASTROPODA	Natica	spp	ESC	ESC	\N	\N
1fc2ed76-ad1a-4320-83be-90563b44c1ad	Anguilles spaghetti	MORINGUIDAE	Moringua	edwardsi	AMM	MOR	\N	\N
59396f2a-fdce-460a-b7fa-69fcfd96d4f3	Paromola	HOMOLIDAE	Paromola	cuvieri	OLV	HOM/Par.cuv	\N	\N
7fbf9ca2-b950-49a6-81d9-9ce2358b40b3	Dauphin commun	DELPHINIDAE	Delphinus	delphis	DCO	DEL/Del.del	\N	\N
80da641e-06e2-4fb1-926e-112519548e01	Dauphin commun a bec large	DELPHINIDAE	Delphinus	capensis	DCZ	DEL/Del.cap	\N	\N
b02879bc-4803-4a90-8db9-865c34f3d106	Poisson melange	_AUTRE	\N	Poisson generic	PPP	P	crevette	\N
5f3d9bc8-ef89-4e49-8a1d-ee57d99475a0	Nouveau Poisson	_AUTRE	\N	Nouveau poisson	\N	POI	\N	\N
c1c0ad30-bc7c-4c9d-bbc7-e87a5ee21e0a	Boue	_AUTRE	\N	Boue	\N	XBOU	\N	\N
1d434efc-2bfb-4157-86cf-3e79db2800fa	Bois	_AUTRE	\N	Bois	\N	XBOI	\N	\N
24abd3dd-3230-4a5e-84c6-186967217ca0	Cailloux	_AUTRE	\N	Cailloux	\N	XCAI	\N	\N
9b8972dd-0828-41c6-a7fc-93a253e40fdb	Déchets	_AUTRE	\N	Dechets	\N	XDEC	\N	\N
\.


--
-- Data for Name: infraction; Type: TABLE DATA; Schema: infraction; Owner: postgres
--

COPY infraction.infraction (id, datetime, username, id_pv, date_i, t_org, name_org, id_pirogue, pir_name, immatriculation, id_owner, owner_first, owner_last, owner_idcard, owner_t_card, owner_t_nationality, owner_telephone, id_fisherman_1, fish_first_1, fish_last_1, fish_idcard_1, fish_t_card_1, fish_t_nationality_1, fish_telephone_1, id_fisherman_2, fish_first_2, fish_last_2, fish_idcard_2, fish_t_card_2, fish_t_nationality_2, fish_telephone_2, id_fisherman_3, fish_first_3, fish_last_3, fish_idcard_3, fish_t_card_3, fish_t_nationality_3, fish_telephone_3, id_fisherman_4, fish_first_4, fish_last_4, fish_idcard_4, fish_t_card_4, fish_t_nationality_4, fish_telephone_4, pir_conf, eng_conf, net_conf, doc_conf, other_conf, amount, payment, n_dep, n_cdc, n_lib, comments, location, settled, owner_ycard, fish_ycard_1, fish_ycard_2, fish_ycard_3, fish_ycard_4) FROM stdin;
\.


--
-- Data for Name: infractions; Type: TABLE DATA; Schema: infraction; Owner: postgres
--

COPY infraction.infractions (id, datetime, username, t_infraction, id_infraction) FROM stdin;
\.


--
-- Data for Name: t_infraction; Type: TABLE DATA; Schema: infraction; Owner: postgres
--

COPY infraction.t_infraction (id, infraction) FROM stdin;
\.


--
-- Data for Name: t_org; Type: TABLE DATA; Schema: infraction; Owner: postgres
--

COPY infraction.t_org (id, org, active) FROM stdin;
\.


--
-- Data for Name: capture; Type: TABLE DATA; Schema: poisson; Owner: postgres
--

COPY poisson.capture (id, datetime, username, id_maree, id_species, poids, t_taille) FROM stdin;
\.


--
-- Data for Name: maree; Type: TABLE DATA; Schema: poisson; Owner: postgres
--

COPY poisson.maree (id, datetime, username, id_navire, date_d, date_r, captain, nlance, port_d, port_r, t_zone, rejets) FROM stdin;
\.


--
-- Data for Name: t_taille; Type: TABLE DATA; Schema: poisson; Owner: postgres
--

COPY poisson.t_taille (id, taille) FROM stdin;
\.


--
-- Data for Name: t_zone; Type: TABLE DATA; Schema: poisson; Owner: postgres
--

COPY poisson.t_zone (id, zone) FROM stdin;
\.


--
-- Data for Name: -a; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."-a" (gid, name, descriptio, geom) FROM stdin;
\.


--
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.spatial_ref_sys (srid, auth_name, auth_srid, srtext, proj4text) FROM stdin;
\.


--
-- Data for Name: objet; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.objet (id, datetime, username, maree, t_zee, n_objet, id_route, n_route, l_route, t_objet, type_balise, code_balise, t_operation, t_appartenance, t_devenir, remarque) FROM stdin;
\.


--
-- Data for Name: prise_access; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.prise_access (id, datetime, username, maree, n_calee, t_type, t_zee, id_route, n_route, l_route, h_d, h_c, h_f, vitesse, direction, d_max, t_prise, id_species, t_action, t_raison, poids, n_ind, taille, photo, remarque) FROM stdin;
\.


--
-- Data for Name: prise_access_taille; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.prise_access_taille (id, datetime, username, maree, n_cale, id_route, n_route, l_route, id_species, t_measure, taille, poids, t_sexe, t_capture, t_relache, photo, remarque) FROM stdin;
\.


--
-- Data for Name: route; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.route (id, datetime, username, id_navire, maree, date, n_route, l_route, "time", speed, t_activite, t_neighbours, temperature, windspeed, t_detection, t_systeme, comment, location) FROM stdin;
\.


--
-- Data for Name: t_action; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_action (id, action) FROM stdin;
\.


--
-- Data for Name: t_activite; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_activite (id, activite) FROM stdin;
\.


--
-- Data for Name: t_appartenance; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_appartenance (id, appartenance) FROM stdin;
\.


--
-- Data for Name: t_capture; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_capture (id, capture) FROM stdin;
\.


--
-- Data for Name: t_categorie; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_categorie (id, categorie) FROM stdin;
\.


--
-- Data for Name: t_detection; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_detection (id, detection) FROM stdin;
\.


--
-- Data for Name: t_devenir; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_devenir (id, devenir) FROM stdin;
\.


--
-- Data for Name: t_espece; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_espece (id, espece) FROM stdin;
\.


--
-- Data for Name: t_measure; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_measure (id, measure) FROM stdin;
\.


--
-- Data for Name: t_neighbours; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_neighbours (id, neighbours) FROM stdin;
\.


--
-- Data for Name: t_objet; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_objet (id, objet) FROM stdin;
\.


--
-- Data for Name: t_operation; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_operation (id, operation) FROM stdin;
\.


--
-- Data for Name: t_peche; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_peche (id, peche) FROM stdin;
\.


--
-- Data for Name: t_prise; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_prise (id, prise) FROM stdin;
\.


--
-- Data for Name: t_raison; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_raison (id, raison) FROM stdin;
\.


--
-- Data for Name: t_relache; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_relache (id, relache) FROM stdin;
\.


--
-- Data for Name: t_sexe; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_sexe (id, sexe) FROM stdin;
\.


--
-- Data for Name: t_systeme; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_systeme (id, systeme) FROM stdin;
\.


--
-- Data for Name: t_type; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_type (id, type) FROM stdin;
\.


--
-- Data for Name: t_zee; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.t_zee (id, zee) FROM stdin;
\.


--
-- Data for Name: thon_rejete; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.thon_rejete (id, datetime, username, maree, t_zee, n_calee, t_type, id_route, n_route, l_route, h_d, h_c, h_f, vitesse, direction, d_max, id_species, t_categorie, t_raison, poids, monte, photo, remarque) FROM stdin;
\.


--
-- Data for Name: thon_rejete_taille; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.thon_rejete_taille (id, datetime, username, maree, id_species, n_calee, n_route, l_route, id_route, c009, c010, c011, c012, c013, c014, c015, c016, c017, c018, c019, c020, c021, c022, c023, c024, c025, c026, c027, c028, c029, c030, c031, c032, c033, c034, c035, c036, c037, c038, c039, c040, c041, c042, c043, c044, c045, c046, c047, c048, c049, c050, c051, c052, c053, c054, c055, c056, c057, c058, c059, c060, c061, c062, c063, c064, c065, c066, c067, c068, c069, c070, c071, c072, c073, c074, c075, c076, c077, c078, c079, c080, c081, c082, c083, c084, c085, c086, c087, c088, c089, c090, c091, c092, c093, c094, c095, c096, c097, c098, c099, c100, c110, c111, c112, c135, c138, c139, c140, c144, c145, c146, c147, c148, c149, c150, c151, c154, c155, c156, c157, c158, c159, c160, c170) FROM stdin;
\.


--
-- Data for Name: thon_retenue; Type: TABLE DATA; Schema: seiners; Owner: postgres
--

COPY seiners.thon_retenue (id, datetime, username, maree, t_zee, n_calee, t_type, id_route, n_route, l_route, h_d, h_c, h_f, vitesse, direction, d_max, sonar, raison, id_species, t_categorie, poids, cuve, remarque) FROM stdin;
\.


--
-- Data for Name: mpa; Type: TABLE DATA; Schema: shapefiles; Owner: postgres
--

COPY shapefiles.mpa (gid, type, code, nom_apa, superf_km2, pechecoutu, pecheartis, pecheindus, pechesport, geom) FROM stdin;
\.


--
-- Data for Name: mpa_buffer; Type: TABLE DATA; Schema: shapefiles; Owner: postgres
--

COPY shapefiles.mpa_buffer (gid, name, nomcomplet, superf_km2, geom) FROM stdin;
\.


--
-- Data for Name: captures; Type: TABLE DATA; Schema: thon; Owner: postgres
--

COPY thon.captures (id, datetime, username, id_lance, rejete, id_species, taille, poids) FROM stdin;
\.


--
-- Data for Name: entreesortie; Type: TABLE DATA; Schema: thon; Owner: postgres
--

COPY thon.entreesortie (id, datetime, username, id_navire, eez, date_e, heure_e, entree, yft, bet, skj, fri, remarques, location) FROM stdin;
\.


--
-- Data for Name: lance; Type: TABLE DATA; Schema: thon; Owner: postgres
--

COPY thon.lance (id, datetime, username, id_navire, date_c, heure_c, eez, success, banclibre, balise_id, water_temp, wind_speed, wind_dir, cur_speed, comment, location) FROM stdin;
\.


--
-- Data for Name: topology; Type: TABLE DATA; Schema: topology; Owner: postgres
--

COPY topology.topology (id, name, srid, "precision", hasz) FROM stdin;
\.


--
-- Data for Name: layer; Type: TABLE DATA; Schema: topology; Owner: postgres
--

COPY topology.layer (topology_id, layer_id, schema_name, table_name, feature_column, feature_type, level, child_id) FROM stdin;
\.


--
-- Data for Name: captures; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.captures (id, datetime, username, id_route, maree, lance, id_species, poids, comment, n_ind) FROM stdin;
\.


--
-- Data for Name: captures_mammal; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.captures_mammal (id, datetime, username, id_route, maree, date, "time", lance, id_species, n_ind, t_sex, taille, t_capture, t_relache, preleve, camera, photo, remarque, poids) FROM stdin;
\.


--
-- Data for Name: captures_requin; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.captures_requin (id, datetime, username, id_route, maree, date, "time", lance, id_species, n_ind, t_sex, taille, poids, t_capture, t_relache, preleve, camera, photo, remarque) FROM stdin;
\.


--
-- Data for Name: captures_tortue; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.captures_tortue (id, datetime, username, id_route, maree, date, "time", id_species, n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque) FROM stdin;
\.


--
-- Data for Name: cm_cre; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.cm_cre (id, datetime, username, id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm4_cre, cm5_cre, cm6_cre, cm7_cre, cm8_cre, cm9_cre, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre, cm71_cre, cm72_cre, cm73_cre, cm74_cre, cm75_cre, cm76_cre, cm77_cre, cm78_cre, cm79_cre, cm80_cre, cm81_cre, cm82_cre, cm83_cre, cm84_cre, cm85_cre) FROM stdin;
\.


--
-- Data for Name: cm_poi; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.cm_poi (id, datetime, username, id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm2_poi, cm3_poi, cm4_poi, cm5_poi, cm6_poi, cm7_poi, cm8_poi, cm9_poi, cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi, cm71_poi, cm72_poi, cm73_poi, cm74_poi, cm75_poi, cm76_poi, cm77_poi, cm78_poi, cm79_poi, cm80_poi, cm81_poi, cm82_poi, cm83_poi, cm84_poi, cm85_poi, cm86_poi, cm87_poi, cm88_poi, cm89_poi, cm90_poi, cm91_poi, cm92_poi, cm93_poi, cm94_poi, cm95_poi, cm96_poi, cm97_poi, cm98_poi, cm99_poi, cm100_poi, cm101_poi, cm102_poi, cm103_poi, cm104_poi, cm105_poi, cm106_poi, cm107_poi, cm108_poi, cm109_poi, cm110_poi) FROM stdin;
\.


--
-- Data for Name: ft_cre; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.ft_cre (id, datetime, username, id_route, maree, lance, id_species, t_sex, t_maturity, poids, ft1_cre, ft2_cre, ft3_cre, ft4_cre, ft5_cre, ft6_cre, ft7_cre, ft8_cre, ft9_cre, ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, ft70_cre, ft71_cre, ft72_cre, ft73_cre, ft74_cre, ft75_cre, ft76_cre, ft77_cre, ft78_cre, ft79_cre, ft80_cre, ft81_cre, ft82_cre, ft83_cre, ft84_cre, ft85_cre, ft86_cre, ft87_cre, ft88_cre, ft89_cre, ft90_cre, ft91_cre, ft92_cre, ft93_cre, ft94_cre, ft95_cre, ft96_cre, ft97_cre, ft98_cre, ft99_cre, ft100_cre) FROM stdin;
\.


--
-- Data for Name: ft_poi; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.ft_poi (id, datetime, username, id_route, maree, lance, t_rejete, id_species, t_measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi) FROM stdin;
\.


--
-- Data for Name: p_day; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.p_day (id, datetime, username, maree, id_navire, date_d, lance_d, lance_f, id_species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi, comment) FROM stdin;
\.


--
-- Data for Name: p_lance; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.p_lance (id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi, comment) FROM stdin;
\.


--
-- Data for Name: poids_taille; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.poids_taille (id, datetime, username, maree, id_species, t_measure, taille, p1, p2, p3, p4, p5) FROM stdin;
\.


--
-- Data for Name: route; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.route (id, datetime, username, id_navire, maree, t_fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment, location_d, location_f) FROM stdin;
\.


--
-- Data for Name: route_accidentelle; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.route_accidentelle (id, datetime, username, t_fleet, id_navire, maree, date, "time", t_co, lance, location) FROM stdin;
\.


--
-- Data for Name: t_co; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_co (id, co) FROM stdin;
\.


--
-- Data for Name: t_condition; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_condition (id, condition) FROM stdin;
\.


--
-- Data for Name: t_fleet; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_fleet (id, fleet) FROM stdin;
\.


--
-- Data for Name: t_maturity; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_maturity (id, maturity) FROM stdin;
\.


--
-- Data for Name: t_measure; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_measure (id, measure) FROM stdin;
\.


--
-- Data for Name: t_project; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_project (id, project) FROM stdin;
\.


--
-- Data for Name: t_rejete; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_rejete (id, rejete) FROM stdin;
\.


--
-- Data for Name: t_ring; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_ring (id, ring) FROM stdin;
\.


--
-- Data for Name: t_role; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_role (id, role) FROM stdin;
\.


--
-- Data for Name: t_sex; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_sex (id, sex) FROM stdin;
\.


--
-- Data for Name: t_taille_cre; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_taille_cre (id, taille_cre) FROM stdin;
\.


--
-- Data for Name: t_taille_poi; Type: TABLE DATA; Schema: trawlers; Owner: postgres
--

COPY trawlers.t_taille_poi (id, taille_poi) FROM stdin;
\.


--
-- Data for Name: captures; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.captures (id, datetime, username, id_route, maree, lance, id_species, poids, comment, n_ind) FROM stdin;
\.


--
-- Data for Name: captures_mammal; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.captures_mammal (id, datetime, username, id_route, maree, date, "time", lance, id_species, n_ind, t_sex, taille, t_capture, t_relache, preleve, camera, photo, remarque, poids) FROM stdin;
\.


--
-- Data for Name: captures_requin; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.captures_requin (id, datetime, username, id_route, maree, date, "time", lance, id_species, n_ind, t_sex, taille, poids, t_capture, t_relache, preleve, camera, photo, remarque) FROM stdin;
\.


--
-- Data for Name: captures_tortue; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.captures_tortue (id, datetime, username, id_route, maree, date, "time", id_species, n_ind, t_sex, length, width, ring, position_1, code_1, position_2, code_2, t_capture, t_relache, resumation, resumation_res, preleve, camera, photo, remarque) FROM stdin;
\.


--
-- Data for Name: cm_cre; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.cm_cre (id, datetime, username, id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm4_cre, cm5_cre, cm6_cre, cm7_cre, cm8_cre, cm9_cre, cm10_cre, cm11_cre, cm12_cre, cm13_cre, cm14_cre, cm15_cre, cm16_cre, cm17_cre, cm18_cre, cm19_cre, cm20_cre, cm21_cre, cm22_cre, cm23_cre, cm24_cre, cm25_cre, cm26_cre, cm27_cre, cm28_cre, cm29_cre, cm30_cre, cm31_cre, cm32_cre, cm33_cre, cm34_cre, cm35_cre, cm36_cre, cm37_cre, cm38_cre, cm39_cre, cm40_cre, cm41_cre, cm42_cre, cm43_cre, cm44_cre, cm45_cre, cm46_cre, cm47_cre, cm48_cre, cm49_cre, cm50_cre, cm51_cre, cm52_cre, cm53_cre, cm54_cre, cm55_cre, cm56_cre, cm57_cre, cm58_cre, cm59_cre, cm60_cre, cm61_cre, cm62_cre, cm63_cre, cm64_cre, cm65_cre, cm66_cre, cm67_cre, cm68_cre, cm69_cre, cm70_cre, cm71_cre, cm72_cre, cm73_cre, cm74_cre, cm75_cre, cm76_cre, cm77_cre, cm78_cre, cm79_cre, cm80_cre, cm81_cre, cm82_cre, cm83_cre, cm84_cre, cm85_cre) FROM stdin;
\.


--
-- Data for Name: cm_poi; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.cm_poi (id, datetime, username, id_route, maree, lance, id_species, t_taille_poi, t_taille_cre, poids, cm2_poi, cm3_poi, cm4_poi, cm5_poi, cm6_poi, cm7_poi, cm8_poi, cm9_poi, cm10_poi, cm11_poi, cm12_poi, cm13_poi, cm14_poi, cm15_poi, cm16_poi, cm17_poi, cm18_poi, cm19_poi, cm20_poi, cm21_poi, cm22_poi, cm23_poi, cm24_poi, cm25_poi, cm26_poi, cm27_poi, cm28_poi, cm29_poi, cm30_poi, cm31_poi, cm32_poi, cm33_poi, cm34_poi, cm35_poi, cm36_poi, cm37_poi, cm38_poi, cm39_poi, cm40_poi, cm41_poi, cm42_poi, cm43_poi, cm44_poi, cm45_poi, cm46_poi, cm47_poi, cm48_poi, cm49_poi, cm50_poi, cm51_poi, cm52_poi, cm53_poi, cm54_poi, cm55_poi, cm56_poi, cm57_poi, cm58_poi, cm59_poi, cm60_poi, cm61_poi, cm62_poi, cm63_poi, cm64_poi, cm65_poi, cm66_poi, cm67_poi, cm68_poi, cm69_poi, cm70_poi, cm71_poi, cm72_poi, cm73_poi, cm74_poi, cm75_poi, cm76_poi, cm77_poi, cm78_poi, cm79_poi, cm80_poi, cm81_poi, cm82_poi, cm83_poi, cm84_poi, cm85_poi, cm86_poi, cm87_poi, cm88_poi, cm89_poi, cm90_poi, cm91_poi, cm92_poi, cm93_poi, cm94_poi, cm95_poi, cm96_poi, cm97_poi, cm98_poi, cm99_poi, cm100_poi, cm101_poi, cm102_poi, cm103_poi, cm104_poi, cm105_poi, cm106_poi, cm107_poi, cm108_poi, cm109_poi, cm110_poi) FROM stdin;
\.


--
-- Data for Name: ft_cre; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.ft_cre (id, datetime, username, id_route, maree, lance, id_species, t_sex, t_maturity, poids, ft1_cre, ft2_cre, ft3_cre, ft4_cre, ft5_cre, ft6_cre, ft7_cre, ft8_cre, ft9_cre, ft10_cre, ft11_cre, ft12_cre, ft13_cre, ft14_cre, ft15_cre, ft16_cre, ft17_cre, ft18_cre, ft19_cre, ft20_cre, ft21_cre, ft22_cre, ft23_cre, ft24_cre, ft25_cre, ft26_cre, ft27_cre, ft28_cre, ft29_cre, ft30_cre, ft31_cre, ft32_cre, ft33_cre, ft34_cre, ft35_cre, ft36_cre, ft37_cre, ft38_cre, ft39_cre, ft40_cre, ft41_cre, ft42_cre, ft43_cre, ft44_cre, ft45_cre, ft46_cre, ft47_cre, ft48_cre, ft49_cre, ft50_cre, ft51_cre, ft52_cre, ft53_cre, ft54_cre, ft55_cre, ft56_cre, ft57_cre, ft58_cre, ft59_cre, ft60_cre, ft61_cre, ft62_cre, ft63_cre, ft64_cre, ft65_cre, ft66_cre, ft67_cre, ft68_cre, ft69_cre, ft70_cre, ft71_cre, ft72_cre, ft73_cre, ft74_cre, ft75_cre, ft76_cre, ft77_cre, ft78_cre, ft79_cre, ft80_cre, ft81_cre, ft82_cre, ft83_cre, ft84_cre, ft85_cre, ft86_cre, ft87_cre, ft88_cre, ft89_cre, ft90_cre, ft91_cre, ft92_cre, ft93_cre, ft94_cre, ft95_cre, ft96_cre, ft97_cre, ft98_cre, ft99_cre, ft100_cre) FROM stdin;
\.


--
-- Data for Name: ft_poi; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.ft_poi (id, datetime, username, id_route, maree, lance, t_rejete, id_species, t_measure, poids, ft1_poi, ft2_poi, ft3_poi, ft4_poi, ft5_poi, ft6_poi, ft7_poi, ft8_poi, ft9_poi, ft10_poi, ft11_poi, ft12_poi, ft13_poi, ft14_poi, ft15_poi, ft16_poi, ft17_poi, ft18_poi, ft19_poi, ft20_poi, ft21_poi, ft22_poi, ft23_poi, ft24_poi, ft25_poi, ft26_poi, ft27_poi, ft28_poi, ft29_poi, ft30_poi, ft31_poi, ft32_poi, ft33_poi, ft34_poi, ft35_poi, ft36_poi, ft37_poi, ft38_poi, ft39_poi, ft40_poi, ft41_poi, ft42_poi, ft43_poi, ft44_poi, ft45_poi, ft46_poi, ft47_poi, ft48_poi, ft49_poi, ft50_poi, ft51_poi, ft52_poi, ft53_poi, ft54_poi, ft55_poi, ft56_poi, ft57_poi, ft58_poi, ft59_poi, ft60_poi, ft61_poi, ft62_poi, ft63_poi, ft64_poi, ft65_poi, ft66_poi, ft67_poi, ft68_poi, ft69_poi, ft70_poi, ft71_poi, ft72_poi, ft73_poi, ft74_poi, ft75_poi, ft76_poi, ft77_poi, ft78_poi, ft79_poi, ft80_poi, ft81_poi, ft82_poi, ft83_poi, ft84_poi, ft85_poi, ft86_poi, ft87_poi, ft88_poi, ft89_poi, ft90_poi, ft91_poi, ft92_poi, ft93_poi, ft94_poi, ft95_poi, ft96_poi, ft97_poi, ft98_poi, ft99_poi, ft100_poi, ft101_poi, ft102_poi, ft103_poi, ft104_poi, ft105_poi, ft106_poi, ft107_poi, ft108_poi, ft109_poi, ft110_poi, ft111_poi, ft112_poi) FROM stdin;
\.


--
-- Data for Name: p_day; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.p_day (id, datetime, username, maree, id_navire, date_d, lance_d, lance_f, id_species, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi, comment) FROM stdin;
\.


--
-- Data for Name: p_lance; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.p_lance (id, datetime, username, id_route, id_species, maree, lance, c0_cre, c1_cre, c2_cre, c3_cre, c4_cre, c5_cre, c6_cre, c7_cre, c8_cre, c9_cre, c0_poi, c1_poi, c2_poi, c3_poi, c4_poi, c5_poi, c6_poi, comment) FROM stdin;
\.


--
-- Data for Name: poids_taille; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.poids_taille (id, datetime, username, maree, id_species, t_measure, taille, p1, p2, p3, p4, p5) FROM stdin;
\.


--
-- Data for Name: route; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.route (id, datetime, username, id_navire, maree, t_fleet, date, lance, h_d, h_f, depth_d, depth_f, speed, reject, sample, comment, location_d, location_f) FROM stdin;
\.


--
-- Data for Name: route_accidentelle; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.route_accidentelle (id, datetime, username, t_fleet, id_navire, maree, date, "time", t_co, lance, location) FROM stdin;
\.


--
-- Data for Name: t_co; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_co (id, co) FROM stdin;
\.


--
-- Data for Name: t_condition; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_condition (id, condition) FROM stdin;
\.


--
-- Data for Name: t_fleet; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_fleet (id, fleet) FROM stdin;
\.


--
-- Data for Name: t_maturity; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_maturity (id, maturity) FROM stdin;
\.


--
-- Data for Name: t_measure; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_measure (id, measure) FROM stdin;
\.


--
-- Data for Name: t_project; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_project (id, project) FROM stdin;
\.


--
-- Data for Name: t_rejete; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_rejete (id, rejete) FROM stdin;
\.


--
-- Data for Name: t_ring; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_ring (id, ring) FROM stdin;
\.


--
-- Data for Name: t_role; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_role (id, role) FROM stdin;
\.


--
-- Data for Name: t_sex; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_sex (id, sex) FROM stdin;
\.


--
-- Data for Name: t_taille_cre; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_taille_cre (id, taille_cre) FROM stdin;
\.


--
-- Data for Name: t_taille_poi; Type: TABLE DATA; Schema: trawlers_server; Owner: postgres
--

COPY trawlers_server.t_taille_poi (id, taille_poi) FROM stdin;
\.


--
-- Data for Name: project; Type: TABLE DATA; Schema: users; Owner: postgres
--

COPY users.project (id, datetime, username, id_user, t_project, t_role, active) FROM stdin;
b6635d7b-62ef-4b80-ac72-9d4b40853b57	2023-10-04 16:23:00.749284	jmensa	f5520c00-19b3-495d-a037-8f6060106aec	1	1	t
94428142-d711-4b08-820a-406bae3e0edb	2023-10-04 16:23:00.768523	jmensa	f5520c00-19b3-495d-a037-8f6060106aec	2	1	t
ded59cc1-f28b-4ca8-89fc-f7f5bddc94cd	2023-10-04 16:23:00.780978	jmensa	f5520c00-19b3-495d-a037-8f6060106aec	3	1	t
2167e290-75f8-4b8f-aa13-6778a947bc95	2023-10-04 16:23:00.783589	jmensa	f5520c00-19b3-495d-a037-8f6060106aec	4	1	t
c4cb1cbd-76f8-47b4-8ccf-aef0860ccaa4	2023-10-04 16:23:00.784156	jmensa	f5520c00-19b3-495d-a037-8f6060106aec	5	1	t
18ab1ff9-b808-4488-bd39-7643fb93bfdf	2023-10-04 16:23:00.795311	jmensa	f5520c00-19b3-495d-a037-8f6060106aec	6	1	t
d7e18595-efff-468a-b4e4-96c273209aa6	2023-10-04 16:23:00.80712	jmensa	f5520c00-19b3-495d-a037-8f6060106aec	7	1	t
\.


--
-- Data for Name: t_project; Type: TABLE DATA; Schema: users; Owner: postgres
--

COPY users.t_project (id, project, active) FROM stdin;
1	Donnes VMS Industrielle	t
2	Autorisations Peche Artisanale	t
3	Capture Peche Artisanale	t
4	Infractions Peche Artisanale	t
5	Program Observateur Peche Industrielle	t
6	Donnes VMS Artisanale	t
7	Declaration Captures Peche Thoniere	t
8	Declaration Captures Peche Crevettier	t
\.


--
-- Data for Name: t_role; Type: TABLE DATA; Schema: users; Owner: postgres
--

COPY users.t_role (id, role, active) FROM stdin;
0	Signataire	t
1	Administrateur	t
2	Gestionnaire	t
3	Utilisateur	t
99	Utilisateur externe	t
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: users; Owner: postgres
--

COPY users.users (id, datetime, username, first_name, last_name, nickname, email, password) FROM stdin;
f5520c00-19b3-495d-a037-8f6060106aec	2023-10-04 16:23:00.603167	jmensa	jean	mensa	jmensa	jmensa@wcs.org	queirolo
\.


--
-- Data for Name: navire; Type: TABLE DATA; Schema: vms; Owner: postgres
--

COPY vms.navire (id, datetime, username, navire, flag, owners, fullname, radio, registration, registration_ext, registration_int, registration_qrt, mobile, mmsi, imo, port, active, beacon, satellite, unknown, t_navire, other_names) FROM stdin;
\.


--
-- Data for Name: positions; Type: TABLE DATA; Schema: vms; Owner: postgres
--

COPY vms.positions (id, datetime, username, id_navire, date_p, speed, location) FROM stdin;
\.


--
-- Data for Name: t_navire; Type: TABLE DATA; Schema: vms; Owner: postgres
--

COPY vms.t_navire (id, navire) FROM stdin;
\.


--
-- Name: carte_carte_seq; Type: SEQUENCE SET; Schema: artisanal; Owner: postgres
--

SELECT pg_catalog.setval('artisanal.carte_carte_seq', 1, false);


--
-- Name: license_license_seq; Type: SEQUENCE SET; Schema: artisanal; Owner: postgres
--

SELECT pg_catalog.setval('artisanal.license_license_seq', 1, false);


--
-- Name: t_license_id_seq; Type: SEQUENCE SET; Schema: artisanal; Owner: postgres
--

SELECT pg_catalog.setval('artisanal.t_license_id_seq', 1, false);


--
-- Name: t_site_id_seq; Type: SEQUENCE SET; Schema: artisanal; Owner: postgres
--

SELECT pg_catalog.setval('artisanal.t_site_id_seq', 1, false);


--
-- Name: t_site_obb_id_seq; Type: SEQUENCE SET; Schema: artisanal; Owner: postgres
--

SELECT pg_catalog.setval('artisanal.t_site_obb_id_seq', 1, false);


--
-- Name: t_status_id_seq; Type: SEQUENCE SET; Schema: artisanal; Owner: postgres
--

SELECT pg_catalog.setval('artisanal.t_status_id_seq', 1, false);


--
-- Name: t_strata_id_seq; Type: SEQUENCE SET; Schema: artisanal; Owner: postgres
--

SELECT pg_catalog.setval('artisanal.t_strata_id_seq', 1, false);


--
-- Name: t_study_id_seq; Type: SEQUENCE SET; Schema: artisanal; Owner: postgres
--

SELECT pg_catalog.setval('artisanal.t_study_id_seq', 1, false);


--
-- Name: -a_gid_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."-a_gid_seq"', 1, false);


--
-- Name: mpa_buffer_gid_seq; Type: SEQUENCE SET; Schema: shapefiles; Owner: postgres
--

SELECT pg_catalog.setval('shapefiles.mpa_buffer_gid_seq', 1, false);


--
-- Name: mpa_gid_seq; Type: SEQUENCE SET; Schema: shapefiles; Owner: postgres
--

SELECT pg_catalog.setval('shapefiles.mpa_gid_seq', 1, false);


--
-- Name: topology_id_seq; Type: SEQUENCE SET; Schema: topology; Owner: postgres
--

SELECT pg_catalog.setval('topology.topology_id_seq', 1, false);


--
-- Name: captures captures_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.captures
    ADD CONSTRAINT captures_pkey PRIMARY KEY (id);


--
-- Name: carte carte_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.carte
    ADD CONSTRAINT carte_pkey PRIMARY KEY (id);


--
-- Name: effort effort_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.effort
    ADD CONSTRAINT effort_pkey PRIMARY KEY (id);


--
-- Name: fisherman fisherman_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.fisherman
    ADD CONSTRAINT fisherman_pkey PRIMARY KEY (id);


--
-- Name: fleet fleet_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.fleet
    ADD CONSTRAINT fleet_pkey PRIMARY KEY (id);


--
-- Name: infraction infraction_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.infraction
    ADD CONSTRAINT infraction_pkey PRIMARY KEY (id);


--
-- Name: infractions infractions_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.infractions
    ADD CONSTRAINT infractions_pkey PRIMARY KEY (id);


--
-- Name: license license_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.license
    ADD CONSTRAINT license_pkey PRIMARY KEY (id);


--
-- Name: maree maree_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.maree
    ADD CONSTRAINT maree_pkey PRIMARY KEY (id);


--
-- Name: market market_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.market
    ADD CONSTRAINT market_pkey PRIMARY KEY (id);


--
-- Name: owner owner_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.owner
    ADD CONSTRAINT owner_pkey PRIMARY KEY (id);


--
-- Name: pelagic_lkp pelagic_lkp_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.pelagic_lkp
    ADD CONSTRAINT pelagic_lkp_pkey PRIMARY KEY (id);


--
-- Name: pelagic_points pelagic_points_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.pelagic_points
    ADD CONSTRAINT pelagic_points_pkey PRIMARY KEY (id);


--
-- Name: pelagic_tracks pelagic_tracks_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.pelagic_tracks
    ADD CONSTRAINT pelagic_tracks_pkey PRIMARY KEY (id);


--
-- Name: pirogue pirogue_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.pirogue
    ADD CONSTRAINT pirogue_pkey PRIMARY KEY (id);


--
-- Name: t_card t_card_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_card
    ADD CONSTRAINT t_card_pkey PRIMARY KEY (id);


--
-- Name: t_coop t_coop_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_coop
    ADD CONSTRAINT t_coop_pkey PRIMARY KEY (id);


--
-- Name: t_gear t_gear_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_gear
    ADD CONSTRAINT t_gear_pkey PRIMARY KEY (id);


--
-- Name: t_immatriculation t_immatriculation_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_immatriculation
    ADD CONSTRAINT t_immatriculation_pkey PRIMARY KEY (id);


--
-- Name: t_infraction t_infraction_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_infraction
    ADD CONSTRAINT t_infraction_pkey PRIMARY KEY (id);


--
-- Name: t_license t_license_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_license
    ADD CONSTRAINT t_license_pkey PRIMARY KEY (id);


--
-- Name: t_nationality t_nationality_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_nationality
    ADD CONSTRAINT t_nationality_pkey PRIMARY KEY (id);


--
-- Name: t_navire t_navire_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_navire
    ADD CONSTRAINT t_navire_pkey PRIMARY KEY (id);


--
-- Name: t_pirogue t_pirogue_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_pirogue
    ADD CONSTRAINT t_pirogue_pkey PRIMARY KEY (id);


--
-- Name: t_registration t_registration_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_registration
    ADD CONSTRAINT t_registration_pkey PRIMARY KEY (id);


--
-- Name: t_site_obb t_site_obb_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_site_obb
    ADD CONSTRAINT t_site_obb_pkey PRIMARY KEY (id);


--
-- Name: t_site t_site_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_site
    ADD CONSTRAINT t_site_pkey PRIMARY KEY (id);


--
-- Name: t_status t_status_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_status
    ADD CONSTRAINT t_status_pkey PRIMARY KEY (id);


--
-- Name: t_strata t_strata_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_strata
    ADD CONSTRAINT t_strata_pkey PRIMARY KEY (id);


--
-- Name: t_study t_study_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_study
    ADD CONSTRAINT t_study_pkey PRIMARY KEY (id);


--
-- Name: t_zone t_zone_pkey; Type: CONSTRAINT; Schema: artisanal; Owner: postgres
--

ALTER TABLE ONLY artisanal.t_zone
    ADD CONSTRAINT t_zone_pkey PRIMARY KEY (id);


--
-- Name: enq_catch enq_catch_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.enq_catch
    ADD CONSTRAINT enq_catch_pkey PRIMARY KEY (id);


--
-- Name: enq_maree enq_maree_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.enq_maree
    ADD CONSTRAINT enq_maree_pkey PRIMARY KEY (id);


--
-- Name: log_catch log_catch_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.log_catch
    ADD CONSTRAINT log_catch_pkey PRIMARY KEY (id);


--
-- Name: log_maree log_maree_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.log_maree
    ADD CONSTRAINT log_maree_pkey PRIMARY KEY (id);


--
-- Name: obs_action obs_action_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.obs_action
    ADD CONSTRAINT obs_action_pkey PRIMARY KEY (id);


--
-- Name: obs_catch obs_catch_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.obs_catch
    ADD CONSTRAINT obs_catch_pkey PRIMARY KEY (id);


--
-- Name: obs_fish obs_fish_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.obs_fish
    ADD CONSTRAINT obs_fish_pkey PRIMARY KEY (id);


--
-- Name: obs_mammals obs_mammals_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.obs_mammals
    ADD CONSTRAINT obs_mammals_pkey PRIMARY KEY (id);


--
-- Name: obs_maree obs_maree_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.obs_maree
    ADD CONSTRAINT obs_maree_pkey PRIMARY KEY (id);


--
-- Name: obs_poids_taille obs_poids_taille_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.obs_poids_taille
    ADD CONSTRAINT obs_poids_taille_pkey PRIMARY KEY (id);


--
-- Name: obs_sharks obs_sharks_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.obs_sharks
    ADD CONSTRAINT obs_sharks_pkey PRIMARY KEY (id);


--
-- Name: obs_turtles obs_turtles_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.obs_turtles
    ADD CONSTRAINT obs_turtles_pkey PRIMARY KEY (id);


--
-- Name: t_action t_action_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.t_action
    ADD CONSTRAINT t_action_pkey PRIMARY KEY (id);


--
-- Name: t_gear t_gear_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.t_gear
    ADD CONSTRAINT t_gear_pkey PRIMARY KEY (id);


--
-- Name: t_integrity t_integrity_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.t_integrity
    ADD CONSTRAINT t_integrity_pkey PRIMARY KEY (id);


--
-- Name: t_maturity t_maturity_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.t_maturity
    ADD CONSTRAINT t_maturity_pkey PRIMARY KEY (id);


--
-- Name: t_mission t_mission_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.t_mission
    ADD CONSTRAINT t_mission_pkey PRIMARY KEY (id);


--
-- Name: t_sex t_sex_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.t_sex
    ADD CONSTRAINT t_sex_pkey PRIMARY KEY (id);


--
-- Name: t_status t_status_pkey; Type: CONSTRAINT; Schema: artisanal_catches; Owner: postgres
--

ALTER TABLE ONLY artisanal_catches.t_status
    ADD CONSTRAINT t_status_pkey PRIMARY KEY (id);


--
-- Name: capture capture_pkey; Type: CONSTRAINT; Schema: crevette; Owner: postgres
--

ALTER TABLE ONLY crevette.capture
    ADD CONSTRAINT capture_pkey PRIMARY KEY (id);


--
-- Name: lance lance_pkey; Type: CONSTRAINT; Schema: crevette; Owner: postgres
--

ALTER TABLE ONLY crevette.lance
    ADD CONSTRAINT lance_pkey PRIMARY KEY (id);


--
-- Name: t_taille t_taille_pkey; Type: CONSTRAINT; Schema: crevette; Owner: postgres
--

ALTER TABLE ONLY crevette.t_taille
    ADD CONSTRAINT t_taille_pkey PRIMARY KEY (id);


--
-- Name: t_zone t_zone_pkey; Type: CONSTRAINT; Schema: crevette; Owner: postgres
--

ALTER TABLE ONLY crevette.t_zone
    ADD CONSTRAINT t_zone_pkey PRIMARY KEY (id);


--
-- Name: species species_pkey; Type: CONSTRAINT; Schema: fishery; Owner: postgres
--

ALTER TABLE ONLY fishery.species
    ADD CONSTRAINT species_pkey PRIMARY KEY (id);


--
-- Name: infraction infraction_pkey; Type: CONSTRAINT; Schema: infraction; Owner: postgres
--

ALTER TABLE ONLY infraction.infraction
    ADD CONSTRAINT infraction_pkey PRIMARY KEY (id);


--
-- Name: infractions infractions_pkey; Type: CONSTRAINT; Schema: infraction; Owner: postgres
--

ALTER TABLE ONLY infraction.infractions
    ADD CONSTRAINT infractions_pkey PRIMARY KEY (id);


--
-- Name: t_infraction t_infraction_pkey; Type: CONSTRAINT; Schema: infraction; Owner: postgres
--

ALTER TABLE ONLY infraction.t_infraction
    ADD CONSTRAINT t_infraction_pkey PRIMARY KEY (id);


--
-- Name: t_org t_org_pkey; Type: CONSTRAINT; Schema: infraction; Owner: postgres
--

ALTER TABLE ONLY infraction.t_org
    ADD CONSTRAINT t_org_pkey PRIMARY KEY (id);


--
-- Name: capture capture_pkey; Type: CONSTRAINT; Schema: poisson; Owner: postgres
--

ALTER TABLE ONLY poisson.capture
    ADD CONSTRAINT capture_pkey PRIMARY KEY (id);


--
-- Name: maree maree_pkey; Type: CONSTRAINT; Schema: poisson; Owner: postgres
--

ALTER TABLE ONLY poisson.maree
    ADD CONSTRAINT maree_pkey PRIMARY KEY (id);


--
-- Name: t_taille t_taille_pkey; Type: CONSTRAINT; Schema: poisson; Owner: postgres
--

ALTER TABLE ONLY poisson.t_taille
    ADD CONSTRAINT t_taille_pkey PRIMARY KEY (id);


--
-- Name: t_zone t_zone_pkey; Type: CONSTRAINT; Schema: poisson; Owner: postgres
--

ALTER TABLE ONLY poisson.t_zone
    ADD CONSTRAINT t_zone_pkey PRIMARY KEY (id);


--
-- Name: -a -a_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."-a"
    ADD CONSTRAINT "-a_pkey" PRIMARY KEY (gid);


--
-- Name: objet objet_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.objet
    ADD CONSTRAINT objet_pkey PRIMARY KEY (id);


--
-- Name: prise_access prise_access_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.prise_access
    ADD CONSTRAINT prise_access_pkey PRIMARY KEY (id);


--
-- Name: prise_access_taille prise_access_taille_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.prise_access_taille
    ADD CONSTRAINT prise_access_taille_pkey PRIMARY KEY (id);


--
-- Name: route route_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.route
    ADD CONSTRAINT route_pkey PRIMARY KEY (id);


--
-- Name: t_action t_action_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_action
    ADD CONSTRAINT t_action_pkey PRIMARY KEY (id);


--
-- Name: t_activite t_activite_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_activite
    ADD CONSTRAINT t_activite_pkey PRIMARY KEY (id);


--
-- Name: t_appartenance t_appartenance_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_appartenance
    ADD CONSTRAINT t_appartenance_pkey PRIMARY KEY (id);


--
-- Name: t_capture t_capture_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_capture
    ADD CONSTRAINT t_capture_pkey PRIMARY KEY (id);


--
-- Name: t_categorie t_categorie_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_categorie
    ADD CONSTRAINT t_categorie_pkey PRIMARY KEY (id);


--
-- Name: t_detection t_detection_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_detection
    ADD CONSTRAINT t_detection_pkey PRIMARY KEY (id);


--
-- Name: t_devenir t_devenir_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_devenir
    ADD CONSTRAINT t_devenir_pkey PRIMARY KEY (id);


--
-- Name: t_espece t_espece_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_espece
    ADD CONSTRAINT t_espece_pkey PRIMARY KEY (id);


--
-- Name: t_measure t_measure_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_measure
    ADD CONSTRAINT t_measure_pkey PRIMARY KEY (id);


--
-- Name: t_neighbours t_neighbours_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_neighbours
    ADD CONSTRAINT t_neighbours_pkey PRIMARY KEY (id);


--
-- Name: t_objet t_objet_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_objet
    ADD CONSTRAINT t_objet_pkey PRIMARY KEY (id);


--
-- Name: t_operation t_operation_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_operation
    ADD CONSTRAINT t_operation_pkey PRIMARY KEY (id);


--
-- Name: t_peche t_peche_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_peche
    ADD CONSTRAINT t_peche_pkey PRIMARY KEY (id);


--
-- Name: t_prise t_prise_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_prise
    ADD CONSTRAINT t_prise_pkey PRIMARY KEY (id);


--
-- Name: t_raison t_raison_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_raison
    ADD CONSTRAINT t_raison_pkey PRIMARY KEY (id);


--
-- Name: t_relache t_relache_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_relache
    ADD CONSTRAINT t_relache_pkey PRIMARY KEY (id);


--
-- Name: t_sexe t_sexe_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_sexe
    ADD CONSTRAINT t_sexe_pkey PRIMARY KEY (id);


--
-- Name: t_systeme t_systeme_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_systeme
    ADD CONSTRAINT t_systeme_pkey PRIMARY KEY (id);


--
-- Name: t_type t_type_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_type
    ADD CONSTRAINT t_type_pkey PRIMARY KEY (id);


--
-- Name: t_zee t_zee_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.t_zee
    ADD CONSTRAINT t_zee_pkey PRIMARY KEY (id);


--
-- Name: thon_rejete thon_rejete_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.thon_rejete
    ADD CONSTRAINT thon_rejete_pkey PRIMARY KEY (id);


--
-- Name: thon_rejete_taille thon_rejete_taille_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.thon_rejete_taille
    ADD CONSTRAINT thon_rejete_taille_pkey PRIMARY KEY (id);


--
-- Name: thon_retenue thon_retenue_pkey; Type: CONSTRAINT; Schema: seiners; Owner: postgres
--

ALTER TABLE ONLY seiners.thon_retenue
    ADD CONSTRAINT thon_retenue_pkey PRIMARY KEY (id);


--
-- Name: mpa_buffer mpa_buffer_pkey; Type: CONSTRAINT; Schema: shapefiles; Owner: postgres
--

ALTER TABLE ONLY shapefiles.mpa_buffer
    ADD CONSTRAINT mpa_buffer_pkey PRIMARY KEY (gid);


--
-- Name: mpa mpa_pkey; Type: CONSTRAINT; Schema: shapefiles; Owner: postgres
--

ALTER TABLE ONLY shapefiles.mpa
    ADD CONSTRAINT mpa_pkey PRIMARY KEY (gid);


--
-- Name: captures captures_pkey; Type: CONSTRAINT; Schema: thon; Owner: postgres
--

ALTER TABLE ONLY thon.captures
    ADD CONSTRAINT captures_pkey PRIMARY KEY (id);


--
-- Name: entreesortie entreesortie_pkey; Type: CONSTRAINT; Schema: thon; Owner: postgres
--

ALTER TABLE ONLY thon.entreesortie
    ADD CONSTRAINT entreesortie_pkey PRIMARY KEY (id);


--
-- Name: lance lance_pkey; Type: CONSTRAINT; Schema: thon; Owner: postgres
--

ALTER TABLE ONLY thon.lance
    ADD CONSTRAINT lance_pkey PRIMARY KEY (id);


--
-- Name: project project_pkey; Type: CONSTRAINT; Schema: users; Owner: postgres
--

ALTER TABLE ONLY users.project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- Name: t_project t_project_pkey; Type: CONSTRAINT; Schema: users; Owner: postgres
--

ALTER TABLE ONLY users.t_project
    ADD CONSTRAINT t_project_pkey PRIMARY KEY (id);


--
-- Name: t_role t_role_pkey; Type: CONSTRAINT; Schema: users; Owner: postgres
--

ALTER TABLE ONLY users.t_role
    ADD CONSTRAINT t_role_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: users; Owner: postgres
--

ALTER TABLE ONLY users.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: navire navire_pkey; Type: CONSTRAINT; Schema: vms; Owner: postgres
--

ALTER TABLE ONLY vms.navire
    ADD CONSTRAINT navire_pkey PRIMARY KEY (id);


--
-- Name: positions positions_pkey; Type: CONSTRAINT; Schema: vms; Owner: postgres
--

ALTER TABLE ONLY vms.positions
    ADD CONSTRAINT positions_pkey PRIMARY KEY (id);


--
-- Name: t_navire t_navire_pkey; Type: CONSTRAINT; Schema: vms; Owner: postgres
--

ALTER TABLE ONLY vms.t_navire
    ADD CONSTRAINT t_navire_pkey PRIMARY KEY (id);


--
-- Name: trgm_idx_first_name_fisherman; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_first_name_fisherman ON artisanal.fisherman USING gist (first_name public.gist_trgm_ops);


--
-- Name: trgm_idx_first_name_owner; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_first_name_owner ON artisanal.owner USING gist (first_name public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_first_infraction; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_fish_first_infraction ON artisanal.infraction USING gist (fish_first public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_idcard_infraction; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_fish_idcard_infraction ON artisanal.infraction USING gist (fish_idcard public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_last_infraction; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_fish_last_infraction ON artisanal.infraction USING gist (fish_last public.gist_trgm_ops);


--
-- Name: trgm_idx_idcard_fisherman; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_idcard_fisherman ON artisanal.fisherman USING gist (idcard public.gist_trgm_ops);


--
-- Name: trgm_idx_idcard_owner; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_idcard_owner ON artisanal.owner USING gist (idcard public.gist_trgm_ops);


--
-- Name: trgm_idx_immatriculation; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_immatriculation ON artisanal.pirogue USING gist (immatriculation public.gist_trgm_ops);


--
-- Name: trgm_idx_last_name_fisherman; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_last_name_fisherman ON artisanal.fisherman USING gist (last_name public.gist_trgm_ops);


--
-- Name: trgm_idx_last_name_owner; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_last_name_owner ON artisanal.owner USING gist (last_name public.gist_trgm_ops);


--
-- Name: trgm_idx_name; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_name ON artisanal.pirogue USING gist (name public.gist_trgm_ops);


--
-- Name: trgm_idx_pir_name_infraction; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_pir_name_infraction ON artisanal.infraction USING gist (pir_name public.gist_trgm_ops);


--
-- Name: trgm_idx_pir_reg_infraction; Type: INDEX; Schema: artisanal; Owner: postgres
--

CREATE INDEX trgm_idx_pir_reg_infraction ON artisanal.infraction USING gist (immatriculation public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_first_1_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_fish_first_1_infraction ON infraction.infraction USING gist (fish_first_1 public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_first_2_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_fish_first_2_infraction ON infraction.infraction USING gist (fish_first_2 public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_first_3_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_fish_first_3_infraction ON infraction.infraction USING gist (fish_first_3 public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_idcard_1_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_fish_idcard_1_infraction ON infraction.infraction USING gist (fish_idcard_1 public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_idcard_2_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_fish_idcard_2_infraction ON infraction.infraction USING gist (fish_idcard_2 public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_idcard_3_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_fish_idcard_3_infraction ON infraction.infraction USING gist (fish_idcard_3 public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_last_1_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_fish_last_1_infraction ON infraction.infraction USING gist (fish_last_1 public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_last_2_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_fish_last_2_infraction ON infraction.infraction USING gist (fish_last_2 public.gist_trgm_ops);


--
-- Name: trgm_idx_fish_last_3_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_fish_last_3_infraction ON infraction.infraction USING gist (fish_last_3 public.gist_trgm_ops);


--
-- Name: trgm_idx_owner_first_fraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_owner_first_fraction ON infraction.infraction USING gist (owner_first public.gist_trgm_ops);


--
-- Name: trgm_idx_owner_idcard_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_owner_idcard_infraction ON infraction.infraction USING gist (owner_idcard public.gist_trgm_ops);


--
-- Name: trgm_idx_owner_last_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_owner_last_infraction ON infraction.infraction USING gist (owner_last public.gist_trgm_ops);


--
-- Name: trgm_idx_pir_name_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_pir_name_infraction ON infraction.infraction USING gist (pir_name public.gist_trgm_ops);


--
-- Name: trgm_idx_pir_reg_infraction; Type: INDEX; Schema: infraction; Owner: postgres
--

CREATE INDEX trgm_idx_pir_reg_infraction ON infraction.infraction USING gist (immatriculation public.gist_trgm_ops);


--
-- Name: trgm_idx_obj_maree; Type: INDEX; Schema: seiners; Owner: postgres
--

CREATE INDEX trgm_idx_obj_maree ON seiners.objet USING gist (maree public.gist_trgm_ops);


--
-- Name: trgm_idx_route_maree; Type: INDEX; Schema: seiners; Owner: postgres
--

CREATE INDEX trgm_idx_route_maree ON seiners.route USING gist (maree public.gist_trgm_ops);


--
-- Name: trgm_idx_thon_rejete_maree; Type: INDEX; Schema: seiners; Owner: postgres
--

CREATE INDEX trgm_idx_thon_rejete_maree ON seiners.thon_rejete USING gist (maree public.gist_trgm_ops);


--
-- Name: trgm_idx_thon_retenue_maree; Type: INDEX; Schema: seiners; Owner: postgres
--

CREATE INDEX trgm_idx_thon_retenue_maree ON seiners.thon_retenue USING gist (maree public.gist_trgm_ops);


--
-- Name: id_navire_date_p_idx; Type: INDEX; Schema: vms; Owner: postgres
--

CREATE INDEX id_navire_date_p_idx ON vms.positions USING btree (id_navire, date_p DESC);


--
-- Name: id_navire_datetime_idx; Type: INDEX; Schema: vms; Owner: postgres
--

CREATE INDEX id_navire_datetime_idx ON vms.positions USING btree (id_navire, datetime DESC);


--
-- Name: themis_navire_date_p_idx; Type: INDEX; Schema: vms; Owner: postgres
--

CREATE INDEX themis_navire_date_p_idx ON vms.navire USING btree (navire);


--
-- Name: themis_navire_id_idx; Type: INDEX; Schema: vms; Owner: postgres
--

CREATE INDEX themis_navire_id_idx ON vms.navire USING btree (id);


--
-- PostgreSQL database dump complete
--

