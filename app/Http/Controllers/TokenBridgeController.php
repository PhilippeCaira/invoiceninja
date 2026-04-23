<?php

namespace App\Http\Controllers;

class TokenBridgeController extends Controller
{
    /**
     * Fork OIDC : route-bridge qui reçoit un token CompanyToken en
     * querystring et l'injecte dans localStorage du frontend Flutter
     * avant de naviguer vers le dashboard. Utilisé uniquement dans le
     * flow SSO du fork pour contourner le fait que Flutter ignore la
     * session Laravel et lit uniquement localStorage.access_token.
     */
    public function show()
    {
        $token = request()->query('t', '');
        // Validation : token doit être 64 chars alphanumériques
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
  window.location.replace('/#/dashboard');
</script>
<noscript>Enable JavaScript to continue, or go to <a href="/#/dashboard">the dashboard</a>.</noscript>
</body>
</html>
HTML);
    }
}
