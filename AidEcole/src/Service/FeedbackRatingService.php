<?php 
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Feedback;

class FeedbackRatingService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Calculate average ratings for each user role.
     */
    public function getRoleBasedRatings(): array
    {
        $repository = $this->entityManager->getRepository(Feedback::class);

        // Define role-specific fields
        $roleFields = [
            'centre_de_formation' => ['avis6', 'avis7'],
            'donateur' => ['avis4', 'avis5'],
            'enseignant' => ['avis10', 'avis11'],
            'parent' => ['avis1', 'avis2', 'avis3'],
            'responsable' => ['avis8', 'avis9'],
        ];

        $ratings = [];

        foreach ($roleFields as $role => $fields) {
            $totalNote = 0;
            $count = 0;

            // Fetch all feedback entries
            $feedbacks = $repository->findAll();

            foreach ($feedbacks as $feedback) {
                foreach ($fields as $field) {
                    // Check if the field is not null and calculate the note
                    if (!empty($feedback->{'get' . ucfirst($field)}())) {
                        $totalNote += $feedback->getNote();
                        $count++;
                    }
                }
            }

            // Calculate average rating for the role
            $ratings[$role] = $count > 0 ? round($totalNote / $count, 2) : 0;
        }

        return $ratings;
    }
}