<?php

namespace App\Repository;

use App\Entity\Images;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function create(Object $dto, Object $user, array $images = [])
    {

        $post = new Post();
        $post->setTitle($dto->title);
        $post->setContent($dto->content);
        $post->setAuthor($user);

        if (count($images)) {

            foreach($images as $image) {

                $imgInstance = new Images();
                $imgInstance->setFileName($image->file_name);
                $imgInstance->setFilePath($image->file_path);
                $post->addImage($imgInstance);
                $this->_em->persist($imgInstance);

            }

        }


        $this->_em->persist($post);
        $this->_em->flush();

        return $post;
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
