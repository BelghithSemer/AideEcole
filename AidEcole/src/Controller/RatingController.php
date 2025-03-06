<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FeedbackRatingService;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/ratings')]
class RatingController extends AbstractController
{
    private $feedbackRatingService;

    public function __construct(FeedbackRatingService $feedbackRatingService)
    {
        $this->feedbackRatingService = $feedbackRatingService;
    }


    #[Route('/show', name: 'show_rating')]
    public function showRatings(): Response
    {
        $ratings = $this->feedbackRatingService->getRoleBasedRatings();

        $user = $this->getUser();
        return $this->render('rating/index.html.twig', [
            'ratings' => $ratings,
            'user'=>$user
        ]);
    }
}