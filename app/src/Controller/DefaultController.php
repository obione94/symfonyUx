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
use Symfony\Component\Finder\Finder;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    const API_PARAMS = "Bearer erfifbheiurfbierb";
    const PATH_CSV = "/var/www/app/public/build/csv";

    public function __construct(private HttpClientInterface $client)
    {
    }


	/**
	 * @Route(name="home", path="/home")
	 */
    public function index(ChartBuilderInterface $chartBuilder): Response
    {

        //get data from client
        $apiToTo = self::getApiToTo();
        $computedValue = $apiToTo["computedValue"];
        $url = $apiToTo["url"];
        $filter = $apiToTo["filter"];

        $callback = function() use($url, $computedValue, $filter){
            // ... do some HTTP request or heavy computations
            $response = $this->client->request('GET',$url);
            
            $content = $response->getContent();
            // $content = '{"id":521583, "name":"symfony-docs", ...}'
            
            $content = $response->toArray();
            // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
            
            //if cache no expire dont dump that
            dump($content);

            //return data
            return $filter($computedValue);
        };

        //get or set cache data
        $chartValue = $this->getcache('my_cache_key',$callback);


        
        /*
        *get et filtre csv
        */
        $filterCsv = self::filterCsv();    
        dump($this->csvToArray("cities.csv", $filterCsv["arrayCsvAsso"]));
        dump($this->csvToArray("cities.csv", $filterCsv["arrayCsvBrut"]));

        $computedValueFromCsv = $apiToTo["computedValueFromCsv"];
        $csv = $apiToTo["csv"];
        $filterCsvBrut= $filterCsv["arrayCsvBrut"];
        $callback = function() use($csv, $computedValueFromCsv, $filterCsvBrut, $filter){
            // ... get csv file
            $arrayCsv = $this->csvToArray($csv, $filterCsvBrut);
            dump($arrayCsv);

            //return data
            $computedValueFromCsvFilter = $filter($computedValueFromCsv);
            $computedValueFromCsvFilter["labels"] = explode(",",$arrayCsv[0]);
            $computedValueFromCsvFilter["datasets"][0]["data"] = explode(",",$arrayCsv[1]);
            return $computedValueFromCsvFilter;
        };


        dump($callback());
        dump($chartValue);


        return $this->render('home.html.twig',
			[
				'chart' => ($chartBuilder->createChart(Chart::TYPE_LINE))->setData($chartValue),
                'chartCsv' => ($chartBuilder->createChart(Chart::TYPE_LINE))->setData($callback())
			]
    	);
    }


    //get and set cache in filessteme
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

    //parameter for api
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
            "computedValueFromCsv" => [
                "totoro"=>
                [
                    'labels' => [],
                    'datasets' => [
                        [
                            'label' => 'Sales!',
                            'backgroundColor' => 'rgb(255, 99, 132)',
                            'borderColor' => 'rgb(255, 99, 132)',
                            'data' => [],
                        ],
                    ],
                ],
            ],

            "url" => 'https://api.github.com/repos/symfony/symfony-docs',
            "csv" => 'chart.csv',

            "filter" => function($data) {
                return $data["totoro"];
            },
            //other params
            "Bearer" => self::API_PARAMS,
        ];
    }

    //get file csv
    public function csvToArray(string $fileName, callable $filter): array
    {
        $finder = new Finder();
        $finder->files()
            ->in(self::PATH_CSV)
            ->name($fileName);

        foreach ($finder as $file) { $csv = $file; }

        return $filter($csv);
    }


    //filter de csv
    public static function filterCsv(): array
    {
        return  [
            'arrayCsvAsso' => function($csv) {
                $rows = [];
                if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
                    $i = 0;
                    while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                        $i++;
                        if (1 === $i) { 
                            $head = explode(',',current($data));
                            continue; 
                        }
                        $_rows = [];
                        foreach ($head as $key => $value) {
                            $_data = explode(',',current($data));
                            $_rows[$value] = $_data[$key];
                        }
                        $rows[] = $_rows;
                    }

                    fclose($handle);
                }

                return $rows;
            },
            "arrayCsvBrut" => function($csv) {
                $rows = [];
                if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                        $rows[] = current($data);
                    }
                    fclose($handle);
                }
                return $rows;
            },

        ];
    }
}
