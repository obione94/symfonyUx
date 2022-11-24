<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{
	/**
	 * @Route(name="home", path="/home")
	 */
    public function index(HttpClientInterface $client): Response
    {

		/*$response = $client->request('GET', 'https://digital.iservices.rte-france.com/open_api/ecowatt/v4/signals', [
			'headers' => [
				'Accept' => 'application/json',
				'Authorization' => 'Bearer ARCtVzKR593sjXELAtRU21D6YNWVbOXDSxR44vYkM2R2qjugtWto63',
			],
		]);

		dump($response->toArray());
		*/


        return $this->render('home.html.twig');
    }
}
