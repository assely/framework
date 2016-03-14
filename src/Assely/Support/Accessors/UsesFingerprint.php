<?php

namespace Assely\Support\Accessors;

use Illuminate\Support\Str;

trait UsesFingerprint
{
    /**
     * Fingerprint value.
     *
     * @var string
     */
    protected $fingerprint;

    /**
     * Gets the value of fingerprint.
     *
     * @return mixed
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    /**
     * Sets the value of fingerprint.
     *
     * @return self
     */
    public function setFingerprint($fingerprint = null)
    {
        if (isset($fingerprint)) {
            $this->fingerprint = $fingerprint;
        } else {
            $this->fingerprint = "assely-" . Str::random();
        }

        return $this;
    }
}
