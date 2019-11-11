<?php

namespace MPNDEV\D8TDD\Http;

use Symfony\Component\HttpFoundation\Request;

class JsonRequest {

  public $url;
  public $method;
  public $parameters;
  public $cookies;
  public $files;
  public $server;
  public $content;

  /**
   * JsonRequest constructor.
   *
   * @param $url
   */
  private function __construct($url) {
    $this->url = $url;
    $this->method = 'GET';
    $this->parameters = [];
    $this->cookies = [];
    $this->files = [];
    $this->server = [];
    $this->content = [];
  }

  /**
   * @param $url
   *
   * @return static
   */
  public static function to($url) {
    return new static($url);
  }

  /**
   * @param $method
   *
   * @return $this
   */
  public function using($method) {
    $this->method = $method;
    return $this;
  }

  /**
   * @param $parameters
   *
   * @return $this
   */
  public function withParameters($parameters) {
    $this->parameters = $parameters;
    return $this;
  }

  /**
   * @param $cookies
   *
   * @return $this
   */
  public function withCookie($cookies) {
    $this->cookies = $cookies;
    return $this;
  }

  /**
   * @param $files
   *
   * @return $this
   */
  public function withFiles($files) {
    $this->files = $files;
    return $this;
  }

  /**
   * @param $server
   *
   * @return $this
   */
  public function withServer($server) {
    $this->server = $server;
    return $this;
  }

  /**
   * @param $content
   *
   * @return $this
   */
  public function withContent($content) {
    $this->content = $content;
    return $this;
  }

  /**
   * @return mixed
   */
  public function send() {
    $kernel = \Drupal::getContainer()->get('http_kernel');
    $request = Request::create($this->url, $this->method, $this->parameters, $this->cookies, $this->files, $this->server, $this->content);
    $request->headers->set('Content-Type', 'application/json');
    return $kernel->handle($request);
  }

}
