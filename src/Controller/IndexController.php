<?php
namespace App\Controller;
use App\Srevice\DetectFieldType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->redirectToRoute('detail');
    }

    /**
     * @Route("/detail", name="detail")
     * @param Request $request
     * @return Response
     */
    public function detail(Request $request): Response
    {
        $path = $request->get('q') ?: '';
        $data = $this->loadData('proposal_list.json', $path);
        dump($data);

        $properties = $this->detectFieldType->detect($data, $path, 2);
        $tables = array_filter($properties, function ($property) {
            return $property['type'] == DetectFieldType::TABLE_TYPE;
        });
        $propertyList = array_filter($properties, function ($property) {
            return $property['type'] == DetectFieldType::PROPERTY_LIST_TYPE;
        });

        return $this->render('index/detail.html.twig', [
            'data' => $data,
            'properties' => $properties,
            'path' => $path,
            'tables' => $tables,
            'propertyList' => $propertyList,
        ]);
    }

    /**
     * @Route("/tooltip", name="tooltip")
     * @param Request $request
     * @return Response
     */
    public function tooltip(Request $request): Response
    {
        $path = $request->get('q') ?: '';
        $data = $this->loadData('proposal_list.json', $path);
        dump($data);

        $properties = $this->detectFieldType->detect($data, $path, 2);

        return $this->render('index/tooltip.html.twig', [
            'data' => $data,
            'properties' => $properties,
            'path' => $path
        ]);
    }

    /**
     * @param string $fileName
     * @param $dataPath
     * @return mixed|null
     */
    private function loadData(string $fileName, $dataPath)
    {
        $path = __DIR__ . '/' . $fileName;
        $content = file_get_contents($path);

        $dataPath = array_filter(explode('.', $dataPath), function ($node) {
            return $node !== '';
        });
        dump($dataPath);
        $data = json_decode($content, true, 512);

        foreach ($dataPath as $node) {
            $data = $data[$node] ?? $node[(int)$node] ?? null;
        }

        return $data;
    }
}