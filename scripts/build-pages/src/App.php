<?php

namespace myproject;

class App {
  public static function instance() {
    return new self();
  }

  public function run() {
    // Make sure each section has a page explaining how to use it.
    $this->sections()->build();
    // Make sure each multilingual page has its translations built.
    $this->multilingualPages()->build();
    // $this->articles()->build();
    // $this->projects()->build();
  }

  public function multilingualPages() {
    return new MultilingualPages();
  }

  public function sections() {
    return new Sections();
  }
  public function articles() {
    return new Articles();
  }
  public function projects() {
    return new Projects();
  }

}
