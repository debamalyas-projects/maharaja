<?php

  namespace WebpConverter\Settings;

  use WebpConverter\Error\LibsError;
  use WebpConverter\Error\PassthruError;
  use WebpConverter\Error\PathsError;
  use WebpConverter\Error\RestapiError;
  use WebpConverter\Error\RewritesError;
  use WebpConverter\Error\SettingsError;

  class Errors
  {
    const ERRORS_CACHE_OPTION = 'webpc_errors_cache';

    private $cache         = [];
    private $filePath      = WEBPC_PATH . '/resources/components/errors/%s.php';
    private $allowedErrors = ['rewrites_cached'];

    public function __construct()
    {
      add_filter('webpc_server_errors',          [$this, 'getServerErrors'],         10, 3);
      add_filter('webpc_server_errors_messages', [$this, 'getServerErrorsMessages'], 10, 3);
    }

    /* ---
      Functions
    --- */

    public function getServerErrors($values, $onlyErrors = false, $isForceRefresh = false)
    {
      $errors = get_option(self::ERRORS_CACHE_OPTION, $this->cache);
      if ($isForceRefresh) {
        $errors = $this->getErrorsList();
      }
      $errors = array_filter($errors, function($error) use ($onlyErrors) {
        return (!$onlyErrors || !in_array($error, $this->allowedErrors));
      });

      return $errors;
    }

    public function getServerErrorsMessages($values, $onlyErrors = false, $isForceRefresh = false)
    {
      $errors = $this->getServerErrors($values, $onlyErrors, $isForceRefresh);
      return $this->loadErrorMessages($errors);
    }

    private function loadErrorMessages($errors)
    {
      $list = [];
      foreach ($errors as $error) {
        ob_start();
        include sprintf($this->filePath, str_replace('_', '-', $error));
        $list[$error] = ob_get_clean();
      }

      return $list;
    }

    private function getErrorsList()
    {
      $errors = [];
      if ($newErrors = (new LibsError())->getErrorCodes()) {
        $errors = array_merge($errors, $newErrors);
      }
      if ($newErrors = (new RestapiError())->getErrorCodes()) {
        $errors = array_merge($errors, $newErrors);
      }
      if ($newErrors = (new PathsError())->getErrorCodes()) {
        $errors = array_merge($errors, $newErrors);
      }
      if ($newErrors = (new PassthruError())->getErrorCodes()) {
        $errors = array_merge($errors, $newErrors);
      } else if ($newErrors = (new RewritesError())->getErrorCodes()) {
        $errors = array_merge($errors, $newErrors);
      }
      if (!$errors && ($newErrors = (new SettingsError())->getErrorCodes())) {
        $errors = array_merge($errors, $newErrors);
      }

      $this->cache = $errors;
      update_option(self::ERRORS_CACHE_OPTION, $errors);

      return $errors;
    }

    public static function setExtensionsForDebug($settings)
    {
      $settings['extensions'] = array_unique(array_merge(
        ['png2', 'png'],
        $settings['extensions']
      ));
      return $settings;
    }
  }