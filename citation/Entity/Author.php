<?php
declare(strict_types=1);

namespace Entity;

use JsonSerializable;

/**
 * Class Author
 * Représente un auteur avec plusieurs citations.
 */
class Author implements JsonSerializable
{
    private ?int $id;
    private string $prenom;
    private string $nom;
    private ?int $anneeNaissance;

    /** @var Citation[]*/
    private array $citations = [];

    /**
     * @param int|null $id
     * @param string $prenom
     * @param string $nom
     * @param int|null $anneeNaissance
     */
    public function __construct(?int $id, string $prenom, string $nom, ?int $anneeNaissance = null)
    {
        if ($prenom === '' || $nom === '') {
            throw new \InvalidArgumentException('Prénom et nom doivent être non vides.');
        }
        $this->id = $id;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->anneeNaissance = $anneeNaissance;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        if ($prenom === '') {
            throw new \InvalidArgumentException('Prénom vide.');
        }
        $this->prenom = $prenom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        if ($nom === '') {
            throw new \InvalidArgumentException('Nom vide.');
        }
        $this->nom = $nom;
    }

    public function getAnneeNaissance(): ?int
    {
        return $this->anneeNaissance;
    }

    public function setAnneeNaissance(?int $annee): void
    {
        $this->anneeNaissance = $annee;
    }

    /**
     * Retourne les citations associées à cet auteur.
     * @return Citation[]
     */
    public function getCitations(): array
    {
        return $this->citations;
    }

    /**
     * Ajoute une citation à cet auteur (API publique). La méthode délègue la gestion
     * de la relation à la citation afin d'éviter les incohérences.
     */
    public function addCitation(Citation $c): void
    {
        $c->setAuteur($this);
    }

    /**
     * Retire une citation de cet auteur (API publique).
     */
    public function removeCitation(Citation $c): void
    {
        if ($c->getAuteur() === $this) {
            $c->setAuteur(null);
        } else {
            // Si la citation n'a pas cet auteur, s'assurer qu'elle n'est pas dans le tableau
            $this->_detachCitation($c);
        }
    }

    /**
     * Méthode interne : attacher une citation sans provoquer de boucle.
     * Utilisée par Citation::setAuteur().
     */
    public function _attachCitation(Citation $c): void
    {
        foreach ($this->citations as $existing) {
            if ($existing === $c) {
                return;
            }
        }
        $this->citations[] = $c;
    }

    /**
     * Méthode interne : détacher une citation sans provoquer de boucle.
     */
    public function _detachCitation(Citation $c): void
    {
        $index = array_search($c, $this->citations, true);
        if ($index !== false) {
            array_splice($this->citations, (int)$index, 1);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'prenom' => $this->prenom,
            'nom' => $this->nom,
            'anneeNaissance' => $this->anneeNaissance,
            'citations' => array_map(static function (Citation $c) { return $c->getId(); }, $this->citations),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
