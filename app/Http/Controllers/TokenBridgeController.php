<?php

namespace App\Http\Controllers;

class TokenBridgeController extends Controller
{
    /**
     * Fork OIDC : route-bridge qui reçoit un token CompanyToken en
     * querystring, dépose un X-NINJA-TOKEN dans le localStorage Flutter
     * et set un cookie `sso_in_progress` (1 minute) pour bloquer la
     * boucle infinie /login → /auth/oidc → /token-bridge → /login pendant
     * que le bundle Flutter consomme le token via _trySsoBootstrap.
     * Utilise `?local=1` dans le redirect pour skip flutterRoute SSO check.
     */
    public function show()
    {
        $token = request()->query('t', '');
        if (!preg_match('/^[a-zA-Z0-9]{64}$/', $token)) {
            return redirect('/login');
        }
        $safe = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');
        return response(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Signing you in…</title>
</head>
<body>
<script>
  try {
    localStorage.setItem('X-NINJA-TOKEN', '{$safe}');
    localStorage.setItem('access_token', '{$safe}');
  } catch (e) {}
  window.location.replace('/?local=1#/dashboard');
</script>
<noscript>Enable JavaScript to continue, or go to <a href="/?local=1#/dashboard">the dashboard</a>.</noscript>
</body>
</html>
HTML)->cookie('sso_in_progress', '1', 1, '/', null, true, true);
    }
}
