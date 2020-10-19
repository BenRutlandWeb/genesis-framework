<?php

namespace Genesis\Http;

class Response
{
    /**
     * An array of headers to send with the response
     *
     * @var array
     */
    protected $headers = [];

    /**
     * The response body
     *
     * @var string
     */
    protected $content;

    /**
     * Set the content
     *
     * @param string $content
     *
     * @return self
     */
    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set a header
     *
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set a view as the response
     *
     * @param string $view
     *
     * @return self
     */
    public function view(string $view): self
    {
        return $this->content(view($view));
    }

    /**
     * Return a json response
     *
     * @param mixed $data
     *
     * @return self
     */
    public function json($data): self
    {
        $this->header('Content-Type', 'application/json');

        $this->content = json_encode($data);

        return $this;
    }

    /**
     * Send the response
     *
     * @return void
     */
    public function send(): void
    {
        foreach ($this->headers as $key => $value) {
            header("{$key}: $value");
        }
        die($this->content);
    }

    /**
     * Send the response as a string
     *
     * @return string
     */
    public function __toString()
    {
        $this->send();
    }
}
