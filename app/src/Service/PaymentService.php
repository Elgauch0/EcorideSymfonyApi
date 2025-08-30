<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class PaymentService
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Gère la logique de paiement d'une course pour un utilisateur et un commentaire donnés.
     * Cette méthode déduit les crédits du client, paie le chauffeur et l'administrateur.
     *
     * @param User $user Le client qui a laissé le commentaire.
     * @param Comment $comment Le commentaire qui déclenche le paiement.
     */
    public function hundlePayment(User $user, Comment $comment): void
    {
        $itinerary = $comment->getItinerary();
        $driver = $itinerary->getVehicule()->getDriver();
        $pricePerSeat = $itinerary->getPrice();

        // 1. Trouver l'administrateur
        $admin = $this->em->getRepository(User::class)->findOneBy(['roles' => ['["ROLE_ADMIN"]']]);
        if (!$admin) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Utilisateur administrateur introuvable.');
        }

        $reservations = $itinerary->getReservations();
        $totalPriceToPay = 0;
        $totalAdminFee = 0;

        // 2. Calculer le total à payer en traitant chaque réservation de l'utilisateur
        foreach ($reservations as $reservation) {
            // S'assurer que la réservation appartient au bon utilisateur et n'est pas annulée
            if ($reservation->getClientId() === $user && !$reservation->isCancelled()) {
                $priceToPayForReservation = $pricePerSeat * $reservation->getSeatsReserved();

                $totalPriceToPay += $priceToPayForReservation;
                $totalAdminFee += 2 * $reservation->getSeatsReserved();
            }
        }

        // 3. Lancer une exception si aucune réservation valide n'a été trouvée
        if ($totalPriceToPay === 0) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                "Aucune réservation valide n'a été trouvée pour cet utilisateur sur cet itinéraire."
            );
        }

        // 4. Calculer les montants finaux
        $paieToDriver = $totalPriceToPay - $totalAdminFee;
        $paieToClient = -$totalPriceToPay;
        $paieToAdmin = $totalAdminFee;

        // 5. Mettre à jour les crédits
        $user->setCredits($user->getCredits() + $paieToClient);
        $driver->setCredits($driver->getCredits() + $paieToDriver);
        $admin->setCredits($admin->getCredits() + $paieToAdmin);

        // Note: L'appel à $this->em->flush() doit être fait dans le contrôleur qui utilise ce service
        // pour s'assurer que toutes les modifications sont sauvegardées en une seule transaction.
    }
}
