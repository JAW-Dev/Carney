<?php
  $redirect_uri = getenv('APP_LOGIN_REDIRECT_URI') ?: 'carnage://login';
  $current_uri = $_SERVER['REQUEST_URI'];
  $parts = parse_url($current_uri);
  header("Location: $redirect_uri?{$parts['query']}");
