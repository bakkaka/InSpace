<?php

namespace App\Entity;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\ExecutionContextInterface;


/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    // use TimestampableEntity;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;






    /**
     *  @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez remplir ce champs,Le champs Content ne peut étre vide!")
     * @ORM\Column(name="text", type="text")
     */
    private $content;


    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez remplir ce champs,Le titre ne peut étre vide!")
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image",  cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $image;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comments;




    /**
     * @ORM\Column(type="integer")
     */
    private $heartCount = 0;
	
	 /**
     * @ORM\Column(type="integer")
     */
    private $visitCount = 0;
	

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="articles")
     * @Assert\NotNull(message="Please set an author")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $specificLocationName;

    /*
     * @ORM\Column(type="string", length=255, nullable=true)
     *
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
    private $specificLocationName;
*/


    public function __construct()
    {

        $this->date = new DateTime();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();

       // $this->articlecategories = new ArrayCollection();

       // $this->horaires = new ArrayCollection();
       // $this->cities = new ArrayCollection();

        $this->comments = new ArrayCollection();
        //$this->tags = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = new DateTime();

        return $this;
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



    // public function getContent(): ?string
    //{
    //    return $this->content;
    //}

    public function getContent($length = null)
    {
        if (false === is_null($length) && $length > 0)
            return substr($this->content, 0, $length);
        else
            return $this->content;
    }


    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }



    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }



    







    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

   

   
    public function isPublished(): bool
    {
        return $this->publishedAt !== null;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): self
    {
        $this->publishedAt = new DateTime("now");

        return $this;
    }

    public function getHeartCount(): ?int
    {
        return $this->heartCount;
    }

    public function incrementHeartCount(): self
    {
        $this->heartCount = $this->heartCount + 1;
        return $this;
    }

    public function setHeartCount(int $heartCount): self
    {
        $this->heartCount = $heartCount;

        return $this;
    }

   
    

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;
       // if (!$this->location || $this->location === 'interstellar_space') {
       //     $this->setSpecificLocationName(null);
       // }

        return $this;
    }

    public function getSpecificLocationName(): ?string
    {
        return $this->specificLocationName;
    }

    public function setSpecificLocationName(?string $specificLocationName): self
    {
        $this->specificLocationName = $specificLocationName;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getVisitCount(): ?int
    {
        return $this->visitCount;
    }
	
	 public function incrementVisitCount(): self
    {
        $this->visitCount = $this->visitCount + 1;
        return $this;
    }

    public function setVisitCount(int $visitCount): self
    {
        $this->visitCount = $visitCount;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }






}
