<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Exception;
use RuntimeException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function findAllOrdered()
    {
        

        return $this->createQueryBuilder('payment')
                        ->leftJoin('payment.employee','employee')
                        ->addSelect('employee')
                        ->addOrderBy('employee.lastName', 'asc')
                        ->addOrderBy('payment.month', 'desc')
                        ->getQuery()
                        ->execute();
    }

    public function getStatsForSkill(int $skillId): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('min', 'min');
        $rsm->addScalarResult('max', 'max');
        $rsm->addScalarResult('avg', 'avg');
        $sql = 'SELECT MAX(payment.amount) AS max, MIN(payment.amount) AS min, AVG(payment.amount) AS avg FROM payment WHERE primary_skill_id = :primary_skill_id';
        $nativeQuery = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $nativeQuery->setParameter('primary_skill_id', $skillId);

        try {
            $result = $nativeQuery->getSingleResult();
        } catch (Exception $exception) {
            throw new RuntimeException(sprintf(
                'Query calculating stats (min, max and avg) for payments of specified employees failed with errors: "%s"', $exception->getMessage()
            ), $exception->getCode(), $exception);
        }

        $result['min'] = (float) ($result['nin'] ?? 0);
        $result['max'] = (float) ($result['max'] ?? 0);
        $result['avg'] = (float) ($result['avg'] ?? 0);

        return $result;
    }

    public function getPaymentAmountsForSkill(int $skillId): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder
            ->select('p.amount')
            ->from(Payment::class, 'p')
            ->where($queryBuilder->expr()->eq('IDENTITY(p.primarySkill)', $skillId))
        ;

        return $queryBuilder->getQuery()->getResult('COLUMN_HYDRATOR_FLOAT');
    }
}
