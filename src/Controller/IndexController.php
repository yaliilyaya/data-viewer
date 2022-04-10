<?php
namespace App\Controller;
use App\Srevice\DetectFieldType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 * @see Route
 */
class IndexController extends AbstractController
{

    /**
     * @var DetectFieldType
     */
    private $detectFieldType;

    public function __construct(DetectFieldType $detectFieldType)
    {
        $this->detectFieldType = $detectFieldType;
    }

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('index/list.html.twig', [
            'items' => []
        ]);
    }
    /**
     * @Route("/detail", name="detail")
     * @return Response
     */
    public function detail(): Response
    {
        $dataSource = $this->loadData('proposal_list.json');
        $data = $dataSource;
        $path = '';
        $properties = $this->detectFieldType->detect($data, $path, 2);
        $tables = array_filter($properties, function ($property) {
            return $property['type'] == DetectFieldType::TABLE_TYPE;
        });
        $propertyList = array_filter($properties, function ($property) {
            return $property['type'] == DetectFieldType::PROPERTY_LIST_TYPE;
        });
//        dump($properties);
//        dump($propertyList);
//        dump($tables);
//        die(__FILE__);
//        $properties = array_filter($properties, function ($property) {
//            return !in_array($property['type'], [DetectFieldType::TABLE_TYPE, DetectFieldType::PROPERTY_LIST_TYPE]);
//        });

//        dump(
//            $tables
//        );
////        dump($data);
//        die(__FILE__);
        return $this->render('index/detail.html.twig', [
            'data' => $data,
            'properties' => $properties,
            'path' => $path,
            'tables' => $tables,
            'table' => current($tables),
            'propertyList' => $propertyList,
        ]);
    }

    private function loadData(string $fileName)
    {
        $path = __DIR__ . '/' . $fileName;
        $content = file_get_contents($path);

        return json_decode($content, true, 512);
    }
}