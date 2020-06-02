<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $notification): void
    {
        $this->_em->persist($notification);
        $this->_em->flush();
    }

    /**
     * Get unread notifications for the current user.
     */
    public function getUnreadNotification(User $user)
    {
        $qb = $this->createQueryBuilder('n');
        $qb
            ->innerJoin('n.userNotifications', 'un')
            ->where('un.isRead = 0')
            ->andWhere('un.user = :user')
            ->setParameter(':user', $user)
            ->orderBy('n.createdAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }
}
