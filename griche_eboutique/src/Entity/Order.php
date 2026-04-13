<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'shop_order')]
#[ORM\Index(columns: ['user_id'], name: 'idx_order_user')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'json')]
    private array $items = [];

    #[ORM\Column]
    private int $subtotalCents = 0;

    #[ORM\Column]
    private int $shippingCents = 0;

    #[ORM\Column]
    private int $totalCents = 0;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function getSubtotalCents(): int
    {
        return $this->subtotalCents;
    }

    public function setSubtotalCents(int $subtotalCents): self
    {
        $this->subtotalCents = $subtotalCents;
        return $this;
    }

    public function getShippingCents(): int
    {
        return $this->shippingCents;
    }

    public function setShippingCents(int $shippingCents): self
    {
        $this->shippingCents = $shippingCents;
        return $this;
    }

    public function getTotalCents(): int
    {
        return $this->totalCents;
    }

    public function setTotalCents(int $totalCents): self
    {
        $this->totalCents = $totalCents;
        return $this;
    }
}

