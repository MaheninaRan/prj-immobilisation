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
select prodF.id,fo.id as idfournisseur,fo.nom as fournisseur,gen.compte, gen.code,gen.id as idgenre, gen.genre,prod.id as idarticle, prod.nom as article,prodF.prix,prodF.tva,prodF.quantite
from fournisseur as fo 
inner join produitFournisseur as prodF on prodF.idfournisseur=fo.id
inner join produit as prod on prodF.idproduit=prod.id
inner join comptegenre as gen on gen.id=prod.idgenre group by prodF.id;


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
select distinct prod.id,dem.idDepartement,dem.idSociete, dem.anarany as departement,prod.idfournisseur, prod.fournisseur,dem.id as idBesoin,dem.article,prod.article as marque, dem.num,dem.quantite,dem.dateBesoin, dem.daty,prod.prix,prod.tva, (prod.prix)*(prod.tva/100) as prixTva,(prod.prix*dem.quantite) +(((prod.prix)*(prod.tva/100))*dem.quantite) as total,dem.etat
from demandeDetail as dem 
inner join produitDetail as prod on prod.genre=dem.article;

create table finance(
    id serial primary key,
    id_proforma int,
    dateCommande date
);


create or replace view commandes as
select societe.id as idsociete,
idDepartement,
idfournisseur,
article, 
marque,
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


create table facture(
    id serial primary key,
    idSociete int references societe(id),
    idfournisseur int references fournisseur(id),
    dateFacture date,
    heure time,
    num int,
    idarticle int references produitFournisseur(id),
    quantite float,
    fraisLivraison float
);

create or replace view facturedetail as 
select fac.id, fac.dateFacture,fac.heure,fac.num, prod.idfournisseur,prod.fournisseur,prod.compte,prod.code,prod.idgenre,prod.genre,prod.idarticle, prod.article,prod.prix as prixu,prod.tva,fac.quantite,fac.fraisLivraison, (prod.prix*fac.quantite)+fac.fraisLivraison  as total 
from facture as fac 
inner join produitDetail as prod on fac.idarticle=prod.id;



create table livraison(
    id serial primary key,
    idLivreur int references livreur(id),
    numfacture int references facture(num),
    dateLivraison date,
    heure time,
    fraisLivraison float,
    statut int
);
create table compta(
    id int primary key auto_increment,
    nom varchar(30),
    prenom varchar(30),
    email varchar(55),
    mdp varchar(55)
);

create table personnelle(
    id int primary key auto_increment,
    nom varchar(55),
    adresse varchar(30),
    contact varchar(30),
    email varchar(30),
    pass varchar(30)
);

create table responsable(
    id int primary key auto_increment,
    nom varchar(30),
    prenom varchar(30),
    email varchar(55),
    mdp varchar(55)
);
create or replace view livraisondetail as 
select liv.id, liv.dateLivraison,liv.heure,fac.fournisseur,fac.compte,fac.code,fac.idgenre,fac.genre,fac.idarticle,fac.article,fac.prixu,fac.quantite,fac.fraisLivraison,fac.total,livr.nom as nomlivreur, livr.prenom as prenomlivreur,livr.contact,livr.email,liv.statut
from facturedetail as fac 
inner join livraison as liv on liv.numfacture=fac.num
inner join livreur as livr on livr.id=liv.idLivreur;

create table amortissement(
    id int primary key auto_increment,
    nom varchar(30)
);

create table reception(
    id int primary key auto_increment,
    idlivraison int references livraison(id),
    amortissement int references amortissement(id),
    statut int
);

create or replace view receptiondetail as 
select rec.id as idrecept, rec.amortissement,liv.*,rec.statut as statutreception
from reception as rec 
inner join livraisondetail as liv on liv.id=rec.idlivraison;

create table lieu(
    id int primary key auto_increment,
    lieu varchar(255)
);

create table utilisation(
    id int primary key auto_increment,
    idmarchandises int references receptiondetail(id),
    idpersonnelle int references personnelle(id),
    descript varchar(120) not null,
    etat varchar(255),
    lieu varchar(55),
    dureedevie varchar(55),
    date date,
    tauxAmorti float,
    statut int 
);

create or replace view utilisation_v as 
select util.id as idutil, rec.*, pers.nom as persnom, pers.adresse as persadresse,pers.contact as perscontact
from utilisation as util 
inner join receptiondetail as rec on rec.id=util.idmarchandises
inner join personnelle as pers on pers.id=util.idpersonnelle;

create table suivie(
    id int primary key auto_increment,
    idpersonnelle int references personnelle(id),
    idmarchandise int references receptiondetail(id),
    etat varchar(55),
    lieu varchar(40),
    descript varchar(120) not null,
    datesuivie date,
    statut int
);

create or replace view suivie_v as 
select suiv.id as idsuivie,rec.*, suiv.etat, suiv.lieu,suiv.descript,suiv.datesuivie, pers.nom as persnom, pers.adresse as persadresse,pers.contact as perscontact
from suivie as suiv 
inner join receptiondetail as rec on suiv.idmarchandise=rec.id 
inner join personnelle as pers on pers.id=suiv.idpersonnelle;


create table detail_voiture(
    id serial primary key,
    idlivraison int references livraisondetail(id),
    couleur varchar(55),
    moteur varchar(55),
    datevisite date,
    assurance date,
    vidange date,
    annesortie date  
);

create table detail_moto(
    id serial primary key,
    idlivraison int references livraisondetail(id),
    couleur varchar(55),
    vinette date,
    assurance date,
    vidange date,
    CC varchar(222),
    moteur varchar(55),
    annesortie date
);

create table detail_ordi(
    id serial primary key,
    idlivraison int references livraisondetail(id),
    couleur varchar(55),
    graphe varchar(55),
    ram varchar(55),
    systemeexploitation varchar(55),
    processeur varchar(233),
    memoire varchar(200),
    ecran varchar(100)
);

create table maintenance_materiel(
    id serial primary key,
    nom varchar(75),
    idcategorie int references genre(id)
);


create table maintenance(
    id int primary key auto_increment,
    dates date,
    idreception int references receptiondetail(id),
    idmaintenance_materiel int references maintenance_materiel(id),
    prix numeric
);

create table demandeUtilisation(
    id int primary key auto_increment,
    idpersonnelle int references personnelle(id),
    idmarchandise int references receptiondetail(id),
    dateDebut date,
    heureDebut time,
    dateFin date,
    heureFin time,
    lieu varchar(30)
);

create or replace view demandeUtil_v as 
select demU.id as iddemande, demU.dateDebut,demU.heureDebut,demU.dateFin,demU.heureFin,rec.*,  pers.nom as persnom, pers.adresse as persadresse,pers.contact as perscontact
from demandeUtilisation as demU 
inner join personnelle as pers on pers.id=demU.idpersonnelle
inner join receptiondetail as rec on rec.id=demU.idmarchandise;

create or replace view maintenancedetail as 
select main.id as idmain,mainM.nom as maintenance, main.prix, rec.*
from maintenance as main 
inner join receptiondetail as rec on rec.id=main.idreception
inner join maintenance_materiel as mainM on mainM.id=main.idmaintenance_materiel;

INSERT INTO societe (lieu, adresse, contact, nom, nomdg, email) VALUES
('Paris', '10 Rue de la Republique', '0123 45 67 89', 'ABC Corporation', 'John Doe', 'john.doe@abccorp.com');

INSERT INTO services (id, anarany,idSociete, mdp) VALUES
(1, 'ressources humaines',1,'1234'),
(2, 'comptable',1,'1234'),
(3, 'marketing',1,'1234'),
(4,'achat',1,'1234'),
(5,'recepteur',1,'1234'),
(6,'personnelle',1,'1234');

insert into amortissement(nom) values
('Amortissement lineaire'),
('Amortissement degressive');

insert into lieu(lieu) values
('Paris'),
('Londres');

insert into maintenance_materiel(nom,idcategorie) values
    ("RAM",3),
    ("Disque dur",3),
    ("Souris",3),
    ("Chargeur",3),
    ("Ecran",3),
    ("Vidange",1),
    ("Pneu",1),
    ("Lavage",1),
    ("Bougie",1),
    ("Filtre",1);

INSERT INTO personneservice (id, nom, prenom, sexe,contact, datenaissance, email, idService) VALUES
(1, 'Randrianjanahary', 'Mahenina', 'homme','034 43 914 50', '2004-04-26', 'mahenina@gmail.com', 1),
(2, 'Rajaonarisoa', 'Mirana', 'homme', '034 78 678 45','1985-05-15', 'mirana@gmail.com', 2),
(3, 'Marie', 'Francois', 'femme','+261 26 678 22','1992-08-20', 'marie@gmail.com', 3),
(4,'Rafalimanana','Junior','homme','032 23 678 23','2000-12-18','junior@gmail.com',4),
(5,'Rosario','Theresa','femme','022 12 567 23','2001-09-12','theresa@gmail.com',5);

insert into personnelle(nom,adresse,contact) values
('jean','IAH5BIS','0345569874'),
('Jazz','VBN','08932389'),
('Martin','12JK','+0227891289');

insert into chef(nom,prenom,genre,naissance,contact,email,motdepasse,fonction) VALUES ('Jordy','Toky','homme','20002-03-12','0340056712','toky@gmail.com','toky','departement');
insert into chef(nom,prenom,genre,naissance,contact,email,motdepasse,fonction) VALUES ('Rabenja','Liana','femme','20002-03-12','0388906764','liana@gmail.com','liana','DAF');
insert into compta(nom,prenom,email,mdp) values("Randria", "Mahenina","Mahenina@gmail.com","mahenina");
insert into responsable(nom, prenom,email,mdp) values ("Rabenja","Liana","Liana@gmail.com","liana");

INSERT INTO fournisseur (nom,province,adresse,email, phone, pass) VALUES
('Jumbo score','Antananarivo','TB126 Tanjombato','jumbo@email.com', 123456789, '1234'),
('Leader price','Antananarivo','MB45 Andoharanofotsy', 'leader@email.com', 123456789, '12346'),
('Shop U' ,'Antananarivo','Ambohimahitsy', 'shopU@email.com', 987654321, '12345');

insert into livreur(idfournisseur,nom,prenom,contact,email) values 
(1,'Rakotoson','Louis','034 342 22 08', 'louis@gmail.com'),
(1,'Raharisoa','Benja','033 112 34 78', 'benja@gmail.com'),
(1,'Cavani','Leo','034 34 224 08', 'louis@gmail.com'),
(2,'Clavier','Marco','034 00 234 22', 'marco@gmail.com'),
(2,'Samoelison','Alex','033 23 196 90', 'alex@gmail.com'),
(2,'Heriniana','Marck','034 12 223 07', 'marck@gmail.com'),
(3,'Manantena','Angelo','032 008 22 76', 'angelo@gmail.com'),
(3,'Rakotomalala','John','033 102 32 67', 'john@gmail.com'),
(3,'Andriantsoa','Brayane','034 025 24 56', 'brayane@gmail.com');


insert into compte(code,nom) values 
(215,"Materiel transport"),
(200,"Materiel bureau");

insert into genre(idcompte,nom) values
(1,"Voiture"),
(1,"Moto"),
(2,"Stylo"),
(2,"Ordinateur"),
(2,"Decoration");


insert into produit(nom,idgenre) values
("BMW",1),
("Mazda",1),
("Toyota",1),
("Audi",1),
("GP",2),
("Racing",2),
("Jog",2),
("Lauria",3),
("Schneider",3),
("Maped",3),
("Maria",3),
("Hp",4),
("ASUS",4),
("Dell",4),
("Mac",4),
("Fleur",5),
("Rido",5),
("Peinture",5);

insert into produitFournisseur(idfournisseur,idproduit,prix,tva,quantite) values
(1,1,100000000,20,5),
(1,4,77000000,20,10),
(2,1,35000000,20,15),
(2,2,10000000,20,30),
(2,3,23500000,20,12),
(3,2,18300000,20,25),
(3,4,100000000,20,8),
(1,5,20000000,20,3),
(1,7,6000000,20,30),
(2,6,12000000,20,20),
(1,8,700,20,130),
(1,9,800,20,150),
(1,10,500,20,150),
(1,11,750,20,170),
(2,8,500,20,130),
(2,9,700,20,150),
(2,10,600,20,150),
(2,11,700,20,170),
(3,8,650,20,1000),
(3,9,1000,20,800),
(3,10,600,20,200),
(3,11,900,20,300),
(2,13,3000000,20,60),
(2,14,2600000,20,60),
(1,12,2500000,20,80),
(3,15,3000000,20,100),
(3,12,195000,20,100),
(1,16,4000,20,100),
(1,17,7000,20,100),
(3,16,5000,20,200),
(3,18,10000,20,200),
(2,16,3000,20,200),
(2,17,8000,20,200),
(2,18,20000,20,200);

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


