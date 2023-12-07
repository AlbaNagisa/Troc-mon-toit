<?php

namespace Controllers\Admin;

use Controllers\AdminController;
use Models\Review;
use Twig\Environment;

class ReviewController extends AdminController
{
    private Review $review;
    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->review = new review();
    }
    public function index()
    {
        echo $this->twig->render("admin/review.html.twig", ["reviews" => $this->review->getAll()]);
    }

    public function add()
    {
        $this->review->add($_POST['dateStart'], $_POST['dateEnd'], $_POST['id_housing'], $_POST['comment'], $_POST['stars'], $_POST['userId']);
        header("Location: /housing/" . $_POST['id_housing']);
    }

    public function delete()
    {
        $this->review->delete($_POST["id"]);
        header("Location: /admin/review");
    }

    public function modify()
    {
        $this->review->modify($_POST['id'], $_POST['dateStart'], $_POST['dateEnd'], $_POST['comment'], $_POST['stars']);
        header("Location: /admin/review");
    }
}
