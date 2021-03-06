<?php

declare(strict_types=1);

/*
 * This file is part of the Geocoder package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Geocoder\Model;

use Geocoder\Location;

/**
 * A class that builds a Location or any of its subclasses.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class AddressBuilder
{
    /**
     * @var string
     */
    private $providedBy;

    /**
     * @var Coordinates|null
     */
    private $coordinates;

    /**
     * @var Bounds|null
     */
    private $bounds;

    /**
     * @var string|null
     */
    private $streetNumber;

    /**
     * @var string|null
     */
    private $streetName;

    /**
     * @var string|null
     */
    private $locality;

    /**
     * @var string|null
     */
    private $postalCode;

    /**
     * @var string|null
     */
    private $subLocality;

    /**
     * @var array
     */
    private $adminLevels = [];

    /**
     * @var string|null
     */
    private $country;

    /**
     * @var string|null
     */
    private $countryCode;

    /**
     * @var string|null
     */
    private $timezone;

    /**
     * @param string $providedBy
     */
    public function __construct(string $providedBy)
    {
        $this->providedBy = $providedBy;
    }

    /**
     * @param string $class
     *
     * @return Address
     */
    public function build($class = Address::class)
    {
        if (!is_a($class, Address::class, true)) {
            throw new \LogicException('First parameter to LocationBuilder::build must be a class name extending Geocoder\Model\Address');
        }

        return new $class(
            $this->providedBy,
            new AdminLevelCollection($this->adminLevels),
            $this->coordinates,
            $this->bounds,
            $this->streetNumber,
            $this->streetName,
            $this->postalCode,
            $this->locality,
            $this->subLocality,
            new Country($this->country, $this->countryCode),
            $this->timezone
        );
    }

    /**
     * @param float $south
     * @param float $west
     * @param float $north
     * @param float $east
     *
     * @return AddressBuilder
     */
    public function setBounds($south, $west, $north, $east)
    {
        try {
            $this->bounds = new Bounds($south, $west, $north, $east);
        } catch (\InvalidArgumentException $e) {
            $this->bounds = null;
        }

        return $this;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return AddressBuilder
     */
    public function setCoordinates($latitude, $longitude)
    {
        try {
            $this->coordinates = new Coordinates($latitude, $longitude);
        } catch (\InvalidArgumentException $e) {
            $this->coordinates = null;
        }

        return $this;
    }

    /**
     * @param int    $level
     * @param string $name
     * @param string $code
     *
     * @return AddressBuilder
     */
    public function addAdminLevel($level, $name, $code)
    {
        $this->adminLevels[] = new AdminLevel($level, $name, $code);

        return $this;
    }

    /**
     * @param null|string $streetNumber
     *
     * @return AddressBuilder
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * @param null|string $streetName
     *
     * @return AddressBuilder
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;

        return $this;
    }

    /**
     * @param null|string $locality
     *
     * @return AddressBuilder
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * @param null|string $postalCode
     *
     * @return AddressBuilder
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @param null|string $subLocality
     *
     * @return AddressBuilder
     */
    public function setSubLocality($subLocality)
    {
        $this->subLocality = $subLocality;

        return $this;
    }

    /**
     * @param array $adminLevels
     *
     * @return AddressBuilder
     */
    public function setAdminLevels($adminLevels)
    {
        $this->adminLevels = $adminLevels;

        return $this;
    }

    /**
     * @param null|string $country
     *
     * @return AddressBuilder
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param null|string $countryCode
     *
     * @return AddressBuilder
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @param null|string $timezone
     *
     * @return AddressBuilder
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function setValue($name, $value)
    {
        $this->$name = $value;

        return $this;
    }

    /**
     * @param $name
     * @param null $default
     */
    public function getValue($name, $default = null)
    {
        return $this->$name ?? $default;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasValue($name)
    {
        return property_exists($this, $name);
    }
}
