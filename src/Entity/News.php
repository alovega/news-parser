<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 * @ORM\Table(name="news",indexes={@Index(name="search_idx", fields={"title", "created_at", "updated_at", "date_added"})})
 *  @ORM\HasLifecycleCallbacks
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_url;

    /**
     * @ORM\Column(type="datetime",  nullable=true)
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime",  nullable=true)
     */
    protected $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */

    public function onPrePersist()
    {
        $this->created_at = new \DateTime("now");
    }

     /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */

    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
    }
}
