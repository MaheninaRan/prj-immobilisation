drop database comerceimob;
create database comerceimob;
use comerceimob;


INSERT INTO societe (lieu, adresse, contact, nom, nomdg, email) VALUES
('Paris', '10 Rue de la Republique', '0123 45 67 89', 'ABC Corporation', 'John Doe', 'john.doe@abccorp.com');

-- Insertion de données dans la table 'services'
INSERT INTO services (id, anarany,idSociete, mdp) VALUES
(1, 'ressources humaines',1,'1234'),
(2, 'comptable',1,'1234'),
(3, 'marketing',1,'1234'),
(4,'achat',1,'1234'),
(5,'recepteur',1,'1234');

-- Insertion de données dans la table 'personneservice'
INSERT INTO personneservice (id, nom, prenom, sexe,contact, datenaissance, email, idService) VALUES
(1, 'Randrianjanahary', 'Mahenina', 'homme','034 43 914 50', '2004-04-26', 'mahenina@gmail.com', 1),
(2, 'Rajaonarisoa', 'Mirana', 'homme', '034 78 678 45','1985-05-15', 'mirana@gmail.com', 2),
(3, 'Marie', 'Francois', 'femme','+261 26 678 22','1992-08-20', 'marie@gmail.com', 3),
(4,'Rafalimanana','Junior','homme','032 23 678 23','2000-12-18','junior@gmail.com',4),
(5,'Rosario','Theresa','femme','022 12 567 23','2001-09-12','theresa@gmail.com',5);

-- Insertion de données dans la table 'besoin'
insert into chef(nom,prenom,genre,naissance,contact,email,motdepasse,fonction) VALUES ('Jordy','Toky','homme','20002-03-12','0340056712','toky@gmail.com','toky','departement');
insert into chef(nom,prenom,genre,naissance,contact,email,motdepasse,fonction) VALUES ('Rabenja','Liana','femme','20002-03-12','0388906764','liana@gmail.com','liana','DAF');

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


