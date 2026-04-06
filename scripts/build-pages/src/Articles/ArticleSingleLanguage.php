<?php

namespace myproject\Articles;

use myproject\SingleLanguageItem;

class ArticleSingleLanguage extends SingleLanguageItem {
  public static function slugify($text, string $divider = '-') {
    $ret = self::slugifyFull($text, $divider);
    $parts = explode($divider, $ret);
    $smaller = array_slice($parts, 0, 3);
    return implode($divider, $smaller);
  }
  // https://stackoverflow.com/a/2955878
  public static function slugifyFull($text, string $divider = '-') {
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
  }
  public function build() {
    if ($this->filename()) {
      $this->makeFile(
        location: '/app/docs/generated_articles/posts/',
        filename: $this->filename(),
        frontMatter: [
          'title' => $this->title(),
          'permalink' => $this->permalink(),
        ],
        content: $this->content(),
      );
      foreach ($this->redirects() as $redirect) {
        $this->makeFile(
          location: '/app/docs/generated_redirects/posts/',
          filename: $this->slugify($redirect),
          frontMatter: [
            'redirectTo' => $this->permalink(),
            'permalink' => $redirect,
          ],
        );
      }
    }
  }
  public function title() {
    return $this->item()->structure()[$this->map('title')];
  }
  public function content() {
    return $this->item()->structure()[$this->map('body')];
  }
  public function makeFile(
    string $location,
    string $filename,
    array $frontMatter,
    string $content = '',
  ) {
    if (str_contains($filename, '/')) {
      throw new \Exception('Invalid filename: ' . $filename);
    }
    if (!file_exists($location)) {
      mkdir($location, recursive: true);
    }
    file_put_contents(
      $location . '/' . $filename,
        yaml_emit($frontMatter)
        . '---' . PHP_EOL
        . PHP_EOL
        . $content
    );
  }
  public function permalink() {
    return $this->map('prefix') . '/' . $this->item()->date() . '/' . $this->slugify($this->title()) . '/';
  }
}
