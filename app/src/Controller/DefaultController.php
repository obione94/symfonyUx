<?php

namespace App\Controller;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;




/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    const API_PARAMS = "Bearer erfifbheiurfbierb";

    public function __construct(private HttpClientInterface $client)
    {
    }


	/**
	 * @Route(name="home", path="/home")
	 */
    public function index(ChartBuilderInterface $chartBuilder): Response
    {

        $computedValue = self::getApiToTo()["computedValue"];
        $url = self::getApiToTo()["url"];
        $filter = self::getApiToTo()["filter"];

        $callback = function() use($url, $computedValue, $filter){
            // ... do some HTTP request or heavy computations
            $response = $this->client->request('GET',$url);
            // $contentType = 'application/json'
            
            $content = $response->getContent();
            // $content = '{"id":521583, "name":"symfony-docs", ...}'
            
            $content = $response->toArray();
            // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
            
            //if cache no expire dont dump that
            dump($content);

            //return data
            return $filter($computedValue);
        };
    
        $value = $this->getcache('my_cache_key',$callback);

        return $this->render('home.html.twig',
			[
				'chart' => ($chartBuilder->createChart(Chart::TYPE_LINE))->setData($value)
			]
    	);
    }



    public function getCache(string $name, callable $callback): array|string
    {

        $cache = new FilesystemAdapter();
        
        /*
        //delete cache for expire
        $cache->delete($name);
        */
        $value = $cache->get($name, function (ItemInterface $item) use ($callback) {
            $item->expiresAfter(3600);
            //if cache no expire dont dump that
            dump('coucou');
            return json_encode($callback());
        });


        return json_decode($value, true);
    }

    public static function getApiToTo(): array
    {
       return  [

            "computedValue" => [
                "totoro"=>
                [
                    'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    'datasets' => [
                        [
                            'label' => 'Sales!',
                            'backgroundColor' => 'rgb(255, 99, 132)',
                            'borderColor' => 'rgb(255, 99, 132)',
                            'data' => [522, 1500, 2250, 2197, 2345, 3122, 3099],
                        ],
                    ],
                ],
            ],

            "url" => 'https://api.github.com/repos/symfony/symfony-docs',

            "filter" => function($data) {
                return $data["totoro"];
            },
            //other params
            "Bearer" => self::API_PARAMS,
        ];
    }
}
