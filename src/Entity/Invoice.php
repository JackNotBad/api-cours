<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\InvoiceRepository;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;


#[ApiResource(

)]
#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ApiResource(
    paginationEnabled: true,
    paginationItemsPerPage: 5,
    order: ['amount' => 'DESC'],
    normalizationContext: ['groups' => ['invoices_read']],
            )
]

class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['invoices_read', 'customer_read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['invoices_read', 'customer_read'])]
    private ?float $amount = null;

    #[ORM\Column]
    #[Groups(['invoices_read', 'customer_read'])]
    private ?\DateTime $sentAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['invoices_read', 'customer_read'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['invoices_read'])]
    private ?Customer $customer = null;

    #[ORM\Column]
    #[Groups(['invoices_read', 'customer_read'])]
    private ?int $chrono = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('amount', new Assert\NotBlank(message: 'test message amount NotBlank'));
        $metadata->addPropertyConstraint('amount', new Assert\Type(
                                                                        type: 'integer',
                                                                        message: 'test message amount Type'
                                                                    ));
        $metadata->addPropertyConstraint('sentAt', new Assert\DateTime(
                                                                            format: 'Y-m-d',
                                                                            message: 'test message sentAt'
                                                                        ));
        $metadata->addPropertyConstraint('status', new Assert\NotBlank(message: "test message status NotBlank"));    
        $metadata->addPropertyConstraint('status', new Assert\Choice(
                                                                        choices: ['SENT', 'PAID', 'CANCELLED'],
                                                                        message: 'test message status Choice.',
                                                                    ));    
        $metadata->addPropertyConstraint('customer', new Assert\NotBlank(message: "test message customer NotBlank"));    
        $metadata->addPropertyConstraint('chrono', new Assert\NotBlank(message: "test message chrono NotBlank"));    
        $metadata->addPropertyConstraint('chrono', new Assert\Type(
                                                                    type: 'integer',
                                                                    message: 'test message chrono Type'
                                                                ));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTime $sentAt): static
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getChrono(): ?int
    {
        return $this->chrono;
    }

    public function setChrono(int $chrono): static
    {
        $this->chrono = $chrono;

        return $this;
    }
}
