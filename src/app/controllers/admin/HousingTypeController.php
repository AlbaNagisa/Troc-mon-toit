<?

namespace Controllers\Admin;

use Models\HousingType;
use Twig\Environment;
use Controllers\AdminController;

class HousingTypeController extends AdminController {

    private HousingType $housingType;
    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->housingType = new HousingType();
    }
        public function typeIndex() {
            echo $this->twig->render("admin/housingType.html.twig", ["housingTypes" => $this->housingType->getAll()]);
        }

        public function create()
    {
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            $id = $this->housingType->create($name);
        }
        echo $this->twig->render("admin/housingType.html.twig", ["housingTypes" => $this->housingType->getAll()]);

    }

    public function delete()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->housingType->delete($id);
            header("Location: /admin/housingType");
        } else {
            header("Location: /admin/housingType");
        }
    }
    public function modify()
    {
        if (isset($_POST['id']) && isset($_POST['name'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $this->housingType->update($name, $id);
        }
            header("Location: /admin/housingType");
    }
}
