<?php


namespace App\Factory;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Itinerary;
use App\Model\CommentDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentFactory
{

    public function __construct(private EntityManagerInterface $em) {}






    public function createFromDto(CommentDTO $commentDTO, User $user): Comment
    {

        $itinerary = $this->em->getRepository(Itinerary::class)->find($commentDTO->id_itinerary);
        if (!$itinerary) {
            throw new NotFoundHttpException('Itinerary non trouvé.');
        }
        if ($itinerary->isCancelled()) {
            throw new BadRequestException("L'itinéraire est annulé.");
        }
        if (!$itinerary->isFinished()) {
            throw new BadRequestException("L'itinéraire n'est pas encore terminé.");
        }
        if (!$itinerary->isReservedBy($user)) {
            throw new BadRequestException("Vous n'avez pas réservé cet itinéraire.");
        }
        $existingComment = $this->em->getRepository(Comment::class)->findOneBy([
            'user' => $user,
            'itinerary' => $itinerary,
        ]);

        if ($existingComment) {
            throw new BadRequestException("Vous avez déjà laissé un avis sur cet itinéraire.");
        }


        $comment = new Comment();
        $comment->setItinerary($itinerary);
        $comment->setContent($commentDTO->content);
        $comment->setIsArrived($commentDTO->isArrived);
        $comment->setIsApproved($commentDTO->isApproved);
        $comment->setNote($commentDTO->note);









        return $comment;
    }
}
