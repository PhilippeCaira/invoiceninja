<!DOCTYPE html>
<html data-report-errors="{{ $report_errors }}" data-rc="{{ $rc }}" data-user-agent="{{ $user_agent }}" data-login="{{ $login }}">
<head>
    <!-- Source: https://github.com/invoiceninja/invoiceninja -->
    <!-- Version: {{ config('ninja.app_version') }} -->
  <meta charset="UTF-8">
  <title>{{ config('ninja.app_name') }}</title>
  <meta name="google-signin-client_id" content="{{ config('services.google.client_id') }}">
  @if(filter_var(env('OIDC_AUTO_REDIRECT', false), FILTER_VALIDATE_BOOLEAN))
  <script>
    // Fork OIDC : le shell React d'Invoice Ninja peut naviguer en client
    // vers /#/login (hash) sans requête HTTP que Traefik voit. On
    // intercepte au niveau du document et on redirige vers /auth/oidc.
    (function() {
      if (location.search.indexOf('local=1') !== -1) return;
      var redirectToSso = function() {
        if (location.hash === '#/login' || location.pathname === '/login' ||
            location.hash.indexOf('#/login') === 0 || location.pathname.indexOf('/login') === 0) {
          window.location.replace('/auth/oidc');
        }
      };
      window.addEventListener('hashchange', redirectToSso);
      window.addEventListener('popstate', redirectToSso);
      redirectToSso();
    })();
  </script>
  @endif

  @include('react.head')

</head>

<body class="h-full">
  <noscript>You need to enable JavaScript to run this app.</noscript>
  <div id="root"></div>
  
</body>

<!--

If you are reading this, there is a fair change that the react application has not loaded for you. There are a couple of solutions:

1. Download the release file from https://github.com/invoiceninja/invoiceninja and overwrite your current installation.
2. Switch back to the Flutter application by editing the database, you can do this with the following SQL

UPDATE accounts SET
set_react_as_default_ap = 0;

-->
</html>
