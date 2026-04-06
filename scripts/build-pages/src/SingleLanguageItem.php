<?php

namespace myproject;

class SingleLanguageItem {
  protected MultilingualItem $multilingualItem;
  protected array $mapping;

  public function __construct(
    MultilingualItem $multilingualItem,
    array $mapping,
  ) {
    $this->multilingualItem = $multilingualItem;
    $this->mapping = $mapping;
  }

  public function map(string $key) : string {
    $candidate = $this->mapping[$key] ?? '';
    if (!$candidate) {
      throw new \Exception("No mapping for $key");
    }
    return $candidate;
  }

  public function item() : MultilingualItem {
    return $this->multilingualItem;
  }
  public function redirects() {
    if ($this->item()->has($this->map('redirects'))) {
      return $this->item()->structure()[$this->map('redirects')];
    }
    return [];
  }

  public function filename() {
    $this->item()->date() . '-' . self::slugify($this->title()) . '.md';
  }

}
