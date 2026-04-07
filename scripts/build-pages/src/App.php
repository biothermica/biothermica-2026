<?php

namespace myproject;

class App {
  public static function instance() {
    return new self();
  }

  public function run() {
    // Make sure each section has a page explaining how to use it.
    foreach ([
      'articles' => [],
    ] as $collection => $options) {
      $this->collection($collection, $options)->build();
    }

    $this->sections()->build();
    // Make sure each multilingual page has its translations built.
    $this->multilingualPages('pages')->build();
    $this->multilingualPages('generated_pages')->build();
    // $this->articles()->build();
    // $this->projects()->build();
  }

  public function collection($id, $options) {
    return new Collection($id, $options);
  }

  public function multilingualPages($type) {
    return new MultilingualPages($type);
  }

  public function sections() {
    return new Sections();
  }

}
