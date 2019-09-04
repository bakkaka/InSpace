<?php
// src/Entity/User.php
namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function in_array;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")

 * @ORM\Table(name="leimen_user")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     ** @Groups("main")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $fullName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiToken", mappedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $apitokens;




    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user", cascade={"remove"})
     */
    private $comments;



    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="datetime")
     */
    private $agreedTermsAt;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
    /* @Assert\Valid()
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private $articles;
	
	/**
     * @ORM\OneToOne(targetEntity="App\Entity\Author")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotNull(message="Please set an author")
     */
    private $author;

    public function __construct()
    {
        $this->roles = array('ROLE_ADMIN');
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->apitokens = new ArrayCollection();
    }

    // other properties and methods

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getFullName()
    {
        return $this->fullName;
    }
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }


    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set image
     *
     * @param Image $image
     * @return User
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return mixed
	 * @return User
     */

    public function __toString()
    {
        return $this->getUsername();
		return $this;

        
    }



    public function isUser()
    {
        return in_array('ROLE_USER', $this->getRoles());
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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getAgreedTermsAt(): ?DateTimeInterface
    {
        return $this->agreedTermsAt;
    }

    public function setAgreedTermsAt(DateTimeInterface $agreedTermsAt): self
    {
        $this->agreedTermsAt = new Datetime();

        return $this;
    }
    public function agreeToTerms()
    {
        $this->agreedTermsAt = new DateTime();
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ApiToken[]
     */
    public function getApitokens(): Collection
    {
        return $this->apitokens;
    }

    public function addApitoken(ApiToken $apitoken): self
    {
        if (!$this->apitokens->contains($apitoken)) {
            $this->apitokens[] = $apitoken;
            $apitoken->setUser($this);
        }

        return $this;
    }

    public function removeApitoken(ApiToken $apitoken): self
    {
        if ($this->apitokens->contains($apitoken)) {
            $this->apitokens->removeElement($apitoken);
            // set the owning side to null (unless already changed)
            if ($apitoken->getUser() === $this) {
                $apitoken->setUser(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): self
    {
        $this->author = $author;

        return $this;
    }

}