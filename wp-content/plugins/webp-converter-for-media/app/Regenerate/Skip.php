<?php

  namespace WebpConverter\Regenerate;

  use WebpConverter\Convert\Directory;
  use WebpConverter\Convert\Size;

  class Skip
  {
    public function __construct()
    {
      add_filter('webpc_files_paths', [$this, 'skipExistsImages'], 10, 2); 
    }

    /* ---
      Functions
    --- */

    public function skipExistsImages($paths, $skipExists = true)
    {
      if (!$skipExists) return $paths;

      $directory = new Directory();
      foreach ($paths as $key => $path) {
        $output = $directory->getPath(urldecode($path), false);
        if (file_exists($output) || file_exists($output . Size::DELETED_FILE_EXTENSION)) {
          unset($paths[$key]);
        }
      }
      return $paths;
    }
  }