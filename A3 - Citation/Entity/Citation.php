<?php
declare(strict_types=1);

namespace Entity;

use JsonSerializable;

/**
 * Class Citation
 * Représente une citation appartenant à un auteur.
 */
class Citation implements JsonSerializable
{
    private ?int $id;
    private string $texte;
    private \DateTimeImmutable $dateAjout;
    private ?Author $auteur = null;

    public function __construct(?int $id, string $texte, ?\DateTimeImmutable $dateAjout = null)
    {
        if ($texte === '') {
            throw new \InvalidArgumentException('Le texte de la citation doit être non vide.');
        }
        $this->id = $id;
        $this->texte = $texte;
        $this->dateAjout = $dateAjout ?? new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexte(): string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): void
    {
        if ($texte === '') {
            throw new \InvalidArgumentException('Le texte de la citation doit être non vide.');
        }
        $this->texte = $texte;
    }

    public function getDateAjout(): \DateTimeImmutable
    {
        return $this->dateAjout;
    }

    public function getAuteur(): ?Author
    {
        return $this->auteur;
    }

    /**
     * Définit l'auteur de la citation. Gère la relation bidirectionnelle en
     * utilisant les méthodes internes d'Author pour éviter les boucles.
     */
    public function setAuteur(?Author $a): void
    {
        if ($this->auteur === $a) {
            return;
        }

        $previous = $this->auteur;
        $this->auteur = $a;

        if ($previous !== null) {
            $previous->_detachCitation($this);
        }

        if ($a !== null) {
            $a->_attachCitation($this);
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'texte' => $this->texte,
            'dateAjout' => $this->dateAjout->format(DATE_ATOM),
            'auteur' => $this->auteur ? $this->auteur->getId() : null,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
