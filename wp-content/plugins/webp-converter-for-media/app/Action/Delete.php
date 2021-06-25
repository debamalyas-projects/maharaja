<?php

  namespace WebpConverter\Action;

  use WebpConverter\Convert\Directory;
  use WebpConverter\Convert\Size;

  class Delete
  {
    public function __construct()
    {
      add_action('webpc_delete_paths', [$this, 'deleteFilesByPaths']);
    }

    /* ---
      Functions
    --- */

    public function deleteFilesByPaths($paths)
    {
      foreach ($paths as $path) {
        $this->deleteFileByPath($path);
      }
    }

    private function deleteFileByPath($path)
    {
      if (!($sourceWebP = Directory::getPath($path))) {
        return;
      }

      if (is_writable($sourceWebP)) {
        unlink($sourceWebP);
      } else if (is_writable($sourceWebP . SIZE::DELETED_FILE_EXTENSION)) {
        unlink($sourceWebP . SIZE::DELETED_FILE_EXTENSION);
      }
    }
  }