<?php

namespace Controllers;

use Models\Housing;
use Models\Like;
use Models\Review;

class ReviewController extends Controller
{

    private Review $review;
    public function __construct(\Twig\Environment $twig)
    {
        parent::__construct($twig);
        $this->review = new Review();
    }
    public function review()
    {
        $this->review->add($_POST['dateStart'], $_POST['dateEnd'], $_POST['id'], $_POST['comment'], $_POST['stars']);
        header("Location: /housing/" . $_POST['id']);
        return;
    }
}
