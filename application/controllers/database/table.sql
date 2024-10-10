drop database comerceimob;
create database comerceimob;
use comerceimob;

create table fournisseur(
    id int primary key auto_increment,
    nom varchar(50),
    province varchar(50),
    adresse varchar(50),
    email varchar(100),
    phone int,
    pass varchar(100)
);

create table livreur(
    id int primary key auto_increment,
    idfournisseur int references fournisseur(id),
    nom varchar(30),
    prenom varchar(30),
    contact varchar(30),
    email varchar(30)
);


create table societe (
    id int primary key auto_increment,
    lieu varchar(255),
    adresse varchar(255),
    contact varchar(255),
    nom varchar(255),
    nomdg varchar(255),
    email varchar(255)
);

create table compte(
    id int primary key auto_increment,
    code int,
    nom varchar(40)
);

create table genre(
    id int primary key auto_increment,
    idcompte int references compte(id),
    nom varchar(30)
); 

create or replace view comptegenre as 
select gen.id,co.code, co.nom as compte, gen.nom as genre
from genre as gen 
inner join compte as co on gen.idcompte=co.id;

create table Produit(
    id int primary key auto_increment,
    nom varchar(50),
    idgenre int references genre(id)
);

create table produitFournisseur(
    id int primary key auto_increment,
    idfournisseur int references fournisseur(id),
    idproduit int references produit(id),
    prix float,
    tva float,
    quantite float 
);


create or replace view produitDetail as 
select prodF.id,fo.id as idfournisseur,fo.nom as fournisseur,gen.compte, gen.code, gen.genre, prod.nom as article,prodF.prix,prodF.tva,prodF.quantite
from fournisseur as fo 
inner join produitFournisseur as prodF on prodF.idfournisseur=fo.id
inner join produit as prod on prodF.idfournisseur=fo.id
inner join comptegenre as gen on gen.id=prod.idgenre;

create table chef(
    id int primary key auto_increment,
    nom varchar(30),
    prenom varchar(30),
    genre varchar(30),
    naissance date,
    contact varchar(30),
    email varchar(30),
    motdepasse varchar(30),
    fonction varchar(30)
);

CREATE TABLE services(
    id INT PRIMARY KEY auto_increment,
    anarany VARCHAR(255),
    idSociete int,
    mdp VARCHAR(255),
    FOREIGN KEY (idSociete) REFERENCES societe(id)
);

CREATE TABLE personneservice(
    id INT PRIMARY KEY auto_increment,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    sexe VARCHAR(10),
    contact varchar(30),
    datenaissance DATE,
    email VARCHAR(255),
    idService INT,
    FOREIGN KEY (idService) REFERENCES services(id)
);

create or replace view departement AS 
select serv.id, serv.anarany,serv.mdp,prs.nom,prs.prenom,prs.sexe,prs.contact,prs.datenaissance as naissance,prs.email
from services as serv 
inner join personneservice as prs on prs.idService=serv.id;

CREATE TABLE besoin(
    id INT PRIMARY KEY auto_increment,
    idService INT,
    num int,
    article VARCHAR(255),
    quantite INT,
    dateEnvoie date,
    dateBesoin date,
    etat int,
    FOREIGN KEY (idService) REFERENCES services(id)
);

create or replace view besoinDetail as 
select bes.id,serv.id as idDepartement,serv.idSociete,serv.anarany,bes.num,bes.article,bes.quantite,bes.dateEnvoie,bes.dateBesoin,bes.etat
from besoin as bes 
inner join services as serv on serv.id=bes.idService;

CREATE TABLE demande(
    id INT PRIMARY KEY auto_increment,
    idBesoin INT,
    daty DATE,
    FOREIGN KEY (idBesoin) REFERENCES besoin(id)
);

create or replace view demandeDetail as 
select bes.id,bes.idDepartement,bes.idSociete, bes.anarany,bes.num,bes.article,bes.quantite,bes.dateEnvoie,bes.dateBesoin,dem.daty,bes.etat
from besoinDetail as bes 
inner join demande as dem on dem.idBesoin=bes.id;

create or replace view proforma as 
select distinct prod.id,dem.idDepartement,dem.idSociete, dem.anarany as departement,prod.idfournisseur, prod.fournisseur,dem.id as idBesoin,dem.article,dem.num,dem.quantite,dem.dateBesoin, dem.daty,prod.prix,prod.tva, (prod.prix)*(prod.tva/100) as prixTva,(prod.prix*dem.quantite) +(((prod.prix)*(prod.tva/100))*dem.quantite) as total,dem.etat
from demandeDetail as dem 
inner join produitDetail as prod on prod.article=dem.article;

create table finance(
    id serial primary key,
    id_proforma int,
    dateCommande date
);

create table facture(
    id serial primary key,
    idSociete int references societe(id),
    idfournisseur int references fournisseur(id),
    dateFacture date,
    heure time,
    num int,
    idarticle int references article(id),
    quantite float,
    fraisLivraison float
);

create table livraison(
    id serial primary key,
    idLivreur int references livreur(id),
    numfacture int,
    dateLivraison date,
    heure time,
    fraisLivraison float 
);

create or replace view commandes as
select societe.id as idsociete,
idDepartement,
idfournisseur,
article, 
quantite,
f.dateCommande,
dateBesoin,
daty,
total,
societe.lieu,
societe.adresse,
societe.contact,
societe.nom,
societe.nomdg,
societe.email
from proforma as pro
inner join finance as f on f.id_proforma=pro.id
INNER JOIN societe as societe on societe.id=pro.idSociete;



SELECT
    fournisseur,
    MIN(article) AS article,
    num,
    MIN(quantite) AS quantite,
    MIN(prix) AS prix,
    MIN(tva) AS tva,
    MIN(prixTva) AS prixTva,
    MIN(total) AS total
FROM
    proforma
WHERE
    fournisseur = 'Leader price' AND num = '1'
GROUP BY
    id,fournisseur, article,num, quantite,prix,prixTva,total;