<?php
namespace App\Model;
use App\Entity\Admin;
use App\Entity\Alergene;
use App\Entity\Categorie;
use App\Entity\Nutriscore;
use App\Entity\Producteur;
use App\Entity\Produit;
use SQLite3;
use InvalidArgumentException;

class BDD
{
    private static string $cheminDeLaBDD = '../var/data_dev.db';
    static public function Producteur()
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $requete = "select siret,email,nom,prenom,marque,logo,adresse,is_verified,resiliation,archiver from producteur where is_verified = true and (resiliation IS NULL or resiliation > date('now')) and (archiver IS NULL or archiver > date('now'))";
        $result = $bdd->query($requete);
        $producteurs = array();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $producteur = new Producteur();
                $producteur->setSiret($row['siret']);
                $producteur->setEmail($row['email']);
                $producteur->setNom($row['nom']);
                $producteur->setPrenom($row['prenom']);
                $producteur->setMarque($row['marque']);
                $producteur->setLogo($row['logo']);
                $producteur->setAdresse($row['adresse']);
                $producteur->setIsVerified($row['is_verified']);
                $producteur->setResiliation($row['resiliation']);
                $producteur->setArchiver($row['archiver']);
                $producteurs[] = $producteur;
            }
        }
        return $producteurs;
    }
    static public function archive()
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $requete = "select siret,email,nom,prenom,marque,logo,adresse,is_verified,resiliation,archiver from producteur where archiver IS NOT NULL";
        $result = $bdd->query($requete);
        $producteurs = array();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $producteur = new Producteur();
                $producteur->setSiret($row['siret']);
                $producteur->setEmail($row['email']);
                $producteur->setNom($row['nom']);
                $producteur->setPrenom($row['prenom']);
                $producteur->setMarque($row['marque']);
                $producteur->setLogo($row['logo']);
                $producteur->setAdresse($row['adresse']);
                $producteur->setIsVerified($row['is_verified']);
                $producteur->setResiliation($row['resiliation']);
                $producteur->setArchiver($row['archiver']);
                $producteurs[] = $producteur;
            }
        }
        return $producteurs;
    }

    static public function Produit()
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $requete = "select id,produit.nom,description,prix,image,producteur_id,pourcentage,quantite,nutriscore_id,ingredients,est_bio from produit";
        $result = $bdd->query($requete);
        $produits = array();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $produit = new Produit();
                $produit->setNom($row['nom']);
                $produit->setDescription($row['description']);
                $produit->setPrix($row['prix']);
                $produit->setImage($row['image']);
                $requete = "select siret,email,nom,prenom,marque,logo,adresse,is_verified,resiliation,archiver from producteur where id = " . $row['producteur_id'];
                $result2 = $bdd->query($requete);
                if ($result2) {
                    $row2 = $result2->fetchArray(SQLITE3_ASSOC);
                    $producteur = new Producteur();
                    $producteur->setSiret($row2['siret']);
                    $producteur->setEmail($row2['email']);
                    $producteur->setNom($row2['nom']);
                    $producteur->setPrenom($row2['prenom']);
                    $producteur->setMarque($row2['marque']);
                    $producteur->setLogo($row2['logo']);
                    $produit->setProducteur($producteur);
                }
                $produit->setPourcentage($row['pourcentage']);
                $produit->setQuantite($row['quantite']);
                if ($row['nutriscore_id']) {
                    $requete = "select score from nutriscore where id = " . $row['nutriscore_id'];
                    $result2 = $bdd->query($requete);
                    if ($result2) {
                        $row2 = $result2->fetchArray(SQLITE3_ASSOC);
                        $nutriscore = new Nutriscore();
                        $nutriscore->setScore($row2['score']);
                        $produit->setNutriscore($nutriscore);
                    }
                }
                $produit->setIngredients($row['ingredients']);
                $produit->setEstBio($row['est_bio']);
                $produit = self::getAlergenesByProduit($row['id'],$produit);
                $produit = self::getCategorieByProduit($row['id'],$produit);
                $produits[] = $produit;
            }
        }
        return $produits;
    }
    static public function getAlergenesByProduit($produitId,Produit $produit)
{
    $bdd = new SQLite3(BDD::$cheminDeLaBDD);
    $requete = "SELECT alergene.nom FROM alergene_produit 
                INNER JOIN alergene ON alergene_produit.alergene_id = alergene.id 
                WHERE alergene_produit.produit_id = :id";
    $stmt = $bdd->prepare($requete);
    $stmt->bindValue(':id', $produitId);
    $result = $stmt->execute();
    
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $alertegene = new Alergene();
        $alertegene->setNom($row['nom']);
        $produit->addAlergene($alertegene);
    }
    return $produit;
}
static public function getCategorieByProduit($produitId,Produit $produit)
{
    $bdd = new SQLite3(BDD::$cheminDeLaBDD);
    $requete = "SELECT categorie.nom FROM categorie_produit 
                INNER JOIN categorie ON categorie_produit.categorie_id = categorie.id 
                WHERE categorie_produit.produit_id = :id";
    $stmt = $bdd->prepare($requete);
    $stmt->bindValue(':id', $produitId);
    $result = $stmt->execute();
    
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $categorie = new Categorie();
        $categorie->setNom($row['nom']);
        $produit->addCategory($categorie);
    }
    return $produit;
}

    static public function Nutriscore()
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $requete = "select score from nutriscore";
        $result = $bdd->query($requete);
        $nutriscores = array();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $nutriscore = new Nutriscore();
                $nutriscore->setScore($row['score']);
                $nutriscores[] = $nutriscore;
            }
        }
        return $nutriscores;
    }
    static public function Alergene()
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $requete = "select nom from alergene";
        $result = $bdd->query($requete);
        $alergenes = array();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $alergene = new Alergene();
                $alergene->setNom($row['nom']);
                $alergenes[] = $alergene;
            }
        }
        return $alergenes;
    }
    static public function Categorie()
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $requete = "select nom from categorie";
        $result = $bdd->query($requete);
        $categories = array();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $categorie = new Categorie();
                $categorie->setNom($row['nom']);
                $categories[] = $categorie;
            }
        }
        return $categories;
    }
    static public function Admin()
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $requete = "select email,password,roles,nom,prenom from admin";
        $result = $bdd->query($requete);
        $admins = array();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $admin = new Admin();
                $admin->setEmail($row['email']);
                $admin->setPassword($row['password']);
                $admin->setRoles($row['roles']);
                $admin->setNom($row['nom']);
                $admin->setPrenom($row['prenom']);
                $admins[] = $admin;
            }
        }
        return $admins;
    }
    static public function ajouterCategorie(string $nom)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);

        // Récupère le contenu actuel
        $requete = $bdd->prepare("insert into categorie (nom) values (?)");
        $requete->bindValue(1, $nom, SQLITE3_TEXT);
        $result = $requete->execute();
        return $result ? 1 : -1;
    }

    static public function ajouterProducteur()
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $requete = "select siret,email,nom,prenom,marque,logo,adresse,is_verified,resiliation,archiver from producteur where is_verified = false";
        $result = $bdd->query($requete);
        $producteurs = array();
        if ($result) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $producteur = new Producteur();
                $producteur->setSiret($row['siret']);
                $producteur->setEmail($row['email']);
                $producteur->setNom($row['nom']);
                $producteur->setPrenom($row['prenom']);
                $producteur->setMarque($row['marque']);
                $producteur->setLogo($row['logo']);
                $producteur->setAdresse($row['adresse']);
                $producteur->setIsVerified($row['is_verified']);
                $producteur->setResiliation($row['resiliation']);
                $producteur->setArchiver($row['archiver']);
                $producteurs[] = $producteur;
            }
        }
        return $producteurs;
    }

    static public function ajouterProduit(string $nom, string $description, string $prix, string $image = null, Producteur $producteur, float $pourcentage, int $quantite, Nutriscore $nutriscore = null, string $ingredients, bool $est_bio, array $alergenes = [], array $categories = [])
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);

        // Récupère le contenu actuel
        $requete = $bdd->prepare("insert into produit (nom, description, prix, image, producteur, pourcentage, quantite, nutriscore, ingredients, est_bio) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $requete->bindValue(1, $nom, SQLITE3_TEXT);
        $requete->bindValue(2, $description, SQLITE3_TEXT);
        $requete->bindValue(3, $prix, SQLITE3_TEXT);
        $requete->bindValue(4, $image, SQLITE3_TEXT);
        $requete->bindValue(5, $producteur->getId(), SQLITE3_INTEGER);
        $requete->bindValue(6, $pourcentage, SQLITE3_FLOAT);
        $requete->bindValue(7, $quantite, SQLITE3_INTEGER);
        if ($nutriscore) {
            $requete->bindValue(8, $nutriscore->getId(), SQLITE3_INTEGER);
        } else {
            $requete->bindValue(8, null, SQLITE3_NULL);
        }
        $requete->bindValue(9, $ingredients, SQLITE3_TEXT);
        $requete->bindValue(10, (int) $est_bio, SQLITE3_INTEGER);
        $result = $requete->execute();
        if ($result) {
            $produitId = $bdd->lastInsertRowID();

            // Ajouter les allergènes
            foreach ($alergenes as $alergene) {
                $alergeneReq = $bdd->prepare("INSERT INTO alergene_produit (alergene_id, produit_id) VALUES (?, ?)");
                $alergeneReq->bindValue(1, $alergene->getId(), SQLITE3_INTEGER);
                $alergeneReq->bindValue(2, $produitId, SQLITE3_INTEGER);
                $alergeneReq->execute();
            }

            // Ajouter les catégories
            foreach ($categories as $categorie) {
                $categorieReq = $bdd->prepare("INSERT INTO categorie_produit (categorie_id, produit_id) VALUES (?, ?)");
                $categorieReq->bindValue(1, $categorie->getId(), SQLITE3_INTEGER);
                $categorieReq->bindValue(2, $produitId, SQLITE3_INTEGER);
                $categorieReq->execute();
            }
        }
        return $result ? 1 : -1;
    }



    /**
     * Définit la date de résiliation à aujourd'hui pour un producteur.
     */
    static public function resilierMaintenant(string $siret): int
    {
        $date = (new \DateTime())->format('Y-m-d');
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $upd = $bdd->prepare('UPDATE producteur SET resiliation = ? WHERE siret = ?');
        $upd->bindValue(1, $date, SQLITE3_TEXT);
        $upd->bindValue(2, $siret, SQLITE3_TEXT);
        $res = $upd->execute();
        return $res ? 1 : -1;
    }
    static public function resilier2Mois(string $siret): int
    {
        $date = (new \DateTime())->add(new \DateInterval('P2M'))->format('Y-m-d');
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $upd = $bdd->prepare('UPDATE producteur SET resiliation = ? WHERE siret = ?');
        $upd->bindValue(1, $date, SQLITE3_TEXT);
        $upd->bindValue(2, $siret, SQLITE3_TEXT);
        $res = $upd->execute();
        return $res ? 1 : -1;
    }
    static public function resilier6Mois(string $siret): int
    {
        $date = (new \DateTime())->add(new \DateInterval('P6M'))->format('Y-m-d');
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $upd = $bdd->prepare('UPDATE producteur SET resiliation = ? WHERE siret = ?');
        $upd->bindValue(1, $date, SQLITE3_TEXT);
        $upd->bindValue(2, $siret, SQLITE3_TEXT);
        $res = $upd->execute();
        return $res ? 1 : -1;
    }

    /**
     * Maintenance quotidienne des producteurs :
     * 1. Pour les records dont la date de resiliation est aujourd'hui,
     *    définit archiver = date d'aujourd'hui + 10 ans.
     * 2. Supprime complètement ceux dont archiver est aujourd'hui.
     * Retourne le nombre total de lignes modifiées ou supprimées.
     */
    static public function maintenanceProducteurs(): int
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $today = (new \DateTime())->format('Y-m-d');

        // étape 1 : planifier archivage dans 10 ans
        $future = (new \DateTime())->add(new \DateInterval('P10Y'))->format('Y-m-d');
        $upd = $bdd->prepare('UPDATE producteur SET archiver = ? WHERE resiliation = ?');
        $upd->bindValue(1, $future, SQLITE3_TEXT);
        $upd->bindValue(2, $today, SQLITE3_TEXT);
        $upd->execute();
        $count1 = $bdd->changes();

        // étape 2 : supprimer ceux dont archiver == today
        $del = $bdd->prepare('DELETE FROM producteur WHERE archiver = ?');
        $del->bindValue(1, $today, SQLITE3_TEXT);
        $del->execute();
        $count2 = $bdd->changes();

        return $count1 + $count2;
    }

    /**
     * Édite les informations d'un producteur identifié par son SIRET.
     * Tous les paramètres sauf siret sont optionnels.
     * Retourne 1 si succès, -1 si erreur.
     */
    static public function editerProducteur(
        string $siret,
        ?string $email = null,
        ?string $nom = null,
        ?string $prenom = null,
        ?string $marque = null,
        ?string $logo = null,
        ?string $adresse = null
    ): int {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);

        // Récupère les valeurs actuelles
        $requete = $bdd->prepare("
            SELECT email, nom, prenom, marque, logo, adresse 
            FROM producteur 
            WHERE siret = ?
        ");
        $requete->bindValue(1, $siret, SQLITE3_TEXT);
        $result = $requete->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            return -1; // Producteur non trouvé
        }

        // Utilise les nouvelles valeurs ou les anciennes
        $email = $email ?? $result['email'];
        $nom = $nom ?? $result['nom'];
        $prenom = $prenom ?? $result['prenom'];
        $marque = $marque ?? $result['marque'];
        $logo = $logo ?? $result['logo'];
        $adresse = $adresse ?? $result['adresse'];

        // Mise à jour
        $update = $bdd->prepare("
            UPDATE producteur 
            SET email = ?, nom = ?, prenom = ?, marque = ?, logo = ?, adresse = ? 
            WHERE siret = ?
        ");
        $update->bindValue(1, $email, SQLITE3_TEXT);
        $update->bindValue(2, $nom, SQLITE3_TEXT);
        $update->bindValue(3, $prenom, SQLITE3_TEXT);
        $update->bindValue(4, $marque, SQLITE3_TEXT);
        $update->bindValue(5, $logo, SQLITE3_TEXT);
        $update->bindValue(6, $adresse, SQLITE3_TEXT);
        $update->bindValue(7, $siret, SQLITE3_TEXT);

        $res = $update->execute();
        return $res ? 1 : -1;
    }

    /**
     * Édite les informations d'un produit identifié par son ID.
     * Tous les paramètres sauf id sont optionnels.
     * Retourne 1 si succès, -1 si erreur.
     */
    static public function editerProduit(
        int $id,
        ?string $nom = null,
        ?string $quantite = null,
        ?float $prix = null,
        ?float $pourcentage = null,
        ?string $description = null,
        ?bool $est_bio = null,
        ?string $image = null,
        ?string $ingredients = null
    ): int {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);

        // Récupère les valeurs actuelles
        $requete = $bdd->prepare("
            SELECT nom, quantite, prix, pourcentage, description, est_bio, image, ingredients 
            FROM produit 
            WHERE id = ?
        ");
        $requete->bindValue(1, $id, SQLITE3_INTEGER);
        $result = $requete->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            return -1; // Produit non trouvé
        }

        // Utilise les nouvelles valeurs ou les anciennes
        $nom = $nom ?? $result['nom'];
        $quantite = $quantite ?? $result['quantite'];
        $prix = $prix ?? $result['prix'];
        $pourcentage = $pourcentage ?? $result['pourcentage'];
        $description = $description ?? $result['description'];
        $est_bio = $est_bio ?? $result['est_bio'];
        $image = $image ?? $result['image'];
        $ingredients = $ingredients ?? $result['ingredients'];

        // Mise à jour
        $update = $bdd->prepare("
            UPDATE produit 
            SET nom = ?, quantite = ?, prix = ?, pourcentage = ?, description = ?, est_bio = ?, image = ?, ingredients = ? 
            WHERE id = ?
        ");
        $update->bindValue(1, $nom, SQLITE3_TEXT);
        $update->bindValue(2, $quantite, SQLITE3_TEXT);
        $update->bindValue(3, $prix, SQLITE3_FLOAT);
        $update->bindValue(4, $pourcentage, SQLITE3_FLOAT);
        $update->bindValue(5, $description, SQLITE3_TEXT);
        $update->bindValue(6, $est_bio, SQLITE3_INTEGER);
        $update->bindValue(7, $image, SQLITE3_TEXT);
        $update->bindValue(8, $ingredients, SQLITE3_TEXT);
        $update->bindValue(9, $id, SQLITE3_INTEGER);

        $res = $update->execute();
        return $res ? 1 : -1;
    }

    /**
     * Édite un produit depuis sa catégorie.
     * Paramètres optionnels.
     * Retourne 1 si succès, -1 si erreur.
     */
    static public function editerCategorie(int $id, ?string $nom = null): int
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);

        // Récupère la valeur actuelle
        $requete = $bdd->prepare("SELECT nom FROM categorie WHERE id = ?");
        $requete->bindValue(1, $id, SQLITE3_INTEGER);
        $result = $requete->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            return -1; // Catégorie non trouvée
        }

        // Utilise la nouvelle valeur ou l'ancienne
        $nom = $nom ?? $result['nom'];

        // Mise à jour
        $update = $bdd->prepare("UPDATE categorie SET nom = ? WHERE id = ?");
        $update->bindValue(1, $nom, SQLITE3_TEXT);
        $update->bindValue(2, $id, SQLITE3_INTEGER);

        $res = $update->execute();
        return $res ? 1 : -1;
    }

    /**
     * Édite les informations d'un admin identifié par son ID.
     * Tous les paramètres sauf id sont optionnels.
     * Retourne 1 si succès, -1 si erreur.
     */
    static public function editerAdmin(
        int $id,
        ?string $email = null,
        ?string $nom = null,
        ?string $prenom = null,
        ?string $password = null,
        ?string $roles = null
    ): int {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);

        // Récupère les valeurs actuelles
        $requete = $bdd->prepare("
            SELECT email, nom, prenom, password, roles 
            FROM admin 
            WHERE id = ?
        ");
        $requete->bindValue(1, $id, SQLITE3_INTEGER);
        $result = $requete->execute()->fetchArray(SQLITE3_ASSOC);

        if (!$result) {
            return -1; // Admin non trouvé
        }

        // Utilise les nouvelles valeurs ou les anciennes
        $email = $email ?? $result['email'];
        $nom = $nom ?? $result['nom'];
        $prenom = $prenom ?? $result['prenom'];
        $password = $password ?? $result['password'];
        $roles = $roles ?? $result['roles'];

        // Mise à jour
        $update = $bdd->prepare("
            UPDATE admin 
            SET email = ?, nom = ?, prenom = ?, password = ?, roles = ? 
            WHERE id = ?
        ");
        $update->bindValue(1, $email, SQLITE3_TEXT);
        $update->bindValue(2, $nom, SQLITE3_TEXT);
        $update->bindValue(3, $prenom, SQLITE3_TEXT);
        $update->bindValue(4, $password, SQLITE3_TEXT);
        $update->bindValue(5, $roles, SQLITE3_TEXT);
        $update->bindValue(6, $id, SQLITE3_INTEGER);

        $res = $update->execute();
        return $res ? 1 : -1;
    }

    // methods for producteur suspension and deletion
    static public function supprimerProduit($id)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $sup = $bdd->prepare('DELETE FROM produit WHERE id = ?');
        $sup->bindValue(1, $id, SQLITE3_INTEGER);
        $res = $sup->execute();
        if ($res) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo $error;
            return -1;
        }
    }

    // methods for producteur suspension and deletion
    static public function supprimerCategorie($id)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $sup = $bdd->prepare('DELETE FROM categorie WHERE id = ?');
        $sup->bindValue(1, $id, SQLITE3_INTEGER);
        $res = $sup->execute();
        if ($res) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo $error;
            return -1;
        }
    }

    static public function supprimerAdmin($id)
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $sup = $bdd->prepare('DELETE FROM admin WHERE id = ?');
        $sup->bindValue(1, $id, SQLITE3_INTEGER);
        $res = $sup->execute();
        if ($res) {
            return 1;
        } else {
            $error = $bdd->lastErrorMsg();
            echo $error;
            return -1;
        }
    }

    static public function imagesUtilisees()
    {
        $bdd = new SQLite3(BDD::$cheminDeLaBDD);
        $result = $bdd->query("SELECT lien_image FROM contenue WHERE lien_image IS NOT NULL AND lien_image != ''");
        $usedImages = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $usedImages[] = basename($row['lien_image']);
        }
        return $usedImages;
    }
}