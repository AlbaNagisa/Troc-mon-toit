<?

namespace Controllers\Admin;
use Controllers\AdminController;
use Models\Services;
use Twig\Environment;

class ServicesController extends AdminController
{
    private Services $services;
    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->services = new Services();
    }
    public function index()
    {
        echo $this->twig->render("admin/services.html.twig", ["services" => $this->services->getAll()]);
    }

    public function create()
    {
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            $id = $this->services->create($name);
        }
        echo $this->twig->render("admin/services.html.twig", ["services" => $this->services->getAll()]);

    }

    public function delete()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->services->delete($id);
            header("Location: /admin/service");
        } else {
            header("Location: /admin/service");
        }
    }
    public function modify()
    {
        if (isset($_POST['id']) && isset($_POST['name'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $this->services->update($name, $id);
            header("Location: /admin/service");
        } else {
            header("Location: /admin/service");
        }
    }
}
