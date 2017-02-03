<?php

class WP_Widget
{
    //
}

class WP
{
    public function __construct($request = '')
    {
        $this->request = $request;
    }
}

class WP_Query
{
    public function __construct($query_vars = [])
    {
        $this->query_vars = $query_vars;
    }
}
