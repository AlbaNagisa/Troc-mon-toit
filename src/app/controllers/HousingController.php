<?php

namespace Controllers;

use Controllers\Controller;
use Models\Booking;
use Models\City;
use Models\Equipments;
use Models\Housing;
use Models\HousingType;
use Models\Like;
use Models\Review;
use Models\Services;
use Twig\Environment;

class HousingController extends Controller
{
    private Housing $housing;
    private Booking $booking;
    private Review $review;
    public function __construct(protected Environment $twig, protected array $param = [])
    {
        parent::__construct($twig);
        $this->housing = new Housing();
        $this->booking = new Booking();
        $this->review = new Review();
    }
    public function index()
    {
        $housing = $this->housing->getById($this->param['id']);
        if ($housing === false) {
            header("Location: /404");
            return;
        }

        echo $this->twig->render("home/housing.html.twig", ['housing' => $housing, 'date' => date("Y-m-d"), 'bookings' => $this->booking->getByUserId($_SESSION['user']['id']), "userReview" => $this->review->getByUserIdAndHousingId($_SESSION['user']['id'], $this->param['id']), "reviews" => $this->review->getByHousing($this->param['id'])]);
    }

    public function booking()
    {
        $housing = $this->housing->getById($_POST['id']);
        $dateStart = $_POST['start'];
        $dateEnd = $_POST['end'];
        $dateStart = strtotime($dateStart);
        $dateEnd = strtotime($dateEnd);
        /*    */
        if ($dateStart > $dateEnd) {
            header("Location: /housing/" . $_POST['id']);
            return;
        }
        $booked = $this->booking->getByHousing($_POST['id']);
        foreach ($booked as $book) {
            $book['date_start'] = strtotime($book['date_start']);
            $book['date_end'] = strtotime($book['date_end']);
            if (($dateStart >= $book['date_start'] && $dateStart <= $book['date_end']) || ($dateEnd >= $book['date_start'] && $dateEnd <= $book['date_end'])) {
                header("Location: /housing/" . $_POST['id']);
                return;
            }
        }
        $total = (($dateEnd - $dateStart) / (60 * 60 * 24)) * $housing['night_price'];
        $dateStart = date("Y-m-d", $dateStart);
        $dateEnd = date("Y-m-d", $dateEnd);
        $this->booking->booking($dateStart, $dateEnd, $total, $_POST['id']);
        header("Location: /housing/" . $_POST['id']);
        return;
    }



    public function addLike()
    {
        $like = new Like();
        $like->add($_SESSION['user']['id'], $_POST['id']);
        header("Location: /");
        return;
    }

    public function deleteLike()
    {
        $like = new Like();
        $like->delete($_SESSION['user']['id'], $_POST['id']);
        header("Location: /");
        return;
    }
}
