<?php

namespace myproject\Articles;

use myproject\MultilingualItem;

class Article extends MultilingualItem {
  public function build() {
    foreach ([
      new ArticleSingleLanguage($this, [
        'title' => 'title_en',
        'body' => 'body_en',
        'redirects' => 'redirects_en',
        'prefix' => '/news',
      ]),
      new ArticleSingleLanguage($this, [
        'title' => 'title_fr',
        'body' => 'body_fr',
        'redirects' => 'redirects_fr',
        'prefix' => '/fr/nouvelles',
      ]),
    ] as $articleInLanguage) {
      $articleInLanguage->build();
    }
  }
}
