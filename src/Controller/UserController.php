<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GuzzleHTTP\Client as GuzzleClient;

class UserController extends Controller
{
    /**
     * @Route("", name="user")
     */
    public function index()
    {
        $config = array (
            'base_uri' => 'https://elynuccia.eu.auth0.com/api/v2/',
            'headers' => array (
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6IlEwTXhSVE0wUlRZek4wUTROell5TnpnMVJFRXlOamhFUXpVeFFqazJRMFkyTlRGR01UZEJPUSJ9.eyJpc3MiOiJodHRwczovL2VseW51Y2NpYS5ldS5hdXRoMC5jb20vIiwic3ViIjoidGpGY1o2U1JJaFZNMk8xRXpmWjJ0ZDFGZ3FVck1pS0RAY2xpZW50cyIsImF1ZCI6Imh0dHBzOi8vZWx5bnVjY2lhLmV1LmF1dGgwLmNvbS9hcGkvdjIvIiwiaWF0IjoxNTIyMzA5NDg2LCJleHAiOjE1MjIzOTU4ODYsImF6cCI6InRqRmNaNlNSSWhWTTJPMUV6ZloydGQxRmdxVXJNaUtEIiwic2NvcGUiOiJyZWFkOmNsaWVudF9ncmFudHMgY3JlYXRlOmNsaWVudF9ncmFudHMgZGVsZXRlOmNsaWVudF9ncmFudHMgdXBkYXRlOmNsaWVudF9ncmFudHMgcmVhZDp1c2VycyB1cGRhdGU6dXNlcnMgZGVsZXRlOnVzZXJzIGNyZWF0ZTp1c2VycyByZWFkOnVzZXJzX2FwcF9tZXRhZGF0YSB1cGRhdGU6dXNlcnNfYXBwX21ldGFkYXRhIGRlbGV0ZTp1c2Vyc19hcHBfbWV0YWRhdGEgY3JlYXRlOnVzZXJzX2FwcF9tZXRhZGF0YSBjcmVhdGU6dXNlcl90aWNrZXRzIHJlYWQ6Y2xpZW50cyB1cGRhdGU6Y2xpZW50cyBkZWxldGU6Y2xpZW50cyBjcmVhdGU6Y2xpZW50cyByZWFkOmNsaWVudF9rZXlzIHVwZGF0ZTpjbGllbnRfa2V5cyBkZWxldGU6Y2xpZW50X2tleXMgY3JlYXRlOmNsaWVudF9rZXlzIHJlYWQ6Y29ubmVjdGlvbnMgdXBkYXRlOmNvbm5lY3Rpb25zIGRlbGV0ZTpjb25uZWN0aW9ucyBjcmVhdGU6Y29ubmVjdGlvbnMgcmVhZDpyZXNvdXJjZV9zZXJ2ZXJzIHVwZGF0ZTpyZXNvdXJjZV9zZXJ2ZXJzIGRlbGV0ZTpyZXNvdXJjZV9zZXJ2ZXJzIGNyZWF0ZTpyZXNvdXJjZV9zZXJ2ZXJzIHJlYWQ6ZGV2aWNlX2NyZWRlbnRpYWxzIHVwZGF0ZTpkZXZpY2VfY3JlZGVudGlhbHMgZGVsZXRlOmRldmljZV9jcmVkZW50aWFscyBjcmVhdGU6ZGV2aWNlX2NyZWRlbnRpYWxzIHJlYWQ6cnVsZXMgdXBkYXRlOnJ1bGVzIGRlbGV0ZTpydWxlcyBjcmVhdGU6cnVsZXMgcmVhZDpydWxlc19jb25maWdzIHVwZGF0ZTpydWxlc19jb25maWdzIGRlbGV0ZTpydWxlc19jb25maWdzIHJlYWQ6ZW1haWxfcHJvdmlkZXIgdXBkYXRlOmVtYWlsX3Byb3ZpZGVyIGRlbGV0ZTplbWFpbF9wcm92aWRlciBjcmVhdGU6ZW1haWxfcHJvdmlkZXIgYmxhY2tsaXN0OnRva2VucyByZWFkOnN0YXRzIHJlYWQ6dGVuYW50X3NldHRpbmdzIHVwZGF0ZTp0ZW5hbnRfc2V0dGluZ3MgcmVhZDpsb2dzIHJlYWQ6c2hpZWxkcyBjcmVhdGU6c2hpZWxkcyBkZWxldGU6c2hpZWxkcyB1cGRhdGU6dHJpZ2dlcnMgcmVhZDp0cmlnZ2VycyByZWFkOmdyYW50cyBkZWxldGU6Z3JhbnRzIHJlYWQ6Z3VhcmRpYW5fZmFjdG9ycyB1cGRhdGU6Z3VhcmRpYW5fZmFjdG9ycyByZWFkOmd1YXJkaWFuX2Vucm9sbG1lbnRzIGRlbGV0ZTpndWFyZGlhbl9lbnJvbGxtZW50cyBjcmVhdGU6Z3VhcmRpYW5fZW5yb2xsbWVudF90aWNrZXRzIHJlYWQ6dXNlcl9pZHBfdG9rZW5zIGNyZWF0ZTpwYXNzd29yZHNfY2hlY2tpbmdfam9iIGRlbGV0ZTpwYXNzd29yZHNfY2hlY2tpbmdfam9iIHJlYWQ6Y3VzdG9tX2RvbWFpbnMgZGVsZXRlOmN1c3RvbV9kb21haW5zIGNyZWF0ZTpjdXN0b21fZG9tYWlucyByZWFkOmVtYWlsX3RlbXBsYXRlcyBjcmVhdGU6ZW1haWxfdGVtcGxhdGVzIHVwZGF0ZTplbWFpbF90ZW1wbGF0ZXMiLCJndHkiOiJjbGllbnQtY3JlZGVudGlhbHMifQ.AcFrRSbFFQ22J5oDWXej-kYpxCjjPSiRQQeEmqKerKPoDrzmaH2kIebVT2z5oDtFUGSrsNMAJM3vTdrhsyn2-5v92c8qAEbwyHCyP0BqczT9v5WF9xWHSlk3OQiuA6cF--FXhOT8GHl3VefRGCGE6c9O4s0v5w1CTApo-OqV8J9xKUgkQkGsiXhjssVA9QR7J8ffd7OACM3tkCsAbVcqgI5AzcIopuyPf3Hrxx55gOeloyS9uxAhd1zi8JGkCKs6rW_ViUSuiaypbdSUhWrdmtoDp9-jAJ9V2_ckGPLdu316r-pg7lQsfawvyak3p9pAA5yNlJHkA8Dl8HcfEJykpw'
            )
        );

        $guzzle = new GuzzleClient($config);

        // $response = $guzzle -> request('GET' , 'users', [ 'query' => ['q' => 'email:"elynuccia@gmail.com"']] );

        //var_dump($response);
        // var_dump(json_decode($response->getBody()-> getContents()), true);

        $user = ($this->getUser()) ? $this->getUser() : null;

        return $this->render('user/index.html.twig',
            array(
                'controller_name' => 'UserController',
                'user' => $user
            ),
            $this->redirect('/connect/auth0')
        );
        /*return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user
        ]);*/
    }
}
