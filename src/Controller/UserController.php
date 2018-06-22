<?php

namespace App\Controller;

use App\Utility\Auth0Api;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GuzzleHTTP\Client as GuzzleClient;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 *
 * Class StudentController
 * @package App\Controller
 */
class UserController extends Controller
{
    /**
     * @Route("/list", name="user_list")
     */
    public function index()
    {
        $config = array (
            'base_uri' => 'https://elynuccia.eu.auth0.com/api/v2/',
            'headers' => array (
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6IlEwTXhSVE0wUlRZek4wUTROell5TnpnMVJFRXlOamhFUXpVeFFqazJRMFkyTlRGR01UZEJPUSJ9.eyJpc3MiOiJodHRwczovL2VseW51Y2NpYS5ldS5hdXRoMC5jb20vIiwic3ViIjoidGpGY1o2U1JJaFZNMk8xRXpmWjJ0ZDFGZ3FVck1pS0RAY2xpZW50cyIsImF1ZCI6Imh0dHBzOi8vZWx5bnVjY2lhLmV1LmF1dGgwLmNvbS9hcGkvdjIvIiwiaWF0IjoxNTI5NDkzNDgzLCJleHAiOjE1ODk5NzM0ODMsImF6cCI6InRqRmNaNlNSSWhWTTJPMUV6ZloydGQxRmdxVXJNaUtEIiwic2NvcGUiOiJyZWFkOmNsaWVudF9ncmFudHMgY3JlYXRlOmNsaWVudF9ncmFudHMgZGVsZXRlOmNsaWVudF9ncmFudHMgdXBkYXRlOmNsaWVudF9ncmFudHMgcmVhZDp1c2VycyB1cGRhdGU6dXNlcnMgZGVsZXRlOnVzZXJzIGNyZWF0ZTp1c2VycyByZWFkOnVzZXJzX2FwcF9tZXRhZGF0YSB1cGRhdGU6dXNlcnNfYXBwX21ldGFkYXRhIGRlbGV0ZTp1c2Vyc19hcHBfbWV0YWRhdGEgY3JlYXRlOnVzZXJzX2FwcF9tZXRhZGF0YSBjcmVhdGU6dXNlcl90aWNrZXRzIHJlYWQ6Y2xpZW50cyB1cGRhdGU6Y2xpZW50cyBkZWxldGU6Y2xpZW50cyBjcmVhdGU6Y2xpZW50cyByZWFkOmNsaWVudF9rZXlzIHVwZGF0ZTpjbGllbnRfa2V5cyBkZWxldGU6Y2xpZW50X2tleXMgY3JlYXRlOmNsaWVudF9rZXlzIHJlYWQ6Y29ubmVjdGlvbnMgdXBkYXRlOmNvbm5lY3Rpb25zIGRlbGV0ZTpjb25uZWN0aW9ucyBjcmVhdGU6Y29ubmVjdGlvbnMgcmVhZDpyZXNvdXJjZV9zZXJ2ZXJzIHVwZGF0ZTpyZXNvdXJjZV9zZXJ2ZXJzIGRlbGV0ZTpyZXNvdXJjZV9zZXJ2ZXJzIGNyZWF0ZTpyZXNvdXJjZV9zZXJ2ZXJzIHJlYWQ6ZGV2aWNlX2NyZWRlbnRpYWxzIHVwZGF0ZTpkZXZpY2VfY3JlZGVudGlhbHMgZGVsZXRlOmRldmljZV9jcmVkZW50aWFscyBjcmVhdGU6ZGV2aWNlX2NyZWRlbnRpYWxzIHJlYWQ6cnVsZXMgdXBkYXRlOnJ1bGVzIGRlbGV0ZTpydWxlcyBjcmVhdGU6cnVsZXMgcmVhZDpydWxlc19jb25maWdzIHVwZGF0ZTpydWxlc19jb25maWdzIGRlbGV0ZTpydWxlc19jb25maWdzIHJlYWQ6ZW1haWxfcHJvdmlkZXIgdXBkYXRlOmVtYWlsX3Byb3ZpZGVyIGRlbGV0ZTplbWFpbF9wcm92aWRlciBjcmVhdGU6ZW1haWxfcHJvdmlkZXIgYmxhY2tsaXN0OnRva2VucyByZWFkOnN0YXRzIHJlYWQ6dGVuYW50X3NldHRpbmdzIHVwZGF0ZTp0ZW5hbnRfc2V0dGluZ3MgcmVhZDpsb2dzIHJlYWQ6c2hpZWxkcyBjcmVhdGU6c2hpZWxkcyBkZWxldGU6c2hpZWxkcyB1cGRhdGU6dHJpZ2dlcnMgcmVhZDp0cmlnZ2VycyByZWFkOmdyYW50cyBkZWxldGU6Z3JhbnRzIHJlYWQ6Z3VhcmRpYW5fZmFjdG9ycyB1cGRhdGU6Z3VhcmRpYW5fZmFjdG9ycyByZWFkOmd1YXJkaWFuX2Vucm9sbG1lbnRzIGRlbGV0ZTpndWFyZGlhbl9lbnJvbGxtZW50cyBjcmVhdGU6Z3VhcmRpYW5fZW5yb2xsbWVudF90aWNrZXRzIHJlYWQ6dXNlcl9pZHBfdG9rZW5zIGNyZWF0ZTpwYXNzd29yZHNfY2hlY2tpbmdfam9iIGRlbGV0ZTpwYXNzd29yZHNfY2hlY2tpbmdfam9iIHJlYWQ6Y3VzdG9tX2RvbWFpbnMgZGVsZXRlOmN1c3RvbV9kb21haW5zIGNyZWF0ZTpjdXN0b21fZG9tYWlucyByZWFkOmVtYWlsX3RlbXBsYXRlcyBjcmVhdGU6ZW1haWxfdGVtcGxhdGVzIHVwZGF0ZTplbWFpbF90ZW1wbGF0ZXMiLCJndHkiOiJjbGllbnQtY3JlZGVudGlhbHMifQ.HLHkI-xuD-CMT6CHc3rVwg3A4FHozOcHPhzMqJzQT3sjTizZe0s2cRnl2cz9skAjunJ5eTy1dkCOIqSYhVMQvTaMccw6h28njMrFRNWzzxN3cUGgk1NfcfP0gF46lpiKrz1o-YRaMV3wkbIjye8DmRxmrjqj251uMXEMaaQ3jg5Cb3ByZnopcil2vZPHGUuvEbGLTf8g_ZYt5cMXNcp2u0eeRezT4Gjpwb1AI0tPnBlM913BeF0gNBpcqh08S5hO0LGm1k-PxqScmZAmi-eNHQQc4SnyXBVrfzJ4VvRn728jHO3LZ0JchJKm6zaPP9SauKakCJLCf6kd-mFdaOnrsg'
            )
        );

        $guzzle = new GuzzleClient($config);

        $response = $guzzle -> request('GET' , 'users', [ 'query' => ['q' => 'email:"elynuccia@gmail.com"']] );
        $response = $guzzle -> request('GET' , 'users');

        //var_dump($response);
        var_dump(json_decode($response->getBody()->getContents()), true);

        exit;


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

    /**
     * @Route("/search", name="user_search")
     */
    public function search(Request $request, Auth0Api $auth0Api)
    {
        $results = array('results');

        if(!$request->isXmlHttpRequest()) {
            $response = new Response('not allowed');
            $response->setStatusCode(403);

            return $response;
        }

        foreach($auth0Api->getUsers($request->get('q')) as $key => $user) {
            $results['results'][] = array(
                'id' => $user->user_id,
                'text' => $user->name,
                'imageSrc' => $user->picture
            );
        }

        return new Response(
            json_encode($results)
        );
    }

}
