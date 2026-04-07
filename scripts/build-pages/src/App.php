<?php

namespace myproject;

class App {
  public static function instance() {
    return new self();
  }

  public function slugify($str, $default = '') {
    $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($str));
    return $slug ?: $default;
  }

  public function run() {
    // Make sure each section has a page explaining how to use it.
    foreach ([
      'articles' => function($orig) {
        return [
          'title' => [
            'en' => $orig['title_en'] ?? '',
            'fr' => $orig['title'] ?? '',
          ],
          'date' => $orig['mydate'] ?? '',
          'paths' => [
            'en' => '/articles/' . ($orig['mydate'] ? ($orig['mydate'] . '/') : '') . $this->slugify($orig['title_en'] ?? '', 'article') . '/',
            'fr' => '/fr/articles/' . ($orig['mydate'] ? ($orig['mydate'] . '/') : '') . $this->slugify($orig['title'] ?? '', 'article') . '/',
          ],
        ];
      },
    ] as $collection => $callback) {
      $this->collection($collection, $callback)->build();
    }

    $this->sections()->build();
    // Make sure each multilingual page has its translations built.
    $this->multilingualPages('pages')->build();
    $this->multilingualPages('generated_pages')->build();
    // $this->articles()->build();
    // $this->projects()->build();
  }

  public function collection($id, $callback) {
    return new Collection($id, $callback);
  }

  public function multilingualPages($type) {
    return new MultilingualPages($type);
  }

  public function sections() {
    return new Sections();
  }

}
