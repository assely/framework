<?php

namespace Assely\Nonce;

use Illuminate\Http\Request;

class NonceFactory
{
    /**
     * Construct factory.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create nonce.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return string
     */
    public function create($slug)
    {
        return $this->make($slug)->make();
    }

    /**
     * Render nonce form fields.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return string
     */
    public function fields($slug)
    {
        return $this->make($slug)->render();
    }

    /**
     * Verify nonce value.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return boolean
     */
    public function verify($slug)
    {
        $nonce = $this->make($slug);

        return $nonce->check($this->request->input($nonce->getSlug()));
    }

    /**
     * Verify nonce value.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return boolean
     */
    public function check($slug, $token)
    {
        return $this->make($slug)->check($token);
    }

    /**
     * Create Nonce instance.
     *
     * @param  string $slug
     *
     * @return \Assely\Nonce\Nonce
     */
    private function make($slug)
    {
        return new Nonce($slug);
    }
}
