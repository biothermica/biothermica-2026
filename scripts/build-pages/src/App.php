<?php

namespace myproject;

class App {
  public static function instance() {
    return new self();
  }

  public function run(string $betaBasePath) {
    // Make sure each section has a page explaining how to use it.
    $this->sections()->build($betaBasePath);
    // Make sure each multilingual page has its translations built.
    $this->multilingualPages()->build($betaBasePath);
  }

  public function multilingualPages() {
    return new MultilingualPages();
  }

  public function sections() {
    return new Sections();
  }
}
