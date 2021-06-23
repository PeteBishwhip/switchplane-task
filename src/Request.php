<?php

namespace Task;

use Psr\Http\Message\ServerRequestInterface;

class Request
{
    protected array $attributes = [];

    public static function factory(ServerRequestInterface $serverRequest): static
    {
        return (new self())->sortRequestData($serverRequest);
    }

    public function sortRequestData(ServerRequestInterface $serverRequest): static
    {
        $this->attributes = $serverRequest->getQueryParams();

        foreach ($this->attributes as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    public function has(string $attribute)
    {
        return array_key_exists($attribute, $this->attributes);
    }

    public function get(string $attribute, $default = null)
    {
        if ($this->has($attribute)) {
            return $this->attributes[$attribute];
        }

        return $default;
    }
}