<?php

namespace Controllers;

use Models\Booking;
use Models\Housing;
use Models\Like;
use Models\Review;
use Twig\Environment;

class UserController extends Controller
{
    private Like $like;
    private Housing $housing;
    private Booking $booking;
    private Review $review;
    public function __construct(protected Environment $twig)
    {
        parent::__construct($twig);
        $this->like = new Like();
        $this->housing = new Housing();
        $this->booking = new Booking();
        $this->review = new Review();
    }
    public function index()
    {
        $likes = $this->like->getByUserId($_SESSION['user']['id']);
        for ($i = 0; $i < count($likes); $i++) {
            $likes[$i]['housing'] = $this->housing->getById($likes[$i]['id_housing']);
        }
        $bookings = $this->booking->getByUserId($_SESSION['user']['id']);
        for ($i = 0; $i < count($bookings); $i++) {
            $bookings[$i]['housing'] = $this->housing->getById($bookings[$i]['id_housing']);
        }

        $reviews = $this->review->getByUserId($_SESSION['user']['id']);
        for ($i = 0; $i < count($reviews); $i++) {
            $reviews[$i]['housing'] = $this->housing->getById($reviews[$i]['id_housing']);
        }
        echo $this->twig->render('user/index.html.twig', ["likes" => $likes, "bookings" => $bookings, "reviews" => $reviews]);
    }

    public function login()
    {
        echo $this->twig->render('user/login.html.twig');
    }
    public function loginPost()
    {
        $user = $this->user->getByUsername($_POST['username']);

        if (!$user) {
            return header("Location: /login");
        }

        if (password_verify($_POST['password'], $user['password'])) {
            $user['password'] = $_POST['password'];
            $_SESSION['user'] = $user;
            return header("Location: /");
        }

        return header("Location: /login");
    }

    public function register()
    {
        echo $this->twig->render('user/register.html.twig');
    }

    public function registerPost()
    {
        $userId = $this->user->create($_POST);

        if (!is_int(intval($userId))) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $referringPage = $_SERVER['HTTP_REFERER'];
                if (str_contains($referringPage, "admin")) {
                    return header("Location: /admin?userSuccess=false");
                }
            }
            echo $this->twig->render('user/register.html.twig', ["error" => $userId]);
            return;
        } else {
            $user = $this->user->getById(intval($userId));
            if (isset($_SERVER['HTTP_REFERER'])) {
                $referringPage = $_SERVER['HTTP_REFERER'];
                if (str_contains($referringPage, "admin") && $user) {
                    return header("Location: /admin?userSuccess=true");
                } else if (str_contains($referringPage, "admin") && !$user) {
                    return header("Location: /admin?userSuccess=false");
                }
            }
            if (!$user) {
                echo $this->twig->render('user/register.html.twig', ["error" => "Le nom d'utilisateur/email ou numero de telephone est deja utilise."]);
                return;
            }
            $user['password'] = $_POST['password'];
            $_SESSION['user'] = $user;
            return header("Location: /");
        }
    }
}
