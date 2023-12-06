<?
namespace Controllers;

use Models\User;
use Twig\Environment;

class TestController extends Controller
{
    public function __construct(protected Environment $twig, protected array $param = []) {
        parent::__construct($twig);
    }
    public function index()
    {
        echo var_dump($this->param['id']);
    }
}
