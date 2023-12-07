<?

namespace Controllers;


use Models\User;
use Twig\Environment;
use Controllers\Controller;

class AdminController extends Controller
{
    public function __construct(protected Environment $twig)
    {
        parent::__construct($twig);
        if ($this->user->getById($_SESSION['user']['id'])['role'] != "Admin") {
            header("Location: /");
        }
    }

    public function showAdmin()
    {
        echo $this->twig->render('admin/index.html.twig', ['userSuccess' => $_GET['userSuccess'] ?? null]);
    }
}
