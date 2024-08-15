<?php

namespace App\Entity;

use App\Repository\AdRepository;
use App\Validator as AdsValidator;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity, that describes ad's fields.
 *
 * <pre>
 *      $ad = (new Ad)
 *          ->setText($text)
 *          ->setPrice($price)
 *          ->setBanner($banner);
 * </pre>
 *
 * @since   0.0.1
 *
 * @author  WorHyako
 */
#[AdsValidator\AdFields]
#[ORM\Entity(repositoryClass: AdRepository::class)]
class Ad
{
    public static int $minPrice = 1;

    public static int $minBannerLength = 1;

    public static int $minTextLength = 1;

    public static int $showLimit = 4;

    /**
     * @var int|null Ad id in DB.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var int Ad price.
     *          <p/>
     *          Should have non-zero positive value.
     */
    #[ORM\Column]
    private int $price = 0;

    /**
     * @var string|null Ad banner reference.
     *                  <p/>
     *                  Should have length greater 10.
     */
    #[ORM\Column(length: 255)]
    private ?string $banner = null;

    /**
     * @var string|null Ad text.
     *                  <p/>
     *                  Should have length greater 10.
     */
    #[ORM\Column(length: 255)]
    private ?string $text = null;

    /**
     * @var int Ad show count.
     *          <p/>
     *          Should have non-zero positive value.
     */
    #[ORM\Column]
    private int $showCount = 0;

    /**
     * Compares two objects with no account id.
     *
     * <pre>
     *          $isEqual = Ad::cmpData($firstAd, $secondId);
     * </pre>
     *
     * @param Ad|null $one The first object to compare.
     *
     * @param Ad|null $two The second object to compare.
     *
     * @return bool <code>true</code> if objects are equal
     *              <p/>
     *              <code>true</code> if objects aren't equal.
     */
    public static function cmpData(Ad|null $one, Ad|null $two): bool
    {
        return $one->getPrice() == $two->getPrice()
            && $one->getBanner() == $two->getBanner()
            && $one->getShowCount() == $two->getShowCount()
            && $one->getText() == $two->getText();
    }

#region Accessors/Mutators

    public function incrementShowCount(): static
    {
        $this->showCount++;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(string|null $banner): static
    {
        $this->banner = $banner;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string|null $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getShowCount(): ?int
    {
        return $this->showCount;
    }

    public function setShowCount(int $showCount): static
    {
        $this->showCount = $showCount;

        return $this;
    }

#endregion Accessors/Mutators

}
