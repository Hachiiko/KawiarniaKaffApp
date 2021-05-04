<?php

namespace App\Entity;

use App\Enum\TableReservationStatusEnum;
use App\Repository\TableReservationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableReservationRepository::class)
 */
class TableReservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Table::class)
     */
    private Table $table;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tableReservations")
     */
    private User $owner;

    /**
     * @ORM\Column(type="table_reservation_status")
     */
    private TableReservationStatusEnum $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $dateFrom;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $dateTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $phone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTable(): Table
    {
        return $this->table;
    }

    public function setTable($table): void
    {
        $this->table = $table;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): void
    {
        $this->owner = $owner;
    }

    public function getStatus(): TableReservationStatusEnum
    {
        return $this->status;
    }

    public function setStatus(TableReservationStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getDateFrom(): DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function setDateFrom(DateTimeInterface $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    public function getDateTo(): DateTimeInterface
    {
        return $this->dateTo;
    }

    public function setDateTo(DateTimeInterface $dateTo): void
    {
        $this->dateTo = $dateTo;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}
