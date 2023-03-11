<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomClient = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateDisponibilite = null;

    #[ORM\Column]
    private ?int $nbrAppareil = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    private ?Villes $ville = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    private ?Appareil $typeAppareil = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    private ?Statut $statut = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    private ?User $manager = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): self
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateDisponibilite(): ?\DateTimeImmutable
    {
        return $this->dateDisponibilite;
    }

    public function setDateDisponibilite(\DateTimeImmutable $dateDisponibilite): self
    {
        $this->dateDisponibilite = $dateDisponibilite;

        return $this;
    }

    public function getNbrAppareil(): ?int
    {
        return $this->nbrAppareil;
    }

    public function setNbrAppareil(int $nbrAppareil): self
    {
        $this->nbrAppareil = $nbrAppareil;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getVille(): ?Villes
    {
        return $this->ville;
    }

    public function setVille(?Villes $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTypeAppareil(): ?Appareil
    {
        return $this->typeAppareil;
    }

    public function setTypeAppareil(?Appareil $typeAppareil): self
    {
        $this->typeAppareil = $typeAppareil;

        return $this;
    }

    public function getStatut(): ?statut
    {
        return $this->statut;
    }

    public function setStatut(?statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

}
