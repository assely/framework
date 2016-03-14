<?php

namespace Assely\Nonce;

use Assely\Support\Accessors\HasSlug;

class Nonce
{
    use HasSlug;

    /**
     * Nonce key.
     *
     * @var string
     */
    private $key;

    /**
     * Construnct nonce.
     *
     * @param string $slug
     * @param array $arguments
     */
    public function __construct($slug)
    {
        $this->setSlug($slug);
        $this->setKey($slug);
    }

    /**
     * Generate nonce value.
     *
     * @return string
     */
    public function make()
    {
        return wp_create_nonce($this->getSlug());
    }

    /**
     * Generate nonce form fields.
     *
     * @return string
     */
    public function render()
    {
        return wp_nonce_field($this->getSlug(), $this->getKey(), true, false);
    }

    /**
     * Check if nonce value is valid.
     *
     * @return boolean
     */
    public function check($token)
    {
        return wp_verify_nonce($token, $this->getSlug());
    }

    /**
     * Verify nonce value. Abort if it is not valid.
     *
     * @throws \Assely\Nonce\NonceException
     *
     * @return boolean
     */
    public function checkOrFail($token)
    {
        if ($status = $this->check($token)) {
            throw new NonceException("Nonce [{$this->getSlug()}] is invalid.");
        }

        return $status;
    }

    /**
     * Gets the nonce key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the value of key.
     *
     * @param mixed $key the key
     *
     * @return self
     */
    private function setKey($key)
    {
        $this->key = "_{$key}-nonce";

        return $this;
    }
}
